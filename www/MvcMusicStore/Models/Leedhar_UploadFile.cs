using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Data.Entity;

namespace MvcMusicStore.Models
{
    public class Leedhar_UploadFile : DbContext
    {

        public DbSet<Upload> Upload { get; set; }
    }
}