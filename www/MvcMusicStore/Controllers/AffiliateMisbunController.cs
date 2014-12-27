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
    public class AffiliateMisbunController : Controller
    {
        private MisbunEntities db = new MisbunEntities();

        //
        // GET: /AffiliateMisbun/

        public ViewResult Index()
        {
            return View(db.Affiliates.ToList());
        }

        //
        // GET: /AffiliateMisbun/Details/5

        public ViewResult Details(int id)
        {
            Affiliate affiliate = db.Affiliates.Find(id);
            return View(affiliate);
        }

        //
        // GET: /AffiliateMisbun/Create

        public ActionResult Create()
        {
            return View();
        } 

        //
        // POST: /AffiliateMisbun/Create

        [HttpPost]
        public ActionResult Create(Affiliate affiliate)
        {
            if (ModelState.IsValid)
            {
                db.Affiliates.Add(affiliate);
                db.SaveChanges();
                return RedirectToAction("Index");  
            }

            return View(affiliate);
        }
        
        //
        // GET: /AffiliateMisbun/Edit/5
 
        public ActionResult Edit(int id)
        {
            Affiliate affiliate = db.Affiliates.Find(id);
            return View(affiliate);
        }

        //
        // POST: /AffiliateMisbun/Edit/5

        [HttpPost]
        public ActionResult Edit(Affiliate affiliate)
        {
            if (ModelState.IsValid)
            {
                db.Entry(affiliate).State = EntityState.Modified;
                db.SaveChanges();
                return RedirectToAction("Index");
            }
            return View(affiliate);
        }

        //
        // GET: /AffiliateMisbun/Delete/5
 
        public ActionResult Delete(int id)
        {
            Affiliate affiliate = db.Affiliates.Find(id);
            return View(affiliate);
        }

        //
        // POST: /AffiliateMisbun/Delete/5

        [HttpPost, ActionName("Delete")]
        public ActionResult DeleteConfirmed(int id)
        {            
            Affiliate affiliate = db.Affiliates.Find(id);
            db.Affiliates.Remove(affiliate);
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