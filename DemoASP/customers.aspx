<!DOCTYPE html>
<html>
<head>
<title>ASP Demo</title>
<link href="Site.css" rel="stylesheet">
</head>
<body>
<div id="main">
<h1>Customers</h1>

<%
set conn=Server.CreateObject("ADODB.Connection")
conn.Provider="Microsoft.Jet.OLEDB.4.0"
conn.Open("https://www.apphb.com/DemoASP/Northwind.mdb")
set rs = Server.CreateObject("ADODB.recordset")
rs.Open "Select CompanyName, City, Country from Customers", conn
%>
<table border="1">
<tr><th>Name</th><th>City</th><th>Country</th></tr>
<%do until rs.EOF%>
<tr>
<%for each x in rs.Fields%>
<td>
<%Response.Write(x.value)%>
</td>
<%next%>
</tr>
<%
rs.MoveNext
loop
%>
</table>
<%
rs.close
conn.close
%>

<!-- #include file="Footer.inc" -->
</div>

</body>
</html> 
