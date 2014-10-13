<%@ Page Language="C#" EnableViewState="false" Debug="true" %>
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
    static protected string ACCEPT_EXTNS = System.Configuration.ConfigurationSettings.AppSettings["ACCEPT_FILE_EXTENSION"];
    static protected string MAXFILESIZE = System.Configuration.ConfigurationSettings.AppSettings["MAXFILESIZE"];
    static public string output = "";
    static public string filename = "";

    protected void Page_Load(object sender, EventArgs e)
    {
    	output = "";
    	filename = "";

		if (IsPostBack) {
		    if(hideTrigType.Value != ""){
		        try 
        		{
			        if(hideTrigType.Value == "openFile"){
			            string encoding = hideEnc.Value;
			            FileRead(encoding);
					}
		        } 
		        catch(Exception ex) 
		        {
		            //Console.WriteLine("The process failed: {0}", ex.ToString());
		            //outputResultForNewFolder = "failed";
		        } 
		        finally {
		        	hideTrigType.Value = "";
		        }
		    }
		}
    }

    public void FileRead(string encoding)
    {
        if (filUpload.PostedFile != null)
        {
        	HttpPostedFile myFile = filUpload.PostedFile;
        	int maxFileLen = int.Parse(MAXFILESIZE);
            int nFileLen = myFile.ContentLength;
            if (nFileLen == 0)
            {
                output = "There wasn't any file uploaded.";
                return;
            }else if(nFileLen > maxFileLen){
				output = "Provided file exceeds max size allowed! Max file size: " + MAXFILESIZE + "byte";
				return;
			}
            string extension = System.IO.Path.GetExtension(myFile.FileName).ToLower();
            if (ACCEPT_EXTNS.IndexOf(","+extension.ToLower()+",") == -1)
            {
                output = "Selected file is not accepted!";
                return;
            }
			filename = myFile.FileName;
            byte[] myData = new Byte[nFileLen];
            StreamReader reader = null;
            
            try
			{
				Encoding curEnc = Encoding.GetEncoding(encoding);
                reader = new StreamReader(myFile.InputStream, curEnc);
                output = reader.ReadToEnd();
                //Encoding enc = reader.CurrentEncoding;
                Encoding utf8 = Encoding.UTF8;
                Byte[] befBytes = curEnc.GetBytes(output);
                // Convert to UTF-8
                Byte[] aftBytes = Encoding.Convert(curEnc, utf8, befBytes);
                output = utf8.GetString(aftBytes);
                    
			    output = output.Replace("&", "&amp;");
		        //output = output.Replace("&pound;","&163;");
				//output = output.Replace("&cent;","&162;");
				//output = output.Replace("&trade;","&#8482;");
				//output = output.Replace("&reg;","&#174;");
				//output = output.Replace("&copy;","&#169;");
	     		output = output.Replace("<", "&lt;");
		 		output = output.Replace(">", "&gt;");
		        output = output.Replace("'", "&#39;");
		 		output = output.Replace("\"", "&quot;");
		 		//output = output.Replace(" ", "&nbsp;");
			}
			finally
			{
			    if (reader != null)
			    {
			        reader.Close();
			    }
			}
        }
    }
    
</script>
<HTML>
	<HEAD>
		<title>fileOpenForIE</title>
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

	  function openFile(val){
	        $("hideTrigType").value = "openFile";
	  		$("hideEnc").value = val;
	  		document.formFileUpload.submit();
	  }
	  
	  function newFile(filename){
	  	window.parent.OgEditor.newFile("/" + filename,"default", $("fContents").value);
	  }
	  window.onload = function(){
	  <%
	    if(filename != ""){
			Response.Write("newFile(\"" + filename + "\",\"default\");" + System.Environment.NewLine);
		}
	  %>
	  }
	  
	  -->	
	  </script>
	</HEAD>
	<body>
		<form id="formFileUpload" name="formFileUpload" method="post" action="fileOpenForIE.aspx" runat="server" enctype="multipart/form-data">
			<INPUT id="filUpload"  type="file" name="filUpload" accept="text/*" runat="server">
			<input type="hidden" id="hideTrigType" name="hideTrigType" value="" runat="server"/>
			<input type="hidden" id="hideEnc" name="hideEnc" value="" runat="server"/>
			<textarea id="fContents" name="fContents" rows="600" cols="150" style="display:none;"><%=output %></textarea>
		</form>
	</body>
</HTML>
