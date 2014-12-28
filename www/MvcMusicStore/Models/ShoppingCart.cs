using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace MvcMusicStore.Models
{
    public partial class ShoppingCart
    {
        BlogEntities blogDB = new BlogEntities();

        string ShoppingCartId { get; set; }

        public const string CartSessionKey = "CartId";

        public static ShoppingCart GetCart(HttpContextBase context)
        {
            var cart = new ShoppingCart();
            cart.ShoppingCartId = cart.GetCartId(context);
            return cart;
        }

        // Helper method to simplify shopping cart calls
        public static ShoppingCart GetCart(Controller controller)
        {
            return GetCart(controller.HttpContext);
        }

        public void AddToCart(Blog blog)
        {
            // Get the matching cart and blog instances
            var cartItem = blogDB.Carts.SingleOrDefault(
c => c.CartId == ShoppingCartId
&& c.BlogId == blog.BlogId);

            if (cartItem == null)
            {
                // Create a new cart item if no cart item exists
                cartItem = new Cart
                {
                    BlogId = blog.BlogId,
                    CartId = ShoppingCartId,
                    Count = 1,
                    DateCreated = DateTime.Now
                };

                blogDB.Carts.Add(cartItem);
            }
            else
            {
                // If the item does exist in the cart, then add one to the quantity
                cartItem.Count++;
            }

            // Save changes
            blogDB.SaveChanges();
        }

        public int RemoveFromCart(int id)
        {
            // Get the cart
            var cartItem = blogDB.Carts.Single(
cart => cart.CartId == ShoppingCartId
&& cart.RecordId == id);

            int itemCount = 0;

            if (cartItem != null)
            {
                if (cartItem.Count > 1)
                {
                    cartItem.Count--;
                    itemCount = cartItem.Count;
                }
                else
                {
                    blogDB.Carts.Remove(cartItem);
                }

                // Save changes
                blogDB.SaveChanges();
            }

            return itemCount;
        }

        public void EmptyCart()
        {
            var cartItems = blogDB.Carts.Where(cart => cart.CartId == ShoppingCartId);

            foreach (var cartItem in cartItems)
            {
                blogDB.Carts.Remove(cartItem);
            }

            // Save changes
            blogDB.SaveChanges();
        }

        public List<Cart> GetCartItems()
        {
            return blogDB.Carts.Where(cart => cart.CartId == ShoppingCartId).ToList();
        }

        public int GetCount()
        {
            // Get the count of each item in the cart and sum them up
            int? count = (from cartItems in blogDB.Carts
                          where cartItems.CartId == ShoppingCartId
                          select (int?)cartItems.Count).Sum();

            // Return 0 if all entries are null
            return count ?? 0;
        }

        public decimal GetTotal()
        {
            // Multiply blog price by count of that blog to get 
            // the current price for each of those blogs in the cart
            // sum all blog price totals to get the cart total
            decimal? total = (from cartItems in blogDB.Carts
                              where cartItems.CartId == ShoppingCartId
                              select (int?)cartItems.Count * cartItems.Blog.Price).Sum();
            return total ?? decimal.Zero;
        }

        public int CreateOrder(Order order)
        {
            decimal orderTotal = 0;

            var cartItems = GetCartItems();

            // Iterate over the items in the cart, adding the order details for each
            foreach (var item in cartItems)
            {
                var orderDetail = new OrderDetail
                {
                    BlogId = item.BlogId,
                    OrderId = order.OrderId,
                    UnitPrice = item.Blog.Price,
                    Quantity = item.Count
                };

                // Set the order total of the shopping cart
                orderTotal += (item.Count * item.Blog.Price);

                blogDB.OrderDetails.Add(orderDetail);

            }

            // Set the order's total to the orderTotal count
            order.Total = orderTotal;

            // Save the order
            blogDB.SaveChanges();

            // Empty the shopping cart
            EmptyCart();

            // Return the OrderId as the confirmation number
            return order.OrderId;
        }

        // We're using HttpContextBase to allow access to cookies.
        public string GetCartId(HttpContextBase context)
        {
            if (context.Session[CartSessionKey] == null)
            {
                if (!string.IsNullOrWhiteSpace(context.User.Identity.Name))
                {
                    context.Session[CartSessionKey] = context.User.Identity.Name;
                }
                else
                {
                    // Generate a new random GUID using System.Guid class
                    Guid tempCartId = Guid.NewGuid();

                    // Send tempCartId back to client as a cookie
                    context.Session[CartSessionKey] = tempCartId.ToString();
                }
            }

            return context.Session[CartSessionKey].ToString();
        }

        // When a user has logged in, migrate their shopping cart to
        // be associated with their username
        public void MigrateCart(string userName)
        {
            var shoppingCart = blogDB.Carts.Where(c => c.CartId == ShoppingCartId);

            foreach (Cart item in shoppingCart)
            {
                item.CartId = userName;
            }
            blogDB.SaveChanges();
        }
    }
}