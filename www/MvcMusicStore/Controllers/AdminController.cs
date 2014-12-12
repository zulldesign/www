using System;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using MvcMusicStore.Models;

namespace MvcMusicStore.Controllers
{
   
    public class AdminController : Controller
    {
        //
        // GET: /Admin/
        Leedhar_UploadFile _DB = new Leedhar_UploadFile();
        public ActionResult Index()
        {
            var client = new HttpClient();
            var response = client.GetAsync(Url.Action("gallery", "photo", null, Request.Url.Scheme)).Result;
            var value = response.Content.ReadAsStringAsync().Result;

            var result = JsonConvert.DeserializeObject<List<Photo>>(value);

            return View(result);
        }

        public ActionResult UploadFile()
        {
            ViewData["Success"] = "";
            return View();
        }

        [AcceptVerbs(HttpVerbs.Post)]
        public ActionResult UploadFile(string Title)
        {
            _DB.Upload.Add(new Upload() { Title = Title });
            _DB.SaveChanges();
           
            int Id = (from a in _DB.Upload

                      select a.Upload_id).Max();

            if (Id > 0)
            {
                if (Request.Files["file"].ContentLength > 0)
                {
                    string extension = System.IO.Path.GetExtension(Request.Files["file"].FileName);
                    string path1 = string.Format("{0}/{1}{2}", Server.MapPath("~/documents/Files"), Id, extension);
                    if (System.IO.File.Exists(path1))
                        System.IO.File.Delete(path1);

                    Request.Files["file"].SaveAs(path1);


                }
                ViewData["Success"] = "Success";
            }
            else
            {
                ViewData["Success"] = "Upload Failed";
            }
            return View();
        }


    }
}
