﻿using System;
using System.Configuration;
using System.Net.Http;
using System.Threading.Tasks;
using SampleFacebookApp.Models;

namespace SampleFacebookApp
{
    public static class ShoppingSearchClient
    {
        private const string SearchApiTemplate = "https://www.googleapis.com/shopping/search/v1/public/products?key={0}&country=US&q={1}&alt=json";
        private static HttpClient client = new HttpClient();

        public static string AppKey = ConfigurationManager.AppSettings["uid6336-25643656-80"];

        public static Task<SearchResult> GetProductsAsync(string query)
        {
            if (String.IsNullOrEmpty(AppKey))
            {
                throw new InvalidOperationException("uid6336-25643656-80");
            }

            query = query.Replace(" ", "+");
            string searchQuery = String.Format(SearchApiTemplate, AppKey, query);
            var response = client.GetAsync(searchQuery).Result.EnsureSuccessStatusCode();
            return response.Content.ReadAsAsync<SearchResult>();
        }
    }
}
