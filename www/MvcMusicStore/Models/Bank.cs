using System;
using System.Data.Entity;

namespace MvcMusicStore.Models
{
    public class Customer
    {
        public int CustomerID { get; set; }

        public string FirstName { get; set; }
        public string LastName { get; set; }
        public string Title { get; set; }
        public string HomePhone { get; set; }
    }

    public class BankContext : DbContext
    {
        public DbSet<Customer> Customers { get; set; }
    }
}