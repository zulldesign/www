using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using MvcMusicStore.Models;

namespace MvcMusicStore.Controllers {
    public class MisbunController : Controller {
        MisbunEntities misbunDB = new MisbunEntities();
        //
        // GET: /Store/

        public ActionResult Details()
        {
            return View();
        }

        public ActionResult Browse()
        {
            return View();
        }

        public ActionResult Index() 
        {
            var kategoris = misbunDB.Kategoris;
            return View(kategoris);
        }


        //
        // GET: /Store/GenreMenu

        [ChildActionOnly]
        public ActionResult GenreMenu()
        {
            var kategoris = misbunDB.Kategoris.Take(9).ToList();

            return PartialView(kategoris);
        }

    }
}
