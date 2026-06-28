---
title: "Calling a Web Method in C# without Service Reference"
description: "If you need to call a web method but fail to have a WSDL or want your code to be dynamic, then SOAP and `HttpWebRequest`s are the way to go."
pubDate: "2014-11-24T08:00:32"
heroImage: "/blog/uploads/2014/11/This-is-a-SOAP-request.png"
tags: ["c#", "coding", "tutorial", "web dev"]
categories: ["Technology"]
---

Last week I gave you a method that, [using SOAP and HttpWebRequest, allowed you to invoke a Web Method without a WSDL or a Web Reference](/blog/calling-webservice-without-wsdl-or-web-reference/). Well today, I'll give you an improved version of that method. In fact I'll give you a whole ready-to-use class with additional functionality.

Recapping, if you need to call a web method but fail to have a WSDL or can't use a Web or Service Reference because you want your code to be dynamic, then SOAP and `HttpWebRequest`s are the way to go. You can then create a Proxy having the same API (methods, inputs, outputs) as the WebService you're trying to call. For instance:

```
internal class ExampleAPIProxy
{
    private static WebService ExampleAPI = new WebService("http://.../example.asmx");    // DEFAULT location of the WebService, containing the WebMethods

    public static void ChangeUrl(string webserviceEndpoint)
    {
        ExampleAPI = new WebService(webserviceEndpoint);
    }

    public static string ExampleWebMethod(string name, int number)
    {
        ExampleAPI.PreInvoke();

        ExampleAPI.AddParameter("name", name);                    // Case Sensitive! To avoid typos, just copy the WebMethod's signature and paste it
        ExampleAPI.AddParameter("number", number.ToString());     // all parameters are passed as strings
        try
        {
            ExampleAPI.Invoke("ExampleWebMethod");                // name of the WebMethod to call (Case Sentitive again!)
        }
        finally { ExampleAPI.PosInvoke(); }

        return ExampleAPI.ResultString;                           // you can either return a string or an XML, your choice
    }
}
```

For that to work you'll need the WebService class.

```
/// <summary>
/// This class is an alternative when you can't use Service References. It allows you to invoke Web Methods on a given Web Service URL.
/// Based on the code from http://stackoverflow.com/questions/9482773/web-service-without-adding-a-reference
/// </summary>
public class WebService
{
    public string Url { get; private set; }
    public string Method { get; private set; }
    public Dictionary<string, string> Params = new Dictionary<string, string>();
    public XDocument ResponseSOAP = XDocument.Parse("<root/>");
    public XDocument ResultXML = XDocument.Parse("<root/>");
    public string ResultString = String.Empty;

    private Cursor InitialCursorState;

    public WebService()
    {
        Url = String.Empty;
        Method = String.Empty;
    }
    public WebService(string baseUrl)
    {
        Url = baseUrl;
        Method = String.Empty;
    }
    public WebService(string baseUrl, string methodName)
    {
        Url = baseUrl;
        Method = methodName;
    }

    // Public API

    /// <summary>
    /// Adds a parameter to the WebMethod invocation.
    /// </summary>
    /// <param name="name">Name of the WebMethod parameter (case sensitive)</param>
    /// <param name="value">Value to pass to the paramenter</param>
    public void AddParameter(string name, string value)
    {
        Params.Add(name, value);
    }

    public void Invoke()
    {
        Invoke(Method, true);
    }

    /// <summary>
    /// Using the base url, invokes the WebMethod with the given name
    /// </summary>
    /// <param name="methodName">Web Method name</param>
    public void Invoke(string methodName)
    {
        Invoke(methodName, true);
    }

    /// <summary>
    /// Cleans all internal data used in the last invocation, except the WebService's URL.
    /// This avoids creating a new WebService object when the URL you want to use is the same.
    /// </summary>
    public void CleanLastInvoke()
    {
        ResponseSOAP = ResultXML = null;
        ResultString = Method = String.Empty;
        Params = new Dictionary<string, string>();
    }

    #region Helper Methods

    /// <summary>
    /// Checks if the WebService's URL and the WebMethod's name are valid. If not, throws ArgumentNullException.
    /// </summary>
    /// <param name="methodName">Web Method name (optional)</param>
    private void AssertCanInvoke(string methodName = "")
    {
        if (Url == String.Empty)
            throw new ArgumentNullException("You tried to invoke a webservice without specifying the WebService's URL.");
        if ((methodName == "") && (Method == String.Empty))
            throw new ArgumentNullException("You tried to invoke a webservice without specifying the WebMethod.");
    }

    private void ExtractResult(string methodName)
    {
        // Selects just the elements with namespace http://tempuri.org/ (i.e. ignores SOAP namespace)
        XmlNamespaceManager namespMan = new XmlNamespaceManager(new NameTable());
        namespMan.AddNamespace("foo", "http://tempuri.org/");

        XElement webMethodResult = ResponseSOAP.XPathSelectElement("//foo:" + methodName + "Result", namespMan);
        // If the result is an XML, return it and convert it to string
        if (webMethodResult.FirstNode.NodeType == XmlNodeType.Element)
        {
            ResultXML = XDocument.Parse(webMethodResult.FirstNode.ToString());
            ResultXML = Utils.RemoveNamespaces(ResultXML);
            ResultString = ResultXML.ToString();
        }
        // If the result is a string, return it and convert it to XML (creating a root node to wrap the result)
        else
        {
            ResultString = webMethodResult.FirstNode.ToString();
            ResultXML = XDocument.Parse("<root>" + ResultString + "</root>");
        }
    }

    /// <summary>
    /// Invokes a Web Method, with its parameters encoded or not.
    /// </summary>
    /// <param name="methodName">Name of the web method you want to call (case sensitive)</param>
    /// <param name="encode">Do you want to encode your parameters? (default: true)</param>
    private void Invoke(string methodName, bool encode)
    {
        AssertCanInvoke(methodName);
        string soapStr =
            @"<?xml version=""1.0"" encoding=""utf-8""?>
                <soap:Envelope xmlns:xsi=""http://www.w3.org/2001/XMLSchema-instance""
                   xmlns:xsd=""http://www.w3.org/2001/XMLSchema""
                   xmlns:soap=""http://schemas.xmlsoap.org/soap/envelope/"">
                  <soap:Body>
                    <{0} xmlns=""http://tempuri.org/"">
                      {1}
                    </{0}>
                  </soap:Body>
                </soap:Envelope>";

        HttpWebRequest req = (HttpWebRequest)WebRequest.Create(Url);
        req.Headers.Add("SOAPAction", "\"http://tempuri.org/" + methodName + "\"");
        req.ContentType = "text/xml;charset=\"utf-8\"";
        req.Accept = "text/xml";
        req.Method = "POST";

        using (Stream stm = req.GetRequestStream())
        {
            string postValues = "";
            foreach (var param in Params)
            {
                if (encode) postValues += string.Format("<{0}>{1}</{0}>", HttpUtility.HtmlEncode(param.Key), HttpUtility.HtmlEncode(param.Value));
                else postValues += string.Format("<{0}>{1}</{0}>", param.Key, param.Value);
            }

            soapStr = string.Format(soapStr, methodName, postValues);
            using (StreamWriter stmw = new StreamWriter(stm))
            {
                stmw.Write(soapStr);
            }
        }

        using (StreamReader responseReader = new StreamReader(req.GetResponse().GetResponseStream()))
        {
            string result = responseReader.ReadToEnd();
            ResponseSOAP = XDocument.Parse(Utils.UnescapeString(result));
            ExtractResult(methodName);
        }
    }

    /// <summary>
    /// This method should be called before each Invoke().
    /// </summary>
    internal void PreInvoke()
    {
        CleanLastInvoke();
        InitialCursorState = Cursor.Current;
        Cursor.Current = Cursor.WaitCursor;
        // feel free to add more instructions to this method
    }

    /// <summary>
    /// This method should be called after each (successful or unsuccessful) Invoke().
    /// </summary>
    internal void PosInvoke()
    {
        Cursor.Current = InitialCursorState;
        // feel free to add more instructions to this method
    }

    #endregion
}
```

And lastly the Utils class:

```
public static class Utils
{
    /// <summary>
    /// Remove all xmlns:* instances from the passed XmlDocument to simplify our xpath expressions
    /// </summary>
    public static XDocument RemoveNamespaces(XDocument oldXml)
    {
        // FROM: http://social.msdn.microsoft.com/Forums/en-US/bed57335-827a-4731-b6da-a7636ac29f21/xdocument-remove-namespace?forum=linqprojectgeneral
        try
        {
            XDocument newXml = XDocument.Parse(Regex.Replace(
                oldXml.ToString(),
                @"(xmlns:?[^=]*=[""][^""]*[""])",
                "",
                RegexOptions.IgnoreCase | RegexOptions.Multiline)
            );
            return newXml;
        }
        catch (XmlException error)
        {
            throw new XmlException(error.Message + " at Utils.RemoveNamespaces");
        } 
    }

    /// <summary>
    /// Remove all xmlns:* instances from the passed XmlDocument to simplify our xpath expressions
    /// </summary>
    public static XDocument RemoveNamespaces(string oldXml)
    {
        XDocument newXml = XDocument.Parse(oldXml);
        return RemoveNamespaces(newXml);
    }

    /// <summary>
    /// Converts a string that has been HTML-enconded for HTTP transmission into a decoded string.
    /// </summary>
    /// <param name="escapedString">String to decode.</param>
    /// <returns>Decoded (unescaped) string.</returns>
    public static string UnescapeString(string escapedString)
    {
        return HttpUtility.HtmlDecode(escapedString);
    }
}
```
