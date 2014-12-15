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
    public class BlogController : Controller
    {
        private BlogEntities db = new BlogEntities();

        //
        // GET: /Blog/

        public ViewResult Index()
        {
            var blogs = db.Blogs.Include(b => b.Product).Include(b => b.Category);
            return View(blogs.ToList());
        }

        //
        // GET: /Blog/Details/5

        public ViewResult Details(int id)
        {
            Blog blog = db.Blogs.Find(id);
            return View(blog);
        }

        //
        // GET: /Blog/Create

        public ActionResult Create()
        {
            ViewBag.ProductId = new SelectList(db.Products, "ProductId", "Name");
            ViewBag.CategoryId = new SelectList(db.Categories, "CategoryId", "Name");
            return View();
        } 

        //
        // POST: /Blog/Create

        [HttpPost]
        public ActionResult Create(Blog blog)
        {
            if (ModelState.IsValid)
            {
                db.Blogs.Add(blog);
                db.SaveChanges();
                return RedirectToAction("Index");  
            }

            ViewBag.ProductId = new SelectList(db.Products, "ProductId", "Name", blog.ProductId);
            ViewBag.CategoryId = new SelectList(db.Categories, "CategoryId", "Name", blog.CategoryId);
            return View(blog);
        }
        
        //
        // GET: /Blog/Edit/5
 
        public ActionResult Edit(int id)
        {
            Blog blog = db.Blogs.Find(id);
            ViewBag.ProductId = new SelectList(db.Products, "ProductId", "Name", blog.ProductId);
            ViewBag.CategoryId = new SelectList(db.Categories, "CategoryId", "Name", blog.CategoryId);
            return View(blog);
        }

        //
        // POST: /Blog/Edit/5

        [HttpPost]
        public ActionResult Edit(Blog blog)
        {
            if (ModelState.IsValid)
            {
                db.Entry(blog).State = EntityState.Modified;
                db.SaveChanges();
                return RedirectToAction("Index");
            }
            ViewBag.ProductId = new SelectList(db.Products, "ProductId", "Name", blog.ProductId);
            ViewBag.CategoryId = new SelectList(db.Categories, "CategoryId", "Name", blog.CategoryId);
            return View(blog);
        }

        //
        // GET: /Blog/Delete/5
 
        public ActionResult Delete(int id)
        {
            Blog blog = db.Blogs.Find(id);
            return View(blog);
        }

        //
        // POST: /Blog/Delete/5

        [HttpPost, ActionName("Delete")]
        public ActionResult DeleteConfirmed(int id)
        {            
            Blog blog = db.Blogs.Find(id);
            db.Blogs.Remove(blog);
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