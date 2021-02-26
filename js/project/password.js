/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
(function(c) {
        var b = "pswd_info_valid"
            , a = "pswd_info_invalid";
        c.fn.makeValid = function() {
            this.removeClass(a).addClass(b);
        }
        ;
        c.fn.makeInValid = function() {
            this.removeClass(b).addClass(a);
        }
        ;
    }
)(jQuery);

(function(a) {
        var c = "pswd_info_valid_portal"
            , b = "pswd_info_invalid_portal";
        a.fn.makeValidPortal = function() {
            this.removeClass(b).addClass(c);
        };
        a.fn.makeInValidPortal = function() {
            this.removeClass(c).addClass(b);
        };
    }
)(jQuery);

/**
 *
 * @param c
 * @returns {boolean}
 * @constructor
 */
function ValidatePassword(c) {
    var b = $(c).val()
        , a = 0;
    b.length >= 8 && b.length <= 50 ? (jQuery("#pswd_info_length").makeValid(),
        a++) : jQuery("#pswd_info_length").makeInValid();
    b.match(/[a-z]/) ? (jQuery("#pswd_info_lower").makeValid(),
        a++) : jQuery("#pswd_info_lower").makeInValid();
    b.match(/[A-Z]/) ? (jQuery("#pswd_info_capital").makeValid(),
        a++) : jQuery("#pswd_info_capital").makeInValid();
    b.match(/\d/) ? (jQuery("#pswd_info_number").makeValid(),
        a++) : jQuery("#pswd_info_number").makeInValid();
    b.match(/^[A-Za-z0-9-.@#&?'\/",\+\(\)!]*$/) ? (jQuery("#pswd_info_symbol").makeValid(),
        a++) : jQuery("#pswd_info_symbol").makeInValid();
    b.toUpperCase() !== jQuery("#username").val().toUpperCase() ? (jQuery("#pswd_info_username").makeValid(),
        a++) : jQuery("#pswd_info_username").makeInValid();
    if (!b.match(/(.)\1\1/)) {
        jQuery("#pswd_info_repeats").makeValid();
        a++;
    } else {
        jQuery("#pswd_info_repeats").makeInValid();
    }
    if (a === 7) {
        jQuery("#form-group-tPasswordNew").removeClass("has-error");
        jQuery("#tPasswordNew").removeClass("is-invalid");
    }
    return a === 7;
}

/**
 *
 * @param d
 * @returns {boolean}
 * @constructor
 */
function ValidatePasswordv3(d) {
    var a = escapeElementId($(d).attr("id"));
    if (jQuery("#form-group-" + a).hasClass("has-error")) {
        jQuery("#form-group-" + a).removeClass("has-error");
        jQuery("#error-" + a).empty();
        jQuery("#" + a).removeAttr("aria-invalid");
        jQuery("#sr-only-error-" + a).empty();
    }
    var c = $(d).val()
        , b = 0;
    c.length >= 8 && c.length <= 50 ?
        ($("li[id^='pswd_info_length']").makeValidPortal(), b++) : $("li[id^='pswd_info_length']").makeInValidPortal();
    c.match(/[A-Z]/) ?
        ($("li[id^='pswd_info_capital']").makeValidPortal(), b++) : $("li[id^='pswd_info_capital']").makeInValidPortal();
    c.match(/\d/) ?
        ($("li[id^='pswd_info_number']").makeValidPortal(), b++) : $("li[id^='pswd_info_number']").makeInValidPortal();
    c.match(/^[A-Za-z0-9-.@#&?'\/",\+\(\)!]*$/) ?
        ($("li[id^='pswd_info_symbol']").makeValidPortal(), b++) : $("li[id^='pswd_info_symbol']").makeInValidPortal();
    c.toUpperCase() !== jQuery("#tuserName").val().toUpperCase() ?
        ($("li[id^='pswd_info_username']").makeValidPortal(), b++) : $("li[id^='pswd_info_username']").makeInValidPortal();

    if (!c.match(/(.)\1\1/)) {
        $("li[id^='pswd_info_repeats']").makeValidPortal();
        b++;
    }
    else {
        $("li[id^='pswd_info_repeats']").makeInValidPortal();
    }

    if (b === 7) {
        jQuery("#form-group-" + a).removeClass("has-error");
    }
    return b === 7;
}

/**
 *
 * @param d
 * @returns {boolean}
 * @constructor
 */
function ValidatePasswordv2(d) {
    var a = escapeElementId($(d).attr("id"));

    if (jQuery("#form-group-" + a).hasClass("has-error")) {
        jQuery("#form-group-" + a).removeClass("has-error");
        jQuery("#error-" + a).empty();
        jQuery("#" + a).removeAttr("aria-invalid");
        jQuery("#sr-only-error-" + a).empty();
    }
    var c = $(d).val()
        , b = 0;
    c.length >= 8 && c.length <= 50 ?
        (jQuery("#pswd_info_length").makeValid(), b++) : jQuery("#pswd_info_length").makeInValid();
    c.match(/[a-z]/) ?
        (jQuery("#pswd_info_lower").makeValid(), b++) : jQuery("#pswd_info_lower").makeInValid();
    c.match(/[A-Z]/) ?
        (jQuery("#pswd_info_capital").makeValid(), b++) : jQuery("#pswd_info_capital").makeInValid();
    c.match(/\d/) ?
        (jQuery("#pswd_info_number").makeValid(), b++) : jQuery("#pswd_info_number").makeInValid();
    c.match(/^[A-Za-z0-9-.@#&?'\/",\+\(\)!]*$/) ?
        (jQuery("#pswd_info_symbol").makeValid(), b++) : jQuery("#pswd_info_symbol").makeInValid();
    c.toUpperCase() !== jQuery("#tuserName").val().toUpperCase() ?
        (jQuery("#pswd_info_username").makeValid(), b++) : jQuery("#pswd_info_username").makeInValid();

    if (!c.match(/(.)\1\1/)) {
        jQuery("#pswd_info_repeats").makeValid();
        b++;
    }
    else {
        jQuery("#pswd_info_repeats").makeInValid();
    }

    if (b === 7) {
        jQuery("#form-group-" + a).removeClass("has-error");
    }

    return b === 7;
}

/**
 *
 * @param a
 * @param f
 * @constructor
 */
function ValidateRetypePassword(a, f) {
    var e = $(a).val()
        , c = $(f).val();
    var b = escapeElementId($(a).attr("id"));
    if (jQuery("#form-group-" + b).hasClass("has-error")) {
        jQuery("#form-group-" + b).removeClass("has-error");
        jQuery("#error-" + b).empty();
        jQuery("#" + b).removeAttr("aria-invalid").removeClass("is-invalid");
        jQuery("#sr-only-error-" + b).empty();
    }
    jQuery("#retype_pswd_info_invalid").hide();
    jQuery("#retype_pswd_info_matching").hide();
    jQuery("#retype_pswd_info_matching_progress").hide();
    jQuery("#retype_pswd_info_match").hide();
    jQuery("#retype_pswd_info_begin").hide();
    if (jQuery("#form-group-tPasswordNew").hasClass("has-error") || c.length == 0) {
        jQuery("#form-group-tPasswordRetype").addClass("has-error");
        jQuery("#tPasswordRetype").addClass("is-invalid");
        jQuery("#retype_pswd_info_invalid").makeInValid();
        jQuery("#retype_pswd_info_invalid").show();
    }
    else {
        if (e.length == 0) {
            jQuery("#retype_pswd_info_begin").show();
        }
        else {
            if (e == c) {
                jQuery("#retype_pswd_info_match").show();
            }
            else {
                for (var d = 0; d < e.length; d++) {
                    if (e.charAt(d) != c.charAt(d)) {
                        jQuery("#form-group-tPasswordRetype").addClass("has-error");
                        jQuery("#tPasswordRetype").addClass("is-invalid");
                        jQuery("#retype_pswd_info_matching").makeInValid();
                        jQuery("#retype_pswd_info_matching").show();
                        jQuery("#retype_pswd_info_matching_progress").hide();
                        break;
                    }
                    else {
                        jQuery("#retype_pswd_info_matching_progress").show();
                    }
                }
            }
        }
    }
}

/**
 *
 * @param a
 * @param f
 * @constructor
 */
function ValidateRetypePasswordv3(a, f) {
    var e = $(a).val()
        , c = $(f).val();
    var b = escapeElementId($(a).attr("id"));
    if (jQuery("#form-group-" + b).hasClass("has-error")) {
        jQuery("#form-group-" + b).removeClass("has-error");
        jQuery("#error-" + b).empty();
        jQuery("#" + b).removeAttr("aria-invalid");
        jQuery("#sr-only-error-" + b).empty();
    }
    jQuery("#retype_pswd_info_invalid").hide();
    jQuery("#retype_pswd_info_matching").hide();
    jQuery("#retype_pswd_info_matching_progress").hide();
    jQuery("#retype_pswd_info_match").hide();
    jQuery("#retype_pswd_info_begin").hide();
    if (jQuery("#form-group-tPasswordNew").hasClass("has-error") || c.length == 0) {
        jQuery("#form-group-tPasswordRetype").addClass("has-error");
        jQuery("#retype_pswd_info_invalid").makeInValidPortal();
        jQuery("#retype_pswd_info_invalid").show();
    }
    else {
        if (e.length == 0) {
            jQuery("#retype_pswd_info_begin").show();
        }
        else {
            if (e == c) {
                jQuery("#retype_pswd_info_match").show();
            }
            else {
                for (var d = 0; d < e.length; d++) {
                    if (e.charAt(d) != c.charAt(d)) {
                        jQuery("#form-group-tPasswordRetype").addClass("has-error");
                        jQuery("#retype_pswd_info_matching").makeInValidPortal();
                        jQuery("#retype_pswd_info_matching").show();
                        jQuery("#retype_pswd_info_matching_progress").hide();
                        break;
                    }
                    else {
                        jQuery("#retype_pswd_info_matching_progress").show();
                    }
                }
            }
        }
    }
}

jQuery(document).ready(function () {
    jQuery("form").keypress(function (d) {
        if (d.which == 13) {
            d.preventDefault();
            return false;
        }
    });

    jQuery(function () {
        jQuery('[data-toggle="popover"]').popover({
            container: "body",
            trigger: "focus hover",
            placement: "top",
            html: true,
            content: popoverContent
        });
    });

    var c = {
        content: "",
        placement: "top",
        trigger: "manual",
        html: true,
        "template": '<div class="popover popover-danger" role="tooltip"><div class="arrow"></div><div class="popover-content"></div></div>'
    };

    jQuery("#tPassword").popover({
        content: function () {
            return jQuery("#popover_content_wrapper").html();
        },
        placement: "bottom",
        trigger: "manual",
        html: true,
        "template": '<div class="popover" role="tooltip"><div class="arrow"></div><div class="popover-content"></div></div>'
    });

    jQuery("#tPassword").
    on("keydown", function () {
        jQuery("#form-group-tPassword").removeClass("has-error");
    }).
    on("keyup", function (d) {
        d.stopImmediatePropagation();
        ValidatePasswordv2(jQuery(this));
    }).
    on("focus", function () {
        ValidatePasswordv2(jQuery(this));
        jQuery("#tPassword").popover("show");
    }).
    on("blur", function () {
        jQuery(this).popover("hide");
        if (jQuery("#tPassword").val() == "") {
            jQuery("#form-group-tPassword").addClass("has-error");
        }
        else {
            var d = {};
            d["fieldToValidate"] = jQuery(this).attr("name");
            d[jQuery(this).attr("name")] = jQuery(this).val();
            d["isAjax"] = true;
            d["ajaxUrl"] = jQuery( '#action-url' ).val();
            validateFieldMapJSON(d, false, function (f) { validateFieldMapJSONField(f, d); });
        }
    }).
    on("focusout", function () {
        //When tab from another input
        passwordRetypeField = jQuery("#tPasswordRetype");
        pwd2 = passwordRetypeField.val();
        if ((jQuery(this).val().length > 0) && (pwd2.length == 0)) {  }
        else {
            if (jQuery(this).val().length == 0) { }
            else {
                if (jQuery(this).val() == pwd2) {
                    jQuery("#form-group-tPassword").removeClass("has-error");
                }
                else {
                    jQuery("#form-group-tPassword").addClass("has-error");
                }
            }
        }
    }).
    on("click", function () {
        clearValidationError('tPassword');
    });

    jQuery("#tPasswordRetype").popover({
        content: function () {
            return jQuery("#popover_content_wrapper_retype").html();
        },
        placement: "bottom",
        trigger: "manual",
        html: true,
        "template": '<div class="popover" role="tooltip"><div class="arrow"></div><div class="popover-content"></div></div>'
    });

    jQuery("#tPasswordRetype").
    on("keydown", function () {
        jQuery("#form-group-tPasswordRetype").removeClass("has-error");
    }).
    on("keyup", function (d) {
        d.stopImmediatePropagation();
        ValidateRetypePassword(jQuery(this), jQuery("#tPassword"));
    }).
    on("focus", function () {
        ValidateRetypePassword(jQuery(this), jQuery("#tPassword"));
        jQuery("#tPasswordRetype").popover("show");
    }).
    on("blur", function () {
        jQuery(this).popover("hide");
    }).
    on("focusout", function () {
        pwd2 = jQuery("#tPasswordRetype").val();
        if (jQuery("#form-group-tPassword").hasClass("has-error") || pwd2.length == 0) {
            jQuery("#form-group-tPasswordRetype").addClass("has-error");
        }
        else {
            if (jQuery("#tPassword").val() == pwd2) {
                jQuery("#form-group-tPasswordRetype").removeClass("has-error");
            }
            else {
                jQuery("#form-group-tPasswordRetype").addClass("has-error");
            }
        }
    }).
    on("click", function () {
        clearValidationError('tPasswordRetype');
    });
});