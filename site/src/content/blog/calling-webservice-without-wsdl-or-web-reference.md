---
title: "Calling Web Service without WSDL or Web Reference"
description: "What's the right way, on Visual Studio, to call an external web service, without WSDL, ASMX or adding a web reference? Solution: make a C# SOAP request!"
pubDate: "2014-11-17T08:00:24"
heroImage: "/blog/uploads/2014/11/77043.jpg"
tags: ["c#", "coding", "tutorial", "web dev"]
categories: ["Technology"]
---

[](http://www.diogonunes.com/blog/wp-content/uploads/2014/11/77043.jpg)

Once I had to test in C# a dozen of web services developed by a third-party. However they provided no WSDL and no ASMX - therefore it would be impossible to use Web References. They only provided the name of the web methods, their urls, calling credentials and an XML request example.

- **WSDL \[FAIL\]:** I tried to [obtain an wsdl by executing Visual Studio's `wsdl.exe`](http://stackoverflow.com/questions/1394930/how-to-generate-web-service-out-of-wsdl) but I got an error saying something like `"The HTML document does not contain identifying information about the Web service."`
- **Service Reference \[FAIL\]:** On Visual Studio, I tried adding a Service Reference, providing the web service's url. I got `an error downloading '<url>/_vti_bin/ListData.svc/$metadata'. Metadata contains a reference that cannot be resolved.` Maybe the web service was an ASMX instead of a WCF?
- **SOAP \[almost WIN\]:** My last hope on cleaning this mess was [SOAP](http://stackoverflow.com/questions/1277212/how-to-call-a-web-service-with-no-wsdl-in-net) (pun intended). Indeed, I tried creating a class based on the [code available here](http://mikehadlow.blogspot.pt/2006/05/making-raw-web-service-calls-with.html). Sadly, when Visual Studio executed that class on IE11 I got a rather random `HTTP Error 403.14 - Forbidden, the Web server is configured to not list the contents of this directory`. However I was on the right track.

After all those attempts... **what is the right way, on Visual Studio, to call an external web service, without WSDL, ASMX or adding a web reference?**

### Solution - SOAP Messages

Turns out SOAP was really the way to go. The **execution flow** I used was: create a Web/SOAP request, set request method to POST (for some reason the 3rd party developed the webservices that way), convert the XML containing the WebService input into a byte array (payload), write the XML payload into the web request, use the web request to get a response from the WebService, return an XML with the WebService response (or an error XML).

I had to call WebServices with an URL pattern of:

`http://www.client.com/_ws/<METHOD_NAME>?sso=<AUTHENTICATION>&o=<GET/SET/DEL>`.

For example: `http://www.client.com/_ws/products?sso=PASSword123&o=GET`

Here is the C# code to call a WebService, through its URL, using SOAP alone:

```
/// <returns>ResultCode, 1 if success.</returns>
private XmlDocument CallWebService(string method, string operation, string xmlPayload)
{
    string result = "";
    string CREDENTIALS = "PASSword123";
    string URL_ADDRESS = "http://www.client.com/_ws/" + method + "?sso=" + CREDENTIALS + "&o=" + operation +;  //TODO: customize to your needs
    // ===== You shoudn't need to edit the lines below =====

    // Create the web request
    HttpWebRequest request = WebRequest.Create(new Uri(URL_ADDRESS)) as HttpWebRequest;

    // Set type to POST
    request.Method = "POST";
    request.ContentType = "application/xml";

    // Create the data we want to send
    StringBuilder data = new StringBuilder();
    data.Append(xmlPayload);
    byte[] byteData = Encoding.UTF8.GetBytes(data.ToString());      // Create a byte array of the data we want to send
    request.ContentLength = byteData.Length;                        // Set the content length in the request headers

    // Write data to request
    using (Stream postStream = request.GetRequestStream())
    {
        postStream.Write(byteData, 0, byteData.Length);
    }

    // Get response and return it
    XmlDocument xmlResult = new XmlDocument();
    try
    {
        using (HttpWebResponse response = request.GetResponse() as HttpWebResponse)
        {
            StreamReader reader = new StreamReader(response.GetResponseStream());
            result = reader.ReadToEnd();
            reader.Close();
        }
        xmlResult.LoadXml(result);
    }
    catch (Exception e)
    {
        xmlResult = CreateErrorXML(e.Message, "");  //TODO: returns an XML with the error message
    }
    return xmlResult;
}
```

There's an even [better way here](http://www.diogonunes.com/blog/calling-a-web-method-in-c-without-a-service-reference/).
