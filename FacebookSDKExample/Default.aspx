<%@ Page Language="C#" AutoEventWireup="true" CodeFile="Default.aspx.cs" Inherits="_Default" %>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head id="Head1" runat="server">
    <title>Which SportsPerson are you?</title>
    <style type="text/css">
        #Find {
            -moz-user-select: none;
            background: #35619F;
            border: 1px solid #082783;
            white-space: nowrap;
            padding: 0.3em 0.6em 0.375em;
            color: white;
            text-decoration: none;
            text-align: center;
            font: bold 14px/normal 'lucida grande', tahoma, verdana, arial, sans-serif;
        }
    </style>
</head>
<body>
    <form id="form1" runat="server">
        <div style="width: 290px; margin: 0 auto;">
            <font size="5">Which SportsPerson are you?</font>
            <br />
            <br />
            <div style="width: 100px; margin: 0 auto;">
                <asp:Button ID="Find" Text="Click to Find" runat="server" Width="100px" CssClass="m-btn"
                    OnClick="Find_Click" />
            </div>
        </div>
        <br />
        <hr />
        <br />
        <div style="clear: right; float: right">
            <div style="float: right">
                <asp:Button ID="Login" runat="server" Text="Log in with Facebook" OnClick="Login_Click" />
                <br />
                <asp:Label ID="NameText" runat="server" Text="" Font-Size="Small" Visible="false"></asp:Label>
            </div>
            <br />
            <br />
            <br />
            <asp:Label ID="Label1" runat="server" Text="Sports Persons you Like" /><br />
            <asp:ListBox ID="SportsPersonList" runat="server" Width="200px" Height="200px"></asp:ListBox>
            <br />
            <br />
            <asp:TextBox ID="FeedbackText" runat="server" TextMode="MultiLine" Width="195px"
                Height="40px"></asp:TextBox><br />
            <asp:Button ID="GiveFeedback" runat="server" Width="100px" Text="Give Feedback" OnClick="GiveFeedback_Click" />
            <asp:Button runat="server" ID="SharePage" Text="Share Page" Width="100px" OnClick="SharePage_Click" /><br />
        </div>
        <div style="clear: left; float: left; margin-right: 80px; margin-bottom: 40px">
            <asp:Label ID="Label2" runat="server" Text="Your Friends"></asp:Label><br />
            <div style="overflow-y: scroll; width: 200px; height: 340px; border: 1px solid gray">
                <asp:CheckBoxList ID="FriendList" runat="server">
                </asp:CheckBoxList>
            </div>
        </div>
        <div style="float: left; margin-right: 15px">
            <div style="width: 200px; margin: 0 auto;">
                <asp:Label ID="FinalName" runat="server" Text=""></asp:Label><br />
            </div>
            <asp:Image ID="FinalImage" runat="server" Width="500px" Height="300px" ImageUrl="Images/White-Image.png" /><br />
            <br />
            <asp:Button runat="server" ID="TagShare" Text="Tag & Share" Width="150px" OnClick="TagShare_Click" />&nbsp;&nbsp;&nbsp;
        <asp:Button runat="server" Text="Share Photo" ID="SharePhoto" Width="150px" OnClick="SharePhoto_Click" />&nbsp;&nbsp;&nbsp;
        <asp:Button ID="ShareStatus" runat="server" Text="Update Status" Width="150px" OnClick="ShareStatus_Click" />
        </div>
        <div style="clear: both;">
            <asp:Label ID="Label3" runat="server" Text="Status: " Font-Bold="true"></asp:Label>
            <asp:Label ID="Status" runat="server" Text=""></asp:Label>
        </div>
    </form>
</body>
</html>

