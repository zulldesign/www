var VhplustoolbarfuncMap = new Array;
VhplustoolbarfuncMap["open"] = {type:"btn", title:"Open", method:"OgEditor.funcBtnPressed('dialgOpen');"};
VhplustoolbarfuncMap["new"] = {type:"btn", title:"New", method:"OgEditor.funcBtnPressed('dialgNew');"};
VhplustoolbarfuncMap["save"] = {type:"btn", title:"Save", method:"OgEditor.funcBtnPressed('dialgSaveAs');"};
VhplustoolbarfuncMap["component"] = {type:"btn", title:"component", method:"OgEditor.funcBtnPressed('dialgComponent');"};
VhplustoolbarfuncMap["print"] = {type:"btn", title:"print", method:"OgEditor.printContents();"};
VhplustoolbarfuncMap["cut"] = {type:"btn", title:"Cut", method:"OgEditor.execCmd('cut', null, null);"};
VhplustoolbarfuncMap["copy"] = {type:"btn", title:"Copy", method:"OgEditor.funcBtnPressed('copyToWebClipBoard');"};
VhplustoolbarfuncMap["paste"] = {type:"btn", title:"Paste", method:"OgEditor.funcBtnPressed('pasteFromWebClipBoard');"};
VhplustoolbarfuncMap["undo"] = {type:"btn", title:"Undo", method:"OgEditor.funcBtnPressed('undo');"};
VhplustoolbarfuncMap["redo"] = {type:"btn", title:"Redo", method:"OgEditor.funcBtnPressed('redo');"};
VhplustoolbarfuncMap["bold"] = {type:"btn", title:"Bold", method:"OgEditor.execCmd('bold', null, null);"};
VhplustoolbarfuncMap["italic"] = {type:"btn", title:"Italic", method:"OgEditor.execCmd('italic', null, null);"};
VhplustoolbarfuncMap["underline"] = {type:"btn", title:"Underline", method:"OgEditor.execCmd('underline', null, null);"};
VhplustoolbarfuncMap["strikethrough"] = {type:"btn", title:"Strikethrough", method:"OgEditor.funcBtnPressed('strikethrough');"};
VhplustoolbarfuncMap["subscript"] = {type:"btn", title:"Subscript", method:"OgEditor.execCmd('subscript', null, null);"};
VhplustoolbarfuncMap["superscript"] = {type:"btn", title:"superscript", method:"OgEditor.execCmd('superscript', null, null);"};
VhplustoolbarfuncMap["link"] = {type:"btn", title:"Insert/edit hyperlink", method:"OgEditor.funcBtnPressed('dialgLink');"};
VhplustoolbarfuncMap["table"] = {type:"btn", title:"Insert table", method:"OgEditor.funcBtnPressed('dialgTable');"};
VhplustoolbarfuncMap["image"] = {type:"btn", title:"Insert image", method:"OgEditor.funcBtnPressed('dialgImage');"};
VhplustoolbarfuncMap["orderlist"] = {type:"btn", title:"Ordered list", method:"OgEditor.funcBtnPressed('orderlist');"};
VhplustoolbarfuncMap["uorderlist"] = {type:"btn", title:"Unordered list", method:"OgEditor.funcBtnPressed('uorderlist');"};
VhplustoolbarfuncMap["fontcolor"] = {type:"btn", title:"Font color", method:"OgEditor.funcBtnPressed('fontcolor');"};
VhplustoolbarfuncMap["backcolor"] = {type:"btn", title:"Background color", method:"OgEditor.funcBtnPressed('backcolor');"};
VhplustoolbarfuncMap["justifyleft"] = {type:"btn", title:"Align left", method:"OgEditor.execCmd('justifyleft', null, null);"};
VhplustoolbarfuncMap["justifycenter"] = {type:"btn", title:"Align center", method:"OgEditor.execCmd('justifycenter', null, null);"};
VhplustoolbarfuncMap["justifyright"] = {type:"btn", title:"Align right", method:"OgEditor.execCmd('justifyright', null, null);"};
VhplustoolbarfuncMap["indent"] = {type:"btn", title:"Indent", method:"OgEditor.execCmd('indent', null, null);"};
VhplustoolbarfuncMap["outdent"] = {type:"btn", title:"Outdent", method:"OgEditor.execCmd('outdent', null, null);"};
VhplustoolbarfuncMap["rule"] = {type:"btn", title:"Insert horizontal ruler", method:"OgEditor.funcBtnPressed('rule');"};
VhplustoolbarfuncMap["pagebreak"] = {type:"btn", title:"Insert pagebreak", method:"OgEditor.funcBtnPressed('pagebreak');"};
VhplustoolbarfuncMap["guideline"] = {type:"btn", title:"Visual Aid on/off", method:"OgEditor.showOrHideGuides();"};
VhplustoolbarfuncMap["|"] = {type:"sep",method:""};
VhplustoolbarfuncMap["button"] = {type:"btn", title:"Insert button", method:"OgEditor.funcBtnPressed('dialgButton');"};
VhplustoolbarfuncMap["checkbox"] = {type:"btn", title:"Insert checkbox", method:"OgEditor.funcBtnPressed('dialgCheckBox');"};
VhplustoolbarfuncMap["radiobutton"] = {type:"btn", title:"Insert radio button", method:"OgEditor.funcBtnPressed('dialgRadio');"};
VhplustoolbarfuncMap["ddlist"] = {type:"btn", title:"Insert dropdownlist", method:"OgEditor.funcBtnPressed('dialgSurround');"};
VhplustoolbarfuncMap["text"] = {type:"btn", title:"Insert text field", method:"OgEditor.funcBtnPressed('dialgInputText');"};
VhplustoolbarfuncMap["textarea"] = {type:"btn", title:"Insert textarea", method:"OgEditor.funcBtnPressed('dialgTextArea');"};
VhplustoolbarfuncMap["find"] = {type:"btn", title:"Find and Replace", method:"OgEditor.funcBtnPressed('dialgFind');"};
//VhplustoolbarfuncMap["surround"] = {type:"btn", title:"Surround with block element", method:"OgEditor.openDialogForDebug(true);"};// for Debugging
VhplustoolbarfuncMap["surround"] = {type:"btn", title:"Wrap Html tag around Selection", method:"OgEditor.funcBtnPressed('dialgSurround');"};
VhplustoolbarfuncMap["cctrl"] = {type:"btn", title:"Custom Control", method:"OgEditor.funcBtnPressed('dialgCCtrlList');"};



var ogDDList = new Object();
ogDDList["Heading"] = {ddlistID:"ddlHeading", 
	classID:"ddlcontainer",
    ddlistItems:[{attribute:"tag", property:"", value:"H1", label:"H1", id:"ddloption1", click:"OgEditor.setHeading('H1');"},
               {attribute:"tag", property:"", value:"H2", label:"H2", id:"ddloption2", click:"OgEditor.setHeading('H2');"},
               {attribute:"tag", property:"", value:"H3", label:"H3", id:"ddloption3", click:"OgEditor.setHeading('H3');"},
               {attribute:"tag", property:"", value:"H4", label:"H4", id:"ddloption4", click:"OgEditor.setHeading('H4');"},
               {attribute:"tag", property:"", value:"H5", label:"H5", id:"ddloption5", click:"OgEditor.setHeading('H5');"},
               {attribute:"tag", property:"", value:"H6", label:"H6", id:"ddloption6", click:"OgEditor.setHeading('H6');"}]};
               
ogDDList["fontFamily"] = {ddlistID:"ddlFontFamily", 
	classID:"ddlcontainer",
    ddlistItems:[{attribute:"style", property:"font-family", value:"Arial", label:"Arial", id:"ddloption1", click:"OgEditor.setFontFamily('Arial');"},
               {attribute:"style", property:"font-family", value:"Comic Sans MS", label:"Comic Sans MS", id:"ddloption2", click:"OgEditor.setFontFamily('Comic Sans MS');"},
               {attribute:"style", property:"font-family", value:"Courier New", label:"Courier New", id:"ddloption3", click:"OgEditor.setFontFamily('Courier New');"},
               {attribute:"style", property:"font-family", value:"Times New Roman", label:"Times New Roman", id:"ddloption4", click:"OgEditor.setFontFamily('Times New Roman');"},
               {attribute:"style", property:"font-family", value:"Impact", label:"Impact", id:"ddloption5", click:"OgEditor.setFontFamily('Impact');"},
               {attribute:"style", property:"font-family", value:"Trebuchet MS", label:"Trebuchet MS", id:"ddloption6", click:"OgEditor.setFontFamily('Trebuchet MS');"},
               {attribute:"style", property:"font-family", value:"Verdada", label:"Verdada", id:"ddloption7", click:"OgEditor.setFontFamily('Verdada');"}]};
               
ogDDList["fontSize"] = {ddlistID:"ddlFontSize", 
	classID:"ddlcontainer",
    ddlistItems:[{attribute:"style", property:"font-size", value:"xx-small", label:"xx-small", id:"ddloption1", click:"OgEditor.setFontSize('xx-small');"},
               {attribute:"style", property:"font-size", value:"x-small", label:"x-small", id:"ddloption2", click:"OgEditor.setFontSize('x-small');"},
               {attribute:"style", property:"font-size", value:"small", label:"small", id:"ddloption3", click:"OgEditor.setFontSize('small');"},
               {attribute:"style", property:"font-size", value:"medium", label:"medium", id:"ddloption4", click:"OgEditor.setFontSize('medium');"},
               {attribute:"style", property:"font-size", value:"large", label:"large", id:"ddloption5", click:"OgEditor.setFontSize('large');"},
               {attribute:"style", property:"font-size", value:"x-large", label:"x-large", id:"ddloption6", click:"OgEditor.setFontSize('x-large');"},
               {attribute:"style", property:"font-size", value:"xx-large", label:"xx-large", id:"ddloption3", click:"OgEditor.setFontSize('xx-large');"},
               {attribute:"style", property:"font-size", value:"9px", label:"9px", id:"ddloption6", click:"OgEditor.setFontSize('9px');"},
               {attribute:"style", property:"font-size", value:"12px", label:"12px", id:"ddloption6", click:"OgEditor.setFontSize('12px');"},
               {attribute:"style", property:"font-size", value:"14px", label:"14px", id:"ddloption6", click:"OgEditor.setFontSize('14px');"},
               {attribute:"style", property:"font-size", value:"16px", label:"16px", id:"ddloption6", click:"OgEditor.setFontSize('16px');"},
               {attribute:"style", property:"font-size", value:"18px", label:"18px", id:"ddloption6", click:"OgEditor.setFontSize('18px');"},
               {attribute:"style", property:"font-size", value:"24px", label:"24px", id:"ddloption6", click:"OgEditor.setFontSize('24px');"},
               {attribute:"style", property:"font-size", value:"36px", label:"36px", id:"ddloption7", click:"OgEditor.setFontSize('36px');"}]};


