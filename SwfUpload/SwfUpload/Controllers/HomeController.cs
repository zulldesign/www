using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace SwfUpload.Controllers
{
    public class HomeController : Controller
    {
        /// <summary>
        /// Multiple file upload with asp.net mvc and HTML5
        /// </summary>
        /// <returns></returns>
        public ActionResult Index()
        {
            return View();
        }
        /// <summary>
        /// Post method for uploading files
        /// </summary>
        /// <param name="files"></param>
        /// <returns></returns>
        [HttpPost]
        public ActionResult Index(HttpPostedFileBase[] files)
        {
            try
            {
                /*Lopp for multiple files*/
                foreach (HttpPostedFileBase file in files)
                {
                    /*Geting the file name*/
                    string filename = System.IO.Path.GetFileName(file.FileName);
                    /*Saving the file in server folder*/
                    file.SaveAs(Server.MapPath("~/Images/" + filename));
                    string filepathtosave = "Images/" + filename;
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