var EditableTypes = {
    Text: 'text',
    BackgroundColor: 'background-color',
    Image: 'image'
}

var customTextModal =
    '<div class="update-text-popup">' +
    '<label for="textarea">Update Text</label>' +
    '<textarea id="textarea" rows="5"></textarea>' +
    '<button id="update-text">Update Text</button>' +
    '</div>';
var imgUrls = [
    {
        src: "https://5fef0e83e88e7201d5ac-03d894bd6aa9e18271441adec0a042f6.ssl.cf5.rackcdn.com/1041/20/LPHD0/10410320/10410320-V2W7419cY5VNyRfN.jpg",
        title: "Berkshire Model Exterior<button class='btn-select-img'>Select This Image</button>"
    },
    {
        src: "https://5fef0e83e88e7201d5ac-03d894bd6aa9e18271441adec0a042f6.ssl.cf5.rackcdn.com/1041/77/LPHD0/10410677/10410677-jxR7bJ7RhmY7QJyR.jpg",
        title: "Picture of Exterior of Hudson Model<button class='btn-select-img'>Select This Image</button>"
    },
    {
        src: "https://5fef0e83e88e7201d5ac-03d894bd6aa9e18271441adec0a042f6.ssl.cf5.rackcdn.com/1041/20/LPHD0/10410320/10410320-SmY4SDPP44rTtZMJ.jpg",
        title: "Chatham Model Exterior<button class='btn-select-img'>Select This Image</button>"
    },
    {
        src: "https://5fef0e83e88e7201d5ac-03d894bd6aa9e18271441adec0a042f6.ssl.cf5.rackcdn.com/1027/81/LPHD0/10276581/10276581-hqGKgPJrFg5sNN4h.jpg",
        title: "Master Bath<button class='btn-select-img'>Select This Image</button>"
    }
];


function trim(str) {
    return str.replace(/^\s+|\s+$/gm, '');
}

function RGBAtoRGB(rgba) {
    if (!rgba.includes('rgba')) {
        return rgba
    }
    var parts = rgba.substring(rgba.indexOf("(")).split(","),
        r = parseInt(trim(parts[0].substring(1)), 10),
        g = parseInt(trim(parts[1]), 10),
        b = parseInt(trim(parts[2]), 10),
        a = parseFloat(trim(parts[3].substring(0, parts[3].length - 1))).toFixed(2);

    if (a == 0) {
        return "rgb(255, 255, 255)"
    }
    var r3 = Math.round(((1 - a) * r + (a * r))),
        g3 = Math.round(((1 - a) * g + (a * g))),
        b3 = Math.round(((1 - a) * b + (a * b)))
    return "rgb(" + r3 + "," + g3 + "," + b3 + ")"

}

function getEditButtonNode(types) {
    console.log(types, '---types---')
    var buttonContainer = $('<div class="edit-button-container"></div>');
    if (types.includes(EditableTypes.BackgroundColor)) {
        var buttonNode = $('<button class="edit">Change Background Color</button>')
        appendEventToEditButton(buttonNode, EditableTypes.BackgroundColor)
        buttonNode.appendTo(buttonContainer)
    }
    if (types.includes(EditableTypes.Image)) {
        var buttonNode = $('<button class="edit">Change Image</button>')
        appendEventToEditButton(buttonNode, EditableTypes.Image)
        buttonNode.appendTo(buttonContainer)
    }
    if (types.includes(EditableTypes.Text)) {
        var buttonNode = $('<button class="edit">Change Text</button>')
        appendEventToEditButton(buttonNode, EditableTypes.Text)
        buttonNode.appendTo(buttonContainer)
    }
    return buttonContainer;
}

function appendEventToEditButton(buttonNode, type) {
    buttonNode.on('click', function (e) {
        if (!!picker) {
            picker.exit()
        }
        if (type === EditableTypes.BackgroundColor) {
            picker = new CP(this, false)
            picker.enter()
            var bgColor = root.css('backgroundColor')
            picker.set(RGBAtoRGB(bgColor))
            picker.on('change', function (color) {
                root.css('background-color', color);
            })
        } else if (type === EditableTypes.Image) {
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
                            console.log('image onclick')
                            magnificPopup.items // array that holds data for popup items
                            magnificPopup.currItem // data for current item
                            magnificPopup.index
                            magnificPopup.st.mainEl
                            // var target = magnificPopup.st.el[0] // Target clicked element that opened popup (works if popup is initialized from DOM element)
                            root.css('background-image', 'url(' + magnificPopup.currItem.src + ')')
                            magnificPopup.close()
                        });
                    },
                    beforeClose: function () {
                    }
                }
            });
        } else if (type === EditableTypes.Text) {
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
                        var initialVal = root.text().trim()
                        $('#textarea').val(initialVal);

                        $("#update-text").on('click', function () {
                            var updatedVal = $("#textarea").val().trim()
                            root.text(updatedVal);
                            magnificPopup.close()
                        });
                    },
                    beforeClose: function () {
                    }
                }
            }, 0);
        }
    })
}

var magnificPopup = $.magnificPopup.instance;
var picker = null
var root = null

$(document).click(function(e) {
    $target = $(e.target);
    if (!$target.closest('.color-picker').length && !$target.closest("button.edit").length && !!picker){
        picker.exit()
    }
})

$(function () {
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