using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace www.Controllers
{
    public class TutorialsController : Controller
    {
        //
        // GET: /Tutorials/

        public ActionResult Index()
        {
            return View();
        }

        // 
        // GET: /Tutorials/Welcome/ 

        public ActionResult Welcome(string name, int numTimes = 1)
        {
            ViewBag.Message = "Hello " + name;
            ViewBag.NumTimes = numTimes;

            return View();
        }

    }
}
