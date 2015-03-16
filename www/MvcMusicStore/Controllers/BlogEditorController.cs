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
    public class BlogEditorController : Controller
    {
        private BlogEntities db = new BlogEntities();

        //
        // GET: /BlogEditor/

        public ViewResult Index()
        {
            var blogs = db.Blogs.Include(b => b.Product).Include(b => b.Category);
            return View(blogs.ToList());
        }

        //
        // GET: /BlogEditor/Details/5

        public ViewResult Details(int id)
        {
            Blog blog = db.Blogs.Find(id);
            return View(blog);
        }

        //
        // GET: /BlogEditor/Create

        public ActionResult Create()
        {
            ViewBag.ProductId = new SelectList(db.Products, "ProductId", "Name");
            ViewBag.CategoryId = new SelectList(db.Categories, "CategoryId", "Name");
            return View();
        } 

        //
        // POST: /BlogEditor/Create

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
        // GET: /BlogEditor/Edit/5
 
        public ActionResult Edit(int id)
        {
            Blog blog = db.Blogs.Find(id);
            ViewBag.ProductId = new SelectList(db.Products, "ProductId", "Name", blog.ProductId);
            ViewBag.CategoryId = new SelectList(db.Categories, "CategoryId", "Name", blog.CategoryId);
            return View(blog);
        }

        //
        // POST: /BlogEditor/Edit/5

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
        // GET: /BlogEditor/Delete/5
 
        public ActionResult Delete(int id)
        {
            Blog blog = db.Blogs.Find(id);
            return View(blog);
        }

        //
        // POST: /BlogEditor/Delete/5

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