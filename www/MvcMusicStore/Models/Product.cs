using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Data.Entity;

namespace MvcMusicStore.Models
{
    public class Product
    {
        public int Id { get; set; }
        public string ProductName { get; set; }        
    }

    public class Category
    {
        public int Id { get; set; }
        public string CategoryName { get; set; }        
    }

    public class StoresContext : DbContext
    {
        public DbSet<Product> Products { get; set; }
        public DbSet<Category> Categories { get; set; }
    }
}