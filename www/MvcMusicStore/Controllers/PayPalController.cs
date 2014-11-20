﻿using MvcMusicStore.Models;
using System;
using System.Collections.Generic;
using System.Configuration;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace MvcMusicStore.Controllers
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

         //<add key="business" value="admin@zulldesign.ml" />

        public ActionResult Index()
        {
            return View();
        }
    }
}