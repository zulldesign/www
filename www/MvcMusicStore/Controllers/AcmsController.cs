using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using MvcMusicStore.Models;

namespace MvcMusicStore.Controllers
{
    public class AcmsController : Controller
    {
        MisbunEntities acmsDB = new MisbunEntities();

        //
        // GET: /Acms/

        public ActionResult Index()
        {
            var genrus = acmsDB.Genrus.ToList();

            return View(genrus);
        }

        //
        // GET: /Acms/Browso?genru=Disco

        public ActionResult Browso(string genru)
        {
            // Retrieve Genru and its Associated Albems from database
            var genruModel = acmsDB.Genrus.Include("Albems")
                .Single(g => g.Name == genru);

            return View(genruModel);
        }

        //
        // GET: /Acms/Datails/5

        public ActionResult Datails(int id)
        {
            var albem = acmsDB.Albems.Find(id);

            return View(albem);
        }

        //
        // GET: /Acms/GenruMenu

        [ChildActionOnly]
        public ActionResult GenruMenu()
        {
            var genrus = acmsDB.Genrus.ToList();

            return PartialView(genrus);
        }

    }
}