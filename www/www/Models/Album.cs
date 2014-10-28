using System.ComponentModel;
using System.ComponentModel.DataAnnotations;
using System.Web.Mvc;
using System.Collections.Generic;

namespace www.Models
{
    public class Album
    {
        public string Title { get; set; }
        public Genre Genre { get; set; }
    }
}