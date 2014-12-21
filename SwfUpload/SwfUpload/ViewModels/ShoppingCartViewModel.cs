using System.Collections.Generic;
using SwfUpload.Models;

namespace SwfUpload.ViewModels
{
    public class ShoppingCartViewModel
    {
        public List<Cart> CartItems { get; set; }
        public decimal CartTotal { get; set; }
    }
}