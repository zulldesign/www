using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace www.Controllers
{
    public class MvcController : Controller
    {
        //
        // GET: /Mvc/

        public ActionResult Index()
        {
            return View();
        }

        // 
        // GET: /Mvc/Welcome/ 

        public ActionResult Welcome(string name, int numTimes = 1)
        {
            ViewBag.Message = "Hello " + name;
            ViewBag.NumTimes = numTimes;

            return View();
        }

    }
}
