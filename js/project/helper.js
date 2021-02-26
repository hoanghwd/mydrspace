/**
 * Created by hdo on 1/11/2019.
 */

/**
 * Redirect page
 * @param url
 */
function redirectToPage(url) {
    window.location = url;
}

/**
 * Refresh page
 */
function refreshPage() {
    location.reload();
}

/**
 * Url Encode
 * @param text
 */
function urlEncode(text) {
    return
    encodeURIComponent(text)
            .replace(/!/g, '%21')
            .replace(/'/g, '%27')
            .replace(/\(/g, '%28')
            .replace(/\)/g, '%29')
            .replace(/\*/g, '%2A')
            .replace(/%20/g, '+');
}

/**
 * Url Decode
 * @param text
 * @returns {string}
 */
function urlDecode(text) {
    return decodeURIComponent((text + '').replace(/\+/g, '%20'));
}

/**
 *
 * @param text
 * @returns {*}
 */
function htmlDecode(text) {
    return text.replace(/(&quot\;)/g, "\"");
}

/**
 * Get base Img Url
 * @returns {*|jQuery}
 */
function baseImgUrl() {
    return jQuery('#img-url').val();
}

/**
 * Get base Url
 * @returns {*|jQuery}
 */
function baseUrl() {
    return jQuery('#base-url').val();
}

/**
 *
 */
function resovePrototypeConflict() {

    if (Prototype.BrowserFeatures.ElementExtensions) {
        var disablePrototypeJS = function (method, pluginsToDisable) {
            var handler = function (event) {
                event.target[method] = undefined;
                setTimeout(function () {
                    delete event.target[method];
                }, 0);
            };
            pluginsToDisable.each(function (plugin) {
                jQuery(window).on(method + '.bs.' + plugin, handler);
            });
        },
        pluginsToDisable = ['collapse', 'dropdown', 'modal', 'tooltip', 'popover'];
        disablePrototypeJS('show', pluginsToDisable);
        disablePrototypeJS('hide', pluginsToDisable);
    }

    (function () {
        var isBootstrapEvent = false;
        if (window.jQuery) {
            var all = jQuery('*');
            jQuery.each(['hide.bs.dropdown',
                'hide.bs.collapse',
                'hide.bs.modal',
                'hide.bs.tooltip',
                'hide.bs.popover',
                'hide.bs.tab'], function (index, eventName) {
                all.on(eventName, function (event) {
                    isBootstrapEvent = true;
                });
            });
        }
        var originalHide = Element.hide;
        Element.addMethods({
            hide: function (element) {
                if (isBootstrapEvent) {
                    isBootstrapEvent = false;
                    return element;
                }
                return originalHide(element);
            }
        });
    })(); 
    
    /* ------- THE FUNCTION BEING CALLED TO UTILIZE PROTOTYPE.JS ------ */
    //Sortable.create('NewSortOrder', {onUpdate: updateSortOrder})
}

/**
 *
 * @param a
 * @returns {string|string}
 */
function sanitizeHtmlOut(a) {
    return typeof a === "undefined" ? "" : String(a).replace(/&/g, "&amp;").replace(/"/i, "&quot;").replace(/</i, "&lt;").replace(/>/i, "&gt;").replace(/'/i, "&apos;");
}

/**
 *
 * @param b
 * @returns {string}
 */
function escapeElementId(b) {
    var a = b.replace(/[[]/g, "\\[");
    return a.replace(/]/g, "\\]");
}

var entityMap = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#39;",
    "/": "&#x2F;"
};

/**
 *
 * @param a
 * @returns {string}
 */
function escapeHtml(a) {
    return String(a).replace(/[&<>"'\/]/g, function b(c) {
        return entityMap[c];
    });
}

/**
 *
 * @param a
 * @returns {string|string}
 */
function sanitize(a) {
    return typeof a === "undefined" ? "" : String(a).replace(/&/g, "&amp;").replace(/"/i, "&quot;").replace(/</i, "&lt;").replace(/>/i, "&gt;").replace(/'/i, "&apos;");
}

/**
 *
 * @param a
 */
function log(a) {
    jQuery("<div>").text(a).prependTo("#log");
    jQuery("#log").scrollTop(0);
}

/**
 *
 * @param a
 * @returns {string|*|jQuery}
 */
function htmlEncode(a) {
    if (a) {
        return jQuery("<div />").text(a).html();
    } else {
        return "";
    }
}

/**
 *
 * @param a
 * @returns {string|jQuery}
 */
function htmlDecode(a) {
    if (a) {
        return $("<div />").html(a).text();
    } else {
        return "";
    }
}

/**
 *
 * @returns {string|null|undefined|jQuery}
 */
function popoverTitleContent() {
    if ($(this).attr("data-remotetitle")) {
        var b = "";
        var c = $(this).attr("data-remotetitle");
        var a = c + " " + b;
        return a;
    }
    return $(this).attr("title");
}

/**
 *
 * @returns {string|null|undefined|jQuery}
 */
function popoverContent() {
    if (jQuery(this).attr("data-remotecontent")) {
        var a = jQuery.ajax({
            url: jQuery(this).attr("data-remotecontent"),
            type: "GET",
            data: jQuery(this).serialize(),
            dataType: "html",
            async: false,
            success: function() {},
            error: function() {}
        }).responseText;
        return a;
    }
    return $(this).attr("data-content");
}

function overlayOn()
{
    jQuery(".overlay").show();
}

function overlayOff()
{
    jQuery(".overlay").hide();
}

function scrollToTop() {
    var position =
        document.body.scrollTop || document.documentElement.scrollTop;
    if (position) {
        window.scrollBy(0, -Math.max(1, Math.floor(position / 10)));
        scrollAnimation = setTimeout("scrollToTop()", 2);
    } else clearTimeout(scrollAnimation);
}