<%@ Control Language="C#" Inherits="System.Web.Mvc.ViewUserControl<dynamic>" %>
<%@ Page Language="C#" MasterPageFile="~/Views/Shared/Site.Master" Inherits="System.Web.Mvc.ViewPage" %>
<%@ Import Namespace="www.Helpers" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head id="Head1" runat="server">
    <title>Product Index</title>
</head>
<body>
    <div>
    
    <%= Html.Encode(ViewData["message"]) %>
    
    </div>

<asp:Content ID="indexContent" ContentPlaceHolderID="MainContent" runat="server">

    <!-- Calling helper without HTML attributes -->
    <%= Html.Image("img1", ResolveUrl("/Content/XBox.jpg"), "XBox Console") %>


    <!-- Calling helper with HTML attributes -->
    <%= Html.Image("img1", ResolveUrl("/Content/XBox.jpg"), "XBox Console", new {border="4px"})%>


</asp:Content>
</body>
</html>

