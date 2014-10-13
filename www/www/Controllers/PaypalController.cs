using www.Models;
using System;
using System.Collections.Generic;
using System.Configuration;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace www.Controllers
{
    public class PaypalController : Controller
    {
        //[Authorize(Roles="Customers")]
        public ActionResult ValidateCommand(string product, string totalPrice)
        {
            bool useSandbox = Convert.ToBoolean(ConfigurationManager.AppSettings["IsSandbox"]);
            var Paypal = new PaypalModel(useSandbox);

            Paypal.item_name = product;
            Paypal.amount = totalPrice;
            return View(Paypal);
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

        //<add key="business" value="asrce2_1311074442_biz@gmail.com" />
        public ActionResult Index()
        {
            return View();
        }

    }
}
