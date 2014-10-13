<!--##SESSION ntkcategoryconfig##-->
var oCat_root = new Menu();

<!--%$CATEGORIES-->

<!--%$COND CustomQueries/SubCategoryCount/GT/0-->
var oCat_<!--%=CategoryID--> = oCat_root.CreateMenu();
oCat_<!--%=CategoryID-->.displayHtml = "<!--%=CategoryName-->";
oCat_<!--%=CategoryParentID-->.AddItem(oCat_<!--%=CategoryID-->);
<!--%$/COND-->
<!--%$COND CustomQueries/SubCategoryCount/EQ/0-->
var oCat_<!--%=CategoryID--> = oCat_root.CreateLink();
oCat_<!--%=CategoryID-->.displayHtml = "<!--%=CategoryName-->";
oCat_<!--%=CategoryID-->.href = NTK_RootPath + "<!--%=CatFn-->";
oCat_<!--%=CategoryParentID-->.AddItem(oCat_<!--%=CategoryID-->);
<!--%$/COND-->

<!--%$/CATEGORIES-->

oCat_root.SetOrientation("v");
//oCat_root.SetSubMenuImage("images/flyout_arrow.gif", 4, 7);
oCat_root.SetSize(150, 20);
<!--##/SESSION##-->