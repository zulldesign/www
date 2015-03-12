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
    public class CartController : Controller
    {
        private BlogEntities db = new BlogEntities();

        //
        // GET: /Cart/

        public ViewResult Index()
        {
            var carts = db.Carts.Include(c => c.Blog);
            return View(carts.ToList());
        }

        //
        // GET: /Cart/Details/5

        public ViewResult Details(int id)
        {
            Cart cart = db.Carts.Find(id);
            return View(cart);
        }

        //
        // GET: /Cart/Create

        public ActionResult Create()
        {
            ViewBag.BlogId = new SelectList(db.Blogs, "BlogId", "Title");
            return View();
        } 

        //
        // POST: /Cart/Create

        [HttpPost]
        public ActionResult Create(Cart cart)
        {
            if (ModelState.IsValid)
            {
                db.Carts.Add(cart);
                db.SaveChanges();
                return RedirectToAction("Index");  
            }

            ViewBag.BlogId = new SelectList(db.Blogs, "BlogId", "Title", cart.BlogId);
            return View(cart);
        }
        
        //
        // GET: /Cart/Edit/5
 
        public ActionResult Edit(int id)
        {
            Cart cart = db.Carts.Find(id);
            ViewBag.BlogId = new SelectList(db.Blogs, "BlogId", "Title", cart.BlogId);
            return View(cart);
        }

        //
        // POST: /Cart/Edit/5

        [HttpPost]
        public ActionResult Edit(Cart cart)
        {
            if (ModelState.IsValid)
            {
                db.Entry(cart).State = EntityState.Modified;
                db.SaveChanges();
                return RedirectToAction("Index");
            }
            ViewBag.BlogId = new SelectList(db.Blogs, "BlogId", "Title", cart.BlogId);
            return View(cart);
        }

        //
        // GET: /Cart/Delete/5
 
        public ActionResult Delete(int id)
        {
            Cart cart = db.Carts.Find(id);
            return View(cart);
        }

        //
        // POST: /Cart/Delete/5

        [HttpPost, ActionName("Delete")]
        public ActionResult DeleteConfirmed(int id)
        {            
            Cart cart = db.Carts.Find(id);
            db.Carts.Remove(cart);
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