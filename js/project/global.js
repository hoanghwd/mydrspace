var GlobalReg = {
    UNITED_STATES: "840 UNITED STATES",
    UNITED_STATES_ABBR: "US",
    EXACT_MATCH: "EXACT MATCH",
    DEFAULT_MATCH: "DEFAULT MATCH",
    INVALID_ADDRESS: "INVALID-ADDRESS",
    INVALID_ZIPCODE: "INVALID-ZIPCODE",
    INVALID_STATE: "INVALID-STATE",
    INVALID_CITY: "INVALID-CITY",
    SUCCESS: "success",
    ERROR: "ERROR",
    CONFIRM: "confirm",
    COMPANY_ADDED: "COMPANY-ADDED",
    errorMsgs: {
        INVALID_ADDRESS_MSG: "You've entered an invalid address",
        INVALID_ZIPCODE_MSG: "You've entered an invalid ZIP Code",
        INVALID_STATE_MSG: "You've entered an invalid state",
        INVALID_CITY_MSG: "You've entered an invalid city"
    },
    regExps: {
        regExptAllSpaces: /^[\s]*$/,
        regExptAllNumbers: /^[\d]*$/,
        regExptUserName: /^(?=.*[a-zA-Z\d])(\w|[\.\@\-\?\,\&\''\/\_\""]){6,50}$/,
        regExptPassword: /^(?:(?!([a-zA-Z0-9-().&@?""#,+''\s\/])\1\1)[a-zA-Z0-9-().&@?""#,+''\s\/]){7,}$/,
        regExptSecAnswer: /^(\w|[a-zA-Z\d\s\.\@\-\?\,\&\/\_\#\+\(\)\""\'']){3,50}$/,
        regExptFName: /^[a-zA-Z \''\-\.]+$/,
        regExptLName: /^[a-zA-Z \''\-\.]+$/,
        regExptName: /^[a-zA-Z \''\-\.]+$/,
        regExptMI: /^[a-zA-Z \''\-\.]*$/,
        regExptCompany: /^[A-Za-z0-9\-\.'_,\"\&()?#\/+@\s]+$/,
        regExptAddress: /^[0-9A-Za-z'\-\._\",\&()?#\/+@\s]+$/,
        regExptApt: /^[0-9A-Za-z'\-\._\",\&()?#\/+@\s]*$/,
        regExptCity: /^[A-Za-z'\-\.\s]+$/,
        regExptZip: /^(\d{5}-\d{4})|(\d{5})$/,
        regExptUrbanCode: /^[A-Za-z0-9""-\.''\,&\(\)\?#\/\+@ ]{0,20}$/,
        regExptAddress1: /^[0-9A-Za-z'\-\._\",\&()?#\/+@\s]+$/,
        regExptAddress2: /^[0-9A-Za-z'\-\._\",\&()?#\/+@\s]*$/,
        regExptAddress3: /^[0-9A-Za-z'\-\._\",\&()?#\/+@\s]*$/,
        regExptCityInt: /^[A-Za-z'\-\.\s]+$/,
        regExptProvince: /^[A-Za-z'\-\.\s]*$/,
        regExptPostalCode: /^[A-Za-z0-9\s]{0,10}$/,
        regExptEmail: /\b(^['_A-Za-z0-9+-]+(\.['_A-Za-z0-9+-]+)*@([A-Za-z0-9-])+(\.[A-Za-z0-9-]+)*((\.[A-Za-z0-9]{2,})|(\.[A-Za-z0-9]{2,}\.[A-Za-z0-9]{2,}))$)\b/,
        regExptPhone: /^([2-9]\d{2})[- ]?(\d{3})[- ]?(\d{4})$/,
        regExptExt: /^[0-9-]*$/,
        regExptPhoneInternational: /^\d{10,15}$/,
        regExptFaxInternational: /^(\d{10,15})*$/,
        regExptFax: /^(([2-9]\d{2})[- ]?(\d{3})[- ]?(\d{4}))*$/,
        regExptMobileInternational: /^(\d{10,15})*$/,
        regExptMobile: /^(([2-9]\d{2})[- ]?(\d{3})[- ]?(\d{4}))*$/
    }
};

/**
 *
 * @param e
 * @param a
 * @param f
 */
var validateFieldMapJSON = function(e, a, f) {
    var d = {};
    d["fieldName"] = e["fieldToValidate"];
    for (var c in e) {
        if (c != "fieldToValidate") {
            d[c] = a ? jQuery.trim(e[c]) : e[c];

        }
    }//for

    jQuery.ajax({
        url     : d["ajaxUrl"],
        cache   : false,
        type    : "post",
        dataType: "json",
        data    : d
    }).
    done(function(j) {
        if (j.rs == "success") {
            b = {
                "status": j.rs
            };
        }
        else {
            b = {
                "status": j.rs,
                "errors": [],
                "list"  : []
            };

            if(j.rs === 'alternate') {
                jQuery.each(j.alternateNameList, function () {
                    b.list.push(this);
                });
            }

            if (j.errors) {
                for (var g = 0; g < j.errors.length; g++) {
                    b.errors.push({
                        "message": j.errors[g]
                    });
                }
            }
            if (j.actionErrors) {
                for (var g = 0; g < j.actionErrors.length; g++) {
                    b.errors.push({
                        "message": j.actionErrors[g]
                    });
                }
            }
            if (j.fieldErrors) {
                for (fieldName in j.fieldErrors) {
                    if (j.fieldErrors.hasOwnProperty(fieldName)) {
                        for (var h = 0; h < j.fieldErrors[fieldName].length; h++) {
                            b.errors.push({
                                "field": fieldName,
                                "message": j.fieldErrors[fieldName][h]
                            });
                        }
                    }

                }
            }
            if (b.errors.length < 1) {
                b = {
                    "status": j.rs
                };
            }
        }
        f(b);
    }).
    always(function() {}).
    fail(function() {
        b = {
            "status": "fail"
        };
        f(b);
    });
};

(function(a) {
    a.validateField = function(d) {
        var e = a.extend({
            field: "#fieldname",
            fields: ["#fieldname", "#fieldName2"],
            required: false,
            icon: "false",
            trim: true
        }, d);
        var f = a(e.field);
        var c = f.attr("id");
        var i = a(e.fields);
        var h = e.icon;
        var g = e.required;
        var b = e.trim;

        f.on("keyup", function(j) {
            if (a("#form-group-" + escapeElementId(c)).hasClass("has-error")) {
                a("#form-group-" + escapeElementId(c)).removeClass("has-error");
                a("#error-" + escapeElementId(c)).empty();
                a("#" + escapeElementId(c)).removeAttr("aria-invalid");
                a("#" + escapeElementId(c)).removeClass("is-invalid");
                a("#sr-only-error-" + escapeElementId(c)).empty();
            }
        }).
        on("focusin", function() {
            if (a("#form-group-" + escapeElementId(c)).hasClass("has-error")) {}
        }).
        on("focusout", function() {
            var k = {};
            k["fieldToValidate"] = a(this).attr("name");
            for (var j = 0; j < i.length; j++) {
                if (a(i[j]).attr("type") === "radio") {
                    k[a(i[j]).attr("name")] = a("input[name=" + a(i[j]).attr("name") + "]:radio:checked").val();
                }
                else {
                    k[a(i[j]).attr("name")] = a(i[j]).val();
                }
            }
            k["required"] = g;
            k["isAjax"] = true;
            k["ajaxUrl"] = jQuery( '#action-url' ).val();
            validateFieldMapJSON(k, false, function (e) { validateFieldMapJSONField(e, k); });
        });
    };
}(jQuery));

