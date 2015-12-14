// Fireup the plugins
$(document).ready(function () {
    applyFunction(mainUserManager);


    // initialise  slideshow
    $('.flexslider').flexslider({
        animation: "slide",
        start: function (slider) {
            $('body').removeClass('loading');
        }
    });

});
/**
 * Handles toggling the navigation menu for small screens.
 */
(function () {

    try {
        var button = document.getElementById('topnav').getElementsByTagName('div')[0],
            menu = document.getElementById('topnav').getElementsByTagName('ul')[0];
    }
    catch (exc) {
    }

    if (undefined === button) {
        return false;
    }

    // Hide button if menu is missing or empty.
    if (undefined === menu || !menu.childNodes.length) {
        button.style.display = 'none';
        return false;
    }

    button.onclick = function () {
        if (-1 == menu.className.indexOf('srt-menu')) {
            menu.className = 'srt-menu';
        }

        if (-1 != button.className.indexOf('toggled-on')) {
            button.className = button.className.replace(' toggled-on', '');
            menu.className = menu.className.replace(' toggled-on', '');
        }
        else {
            button.className += ' toggled-on';
            menu.className += ' toggled-on';
        }
    };
})();

function applyFunction(function_) {
    if (typeof function_ == 'function') {
        function_();
    }
}

function print_object(event) {
    var s = '{\n';
    for (
        var p in event)
        s += '    "' + p + '": "' + event[p] + '"\n';
    return s + '}';
}
