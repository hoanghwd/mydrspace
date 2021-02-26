/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var urbanization = function() {
    if (jQuery("#sstate").val() == GlobalReg.PUERTO_RICO_ABBR) {
        jQuery("#urbanCode").removeClass("hidden");
    } else {
        jQuery("#urbanCode").addClass("hidden");
        jQuery("#turbanCode").val("");
    }
};
var countrySwap = function() {
    jQuery("#addressHolderStep1 div.form-group").removeClass("has-error");
    jQuery("#zipHolderStep1 div.form-group").removeClass("has-error");
    jQuery("#cridHolderStep1 div.form-group").removeClass("has-error");
    if (jQuery("#scountry").val() != GlobalReg.UNITED_STATES) {
        jQuery("#international").removeClass("hidden");
        jQuery("#us").addClass("hidden");
        jQuery("input[id^=rZipCodeLookup]:radio").attr("disabled", true).closest("label").addClass("disabled");
        if ($("input[name=raccount]:radio:checked").val() == "personal") {
            jQuery("#a-address-step1-row").addClass("hidden");
            jQuery("#privacy-policy-section").fadeIn();
        }
    } else {
        jQuery("#international").addClass("hidden");
        jQuery("#us").removeClass("hidden");
        jQuery("input[id^=rZipCodeLookup]:radio").attr("disabled", false).closest("label").removeClass("disabled");
        jQuery("#a-address-step1-row").removeClass("hidden");
        jQuery("#privacy-policy-section").hide();
    }
};
var progressWizard = function(a, b) {
    jQuery("#" + a + "").addClass("hidden");
    jQuery("#" + b + "").removeClass("hidden");
    jQuery("#" + a + "-heading").addClass("hidden");
    jQuery("#" + b + "-heading").removeClass("hidden");
    if (jQuery("#" + a + "-sub-heading").length > 0) {
        jQuery("#" + a + "-sub-heading").addClass("hidden");
    }
    if (jQuery("#" + b + "-sub-heading").length > 0) {
        jQuery("#" + b + "-sub-heading").removeClass("hidden");
    }
};
var progressWizardFinished = function(b, c, a) {
    a = a || "";
    if (jQuery("#ams-verified").val() == "true") {
        jQuery("#" + a + "confirmed-address").removeClass("hidden");
        jQuery("#" + a + "unconfirmed-address").addClass("hidden");
        jQuery("#" + a + "ams-verified-icon").removeClass("glyphicon-exclamation-sign icon-yellow").addClass("glyphicon-ok-sign icon-green");
    } else {
        jQuery("#" + a + "confirmed-address").addClass("hidden");
        jQuery("#" + a + "unconfirmed-address").removeClass("hidden");
        jQuery("#" + a + "ams-verified-icon").removeClass("glyphicon-ok-sign icon-green").addClass("glyphicon-exclamation-sign icon-yellow");
    }
    jQuery("#address-c").val("true");
    jQuery("#" + b + "").addClass("hidden");
    jQuery("#" + c + "").removeClass("hidden");
    jQuery("#privacy-policy-section").fadeIn();
};
var clearAddressForm = function() {
    jQuery("#addressHolderStep1 select").prop("selectedIndex", 0).trigger("change");
    jQuery("#zipHolderStep1 select").prop("selectedIndex", 0);
    jQuery("#cridHolderStep1 select").prop("selectedIndex", 0);
    jQuery("#addressHolderStep1 input").val("");
    jQuery("#zipHolderStep1 input").val("");
    jQuery("#cridHolderStep1 input").val("");
};
var resetLookupPanels = function() {
    jQuery("[id*='Step']").addClass("hidden");
    jQuery("[id*='Step1']").removeClass("hidden");
    countrySwap();
    jQuery("#address-c").val("false");
    jQuery("#ams-verified").val("false");
    jQuery("#company-crid").val("");
    jQuery("#delivery-point").val("");
    jQuery("#ams-status").val("");
    jQuery("#privacy-policy-section").hide();
};
var showSelectedLookupPanel = function() {
    jQuery(".lookup").addClass("hidden");
    resetLookupPanels();
    if (jQuery("input[name=rlookup]:radio:checked").val() == "address") {
        jQuery("#find-by-address").removeClass("hidden");
        jQuery("#address-section-lookup").removeClass("hidden");
    } else {
        if ($("input[name=rlookup]:radio:checked").val() == "zipcode") {
            jQuery("#find-by-zip").removeClass("hidden");
            jQuery("#zip-section-lookup").removeClass("hidden");
        } else {
            if (jQuery("input[name=rlookup]:radio:checked").val() == "company") {
                jQuery("#company-section-lookup").fadeIn();
            } else {
                if (jQuery("input[name=rlookup]:radio:checked").val() == "crid") {
                    jQuery("#find-by-crid").removeClass("hidden");
                    jQuery("#crid-section-lookup").removeClass("hidden");
                }
            }
        }
    }
};
var addressWizardPersonalSetup = function() {
    jQuery("#company-wrap").addClass("hidden");
    jQuery("#a-address-step1").off().on("click", personalAddressStep1);
    jQuery("#a-address-step2").off().on("click", personalAddressStep2);
    jQuery("#a-address-step2-prev").off().on("click", personalAddressStep2Prev);
    jQuery("#a-address-step3").off().on("click", personalAddressStep3);
    jQuery("#a-address-step3-prev").off().on("click", personalAddressStep3Prev);
    jQuery("#a-address-step4").off();
    jQuery("#a-address-step4-prev").off();
    jQuery("#a-address-step5").off();
    jQuery("#a-address-step6-prev").off().on("click", personalAddressStep6Prev);
};
var addressWizardBusinessSetup = function() {
    jQuery("#company-wrap").removeClass("hidden");
    jQuery("#a-address-step1").off().on("click", businessAddressStep1);
    jQuery("#a-address-step2").off().on("click", businessAddressStep2);
    jQuery("#a-address-step2-prev").off().on("click", businessAddressStep2Prev);
    jQuery("#a-address-step3").off().on("click", businessAddressStep3);
    jQuery("#a-address-step3-prev").off().on("click", businessAddressStep3Prev);
    jQuery("#a-address-step4").off().on("click", businessAddressStep4);
    jQuery("#a-address-step4-prev").off().on("click", businessAddressStep4Prev);
    jQuery("#a-address-step5").off().on("click", businessAddressStep5);
    jQuery("#a-address-step6-prev").off().on("click", businessAddressStep6Prev);
};
var drProfileSetup = function() {
    jQuery("#dr-profile-section").show();
    jQuery('#tSpecialties').addClass('required-entry');

};
var step6Array = [
    'tMedicalType',
    'tMedicalType',
    'tSpecialties',
    'tEducation',
    'tSchoolOfGraduate',
    'tInNetworkInsurances',
    'tHospitalAffiliations',
    'tResidency_1',
    'tProfessionalStatement',
    'tcompany'
];
var clearDrProfileForm = function() {
    for(var i = 0; i < step6Array.length; i++) {
        jQuery('#' + step6Array[i]).val("");
    }
}

var addressWizardPersonalZipSetup = function() {
    jQuery("#tzipSearchCompanyWidget").addClass("hidden");
    jQuery("#a-findzip").off().on("click", zipLookupStep1);
    jQuery("#a-step2").off().on("click", zipLookupStep2);
    jQuery("#a-step2-prev").off().on("click", zipLookupStep2Prev);
    jQuery("#a-step3").off().on("click", zipLookupStep3);
    jQuery("#a-step3-prev").off().on("click", zipLookupStep3Prev);
    jQuery("#a-step4").off().on("click", zipLookupStep4);
    jQuery("#a-step4-prev").off().on("click", zipLookupStep4Prev);
    jQuery("#a-step5").off().on("click", zipLookupStep5);
    jQuery("#a-step5-prev").off().on("click", zipLookupStep5Prev);
    jQuery("#a-step6").off().on("click", zipLookupStep6);
    jQuery("#a-step8-prev").off().on("click", zipLookupStep8Prev);
};
var addressWizardBusinessZipSetup = function() {
    jQuery("#tzipSearchCompanyWidget").removeClass("hidden");
    jQuery("#a-findzip").off().on("click", zipLookupStep1);
    jQuery("#a-step2").off().on("click", zipLookupStep2);
    jQuery("#a-step2-prev").off().on("click", zipLookupStep2Prev);
    jQuery("#a-step3").off().on("click", zipLookupStep3);
    jQuery("#a-step3-prev").off().on("click", zipLookupStep3Prev);
    jQuery("#a-step4").off().on("click", zipLookupStep4);
    jQuery("#a-step4-prev").off().on("click", zipLookupStep4Prev);
    jQuery("#a-step5").off().on("click", zipLookupStep5);
    jQuery("#a-step5-prev").off().on("click", zipLookupStep5Prev);
    jQuery("#a-step6").off().on("click", zipLookupStep6);
    jQuery("#a-step7").off().on("click", zipLookupStep7);
    jQuery("#a-step8-prev").off().on("click", zipLookupStep8Prev);
};
var addressObjDebug = function(a) {
    console.log(a.company);
    console.log(a.address1);
    console.log(a.address2);
    console.log(a.address3);
    console.log(a.city);
    console.log(a.state);
    console.log(a.postalcode);
    console.log(a.urbancode);
    console.log(a.country);
    console.log(a.deliverypoint);
    console.log(a.amsstatus);
};
var populateAddressForm = function(a) {
    jQuery("#tcompany").val(htmlDecode(a.company));
    jQuery("#scountry").val(a.country);
    if (a.country == GlobalReg.UNITED_STATES) {
        jQuery("#taddress").val(htmlDecode(a.address1));
        jQuery("#tapt").val(htmlDecode(a.address2));
        jQuery("#tcity").val(a.city);
        jQuery("#sstate").val(a.state);
        urbanization();
        jQuery("#tzip").val(a.postalcode);
        jQuery("#turbanCode").val(a.urbancode);
        if (a.hasOwnProperty("deliverypoint")) {
            jQuery("#delivery-point").val(a.deliverypoint);
        }
        if (a.hasOwnProperty("amsstatus")) {
            jQuery("#ams-status").val(a.amsstatus);
        }
    } else {
        jQuery("#taddress1").val(htmlDecode(a.address1));
        jQuery("#taddress2").val(htmlDecode(a.address2));
        jQuery("#taddress3").val(htmlDecode(a.address3));
        jQuery("#tcityInt").val(a.city);
        jQuery("#tprovince").val(a.state);
        jQuery("#tpostalCode").val(a.postalcode);
    }
};
var populateFinalAddressDisplay = function(a) {
    addressObjDebug(a);
    jQuery("#final-address div.company").html(sanitizeHtmlOut(a.company));
    jQuery("#final-address div.nickname").html(sanitizeHtmlOut(a.nickname));
    jQuery("#final-address div.address1").html(sanitizeHtmlOut(a.address1));
    jQuery("#final-address div.address2").html(sanitizeHtmlOut(a.address2));
    jQuery("#final-address div.address3").html(sanitize(a.address3));
    jQuery("#final-address div span.city").html(sanitizeHtmlOut(a.city));
    jQuery("#final-address div span.state").html(sanitizeHtmlOut(a.state));
    jQuery("#final-address div span.zip").html(sanitizeHtmlOut(a.postalcode));
    jQuery("#final-address span.urban").html(sanitizeHtmlOut(a.urbancode));
    if (a.country != GlobalReg.UNITED_STATES) {
        jQuery("#final-address div.country").html(sanitizeHtmlOut((a.country).substr((a.country).indexOf(" ") + 1)));
    } else {
        jQuery("#final-address div.country").html("");
    }
};
var populateFinalZipAddressDisplay = function(a) {
    jQuery("#zip-final-address div.company").html(sanitizeHtmlOut(a.company));
    jQuery("#zip-final-address div.nickname").html(sanitizeHtmlOut(a.nickname));
    jQuery("#zip-final-address div.address1").html(sanitizeHtmlOut(a.address1));
    jQuery("#zip-final-address div.address2").html(sanitizeHtmlOut(a.address2));
    jQuery("#zip-final-address div.address3").html(sanitize(a.address3));
    jQuery("#zip-final-address div span.city").html(sanitizeHtmlOut(a.city));
    jQuery("#zip-final-address div span.state").html(sanitizeHtmlOut(a.state));
    jQuery("#zip-final-address div span.zip").html(sanitizeHtmlOut(a.postalcode));
    jQuery("#zip-final-address div.urban").html(sanitizeHtmlOut(a.urbancode));
};
var populateFinalCridAddressDisplay = function(a) {
    jQuery("#crid-final-address div.company").html(sanitizeHtmlOut(a.company));
    jQuery("#crid-final-address div.nickname").html(sanitizeHtmlOut(a.nickname));
    jQuery("#crid-final-address div.address1").html(sanitizeHtmlOut(a.address1));
    jQuery("#crid-final-address div.address2").html(sanitizeHtmlOut(a.address2));
    jQuery("#crid-final-address div.address3").html(sanitize(a.address3));
    jQuery("#crid-final-address span.city").html(sanitizeHtmlOut(a.city));
    jQuery("#crid-final-address span.state").html(sanitizeHtmlOut(a.state));
    jQuery("#crid-final-address span.zip").html(sanitizeHtmlOut(a.postalcode));
    jQuery("#crid-final-address span.urban").html(sanitizeHtmlOut(a.urbancode));
    if (a.country != GlobalReg.UNITED_STATES) {
        jQuery("#crid-final-address div.country").html(sanitizeHtmlOut((a.country).substr((a.country).indexOf(" ") + 1)));
    } else {
        jQuery("#crid-final-address div.country").html("");
    }
};
var rangeCalc = function(b, x) {
    var f = "";
    var o = "";
    var w = false;
    var r = false;
    var n = false;
    var p = false;
    var t = jQuery("#div-" + b.attr("id") + " span.address1").html();
    var a = t.indexOf("(")
        , g = t.indexOf(")");
    if (a == 0) {
        n = true;
    }
    if (a > -1 && g > a) {
        f = t.substr(0, a) + t.substr(g + 1);
        o = t.substr(a + 1, ((g - 1) - a));
        var A = o.split(" ");
        var v = 1
            , k = 2
            , j = ""
            , z = "";
        var l = "";
        var d = "";
        if (A.length == 4) {
            l = A[1];
            d = A[3];
        } else {
            l = A[2];
            d = A[4];
            if (A[0].toLowerCase() == "odd") {
                w = true;
            }
            if (A[0].toLowerCase() == "even") {
                r = true;
            }
        }
        if ($.isNumeric(l)) {
            v = parseInt(l);
        } else {
            v = parseInt(l.match(/\d+/));
            j = l.match(/[a-zA-Z]+/);
            if (l.indexOf(j) == 0) {
                p = true;
            }
        }
        if ($.isNumeric(d)) {
            k = parseInt(d);
        } else {
            k = parseInt(d.match(/\d+/));
            z = d.match(/[a-zA-Z]+/);
        }
        var m = ""
            , c = "";
        if (v < k) {
            var h = true;
            for (var u = v; u <= k; u++) {
                if (w) {
                    h = (u % 2 == 0) ? false : true;
                } else {
                    if (r) {
                        h = (u % 2 == 0) ? true : false;
                    }
                }
                if (h) {
                    if (j != "") {
                        c = (p) ? j + u : u + j;
                    } else {
                        c = u;
                    }
                    m += '<option value="' + c + '">' + c + "</option>";
                }
            }
        } else {
            if (j[0].toLowerCase() == z[0].toLowerCase()) {
                var y = [];
                var q = l.match(/\d(.+)$/)[1];
                while (String(q).charCodeAt(0) <= String(d.match(/\d(.+)$/)[1]).charCodeAt(0)) {
                    y.push(q);
                    q = String.fromCharCode(String(q).charCodeAt(0) + 1);
                }
                for (var u = 0; u < y.length; u++) {
                    c = j + v + y[u];
                    m += '<option value="' + c + '">' + c + "</option>";
                }
            } else {
                if (j[0].length > 1 && z[0].length > 1 && !$.isNumeric(v) && !$.isNumeric(k)) {
                    s = j[0].charAt(j[0].length - 1);
                    e = z[0].charAt(z[0].length - 1);
                    var y = [];
                    var q = s;
                    while (String(q).charCodeAt(0) <= String(e).charCodeAt(0)) {
                        y.push(q);
                        q = String.fromCharCode(String(q).charCodeAt(0) + 1);
                    }
                    for (var u = 0; u < y.length; u++) {
                        c = (j[0].charAt(0) + y[u]);
                        m += '<option value="' + c + '">' + c + "</option>";
                    }
                } else {
                    var y = [];
                    var q = j;
                    while (String(q).charCodeAt(0) <= String(z).charCodeAt(0)) {
                        y.push(q);
                        q = String.fromCharCode(String(q).charCodeAt(0) + 1);
                    }
                    for (var u = 0; u < y.length; u++) {
                        c = ($.isNumeric(v)) ? ((p) ? y[u] + v : v + y[u]) : y[u];
                        m += '<option value="' + c + '">' + c + "</option>";
                    }
                }
            }
        }
        jQuery("#" + x + "ste-range").empty().append(m);
    }
    if (n) {
        jQuery("#" + x + "range-address1").before(jQuery("#" + x + "ste-range"));
        jQuery("#" + x + "ste-range").addClass("begin");
    } else {
        jQuery("#" + x + "ste-range").before(jQuery("#" + x + "range-address1"));
        jQuery("#" + x + "ste-range").removeClass("begin");
    }
    jQuery("#" + x + "range-address1").html(f);
    jQuery("#" + x + "range-address2").html(jQuery("#div-" + b.attr("id") + " span.address2").html());
    jQuery("#" + x + "range-city").html(jQuery("#div-" + b.attr("id") + " span.city").html());
    jQuery("#" + x + "range-state").html(jQuery("#div-" + b.attr("id") + " span.state").html());
    jQuery("#" + x + "range-zip").html(jQuery("#div-" + b.attr("id") + " span.zip").html());
};
var updateViewport = function(a, f, b) {
    b = b || 260;
    var c = jQuery("#" + a).height();
    if (c < b) {
        jQuery("#" + f + " .viewport").css("height", (c + 20));
    } else {
        jQuery("#" + f + " .viewport").css("height", b);
    }
    var d = jQuery("#" + f).data("plugin_tinyscrollbar");
    d.update();
};
var amsLookupService = function(b, c) {
    var a;
    jQuery.ajax({
        url: "/entreg/json/AmsValidateLookupAction",
        cache: false,
        type: "post",
        data: {
            taddress: b.address1,
            tapt: b.address2,
            tcity: b.city,
            sstate: b.state,
            tzip: b.postalcode,
            turbanCode: b.urbancode,
            scountry: b.country
        }
    }).done(function(d) {
        a = {
            "rs": d.rs,
            "data": d
        };
        c(a);
    }).always(function() {}).fail(function(d, g, f) {
        a = {
            "rs": "error",
            "data": {
                "rs": "error",
                "rsMsg": "eReg: " + f,
                "jqXHR": d,
                "textStatus": g
            }
        };
        c(a);
    });
};
var amsUpdateService = function(b, c) {
    var a;
    $.ajax({
        url: "/entreg/secure/json/AmsUpdateAction",
        cache: false,
        type: "post",
        data: {
            taddress: b.address,
            tapt: b.apt,
            tcity: b.city,
            sstate: b.state,
            tzip: b.zip,
            turbanCode: b.urbancode,
            scountry: b.country
        }
    }).done(function(d) {
        a = {
            "rs": d.rs,
            "data": d
        };
        c(a);
    }).always(function() {}).fail(function(d, g, f) {
        a = {
            "rs": "error",
            "data": {
                "rs": "error",
                "rsMsg": f,
                "jqXHR": d,
                "textStatus": g
            }
        };
        c(a);
    });
};
var matchingCompanyLookupService = function(c, d, b, f) {
    var a;
    $.ajax({
        url: "/entreg/json/MatchingCompanyLookupAction",
        cache: false,
        type: "post",
        data: {
            companyName: c.company,
            address: c.address1,
            apt: c.address2,
            address3: c.address3,
            city: c.city,
            state: c.state,
            urban: c.urbancode,
            zip: c.postalcode,
            country: c.country,
            companyMatch: d,
            crid: b
        }
    }).done(function(g) {
        a = {
            "rs": g.rs,
            "data": g
        };
        f(a);
    }).always(function() {}).fail(function(g, i, h) {
        a = {
            "rs": "error",
            "data": {
                "rs": "error",
                "rsMsg": h,
                "jqXHR": g,
                "textStatus": i
            }
        };
        f(a);
    });
};
var validateAddressService = function(b, c) {
    var a;
    $.ajax({
        url: "/entreg/json/ValidateAddressAction",
        cache: false,
        type: "post",
        data: {
            scountry: $.trim(b.scountry),
            tcompany: $.trim(b.tcompany),
            taddress: $.trim(b.taddress),
            tapt: $.trim(b.tapt),
            tcity: $.trim(b.tcity),
            sstate: $.trim(b.sstate),
            tzip: $.trim(b.tzip),
            turbanCode: $.trim(b.turbancode),
            taddress1: $.trim(b.taddress1),
            taddress2: $.trim(b.taddress2),
            taddress3: $.trim(b.taddress3),
            tcityInt: $.trim(b.tcityInt),
            tprovince: $.trim(b.tprovince),
            tpostalCode: $.trim(b.tpostalCode),
            accountType: b.accountType
        }
    }).done(function(d) {
        a = {
            "rs": d.rs,
            "data": d
        };
        c(a, b);
    }).always(function() {}).fail(function(d, g, f) {
        a = {
            "rs": "error",
            "data": {
                "rs": "error",
                "rsMsg": f,
                "jqXHR": d,
                "textStatus": g
            }
        };
        c(a);
    });
};
var companiesSimilarService = function(c, d, b, f) {
    var a;
    $.ajax({
        url: "/entreg/json/CompaniesSimilarAction",
        cache: false,
        type: "post",
        data: {
            companyName: c.company,
            address: c.address1,
            apt: c.address2,
            city: c.city,
            state: c.state,
            postalcode: c.postalcode,
            zip: c.postalcode,
            urban: c.urbancode,
            country: c.country,
            doNotAms: c.doNotAMS,
            companyMatch: d,
            crid: b
        }
    }).done(function(g) {
        a = {
            "rs": g.rs,
            "data": g
        };
        f(a);
    }).always(function() {}).fail(function(g, i, h) {
        a = {
            "rs": "error",
            "data": {
                "rs": "error",
                "rsMsg": h,
                "jqXHR": g,
                "textStatus": i
            }
        };
        f(a);
    });
};
var addCompanyService = function(b, c) {
    var a;
    $.ajax({
        url: "/entreg/json/AddCompanyAction",
        cache: false,
        type: "post",
        data: {
            companyName: b.company,
            address: b.address1,
            apt: b.address2,
            address3: b.address3,
            city: b.city,
            state: b.state,
            zip: b.postalcode,
            urban: b.urbancode,
            country: b.country,
            doNotAms: b.doNotAMS
        }
    }).done(function(d) {
        a = {
            "rs": d.rs,
            "data": d
        };
        c(a);
    }).always(function() {}).fail(function(d, g, f) {
        a = {
            "rs": "error",
            "data": {
                "rs": "error",
                "rsMsg": f,
                "jqXHR": d,
                "textStatus": g
            }
        };
        c(a);
    });
};
var eFXSimilarService = function(d, b, a, f) {
    var c;
    $.ajax({
        url: "/entreg/json/EFXSimilarAction",
        cache: false,
        type: "post",
        data: {
            crid: d,
            groupRefId: b,
            addressRefId: a
        }
    }).done(function(g) {
        c = {
            "rs": g.rs,
            "data": g
        };
        f(c);
    }).always(function() {}).fail(function(g, i, h) {
        c = {
            "rs": "error",
            "data": {
                "rs": "error",
                "rsMsg": h,
                "jqXHR": g,
                "textStatus": i
            }
        };
        f(c);
    });
};
var zipLookupService = function(b, c) {
    var a;
    $.ajax({
        url: "/entreg/json/ZipLookupAction",
        cache: false,
        type: "post",
        data: {
            tzipsearch: b
        }
    }).done(function(d) {
        a = {
            "rs": d.rs,
            "data": d
        };
        c(a);
    }).always(function() {}).fail(function(d, g, f) {
        a = {
            "rs": "error",
            "data": {
                "rs": "error",
                "rsMsg": f,
                "jqXHR": d,
                "textStatus": g
            }
        };
        c(a);
    });
};
var setupStepAMS = function(f, d, a) {
    a = a || "";
    if (f.amsStatus == GlobalReg.ADDRESS_NOT_FOUND || f.amsAddressList.length < 1) {
        jQuery("#" + d + " .found").addClass("hidden");
        jQuery("#" + d + " .notfound").removeClass("hidden");
    } else {
        jQuery("#" + d + " .found").removeClass("hidden");
        jQuery("#" + d + " .notfound").addClass("hidden");
    }
    jQuery("#div-" + a + "ams-same label span.address1").html(f.address);
    jQuery("#div-" + a + "ams-same label span.address2").html(f.apt);
    jQuery("#div-" + a + "ams-same label span.city").html(f.city);
    jQuery("#div-" + a + "ams-same label span.state").html(f.state);
    jQuery("#div-" + a + "ams-same label span.zip").html(f.zip);
    jQuery("#div-" + a + "ams-same label span.urban").html(f.urban);
    var b = [];
    var c = 0;
    $.each(f.amsAddressList, function() {
        c++;
        var g = "false";
        if ((/\(range/i.test(this.addressLine1)) || (/\(even range/i.test(this.addressLine1)) || (/\(odd range/i.test(this.addressLine1))) {
            g = "true";
        }
        b.push('<div id="div-address' + c + '" class="radio">', '<label for="address' + c + '">', '<input type="radio" id="address' + c + '" name="address" rel="' + g + '" />', '<span class="urban">' + ((this.urbanizationCode != null) ? this.urbanizationCode : "") + "</span>", '<span class="address1">' + this.addressLine1.replace(/\sSTE\s/g, "<br>STE ") + "</span>", '<span class="address2">' + (this.addressLine2 != null) ? this.addressLine2 : "" + "</span>", '<span class="city">' + this.city + '</span> <span class="state">' + this.state + '</span> <span class="zip">' + this.postalCode + "</span>", "</label>", "</div>");
    });
    jQuery("#" + a + "#returnedAddresses").html(b.join(""));
};
var setupStepCompanyMatch = function(d, c, a) {
    a = a || "";
    jQuery("#" + a + "div-company-same-match label span.company").html(c.company);
    jQuery("#" + a + "div-company-same-match label span.address1").html(c.address1);
    jQuery("#" + a + "div-company-same-match label span.address2").html(c.address2);
    jQuery("#" + a + "div-company-same-match label span.address3").html(c.address3);
    jQuery("#" + a + "div-company-same-match label span.city").html(c.city);
    jQuery("#" + a + "div-company-same-match label span.state").html(c.state);
    jQuery("#" + a + "div-company-same-match label span.urbancode").html(c.urbancode);
    jQuery("#" + a + "div-company-same-match label span.postalcode").html(c.postalcode);
    if (c.country != GlobalReg.UNITED_STATES) {
        jQuery("#" + a + "div-company-same-match label span.country").html((c.country).substr((c.country).indexOf(" ") + 1));
    } else {
        jQuery("#" + a + "div-company-same-match label span.country").html("");
    }
    if (d.hasOwnProperty("companyMatchStatus")) {
        if (d.companyMatchStatus == "COMPANY-EXACT-MATCH-FOUND" && d.matchedCompaniesAddressList.length < 1) {
            jQuery("#orginal-address").hide();
        } else {
            jQuery("#orginal-address").show();
        }
    } else {
        jQuery("#orginal-address").show();
    }
    var f = [];
    var b = 0;
    $.each(d.matchedCompaniesAddressList, function() {
        b++;
        f.push('<div id="div-' + a + "company-match-address" + b + '" class="radio">', '<label for="' + a + "company-match-address" + b + '">', '<input type="radio" id="' + a + "company-match-address" + b + '" name="' + a + 'company-match-address" value="' + this.crid + '" />', '<span class="company">' + this.companyName + "</span>", '<span class="urban">' + ((this.urbanizationCode != null) ? this.urbanizationCode : "") + "</span>", '<span class="address1">' + this.addressLine1 + "</span>", '<span class="address2">' + ((this.addressLine2 != null) ? this.addressLine2 : "") + "</span>", '<span class="address3">' + ((this.addressLine3 != null) ? this.addressLine3 : "") + "</span>", '<span class="city">' + this.city + '</span> <span class="state">' + ((this.state != null) ? this.state : "") + '</span> <span class="zip">' + ((this.postalCode != null) ? this.postalCode : "") + "</span>", '<span class="country">' + ((this.country == GlobalReg.UNITED_STATES) ? "" : (this.country).substr((this.country).indexOf(" ") + 1)) + "</span>", '<span class="countryISO hidden">' + this.country + "</span>", '<span class="crid">CRID: ' + this.crid + "</span>", '<span class="affiliatedUsers">Affiliated Users: ' + this.affiliatedUsers + "</span>", '<span class="affiliatedUsers">CRID Creation Date: ' + this.cridCreationDateFormatted + "</span>", "</label>", "</div>");
    });
    jQuery("#" + a + "returnedMatchedAddresses").html(f.join(""));
};
var setupStepEfxSimilars = function(c, a) {
    a = a || "";
    var d = [];
    var b = 0;
    $.each(c.matchedCompaniesAddressList, function() {
        b++;
        d.push('<div id="' + a + "div-efx-address" + b + '" class="radio">', '<label for="' + a + "address-efx-match" + b + '">', '<input type="radio" id="' + a + "address-efx-match" + b + '" name="' + a + 'similar-address" value="' + this.crid + '" />', '<span class="company">' + this.companyName + "</span>", '<span class="address1">' + this.addressLine1 + "</span>", '<span class="address2">' + this.addressLine2 + "</span>", '<span class="address3">' + this.addressLine3 + "</span>", '<span class="city">' + this.city + '</span> <span class="state">' + this.state + '</span> <span class="zip">' + this.postalCode + "</span>", "</label>", "</div>");
    });
    d.push('<div id="' + a + 'div-efx-address0" class="radio">', '<label for="' + a + 'address-efx-match0">', '<input type="radio" id="' + a + 'address-efx-match0" name="' + a + 'similar-address" value="0" />', '<span class="company">None of the above</span>', "</label>", "</div>");
    jQuery("#" + a + "returnedEquifaxAddresses").html(d.join(""));
    jQuery("#group-ref-id").val(c.refId);
    jQuery("#company-crid").val(c.crid);
};
var zipLookupStep1 = function(b) {
    b.preventDefault();
    var a = $(this);
    if (!a.hasClass("disabled")) {
        jQuery("#zipLookupHolderStep1 div.form-group").removeClass("has-error");
        jQuery("#zipLookupHolderStep1 span[id^='error-']").empty();
        jQuery("#zipLookupHolderStep1 span[id^='sr-only-error-']").empty();
        $.blockUI();
        a.addClass("disabled");
        zipLookupService($.trim(jQuery("#tzipsearch").val()), function(d) {
            if (d.data.errors) {
                var j = "";
                for (var c = 0; c < d.data.errors.length; c++) {
                    j += ('<div class="error">' + d.data.errors[c] + "</div>");
                }
                jQuery("#step1-ams-address-error-msg").html(j).removeClass("hidden");
                a.removeClass("disabled");
                $backbutton.removeClass("disabled");
                jQuery.blockUI();
            } else {
                if (d.data.fieldErrors) {
                    for (fieldName in d.data.fieldErrors) {
                        if (d.data.fieldErrors.hasOwnProperty(fieldName)) {
                            var h = $("[name='" + fieldName + "']").attr("id");
                            jQuery("#form-group-" + h).addClass("has-error");
                            for (var g = 0; g < d.data.fieldErrors[fieldName].length; g++) {
                                jQuery("#sr-only-error-" + h).text(d.data.fieldErrors[fieldName][g]);
                                jQuery("#error-" + h).append('<span class="error-txt-blk"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + sanitizeHtmlOut(d.data.fieldErrors[fieldName][g]) + "</span>");
                            }
                        }
                    }
                    jQuery("#zipLookupHolderStep1 div.form-group.has-error:first").find("input[type=text],textarea,select").filter(":visible:first").focus();
                    a.removeClass("disabled");
                    jQuery.blockUI();
                } else {
                    if (d.data.rs == "success") {
                        if (d.data.cityStateList.length < 1) {
                            jQuery("#zipLookupHolderStep1 #sr-only-error-tzipsearch").append(sanitizeHtmlOut("There was no City/State found for that ZIP Code"));
                            jQuery("#zipLookupHolderStep1 #error-tzipsearch").append('<span class="error-txt-blk"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + sanitizeHtmlOut("There was no City/State found for that ZIP Code"));
                            a.removeClass("disabled");
                            jQuery.blockUI();
                        } else {
                            if (d.data.cityStateList.length < 2) {
                                jQuery("#zipLookupHolderStep3 span.zip-lookup-step3-zip").html(d.data.zip);
                                jQuery("#zipLookupHolderStep3 span.zip-lookup-step3-city-state").html(d.data.cityStateList[0].city + " " + d.data.cityStateList[0].state);
                                progressWizard("zipLookupHolderStep1", "zipLookupHolderStep3");
                                jQuery("#a-step3-prev").off("click").on("click", function(i) {
                                    i.preventDefault();
                                    jQuery("#zipLookupHolderStep3 label").removeClass("error");
                                    progressWizard("zipLookupHolderStep3", "zipLookupHolderStep1");
                                });
                                a.removeClass("disabled");
                                jQuery.blockUI();
                            } else {
                                jQuery("#zipLookupHolderStep2 span.zip-lookup-step1-zip").html(d.data.zip);
                                var f = [];
                                $.each(d.data.cityStateList, function() {
                                    f.push('<option value="' + this.city + " " + this.state + '">' + this.city + " " + this.state + "</option>");
                                });
                                jQuery("#returnedCityStates").empty().append(f.join(""));
                                progressWizard("zipLookupHolderStep1", "zipLookupHolderStep2");
                                jQuery("#a-step3-prev").off("click").on("click", function(i) {
                                    i.preventDefault();
                                    jQuery("#zipLookupHolderStep3 label").removeClass("error");
                                    progressWizard("zipLookupHolderStep3", "zipLookupHolderStep2");
                                });
                                a.removeClass("disabled");
                                jQuery.blockUI();
                            }
                        }
                    } else {
                        jQuery("#zipLookupHolderStep1 #sr-only-error-tzipsearch").append(sanitizeHtmlOut("There was a problem while looking up that ZIP Code.  Try again later."));
                        jQuery("#zipLookupHolderStep1 #error-tzipsearch").append(sanitizeHtmlOut("There was a problem while looking up that ZIP Code.  Try again later."));
                        a.removeClass("disabled");
                        jQuery.blockUI();
                    }
                }
            }
        });
    }
};
var zipLookupStep2 = function(b) {
    b.preventDefault();
    var a = $.trim((jQuery("#returnedCityStates").val()).slice(-2));
    if (a == GlobalReg.PUERTO_RICO_ABBR) {
        jQuery("#tzipSearchUrbanCodeWidget").removeClass("hidden");
    } else {
        jQuery("#tzipSearchUrbanCodeWidget").addClass("hidden");
        jQuery("#tzipSearchUrbanCode").val("");
    }
    progressWizard("zipLookupHolderStep2", "zipLookupHolderStep3");
    jQuery("#zipLookupHolderStep3 span.zip-lookup-step3-zip").html(jQuery("#zipLookupHolderStep2 span.zip-lookup-step1-zip").html());
    jQuery("#zipLookupHolderStep3 span.zip-lookup-step3-city-state").html(jQuery("#returnedCityStates").val());
};
var zipLookupStep2Prev = function(a) {
    a.preventDefault();
    progressWizard("zipLookupHolderStep2", "zipLookupHolderStep1");
};
var zipLookupStep3 = function(b) {
    b.preventDefault();
    var a = $(this);
    if (!a.hasClass("disabled")) {
        jQuery("#zipLookupHolderStep3 div.form-group").removeClass("has-error");
        jQuery("#zipLookupHolderStep3 span[id^='error-']").empty();
        jQuery("#zipLookupHolderStep3 span[id^='sr-only-error-']").empty();
        jQuery("#a-zip-step3-error-msg").empty();
        addressObj = {
            scountry: GlobalReg.UNITED_STATES,
            tcompany: $.trim(jQuery("#tzipSearchCompany").val()),
            taddress: $.trim(jQuery("#tzipSearchAddress").val()),
            tapt: $.trim(jQuery("#tzipSearchApt").val()),
            tcity: $.trim((jQuery("#zipLookupHolderStep3 span.zip-lookup-step3-city-state").html()).slice(0, -2)),
            sstate: $.trim((jQuery("#zipLookupHolderStep3 span.zip-lookup-step3-city-state").html()).slice(-2)),
            tzip: $.trim(jQuery("#zipLookupHolderStep3 span.zip-lookup-step3-zip").html()),
            turbancode: $.trim(jQuery("#tzipSearchUrbanCode").val()),
            accountType: $.trim($("input[name=raccount]:checked").val())
        };
        $.blockUI();
        a.addClass("disabled");
        validateAddressService(addressObj, function(c, g) {
            if (c.data.fieldErrors) {
                for (fieldName in c.data.fieldErrors) {
                    if (c.data.fieldErrors.hasOwnProperty(fieldName)) {
                        var f = fieldName;
                        if (fieldName == "tcompany") {
                            f = "tzipSearchCompany";
                        } else {
                            if (fieldName == "taddress") {
                                f = "tzipSearchAddress";
                            } else {
                                if (fieldName == "tapt") {
                                    f = "tzipSearchApt";
                                }
                            }
                        }
                        var j = $("[name='" + f + "']").attr("id");
                        jQuery("#form-group-" + j).addClass("has-error");
                        jQuery("#" + j).attr("aria-invalid", "true");
                        for (var d = 0; d < c.data.fieldErrors[fieldName].length; d++) {
                            jQuery("#sr-only-error-" + j).text(c.data.fieldErrors[fieldName][d]);
                            jQuery("#error-" + j).append('<span class="error-txt-blk"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + sanitizeHtmlOut(c.data.fieldErrors[fieldName][d]) + "</span>");
                        }
                    }
                }
                jQuery("#zipLookupHolderStep3 div.form-group.has-error:first").find("input[type=text],textarea,select").filter(":visible:first").focus();
                a.removeClass("disabled");
                jQuery.blockUI();
            } else {
                companyMatchAddressObj = {
                    company: g.tcompany,
                    address1: g.taddress,
                    address2: g.tapt,
                    address3: "",
                    city: g.tcity,
                    state: g.sstate,
                    urbancode: g.turbancode,
                    postalcode: g.tzip,
                    country: g.scountry
                };
                var h = (jQuery("#companyMatch") && jQuery("#companyMatch").val() == "false") ? false : true;
                matchingCompanyLookupService(companyMatchAddressObj, h, "", function(m) {
                    if (m.data.errors) {
                        var r = "";
                        for (var k = 0; k < m.data.errors.length; k++) {
                            r += ('<span class="error-txt-blk"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + m.data.errors[k] + "</span>");
                        }
                        jQuery("#a-zip-step3-error-msg").html(r).removeClass("hidden");
                        a.removeClass("disabled");
                        jQuery.blockUI();
                    } else {
                        if (m.data.fieldErrors) {
                            for (fieldName in m.data.fieldErrors) {
                                if (m.data.fieldErrors.hasOwnProperty(fieldName)) {
                                    var q = $("[name='" + fieldName + "']").attr("id");
                                    jQuery("#form-group-" + q).addClass("has-error");
                                    jQuery("#" + q).attr("aria-invalid", "true");
                                    for (var o = 0; o < m.data.fieldErrors[fieldName].length; o++) {
                                        jQuery("#sr-only-error-" + q).text(m.data.fieldErrors[fieldName][o]);
                                        jQuery("#error-" + q).append('<span class="error-txt-blk"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + sanitizeHtmlOut(m.data.fieldErrors[fieldName][o]) + "</span>");
                                    }
                                }
                            }
                            jQuery("#zipLookupHolderStep3 div.form-group.has-error:first").find("input[type=text],textarea,select").filter(":visible:first").focus();
                            a.removeClass("disabled");
                            jQuery.blockUI();
                        } else {
                            if (m.data.rs == "error") {
                                jQuery("#a-zip-step3-error-msg").html('<span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + m.data.rsMsg).removeClass("hidden");
                                a.removeClass("disabled");
                                jQuery.blockUI();
                            } else {
                                if (m.data.amsStatus == GlobalReg.INVALID_ADDRESS) {
                                    jQuery("#form-group-taddress").addClass("has-error");
                                    jQuery("#taddress").attr("aria-invalid", "true");
                                    jQuery("#sr-only-error-taddress").text(sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_ADDRESS_MSG));
                                    jQuery("#error-taddress").append('<span class="error-txt-blk"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_ADDRESS_MSG) + "</span>");
                                    a.removeClass("disabled");
                                    jQuery.blockUI();
                                } else {
                                    if (m.data.amsStatus == GlobalReg.INVALID_CITY) {
                                        jQuery("#form-group-tcity").addClass("has-error");
                                        jQuery("#tcity").attr("aria-invalid", "true");
                                        jQuery("#sr-only-error-tcity").text(sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_CITY_MSG));
                                        jQuery("#error-tcity").append('<span class="error-txt-blk"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_CITY_MSG) + "</span>");
                                        a.removeClass("disabled");
                                        jQuery.blockUI();
                                    } else {
                                        if (m.data.amsStatus == GlobalReg.INVALID_STATE) {
                                            jQuery("#form-group-sstate").addClass("has-error");
                                            jQuery("#sstate").attr("aria-invalid", "true");
                                            jQuery("#sr-only-error-sstate").text(sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_STATE_MSG));
                                            jQuery("#error-sstate").append('<span class="error-txt-blk"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_STATE_MSG) + "</span>");
                                            a.removeClass("disabled");
                                            jQuery.blockUI();
                                        } else {
                                            if (m.data.amsStatus == GlobalReg.INVALID_ZIPCODE) {
                                                jQuery("#form-group-tzip").addClass("has-error");
                                                jQuery("#tzip").attr("aria-invalid", "true");
                                                jQuery("#sr-only-error-tzip").text(sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_ZIPCODE_MSG));
                                                jQuery("#error-tzip").append('<span class="error-txt-blk"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_ZIPCODE_MSG) + "</span>");
                                                a.removeClass("disabled");
                                                jQuery.blockUI();
                                            } else {
                                                if (m.data.amsStatus == GlobalReg.EXACT_MATCH) {
                                                    var p = {
                                                        company: $.trim(jQuery("#tzipSearchCompany").val()),
                                                        address1: $.trim(m.data.amsAddressList[0].addressLine1),
                                                        address2: $.trim(m.data.amsAddressList[0].addressLine2),
                                                        city: $.trim(m.data.amsAddressList[0].city),
                                                        state: $.trim(m.data.amsAddressList[0].state),
                                                        postalcode: $.trim(m.data.amsAddressList[0].postalCode),
                                                        urbancode: $.trim(m.data.amsAddressList[0].urbanizationCode),
                                                        country: GlobalReg.UNITED_STATES,
                                                        deliverypoint: $.trim(m.data.amsAddressList[0].deliveryPoint),
                                                        amsstatus: $.trim(m.data.amsAddressList[0].amsStatus),
                                                        doNotAMS: false
                                                    };
                                                    jQuery("#ams-verified").val("true");
                                                    populateFinalZipAddressDisplay(p);
                                                    populateAddressForm(p);
                                                    if (m.data.rs == GlobalReg.COMPANY_MATCH_FOUND) {
                                                        setupStepCompanyMatch(m.data, p, "zip-");
                                                        progressWizard("zipLookupHolderStep3", "zipLookupHolderStep6");
                                                        updateViewport("returnedMatchedAddresses", "returned-matched-addresses-scrollbar");
                                                    } else {
                                                        if (m.data.rs == GlobalReg.EQUIFAX_SIMILARS) {
                                                            setupStepEfxSimilars(m.data, "zip-");
                                                            progressWizard("addressHolderStep3", "addressHolderStep7");
                                                            updateViewport("returnedEquifaxAddresses", "returned-equifax-addresses-scrollbar");
                                                        } else {
                                                            if (m.data.rs == GlobalReg.ERROR) {
                                                                jQuery("#a-zip-step3-error-msg").html('<span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> There was an error while processing your address.  Please try again.').removeClass("hidden");
                                                            } else {
                                                                jQuery("#company-crid").val(m.data.crid);
                                                                progressWizardFinished("zipLookupHolderStep3", "zipLookupHolderStep8", "zip-");
                                                            }
                                                        }
                                                    }
                                                    a.removeClass("disabled");
                                                    jQuery.blockUI();
                                                } else {
                                                    jQuery("#ams-verified").val("false");
                                                    if (m.data.amsStatus == "ADDRESS NOT FOUND" || m.data.amsAddressList.length < 1) {
                                                        jQuery("#zipLookupHolderStep4 .found").addClass("hidden");
                                                        jQuery("#zipLookupHolderStep4 .not-found").removeClass("hidden");
                                                    } else {
                                                        jQuery("#zipLookupHolderStep4 .found").removeClass("hidden");
                                                        jQuery("#zipLookupHolderStep4 .not-found").addClass("hidden");
                                                    }
                                                    jQuery("#for-zip-ams-same span.urban").html(m.data.urban);
                                                    jQuery("#for-zip-ams-same span.address1").html(m.data.address);
                                                    jQuery("#for-zip-ams-same span.address2").html(m.data.apt);
                                                    jQuery("#for-zip-ams-same span.city").html(m.data.city);
                                                    jQuery("#for-zip-ams-same span.state").html(m.data.state);
                                                    jQuery("#for-zip-ams-same span.zip").html(m.data.zip);
                                                    var n = [];
                                                    var o = 0;
                                                    $.each(m.data.amsAddressList, function() {
                                                        o++;
                                                        var i = "false";
                                                        if ((/\(range/i.test(this.addressLine1)) || (/\(even range/i.test(this.addressLine1)) || (/\(odd range/i.test(this.addressLine1))) {
                                                            i = "true";
                                                        }
                                                        n.push('<div id="div-zip-address' + o + '" class="radio">', '<label for="zip-address' + o + '">', '<input type="radio" id="zip-address' + o + '" name="zipaddress" rel="' + i + '" />', '<span class="urban">' + ((this.urbanizationCode != null) ? this.urbanizationCode : "") + "</span>", '<span class="address1">' + this.addressLine1.replace(/\sSTE\s/g, "<br>STE ") + "</span>", '<span class="address2">' + (this.addressLine2 != null) ? this.addressLine2 : "" + "</span>", '<span class="city">' + this.city + '</span> <span class="state">' + this.state + '</span> <span class="zip">' + this.postalCode + "</span>", "</label>", "</div>");
                                                    });
                                                    jQuery("#zip-returnedAddresses").html(n.join(""));
                                                    jQuery("#zip-returnedAddresses :radio:first").attr("checked", true);
                                                    progressWizard("zipLookupHolderStep3", "zipLookupHolderStep4");
                                                    jQuery("#verification-zip-returned-addresses").removeClass("hidden");
                                                    jQuery("#verification-zip-returned-addresses-btns").removeClass("hidden");
                                                    updateViewport("zip-returnedAddresses", "zip-returned-addresses-scrollbar");
                                                    a.removeClass("disabled");
                                                    jQuery.blockUI();
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    }
};
var zipLookupStep3Prev = function(a) {
    a.preventDefault();
    progressWizard("zipLookupHolderStep3", "zipLookupHolderStep2");
};
var zipLookupStep4 = function(f) {
    f.preventDefault();
    jQuery("#zipLookupHolderStep4 div.error-txt").addClass("hidden");
    var g = jQuery("#zipLookupHolderStep4 :radio:checked");
    var a = {
        company: $.trim(jQuery("#tzipSearchCompany").val()),
        address1: $.trim(jQuery("#div-" + g.attr("id") + " span.address1").html()).replace(/<(BR|br)>STE/g, " STE"),
        address2: $.trim(jQuery("#div-" + g.attr("id") + " span.address2").html()),
        address3: "",
        city: $.trim(jQuery("#div-" + g.attr("id") + " span.city").html()),
        state: $.trim(jQuery("#div-" + g.attr("id") + " span.state").html()),
        zip: $.trim(jQuery("#div-" + g.attr("id") + " span.zip").html()),
        postalcode: $.trim(jQuery("#div-" + g.attr("id") + " span.zip").html()),
        urbancode: $.trim(jQuery("#div-" + g.attr("id") + " span.urban").html()),
        country: GlobalReg.UNITED_STATES,
        doNotAMS: false
    };
    if (!$('input[name="zipaddress"]:checked').length) {
        jQuery("#step4-zip-ams-radio-required").removeClass("hidden");
    } else {
        if (a.postalcode.length < 1) {
            jQuery("#step4-zip-ams-zip-required").removeClass("hidden");
        } else {
            if (g.attr("rel") === "true") {
                rangeCalc(g, "zip-");
                progressWizard("zipLookupHolderStep4", "zipLookupHolderStep5");
            } else {
                var d = (jQuery("#companyMatch") && jQuery("#companyMatch").val() == "false") ? false : true;
                var c = $(this);
                var b = jQuery("#a-address-step4-prev");
                if (!c.hasClass("disabled")) {
                    $.blockUI();
                    c.addClass("disabled");
                    b.addClass("disabled");
                    if (g.attr("id") === "zip-ams-same") {
                        jQuery("#ams-verified").val("false");
                        a.doNotAMS = true;
                        populateFinalZipAddressDisplay(a);
                        populateAddressForm(a);
                        companiesSimilarService(a, d, jQuery("#hcrid").val(), function(h) {
                            if (h.rs == GlobalReg.COMPANY_MATCH_FOUND) {
                                setupStepCompanyMatch(h.data, a, "zip-");
                                progressWizard("zipLookupHolderStep4", "zipLookupHolderStep6");
                                updateViewport("zip-returnedMatchedAddresses", "zip-returned-matched-addresses-scrollbar");
                            } else {
                                if (h.rs == GlobalReg.EQUIFAX_SIMILARS) {
                                    setupStepEfxSimilars(h.data, "zip-");
                                    progressWizard("zipLookupHolderStep4", "zipLookupHolderStep7");
                                    updateViewport("zip-returnedEquifaxAddresses", "zip-returned-equifax-addresses-scrollbar");
                                } else {
                                    if (h.rs == GlobalReg.ERROR) {
                                        jQuery("#a-zip-step4-error-msg").html('<span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> There was an error while processing your address.  Please try again.').removeClass("hidden");
                                    } else {
                                        jQuery("#company-crid").val(h.data.crid);
                                        progressWizardFinished("zipLookupHolderStep4", "zipLookupHolderStep8", "zip-");
                                    }
                                }
                            }
                            jQuery.blockUI();
                            c.removeClass("disabled");
                            b.removeClass("disabled");
                        });
                    } else {
                        amsLookupService(a, function(h) {
                            if (h.rs == GlobalReg.EXACT_MATCH) {
                                jQuery("#ams-verified").val("true");
                            } else {
                                jQuery("#ams-verified").val("false");
                                a.doNotAMS = true;
                            }
                            populateFinalZipAddressDisplay(a);
                            populateAddressForm(a);
                            companiesSimilarService(a, d, jQuery("#hcrid").val(), function(i) {
                                if (i.rs == GlobalReg.COMPANY_MATCH_FOUND) {
                                    setupStepCompanyMatch(i.data, a, "zip-");
                                    progressWizard("zipLookupHolderStep4", "zipLookupHolderStep6");
                                    updateViewport("zip-returnedMatchedAddresses", "zip-returned-matched-addresses-scrollbar");
                                } else {
                                    if (i.rs == GlobalReg.EQUIFAX_SIMILARS) {
                                        setupStepEfxSimilars(i.data, "zip-");
                                        progressWizard("zipLookupHolderStep4", "zipLookupHolderStep7");
                                        updateViewport("zip-returnedEquifaxAddresses", "zip-returned-equifax-addresses-scrollbar");
                                    } else {
                                        if (i.rs == GlobalReg.ERROR) {
                                            jQuery("#a-zip-step4-error-msg").html("There was an error while processing your address.  Please try again.").removeClass("hidden");
                                        } else {
                                            jQuery("#company-crid").val(i.data.crid);
                                            progressWizardFinished("zipLookupHolderStep4", "zipLookupHolderStep8", "zip-");
                                        }
                                    }
                                }
                                jQuery.blockUI();
                                c.removeClass("disabled");
                                b.removeClass("disabled");
                            });
                        });
                    }
                }
            }
        }
    }
};
var zipLookupStep4Prev = function(a) {
    a.preventDefault();
    progressWizard("zipLookupHolderStep4", "zipLookupHolderStep3");
};
var zipLookupStep5 = function(g) {
    g.preventDefault();
    jQuery("#zipLookupHolderStep5 div.error-txt").addClass("hidden");
    var c = (jQuery("#zip-ste-range").hasClass("begin")) ? (jQuery("#zip-ste-range").val() + (jQuery("#zip-range-address1").html()).replace(/<(BR|br)>/g, " ")) : ((jQuery("#zip-range-address1").html()).replace(/<(BR|br)>/g, " ") + jQuery("#zip-ste-range").val());
    var b = {
        address1: $.trim(c),
        address2: $.trim(jQuery("#zip-range-address2").html()),
        address3: "",
        city: $.trim(jQuery("#zip-range-city").html()),
        state: $.trim(jQuery("#zip-range-state").html()),
        postalcode: $.trim(jQuery("#zip-range-zip").html()),
        urbancode: $.trim(jQuery("#zip-range-urban").html()),
        country: GlobalReg.UNITED_STATES,
        doNotAMS: false
    };
    var f = $(this);
    var d = jQuery("#a-step5-prev");
    if (!f.hasClass("disabled")) {
        $.blockUI();
        f.addClass("disabled");
        d.addClass("disabled");
        amsLookupService(b, function(h) {
            var m = (jQuery("#companyMatch") && jQuery("#companyMatch").val() == "false") ? false : true;
            var k;
            if (h.data.errors) {
                var o = "";
                for (var a = 0; a < h.data.errors.length; a++) {
                    o += ('<div class="error">' + h.data.errors[a] + "</div>");
                }
                jQuery("#a-zip-step5-error-msg").html(o).removeClass("hidden");
                f.removeClass("disabled");
                d.removeClass("disabled");
                jQuery.blockUI();
            } else {
                if (h.data.fieldErrors) {
                    var o = "";
                    for (fieldName in h.data.fieldErrors) {
                        if (h.data.fieldErrors.hasOwnProperty(fieldName)) {
                            var n = $("[name='" + fieldName + "']").attr("id");
                            jQuery("#form-group-" + n).addClass("has-error");
                            for (var j = 0; j < h.data.fieldErrors[fieldName].length; j++) {
                                jQuery("#sr-only-error-" + n).text(h.data.fieldErrors[fieldName][j]);
                                o += ('<div class="error">' + h.data.fieldErrors[fieldName][j] + "</div>");
                            }
                        }
                    }
                    f.removeClass("disabled");
                    d.removeClass("disabled");
                    jQuery.blockUI();
                } else {
                    if (h.data.rs == "error") {
                        jQuery("#a-zip-step5-error-msg").html(h.data.rsMsg).removeClass("hidden");
                        f.removeClass("disabled");
                        d.removeClass("disabled");
                        jQuery.blockUI();
                    } else {
                        if (h.data.amsStatus == GlobalReg.EXACT_MATCH) {
                            k = {
                                company: $.trim(jQuery("#tzipSearchCompany").val()),
                                address1: $.trim(h.data.amsAddressList[0].addressLine1),
                                address2: $.trim(h.data.amsAddressList[0].addressLine2),
                                city: $.trim(h.data.amsAddressList[0].city),
                                state: $.trim(h.data.amsAddressList[0].state),
                                postalcode: $.trim(h.data.amsAddressList[0].postalCode),
                                urbancode: $.trim(h.data.amsAddressList[0].urbanizationCode),
                                country: GlobalReg.UNITED_STATES,
                                deliverypoint: $.trim(h.data.amsAddressList[0].deliveryPoint),
                                amsstatus: $.trim(h.data.amsAddressList[0].amsStatus),
                                doNotAMS: false
                            };
                            jQuery("#ams-verified").val("true");
                        } else {
                            k = {
                                company: $.trim(jQuery("#tzipSearchCompany").val()),
                                address1: $.trim(h.data.address),
                                address2: $.trim(h.data.apt),
                                city: $.trim(h.data.city),
                                state: $.trim(h.data.state),
                                postalcode: $.trim(h.data.zip),
                                urbancode: $.trim(h.data.urban),
                                country: GlobalReg.UNITED_STATES,
                                doNotAMS: false
                            };
                            jQuery("#ams-verified").val("false");
                            k.doNotAMS = true;
                        }
                        populateFinalZipAddressDisplay(k);
                        populateAddressForm(k);
                        companiesSimilarService(k, m, jQuery("#hcrid").val(), function(i) {
                            if (i.data.rs == GlobalReg.COMPANY_MATCH_FOUND) {
                                setupStepCompanyMatch(i.data, k, "zip-");
                                progressWizard("zipLookupHolderStep5", "zipLookupHolderStep6");
                                updateViewport("zip-returnedMatchedAddresses", "zip-returned-matched-addresses-scrollbar");
                            } else {
                                if (i.data.rs == GlobalReg.EQUIFAX_SIMILARS) {
                                    setupStepEfxSimilars(i.data, "zip-");
                                    progressWizard("zipLookupHolderStep5", "zipLookupHolderStep7");
                                    updateViewport("zip-returnedEquifaxAddresses", "zip-returned-equifax-addresses-scrollbar");
                                } else {
                                    if (i.data.rs == GlobalReg.ERROR) {
                                        jQuery("#a-zip-step5-error-msg").html("There was an error while processing your address.  Please try again.").removeClass("hidden");
                                    } else {
                                        jQuery("#company-crid").val(i.data.crid);
                                        progressWizardFinished("zipLookupHolderStep5", "zipLookupHolderStep8", "zip-");
                                    }
                                }
                            }
                            jQuery.blockUI();
                            f.removeClass("disabled");
                            d.removeClass("disabled");
                        });
                    }
                }
            }
        });
    }
};
var zipLookupStep5Prev = function(a) {
    a.preventDefault();
    progressWizard("zipLookupHolderStep5", "zipLookupHolderStep4");
};
var zipLookupStep6 = function(i) {
    i.preventDefault();
    jQuery("#zipLookupHolderStep6 div.error-txt").addClass("hidden");
    var a = $(this);
    var j = jQuery("#a-address-step6-prev");
    if (!a.hasClass("disabled")) {
        if (!$('input[name="zip-company-match-address"]:checked').length) {
            jQuery("#step6-ams-radio-required").removeClass("hidden");
        } else {
            $.blockUI();
            a.addClass("disabled");
            j.addClass("disabled");
            var h = $("input[name=zip-company-match-address]:radio:checked");
            if (h.val() == "same") {
                var g;
                var b = (jQuery("#ams-verified").val() == "true") ? false : true;
                g = {
                    company: $.trim(jQuery("#tcompany").val()),
                    address1: $.trim(jQuery("#taddress").val()),
                    address2: $.trim(jQuery("#tapt").val()),
                    address3: "",
                    city: $.trim(jQuery("#tcity").val()),
                    state: $.trim(jQuery("#sstate").val()),
                    postalcode: $.trim(jQuery("#tzip").val()),
                    urbancode: $.trim(jQuery("#turbanCode").val()),
                    country: jQuery("#scountry").val(),
                    doNotAMS: b
                };
                populateFinalZipAddressDisplay(g);
                populateAddressForm(d);
                addCompanyService(g, function(c) {
                    if (c.rs == GlobalReg.EQUIFAX_SIMILARS) {
                        setupStepEfxSimilars(c.data, "zip-");
                        progressWizard("zipLookupHolderStep6", "zipLookupHolderStep7");
                        updateViewport("zip-returnedEquifaxAddresses", "zip-returned-equifax-addresses-scrollbar");
                    } else {
                        if (c.rs == "ENTREG-DUPLICATE-EXCEPTION") {
                            jQuery("#company-crid").val(c.data.crid);
                            progressWizardFinished("zipLookupHolderStep6", "zipLookupHolderStep8", "zip-");
                        } else {
                            if (c.rs == GlobalReg.ERROR) {
                                jQuery("#rsMsg-error-msg-step6").html("There was an error while processing your address.  Please try again.");
                                jQuery("#step6-ams-address-error-msg").removeClass("hidden");
                            } else {
                                jQuery("#company-crid").val(c.data.crid);
                                progressWizardFinished("addressHolderStep6", "addressHolderStep8", "zip-");
                            }
                        }
                    }
                    jQuery.blockUI();
                    a.removeClass("disabled");
                    j.removeClass("disabled");
                });
            } else {
                a.removeClass("disabled");
                j.removeClass("disabled");
                jQuery.blockUI();
                var k = $.trim(jQuery("#div-" + h.attr("id") + " span.country").html());
                var f = k == "" ? GlobalReg.UNITED_STATES : k;
                var d = {
                    company: $.trim(jQuery("#div-" + h.attr("id") + " span.company").html()),
                    address1: $.trim(jQuery("#div-" + h.attr("id") + " span.address1").html()),
                    address2: $.trim(jQuery("#div-" + h.attr("id") + " span.address2").html()),
                    city: $.trim(jQuery("#div-" + h.attr("id") + " span.city").html()),
                    state: $.trim(jQuery("#div-" + h.attr("id") + " span.state").html()),
                    postalcode: $.trim(jQuery("#div-" + h.attr("id") + " span.zip").html()),
                    urbancode: $.trim(jQuery("#div-" + h.attr("id") + " span.urban").html()),
                    country: f,
                    doNotAMS: false
                };
                populateFinalZipAddressDisplay(d);
                d.country = jQuery("#scountry").val();
                populateAddressForm(d);
                jQuery("#company-crid").val(h.val());
                progressWizardFinished("addressHolderStep6", "addressHolderStep8", "zip-");
            }
        }
    }
};
var zipLookupStep6Prev = function(a) {
    a.preventDefault();
    jQuery("#a-zip-step6-error-msg").addClass("hide");
    jQuery('input[name="zip-company-match-address"]').prop("checked", false);
    progressWizard("zipLookupHolderStep6", "zipLookupHolderStep3");
};
var zipLookupStep7 = function(b) {
    b.preventDefault();
    var a = $(this);
    if (!a.hasClass("disabled")) {
        jQuery("#zipLookupHolderStep7 div.error-txt").addClass("hidden");
        if (!jQuery('input[name="zip-similar-address"]:checked').length) {
            jQuery("#zip-step7-ams-radio-required").removeClass("hidden");
        } else {
            $.blockUI();
            a.addClass("disabled");
            eFXSimilarService(jQuery("#company-crid").val(), jQuery("#group-ref-id").val(), $('input[name="zip-similar-address"]:radio:checked').val(), function(c) {
                progressWizardFinished("zipLookupHolderStep7", "zipLookupHolderStep8", "zip-");
                a.removeClass("disabled");
                jQuery.blockUI();
            });
        }
    }
};
var zipLookupStep8Prev = function(a) {
    a.preventDefault();
    showSelectedLookupPanel();
};
var personalAddressStep1 = function(c) {
    c.preventDefault();
    var b = jQuery(this);
    var a = jQuery("#a-address-step1-back");
    if (!b.hasClass("disabled")) {
        jQuery("#addressHolderStep1 div.form-group").removeClass("has-error");
        jQuery("#addressHolderStep1 span[id^='error-']").empty();
        jQuery("#addressHolderStep1 span[id^='sr-only-error-']").empty();
        amsAddressObj = {
            address1: jQuery.trim(jQuery("#taddress").val()),
            address2: jQuery.trim(jQuery("#tapt").val()),
            city: jQuery.trim(jQuery("#tcity").val()),
            state: jQuery.trim(jQuery("#sstate").val()),
            postalcode: jQuery.trim(jQuery("#tzip").val()),
            urbancode: jQuery.trim(jQuery("#turbanCode").val()),
            country: jQuery.trim(jQuery("#scountry").val())
        };
        jQuery.blockUI();
        b.addClass("disabled");
        a.addClass("disabled");
        amsLookupService(amsAddressObj, function(f) {
            if (f.data.errors) {
                var m = "";
                for (var d = 0; d < f.data.errors.length; d++) {
                    m += ('<div class="error">' + f.data.errors[d] + "</div>");
                }
                jQuery("#step1-ams-address-error-msg").html(m).removeClass("hidden");
                b.removeClass("disabled");
                a.removeClass("disabled");
                jQuery.blockUI();
            } else {
                if (f.data.fieldErrors) {
                    for (fieldName in f.data.fieldErrors) {
                        if (f.data.fieldErrors.hasOwnProperty(fieldName)) {
                            var k = $("[name='" + fieldName + "']").attr("id");
                            jQuery("#form-group-" + k).addClass("has-error");
                            for (var h = 0; h < f.data.fieldErrors[fieldName].length; h++) {
                                jQuery("#sr-only-error-" + k).text(f.data.fieldErrors[fieldName][h]);
                                jQuery("#error-" + k).append('<span class="error-txt-blk"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + sanitizeHtmlOut(f.data.fieldErrors[fieldName][h]) + "</span>");
                            }
                        }
                    }
                    jQuery("#addressHolderStep1 div.form-group.has-error:first").find("input[type=text],textarea,select").filter(":visible:first").focus();
                    b.removeClass("disabled");
                    a.removeClass("disabled");
                    jQuery.blockUI();
                } else {
                    if (f.data.rs == "error") {
                        jQuery("#step1-ams-address-error-msg").html(f.data.rsMsg).removeClass("hidden");
                        b.removeClass("disabled");
                        a.removeClass("disabled");
                        jQuery.unblockUI();
                    } else {
                        if (f.data.amsStatus == GlobalReg.INVALID_ADDRESS) {
                            jQuery("#form-group-taddress").addClass("has-error");
                            jQuery("#taddress").attr("aria-invalid", "true");
                            jQuery("#sr-only-error-taddress").text(sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_ADDRESS_MSG));
                            jQuery("#error-taddress").append('<span class="error-txt-blk"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_ADDRESS_MSG) + "</span>");
                            b.removeClass("disabled");
                            a.removeClass("disabled");
                            jQuery.blockUI();
                        } else {
                            if (f.data.amsStatus == GlobalReg.INVALID_CITY) {
                                jQuery("#form-group-tcity").addClass("has-error");
                                jQuery("#tcity").attr("aria-invalid", "true");
                                jQuery("#sr-only-error-tcity").text(sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_CITY_MSG));
                                jQuery("#error-tcity").append('<span class="error-txt-blk"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_CITY_MSG) + "</span>");
                                b.removeClass("disabled");
                                a.removeClass("disabled");
                                jQuery.blockUI();
                            } else {
                                if (f.data.amsStatus == GlobalReg.INVALID_STATE) {
                                    jQuery("#form-group-sstate").addClass("has-error");
                                    jQuery("#sstate").attr("aria-invalid", "true");
                                    jQuery("#sr-only-error-sstate").text(sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_STATE_MSG));
                                    jQuery("#error-sstate").append('<span class="error-txt-blk"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_STATE_MSG) + "</span>");
                                    b.removeClass("disabled");
                                    a.removeClass("disabled");
                                    jQuery.blockUI();
                                } else {
                                    if (f.data.amsStatus == GlobalReg.INVALID_ZIPCODE) {
                                        jQuery("#form-group-tzip").addClass("has-error");
                                        jQuery("#tzip").attr("aria-invalid", "true");
                                        jQuery("#sr-only-error-tzip").text(sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_ZIPCODE_MSG));
                                        jQuery("#error-tzip").append('<span class="error-txt-blk"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_ZIPCODE_MSG) + "</span>");
                                        b.removeClass("disabled");
                                        a.removeClass("disabled");
                                        jQuery.blockUI();
                                    } else {
                                        if (f.data.amsStatus == GlobalReg.EXACT_MATCH) {
                                            var j = {
                                                nickname: $.trim(jQuery("#tnickName").val()),
                                                address1: $.trim(f.data.amsAddressList[0].addressLine1),
                                                address2: $.trim(f.data.amsAddressList[0].addressLine2),
                                                city: $.trim(f.data.amsAddressList[0].city),
                                                state: $.trim(f.data.amsAddressList[0].state),
                                                postalcode: $.trim(f.data.amsAddressList[0].postalCode),
                                                urbancode: $.trim(f.data.amsAddressList[0].urbanizationCode),
                                                country: $.trim(jQuery("#scountry").val()),
                                                deliverypoint: $.trim(f.data.amsAddressList[0].deliveryPoint),
                                                amsstatus: $.trim(f.data.amsAddressList[0].amsStatus),
                                                doNotAMS: false
                                            };
                                            jQuery("#ams-verified").val("true");
                                            populateFinalAddressDisplay(j);
                                            populateAddressForm(j);
                                            progressWizardFinished("addressHolderStep1", "addressHolderStep6");
                                            b.removeClass("disabled");
                                            a.removeClass("disabled");
                                            jQuery.blockUI();
                                        } else {
                                            jQuery("#ams-verified").val("false");
                                            if (f.data.amsStatus == "ADDRESS NOT FOUND" || f.data.amsAddressList.length < 1) {
                                                jQuery("#addressHolderStep2 .found").addClass("hidden");
                                                jQuery("#addressHolderStep2 .not-found").removeClass("hidden");
                                            } else {
                                                jQuery("#addressHolderStep2 .found").removeClass("hidden");
                                                jQuery("#addressHolderStep2 .not-found").addClass("hidden");
                                            }
                                            jQuery("#for-ams-same span.urban").html(f.data.turban);
                                            jQuery("#for-ams-same span.address1").html(f.data.taddress);
                                            jQuery("#for-ams-same span.address2").html(f.data.tapt);
                                            jQuery("#for-ams-same span.city").html(f.data.tcity);
                                            jQuery("#for-ams-same span.state").html(f.data.sstate);
                                            jQuery("#for-ams-same span.zip").html(f.data.tzip);
                                            var g = [];
                                            var h = 0;
                                            $.each(f.data.amsAddressList, function() {
                                                h++;
                                                var i = "false";
                                                if ((/\(range/i.test(this.addressLine1)) || (/\(even range/i.test(this.addressLine1)) || (/\(odd range/i.test(this.addressLine1))) {
                                                    i = "true";
                                                }
                                                g.push('<div id="div-address' + h + '" class="radio">', '<label for="address' + h + '">', '<input type="radio" id="address' + h + '" name="address" rel="' + i + '" />', '<span class="urban">' + ((this.urbanizationCode != null) ? this.urbanizationCode : "") + "</span>", '<span class="address1">' + this.addressLine1.replace(/\sSTE\s/g, "<br>STE ") + "</span>", '<span class="address2">' + (this.addressLine2 != null) ? this.addressLine2 : "" + "</span>", '<span class="city">' + this.city + '</span> <span class="state">' + this.state + '</span> <span class="zip">' + this.postalCode + "</span>", "</label>", "</div>");
                                            });
                                            jQuery("#returnedAddresses").html(g.join(""));
                                            jQuery("#returnedAddresses :radio:first").attr("checked", true);
                                            progressWizard("addressHolderStep1", "addressHolderStep2");
                                            jQuery("#verification-returned-addresses").removeClass("hidden");
                                            jQuery("#verification-returned-addresses-btns").removeClass("hidden");
                                            updateViewport("returnedAddresses", "returned-addresses-scrollbar");
                                            b.removeClass("disabled");
                                            a.removeClass("disabled");
                                            jQuery.blockUI();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        });
    }
};
var personalAddressStep2 = function(d) {
    d.preventDefault();
    jQuery("#addressHolderStep2 div.error").addClass("hidden");
    var f = jQuery("#addressHolderStep2 :radio:checked");
    var a = {
        nickname: $.trim(jQuery("#tnickName").val()),
        address1: $.trim(jQuery("#div-" + f.attr("id") + " span.address1").html()).replace(/<(BR|br)>STE/g, " STE"),
        address2: $.trim(jQuery("#div-" + f.attr("id") + " span.address2").html()),
        city: $.trim(jQuery("#div-" + f.attr("id") + " span.city").html()),
        state: $.trim(jQuery("#div-" + f.attr("id") + " span.state").html()),
        postalcode: $.trim(jQuery("#div-" + f.attr("id") + " span.zip").html()),
        urbancode: $.trim(jQuery("#div-" + f.attr("id") + " span.urban").html()),
        country: $.trim(jQuery("#scountry").val()),
        doNotAMS: false
    };
    if (!$('input[name="address"]:checked').length) {
        jQuery("#step2-ams-radio-required").removeClass("hidden");
    } else {
        if (f.attr("id") === "ams-same") {
            if (jQuery("#addressHolderStep2 #for-ams-same span.zip").html() == "") {
                jQuery("#step2-ams-zip-required").removeClass("hidden");
            } else {
                jQuery("#ams-verified").val("false");
                a.doNotAMS = true;
                populateFinalAddressDisplay(a);
                populateAddressForm(a);
                progressWizardFinished("addressHolderStep2", "addressHolderStep6");
            }
        } else {
            if (f.attr("rel") === "true") {
                rangeCalc(f, "");
                jQuery("#verification-returned-addresses").addClass("hidden");
                jQuery("#verification-returned-addresses-btns").addClass("hidden");
                progressWizard("addressHolderStep2", "addressHolderStep3");
            } else {
                var c = $(this);
                var b = jQuery("#a-address-step2-prev");
                if (!c.hasClass("disabled")) {
                    jQuery.blockUI();
                    c.addClass("disabled");
                    b.addClass("disabled");
                    amsLookupService(a, function(h) {
                        if (h.data.errors) {
                            var n = "";
                            for (var g = 0; g < h.data.errors.length; g++) {
                                n += ('<div class="error">' + h.data.errors[g] + "</div>");
                            }
                            jQuery("#step2-ams-address-error-msg").html(n).removeClass("hidden");
                            c.removeClass("disabled");
                            b.removeClass("disabled");
                            jQuery.blockUI();
                        } else {
                            if (h.data.fieldErrors) {
                                var n = "";
                                for (fieldName in h.data.fieldErrors) {
                                    if (h.data.fieldErrors.hasOwnProperty(fieldName)) {
                                        var m = $("[name='" + fieldName + "']").attr("id");
                                        jQuery("#form-group-" + m).addClass("has-error");
                                        for (var j = 0; j < h.data.fieldErrors[fieldName].length; j++) {
                                            jQuery("#sr-only-error-" + m).text(h.data.fieldErrors[fieldName][j]);
                                            n += ('<div class="error">' + h.data.fieldErrors[fieldName][j] + "</div>");
                                        }
                                    }
                                }
                                c.removeClass("disabled");
                                b.removeClass("disabled");
                                jQuery.blockUI();
                            } else {
                                if (h.data.rs == "error") {
                                    jQuery("#step2-ams-address-error-msg").html(h.data.rsMsg).removeClass("hidden");
                                    c.removeClass("disabled");
                                    b.removeClass("disabled");
                                    jQuery.blockUI();
                                } else {
                                    if (h.data.amsStatus == GlobalReg.EXACT_MATCH) {
                                        var k = {
                                            nickname: $.trim(jQuery("#tnickName").val()),
                                            address1: $.trim(h.data.amsAddressList[0].addressLine1),
                                            address2: $.trim(h.data.amsAddressList[0].addressLine2),
                                            city: $.trim(h.data.amsAddressList[0].city),
                                            state: $.trim(h.data.amsAddressList[0].state),
                                            postalcode: $.trim(h.data.amsAddressList[0].postalCode),
                                            urbancode: $.trim(h.data.amsAddressList[0].urbanizationCode),
                                            country: $.trim(jQuery("#scountry").val()),
                                            deliverypoint: $.trim(h.data.amsAddressList[0].deliveryPoint),
                                            amsstatus: $.trim(h.data.amsAddressList[0].amsStatus),
                                            doNotAMS: false
                                        };
                                        jQuery("#ams-verified").val("true");
                                        populateFinalAddressDisplay(k);
                                        populateAddressForm(k);
                                        progressWizardFinished("addressHolderStep2", "addressHolderStep6");
                                        c.removeClass("disabled");
                                        b.removeClass("disabled");
                                        jQuery.blockUI();
                                    } else {
                                        jQuery("#ams-verified").val("false");
                                        populateAddressForm(a);
                                        progressWizardFinished("addressHolderStep2", "addressHolderStep6");
                                        c.removeClass("disabled");
                                        b.removeClass("disabled");
                                        jQuery.blockUI();
                                    }
                                }
                            }
                        }
                    });
                }
            }
        }
    }
};
var personalAddressStep2Prev = function(a) {
    a.preventDefault();
    jQuery("#verification-returned-addresses").addClass("hidden");
    jQuery("#verification-returned-addresses-btns").addClass("hidden");
    progressWizard("addressHolderStep2", "addressHolderStep1");
};
var personalAddressStep3 = function(g) {
    g.preventDefault();
    jQuery("#addressHolderStep3 div.error").addClass("hidden");
    var c = (jQuery("#ste-range").hasClass("begin")) ? (jQuery("#ste-range").val() + (jQuery("#range-address1").html()).replace(/<(BR|br)>/g, " ")) : ((jQuery("#range-address1").html()).replace(/<(BR|br)>/g, " ") + jQuery("#ste-range").val());
    var b = {
        address1: $.trim(c),
        address2: $.trim(jQuery("#range-address2").html()),
        city: $.trim(jQuery("#range-city").html()),
        state: $.trim(jQuery("#range-state").html()),
        postalcode: $.trim(jQuery("#range-zip").html()),
        urbancode: $.trim(jQuery("#range-urban").html()),
        country: $.trim(jQuery("#scountry").val()),
        doNotAMS: false
    };
    var f = $(this);
    var d = jQuery("#a-address-step3-prev");
    if (!f.hasClass("disabled")) {
        jQuery.blockUI();
        f.addClass("disabled");
        d.addClass("disabled");
        amsLookupService(b, function(h) {
            if (h.data.errors) {
                var n = "";
                for (var a = 0; a < h.data.errors.length; a++) {
                    n += ('<div class="error">' + h.data.errors[a] + "</div>");
                }
                jQuery("#step3-ams-address-error-msg").html(n).removeClass("hidden");
                f.removeClass("disabled");
                d.removeClass("disabled");
                jQuery.blockUI();
            } else {
                if (h.data.fieldErrors) {
                    var n = "";
                    for (fieldName in h.data.fieldErrors) {
                        if (h.data.fieldErrors.hasOwnProperty(fieldName)) {
                            var m = $("[name='" + fieldName + "']").attr("id");
                            jQuery("#form-group-" + m).addClass("has-error");
                            for (var j = 0; j < h.data.fieldErrors[fieldName].length; j++) {
                                jQuery("#sr-only-error-" + m).text(h.data.fieldErrors[fieldName][j]);
                                n += ('<div class="error">' + h.data.fieldErrors[fieldName][j] + "</div>");
                            }
                        }
                    }
                    f.removeClass("disabled");
                    d.removeClass("disabled");
                    jQuery.blockUI();
                } else {
                    if (h.data.rs == "error") {
                        jQuery("#step3-ams-address-error-msg").html(h.data.rsMsg).removeClass("hidden");
                        f.removeClass("disabled");
                        d.removeClass("disabled");
                        jQuery.blockUI();
                    } else {
                        if (h.data.amsStatus == GlobalReg.EXACT_MATCH) {
                            var k = {
                                nickname: $.trim(jQuery("#tnickName").val()),
                                address1: $.trim(h.data.amsAddressList[0].addressLine1),
                                address2: $.trim(h.data.amsAddressList[0].addressLine2),
                                city: $.trim(h.data.amsAddressList[0].city),
                                state: $.trim(h.data.amsAddressList[0].state),
                                postalcode: $.trim(h.data.amsAddressList[0].postalCode),
                                urbancode: $.trim(h.data.amsAddressList[0].urbanizationCode),
                                country: $.trim(jQuery("#scountry").val()),
                                deliverypoint: $.trim(h.data.amsAddressList[0].deliveryPoint),
                                amsstatus: $.trim(h.data.amsAddressList[0].amsStatus),
                                doNotAMS: false
                            };
                            jQuery("#ams-verified").val("true");
                            populateFinalAddressDisplay(k);
                            populateAddressForm(k);
                            progressWizardFinished("addressHolderStep3", "addressHolderStep6");
                            f.removeClass("disabled");
                            d.removeClass("disabled");
                            jQuery.blockUI();
                        } else {
                            jQuery("#ams-verified").val("false");
                            populateAddressForm(b);
                            progressWizardFinished("addressHolderStep3", "addressHolderStep6");
                            f.removeClass("disabled");
                            d.removeClass("disabled");
                            jQuery.blockUI();
                        }
                    }
                }
            }
        });
    }
};
var personalAddressStep3Prev = function(a) {
    a.preventDefault();
    jQuery("#addressHolderStep3 div.error").addClass("hidden");
    jQuery("#verification-returned-addresses").removeClass("hidden");
    jQuery("#verification-returned-addresses-btns").removeClass("hidden");
    progressWizard("addressHolderStep3", "addressHolderStep2");
};
var personalAddressStep6Prev = function(a) {
    a.preventDefault();
    resetLookupPanels();
};
var businessAddressStep1 = function(b) {
    b.preventDefault();
    var a = $(this);
    if (!a.hasClass("disabled")) {
        jQuery("#addressHolderStep1 div.form-group").removeClass("has-error");
        jQuery("#addressHolderStep1 span[id^='error-']").empty();
        jQuery("#addressHolderStep1 span[id^='sr-only-error-']").empty();
        addressObj = {
            scountry: $.trim(jQuery("#scountry").val()),
            tcompany: $.trim(jQuery("#tcompany").val()),
            taddress: $.trim(jQuery("#taddress").val()),
            tapt: $.trim(jQuery("#tapt").val()),
            tcity: $.trim(jQuery("#tcity").val()),
            sstate: $.trim(jQuery("#sstate").val()),
            tzip: $.trim(jQuery("#tzip").val()),
            turbancode: $.trim(jQuery("#turbanCode").val()),
            taddress1: $.trim(jQuery("#taddress1").val()),
            taddress2: $.trim(jQuery("#taddress2").val()),
            taddress3: $.trim(jQuery("#taddress3").val()),
            tcityInt: $.trim(jQuery("#tcityInt").val()),
            tprovince: $.trim(jQuery("#tprovince").val()),
            tpostalCode: $.trim(jQuery("#tpostalCode").val()),
            accountType: "business"
        };
        jQuery.blockUI();
        a.addClass("disabled");
        validateAddressService(addressObj, function(c, f) {
            if (c.data.fieldErrors) {
                for (fieldName in c.data.fieldErrors) {
                    if (c.data.fieldErrors.hasOwnProperty(fieldName)) {
                        var h = $("[name='" + fieldName + "']").attr("id");
                        jQuery("#form-group-" + h).addClass("has-error");
                        jQuery("#" + h).attr("aria-invalid", "true");
                        for (var d = 0; d < c.data.fieldErrors[fieldName].length; d++) {
                            jQuery("#sr-only-error-" + h).text(c.data.fieldErrors[fieldName][d]);
                            jQuery("#error-" + h).append('<span class="error-txt-blk"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + sanitizeHtmlOut(c.data.fieldErrors[fieldName][d]) + "</span>");
                        }
                    }
                }
                jQuery("#addressHolderStep1 div.form-group.has-error:first").find("input[type=text],textarea,select").filter(":visible:first").focus();
                a.removeClass("disabled");
                jQuery.blockUI();
            } else {
                if (jQuery("#scountry").val() == GlobalReg.UNITED_STATES) {
                    companyMatchAddressObj = {
                        company: f.tcompany,
                        address1: f.taddress,
                        address2: f.tapt,
                        address3: "",
                        city: f.tcity,
                        state: f.sstate,
                        urbancode: f.turbancode,
                        postalcode: f.tzip,
                        country: f.scountry
                    };
                } else {
                    companyMatchAddressObj = {
                        company: f.tcompany,
                        address1: f.taddress1,
                        address2: f.taddress2,
                        address3: f.taddress3,
                        city: f.tcityInt,
                        state: f.tprovince,
                        urbancode: "",
                        postalcode: f.tpostalCode,
                        country: f.scountry
                    };
                }
                var g = (jQuery("#companyMatch") && jQuery("#companyMatch").val() == "false") ? false : true;
                matchingCompanyLookupService(companyMatchAddressObj, g, "", function(k) {
                    if (k.data.errors) {
                        var q = "";
                        for (var j = 0; j < k.data.errors.length; j++) {
                            q += ('<span class="error-txt-blk"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + k.data.errors[j] + "</span>");
                        }
                        jQuery("#step1-ams-address-error-msg").html(q).removeClass("hidden");
                        a.removeClass("disabled");
                        jQuery.blockUI();
                    } else {
                        if (k.data.fieldErrors) {
                            for (fieldName in k.data.fieldErrors) {
                                if (k.data.fieldErrors.hasOwnProperty(fieldName)) {
                                    var p = $("[name='" + fieldName + "']").attr("id");
                                    jQuery("#form-group-" + p).addClass("has-error");
                                    jQuery("#" + p).attr("aria-invalid", "true");
                                    for (var n = 0; n < k.data.fieldErrors[fieldName].length; n++) {
                                        jQuery("#sr-only-error-" + p).text(k.data.fieldErrors[fieldName][n]);
                                        jQuery("#error-" + p).append('<span class="error-txt-blk"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + sanitizeHtmlOut(k.data.fieldErrors[fieldName][n]) + "</span>");
                                    }
                                }
                            }
                            jQuery("#addressHolderStep1 div.form-group.has-error:first").find("input[type=text],textarea,select").filter(":visible:first").focus();
                            a.removeClass("disabled");
                            jQuery.blockUI();
                        } else {
                            if (k.data.rs == "error") {
                                jQuery("#step1-ams-address-error-msg").html('<span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + k.data.rsMsg).removeClass("hidden");
                                a.removeClass("disabled");
                                jQuery.blockUI();
                            } else {
                                if (k.data.amsStatus == GlobalReg.INVALID_ADDRESS) {
                                    jQuery("#form-group-taddress").addClass("has-error");
                                    jQuery("#taddress").attr("aria-invalid", "true");
                                    jQuery("#sr-only-error-taddress").text(sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_ADDRESS_MSG));
                                    jQuery("#error-taddress").append('<span class="error-txt-blk"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_ADDRESS_MSG) + "</span>");
                                    a.removeClass("disabled");
                                    jQuery.blockUI();
                                } else {
                                    if (k.data.amsStatus == GlobalReg.INVALID_CITY) {
                                        jQuery("#form-group-tcity").addClass("has-error");
                                        jQuery("#tcity").attr("aria-invalid", "true");
                                        jQuery("#sr-only-error-tcity").text(sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_CITY_MSG));
                                        jQuery("#error-tcity").append('<span class="error-txt-blk"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_CITY_MSG) + "</span>");
                                        a.removeClass("disabled");
                                        jQuery.blockUI();
                                    } else {
                                        if (k.data.amsStatus == GlobalReg.INVALID_STATE) {
                                            jQuery("#form-group-sstate").addClass("has-error");
                                            jQuery("#sstate").attr("aria-invalid", "true");
                                            jQuery("#sr-only-error-sstate").text(sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_STATE_MSG));
                                            jQuery("#error-sstate").append('<span class="error-txt-blk"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_STATE_MSG) + "</span>");
                                            a.removeClass("disabled");
                                            jQuery.blockUI();
                                        } else {
                                            if (k.data.amsStatus == GlobalReg.INVALID_ZIPCODE) {
                                                jQuery("#form-group-tzip").addClass("has-error");
                                                jQuery("#tzip").attr("aria-invalid", "true");
                                                jQuery("#sr-only-error-tzip").text(sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_ZIPCODE_MSG));
                                                jQuery("#error-tzip").append('<span class="error-txt-blk"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + sanitizeHtmlOut(GlobalReg.errorMsgs.INVALID_ZIPCODE_MSG) + "</span>");
                                                a.removeClass("disabled");
                                                jQuery.blockUI();
                                            } else {
                                                if (k.data.amsStatus == GlobalReg.EXACT_MATCH) {
                                                    var o = {
                                                        company: $.trim(jQuery("#tcompany").val()),
                                                        nickname: $.trim(jQuery("#tnickName").val()),
                                                        address1: $.trim(k.data.amsAddressList[0].addressLine1),
                                                        address2: $.trim(k.data.amsAddressList[0].addressLine2),
                                                        city: $.trim(k.data.amsAddressList[0].city),
                                                        state: $.trim(k.data.amsAddressList[0].state),
                                                        postalcode: $.trim(k.data.amsAddressList[0].postalCode),
                                                        urbancode: $.trim(k.data.amsAddressList[0].urbanizationCode),
                                                        country: $.trim(jQuery("#scountry").val()),
                                                        deliverypoint: $.trim(k.data.amsAddressList[0].deliveryPoint),
                                                        amsstatus: $.trim(k.data.amsAddressList[0].amsStatus),
                                                        doNotAMS: false
                                                    };
                                                    jQuery("#ams-verified").val("true");
                                                    populateFinalAddressDisplay(o);
                                                    populateAddressForm(o);
                                                    if (k.data.rs == GlobalReg.COMPANY_MATCH_FOUND) {
                                                        setupStepCompanyMatch(k.data, o);
                                                        progressWizard("addressHolderStep1", "addressHolderStep4");
                                                        updateViewport("returnedMatchedAddresses", "returned-matched-addresses-scrollbar");
                                                    } else {
                                                        if (k.data.rs == GlobalReg.EQUIFAX_SIMILARS) {
                                                            setupStepEfxSimilars(k.data);
                                                            progressWizard("addressHolderStep1", "addressHolderStep5");
                                                            updateViewport("returnedEquifaxAddresses", "returned-equifax-addresses-scrollbar");
                                                        } else {
                                                            if (k.data.rs == GlobalReg.ERROR) {
                                                                jQuery("#step1-ams-address-error-msg").html('<span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> There was an error while processing your address.  Please try again.').removeClass("hidden");
                                                            } else {
                                                                jQuery("#company-crid").val(k.data.crid);
                                                                progressWizardFinished("addressHolderStep1", "addressHolderStep6");
                                                            }
                                                        }
                                                    }
                                                    a.removeClass("disabled");
                                                    jQuery.blockUI();
                                                } else {
                                                    if (k.data.rs == GlobalReg.COMPANY_MATCH_FOUND) {
                                                        setupStepCompanyMatch(k.data, companyMatchAddressObj);
                                                        progressWizard("addressHolderStep1", "addressHolderStep4");
                                                        updateViewport("returnedMatchedAddresses", "returned-matched-addresses-scrollbar");
                                                        a.removeClass("disabled");
                                                        jQuery.blockUI();
                                                    } else {
                                                        if (k.data.rs == GlobalReg.EQUIFAX_SIMILARS) {
                                                            setupStepEfxSimilars(k.data);
                                                            populateFinalAddressDisplay(companyMatchAddressObj);
                                                            progressWizard("addressHolderStep1", "addressHolderStep5");
                                                            updateViewport("returnedEquifaxAddresses", "returned-equifax-addresses-scrollbar");
                                                            a.removeClass("disabled");
                                                            jQuery.blockUI();
                                                        } else {
                                                            if (k.data.rs == GlobalReg.ERROR) {
                                                                jQuery("#a-address-step1-error-msg").html("There was an error while processing your address.  Please try again.").removeClass("hidden");
                                                                a.removeClass("disabled");
                                                                jQuery.blockUI();
                                                            } else {
                                                                if (k.data.rs == GlobalReg.COMPANY_ADDED) {
                                                                    jQuery("#company-crid").val(k.data.crid);
                                                                    populateFinalAddressDisplay(companyMatchAddressObj);
                                                                    progressWizardFinished("addressHolderStep1", "addressHolderStep6");
                                                                    a.removeClass("disabled");
                                                                    jQuery.blockUI();
                                                                } else {
                                                                    jQuery("#ams-verified").val("false");
                                                                    if (k.data.amsStatus == "ADDRESS NOT FOUND" || k.data.amsAddressList.length < 1) {
                                                                        jQuery("#addressHolderStep2 .found").addClass("hidden");
                                                                        jQuery("#addressHolderStep2 .not-found").removeClass("hidden");
                                                                    } else {
                                                                        jQuery("#addressHolderStep2 .found").removeClass("hidden");
                                                                        jQuery("#addressHolderStep2 .not-found").addClass("hidden");
                                                                    }
                                                                    jQuery("#for-ams-same span.urban").html(k.data.urban);
                                                                    jQuery("#for-ams-same span.address1").html(k.data.address);
                                                                    jQuery("#for-ams-same span.address2").html(k.data.apt);
                                                                    jQuery("#for-ams-same span.city").html(k.data.city);
                                                                    jQuery("#for-ams-same span.state").html(k.data.state);
                                                                    jQuery("#for-ams-same span.zip").html(k.data.zip);
                                                                    var m = [];
                                                                    var n = 0;
                                                                    $.each(k.data.amsAddressList, function() {
                                                                        n++;
                                                                        var i = "false";
                                                                        if ((/\(range/i.test(this.addressLine1)) || (/\(even range/i.test(this.addressLine1)) || (/\(odd range/i.test(this.addressLine1))) {
                                                                            i = "true";
                                                                        }
                                                                        m.push('<div id="div-address' + n + '" class="radio">', '<label for="address' + n + '">', '<input type="radio" id="address' + n + '" name="address" rel="' + i + '" />', '<span class="urban">' + ((this.urbanizationCode != null) ? this.urbanizationCode : "") + "</span>", '<span class="address1">' + this.addressLine1.replace(/\sSTE\s/g, "<br>STE ") + "</span>", '<span class="address2">' + (this.addressLine2 != null) ? this.addressLine2 : "" + "</span>", '<span class="city">' + this.city + '</span> <span class="state">' + this.state + '</span> <span class="zip">' + this.postalCode + "</span>", "</label>", "</div>");
                                                                    });
                                                                    jQuery("#returnedAddresses").html(m.join(""));
                                                                    jQuery("#returnedAddresses :radio:first").attr("checked", true);
                                                                    progressWizard("addressHolderStep1", "addressHolderStep2");
                                                                    jQuery("#verification-returned-addresses").removeClass("hidden");
                                                                    jQuery("#verification-returned-addresses-btns").removeClass("hidden");
                                                                    updateViewport("returnedAddresses", "returned-addresses-scrollbar");
                                                                    a.removeClass("disabled");
                                                                    jQuery.blockUI();
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    }
};
var businessAddressStep2 = function(f) {
    f.preventDefault();
    jQuery("#addressHolderStep2 div.error-txt").addClass("hidden");
    var g = jQuery("#addressHolderStep2 :radio:checked");
    var a = {
        company: $.trim(jQuery("#tcompany").val()),
        nickname: $.trim(jQuery("#tnickName").val()),
        address1: $.trim(jQuery("#div-" + g.attr("id") + " span.address1").html()).replace(/<(BR|br)>STE/g, " STE"),
        address2: $.trim(jQuery("#div-" + g.attr("id") + " span.address2").html()),
        address3: "",
        city: $.trim(jQuery("#div-" + g.attr("id") + " span.city").html()),
        state: $.trim(jQuery("#div-" + g.attr("id") + " span.state").html()),
        zip: $.trim(jQuery("#div-" + g.attr("id") + " span.zip").html()),
        postalcode: $.trim(jQuery("#div-" + g.attr("id") + " span.zip").html()),
        urbancode: $.trim(jQuery("#div-" + g.attr("id") + " span.urban").html()),
        country: $.trim(jQuery("#scountry").val()),
        doNotAMS: false
    };
    if (!$('input[name="address"]:checked').length) {
        jQuery("#step2-ams-radio-required").removeClass("hidden");
    } else {
        if (a.postalcode.length < 1) {
            jQuery("#step2-ams-zip-required").removeClass("hidden");
        } else {
            if (g.attr("rel") === "true") {
                rangeCalc(g, "");
                progressWizard("addressHolderStep2", "addressHolderStep3");
            } else {
                var d = (jQuery("#companyMatch") && jQuery("#companyMatch").val() == "false") ? false : true;
                var c = $(this);
                var b = jQuery("#a-address-step2-prev");
                if (!c.hasClass("disabled")) {
                    jQuery.blockUI();
                    c.addClass("disabled");
                    b.addClass("disabled");
                    if (g.attr("id") === "ams-same") {
                        jQuery("#ams-verified").val("false");
                        a.doNotAMS = true;
                        populateFinalAddressDisplay(a);
                        populateAddressForm(a);
                        companiesSimilarService(a, d, jQuery("#hcrid").val(), function(h) {
                            if (h.rs == GlobalReg.COMPANY_MATCH_FOUND) {
                                setupStepCompanyMatch(h.data, a);
                                progressWizard("addressHolderStep2", "addressHolderStep4");
                                updateViewport("returnedMatchedAddresses", "returned-matched-addresses-scrollbar");
                            } else {
                                if (h.rs == GlobalReg.EQUIFAX_SIMILARS) {
                                    setupStepEfxSimilars(h.data);
                                    progressWizard("addressHolderStep2", "addressHolderStep5");
                                    updateViewport("returnedEquifaxAddresses", "returned-equifax-addresses-scrollbar");
                                } else {
                                    if (h.rs == GlobalReg.ERROR) {
                                        jQuery("#a-address-step2-error-msg").html('<span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> There was an error while processing your address.  Please try again.').removeClass("hidden");
                                    } else {
                                        jQuery("#company-crid").val(h.data.crid);
                                        progressWizardFinished("addressHolderStep2", "addressHolderStep6");
                                    }
                                }
                            }
                            jQuery.blockUI();
                            c.removeClass("disabled");
                            b.removeClass("disabled");
                        });
                    } else {
                        amsLookupService(a, function(h) {
                            if (h.rs == GlobalReg.EXACT_MATCH) {
                                jQuery("#ams-verified").val("true");
                            } else {
                                jQuery("#ams-verified").val("false");
                                a.doNotAMS = true;
                            }
                            populateFinalAddressDisplay(a);
                            populateAddressForm(a);
                            companiesSimilarService(a, d, jQuery("#hcrid").val(), function(i) {
                                if (i.rs == GlobalReg.COMPANY_MATCH_FOUND) {
                                    setupStepCompanyMatch(i.data, a);
                                    progressWizard("addressHolderStep2", "addressHolderStep4");
                                    updateViewport("returnedMatchedAddresses", "returned-matched-addresses-scrollbar");
                                } else {
                                    if (i.rs == GlobalReg.EQUIFAX_SIMILARS) {
                                        setupStepEfxSimilars(i.data);
                                        progressWizard("addressHolderStep2", "addressHolderStep5");
                                        updateViewport("returnedEquifaxAddresses", "returned-equifax-addresses-scrollbar");
                                    } else {
                                        if (i.rs == GlobalReg.ERROR) {
                                            jQuery("#a-address-step2-error-msg").html("There was an error while processing your address.  Please try again.").removeClass("hide");
                                        } else {
                                            jQuery("#company-crid").val(i.data.crid);
                                            progressWizardFinished("addressHolderStep2", "addressHolderStep6");
                                        }
                                    }
                                }
                                jQuery.blockUI();
                                c.removeClass("disabled");
                                b.removeClass("disabled");
                            });
                        });
                    }
                }
            }
        }
    }
};
var businessAddressStep2Prev = function(a) {
    a.preventDefault();
    $('input[name="address"]').prop("checked", false);
    jQuery("#verification-returned-addresses").addClass("hidden");
    jQuery("#verification-returned-addresses-btns").addClass("hidden");
    jQuery("#addressHolderStep2 div.error-txt").addClass("hidden");
    progressWizard("addressHolderStep2", "addressHolderStep1");
};
var businessAddressStep3 = function(g) {
    g.preventDefault();
    jQuery("#addressHolderStep3 div.error-txt").addClass("hidden");
    var c = (jQuery("#ste-range").hasClass("begin")) ? (jQuery("#ste-range").val() + (jQuery("#range-address1").html()).replace(/<(BR|br)>/g, " ")) : ((jQuery("#range-address1").html()).replace(/<(BR|br)>/g, " ") + jQuery("#ste-range").val());
    var b = {
        address1: $.trim(c),
        address2: $.trim(jQuery("#range-address2").html()),
        address3: "",
        city: $.trim(jQuery("#range-city").html()),
        state: $.trim(jQuery("#range-state").html()),
        postalcode: $.trim(jQuery("#range-zip").html()),
        urbancode: $.trim(jQuery("#range-urban").html()),
        country: $.trim(jQuery("#scountry").val()),
        doNotAMS: false
    };
    var f = $(this);
    var d = jQuery("#a-address-step3-prev");
    if (!f.hasClass("disabled")) {
        jQuery.blockUI();
        f.addClass("disabled");
        d.addClass("disabled");
        amsLookupService(b, function(h) {
            var m = (jQuery("#companyMatch") && jQuery("#companyMatch").val() == "false") ? false : true;
            var k;
            if (h.data.errors) {
                var o = "";
                for (var a = 0; a < h.data.errors.length; a++) {
                    o += ('<div class="error">' + h.data.errors[a] + "</div>");
                }
                jQuery("#step3-ams-address-error-msg").html(o).removeClass("hidden");
                f.removeClass("disabled");
                d.removeClass("disabled");
                jQuery.blockUI();
            } else {
                if (h.data.fieldErrors) {
                    var o = "";
                    for (fieldName in h.data.fieldErrors) {
                        if (h.data.fieldErrors.hasOwnProperty(fieldName)) {
                            var n = $("[name='" + fieldName + "']").attr("id");
                            jQuery("#form-group-" + n).addClass("has-error");
                            for (var j = 0; j < h.data.fieldErrors[fieldName].length; j++) {
                                jQuery("#sr-only-error-" + n).text(h.data.fieldErrors[fieldName][j]);
                                o += ('<div class="error">' + h.data.fieldErrors[fieldName][j] + "</div>");
                            }
                        }
                    }
                    f.removeClass("disabled");
                    d.removeClass("disabled");
                    jQuery.blockUI();
                } else {
                    if (h.data.rs == "error") {
                        jQuery("#step3-ams-address-error-msg").html(h.data.rsMsg).removeClass("hidden");
                        f.removeClass("disabled");
                        d.removeClass("disabled");
                        jQuery.blockUI();
                    } else {
                        if (h.data.amsStatus == GlobalReg.EXACT_MATCH) {
                            k = {
                                company: $.trim(jQuery("#tcompany").val()),
                                address1: $.trim(h.data.amsAddressList[0].addressLine1),
                                address2: $.trim(h.data.amsAddressList[0].addressLine2),
                                city: $.trim(h.data.amsAddressList[0].city),
                                state: $.trim(h.data.amsAddressList[0].state),
                                postalcode: $.trim(h.data.amsAddressList[0].postalCode),
                                urbancode: $.trim(h.data.amsAddressList[0].urbanizationCode),
                                country: $.trim(jQuery("#scountry").val()),
                                deliverypoint: $.trim(h.data.amsAddressList[0].deliveryPoint),
                                amsstatus: $.trim(h.data.amsAddressList[0].amsStatus),
                                doNotAMS: false
                            };
                            jQuery("#ams-verified").val("true");
                        } else {
                            k = {
                                company: $.trim(jQuery("#tcompany").val()),
                                nickname: $.trim(jQuery("#tnickName").val()),
                                address1: $.trim(h.data.address),
                                address2: $.trim(h.data.apt),
                                city: $.trim(h.data.city),
                                state: $.trim(h.data.state),
                                postalcode: $.trim(h.data.zip),
                                urbancode: $.trim(h.data.urban),
                                country: $.trim(jQuery("#scountry").val()),
                                doNotAMS: false
                            };
                            jQuery("#ams-verified").val("false");
                            k.doNotAMS = true;
                        }
                        populateFinalAddressDisplay(k);
                        populateAddressForm(k);
                        companiesSimilarService(k, m, jQuery("#hcrid").val(), function(i) {
                            if (i.data.rs == GlobalReg.COMPANY_MATCH_FOUND) {
                                setupStepCompanyMatch(i.data, k);
                                progressWizard("addressHolderStep3", "addressHolderStep4");
                                updateViewport("returnedMatchedAddresses", "returned-matched-addresses-scrollbar");
                            } else {
                                if (i.data.rs == GlobalReg.EQUIFAX_SIMILARS) {
                                    setupStepEfxSimilars(i.data);
                                    progressWizard("addressHolderStep3", "addressHolderStep5");
                                    updateViewport("returnedEquifaxAddresses", "returned-equifax-addresses-scrollbar");
                                } else {
                                    if (i.data.rs == GlobalReg.ERROR) {
                                        jQuery("#a-address-step3-error-msg").html("There was an error while processing your address.  Please try again.").removeClass("hidden");
                                    } else {
                                        jQuery("#company-crid").val(i.data.crid);
                                        progressWizardFinished("addressHolderStep3", "addressHolderStep6");
                                    }
                                }
                            }
                            jQuery.blockUI();
                            f.removeClass("disabled");
                            d.removeClass("disabled");
                        });
                    }
                }
            }
        });
    }
};
var businessAddressStep3Prev = function(a) {
    a.preventDefault();
    jQuery("#a-address-step3-error-msg").addClass("hidden");
    progressWizard("addressHolderStep3", "addressHolderStep2");
};
var businessAddressStep4 = function(d) {
    d.preventDefault();
    jQuery("#addressHolderStep4 div.error-txt").addClass("hidden");
    var c = $(this);
    var b = jQuery("#a-address-step4-prev");
    if (!c.hasClass("disabled")) {
        if (!$('input[name="company-match-address"]:checked').length) {
            jQuery("#step4-ams-radio-required").removeClass("hidden");
        } else {
            jQuery.blockUI();
            c.addClass("disabled");
            b.addClass("disabled");
            var g = $("input[name=company-match-address]:radio:checked");
            if (g.val() == "same") {
                var a;
                if (jQuery("#scountry").val() == GlobalReg.UNITED_STATES) {
                    var f = (jQuery("#ams-verified").val() == "true") ? false : true;
                    a = {
                        company: $.trim(jQuery("#tcompany").val()),
                        address1: $.trim(jQuery("#taddress").val()),
                        address2: $.trim(jQuery("#tapt").val()),
                        address3: "",
                        city: $.trim(jQuery("#tcity").val()),
                        state: $.trim(jQuery("#sstate").val()),
                        postalcode: $.trim(jQuery("#tzip").val()),
                        urbancode: $.trim(jQuery("#turbanCode").val()),
                        country: jQuery("#scountry").val(),
                        doNotAMS: f
                    };
                } else {
                    a = {
                        company: $.trim(jQuery("#tcompany").val()),
                        nickname: $.trim(jQuery("#tnickName").val()),
                        address1: $.trim(jQuery("#taddress1").val()),
                        address2: $.trim(jQuery("#taddress2").val()),
                        address3: $.trim(jQuery("#taddress3").val()),
                        city: $.trim(jQuery("#tcityInt").val()),
                        state: $.trim(jQuery("#tprovince").val()),
                        postalcode: $.trim(jQuery("#tpostalCode").val()),
                        urbancode: "",
                        country: jQuery("#scountry").val(),
                        doNotAMS: true
                    };
                }
                populateFinalAddressDisplay(a);
                addCompanyService(a, function(i) {
                    if (i.rs == GlobalReg.EQUIFAX_SIMILARS) {
                        setupStepEfxSimilars(i.data);
                        progressWizard("addressHolderStep4", "addressHolderStep5");
                        updateViewport("returnedEquifaxAddresses", "returned-equifax-addresses-scrollbar");
                    } else {
                        if (i.rs == "ENTREG-DUPLICATE-EXCEPTION") {
                            jQuery("#company-crid").val(i.data.crid);
                            progressWizardFinished("addressHolderStep4", "addressHolderStep6");
                        } else {
                            if (i.rs == GlobalReg.ERROR) {
                                jQuery("#rsMsg-error-msg-step4").html("There was an error while processing your address.  Please try again.");
                                jQuery("#step4-ams-address-error-msg").removeClass("hidden");
                            } else {
                                jQuery("#company-crid").val(i.data.crid);
                                progressWizardFinished("addressHolderStep4", "addressHolderStep6");
                            }
                        }
                    }
                    jQuery.blockUI();
                    c.removeClass("disabled");
                    b.removeClass("disabled");
                });
            } else {
                c.removeClass("disabled");
                b.removeClass("disabled");
                jQuery.blockUI();
                var h = {
                    company: $.trim(jQuery("#div-" + g.attr("id") + " span.company").html()),
                    address1: $.trim(jQuery("#div-" + g.attr("id") + " span.address1").html()),
                    address2: $.trim(jQuery("#div-" + g.attr("id") + " span.address2").html()),
                    address3: $.trim(jQuery("#div-" + g.attr("id") + " span.address3").html()),
                    city: $.trim(jQuery("#div-" + g.attr("id") + " span.city").html()),
                    state: $.trim(jQuery("#div-" + g.attr("id") + " span.state").html()),
                    postalcode: $.trim(jQuery("#div-" + g.attr("id") + " span.zip").html()),
                    urbancode: $.trim(jQuery("#div-" + g.attr("id") + " span.urban").html()),
                    country: $.trim(jQuery("#div-" + g.attr("id") + " span.countryISO").html()),
                    doNotAMS: false
                };
                populateFinalAddressDisplay(h);
                populateAddressForm(h);
                jQuery("#company-crid").val(g.val());
                progressWizardFinished("addressHolderStep4", "addressHolderStep6");
            }
        }
    }
};
var businessAddressStep4Prev = function(a) {
    a.preventDefault();
    jQuery("#addressHolderStep4 div.error-txt").addClass("hidden");
    $('input[name="company-match-address"]').prop("checked", false);
    progressWizard("addressHolderStep4", "addressHolderStep1");
};
var businessAddressStep5 = function(b) {
    b.preventDefault();
    var a = $(this);
    if (!a.hasClass("disabled")) {
        jQuery("#addressHolderStep5 div.error-txt").addClass("hidden");
        if (!$('input[name="similar-address"]:checked').length) {
            jQuery("#step5-ams-radio-required").removeClass("hidden");
        } else {
            jQuery.blockUI();
            a.addClass("disabled");
            eFXSimilarService(jQuery("#company-crid").val(), jQuery("#group-ref-id").val(), $('input[name="similar-address"]:radio:checked').val(), function(c) {
                progressWizardFinished("addressHolderStep5", "addressHolderStep6");
                a.removeClass("disabled");
                jQuery.blockUI();
            });
        }
    }
};
var businessAddressStep6Prev = function(a) {
    a.preventDefault();
    resetLookupPanels();
};

jQuery(document).ready(function() {
    var a = {
        content: "",
        placement: "top",
        trigger: "manual",
        html: true,
        "template": '<div class="popover popover-danger" role="tooltip"><div class="arrow"></div><div class="popover-content"></div></div>'
    };
    jQuery("#returned-addresses-scrollbar").tinyscrollbar();
    jQuery("#returned-matched-addresses-scrollbar").tinyscrollbar();
    jQuery("#returned-equifax-addresses-scrollbar").tinyscrollbar();
    jQuery("#zip-returned-addresses-scrollbar").tinyscrollbar();
    jQuery("#zip-returned-matched-addresses-scrollbar").tinyscrollbar();
    jQuery("#zip-returned-equifax-addresses-scrollbar").tinyscrollbar();
    countrySwap();
    urbanization();
    jQuery("#scountry").change(function() {
        countrySwap();
    });
    jQuery("#sstate").change(function() {
        urbanization();
    });
    /*
    jQuery("#tnickName").popover(a);
    jQuery.validateField({
        field: "#tnickName",
        fields: ["#tnickName"]
    });
    jQuery("#tcompany").popover(a);
    jQuery.validateField({
        field: "#tcompany",
        fields: ["#tcompany", "#rAccount2"]
    });
    jQuery("#taddress").popover(a);
    jQuery.validateField({
        field: "#taddress",
        fields: ["#taddress"]
    });
    jQuery("#tapt").popover(a);
    jQuery.validateField({
        field: "#tapt",
        fields: ["#tapt"]
    });
    jQuery("#tcity").popover(a);
    jQuery.validateField({
        field: "#tcity",
        fields: ["#tcity"]
    });
     */
    jQuery("#sstate").on("focusin", function() {
        jQuery("#form-group-sstate").removeClass("has-error");
        jQuery("#error-sstate").empty();
        jQuery("#sr-only-error-sstate").empty();
    }).on("focusout", function() {
        if (jQuery(this).val() == "") {
            jQuery("#form-group-sstate").addClass("has-error");
            jQuery("#error-sstate").append('<span class="error-txt-blk"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> State is required.</span>');
            jQuery("#sr-only-error-sstate").html("State is required.");
        }
    });
    /*
    jQuery("#tzip").popover(a);
    jQuery.validateField({
        field: "#tzip",
        fields: ["#tzip"]
    });
    jQuery("#turbanCode").popover(a);
    jQuery.validateField({
        field: "#turbanCode",
        fields: ["#turbanCode"]
    });
    jQuery("#taddress1").popover(a);
    jQuery.validateField({
        field: "#taddress1",
        fields: ["#taddress1"]
    });
    jQuery("#taddress2").popover(a);
    jQuery.validateField({
        field: "#taddress2",
        fields: ["#taddress2"]
    });
    jQuery("#taddress3").popover(a);
    jQuery.validateField({
        field: "#taddress3",
        fields: ["#taddress3"]
    });
    jQuery("#tcityInt").popover(a);
    jQuery.validateField({
        field: "#tcityInt",
        fields: ["#tcityInt"]
    });
    jQuery("#tprovince").popover(a);
    jQuery.validateField({
        field: "#tprovince",
        fields: ["#tprovince"]
    });
    jQuery("#tpostalCode").popover(a);
    jQuery.validateField({
        field: "#tpostalCode",
        fields: ["#tpostalCode"]
    });
    jQuery("#tzipsearch").popover(a);
    jQuery.validateField({
        field: "#tzipsearch",
        fields: ["#tzipsearch"]
    });
    jQuery("#tzipSearchCompany").popover(a);
    jQuery.validateField({
        field: "#tzipSearchCompany",
        fields: ["#tzipSearchCompany", "#rAccount2"]
    });
    jQuery("#tzipSearchAddress").popover(a);
    jQuery.validateField({
        field: "#tzipSearchAddress",
        fields: ["#tzipSearchAddress"]
    });
    jQuery("#tzipSearchApt").popover(a);
    jQuery.validateField({
        field: "#tzipSearchApt",
        fields: ["#tzipSearchApt"]
    });
    jQuery("#tcrid").popover(a);
    jQuery.validateField({
        field: "#tcrid",
        fields: ["#tcrid"],
        required: true
    });
     */
});
