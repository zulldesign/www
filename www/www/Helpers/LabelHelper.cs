﻿using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace www.Helpers
{
    public class LabelHelper
    {
        public static string Label(string target, string text)
        {
            return String.Format("<label for='{0}'>{1}</label>", target, text);
        }
    }
}