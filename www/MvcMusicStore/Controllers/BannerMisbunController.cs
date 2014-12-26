using System;
using System.Collections.Generic;
using System.Data;
using System.Data.Entity;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using MvcMusicStore.Models;

namespace MvcMusicStore.Controllers
{ 
    public class BannerMisbunController : Controller
    {
        private MisbunEntities db = new MisbunEntities();

        //
        // GET: /BannerMisbun/

        public ViewResult Index()
        {
            return View(db.Banners.ToList());
        }

        //
        // GET: /BannerMisbun/Details/5

        public ViewResult Details(int id)
        {
            Banner banner = db.Banners.Find(id);
            return View(banner);
        }

        //
        // GET: /BannerMisbun/Create

        public ActionResult Create()
        {
            return View();
        } 

        //
        // POST: /BannerMisbun/Create

        [HttpPost]
        public ActionResult Create(Banner banner)
        {
            if (ModelState.IsValid)
            {
                db.Banners.Add(banner);
                db.SaveChanges();
                return RedirectToAction("Index");  
            }

            return View(banner);
        }
        
        //
        // GET: /BannerMisbun/Edit/5
 
        public ActionResult Edit(int id)
        {
            Banner banner = db.Banners.Find(id);
            return View(banner);
        }

        //
        // POST: /BannerMisbun/Edit/5

        [HttpPost]
        public ActionResult Edit(Banner banner)
        {
            if (ModelState.IsValid)
            {
                db.Entry(banner).State = EntityState.Modified;
                db.SaveChanges();
                return RedirectToAction("Index");
            }
            return View(banner);
        }

        //
        // GET: /BannerMisbun/Delete/5
 
        public ActionResult Delete(int id)
        {
            Banner banner = db.Banners.Find(id);
            return View(banner);
        }

        //
        // POST: /BannerMisbun/Delete/5

        [HttpPost, ActionName("Delete")]
        public ActionResult DeleteConfirmed(int id)
        {            
            Banner banner = db.Banners.Find(id);
            db.Banners.Remove(banner);
            db.SaveChanges();
            return RedirectToAction("Index");
        }

        protected override void Dispose(bool disposing)
        {
            db.Dispose();
            base.Dispose(disposing);
        }
    }
}