using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using MvcMusicStore.Models;

namespace MvcMusicStore.Controllers
{
    public class HomeController : Controller
    {
        BlogEntities blogDB = new BlogEntities();

        public ActionResult Index()
        {
            // Get most popular blogs
            var blogs = GetTopSellingBlogs(5);

            return View(blogs);
        }

        private List<Blog> GetTopSellingBlogs(int count)
        {
            // Group the order details by blog and return
            // the blogs with the highest count

            return blogDB.Blogs
                .OrderByDescending(a => a.OrderDetails.Count())
                .Take(count)
                .ToList();
        }

        public ActionResult About()
        {
            return View();
        }
        /// <summary>
        /// Post method for uploading files
        /// </summary>
        /// <param name="files"></param>
        /// <returns></returns>
        [HttpPost]
        public ActionResult About(HttpPostedFileBase[] files)
        {
            try
            {
                /*Lopp for multiple files*/
                foreach (HttpPostedFileBase file in files)
                {
                    /*Geting the file name*/
                    string filename = System.IO.Path.GetFileName(file.FileName);
                    /*Saving the file in server folder*/
                    file.SaveAs(Server.MapPath("~/Content/Images/" + filename));
                    string filepathtosave = "Content/Images/" + filename;
                    /*HERE WILL BE YOUR CODE TO SAVE THE FILE DETAIL IN DATA BASE*/
                }

                ViewBag.Message = "File Uploaded successfully.";
            }
            catch
            {
                ViewBag.Message = "Error while uploading the files.";
            }
            return View();
        }
    }
}
