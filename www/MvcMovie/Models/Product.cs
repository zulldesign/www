using System;
using System.Data.Entity;

namespace MvcMusicStore.Models
{
    public class Product
    {
        public int ID { get; set; }
        public string Name { get; set; }
        public DateTime ReleaseDate { get; set; }
        public string Category { get; set; }
        public decimal Price { get; set; }
    }

    public class StoreContext : DbContext
    {
        public DbSet<Product> Products { get; set; }
    }
}