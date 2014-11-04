using fb.Models;
using System;
using System.Collections.Generic;
using System.Configuration;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace fb.Controllers
{
    public class PayPalController : Controller
    {
        //[Authorize(Roles="Customers")]
        public ActionResult ValidateCommand(string product, string totalPrice)
        {
            bool useSandbox = Convert.ToBoolean(ConfigurationManager.AppSettings["IsSandbox"]);
            var paypal = new PayPalModel(useSandbox);

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
         
        //<add key="business" value="asrce2_1311074442_biz@gmail.com" />
        public ActionResult Index()
        {
            return View();
        }

        public ActionResult Software_Developer_Kit_untuk_ASPNet()
        {
            return View();
        }

        public ActionResult Flash_Filem_dalam_Visual_Studio()
        {
            return View();
        }

        public ActionResult Blog(string name, int numTimes = 1)
        {
            ViewBag.Message = " Blog " + name;
            ViewBag.NumTimes = numTimes;

            return View();
        }        

    }
}
