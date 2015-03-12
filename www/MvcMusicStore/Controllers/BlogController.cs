using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using MvcMusicStore.Models;

namespace MvcMusicStore.Controllers
{
    public class BlogController : Controller
    {
        BlogEntities blogDB = new BlogEntities();

        //
        // GET: /Blog/

        public ActionResult Index()
        {
            var categories = blogDB.Categories.ToList();

            return View(categories);
        }

        //
        // GET: /Blog/Browse?genre=Disco

        public ActionResult Browse(string category)
        {
            // Retrieve Category and its Associated Blogs from database
            var categoryModel = blogDB.Categories.Include("Blogs")
                .Single(g => g.Name == category);

            return View(categoryModel);
        }

        //
        // GET: /Blog/Details/5

        public ActionResult Details(int id)
        {
            var blog = blogDB.Blogs.Find(id);

            return View(blog);
        }

        //
        // GET: /Blog/CategoryMenu

        [ChildActionOnly]
        public ActionResult CategoryMenu()
        {
            var categories = blogDB.Categories.ToList();

            return PartialView(categories);
        }

    }
}