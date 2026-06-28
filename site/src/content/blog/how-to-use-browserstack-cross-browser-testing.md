---
title: "Testing for cross-browser compatibility using BrowserStack"
description: "Here's the code I used to integrate my local Selenium tests with BrowserStack's Automate. Their documentation and customer support helped me achieve it."
pubDate: "2016-09-12T08:00:00"
heroImage: "../../assets/blog/uploads/2016/08/6-Useful-Cross-Browser-Testing-Tools.png"
tags: ["coding", "java", "selenium", "testing"]
categories: ["Technology"]
---

## Learn how to do it with this example

Recalling my [BrowserStack review](/blog/browserstack-cross-browser-testing-review), their main tools are:

- **Automate**: Where you run your automated Selenium tests and check the results.
- **Screenshots**: Paste an URL, select the browsers and version you want, and in a few minutes you get a batch of screenshots.
- **Live**: by connecting to their data center you are able to do exploratory testing of your web app on the environment you need without having to worry about virtual machines.

Note that **BrowserStack Automate tells you if a test has completed, not that it passed**. For me [that's just weird](/blog/browserstack-cross-browser-testing-review). So when a test fails locally it doesn't (necessarily) fail remotely. I explicitly created an assert that would always fails like `assertThat(true, is(false))` and it would fail locally and ~pass~ complete on BrowserStack. I had to use that [API workaround to mark tests as failed](http://stackoverflow.com/a/35102092/675577) you will se below.

Here is the code I used to integrate my local Selenium tests with BrowserStack's Automate service. Their [documentation](https://www.browserstack.com/automate/java#getting-started) and customer support helped me achieve it.

```
public class EndToEndBrowserStackTests {

    private static WebDriver driver;
    private static String targetDriver;
    private static String targetRootUrl;
    private static URL browserStackAPI;

    // When a test fails, you need to explicitly mark it as failed on BrowserStack... #fail
    @Rule
    public TestRule testWatcher = new TestWatcher() {
        @Override
        public void failed(Throwable t, Description test) {
            // Take screenshot
            try {
                File screenshotFile = ((TakesScreenshot) driver).getScreenshotAs(OutputType.FILE);
                FileUtils.copyFile(screenshotFile, new File(SCREENSHOTS_DIR + test.getMethodName() + ".png"));
            } catch (Exception e) {
                e.printStackTrace();
            }

            if (driver instanceof RemoteWebDriver) {
                failRemoteTest("Failed test at " + test.getMethodName() + " because '" + t.getMessage().replace('\n', ' ') + "'");
            }
        }
    };

    @BeforeClass
    public static void init() throws Exception {
        // Use system env variables for CI environments or constants if your testing manually on your IDE
        String browserStackUser = System.getenv("BROWSERSTACK_USER");
        String browserStackKey = System.getenv("BROWSERSTACK_ACCESSKEY");
        browserStackAPI = new URL("https://" + browserStackUser + ":" + browserStackKey + "@hub-cloud.browserstack.com/wd/hub");

        targetRootUrl = "http://your-url-under-test.com/"; // either local or remote
        targetDriver = System.getenv("BROWSER_NAME");
    }

    @Test
    public void checkForNavigationLinks() {
        try {
            driver = getDriver();
            driver.navigate().to(targetRootUrl);
            WebDriverWait driverWait = new WebDriverWait(driver, 5);  // seconds
            driverWait.until(ExpectedConditions.presenceOfElementLocated(By.cssSelector("div.navigation")));

            NavigationHeader navHeader = new NavigationHeader(driver);
            assertThat("Header links should be visible", navHeader.isDashboardLinkVisible(), is(true));

            NavigationMenu navMenu = new NavigationMenu(driver);
            assertThat("Menu links should be visible", navMenu.isLoginLinkVisible(), is(true));
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    @After
    public void tearDown() {
        if (driver != null) {
            driver.quit();
        }
    }

    private static WebDriver getDriver() throws Exception {
        DesiredCapabilities caps = new DesiredCapabilities();
        caps.setCapability("os", "Windows");
        caps.setCapability("os_version", "10");
        caps.setCapability("resolution", "1024x768");
        caps.setCapability("browserstack.debug", "true");
        caps.setCapability("browserstack.video", "false");
        caps.setCapability("browserstack.local", System.getenv("BROWSERSTACK_LOCAL");
        caps.setCapability("browserstack.localIdentifier", System.getenv("BROWSERSTACK_LOCAL_IDENTIFIER");
        caps.setCapability("project", "Your Project's Name");   //TODO: the name of your project
        caps.setCapability("build", "");                        //TODO: leave empty if you don't want the results to be grouped by build

        switch (targetDriver.toLowerCase()) {
            case "chrome":
                caps.setCapability("browser", "Chrome");
                caps.setCapability("browser_version", "50.0");
                break;
            case "ie": case "internetexplorer":
                caps.setCapability("browser", "IE");
                caps.setCapability("browser_version", "11.0");
            case "ff": case "firefox":
                caps.setCapability("browser", "Firefox");
                caps.setCapability("browser_version", "46.0");
            case "": case "phantom":
            default:
                return new PhantomJSDriver();
        }
        return new RemoteWebDriver(browserStackAPI, caps);
    }

    private void failRemoteTest(String errorReason) {
        try {
            String remoteSessionId = ((RemoteWebDriver) driver).getSessionId().toString();
            String encodedAuthString = ""; // TODO: Find out which one is yours, it should be a base64 string
            URL url = new URL("https://www.browserstack.com/automate/sessions/" + remoteSessionId + ".json");
            HttpURLConnection connection = (HttpURLConnection) url.openConnection();

            connection.setDoInput(true);
            connection.setDoOutput(true);
            connection.setRequestMethod("PUT");
            connection.setRequestProperty("Accept", "application/json");
            connection.setRequestProperty("Content-Type", "application/json; charset=UTF-8");
            connection.setRequestProperty("Authorization", "Basic " + encodedAuthString);

            String payload = "{\"status\":\"error\", \"reason\":\"" + errorReason + "\"}";
            OutputStreamWriter writer = new OutputStreamWriter(connection.getOutputStream(), "UTF-8");
            writer.write(payload);
            writer.close();

            if (connection.getResponseCode() != 200)
                throw new Exception("Failed to change remote test state.");
            else
                connection.disconnect();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}
```
