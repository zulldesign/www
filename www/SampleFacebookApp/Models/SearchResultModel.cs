namespace SampleFacebookApp.Models
{
    public class SearchResult
    {
        public Item[] items { get; set; }
    }

    public class Item
    {
        public Product product { get; set; }
    }

    public class Product
    {
        public string Title { get; set; }

        public string Description { get; set; }

        public string Link { get; set; }

        public ProductImage[] Images { get; set; }

        public ProductInventory[] Inventories { get; set; }
    }

    public class ProductImage
    {
        public string link { get; set; }
    }

    public class ProductInventory
    {
        public double Price { get; set; }
    }
}