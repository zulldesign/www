//------------------------------------------------------------------------------
// <auto-generated>
//    This code was generated from a template.
//
//    Manual changes to this file may cause unexpected behavior in your application.
//    Manual changes to this file will be overwritten if the code is regenerated.
// </auto-generated>
//------------------------------------------------------------------------------

namespace www.Models
{
    using System;
    using System.Collections.Generic;
    
    public partial class Genre
    {
        public Genre()
        {
            this.Albums = new HashSet<Album>();
        }
    
        public int GenreId { get; set; }
        public string Name { get; set; }
        public string Description { get; set; }
    
        public virtual ICollection<Album> Albums { get; set; }
    }
}
