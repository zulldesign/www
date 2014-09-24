Imports System.Data.Entity

Public Class MoviesController
    Inherits System.Web.Mvc.Controller

    Private db As New MovieDBContext

    '
    ' GET: /Movies/

    Function Index() As ActionResult
        Return View(db.Movies.ToList())
    End Function

    '
    ' GET: /Movies/Details/5

    Function Details(Optional ByVal id As Integer = Nothing) As ActionResult
        Dim movie As Movie = db.Movies.Find(id)
        If IsNothing(movie) Then
            Return HttpNotFound()
        End If
        Return View(movie)
    End Function

    '
    ' GET: /Movies/Create

    Function Create() As ActionResult
        Return View()
    End Function

    '
    ' POST: /Movies/Create

    <HttpPost()> _
    <ValidateAntiForgeryToken()> _
    Function Create(ByVal movie As Movie) As ActionResult
        If ModelState.IsValid Then
            db.Movies.Add(movie)
            db.SaveChanges()
            Return RedirectToAction("Index")
        End If

        Return View(movie)
    End Function

    '
    ' GET: /Movies/Edit/5

    Function Edit(Optional ByVal id As Integer = Nothing) As ActionResult
        Dim movie As Movie = db.Movies.Find(id)
        If IsNothing(movie) Then
            Return HttpNotFound()
        End If
        Return View(movie)
    End Function

    '
    ' POST: /Movies/Edit/5

    <HttpPost()> _
    <ValidateAntiForgeryToken()> _
    Function Edit(ByVal movie As Movie) As ActionResult
        If ModelState.IsValid Then
            db.Entry(movie).State = EntityState.Modified
            db.SaveChanges()
            Return RedirectToAction("Index")
        End If

        Return View(movie)
    End Function

    '
    ' GET: /Movies/Delete/5

    Function Delete(Optional ByVal id As Integer = Nothing) As ActionResult
        Dim movie As Movie = db.Movies.Find(id)
        If IsNothing(movie) Then
            Return HttpNotFound()
        End If
        Return View(movie)
    End Function

    '
    ' POST: /Movies/Delete/5

    <HttpPost()> _
    <ActionName("Delete")> _
    <ValidateAntiForgeryToken()> _
    Function DeleteConfirmed(ByVal id As Integer) As RedirectToRouteResult
        Dim movie As Movie = db.Movies.Find(id)
        db.Movies.Remove(movie)
        db.SaveChanges()
        Return RedirectToAction("Index")
    End Function

    Protected Overrides Sub Dispose(ByVal disposing As Boolean)
        db.Dispose()
        MyBase.Dispose(disposing)
    End Sub

End Class