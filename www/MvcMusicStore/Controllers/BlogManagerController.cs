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
    [Authorize]
    public class BlogManagerController : Controller
    {
        private BlogEntities db = new BlogEntities();

        //
        // GET: /BlogManager/

        public ViewResult Index()
        {
            var blogs = db.Blogs.Include(a => a.Product).Include(a => a.Category);
            return View(blogs.ToList());
        }

        //
        // GET: /BlogManager/Details/5

        public ViewResult Details(int id)
        {
            Blog blog = db.Blogs.Find(id);
            return View(blog);
        }

        //
        // GET: /BlogManager/Create

        public ActionResult Create()
        {
            ViewBag.ProductId = new SelectList(db.Products, "ProductId", "Name");
            ViewBag.CategoryId = new SelectList(db.Categories, "CategoryId", "Name");
            return View();
        } 

        //
        // POST: /BlogManager/Create

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
        // GET: /BlogManager/Edit/5
 
        public ActionResult Edit(int id)
        {
            Blog blog = db.Blogs.Find(id);
            ViewBag.ProductId = new SelectList(db.Products, "ProductId", "Name", blog.ProductId);
            ViewBag.CategoryId = new SelectList(db.Categories, "CategoryId", "Name", blog.CategoryId);
            return View(blog);
        }

        //
        // POST: /BlogManager/Edit/5

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
        // GET: /BlogManager/Delete/5
 
        public ActionResult Delete(int id)
        {
            Blog blog = db.Blogs.Find(id);
            return View(blog);
        }

        //
        // POST: /BlogManager/Delete/5

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