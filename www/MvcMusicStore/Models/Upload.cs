using System;
using System.Data.Entity;

namespace MvcMusicStore.Models
{
    public class Upload
    {
        public int Upload_id { get; set; }
        public string Title { get; set; }
    }

    public class Leedhar_UploadFile : DbContext
    {
        public DbSet<Upload> Uploads { get; set; }
    }
}