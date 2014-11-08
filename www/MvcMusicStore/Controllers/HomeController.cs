// ----------------------------------------------------------------------------------
// Microsoft Developer & Platform Evangelism
// 
// Copyright (c) Microsoft Corporation. All rights reserved.
// 
// THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY KIND, 
// EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE IMPLIED WARRANTIES 
// OF MERCHANTABILITY AND/OR FITNESS FOR A PARTICULAR PURPOSE.
// ----------------------------------------------------------------------------------
// The example companies, organizations, products, domain names,
// e-mail addresses, logos, people, places, and events depicted
// herein are fictitious.  No association with any real company,
// organization, product, domain name, email address, logo, person,
// places, or events is intended or should be inferred.
// ----------------------------------------------------------------------------------

namespace MvcMusicStore.Controllers
{
    using System.Web;
    using System.Web.Mvc;
    using MvcMusicStore.Models;

    public class HomeController : Controller
    {
        // GET: /Home/
        public ActionResult Index()
        {
            return this.View();
        }

        // GET: /Store/Browse
        public string Browse(string genre)
        {
            string message = HttpUtility.HtmlEncode("Store.Browse, Genre = " + genre);

            return message;
        }

        // GET: /Store/Details
        public ActionResult Details(int id)
        {
            var album = new Album { Title = "Album " + id };

            return this.View(album);
        }
    }
}
