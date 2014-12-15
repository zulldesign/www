using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.ComponentModel.DataAnnotations;

namespace MvcMusicStore.Models
{
    public class Upload
    {
        [Key]
        public virtual int Upload_id { get; set; }
        [Required]
        public virtual string Title { get; set; }

    }
}