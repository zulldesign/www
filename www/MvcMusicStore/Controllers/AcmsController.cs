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
            var genres = acmsDB.Genres.ToList();

            return View(genres);
        }

        //
        // GET: /Acms/Browse?genre=Disco

        public ActionResult Browse(string genre)
        {
            // Retrieve Genre and its Associated Albums from database
            var genreModel = acmsDB.Genres.Include("Albums")
                .Single(g => g.Name == genre);

            return View(genreModel);
        }

        //
        // GET: /Acms/Details/5

        public ActionResult Details(int id)
        {
            var album = acmsDB.Albums.Find(id);

            return View(album);
        }

        //
        // GET: /Acms/GenreMenu

        [ChildActionOnly]
        public ActionResult GenreMenu()
        {
            var genres = acmsDB.Genres.ToList();

            return PartialView(genres);
        }

    }
}
