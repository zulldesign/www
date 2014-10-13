using Facebook;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

public partial class _Default : System.Web.UI.Page
{
    //string FBName;
    //string SportsPerson;

    protected void Page_Load(object sender, EventArgs e)
    {
        if (!IsPostBack)
        {
            // Check if already Signed In
            if (Session["AccessToken"] != null)
            {
                // Retrieve user information from database if stored or else create a new FacebookClient with this accesstoken and extract data again.
                GetUserData(Session["AccessToken"].ToString());

            }
            // Check if redirected from facebook
            else if (Request.QueryString["code"] != null)
            {
                string accessCode = Request.QueryString["code"].ToString();

                var fb = new FacebookClient();

                // throws OAuthException 
                dynamic result = fb.Post("oauth/access_token", new
                {

                    client_id = "app_id",

                    client_secret = "app_secret",

                    redirect_uri = "redirect_uri",

                    code = accessCode

                });

                Status.Text = "Logged in. Your session expires in " + result.expires + "ms";

                // Store the access token in the session
                Session["AccessToken"] = result.access_token;

                GetUserData(result.access_token);
            }

            else if (Request.QueryString["error"] != null)
            {
                // Notify the user as you like
                string error = Request.QueryString["error"];
                string errorResponse = Request.QueryString["error_reason"];
                string errorDescription = Request.QueryString["error_description"];

                Status.Text = errorDescription;
            }

            else
            {
                // User not connected, ask them to sign in again
                Status.Text = "Not Signed In";
            }
        }
    }

    protected void Login_Click(object sender, EventArgs e)
    {
        if (Login.Text == "Log Out")
            logout();
        else
        {
            var fb = new FacebookClient();

            var loginUrl = fb.GetLoginUrl(new
            {

                client_id = "app_id",

                redirect_uri = "redirect_uri",

                response_type = "code",

                scope = "email,user_likes,publish_stream" // Add other permissions as needed

            });
            Response.Redirect(loginUrl.AbsoluteUri);
        }
    }

    private void logout()
    {
        var fb = new FacebookClient();

        var logoutUrl = fb.GetLogoutUrl(new
        {
            access_token = Session["AccessToken"],

            next = "redirect_uri"

        });

        Session.Remove("AccessToken");

        Response.Redirect(logoutUrl.AbsoluteUri);
    }

    protected void ShareStatus_Click(object sender, EventArgs e)
    {
        var fb = new FacebookClient(Session["AccessToken"].ToString());

        var parameters = new Dictionary<string, object>();
        parameters["message"] = "If " + ViewState["FBName"] + " was a sports person, he would have been " + ViewState["SportsPerson"];

        dynamic res = fb.Post("/me/feed", parameters);

        Status.Text = "Status Updated. Post ID: " + res.id;
    }

    protected void SharePhoto_Click(object sender, EventArgs e)
    {
        var fb = new FacebookClient(Session["AccessToken"].ToString());

        string sportsperson = ViewState["SportsPerson"].ToString();

        var parameters = new Dictionary<string, object>();
        parameters["name"] = "If " + ViewState["FBName"] + " was a sports person, he would have been " + sportsperson;
        parameters["TestPic"] = new FacebookMediaObject
        {
            ContentType = "image/jpeg",
            FileName = sportsperson + ".jpg"

        }.SetValue(File.ReadAllBytes(Server.MapPath("~\\Images\\" + sportsperson + ".jpg")));

        dynamic res = fb.Post("me/Photos", parameters);

        Status.Text = "Photo Uploaded. Photo ID: " + res.id;
    }

    protected void SharePage_Click(object sender, EventArgs e)
    {
        var fb = new FacebookClient(Session["AccessToken"].ToString());

        var parameters = new Dictionary<string, object>();
        parameters["link"] = "http://www.facebook.com/thepcwizardblog";       
   
        dynamic res = fb.Post("/me/feed", parameters);

        Status.Text = "Thank you for sharing. Post ID: " + res.id;
    }

    protected void TagShare_Click(object sender, EventArgs e)
    {
        List<ListItem> checkedItems = new List<ListItem>();

        foreach (ListItem item in FriendList.Items)
        {
            if (item.Selected)
            {
                checkedItems.Add(item);
            }
        }

        var fb = new FacebookClient(Session["AccessToken"].ToString());

        int x = 10, y = 10;
        UserTags[] tags = new UserTags[checkedItems.Count];

        for (int i = 0; i < checkedItems.Count; i++)
        {
            ListItem item = checkedItems[i];

            UserTags tag = new UserTags();
            tag.tag_uid = long.Parse(item.Value);
            tag.x = x;
            tag.y = y;

            tags[i] = tag;

            x += 10;
            y += 10;
        }

        string sportsperson = ViewState["SportsPerson"].ToString();

        var parameters = new Dictionary<string, object>();
        parameters["name"] = "If " + ViewState["FBName"] + " was a sports person, he would have been " + sportsperson;
        parameters["tags"] = tags;
        parameters["TestPic"] = new FacebookMediaObject
        {
            ContentType = "image/jpeg",
            FileName = sportsperson + ".jpg"

        }.SetValue(File.ReadAllBytes(Server.MapPath("~\\Images\\" + sportsperson + ".jpg")));

        dynamic res = fb.Post("me/Photos", parameters);

        Status.Text = "Photo Uploaded. Photo ID: " + res.id;
    }

    protected void Find_Click(object sender, EventArgs e)
    {
        if (Session["AccessToken"] != null)
        {
            Random rnd = new Random();
            int rnum = rnd.Next(SportsPersonList.Items.Count);

            string SportsPerson = SportsPersonList.Items[rnum].ToString();

            ViewState["SportsPerson"] = SportsPerson;

            FinalName.Text = SportsPerson;

            FinalImage.ImageUrl = "Images/" + SportsPerson + ".jpg";

            //Response.Write("Random Item: " + SportsPerson);
        }
        else
            Status.Text = "You need to login";
    }

    private void GetUserData(string accessToken)
    {
        var fb = new FacebookClient(accessToken);

        dynamic me = fb.Get("me?fields=friends,name,email,favorite_athletes");

        string id = me.id; // Store in database
        string email = me.email; // Store in database
        string FBName = me.name; // Store in database            

        NameText.Visible = true;
        NameText.Text = FBName;

        ViewState["FBName"] = FBName; // Storing User's Name in ViewState

        var friends = me.friends;

        foreach (var friend in (JsonArray)friends["data"])
        {
            ListItem item = new ListItem((string)(((JsonObject)friend)["name"]), (string)(((JsonObject)friend)["id"]));
            FriendList.Items.Add(item);
        }

        var athletes = me.favorite_athletes;

        foreach (var athlete in (JsonArray)athletes)
        {
            SportsPersonList.Items.Add((string)(((JsonObject)athlete)["name"]));
        }

        Login.Text = "Log Out";
    }

    protected void GiveFeedback_Click(object sender, EventArgs e)
    {
        var fb = new FacebookClient(Session["AccessToken"].ToString());

        var parameters = new Dictionary<string, object>();
        parameters["message"] = FeedbackText.Text;

        dynamic res = fb.Post("/page_id/feed", parameters);

        Status.Text = "Thanks for your feedback. Post ID: " + res.id;
    }

}