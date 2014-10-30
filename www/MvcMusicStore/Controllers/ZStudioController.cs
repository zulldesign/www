using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace MvcMusicStore.Controllers
{
    public class ZStudioController : Controller
    {
        //
        // GET: /ZStudio/

        public ActionResult Index()
        {
            return View();
        }

        //
        // GET: /ZStudio/Login

        public ActionResult Login()
        {
            return View();
        }

    }
}
