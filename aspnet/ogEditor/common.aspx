<%@ Page Language="C#" EnableViewState="false" %>
<%@ Import Namespace="System" %>
<%@ Import Namespace="System.IO" %>
<%@ Import Namespace="System.Text" %>
<%@ Import Namespace="System.Net" %>
<%@ Import Namespace="System.Collections" %>
<%@ Import Namespace="System.Web" %>
<%@ Import Namespace="System.Collections.Specialized" %>
<%@ Import Namespace="System.Security.Cryptography" %>
<%@ Import Namespace="System.Text.RegularExpressions" %>
<%@ Import Namespace="System.Xml" %>

<script runat="server">
    static protected string PROFILE_HOME = "";
    static protected string PROFILE_ROOT = System.Configuration.ConfigurationSettings.AppSettings["PROFILE_ROOT"];
    static protected string userRoot;
    static protected string TEMPLATE_HOME = "template";
    static protected string DEFAULT_TNAME = "default";
    static protected string ACCEPT_FILE_EXTENSION = System.Configuration.ConfigurationSettings.AppSettings["ACCEPT_FILE_EXTENSION"];
    static protected string serverPath = "";
    static protected int level = 0;
    static protected int depth = 0;
    static protected int rnum = 1;
    static protected string rootName = "";
    static protected string rootDirName = "/";
    static public string output = "";
    static public string val = "";
    static protected string trigType = "";
    static protected string fName = "";
    static public string fResult = "";
    static public string isFileExist = "";
            
    private void Page_Load(object sender, EventArgs e)
	{
	    val = "";
	    trigType = "";
	    fResult = "";
	    //filePath = "";
	    isFileExist = "";
	    userRoot = "";
	    if(Request.QueryString["user"] != ""){
    		userRoot = Request.QueryString["user"];
    	}else{ 
    		return; 
    	}
    	PROFILE_HOME = PROFILE_ROOT + "\\" + userRoot;
        serverPath = Server.MapPath(Request.ApplicationPath);
        
        if(Request.Params["hideTrigType"] != null){
            trigType = Request.Params["hideTrigType"];
            if(trigType == "loadFile_to_openFile"){
	            fName = Request.Params["hideFName"];
	            
	            ReadFile(serverPath + "\\" + PROFILE_HOME + "\\" + fName);
	        }else if(trigType == "isFileExist"){
	        	string fileName = Request.Params["hideFName"];
	            string fPath = Request.Params["hidePath"];
	            string fullPath = "";
	            if(fPath == "/"){
		            fullPath = serverPath + "\\" + PROFILE_HOME + "\\" + fileName;
		        }else{
		            fullPath = serverPath + "\\" + PROFILE_HOME + fPath + "\\" + fileName;
		        }
		        if (File.Exists(fullPath)) {
		        	isFileExist = "true";
		        }else{
		        	isFileExist = "false";
		        }
            }else if(trigType == "saveAs_to_refreshAndOpenDir"){
	            fName = Request.Params["hideFName"];
	            string extension = System.IO.Path.GetExtension(fName);
	      		if(extension != "" && ACCEPT_FILE_EXTENSION.IndexOf(","+extension.ToLower()+",") >= 0){
		            string fPath = Request.Params["hidePath"];
		            string fEnc = Request.Params["hideEnc"];
		            string fRet = Request.Params["hideRet"];
		            string fVal = Request.Params["fContents"];
		            if(fPath == "/"){
		            	if(WriteFile(serverPath + "\\" + PROFILE_HOME + "\\" + fName, fEnc, fRet, fVal)){
		            		fResult = fPath;
		            		fName = fName;
		            	}
		            }else{
		            	if(WriteFile(serverPath + "\\" + PROFILE_HOME + fPath + "\\" + fName, fEnc, fRet, fVal)){
		            		fResult = fPath;
		            		fName = "/" + fName;
		            	}
		            }
	            }else{
	            	//Console.WriteLine("The process failed");
	            	//return;
	            	fResult = "failed";
	            }
            }
        }
	}
	
	public bool WriteFile(string fPath, string fEnc, string fRet, string fVal)
	{
		bool result = false;
		string fileval = fVal;
		try{
			System.IO.StreamWriter sw = new System.IO.StreamWriter(
			    fPath,
			    false,
			    System.Text.Encoding.GetEncoding(fEnc));
			string returnCode = "\r\n";
			switch(fRet)
			{
			  case "LF":
			    returnCode = "\n";
			    break;
			  case "CR":
			    returnCode = "\r";
			    break;
			  default:
			    returnCode = "\r\n";
			    break;
			}
			string[] lines = Regex.Split(fileval, "\r\n");

			foreach (string line in lines)
			{
			    sw.Write(line + returnCode);
			}
			sw.Close();
			result = true;
		} 
        catch(Exception ex) 
        {
            //Console.WriteLine("The process failed: {0}", ex.ToString());
            result = false;
        } 
        finally {
        	
        }
		return result;
	}
	
	public bool ReadFile(string fPath)
    {
        bool result = false;
        if (File.Exists(fPath)) {
	        string codec = "utf-8";

	        Encoding encoding = System.Text.Encoding.GetEncoding(codec);
			StreamReader sr = null;
			try{
				sr = new StreamReader(fPath,encoding);
				val = sr.ReadToEnd();
			}finally{
			    if (sr != null){
			        sr.Close();
			    }
			}
	        
            val = val.Replace("&", "&amp;");
	        //val = val.Replace("&pound;","&163;");
			//val = val.Replace("&cent;","&162;");
			//val = val.Replace("&trade;","&#8482;");
			//val = val.Replace("&reg;","&#174;");
			//val = val.Replace("&copy;","&#169;");
     		val = val.Replace("<", "&lt;");
	 		val = val.Replace(">", "&gt;");
	        val = val.Replace("'", "&#39;");
	 		val = val.Replace("\"", "&quot;");
	 		//val = val.Replace(" ", "&nbsp;");
	 		result = true;
        }
		return result;
    }
</script>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="description" content="">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<title>Untitled</title>

<script type="text/javascript">
<!--

var $ = function (id) { return document.getElementById(id) };

function loadFile_to_openFile(fName){
   $("hideTrigType").value = "loadFile_to_openFile";
   $("fContents").value = "";
   $("hideFName").value = fName;
   document.FormDirTree.submit();
}

function openFile(fPath){
  window.parent.OgEditor.openFile(fPath);
}

function getFileContents(){
  var vals = $("fContents").value;
  return vals;
}

function validate_to_SaveAs(path,fName){
   $("hideTrigType").value = "isFileExist";
   $("hidePath").value = path;
   $("hideFName").value = fName;
   document.FormDirTree.submit();
}

var validate_to_SaveAs_result = function(result){
	window.parent.OgEditor.saveAs_Interface(result);
}

function saveAs_to_refreshAndOpenDir(path,fName,enc,ret,val){
   $("hideTrigType").value = "saveAs_to_refreshAndOpenDir";
   $("fContents").value = val;
   $("hidePath").value = path;
   $("hideFName").value = fName;
   $("hideEnc").value = enc;
   $("hideRet").value = ret;
   document.FormDirTree.submit();
}

var saveAs_to_refreshAndOpenDir_result = function(fPath,fName){
    window.parent.OgEditor.refreshAndOpenDir(fPath,fName);
}

window.onload = function() {
<% 
      if(val != ""){
          Response.Write("openFile(\"" + hideFName.Value + "\");");
      }
      if(fResult != "" && fResult != "failed"){
      	Response.Write("saveAs_to_refreshAndOpenDir_result('"+fResult+"', '"+fName+"');");
      }else if(fResult == "failed"){
      	Response.Write("alert('Saving File Failed!');");
      }
      if(isFileExist != ""){
      	Response.Write("validate_to_SaveAs_result('"+isFileExist+"');");
      }
%>

$("hideTrigType").value = "";
};
	-->
	</script>
</head>
<body>
<form id="FormDirTree" name="FormDirTree" method="post" runat="server">
<input type="hidden" id="hideTrigType" name="hideTrigType" value="" runat="server">
<input type="hidden" id="hideFName" name="hideFName" value="" runat="server">
<input type="hidden" id="hidePath" name="hidePath" value="" runat="server">
<input type="hidden" id="hideEnc" name="hideEnc" value="" runat="server">
<input type="hidden" id="hideRet" name="hideRet" value="" runat="server">
<textarea id="fContents" name="fContents" rows="600" cols="150" style="display:none;"><%=val %></textarea>
</form>
</body>
</html>
