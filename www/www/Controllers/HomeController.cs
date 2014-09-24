using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace www.Controllers
{
    public class HomeController : Controller
    {
        public ActionResult Index()
        {
            ViewBag.Message = "Modify this template to jump-start your ASP.NET MVC application.";

            return View();
        }

        public ActionResult About()
        {
            ViewBag.Message = "Your app description page.";

            return View();
        }

        public ActionResult Students()
        {
            ViewBag.Message = "Welcome to APPHB.";

            return View();
        }

        public ActionResult Courses()
        {
            ViewBag.Message = "Welcome to APPHB.";

            return View();
        }

        public ActionResult Instructors()
        {
            ViewBag.Message = "Welcome to APPHB.";

            return View();
        }

        public ActionResult Departments()
        {
            ViewBag.Message = "Welcome to APPHB.";

            return View();
        }

        public ActionResult Contact()
        {
            ViewBag.Message = "Your contact page.";

            return View();
        }
    }
}