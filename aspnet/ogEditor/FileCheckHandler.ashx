<%@ WebHandler Language="C#" Class="FileCheckHandler" %>

using System;
using System.Web;
using System.IO;

public class FileCheckHandler : IHttpHandler {
    static public string result = "false";
    public void ProcessRequest (HttpContext context) {
	if (context.Request["path"] != "")
        {
            string path = context.Request["path"];

            path = context.Server.MapPath(context.Request.ApplicationPath) + "\\" + path.Replace("_", "\\").Replace("+",".");
        

            if(path != "" && File.Exists(path)){
                result = "true";
            }
        }
        context.Response.ContentType = "text/plain";
        context.Response.Write(result);
    }

    public bool IsReusable {
	get {
	    return false;
	}
    }
}