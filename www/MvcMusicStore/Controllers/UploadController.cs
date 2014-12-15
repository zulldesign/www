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
    public class UploadController : Controller
    {
        private Leedhar_UploadFile db = new Leedhar_UploadFile();

        //
        // GET: /Upload/

        public ViewResult Index()
        {
            return View(db.Upload.ToList());
        }

        //
        // GET: /Upload/Details/5

        public ViewResult Details(int id)
        {
            Upload upload = db.Upload.Find(id);
            return View(upload);
        }

        //
        // GET: /Upload/Create

        public ActionResult Create()
        {
            return View();
        } 

        //
        // POST: /Upload/Create

        [HttpPost]
        public ActionResult Create(Upload upload)
        {
            if (ModelState.IsValid)
            {
                db.Upload.Add(upload);
                db.SaveChanges();
                return RedirectToAction("Index");  
            }

            return View(upload);
        }
        
        //
        // GET: /Upload/Edit/5
 
        public ActionResult Edit(int id)
        {
            Upload upload = db.Upload.Find(id);
            return View(upload);
        }

        //
        // POST: /Upload/Edit/5

        [HttpPost]
        public ActionResult Edit(Upload upload)
        {
            if (ModelState.IsValid)
            {
                db.Entry(upload).State = EntityState.Modified;
                db.SaveChanges();
                return RedirectToAction("Index");
            }
            return View(upload);
        }

        //
        // GET: /Upload/Delete/5
 
        public ActionResult Delete(int id)
        {
            Upload upload = db.Upload.Find(id);
            return View(upload);
        }

        //
        // POST: /Upload/Delete/5

        [HttpPost, ActionName("Delete")]
        public ActionResult DeleteConfirmed(int id)
        {            
            Upload upload = db.Upload.Find(id);
            db.Upload.Remove(upload);
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