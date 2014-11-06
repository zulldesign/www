﻿using System;
using System.Collections.Generic;
using System.Web;
using SageFrame.Web;
using SageFrame.Framework;
using AspxCommerce.Core;
using SageFrame.Core;

public partial class Modules_Admin_DetailsBrowse_ItemsListByIds : BaseAdministrationUserControl
{
    public int StoreID, PortalID, CustomerID;
    public string UserName, CultureName, UserIP, CountryName, DefaultShoppingOptionImgPath, AllowOutStockPurchase, AllowWishListItemsById;
    public string SessionCode = string.Empty;
    public string ItemIds = string.Empty;
    public int NoOfItemsInARow;
    public string ItemDisplayMode;
    public List<AspxTemplateKeyValue> AspxTemplateValue = new List<AspxTemplateKeyValue>();
    protected void Page_Load(object sender, EventArgs e)
    {
        try
        {
            if (!IsPostBack)
            {
                IncludeCss("ItemsListByIds", "/Templates/" + TemplateName + "/css/MessageBox/style.css", "/Templates/" + TemplateName + "/css/ToolTip/tooltip.css", "/Templates/" + TemplateName + "/css/FancyDropDown/fancy.css");
                IncludeJs("ItemsListByIds", "/js/MessageBox/jquery.easing.1.3.js", "/js/MessageBox/alertbox.js", "/js/jquery.tipsy.js", "/js/FancyDropDown/itemFancyDropdown.js", "/js/SageFrameCorejs/itemTemplateView.js");
                StoreID = GetStoreID;
                PortalID = GetPortalID;
                CustomerID = GetCustomerID;
                UserName = GetUsername;
                CultureName = GetCurrentCultureName;

                StoreSettingConfig ssc = new StoreSettingConfig();
                DefaultShoppingOptionImgPath = ssc.GetStoreSettingsByKey(StoreSetting.DefaultProductImageURL, StoreID, PortalID, CultureName);
                AllowOutStockPurchase = ssc.GetStoreSettingsByKey(StoreSetting.AllowOutStockPurchase, StoreID, PortalID, CultureName);
                AllowWishListItemsById = ssc.GetStoreSettingsByKey(StoreSetting.EnableWishList, StoreID, PortalID, CultureName);
                NoOfItemsInARow = int.Parse(ssc.GetStoreSettingsByKey(StoreSetting.NoOfDisplayItems, StoreID, PortalID, CultureName));
                ItemDisplayMode = ssc.GetStoreSettingsByKey(StoreSetting.ItemDisplayMode, StoreID, PortalID, CultureName);
                if (HttpContext.Current.Session.SessionID != null)
                {
                    SessionCode = HttpContext.Current.Session.SessionID.ToString();
                }

                UserIP = HttpContext.Current.Request.UserHostAddress;
                IPAddressToCountryResolver ipToCountry = new IPAddressToCountryResolver();
                ipToCountry.GetCountry(UserIP, out CountryName);

                 ItemIds = Request.QueryString["id"];
                 IncludeLanguageJS();
                GetAspxTemplates();
            }
            IncludeLanguageJS();

            
        }
        catch (Exception ex)
        {
            ProcessException(ex);
        }
    }

    protected void page_init(object sender, EventArgs e)
    {
        try
        {
            InitializeJS();
        }
        catch (Exception ex)
        {
            ProcessException(ex);
        }
    }

    private void InitializeJS()
    {
        Page.ClientScript.RegisterClientScriptInclude("template", ResolveUrl("~/js/Templating/tmpl.js"));
        Page.ClientScript.RegisterClientScriptInclude("J12", ResolveUrl("~/js/encoder.js"));
        Page.ClientScript.RegisterClientScriptInclude("Paging", ResolveUrl("~/js/Paging/jquery.pagination.js"));
        Page.ClientScript.RegisterClientScriptInclude("cookies", ResolveUrl("~/js/jquery.cookie.js"));
        Page.ClientScript.RegisterClientScriptInclude("aspxTemplate", ResolveUrl("~/js/Templating/AspxTemplate.js"));
  
    }

    private void GetAspxTemplates()
    {
        AspxTemplateValue = AspxGetTemplates.GetAspxTemplateKeyValue();

        foreach (AspxTemplateKeyValue value in AspxTemplateValue)
        {
            string xx = value.TemplateKey + "@" + value.TemplateValue;
            Page.ClientScript.RegisterArrayDeclaration("jsTemplateArray", "\'" + xx + "\'");
        }
    }
    
}
