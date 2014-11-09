using System.Data.Entity;

namespace MvcMusicStore.Models
{
    public class DefaultConnection : DbContext
    {
        public DbSet<Album> Albums { get; set; }
        public DbSet<Genre> Genres { get; set; }
        public DbSet<Artist> Artists { get; set; }        
    }
}