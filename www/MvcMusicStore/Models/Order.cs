//------------------------------------------------------------------------------
// <auto-generated>
//    This code was generated from a template.
//
//    Manual changes to this file may cause unexpected behavior in your application.
//    Manual changes to this file will be overwritten if the code is regenerated.
// </auto-generated>
//------------------------------------------------------------------------------

namespace MvcMusicStore.Models
{
    using System;
    using System.Collections.Generic;
    
    public partial class Order
    {
        public int OrderId { get; set; }
        public System.DateTime OrderTarikh { get; set; }
        public string NamaPengguna { get; set; }
        public string NamaPertama { get; set; }
        public string NamaAkhir { get; set; }
        public string Alamat { get; set; }
        public string Bandar { get; set; }
        public string Negeri { get; set; }
        public string Poskod { get; set; }
        public string Negara { get; set; }
        public string Telefon { get; set; }
        public string Emel { get; set; }
        public decimal Jumlah { get; set; }
    }
}
