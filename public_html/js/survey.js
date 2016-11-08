var types = [ "panel", "header", "text", "select", "textarea", "dateTime"];
var panelTypes = ["info", "success", "warning", "danger" ];
var textTypes = ["text", "mail", "password"];
var enterfunction = null;
var text = "";

var preSubmit = [];

function createInputRow(n) {
    var base = $("#form-createSurvey div:last");
    var row = base.prev().after(jQuery("<div/>", {
        id: "row-" + n,
        class: "row"
    }));
    var field = jQuery("<fieldset/>", {
        id: "field-" + n,
        class: "form-inline"
    }).appendTo($("#row-" + n));

    $("#elementCount").val(n + 1)
    buildRow(0, n);
}

function buildRow(type, n) {
    var textField = $("#text-input-" + n);
    if (textField.length != 0) text = textField.val();

    var field = $("#field-" + n);
    field.empty(); //Clear
    var typeSelect = jQuery("<div/>", {
        id: "typeSelect-" + n,
        class: "form-group col-xs-3 pull-left"
    }).appendTo(field);
    jQuery("<label/>", {
        class: "sr-only",
        for: "typeSelectSelect-" + n,
        text: "Select an input type"
    }).appendTo(typeSelect);
    var select = jQuery("<select/>", {
        id: "typeSelectSelect-" + n,
        name: "type-" + n,
        class: "form-control"
    }).appendTo(typeSelect);
    for (var i = 0; i < types.length; i++) {
        jQuery("<option/>", {
            value: i,
            text: types[i]
        }).appendTo(select);
    }

    $("#typeSelectSelect-" + n).val("" + type);

    createInputs(type, n, text);

    var buttonContainer = jQuery("<div/>", {
        class: "form-group pull-right"
    }).appendTo(field);

    var addButton = jQuery("<button/>", {
        type: "button",
        id: "addButton-" + n,
        class: "btn btn-success",
        text: "Add"
    }).appendTo(buttonContainer);

    select.on("change", function() {
        buildRow(this.value, n);
    });

    addButton.on("click", function () {
        createInputRow(n+1);
        addButton.text("remove");
        addButton.toggleClass("btn-success btn-danger");
        addButton.on("click", function () {
            $("#row-" + n).remove();
        });
    })
}

function createInputs(type, n, text) {
    var field = $("#field-" + n);
    switch (type) {
        case "0":
        case 0: //panel
            var container = jQuery("<div/>", {
                id: "panel-select-container-" + n,
                class: "form-group col-xs-3"
            }).appendTo(field);

            jQuery("</label>", {
                class: "sr-only",
                for: "panel-select-" + n,
                text: "Select panel type"
            }).appendTo(container);

            var panelSelect = jQuery("<select/>", {
                id: "panel-select-" + n,
                class: "form-control",
                name: "panel-select-" + n,
                style: "background-color: #d9edf7; color: #31708f"
            }).appendTo(container);

            for (var i = 0; i < panelTypes.length; i++) {
                jQuery("<option/>", {
                    value: i,
                    text: panelTypes[i]
                }).appendTo(panelSelect);
            }

            var container2 = jQuery("<div/>", {
                id: "panel-input-container-" + n,
                class: "form-group col-xs-4"
            }).appendTo(field);

            jQuery("<label/>", {
                for: "text-input-" + n,
                class: "sr-only",
                text: "Input text for the panel"
            }).appendTo(container2);

            jQuery("<input/>", {
                id: "text-input-" + n,
                name: "text-input-" + n,
                class: "form-control",
                style: "width: 100%",
                placeholder: "The text to appear in a panel"
            }).val(text).appendTo(container2);

            panelSelect.on("change", function() {
                var bgcolours = ["#d9edf7", "#dff0d8", "#fcf8e3", "#f2dede"];
                var fgcolours = ["#31708f", "#3c763d", "#8a6d3b", "#a94442"];
                this.style = "background-color: " + bgcolours[this.value] +"; color: " + fgcolours[this.value];
            });

            break;
        case 1:
        case "1":
            var container = jQuery("<div/>", {
                id: "header-input-container-" + n,
                class: "form-group col-xs-7"
            }).appendTo(field);

            jQuery("<label/>", {
                for: "text-input-" + n,
                class: "sr-only",
                text: "Header text"
            }).appendTo(container);

            jQuery("<input/>", {
                id: "text-input-" + n,
                name: "text-input-" + n,
                class: "form-control",
                style: "width: 100%",
                placeholder: "The text to appear in a panel"
            }).val(text).appendTo(container);
            break;
        case 2:
        case "2":
            var container = jQuery("<div/>", {
                id: "text-select-container-" + n,
                class: "form-group col-xs-3"
            }).appendTo(field);

            jQuery("</label>", {
                class: "sr-only",
                for: "text-select-" + n,
                text: "Select panel type"
            }).appendTo(container);

            var textSelect = jQuery("<select/>", {
                id: "text-select-" + n,
                class: "form-control",
                name: "text-select-" + n
            }).appendTo(container);

            for (var i = 0; i < textTypes.length; i++) {
                jQuery("<option/>", {
                    value: i,
                    text: textTypes[i]
                }).appendTo(textSelect);
            }

            var container2 = jQuery("<div/>", {
                id: "text-input-container-" + n,
                class: "form-group col-xs-4"
            }).appendTo(field);

            jQuery("<label/>", {
                for: "text-input-" + n,
                class: "sr-only",
                text: "Question text"
            }).appendTo(container2);

            jQuery("<input/>", {
                id: "text-input-" + n,
                class: "form-control",
                style: "width: 100%",
                name: "text-input-" + n,
                placeholder: "What do you want to ask?"
            }).val(text).appendTo(container2);
            break;
        case 3:
        case "3":
            var optionNum = 0;

            var container = jQuery("<div/>", {
                id: "select-input-container-" + n,
                class: "form-group col-xs-4"
            }).appendTo(field);

            jQuery("<label/>", {
                for: "text-input-" + n,
                class: "sr-only",
                text: "Option"
            }).appendTo(container);

            var input = jQuery("<input/>", {
                id: "text-input-" + n,
                class: "form-control",
                style: "width: 100%",
                placeholder: "Option text"
            }).val(text).appendTo(container);

            var buttongroup = jQuery("<div/>", {
                class: "form-group col-xs-3 btn-group",
                role: "group"
            }).appendTo(field);

            var saddButton = jQuery("<button/>", {
                type: "button",
                id: "addButton-" + n,
                class: "btn btn-success form-control",
                text: "Add"
            }).appendTo(buttongroup);

            var removeButton = jQuery("<button/>", {
                type: "button",
                id: "removeButton-" + n,
                class: "btn btn-danger form-control",
                text: "Remove"
            }).appendTo(buttongroup);

            var selectContainer = jQuery("<div/>", {
                class: "form-group col-xs-3"
            }).appendTo(field);

            var selecter = jQuery("<select/>", {
                id: "option-selecter-" +n,
                name: "option-selecter-" +n,
                multiple: true,
                class: "form-control",
                style: "width: 100%"
            }).appendTo(selectContainer);

            saddButton.on("click", function() {
                var val = $("#text-input-" + n).val();
                if (val.length > 0) {
                    jQuery("<option/>", {
                        text: val,
                        value: val
                    }).appendTo(selecter);

                    $("#text-input-" + n).val("");
                }
            });

            removeButton.on("click", function() {
                var val = selecter.val();
                if (val.length > 0) {
                    for (var i = 0; i < val.length; i++) {
                        $("#option-selecter-" + n + " option[value='" + val[i] + "']").remove();
                        //TODO move values?
                    }
                }
            });

            enterfunction = function () {
                saddButton.click();
            };

            preSubmit.push(new function () {
                return function() {
                    selectAll('option-selecter-' + n);
                }
            });
            break;
        case 4:
        case "4":
            var container = jQuery("<div/>", {
                class: "form-group col-xs-4"
            }).appendTo(field);

            jQuery("<label/>", {
                for: "row-input-" +n,
                class: "sr-only",
                text: "Rows: "
            }).appendTo(container);

            jQuery("<input/>", {
                id: "row-input-" +n,
                name: "row-input-" +n,
                class: "form-control",
                placeHolder: "Number of rows"
            }).appendTo(container);

            var container2 = jQuery("<div/>", {
                id: "textarea-input-container-" + n,
                class: "form-group col-xs-6"
            }).appendTo(field);

            jQuery("<label/>", {
                for: "text-input-" + n,
                class: "sr-only",
                text: "Enter your question"
            }).appendTo(container2);

            var input = jQuery("<input/>", {
                id: "text-input-" + n,
                name: "text-input-" + n,
                class: "form-control",
                placeholder: "Some question with a long answer"
            }).val(text).appendTo(container2);
            break;
        case "5":
        case 5:
            var container = jQuery("<div/>", {
                class: "form-group col-sx-3"
            }).appendTo(field);

            jQuery("<label/>", {
                for: "date-after-" +n,
                class: "control-label",
                text: "After date: "
            }).appendTo(container);

            var cc1 = jQuery("<div/>", {
                class: "input-group date"
            }).appendTo(container);
            var dateAfter = jQuery("<input/>", {
                class: "form-control",
                type: "text",
                name: "date-after-" +n,
                id: "date-after-" +n
            }).appendTo(cc1);

            var sp1 = jQuery("<span/>", {
                class: "input-group-addon add-on"
            }).appendTo(cc1);

            jQuery("<i/>", {
                class: "glyphicon glyphicon-calendar",
                "data-time-icon": "glyphicon glyphicon-time",
                "data-date-icon": "glyphicon glyphicon-calendar"
            }).appendTo(sp1);

            var container2 = jQuery("<div/>", {
                class: "form-group col-sx-3"
            }).appendTo(field);

            jQuery("<label/>", {
                for: "date-before-" +n,
                class: "control-label",
                text: "Before date: "
            }).appendTo(container2);

            var cc2 = jQuery("<div/>", {
                class: "input-group date"
            }).appendTo(container2);

            var dateBefore = jQuery("<input/>", {
                class: "form-control",
                type: "text",
                id: "date-before-" +n,
                name: "date-before-" +n
            }).appendTo(cc2);

            var sp2 = jQuery("<span/>", {
                class: "input-group-addon add-on"
            }).appendTo(cc2);

            jQuery("<i/>", {
                class: "glyphicon glyphicon-calendar",
                "data-time-icon": "glyphicon glyphicon-time",
                "data-date-icon": "glyphicon glyphicon-calendar"
            }).appendTo(sp2);

            $.getScript("/js/bootstrap-datetimepicker.min.js", function() {
                $.getScript("http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.pt-BR.js", function() {
                    cc1.datetimepicker({
                        format: "yyyy-MM-dd hh:mm:ss",
                        language: 'en',
                        pickTime: true
                    });
                    var picker = cc1.data("datetimepicker");
                    picker.setLocalDate(new Date());
                });
            });

            $.getScript("/js/bootstrap-datetimepicker.min.js", function() {
                $.getScript("http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.pt-BR.js", function() {
                    cc2.datetimepicker({
                        format: "yyyy-MM-dd hh:mm:ss",
                        language: 'en',
                        pickTime: true
                    });
                    var picker = cc2.data("datetimepicker");
                    picker.setLocalDate(new Date());
                });
            });

            break;
    }
}

function selectAll(field) {
    console.log(field);
    var element = document.getElementById(field);
    for (var i = 0; i <element.options.length; i++) {
        element.options[i].selected = true;
        console.log("Option " + i + " selected");
    }
}

$(document).ready(function () {
    $(window).keydown(function (event) {
        if (event.keyCode == 13) {
            if (enterfunction != null) {
                enterfunction();
            }

            event.preventDefault();
            return false;
        }
    });
    var base = $("#form-createSurvey div:last");
    elementCount = base.last().prev().after(jQuery("<input/>", {
        type: "hidden",
        name: "elementCount",
        id: "elementCount",
        value: 0
    }));
    createInputRow(0);

    $("#form-createSurvey").submit(function() {
        for (var func in preSubmit) {
            preSubmit[func]();
        }
    });
});