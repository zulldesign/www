using System;
using System.Collections.Generic;
using System.Data.Entity;
using System.Linq;
using System.Web;

namespace www.Models
{
    public class wwwContext : DbContext
    {
        public DbSet<Review> Reviews { get; set; }
        public DbSet<Category> Categories{ get; set; }
        public DbSet<Comment> Comments { get; set; }

        public wwwContext()
        {
            Configuration.ProxyCreationEnabled = false;
        }
    }
}