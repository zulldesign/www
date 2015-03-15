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
        TekunEntities tekunDB = new TekunEntities();

        //
        // GET: /Tekun/        

        public ActionResult Index()
        {
            var genres = tekunDB.Genres.ToList();

            return View(genres);
        }

        //
        // GET: /Tekun/Browse?genre=Disco        

        public ActionResult Browse(string genre)
        {
            // Retrieve Genre and its Associated Albums from database
            var genreModel = tekunDB.Genres.Include("Albums")
                .Single(g => g.Name == genre);

            return View(genreModel);
        }

        //
        // GET: /Tekun/Details/5        

        public ActionResult Details(int id)
        {
            var album = tekunDB.Albums.Find(id);

            return View(album);
        }

        //
        // GET: /Tekun/CategoryMenu        

        [ChildActionOnly]
        public ActionResult GenreMenu()
        {
            var genres = tekunDB.Genres.ToList();

            return PartialView(genres);
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
