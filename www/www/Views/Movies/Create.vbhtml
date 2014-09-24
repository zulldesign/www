﻿@ModelType www.Movie

@Code
    ViewData("Title") = "Create"
End Code

<h2>Create</h2>

@Using Html.BeginForm()
    @Html.AntiForgeryToken()
    @Html.ValidationSummary(True)

    @<fieldset>
        <legend>Movie</legend>

        <div class="editor-label">
            @Html.LabelFor(Function(model) model.Title)
        </div>
        <div class="editor-field">
            @Html.EditorFor(Function(model) model.Title)
            @Html.ValidationMessageFor(Function(model) model.Title)
        </div>

        <div class="editor-label">
            @Html.LabelFor(Function(model) model.ReleaseDate)
        </div>
        <div class="editor-field">
            @Html.EditorFor(Function(model) model.ReleaseDate)
            @Html.ValidationMessageFor(Function(model) model.ReleaseDate)
        </div>

        <div class="editor-label">
            @Html.LabelFor(Function(model) model.Genre)
        </div>
        <div class="editor-field">
            @Html.EditorFor(Function(model) model.Genre)
            @Html.ValidationMessageFor(Function(model) model.Genre)
        </div>

        <div class="editor-label">
            @Html.LabelFor(Function(model) model.Price)
        </div>
        <div class="editor-field">
            @Html.EditorFor(Function(model) model.Price)
            @Html.ValidationMessageFor(Function(model) model.Price)
        </div>

        <p>
            <input type="submit" value="Create" />
        </p>
    </fieldset>
End Using

<div>
    @Html.ActionLink("Back to List", "Index")
</div>

@Section Scripts
    @Scripts.Render("~/bundles/jqueryval")
End Section
