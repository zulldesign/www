﻿<%@ Control Language="C#" AutoEventWireup="true" CodeFile="AdminDashBoard.ascx.cs"
    Inherits="Modules_AspxCommerce_AspxAdminDashBoard_AdminDashBoard" %>
<%@ Register Src="OrderOverViews.ascx" TagName="OrderOverViews" TagPrefix="uc1" %>
<%@ Register Src="TotalStoreRevenue.ascx" TagName="TotalStoreRevenue" TagPrefix="uc2" %>
<%@ Register Src="RecentReviewsAndRatings.ascx" TagName="RecentReviewsAndRatings"
    TagPrefix="uc3" %>
<%@ Register Src="LatestFiveOrderItems.ascx" TagName="LatestFiveOrderItems" TagPrefix="uc4" %>
<%@ Register Src="MostViewedItems.ascx" TagName="MostViewedItems" TagPrefix="uc5" %>
<%@ Register Src="TopCutomersByOrder.ascx" TagName="TopCutomersByOrder" TagPrefix="uc7" %>
<%@ Register Src="TopSearchTerms.ascx" TagName="TopSearchTerms" TagPrefix="uc8" %>
<%@ Register Src="CategoryQuantity.ascx" TagName="CategoryQuantity" TagPrefix="uc9" %>
<%@ Register Src="UserRecords.ascx" TagName="UserRecords" TagPrefix="uc12" %>
<%@ Register Src="InventoryDetails.ascx" TagName="InventoryDetails" TagPrefix="uc10" %>

<script type="text/javascript">
    $(document).ready(function() {
        var $tabs = $('#dvContainerTab').tabs({ fx: [null, { height: 'show', opacity: 'show'}] });
        $tabs.tabs('select', 0);
        $(".sfLocale").localize({
            moduleKey: AspxAdminDashBoard
        });
    });
    
</script>

<div class="sfFormwrapper">
    <div class="sfTimeSelection">
        <label class="sfLabel sfLocale">
            Select Time:</label>
        <label>
            <select id="ddlParentAllDropdown" class="sfSelect">
                <option value="1" selected="selected" class="sfLocale">Last 24 Hours</option>
                <option value="7" class="sfLocale">Last 7 Days</option>
                <option value="30" class="sfLocale">Last 30 Days</option>
                <option value="365" class="sfLocale">Last 365 Days</option>
            </select></label>
    </div>

    <script type="text/javascript">

        $("#ddlParentAllDropdown").bind("change", function() {
            $(".reportTrigger").find("option[value=" + $.trim($(this).val()) + "]").attr('selected', 'selected').trigger('change');
        })
                    
    </script>

</div>
<!--Order OverView-->
<div class="sfWrapperContainer">
    <div class="cssClassTabPanelTable cssClassOrderOverViewTab">
        <div id="dvContainerTab">
            <ul>
                <li><a href="#dvOrderOverview">
                    <asp:Label ID="lblorderOverview" runat="server" Text="Order Overview " 
                        meta:resourcekey="lblorderOverviewResource1"></asp:Label>
                </a></li>
                <li><a href="#dvTotalStoreRevenue">
                    <asp:Label ID="lbltotalRevenue" runat="server" Text="Total Store Revenue " 
                        meta:resourcekey="lbltotalRevenueResource1"></asp:Label>
                </a></li>
            </ul>
            <div id="dvOrderOverview">
                <uc1:OrderOverViews ID="OrderOverViews1" runat="server" />
            </div>
            <div id="dvTotalStoreRevenue">
                <!--Revenue-->
                <uc2:TotalStoreRevenue ID="TotalStoreRevenue1" runat="server" />
            </div>
        </div>
    </div>
    <uc12:UserRecords ID="UserRecords1" runat="server" />
  
</div>

<!--Chart-->
<div class="sfWrapperContainer">
  <div class="sfTopSearchTab">
        <uc8:TopSearchTerms ID="TopSearchTerms1" runat="server" />
    </div>
<uc9:CategoryQuantity ID="CategoryQuantity1" runat="server" />
</div>

<div class="sfWrapperContainer">
    <!--Inventory Details-->
    <div class="sfInventoryDetail">
        <uc10:InventoryDetails ID="InventoryDetails1" runat="server" />
    </div>
    <div class="sfMostViewItems">
        <uc5:MostViewedItems ID="MostViewedItems1" runat="server" />
    </div>
</div>

<div class="sfWrapperContainer">
    <div class="sfLatestFiveOrder">
        <!--Tab Panels-->
        <uc4:LatestFiveOrderItems ID="LatestFiveOrderItems1" runat="server" />
    </div>
    <div class="sfTopCustomer">
        <uc7:TopCutomersByOrder ID="TopCutomersByOrder1" runat="server" />
    </div>
</div>
<!--Side Boxes-->
<%--<uc9:LatestSearchTerms ID="LatestSearchTerms1" runat="server" />--%>
<div class="cssClassDashBoardBottom">
    <!--Recent Review-->
    <uc3:RecentReviewsAndRatings ID="RecentReviewsAndRatings1" runat="server" />
</div>
