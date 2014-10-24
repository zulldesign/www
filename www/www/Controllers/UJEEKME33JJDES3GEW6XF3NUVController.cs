using www.Models;
using System;
using System.Collections.Generic;
using System.Configuration;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace www.Controllers
{
    public class UJEEKME33JJDES3GEW6XF3NUVController : Controller
    {
        //
        // GET: /UJEEKME33JJDES3GEW6XF3NUV/

        public ActionResult Index()
        {
            return View();
        }

        //[Authorize(Roles="Customers")]
        public ActionResult ValidateCommand(string product, string totalPrice)
        {
            bool useSandbox = Convert.ToBoolean(ConfigurationManager.AppSettings["IsSandbox"]);
            var paypal = new UJEEKME33JJDES3GEW6XF3NUVModel(useSandbox);

            paypal.item_name = product;
            paypal.amount = totalPrice;
            return View(paypal);
        }

        public ActionResult RedirectFromPaypal()
        {
            return View();
        }

        public ActionResult CancelFromPaypal()
        {
            return View();
        }

        public ActionResult NotifyFromPaypal()
        {
            return View();
        }

        //<add key="business" value="admin@zulldesign.ml" />
    }
}