<%@ Page Language="C#" EnableViewState="false" %>
<%@ OutputCache Location="None" VaryByParam="None" %>
<%@ Import Namespace="System" %>
<%@ Import Namespace="System.IO" %>
<%@ Import Namespace="System.Text" %>
<%@ Import Namespace="System.Net" %>
<%@ Import Namespace="System.Web" %>
<%@ Import Namespace="System.Collections.Specialized" %>
<%@ Import Namespace="System.Security.Cryptography" %>

<script runat="server">
    static protected string PROFILE_HOME = "";
    static protected string PROFILE_ROOT = System.Configuration.ConfigurationSettings.AppSettings["PROFILE_ROOT"];
    static protected string userRoot = "";
    static protected string EDPANE_NAME = System.Configuration.ConfigurationSettings.AppSettings["EDPANE_NAME"];
    static protected string EDPANE_HOME = System.Configuration.ConfigurationSettings.AppSettings["EDPANE_HOME"];
    static protected string DEFAULT_EXTNS = System.Configuration.ConfigurationSettings.AppSettings["ACCEPT_FILE_EXTENSION"];
    static protected string IMAGE_EXTNS = System.Configuration.ConfigurationSettings.AppSettings["IMAGE_EXTNS"];
    static protected string EDJS_PATH = System.Configuration.ConfigurationSettings.AppSettings["EDJS_PATH"];
    static protected string EDCSS_PATH = System.Configuration.ConfigurationSettings.AppSettings["EDCSS_PATH"];
    static protected string OGCONFIG_PATH = System.Configuration.ConfigurationSettings.AppSettings["OGCONFIG_PATH"];
    static protected string EMPTY_VALUE = "";
    static protected string edSrcFile = "";
    static protected string profilePath = "";
    static protected int level = 0;
    static protected int depth = 0;
    static protected int rnum = 1;
    static protected int numOfTable = 0;
    static protected string rootName = "";
    static protected string rootDirName = "/";
    static public string output = "";
    static public string postBackOutput;
    static public string val = "";
    static protected string fileExtensionsToAccept = "";
    static protected string refFileType;
    static protected string typeOfBrowse;
    static public bool isEdIgnore;
    static protected bool isTrigTypeEmpty;
    static protected bool isFirstTime;
    
    private void Page_Load(object sender, EventArgs e)
	{
		postBackOutput = "";
	    refFileType = "";
	    typeOfBrowse = "";
	    //userRoot = "";
	    fileExtensionsToAccept = DEFAULT_EXTNS;
	    isEdIgnore = false;
	    isTrigTypeEmpty = true;
	    isFirstTime = false;
	    
	    if(Request.QueryString["fileType"] != ""){
    		string fileType = Request.QueryString["fileType"];
    		switch(fileType)
			{
			  case "image":
			  	fileExtensionsToAccept = IMAGE_EXTNS;
			    break;
			  default:
			    fileExtensionsToAccept = DEFAULT_EXTNS;
			    break;
			}
    	}
    	if(Request.QueryString["edIgnore"] != ""){
    		string fileType = Request.QueryString["edIgnore"];
    		switch(fileType)
			{
			  case "yes":
			  	isEdIgnore = true;
			    break;
			  default:
			    isEdIgnore = false;
			    break;
			}
    	}
    	if(Request.QueryString["browse"] != ""){
    		typeOfBrowse = Request.QueryString["browse"];
    	}
    	if(Request.QueryString["refFileType"] != ""){
    		refFileType = Request.QueryString["refFileType"];
    	}
    	if(Request.QueryString["user"] != ""){
    		userRoot = Request.QueryString["user"];
    	}else{ 
    		return; 
    	}
    	
    	PROFILE_HOME = PROFILE_ROOT + "\\" + userRoot;
	    profilePath = Server.MapPath(Request.ApplicationPath) + "\\" + PROFILE_HOME;
	    edSrcFile = System.IO.Path.Combine(Server.MapPath(Request.ApplicationPath) + "\\" + EDPANE_HOME, EDPANE_NAME);
	    
	    if(!IsPostBack){
	    	isFirstTime = IsFirstTimeBuild();
	    	if(!Directory.Exists(profilePath)) 
	        {
	            // Try to create the directory.
	            DirectoryInfo dir = Directory.CreateDirectory(profilePath);
	        }
	    }else {
		    if(hideTrigType.Value != ""){
		    	isTrigTypeEmpty = false;
		        if(hideTrigType.Value == "createFolder"){
			        string fPath = hideNewFldPath.Value;
			        string fldName = hideNewFldName.Value;
			        if(fPath != "/"){
			        	fldName = "\\" + fldName;
			        }
			        string fldPath = fPath.Replace("/","\\");
			        try 
	        		{
				        string path = profilePath + fldPath + fldName;
				        if(!Directory.Exists(path)){
				        	DirectoryInfo di = Directory.CreateDirectory(path);
				        }
				        postBackOutput = "openFolders('" + fPath + "',null,'createFolder');";
			        } 
			        catch(Exception ex) 
			        {
			            //Console.WriteLine("The process failed: {0}", ex.ToString());
			            //outputResultForNewFolder = "failed";
			        } 
			        finally {}
		        }
		        else if(hideTrigType.Value == "refreshAndOpenDir_to_openFolders"){
		            //postBackOutput = "openFolders('"+hideNewFldPath.Value+"','"+hideFileOpenPath.Value+"');";
		            postBackOutput = "openFolders('"+hideNewFldPath.Value+"');";
		        }
		    }
	    }
	    hideNewFldPath.Value = "";
		hideNewFldName.Value = "";
		hideTrigType.Value = "";
		
	    output = "";
	    bool result = SearchDir(new DirectoryInfo(profilePath), level, rnum, "", rootDirName, true, "/");
	    output += "$(\"FormDirTree\").appendChild(" + rootName + ");";
	}
    
    protected bool SearchDir(DirectoryInfo di, int level, int rnum, string parentTbl, string dirName, bool isLstOne, string  frmHomePathForFolder) {
	    level++;
	    
	    if(depth < level) depth = level;
	    bool hasED = false;
	    string destFile = "";
	    
	    destFile = System.IO.Path.Combine(di.FullName, EDPANE_NAME);
	    if (File.Exists(destFile)) {
	      if(isFirstTime){
	        File.Delete(destFile);
	      	hasED = false;
	      }else{
		  	hasED = true;
		  }
		}
        
        DirectoryInfo[] dis = di.GetDirectories();
	    int dLen = dis.Length;
	    FileInfo[] fis = di.GetFiles();
	    int fLen = fis.Length;
	    if(hasED){ fLen -= 1;}
	    string hasChilds = (dLen > 0 || fLen > 0) ? "true" : "false";
	    
	    string tbl = frmHomePathForFolder.Replace("/", "_");
        if(tbl == "_"){tbl = "_root";}
       
        string strLstOne = isLstOne ? "true" : "false";
        if(dirName == rootDirName){
            rootName = tbl;
            output += "var " + tbl + " = document.createElement('TABLE');" + "\n";
            output += "var " + tbl + " = makeRoot(" + tbl + ",\"" + tbl + "\", " + strLstOne + "," + level + ", \"" + dirName + "\", " + hasChilds + ",\"\");" + "\n";
        }else{
            output += "var " + tbl + " = makeFldrTble(" + parentTbl + ",\"" + tbl + "\", " + strLstOne + "," + level + ", \"" + dirName + "\", " + hasChilds + ",\"\", \"" + frmHomePathForFolder + "\");" + "\n";
        }
        rnum = 0;
        
	    foreach (DirectoryInfo d in dis) {
	      //string pathForFolder = d.FullName.Replace(profilePath, "");
	      string pathForFolder = d.FullName.Replace(Request.ServerVariables["APPL_PHYSICAL_PATH"], String.Empty);
	      pathForFolder = pathForFolder.Replace(PROFILE_ROOT + "\\" + userRoot, String.Empty);
	      pathForFolder = pathForFolder.Replace("\\", "/");
	      rnum++;
	      isLstOne = (fLen == 0 && dLen == rnum)  ? true : false;
	      SearchDir(d, level, rnum, tbl, d.Name, isLstOne, pathForFolder);
	    }

	    rnum = 0;
	    foreach (FileInfo f in fis) {
	      //string frmHomePath = f.FullName.Replace(profilePath, "");
	      if(f.Name != EDPANE_NAME){
		      string relativePath = f.FullName.Replace(Request.ServerVariables["APPL_PHYSICAL_PATH"], String.Empty);
		      relativePath = relativePath.Replace(PROFILE_ROOT + "\\" + userRoot, String.Empty);
		      rnum++;
		      strLstOne = (fLen == rnum) ? "true" : "false";
		      // if fileExtension include the followings ( .html, .htm, .js, .css, txt. ) then create hyperlink
		      string extension = System.IO.Path.GetExtension(f.FullName);
		      if((typeOfBrowse == "" || typeOfBrowse != "folder") && extension != "" && fileExtensionsToAccept.IndexOf(","+extension.ToLower()+",") != -1){
		      	output += "makeFileTble(" + tbl + ", " + strLstOne + "," + level + ", \"" + relativePath.Replace("\\","/") + "\", " + hasChilds + ",\"\", true);" + "\n";
		      }else{
		      	output += "makeFileTble(" + tbl + ", " + strLstOne + "," + level + ", \"" + relativePath.Replace("\\","/") + "\", " + hasChilds + ",\"\", false);" + "\n";
		      }
	      }
	    }
	    
	    //if(!isEdIgnore && isTrigTypeEmpty){
		    // To copy a file to another location and 
	        // overwrite the destination file if it already exists.
	        if (!File.Exists(destFile)) {
	        	//System.IO.File.Copy(edSrcFile, destFile, true);
	        	WriteED(destFile);
	        }
        //}
	    return true;
	 }
	 
	 protected void WriteED(string filePath)
	 {
	 	using (StreamWriter writer = new StreamWriter(filePath, false, System.Text.Encoding.GetEncoding("utf-8")))
		{
		    writer.WriteLine("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">");
		    writer.WriteLine("<html><head>");
		    writer.WriteLine("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">");
		    writer.WriteLine("<meta http-equiv=\"pragma\" content=\"no-cache\">");
			writer.WriteLine("<meta http-equiv=\"Cache-Control\" content=\"no-cache\">");
		    writer.WriteLine("<title>ed</title>");
			writer.WriteLine("<lin" + "k href=\"" + EDCSS_PATH + "\" rel=\"stylesheet\" type=\"text/css\">");
			writer.WriteLine("<scr" + "ipt type=\"text/javascript\" src=\"" + OGCONFIG_PATH + "\" ></scr" + "ipt>");
		    writer.WriteLine("<scr" + "ipt type=\"text/javascript\" src=\"" + EDJS_PATH + "\" ></scr" + "ipt>");
		    writer.WriteLine("</head><body></body></html>");
		}
	 }
	 
	 protected bool IsFirstTimeBuild()
     {
        string result = "0";
        
        string path = Server.MapPath(Request.ApplicationPath) + "\\" + System.Configuration.ConfigurationSettings.AppSettings["BUILD_PATH"];
        if (File.Exists(path)) {
            using (StreamReader sr = new StreamReader(path)) 
            {
                result = sr.ReadLine();
            }
		}
		if(result != "1"){
            using (StreamWriter sw = new StreamWriter(path, false)) 
            {
                sw.WriteLine("1");
            }
        }
		return result != "1" ? true : false;
	 }
			
    
</script>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="description" content="">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<title>ogFileManager</title>
<STYLE type="text/css">
.title
{
FONT-FAMILY: Arial ;
FONT-SIZE: 9px;
}
</style>
<script type="text/javascript">
<!--
var isFirefox = navigator.userAgent.indexOf('Firefox') != -1 ? true : false;
var isIE = navigator.appName == 'Microsoft Internet Explorer' ? true : false;
var isChrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
var isOpera = (navigator.userAgent.toLowerCase().indexOf('opera')+1?1:0);
if (isOpera){
	isIE = false;
}
var $ = function (id) { return document.getElementById(id) };
var depth = <%=depth %> + 4;
var imgWidth = 18;
var maxCellSize = 200;
var level = 0;
var isLstOne = true;
var arFldOpened = [];

function showHideFldWthFld(id, folderName){
<%
if(isEdIgnore){
	Response.Write("if(window.parent && window.parent.document.getElementById('folderBrowserFolderPath')){" + System.Environment.NewLine);
	Response.Write("	window.parent.document.getElementById('folderBrowserFolderPath').value = folderName;" + System.Environment.NewLine);
	Response.Write("}" + System.Environment.NewLine);
	Response.Write("if(window.parent && window.parent.document.getElementById('newFolderBrowserFolderPath')){" + System.Environment.NewLine);
	Response.Write("	window.parent.document.getElementById('newFolderBrowserFolderPath').value = folderName;" + System.Environment.NewLine);
	Response.Write("}" + System.Environment.NewLine);
	Response.Write("if(window.parent && window.parent.document.getElementById('fileBrowserFilePath')){" + System.Environment.NewLine);
	Response.Write("	window.parent.document.getElementById('fileBrowserFilePath').value = folderName;" + System.Environment.NewLine);
	Response.Write("}" + System.Environment.NewLine);
}
if(refFileType != "" && refFileType == "image"){
	Response.Write("// blabla" + System.Environment.NewLine);
}
%>
	
	var table = document.getElementById(id);
	var fldImg = document.getElementById(id+"_f");
	var elem = document.getElementById(id+"_plus");
	var disp = true;
	var cols = 0;
	for (i=1; i<table.rows.length; i++) {
		var row = table.rows[i];
		if (row.style.display != "none") {
			row.style.display = "none";
			disp = false;
		}
		else{
			row.style.display = "";
		}
	}
	if(disp){
<%
if(!isEdIgnore){
	Response.Write("window.parent.OgEditor.setFolderInfo(folderName);" + System.Environment.NewLine);
}
%>
	}
	if(elem.src.indexOf("foldertree_plus") != -1){
	        elem.src = "img/foldertree_minus.gif";
			fldImg.src = "img/foldertree_folderopen.gif";
	}else{
	        elem.src = "img/foldertree_plus.gif";
			fldImg.src = "img/foldertree_folder.gif";
	}
}
	  
	  // 
	  // tb_depth0(root-always0)_depth1(1.2.3...)_depth2
	  // tr_depth0(tb_0��tr 0.1.2.3...)_depth1(tb_0_1��tr 0.1.2.3...)_depth2
	  //
	  // js::depth = 
	  //
	  // ROOT - tb_0  tr_0_0
	  //
	  // depth = depth + 1;
	  // var childs = elem.childNodes;
      // for(var i=0; i<childs.length;i++)
      // {
      //    // add tr 
      //    //childs[0] - file   - tr_0_1
      //    //childs[0] - folder - tr_0_1 - tb_0_1 tr_0_1_0
      //
      //    //childs[1] - file   - tr_0_2
      //    //childs[1] - folder - tr_0_2 - tb_0_2 tr_0_2_0
      //----------------------------------------------------------------
      //    //childs[1].childs[0] - file   - tr_0_2_1
      //    //childs[1].childs[0] - folder - tr_0_2_1 - tb_0_2_1 tr_0_2_1_0
      //
      // js:: function init(){constTree(7(depth num))}
      
      /*
      // Folder or File, if Folder WthFld(+) or NoFld, last folder/file( no ---�c)
	
      
      
      // This is for ROOT
      
      // This is for Folder other than ROOT
      */
      
      
function makeRoot(table, id, isLstOne, level, folderName, hasChilds, strEvent){
	var rowIndex;
	table.id = id;
	table.cellSpacing = "0";
	table.cellPadding = "0";
	table.border = "0";
	table.align = "left";
	table.className = "_";
	table.style.borderCollapse = "collapse";
	var row;
	if(isFirefox || isOpera){
		row = table.insertRow(-1);
	}else{
		rowIndex = table.rows.length;
		row = table.insertRow(rowIndex);
		rowIndex = table.rows.length;
	}
	row.id = "tr_" + id;
	row.align="left";
	row.style.display="";
	var cell;
	var i=0;
	if(isFirefox || isOpera){
		cell = row.insertCell(-1);
	}else{
		cell = row.insertCell(i);
	}
	
	cell.width = imgWidth+"px";
	if(!hasChilds) cell.innerHTML = "<img id=\"" + table.id + "_plus\" onclick=\"showHideFldWthFld('" + table.id + "','/');\" src=\"img/foldertree_plus_noline.gif\"/>";// '--
	else cell.innerHTML = "<img id=\"" + table.id + "_plus\" onclick=\"showHideFldWthFld('" + table.id + "','/');\" src=\"img/foldertree_plus_noline.gif\"/>";// +
	i++;
	if(isFirefox || isOpera){
		cell = row.insertCell(-1);
	}else{
		cell = row.insertCell(i);
	}
	cell.width = imgWidth+"px";
	if(!hasChilds) cell.innerHTML = "<img id=\"" + table.id + "_f\" src=\"img/foldertree_folder.gif\"/>";// '--
	else cell.innerHTML = "<img id=\"" + table.id + "_f\" src=\"img/foldertree_folder.gif\"/>";// +
	i++;
	var colspan = depth - i;
	var cellSize = imgWidth * colspan + maxCellSize;
	if(isFirefox || isOpera){
		cell = row.insertCell(-1);
	}else{
		cell = row.insertCell(i);
	}
	cell.width = cellSize+"px";
	cell.colSpan = colspan;
	cell.innerHTML = "<a href=\"javascript::void(0);\" class=\"title\" style=\"white-space: nowrap\" " + strEvent + " style=\"word-break:keep-all;white-space: nowrap;\"><nobr>" + folderName + "</nobr></a>";
	i++;

	return table;
}
  
  
function makeFldrTble(parntTbl, id, isLstOne, level, folderName, hasChilds, strEvent, frmHomePathForFolder){
	var rowIndex;
	var pRow;
	if(isFirefox || isOpera){
		pRow = parntTbl.insertRow(-1);
	}else{
		rowIndex = parntTbl.rows.length;
		pRow = parntTbl.insertRow(rowIndex);
		rowIndex = parntTbl.rows.length;
	}
	pRow.align="left";
	pRow.style.display="none";
	var pCell;
	if(isFirefox || isOpera){
		pCell = pRow.insertCell(-1);
	}else{
		pCell = pRow.insertCell(0);
	}
	pCell.width = imgWidth+"px";
	pCell.colSpan = depth;

	var table = document.createElement('TABLE');
	table.id = id;
	var className = frmHomePathForFolder.replace(/\//g, "_");
	table.cellSpacing = "0";
	table.cellPadding = "0";
	table.border = "0";
	table.align = "left";
	table.className = className;
	table.style.borderCollapse = "collapse";

	var row;
	if(isFirefox || isOpera){
		row = table.insertRow(-1);
	}else{
		row = table.insertRow();
	}
	row.id = "tr_" + id;
	row.align="left";
	row.style.display="";
	var cell;
	var i=0;
	if(level>0){
	 for(i=0;i<level;i++)
	 {
	    if(isFirefox || isOpera){
			cell = row.insertCell(-1);
		}else{
			cell = row.insertCell(i);
		}
	    cell.width = imgWidth+"px";
	    if(i==(level-1) && isLstOne){
	       if(!hasChilds) cell.innerHTML = "<img src=\"img/foldertree_joinbottom.gif\"/>";// '--
	       else cell.innerHTML = "<img  id=\"" + table.id + "_plus\" onclick=\"showHideFldWthFld('" + table.id + "','" + frmHomePathForFolder + "');\" src=\"img/foldertree_plusbottom.gif\"/>";// +
	    }else if(i==(level-1) && !isLstOne){
	       if(!hasChilds) cell.innerHTML = "<img src=\"img/foldertree_join.gif\"/>";// |--
	       else cell.innerHTML = "<img  id=\"" + table.id + "_plus\" onclick=\"showHideFldWthFld('" + table.id + "','" + frmHomePathForFolder + "');\" src=\"img/foldertree_plus.gif\"/>";// -|----
	    }else {
	       if(i==0){cell.innerHTML = "<img src=\"img/foldertree_empty.gif\"/>";}
	       else {cell.innerHTML = "<img src=\"img/foldertree_line.gif\"/>";}
	    }
	 }
	}
	if(isFirefox || isOpera){
		cell = row.insertCell(-1);
	}else{
		cell = row.insertCell(i);
	}
	cell.width = imgWidth+"px";
	cell.innerHTML = "<img id=\"" + table.id + "_f\" onclick=\"showHideFldWthFld('" + table.id + "','" + frmHomePathForFolder + "');\" src=\"img/foldertree_folder.gif\"/>";
	i++;
	var colspan = depth - i;
	var cellSize = imgWidth * colspan + maxCellSize;
	if(isFirefox || isOpera){
		cell = row.insertCell(-1);
	}else{
		cell = row.insertCell(i);
	}
	cell.width = cellSize+"px";
	cell.colSpan = colspan;
	cell.noWrap = "true";
	cell.innerHTML = "<span style=\"text-align:left;\" ><a href=\"javascript::void(0);\" onclick=\"showHideFldWthFld('" + table.id + "','" + frmHomePathForFolder + "');\" class=\"title\" class=\"title\" style=\"white-space: nowrap\" " + strEvent + " style=\"word-break:keep-all;white-space: nowrap;\"><nobr>" + folderName + "</nobr></a></span>";
	i++;

	pCell.appendChild(table);
	return table;
}


function makeFileTble(parntTbl, isLstOne, level, fullFileName, hasChilds, strEvent, allowLink){
	var fileName = fullFileName.replace(/^(.*)[/]([^/]+\.[^/]+)$/, "$2");
	var rowIndex;
	var row;
	if(isFirefox || isOpera){
		row = parntTbl.insertRow(-1);
	}else{
		rowIndex = parntTbl.rows.length;
		row = parntTbl.insertRow(rowIndex);
	}
	row.align="left";
	row.style.display="none";
	var cell;
	var i=0;
	level++;
	if(level>0){
	 for(i=0;i<level;i++)
	 {
	    if(isFirefox || isOpera){
			cell = row.insertCell(-1);
		}else{
			cell = row.insertCell(i);
		}
	    cell.width = imgWidth+"px";
	    if(i==(level-1) && isLstOne){
	       cell.innerHTML = "<img src=\"img/foldertree_joinbottom.gif\"/>";// '--
	       
	    }else if(i==(level-1) && !isLstOne){
	       cell.innerHTML = "<img src=\"img/foldertree_join.gif\"/>";// |--
	       
	    }else {
	       if(i==0){cell.innerHTML = "<img src=\"img/foldertree_empty.gif\"/>";}
	       else {cell.innerHTML = "<img src=\"img/foldertree_line.gif\"/>";}
	    }
	 }
	}
	if(isFirefox || isOpera){
		cell = row.insertCell(-1);
	}else{
		cell = row.insertCell(i);
	}
	cell.width = imgWidth+"px";
	cell.innerHTML = "<img src=\"img/foldertree_page.gif\"/>";
	i++;
	var colspan = depth - i;
	var cellSize = imgWidth * colspan + maxCellSize;
	if(isFirefox || isOpera){
		cell = row.insertCell(-1);
	}else{
		cell = row.insertCell(i);
	}
	cell.width = cellSize+"px";
	cell.colSpan = colspan;
	cell.noWrap = "true";
	if(allowLink){
<%
		if(refFileType != "" && refFileType == "image"){
%>
			cell.innerHTML = "<span style=\"text-align:left;\" ><a href=\"javascript::void(0);\" class=\"title\" onclick=\"setFileName('" + fullFileName + "')\" style=\"white-space: nowrap\" " + strEvent + " style=\"word-break: keep-all;\">" + fileName + "</a></span>";
<%
		}else{
%>
			cell.innerHTML = "<span style=\"text-align:left;\" ><a href=\"javascript::void(0);\" class=\"title\" onclick=\"openFile('" + fullFileName + "')\" style=\"white-space: nowrap\" " + strEvent + " style=\"word-break: keep-all;\">" + fileName + "</a></span>";
<%
		}
%>
		
	}else{
	cell.innerHTML = "<span style=\"text-align:left;\" class=\"title\" style=\"word-break:keep-all;white-space: nowrap;\" ><nobr>" + fileName + "</nobr></span>";
	}
	i++;
}

function setFileName(fullFileName){
    window.parent.OgEditor.setFileLoc(fullFileName);
}

function refreshWindow(){
	$("hideTrigType").value = "refresh";
	document.FormDirTree.submit();
}

function getHomePath(){
	return $("homePath").value;
};

function createNewFolder(dir, name){
	$("hideNewFldPath").value = dir;
	$("hideNewFldName").value = name;
	$("hideTrigType").value = "createFolder";
	document.FormDirTree.submit();
}

function resultForNewFolder(result, dir){
	window.parent.OgEditor.resultForNewFolder(result, dir);
}

var refreshAndOpenDir_to_openFolders = function(dir,fName){
    $("hideTrigType").value = "refreshAndOpenDir_to_openFolders";
	$("hideNewFldPath").value = dir;
	$("hideFileOpenPath").value = dir + fName;
	document.FormDirTree.submit();
};


function openFolders(dir,fPath,type){
   showHideFldWthFld('_root','/');
   if(dir != "/"){
  	   var flders = dir.split("/");
  	   dir = "";
  	   for(var i=1; i<flders.length; i++){
  	        dir += "/" + flders[i];
  	   		openFolder(dir);
  	   }
   }
   if(fPath){
   		openFile(fPath);
   }
   if(type && type=="createFolder"){
   		window.parent.OgEditor.resultForNewFolder(dir);
   }
}

function openFolder(dir){
		var className = dir.replace(/\//g, "_");
   		var elem = getElementsByClass(className,$("FormDirTree"),"table");
  	    showHideFldWthFld(elem[0].id,dir);
}

function getElementsByClass(searchClass,node,tag) {
	var classElements = new Array();
	if ( node == null )
		node = document;
	if ( tag == null )
		tag = '*';
	var els = node.getElementsByTagName(tag);
	var elsLen = els.length;
	var pattern = new RegExp('(^|\\\\s)'+searchClass+'(\\\\s|$)');
	for (i = 0, j = 0; i < elsLen; i++) {
		if ( pattern.test(els[i].className) ) {
			classElements[j] = els[i];
			j++;
		}
	}
	return classElements;
}

function debugDoc(){
	$("edit_src").value = document.body.innerHTML;
}

function openFile(fPath){
    //alert(fPath);
   	window.parent.OgEditor.loadFile_to_openFile(fPath);
}

function cancelDefault(evt) {
    var evt = window.event || evt;
	if (evt.preventDefault) {
          evt.preventDefault();
    }
    evt.returnValue = false;
}
	
function addEvent(target, type, handler){
	if(target.attachEvent){
		target.attachEvent("on"+type,handler);
	}else if(target.addEventListener){
		target.addEventListener(type,handler,false);
	}else {
		target['on' + type] = handler;
	}
}

window.onload = function() {
        if(isIE){
			addEvent(document.body, 'dragenter', cancelDefault);
			addEvent(document.body, 'dragover', cancelDefault);
			addEvent(document.body, 'drop', cancelDefault);
		}
<%
if(output != ""){
	Response.Write(output + System.Environment.NewLine);
	Response.Write("window.parent.OgEditor.setFolderInfo('/');" + System.Environment.NewLine);
}

if(postBackOutput != ""){
	Response.Write(postBackOutput + System.Environment.NewLine);
}

%>
		window.parent.OgEditor.hideLoading();
};
	-->
	</script>
</head>
<body style="background-color:#eeeeee;">

<form id="FormDirTree" method="post" name="FormDirTree" runat="server">
<input type="hidden" id="hideNewFldPath" name="hideNewFldPath" value="" runat="server">
<input type="hidden" id="hideNewFldName" name="hideNewFldName" value="" runat="server">
<input type="hidden" id="hideTrigType" name="hideTrigType" value="" runat="server">
<input type="hidden" id="hideFName" name="hideFName" value="" runat="server">
<input type="hidden" id="hideFileOpenPath" name="hideFileOpenPath" value="" runat="server">
<input type="hidden" id="arFldOpened" name="arFldOpened" value="" runat="server">
<input type="hidden" id="hideUploadPath" name="hideUploadPath" value="" runat="server"/>
<input type="hidden" id="homePath" name="homePath" value="<%=PROFILE_HOME.Replace("\\", "/") %>" />
</form>
</body>
</html>
