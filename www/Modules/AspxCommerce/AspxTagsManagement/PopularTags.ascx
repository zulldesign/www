﻿<%@ Control Language="C#" AutoEventWireup="true" CodeFile="PopularTags.ascx.cs" Inherits="Modules_AspxTagsManage_TagsManage" %>

<script type="text/javascript">
    $(function() {
        $(".sfLocale").localize({
            moduleKey: AspxTagsManagement
        });
    });
    //<![CDATA[
    var lblShowPopularHeading = '<%=lblShowPopularHeading.ClientID %>'; 
    //]]>
</script>

<div id="divPopularTagDetails">
    <div class="cssClassCommonBox Curve">
        <div class="cssClassHeader">
          <h1>
                <span><asp:Label ID="lblTagHeading" runat="server" Text="Popular Tags" 
                    meta:resourcekey="lblTagHeadingResource1"></asp:Label>
          </h1>
            <div class="cssClassHeaderRight">
                <div class="sfButtonwrapper">
                    <p>
                        <asp:Button ID="btnExportDataToExcel" runat="server" OnClick="Button1_Click" Text="Export to Excel"
                             CssClass="cssClassButtonSubmit" 
                            OnClientClick="PopularTags.ExportDataToExcel()" 
                            meta:resourcekey="btnExportDataToExcelResource1" />
                    </p>
                    <p>
                            <asp:Button  ID="btnExport" runat="server" class="cssClassButtonSubmit"
                            OnClick="ButtonPopularTag_Click" Text="Export to CSV" 
                                OnClientClick="PopularTags.ExportPopularTagToCsvData()" 
                                meta:resourcekey="btnExportResource1"/> 
                    </p>
                    <div class="cssClassClear">
                    </div>
                </div>
            </div>
        </div>
        <div class="sfGridwrapper">
            <div class="sfGridWrapperContent">
                <div class="loading">
                    <img id="ajaxPopularTagsImage2" src=""  title="loading...." alt="loading...."/>
                </div>
                <div class="log">
                </div>
                <table id="gdvPopularTag" width="100%" border="0" cellpadding="0" cellspacing="0">
                </table>
                 <table id="PopularTagExportDataTbl" width="100%" border="0" cellpadding="0" cellspacing="0" style="display:none">
                </table>
            </div>
        </div>
    </div>
</div>
<div id="divShowPopulartagsDetails" style="display:none">
    <div class="cssClassCommonBox Curve">
        <div class="cssClassHeader">
            <h2>
                <asp:Label ID="lblShowPopularHeading" runat="server" 
                    meta:resourcekey="lblShowPopularHeadingResource1"></asp:Label>
            </h2>
            <div class="cssClassHeaderRight">
                <div class="sfButtonwrapper">
                    <p>
                        <asp:Button ID="btnExportToExcel" runat="server" OnClick="Button2_Click" Text="Export to Excel"
                            CssClass=" cssClassButtonSubmit"  
                            OnClientClick="PopularTags.ExportDivDataToExcel()" 
                            meta:resourcekey="btnExportToExcelResource1"/>
                    </p>
                    <p>
                            <asp:Button  ID="btnExportToCSV" runat="server" class="cssClassButtonSubmit"
                            OnClick="ButtonPopularTagsDetail_Click" Text="Export to CSV" 
                                OnClientClick="PopularTags.ExportPopularTagDetailToCsvData()" 
                                meta:resourcekey="btnExportToCSVResource1"/> 
                    </p>
                    <p>
                        <button type="button" id="btnBack">
                            <span><span class="sfLocale">Back</span></span>
                        </button>
                    </p>
                    <div class="cssClassClear">
                    </div>
                </div>
            </div>
        </div>
        <div class="sfGridwrapper">
            <div class="sfGridWrapperContent">
                <div class="loading">
                    <img id="ajaxPopulartagsImage" src="" title="loading...." alt="loading...."/>
                </div>
                <div class="log">
                </div>
                <table id="gdvShowPopulatTagsDetails" width="100%" border="0" cellpadding="0" cellspacing="0">
                </table>
                 <table id="ShowPopularTagDetailsExportTbl" width="100%" border="0" cellpadding="0" cellspacing="0" style="display:none">
                </table>
            </div>
        </div>
    </div>
</div>
<asp:HiddenField ID="HdnValue" runat="server" />
<asp:HiddenField ID="HdnGridData" runat="server" />
<asp:HiddenField ID="_csvPopularTagHdn" runat="server" />
<asp:HiddenField ID="_csvPopularTagDetailHdn" runat="server" />
