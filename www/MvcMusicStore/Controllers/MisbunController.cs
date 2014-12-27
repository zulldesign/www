using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using MvcMusicStore.Models;

namespace MvcMusicStore.Controllers
{
    public class MisbunController : Controller
    {
        MisbunEntities misbunDB = new MisbunEntities();

        //
        // GET: /Misbun/

        public ActionResult Index()
        {
            var genres = misbunDB.Genres.ToList();

            return View(genres);
        }

        //
        // GET: /Misbun/Browse?genre=Disco

        public ActionResult Browse(string genre)
        {
            // Retrieve Genre and its Associated Albums from database
            var genreModel = misbunDB.Genres.Include("Albums")
                .Single(g => g.Name == genre);

            return View(genreModel);
        }

        //
        // GET: /Misbun/Details/5

        public ActionResult Details(int id)
        {
            var album = misbunDB.Albums.Find(id);

            return View(album);
        }

        //
        // GET: /Misbun/GenreMenu

        [ChildActionOnly]
        public ActionResult GenreMenu()
        {
            var genres = misbunDB.Genres.ToList();

            return PartialView(genres);
        }

    }
}