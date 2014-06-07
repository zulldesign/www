// I18N constants

// LANG: "ru", ENCODING: UTF-8 | ISO-8859-1
// Author: Yulya Shtyryakova, <yulya@vdcom.ru>

// FOR TRANSLATORS:
//
//   1. PLEASE PUT YOUR CONTACT INFO IN THE ABOVE LINE
//      (at least a valid email address)
//
//   2. PLEASE TRY TO USE UTF-8 FOR ENCODING;
//      (if this is not possible, please include a comment
//       that states what encoding is necessary.)

HTMLArea.I18N = {

	// the following should be the filename without .js extension
	// it will be used for automatically load plugin language.
	lang: "ru",

	tooltips: {
		bold:           "Полужирный",
		italic:         "Наклонный",
		underline:      "Подчеркнутый",
		strikethrough:  "Перечеркнутый",
		subscript:      "Нижний индекс",
		superscript:    "Верхний индекс",
		justifyleft:    "По левому краю",
		justifycenter:  "По центру",
		justifyright:   "По правому краю",
		justifyfull:    "По ширине",
		orderedlist:    "Нумерованный лист",
		unorderedlist:  "Маркированный лист",
		outdent:        "Уменьшить отступ",
		indent:         "Увеличить отступ",
		forecolor:      "Цвет шрифта",
		hilitecolor:    "Цвет фона",
		horizontalrule: "Горизонтальный разделитель",
		createlink:     "Вставить гиперссылку",
		insertimage:    "Вставить изображение",
		inserttable:    "Вставить таблицу",
		htmlmode:       "Показать Html-код",
		popupeditor:    "Увеличить редактор",
		about:          "О редакторе",
		showhelp:       "Помощь",
		textindicator:  "Текущий стиль",
		undo:           "Отменить",
		redo:           "Повторить",
		cut:            "Вырезать",
		copy:           "Копировать",
		paste:          "Вставить",
		lefttoright:    "Direction left to right",
		righttoleft:    "Direction right to left"
	},

	buttons: {
		"ok":           "OK",
		"cancel":       "Отмена"
	},

	msg: {
		"Path":         "Путь",
		"TEXT_MODE":    "Вы в режиме отображения Html-кода. нажмите кнопку [<>], чтобы переключиться в визуальный режим.",
		
		"IE-sucks-full-screen" :
		// translate here
		"The full screen mode is known to cause problems with Internet Explorer, " +
		"due to browser bugs that we weren't able to workaround.  You might experience garbage " +
		"display, lack of editor functions and/or random browser crashes.  If your system is Windows 9x " +
		"it's very likely that you'll get a 'General Protection Fault' and need to reboot.\n\n" +
		"You have been warned.  Please press OK if you still want to try the full screen editor."
	},

	dialogs: {
		"Cancel"                                            : "Cancel",
		"Insert/Modify Link"                                : "Insert/Modify Link",
		"New window (_blank)"                               : "New window (_blank)",
		"None (use implicit)"                               : "None (use implicit)",
		"OK"                                                : "OK",
		"Other"                                             : "Other",
		"Same frame (_self)"                                : "Same frame (_self)",
		"Target:"                                           : "Target:",
		"Title (tooltip):"                                  : "Title (tooltip):",
		"Top frame (_top)"                                  : "Top frame (_top)",
		"URL:"                                              : "URL:",
		"You must enter the URL where this link points to"  : "You must enter the URL where this link points to"
	}
};
