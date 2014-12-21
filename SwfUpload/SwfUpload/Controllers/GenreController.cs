using System;
using System.Collections.Generic;
using System.Data;
using System.Data.Entity;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using SwfUpload.Models;

namespace SwfUpload.Controllers
{
    [Authorize]
    public class GenreController : Controller
    {
        private MusicStoreEntities db = new MusicStoreEntities();

        //
        // GET: /Genre/

        public ViewResult Index()
        {
            return View(db.Genres.ToList());
        }

        //
        // GET: /Genre/Details/5

        public ViewResult Details(int id)
        {
            Genre genre = db.Genres.Find(id);
            return View(genre);
        }

        //
        // GET: /Genre/Create

        public ActionResult Create()
        {
            return View();
        } 

        //
        // POST: /Genre/Create

        [HttpPost]
        public ActionResult Create(Genre genre)
        {
            if (ModelState.IsValid)
            {
                db.Genres.Add(genre);
                db.SaveChanges();
                return RedirectToAction("Index");  
            }

            return View(genre);
        }
        
        //
        // GET: /Genre/Edit/5
 
        public ActionResult Edit(int id)
        {
            Genre genre = db.Genres.Find(id);
            return View(genre);
        }

        //
        // POST: /Genre/Edit/5

        [HttpPost]
        public ActionResult Edit(Genre genre)
        {
            if (ModelState.IsValid)
            {
                db.Entry(genre).State = EntityState.Modified;
                db.SaveChanges();
                return RedirectToAction("Index");
            }
            return View(genre);
        }

        //
        // GET: /Genre/Delete/5
 
        public ActionResult Delete(int id)
        {
            Genre genre = db.Genres.Find(id);
            return View(genre);
        }

        //
        // POST: /Genre/Delete/5

        [HttpPost, ActionName("Delete")]
        public ActionResult DeleteConfirmed(int id)
        {            
            Genre genre = db.Genres.Find(id);
            db.Genres.Remove(genre);
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