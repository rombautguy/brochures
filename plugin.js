var EditableTypes = {
    Text: 'text',
    BackgroundColor: 'background-color',
    FontColor: 'font-color',
    Image: 'image'
}

var customTextModal =
    '<div class="update-text-popup">' +
    '<label for="textarea">Update Text</label>' +
    '<textarea id="textarea" rows="5"></textarea>' +
    '<button id="btn-update-text" class="btn-custom">Update Text</button>' +
    '</div>';
var imgUrls = [
    {
        src: "https://5fef0e83e88e7201d5ac-03d894bd6aa9e18271441adec0a042f6.ssl.cf5.rackcdn.com/1041/20/LPHD0/10410320/10410320-V2W7419cY5VNyRfN.jpg",
        title: "Berkshire Model Exterior<button class='btn-select-img btn-custom'>Select This Image</button>"
    },
    {
        src: "https://5fef0e83e88e7201d5ac-03d894bd6aa9e18271441adec0a042f6.ssl.cf5.rackcdn.com/1041/77/LPHD0/10410677/10410677-jxR7bJ7RhmY7QJyR.jpg",
        title: "Picture of Exterior of Hudson Model<button class='btn-select-img btn-custom'>Select This Image</button>"
    },
    {
        src: "https://5fef0e83e88e7201d5ac-03d894bd6aa9e18271441adec0a042f6.ssl.cf5.rackcdn.com/1041/20/LPHD0/10410320/10410320-SmY4SDPP44rTtZMJ.jpg",
        title: "Chatham Model Exterior<button class='btn-select-img btn-custom'>Select This Image</button>"
    },
    {
        src: "https://5fef0e83e88e7201d5ac-03d894bd6aa9e18271441adec0a042f6.ssl.cf5.rackcdn.com/1027/81/LPHD0/10276581/10276581-hqGKgPJrFg5sNN4h.jpg",
        title: "Master Bath<button class='btn-select-img btn-custom'>Select This Image</button>"
    }
];


function trimColorStr(str) {
    return str.replace(/^\s+|\s+$/gm, '');
}

function RGBAtoRGB(rgba) {
    if (!rgba.includes('rgba')) {
        return rgba
    }
    var parts = rgba.substring(rgba.indexOf("(")).split(","),
        r = parseInt(trimColorStr(parts[0].substring(1)), 10),
        g = parseInt(trimColorStr(parts[1]), 10),
        b = parseInt(trimColorStr(parts[2]), 10),
        a = parseFloat(trimColorStr(parts[3].substring(0, parts[3].length - 1))).toFixed(2);

    if (a == 0) {
        return "rgb(255, 255, 255)"
    }
    var r3 = Math.round(((1 - a) * r + (a * r))),
        g3 = Math.round(((1 - a) * g + (a * g))),
        b3 = Math.round(((1 - a) * b + (a * b)))
    return "rgb(" + r3 + "," + g3 + "," + b3 + ")"
}

function checkMaxLength(textareaID, maxLength) {
    currentLengthInTextarea = $("#" + textareaID).val().length;
    $(remainingLengthTempId).text(parseInt(maxLength) - parseInt(currentLengthInTextarea));

    if (currentLengthInTextarea > (maxLength)) {
        $("textarea").val($("textarea").val().slice(0, maxLength));
        $(remainingLengthTempId).text(0);
    }
}

function getEditButtonNode(types) {
    var buttonContainer = $('<div class="edit-button-container"></div>');
    if (types.includes(EditableTypes.BackgroundColor)) {
        var buttonNode = $('<button class="edit btn-custom">Change Background Color</button>')
        appendEventToEditButton(buttonNode, EditableTypes.BackgroundColor)
        buttonNode.appendTo(buttonContainer)
    }
    if (types.includes(EditableTypes.FontColor)) {
        var buttonNode = $('<button class="edit btn-custom">Change Font Color</button>')
        appendEventToEditButton(buttonNode, EditableTypes.FontColor)
        buttonNode.appendTo(buttonContainer)
    }
    if (types.includes(EditableTypes.Image)) {
        var buttonNode = $('<button class="edit btn-custom">Change Image</button>')
        appendEventToEditButton(buttonNode, EditableTypes.Image)
        buttonNode.appendTo(buttonContainer)
    }
    if (types.includes(EditableTypes.Text)) {
        var buttonNode = $('<button class="edit btn-custom">Change Text</button>')
        appendEventToEditButton(buttonNode, EditableTypes.Text)
        buttonNode.appendTo(buttonContainer)
    }
    return buttonContainer;
}

function updateTemplateData(id, type, value) {
    var templateData = JSON.parse(localStorage.getItem(templateId) || '{}');

    var targetData = templateData[id] || {}

    targetData[type] = value
    templateData[id] = targetData

    localStorage.setItem(templateId, JSON.stringify(templateData));
}
function restoreTemplateData() {
    var templateData = JSON.parse(localStorage.getItem(templateId) || '{}');

    $.each(templateData, function (id, targetData) {

        var target = $('#' + id)

        $.each(targetData, function (type, value) {
            if (type === EditableTypes.BackgroundColor) {
                target.css('background-color', value);
            }
            if (type === EditableTypes.FontColor) {
                target.css('color', value);
            }
            if (type === EditableTypes.Image) {
                target.css('background-image', 'url(' + value + ')')
            }
            if (type === EditableTypes.Text) {
                target.text(value);
            }
        })
    });
}

function appendBackgroundColorChangeEvent(target) {
    picker = new CP(target, false)
    picker.enter()
    var bgColor = root.css('backgroundColor')
    picker.set(RGBAtoRGB(bgColor))
    picker.on('change', function (color) {
        root.css('background-color', color);
        updateTemplateData(root.attr('id'), EditableTypes.BackgroundColor, color)
    })
}
function appendFontColorChangeEvent(target) {
    picker = new CP(target, false)
    picker.enter()
    var bgColor = root.css('color')
    picker.set(RGBAtoRGB(bgColor))
    picker.on('change', function (color) {
        root.css('color', color);
        updateTemplateData(root.attr('id'), EditableTypes.FontColor, color)
    })
}
function appendImageChangeEvent() {
    $.magnificPopup.open({
        mainClass: 'img-gallery',
        items: imgUrls,
        gallery: {
            enabled: true
        },
        type: 'image', // this is a default type,
        closeBtnInside: true,
        callbacks: {
            beforeOpen: function () {
            },
            updateStatus: function (data) {
                $(".btn-select-img").on('click', function (e) {
                    magnificPopup.items // array that holds data for popup items
                    magnificPopup.currItem // data for current item
                    magnificPopup.index
                    magnificPopup.st.mainEl
                    // var target = magnificPopup.st.el[0] // Target clicked element that opened popup (works if popup is initialized from DOM element)
                    root.css('background-image', 'url(' + magnificPopup.currItem.src + ')')
                    updateTemplateData(root.attr('id'), EditableTypes.Image, magnificPopup.currItem.src)
                    magnificPopup.close()
                });
            },
            beforeClose: function () {
            }
        }
    });
}
function appendTextChangeEvent() {
    $.magnificPopup.open({
        midClick: true,
        items: {
            src: customTextModal,
            type: 'inline'
        },
        // closeBtnInside: true,
        callbacks: {
            beforeOpen: function () {
            },
            open: function () {
                $("button.edit", root).remove()

                var initialVal = root.text().trim().slice(0, maxLength)

                var maxLength = root.data('character-limit')
                if (maxLength) {
                    maxLength && $('#textarea').attr('maxlength', maxLength);
                    maxLength && $('#textarea').after("<div>max-length: " + maxLength + " / <span id='remainingLengthTempId'>" + (maxLength - initialVal.length) + "</span> remaining</div>");
                    $('#textarea').bind("keyup change", function () { checkMaxLength(this.id, maxLength); })
                }

                $('#textarea').val(initialVal);

                $("#btn-update-text").on('click', function () {
                    var updatedVal = $("#textarea").val().trim()
                    root.text(updatedVal);
                    updateTemplateData(root.attr('id'), EditableTypes.Text, updatedVal)
                    magnificPopup.close()
                });
            },
            beforeClose: function () {
            }
        }
    });
}
function appendEventToEditButton(buttonNode, type) {
    buttonNode.on('click', function (e) {
        if (!!picker) {
            picker.exit()
        }
        if (type === EditableTypes.BackgroundColor) {
            appendBackgroundColorChangeEvent(this)
        } else if (type === EditableTypes.FontColor) {
            appendFontColorChangeEvent(this)
        } else if (type === EditableTypes.Image) {
            appendImageChangeEvent()
        } else if (type === EditableTypes.Text) {
            appendTextChangeEvent()
        }
    })
}


var templateId = $('*[data-template-id]').data('template-id')
var magnificPopup = $.magnificPopup.instance;
var picker = null
var root = null

$(document).click(function (e) {
    $target = $(e.target);
    if (!$target.closest('.color-picker').length && !$target.closest("button.edit").length && !!picker) {
        picker.exit()
    }
})

$(function () {
    var templateData = localStorage.getItem(templateId);
    templateData && restoreTemplateData()

    $('*[data-editable]').hover(
        function (e) {
            $('*[data-editable]').removeClass('focus-hover')
            $(".edit-button-container", $('*[data-editable]')).remove()

            root = $(this)
            $(this).addClass('focus-hover')
            e.stopPropagation();

            var types = $(this).data('editable')
            var buttonsNode = getEditButtonNode(types.split(' '))
            $(this).append(buttonsNode)
        },
        function () {
            $("button.edit", this).remove()
            $(this).removeClass('focus-hover')
        }
    );


    $("*[data-editable='image']").hover(function () {
        var images = $(this).data('images');
        if (images) {
            for (var key in images) {
                var image = images[key]
                imgUrls.push({ src: image.photo_url, title: image.caption })
            }
        }
        // console.log('--------images', imgUrls)
    });

}());