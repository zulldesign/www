using System.Collections.Generic;
using System.Linq;
using System.Web.Mvc;

namespace www.Models.Abstract
{
    public interface IReviewRepository
    {
        Review Get(int id);
        IQueryable<Review> GetAll();
        Review Add(Review review);
        Review Update(Review review);
        void Delete(int reviewId);
        IEnumerable<Review> GetByCategory(Category category);
        IEnumerable<Comment> GetReviewComments(int id);
    }
}