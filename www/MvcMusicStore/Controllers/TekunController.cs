using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using System.Configuration;
using MvcMusicStore.Models;

namespace MvcMusicStore.Controllers
{
    public class TekunController : Controller
    {
        BlogEntities blogDB = new BlogEntities();

        //
        // GET: /Blog/

        public ActionResult Index()
        {
            var categories = blogDB.Categories.ToList();

            return View(categories);
        }

        //
        // GET: /Blog/Browse?genre=Disco

        public ActionResult Browse(string category)
        {
            // Retrieve Category and its Associated Blogs from database
            var categoryModel = blogDB.Categories.Include("Blogs")
                .Single(g => g.Name == category);

            return View(categoryModel);
        }

        //
        // GET: /Blog/Details/5

        public ActionResult Details(int id)
        {
            var blog = blogDB.Blogs.Find(id);

            return View(blog);
        }

        //
        // GET: /Blog/CategoryMenu

        [ChildActionOnly]
        public ActionResult CategoryMenu()
        {
            var categories = blogDB.Categories.ToList();

            return PartialView(categories);
        }

        public ActionResult PostToPayPal(string item, string amount)
        {
            MvcMusicStore.Models.Tekun paypal = new Models.Tekun();
            paypal.cmd = "_xclick";
            paypal.business = ConfigurationManager.AppSettings["BusinessAccountKey"];

            bool useSandbox = Convert.ToBoolean(ConfigurationManager.AppSettings["UseSandbox"]);
            if (useSandbox)
                ViewBag.actionURl = "https://www.sandbox.paypal.com/cgi-bin/webscr";
            else
                ViewBag.actionURl = "https://www.paypal.com/cgi-bin/webscr";

            paypal.cancel_return = System.Configuration.ConfigurationManager.AppSettings["CancelURL"];
            paypal.@return = ConfigurationManager.AppSettings["ReturnURL"]; //+"&PaymentId=1"; you can append your order Id here
            paypal.notify_url = ConfigurationManager.AppSettings["NotifyURL"];// +"?PaymentId=1"; to maintain database logic 

            paypal.currency_code = ConfigurationManager.AppSettings["CurrencyCode"];

            paypal.item_name = item;
            paypal.amount = amount;
            return View(paypal);
        }
    }
}
