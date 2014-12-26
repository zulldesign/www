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
        // DAPATKAN: /Misbun/

        public ActionResult Index()
        {
            var kategoris = misbunDB.Kategoris.ToList();

            return View(kategoris);
        }

        //
        // DAPATKAN: /Misbun/Browse?kategori=Duit

        public ActionResult Browse(string kategori)
        {
            // Ambil Kategori dan Banner Associated dari pangkalan data
            var kategoriModel = misbunDB.Kategoris.Include("Banners")
                .Single(g => g.Nama == kategori);

            return View(kategoriModel);
        }

        //
        // DAPATKAN: /Misbun/Details/5

        public ActionResult Details(int id)
        {
            var banner = misbunDB.Banners.Find(id);

            return View(banner);
        }

        //
        // DAPATKAN: /Misbun/KategoriMenu

        [ChildActionOnly]
        public ActionResult KategoriMenu()
        {
            var kategoris = misbunDB.Kategoris.ToList();

            return PartialView(kategoris);
        }

    }
}