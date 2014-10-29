using System;
using System.Collections.Generic;
using System.Web;

namespace www.Models.Abstract
{
    public interface ICategoriesRepository
    {
        Category Get(int id);
        IEnumerable<Category> GetAll();
        Category Add(Category category);
        Category Update(Category review);
        void Delete(int reviewId);
        Category GetByName(string category);
    }
}