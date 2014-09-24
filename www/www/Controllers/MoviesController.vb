Imports System.Data.Entity
Imports www

Namespace www
    Public Class MoviesController
        Inherits System.Web.Mvc.Controller

        Private db As MovieDBContext = New MovieDBContext

        '
        ' GET: /Movies/

        Function Index() As ViewResult
            Return View(db.Movies.ToList())
        End Function

        '
        ' GET: /Movies/Details/5

        Function Details(id As Integer) As ViewResult
            Dim movie As Movie = db.Movies.Find(id)
            Return View(movie)
        End Function

        '
        ' GET: /Movies/Create

        Function Create As ViewResult
            return View()
        End Function

        '
        ' POST: /Movies/Create

        <HttpPost()>
        Function Create(movie As Movie) As ActionResult
            If ModelState.IsValid Then
                db.Movies.Add(movie)
                db.SaveChanges()
                Return RedirectToAction("Index")
            End If

            Return View(movie)
        End Function
        
        '
        ' GET: /Movies/Edit/5
 
        Function Edit(id As Integer) As ViewResult
            Dim movie As Movie = db.Movies.Find(id)
            Return View(movie)
        End Function

        '
        ' POST: /Movies/Edit/5

        <HttpPost()>
        Function Edit(movie As Movie) As ActionResult
            If ModelState.IsValid Then
                db.Entry(movie).State = EntityState.Modified
                db.SaveChanges()
                Return RedirectToAction("Index")
            End If

            Return View(movie)
        End Function

        '
        ' GET: /Movies/Delete/5
 
        Function Delete(id As Integer) As ViewResult
            Dim movie As Movie = db.Movies.Find(id)
            Return View(movie)
        End Function

        '
        ' POST: /Movies/Delete/5

        <HttpPost()>
        <ActionName("Delete")>
        Function DeleteConfirmed(id As Integer) As RedirectToRouteResult
            Dim movie As Movie = db.Movies.Find(id)
            db.Movies.Remove(movie)
            db.SaveChanges()
            Return RedirectToAction("Index")
        End Function

        Protected Overrides Sub Dispose(disposing As Boolean)
            db.Dispose()
            MyBase.Dispose(disposing)
        End Sub

    End Class
End Namespace