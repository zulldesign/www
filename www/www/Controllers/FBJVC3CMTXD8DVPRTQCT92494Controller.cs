using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace www.Controllers
{
    public class FBJVC3CMTXD8DVPRTQCT92494Controller : Controller
    {
        //
        // GET: /FBJVC3CMTXD8DVPRTQCT92494/

        public ActionResult ValidateCommand(string product, string totalPrice)
        {
            bool useSandbox = Convert.ToBoolean(ConfigurationManager.AppSettings["IsSandbox"]);
            var paypal = new FBJVC3CMTXD8DVPRTQCT92494Model(useSandbox);

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

        //<add key="business" value="admin@zulldesign.ml" />
    }
}
