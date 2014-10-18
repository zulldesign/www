﻿<!DOCTYPE html>
<html data-ng-app="contactsapp">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>@ViewBag.Title</title>
    @Styles.Render("~/Content/themes/base/css", "~/Content/css")
    @Scripts.Render("~/bundles/modernizr")
</head>
<body>
    @RenderBody()

    @Scripts.Render("~/bundles/jquery", "~/bundles/extLibs", "~/bundles/localApp")
    @RenderSection("scripts", required: false)    
</body>
</html>
