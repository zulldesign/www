﻿using System;
using System.Web.Mvc;

namespace www.Helpers
{
    public static class LabelExtensions
    {
        public static string Label(this HtmlHelper helper, string target, string text)
        {
            return String.Format("<label for='{0}'>{1}</label>", target, text);

        }
    }
}