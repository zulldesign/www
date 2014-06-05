<!--##SESSION ntkmenuconfig##-->
var oMenu_root = new Menu();

<!--%$MENUS-->

<!--%$COND CustomQueries/SubMenuCount/GT/0-->
var oMenu_<!--%=MenuId--> = oMenu_root.CreateMenu();
oMenu_<!--%=MenuId-->.displayHtml = "<!--%=MenuLink-->";
//oMenu_<!--%=MenuId-->.href = NTK_RootPath + "<!--%=MenuFn-->";
oMenu_<!--%=MenuParentId-->.AddItem(oMenu_<!--%=MenuId-->);
<!--%$/COND-->
<!--%$COND CustomQueries/SubMenuCount/EQ/0-->
var oMenu_<!--%=MenuId--> = oMenu_root.CreateLink();
oMenu_<!--%=MenuId-->.displayHtml = "<!--%=MenuLink-->";
oMenu_<!--%=MenuId-->.href = NTK_RootPath + "<!--%=MenuFn-->";
oMenu_<!--%=MenuParentId-->.AddItem(oMenu_<!--%=MenuId-->);
<!--%$/COND-->

<!--%$/MENUS-->

oMenu_root.SetOrientation("v");
//oMenu_root.SetSubMenuImage("images/flyout_arrow.gif", 4, 7);
oMenu_root.SetSize(150, 20);
<!--##/SESSION##-->