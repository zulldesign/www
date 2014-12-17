using System.Collections.Generic;
using MvcMovie.Models;

namespace MvcMovie.ViewModels
{
    public class ShoppingCartViewModel
    {
        public List<Cart> CartItems { get; set; }
        public decimal CartTotal { get; set; }
    }
}