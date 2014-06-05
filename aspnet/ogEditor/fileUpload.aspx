<%@ Page Language="C#" EnableViewState="false" %>
<%@ OutputCache Location="None" VaryByParam="None" %>
<%@ Import Namespace="System" %>
<%@ Import Namespace="System.IO" %>
<%@ Import Namespace="System.Text" %>
<%@ Import Namespace="System.Net" %>
<%@ Import Namespace="System.Web" %>
<%@ Import Namespace="System.Collections.Specialized" %>
<%@ Import Namespace="System.Security.Cryptography" %>
<%@ Import Namespace="System.Data" %>
<%@ Import Namespace="System.Drawing" %>

<script runat="server">
    static protected string PROFILE_HOME = "";
    static protected string PROFILE_ROOT = System.Configuration.ConfigurationSettings.AppSettings["PROFILE_ROOT"];
    static protected string userRoot = "";
    static protected string ext;
    static protected string IMAGE_EXTNS = System.Configuration.ConfigurationSettings.AppSettings["IMAGE_EXTNS"];
    static protected string UPLOAD_FILE_EXTENSION = System.Configuration.ConfigurationSettings.AppSettings["UPLOAD_FILE_EXTENSION"];
    static protected string MAXFILESIZE = System.Configuration.ConfigurationSettings.AppSettings["MAXFILESIZE"];
    static protected string savePathForUpload;
    static public string output = "";

    protected void Page_Load(object sender, EventArgs e)
    {
    	output = "";
    	//userRoot = "";
    	ext = "";
    	
    	if(Request.QueryString["user"] != ""){
    		userRoot = Request.QueryString["user"];
    	}else{ 
    		return; 
    	}
    	
    	if(Request.QueryString["ext"] != ""){
    		ext = Request.QueryString["ext"];
    	}
        
		if (IsPostBack) {
		    if(hideTrigType.Value != ""){
		        try 
        		{
			        if(hideTrigType.Value == "uploadFile"){
			            string fPath = hideUploadPath.Value;
			            if(fPath != "/"){
			            	fPath += "/";
			            }
			        	fPath = fPath.Replace("/","\\");
				        FileUpload(fPath);
					}
		        } 
		        catch(Exception ex) 
		        {
		            //Console.WriteLine("The process failed: {0}", ex.ToString());
		            //outputResultForNewFolder = "failed";
		        } 
		        finally {
		        	hideTrigType.Value = "";
		    		hideUploadPath.Value = "";
		        }
		    }
		}
    }

    public void FileUpload(string fPath)
    {
        PROFILE_HOME = PROFILE_ROOT + "\\" + userRoot;
        savePathForUpload = Server.MapPath(Request.ApplicationPath) + "\\" + PROFILE_HOME + fPath;
        
        if (filUpload.PostedFile != null)
        {
            HttpPostedFile myFile = filUpload.PostedFile;
            int maxFileLen = int.Parse(MAXFILESIZE);
            int nFileLen = myFile.ContentLength;
            if (nFileLen == 0){
                output = "There wasn't any file uploaded.";
                return;
            }else if(nFileLen > maxFileLen){
            	// July 24 2012 modifed by Y.Ogura
				output = "Provided file exceeds max size allowed! Max file size: " + MAXFILESIZE + "byte";
				
				return;
			}
            string extension = System.IO.Path.GetExtension(myFile.FileName).ToLower();
            string allowExt = UPLOAD_FILE_EXTENSION;
            if(ext != "" && ext == "image"){
            	allowExt = IMAGE_EXTNS;
            }
            if(allowExt.IndexOf(","+extension.ToLower()+",") == -1)
            {
                output = "The selected file could not be uploaded.";
                return;
            }
            byte[] myData = new Byte[nFileLen];
            myFile.InputStream.Read(myData, 0, nFileLen);
            string sFilename = System.IO.Path.GetFileName(myFile.FileName);
            int file_append = 0;
            while(System.IO.File.Exists(savePathForUpload + sFilename))
            {
                file_append++;
                sFilename = System.IO.Path.GetFileNameWithoutExtension(myFile.FileName) + file_append.ToString() + extension;
            }
            System.IO.FileStream newFile = new System.IO.FileStream(savePathForUpload + sFilename, System.IO.FileMode.Create);
            newFile.Write(myData, 0, myData.Length);
            newFile.Close();
            output = "File uploaded successfully!";
        }
    }

</script>

<HTML>
	<HEAD>
		<title>fileUpload</title>
		<style type="text/css">
		<!--
		body {
			margin:0px;
			padding: 0px;
			border:0px;
			font-family:Arial;
			font-size:x-small;
		}
		-->
		</style>
	  <script type="text/javascript">
	  <!--
	  var $ = function (id) { return document.getElementById(id) };

	  function uploadFile(dir){
	  		$("hideUploadPath").value = dir;
	  		$("hideTrigType").value = "uploadFile";
	  		document.formFileUpload.submit();
	  }
	  window.onload = function() {
		<%
		if(output != ""){
			Response.Write("alert('"+output+"'); window.parent.OgEditor.uploadHasDone();");
		}
		%>
	  }
	  -->	
	  </script>
	</HEAD>
	<body>
		<form id="formFileUpload" name="formFileUpload" method="post" runat="server" enctype="multipart/form-data">
			<INPUT id="filUpload"  type="file" name="filUpload" runat="server">
			<input type="button" onclick="uploadFile('/');" value="click me"/>
			<input type="hidden" id="hideTrigType" name="hideTrigType" value="" runat="server"/>
			<input type="hidden" id="hideUploadPath" name="hideUploadPath" value="" runat="server"/>
		</form>
	</body>
</HTML>
