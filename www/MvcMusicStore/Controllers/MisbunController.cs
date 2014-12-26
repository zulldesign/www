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
    public class MisbunController : Controller
    {
        private MisbunEntities db = new MisbunEntities();

        //
        // GET: /Misbun/

        public ViewResult Index()
        {
            return View(db.Kategoris.ToList());
        }

        //
        // GET: /Misbun/Details/5

        public ViewResult Details(int id)
        {
            Kategori kategori = db.Kategoris.Find(id);
            return View(kategori);
        }

        //
        // GET: /Misbun/Create

        public ActionResult Create()
        {
            return View();
        } 

        //
        // POST: /Misbun/Create

        [HttpPost]
        public ActionResult Create(Kategori kategori)
        {
            if (ModelState.IsValid)
            {
                db.Kategoris.Add(kategori);
                db.SaveChanges();
                return RedirectToAction("Index");  
            }

            return View(kategori);
        }
        
        //
        // GET: /Misbun/Edit/5
 
        public ActionResult Edit(int id)
        {
            Kategori kategori = db.Kategoris.Find(id);
            return View(kategori);
        }

        //
        // POST: /Misbun/Edit/5

        [HttpPost]
        public ActionResult Edit(Kategori kategori)
        {
            if (ModelState.IsValid)
            {
                db.Entry(kategori).State = EntityState.Modified;
                db.SaveChanges();
                return RedirectToAction("Index");
            }
            return View(kategori);
        }

        //
        // GET: /Misbun/Delete/5
 
        public ActionResult Delete(int id)
        {
            Kategori kategori = db.Kategoris.Find(id);
            return View(kategori);
        }

        //
        // POST: /Misbun/Delete/5

        [HttpPost, ActionName("Delete")]
        public ActionResult DeleteConfirmed(int id)
        {            
            Kategori kategori = db.Kategoris.Find(id);
            db.Kategoris.Remove(kategori);
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