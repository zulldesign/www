using www.Models;
using System;
using System.Collections.Generic;
using System.Configuration;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace www.Controllers
{
    public class KNPR2C9H4G4KQ6MHC4YKM96XRController : Controller
    {
        //[Authorize(Roles="Customers")]
        public ActionResult ValidateCommand(string product, string totalPrice)
        {
            bool useSandbox = Convert.ToBoolean(ConfigurationManager.AppSettings["IsSandbox"]);
            var paypal = new KNPR2C9H4G4KQ6MHC4YKM96XRModel(useSandbox);

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

        public ActionResult Index()
        {
            return View();
        }

        //<add key="business" value="asrce2_1311074442_biz@gmail.com" />
    }
}
