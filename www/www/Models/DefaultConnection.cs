using System.Data.Entity;

namespace www.Models
{
    public class DefaultConnection : DbContext
    {
        public DbSet<Album> Albums { get; set; }
        public DbSet<Genre> Genres { get; set; }        
    }
}