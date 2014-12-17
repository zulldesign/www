using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Data.Entity;

namespace MvcMovie.Models
{
    public class Leedhar_MvcMovie : DbContext
    {

        public DbSet<Upload> Upload { get; set; }
    }
}