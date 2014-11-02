// Add any fields you want to be saved for each user and specify the field name in the JSON coming back from Facebook
// https://developers.facebook.com/docs/reference/api/user/

namespace SampleFacebookApp.Models
{
    public class RecommendedItem
    {
        public Product Product { get; set; }

        public string Reason { get; set; }
    }
}