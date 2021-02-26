/**
 *
 * @param f
 * @param d
 */
var validateFieldMapJSONField = function (f, d) {
    if(f.status === "success") {
        clearValidationError(d["fieldToValidate"]);

        if( d["fieldToValidate"] === 'tuserName' ) {
            jQuery("#check-username-available").css("display", "block");
        }
    }
    else {
        var g = [];
        jQuery.each(f.errors, function (j, h) {
            if (h.hasOwnProperty("field")) {
                jQuery("#form-group-" + escapeElementId(h.field)).addClass("has-error");
                jQuery("#" + escapeElementId(h.field)).attr("aria-invalid", "true");
                jQuery("#sr-only-error-" + escapeElementId(h.field)).empty().append(sanitizeHtmlOut(h.message));
                jQuery("#error-" + escapeElementId(h.field)).empty().append('<span class="error-txt-blk"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ' + sanitizeHtmlOut(h.message) + "</span>");
            }
        });

        if( d["fieldToValidate"] === 'tuserName' ) {
            //If user exists
            if( f.status === 'alternate' ) {
                var j = [];
                var k = 0;
                j.push('<div id="alternate-alias" class="well well-lg">Need to <a href="'+ baseUrl() +'/login"><strong>sign in</strong></a>?<hr>Try another username or choose one of our suggestions.');
                jQuery.each(f.list, function () {
                    k++;
                    j.push('<div class="radio">', "<label>", '<input type="radio" name="roptUn" id="roptUn' + k + '" value="' + this + '">', this, "</label>", "</div>");
                });
                j.push("</div>");
                jQuery("#check-username-response").html(j.join(""));
            }
        }//if username
    }
}

/**
 *
 * @param fieldName
 */
var clearValidationError = function (fieldName) {
    jQuery('#advice-required-entry-' + fieldName).remove();
    jQuery("#form-group-" + fieldName).removeClass("has-error");
    jQuery("#form-group-" + fieldName).removeClass("trigger");
    jQuery("#" + fieldName).removeClass("validation-failed");
    jQuery("#" + fieldName).removeAttr("aria-invalid");
    jQuery("#sr-only-error-" + + fieldName).empty();
    jQuery("#error-" + escapeElementId(fieldName)).empty();
}

var Register = Class.create();
Register.prototype = {
    initialize: function () {
        var _ajaxUrl = jQuery( '#registration-form' ).attr( 'action' );
        var _c = {
            content: "",
            placement: "top",
            trigger: "manual",
            html: true,
            "template": '<div class="popover popover-danger" role="tooltip"><div class="arrow"></div><div class="popover-content"></div></div>'
        };

        //If "Enter" then cancel form submit
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
        this._createProfileResponse = this._doCreateProfile.bindAsEventListener(this);

        /**
         * Step 1
         * @private
         */
        this._step1 = function () {
            /**
             * Username event handler
             * @private
             */
            this._tUsernameEventHandler = function () {
                jQuery("#tuserName").popover(_c);

                jQuery("#tuserName").
                on("focusin", function (d) {
                    if (jQuery("#form-group-tuserName").hasClass("has-error") || jQuery("#form-group-tuserName").hasClass("trigger")) {
                        jQuery("#form-group-tuserName").removeClass("trigger");
                        var e = {};
                        e["fieldToValidate"] = jQuery(this).attr("name");
                        e[jQuery(this).attr("name")] = jQuery(this).val();
                        e["required"] = true;
                        e["isAjax"] = true;
                        e["ajaxUrl"] = _ajaxUrl;
                        validateFieldMapJSON(e, false, function (f) { validateFieldMapJSONField(f, e); });
                    }
                }).
                on("focusout", function () {
                    jQuery("#check-username-response").empty();
                    var d = {};
                    d["fieldToValidate"] = jQuery(this).attr("name");
                    d[jQuery(this).attr("name")] = jQuery(this).val();
                    d["required"] = true;
                    d["isAjax"] = true;
                    d["ajaxUrl"] = _ajaxUrl;
                    jQuery("#check-username-available").css("display", "none");
                    validateFieldMapJSON(d, false, function (e) { validateFieldMapJSONField(e, d); });
                }).
                on("keyup", function () {
                    jQuery("#check-username-available").css("display", "none");
                    if (jQuery("#form-group-tuserName").hasClass("has-error")) {
                        clearValidationError('tuserName');
                    }
                }).
                on("keypress", function (d) {
                    //sReg();
                    jQuery(this).off("keypress");
                }).
                on("click", function () {
                    if (jQuery("#form-group-tuserName").hasClass("has-error") || jQuery("#form-group-tuserName").hasClass("trigger")) {
                        clearValidationError('tuserName');

                    }

                });
            };

            /**
             * User suggestion event handler
             * @private
             */
            this._tUsernameSuggestEventHandler = function () {
                jQuery("#check-username-response").on("change", "input:radio[name=roptUn]", function (a) {
                    jQuery("#tuserName").val(jQuery(this).val());
                    jQuery("#tuserName").trigger("keyup").trigger("focusout");
                    jQuery("#ssec1").focus();
                });
            };

            //Calling methods
            this._tUsernameEventHandler();
            this._tUsernameSuggestEventHandler();
        };

        /**
         * Step 2
         * @private
         */
        this._step2 = function () {
            //Security question
            /**
             * Security popover
             * @param n
             */
            var _securityAnswerPopOver = function (n) {
                var ansMatch = 'tsecAnswer' + n + 'Match';
                jQuery("#" + ansMatch).popover({
                    content: function () {
                        return jQuery("#popover_content_wrapper_" + ansMatch).html();
                    },
                    placement: "bottom",
                    trigger: "manual",
                    html: true,
                    "template": '<div class="popover" role="tooltip"><div class="arrow"></div><div class="popover-content"></div></div>'
                });
            }

            /**
             * Security question option handler
             * @private
             */
            var _securityQuestion = function () {
                var b = jQuery(this).attr("id");
                var e = parseInt(jQuery(this).val());
                var a = jQuery(this).attr("rel");
                jQuery("#form-group-" + b).removeClass("has-error");
                jQuery("#error-" + b).empty();
                jQuery("#sr-only-error-" + b).empty();
                var d = jQuery("#ssec3").clone();
                jQuery(this).find("option[value='']").prop("disabled", "disabled");
                var c = jQuery("#ssec" + a + "").val();
                jQuery("#ssec" + a + "").html(d.html());
                jQuery("#ssec" + a + " option[value='']").html((a == 1) ? "Select First Question" : "Select Second Question");
                if (jQuery.isNumeric(e)) {
                    jQuery("#ssec" + a + "").find("option[value=" + e + "]").remove();
                }
                jQuery("#ssec" + a + "").val(c);
                if (c != "") {
                    jQuery("#ssec" + a + "").find("option[value='']").prop("disabled", "disabled");
                }
                jQuery("#tsecAnswer" + b.substr(b.length - 1)).focus();
            };

            /**
             * Security questions
             * @private
             */
            this._securityQuestionEventHandler = function () {
                jQuery("#ssec1").
                change(_securityQuestion).
                on("click", function () {
                    if (jQuery("#ssec1").hasClass("validation-failed") ) {
                        clearValidationError('ssec1');
                    }
                });

                jQuery("#ssec2").
                change(_securityQuestion).
                on("click", function () {
                    if (jQuery("#ssec2").hasClass("validation-failed") ) {
                        clearValidationError('ssec2');
                    }
                });
            };

            //Security answers
            /**
             * Validate security answer
             * @param a
             * @param f
             * validateSecurityAnswer(jQuery(this), jQuery("#tsecAnswer1"));
             */
            var _validateSecurityAnswer = function(a, f) {
                var e = $(a).val()
                    , c = $(f).val();
                var b = escapeElementId($(a).attr("id"));
                var retypeEle = "#retype_" + b + '_info_';

                if (jQuery("#" + b).hasClass("has-error")) {
                    clearValidationError(b);
                }

                jQuery(retypeEle + "matching").hide();
                jQuery(retypeEle + "matching_progress").hide();
                jQuery(retypeEle + "match").hide();
                jQuery(retypeEle + "begin").hide();
                jQuery(retypeEle + "invalid").hide();

                if ( c.length == 0 ) {
                    jQuery("#form-group-" + b).addClass("has-error");
                    jQuery(retypeEle + "invalid").makeInValidPortal();
                    jQuery(retypeEle + "invalid").show();
                }
                else {
                    if (e == c) {
                        jQuery("#retype_" + b + "_info_match").show();
                    }
                    else {
                        for (var d = 0; d < e.length; d++) {
                            if (e.charAt(d) != c.charAt(d)) {
                                jQuery("#form-group-" + b).addClass("has-error");
                                jQuery(retypeEle + "matching").makeInValidPortal();
                                jQuery(retypeEle + "matching").show();
                                jQuery(retypeEle + "matching_progress").hide();
                                break;
                            }
                            else {
                                jQuery(retypeEle + "matching_progress").show();
                            }
                        }//for
                    }
                }
            }

            /**
             * Security answers handler
             * @param n
             */
            var _securityAnswerHandler = function(n) {
                var answer = 'tsecAnswer' + n;
                var answerEle = '#' + answer;
                var answerMatch = 'tsecAnswer' + n + 'Match';
                var answerMatchEle = '#' + answerMatch;

                jQuery("#" + answer).
                on("click", function () {
                    if (jQuery("#" + answer).hasClass("validation-failed") ) {
                        clearValidationError(answer);
                    }
                });

                jQuery(answerMatchEle).
                on("focus", function () {
                    _validateSecurityAnswer(jQuery(this), jQuery(answerEle));
                    jQuery(answerMatchEle).popover("show");
                }).
                on("keyup", function (d) {
                    d.stopImmediatePropagation();
                    _validateSecurityAnswer(jQuery(this), jQuery(answerEle));
                }).
                on("keydown", function () {
                    jQuery("#form-group-" + answerMatch).removeClass("has-error");
                }).
                on("blur", function () {
                    jQuery(this).popover("hide");
                }).
                on("focusout", function () {
                    secAnsMatch = jQuery(answerMatchEle).val();
                    if (jQuery("#form-group-" + answer).hasClass("has-error") || secAnsMatch.length == 0) {
                        jQuery("#form-group-" + answerMatch).addClass("has-error");
                    }
                    else {
                        if (jQuery(answerEle).val() == secAnsMatch) {
                            jQuery("#form-group-" + answerMatch).removeClass("has-error");
                        }
                        else {
                            jQuery("#form-group-" + answerMatch).addClass("has-error");
                        }
                    }
                }).
                on("click", function () {
                    if (jQuery(answerMatchEle).hasClass("validation-failed") ) {
                        clearValidationError(answerMatch);
                    }
                });
            }

            /**
             * Security answers
             * @private
             */
            this._securityAnswerEventHandler = function () {
                _securityAnswerHandler(1);
                _securityAnswerHandler(2);
            };

            //Calling methods
            this._securityQuestionEventHandler();
            _securityAnswerPopOver(1);
            _securityAnswerPopOver(2);
            this._securityAnswerEventHandler();
        };

        /**
         * Step 3
         * @private
         */
        this._step3 = function () {
            /**
             * Todo: Set all require entry for step 6
             * @private
             */
            var _setDrProfileRequire = function() {
                if (jQuery("input[name=raccount]:radio:checked").val() === "business") {
                    for(var i = 0; i < step6Array.length; i++) {
                        jQuery('#' + step6Array[i]).addClass('required-entry');
                    }
                }
                else {
                    for(var i = 0; i < step6Array.length; i++) {
                        jQuery('#' + step6Array[i]).removeClass('required-entry');
                        jQuery('#' + step6Array[i]).removeClass('validation-failed');
                        jQuery('#advice-required-entry-' + step6Array[i]).remove();
                    }
                    clearDrProfileForm();
                }
            }

            /**
             * Show account type
             */
            var _showAccountType = function () {
                if (jQuery("input[name=raccount]:radio:checked").val() === "business") {
                    jQuery("#address-type").empty().text("business");
                    jQuery("#cfrm").prop("checked", true);
                    jQuery("#cfrm").prop("disabled", true);
                    jQuery("#cfrmPartners").prop("checked", true);
                    addressWizardBusinessSetup();
                    drProfileSetup();
                }
                else {
                    jQuery("#dr-profile-section").hide();
                    jQuery("#address-type").empty().text("home");
                    jQuery("#cfrm").prop("checked", false);
                    jQuery("#cfrm").prop("disabled", false);
                    jQuery("#cfrmPartners").prop("checked", false);
                    addressWizardPersonalSetup();
                }

                jQuery("#contact-section").fadeIn();
                jQuery("#coa-message").removeClass("hidden");
                showSelectedLookupPanel();
                _setDrProfileRequire();
            };

            //Calling methods
            this._tAccountTypeEventHandler = function () {
                jQuery("input[name=raccount]").change(function () {
                    _showAccountType();
                    clearAddressForm();
                    resetLookupPanels();
                    jQuery('#submit-profile-section').show();
                });
            };
            this._tAccountTypeEventHandler();
        };

        /**
         * Step 4
         * @private
         */
        this._step4 = function () {
            var _phones = [{ "mask": "(###) ###-####"}, { "mask": "(###) ###-##############"}];
            var _emailRetypePopOver = function () {
                jQuery("#temailRetype").popover({
                    content: function () {
                        return jQuery("#popover_content_wrapper_temailRetype").html();
                    },
                    placement: "bottom",
                    trigger: "manual",
                    html: true,
                    "template": '<div class="popover" role="tooltip"><div class="arrow"></div><div class="popover-content"></div></div>'
                });
            }

            /**
             * validate email ajax
             * @private
             */
            var _checkEmailAjax = function () {
                new Ajax.Request(jQuery( '#action-url' ).val(), {
                    method: 'POST',
                    parameters: {
                        temail    : jQuery("#temail").val(),
                        ajax      : true,
                        fieldName : 'temail'
                    },
                    onSuccess: function(transport) {
                        if (transport && transport.status === 200) {
                            var data = transport.responseText.evalJSON(true);
                            if ( data.rs == 'success' ) {
                                var isValid =
                                    '<span id="check-temail-available" style="color:#218748; margin-top: 12px;">' +
                                        '<span class="glyphicon glyphicon-check" aria-hidden="true"></span>' +
                                        '<strong>' + data.successMsg + '</strong>' +
                                    '</span>';
                                jQuery("#result-temail").empty().hide().html(isValid).fadeIn();
                            }
                            else {
                                jQuery("#result-temail").empty().hide().html('');
                            }
                        }
                    },
                    onFailure: function() {
                        location.href = encodeURI(window.location.href);
                    }
                });
            };

            /**
             * Send email verification
             * @private
             */
            var _sendEmailVerificationAjax = function () {
                var submitUrl = (baseUrl()) + '/registration/emailverify';
                new Ajax.Request( submitUrl, {
                    method: 'POST',
                    parameters: {
                        email : jQuery("#tEmailVerification").val(),
                        ajax  : true
                    },
                    onSuccess: function(transport) {
                        if (transport && transport.status === 200) {
                            var data = transport.responseText.evalJSON(true);
                            if ( data.success ) {

                            }
                            else {

                            }
                        }
                    },
                    onFailure: function() {
                        location.href = encodeURI(window.location.href);
                    }
                });
            };

            /**
             * Validate re-type email
             * @param a
             * @param f
             * @private
             * validateReTypeEmail(jQuery(this), jQuery("#temail"));
             */
            var _validateReTypeEmail = function(a, f) {
                var e = $(a).val()
                    , c = $(f).val();
                var b = escapeElementId($(a).attr("id"));
                //b= temailRetype
                if (jQuery("#temailRetype").hasClass("has-error")) {
                    clearValidationError("temailRetype");
                }

                jQuery("#retype_temailRetype_info_matching").hide();
                jQuery("#retype_temailRetype_info_matching_progress").hide();
                jQuery("#retype_temailRetype_info_match").hide();
                jQuery("#retype_temailRetype_info_begin").hide();
                jQuery("#retype_temailRetype_info_invalid").hide();
                jQuery("#retype_temailRetype_info_invalid_match").hide();

                if ( c.length == 0 ) {
                    jQuery("#form-group-" + b).addClass("has-error");
                    jQuery("#retype_temailRetype_info_invalid").makeInValidPortal();
                    jQuery("#retype_temailRetype_info_invalid").show();
                }
                else {
                    if (e == c) {
                        if (jQuery("#form-group-temail").hasClass("has-error") || jQuery("#form-group-temailRetype").hasClass("has-error")) {
                            jQuery("#retype_temailRetype_info_invalid_match").show();
                        }
                        else {
                            jQuery("#retype_temailRetype_info_match").show();
                            clearValidationError("temailRetype");
                            clearValidationError("temail");
                        }
                    }
                    else {
                        for (var d = 0; d < e.length; d++) {
                            if (e.charAt(d) != c.charAt(d)) {
                                jQuery("#form-group-" + b).addClass("has-error");
                                jQuery("#retype_temailRetype_info_matching").makeInValidPortal();
                                jQuery("#retype_temailRetype_info_matching").show();
                                jQuery("#retype_temailRetype_info_matching_progress").hide();
                                break;
                            }
                            else {
                                jQuery("#retype_temailRetype_info_matching_progress").show();
                            }
                        }//for
                    }
                }
            }

            /**
             * Retype email handler
             * @private
             */
            this._tRetypeEmailEventHandler = function () {
                jQuery("#temailRetype").
                on("focus", function () {
                    _validateReTypeEmail(jQuery(this), jQuery("#temail"));
                    jQuery("#temailRetype").popover("show");
                }).
                on("keyup", function (d) {
                    d.stopImmediatePropagation();
                    _validateReTypeEmail(jQuery(this), jQuery("#temail"));
                }).
                on("keydown", function () {
                    jQuery("#form-group-temailRetype").removeClass("has-error");
                }).
                on("blur", function () {
                    jQuery(this).popover("hide");
                }).
                on("focusout", function () {
                    tEmail = jQuery("#temail").val()
                    if ( jQuery("#form-group-temail").hasClass("has-error") || tEmail.length == 0) {
                        jQuery("#form-group-temailRetype").addClass("has-error");
                    }
                    else {
                        //When leaving the field
                        if (jQuery("#temailRetype").val() == tEmail) {
                            if(  jQuery("#form-group-temail").hasClass("has-error") ) { }
                            //Only when email never registered before
                            else {
                                jQuery("#form-group-temailRetype").removeClass("has-error");
                                jQuery("#confirm-modal").modal("show");
                                jQuery("#confirm-modal").on("show.bs.modal",
                                    function (d) {
                                        jQuery("#email-verification-error").addClass("hidden");
                                        jQuery("#tEmailVerification").val("");
                                        jQuery("#btn-submit").removeClass("disabled");
                                        jQuery.unblockUI();
                                    });
                            }
                        }
                        else {
                            jQuery("#form-group-temail").addClass("has-error");
                        }
                    }
                }).
                on("click", function () {
                    if (jQuery("#temailRetype").hasClass("validation-failed") ||  jQuery("#form-group-temail").hasClass("has-error")  ) {
                        clearValidationError("temailRetype");
                    }
                });
            };

            /**
             * Verify modal popup handler
             * @private
             */
            this._tVerifyEmailDlgEventHandler = function () {
                jQuery("#btn-submit-verifyEmailDlg").
                on("click", function (d) {
                    d.preventDefault();
                    jQuery("#email-verification-error").addClass("hidden");
                    if (jQuery.trim(jQuery("#temail").val()) != jQuery.trim(jQuery("#tEmailVerification").val())) {
                        jQuery("#email-verification-error").removeClass("hidden");
                    }
                    else {
                        jQuery("#btn-submit").addClass("disabled");
                        //jQuery("#confirm-modal").modal("hide");
                        _sendEmailVerificationAjax();
                    }
                });
            };

            _emailRetypePopOver();
            jQuery("#confirm-modal").on("show.bs.modal", function (d) {
                jQuery("#email-verification-error").addClass("hidden");
                jQuery("#tEmailVerification").val("");
                jQuery("#btn-submit").removeClass("disabled");
                jQuery.unblockUI();
            });
            jQuery("#tfName").popover(_c);
            jQuery.validateField({
                field    : "#tfName",
                fields   : ["#tfName"],
                required : true,
                ajaxUrl  : _ajaxUrl
            });
            jQuery("#tlName").popover(_c);
            jQuery.validateField({field: "#tlName", fields: ["#tlName"] });
            jQuery("#tphone").popover(_c);
            jQuery.validateField({ field: "#tphone", fields: ["#tphone", "#sphoneType"]});
            jQuery("#temail").popover(_c);
            jQuery.validateField({field: "#temail", fields: ["#temail", "#temailRetype"]});
            jQuery("#temailRetype").popover(_c);
            jQuery.validateField({field: "#temailRetype", fields: ["#temailRetype", "#temail"]});
            jQuery("#temail").
            blur(function () {
                _checkEmailAjax();
            }).
            on("click", function () {
                if (jQuery("#form-group-temail").hasClass("has-error") || jQuery("#form-group-tuserName").hasClass("trigger")) {
                    clearValidationError('temail');

                }
            });
            jQuery("#tEmailVerification").
            on("click", function () {
                jQuery("#email-verification-error").addClass("hidden");
            });
            jQuery('#tphone').inputmask({
                mask: _phones,
                greedy: false,
                definitions: { '#': { validator: "[0-9]", cardinality: 1}}
            });

            //Calling methods
            this._tRetypeEmailEventHandler();
            this._tVerifyEmailDlgEventHandler();
        };

        /**
         * Submit new profile
         * @private
         */
        this._submitProfileEventHandler = function () {
            var dataForm = new VarienForm('registration-form', true);
            jQuery('#registration-form').on('submit', function(f) {
                jQuery.validateField({field: "#tsecAnswer1", fields: ["#tsecAnswer1", "#ssec1"]});

                f.preventDefault();
                var d = jQuery(this);

                if( dataForm.validator.validate() ) {
                    scrollToTop();
                    overlayOn();
                    this._createProfileHandler();
                }
                return false;
            }.bind(this));
        };

        /* Bind all events */
        this._bindEventHandlers();
    },

    _bindEventHandlers: function () {
        this._step1();
        this._step2();
        this._step3();
        this._step4();
        this._submitProfileEventHandler();
    },

    /**
     * Create profile
     * @private
     */
    _createProfileHandler: function () {
        var authUrl = jQuery( '#registration-form' ).attr( 'action' );
        var fieldsToSubmit = {
            //Step 1
            tuserName        : jQuery('#tuserName').val(),
            //Step 2
            tPassword        : jQuery('#tPassword').val(),
            tPasswordRetype  : jQuery('#tPasswordRetype').val(),
            ssec1            : jQuery('#ssec1').val(),
            tsecAnswer1      : jQuery('#tsecAnswer1').val(),
            tsecAnswer1Match : jQuery('#tsecAnswer1Match').val(),
            ssec2            : jQuery('#ssec2').val(),
            tsecAnswer2      : jQuery('#tsecAnswer2').val(),
            tsecAnswer2Match : jQuery('#tsecAnswer2Match').val(),
            //Step 3
            raccount         : jQuery("input[name=raccount]:radio:checked").val(),
            //Step 4
            stitle           : jQuery('#stitle').val(),
            tfName           : jQuery('#tfName').val(),
            tmI              : jQuery('#tmI').val(),
            tlName           : jQuery('#tlName').val(),
            tLanguageSpoken  : jQuery('#tLanguageSpoken').val(),
            temail           : jQuery('#temail').val(),
            temailRetype     : jQuery('#temailRetype').val(),
            tphone           : jQuery('#tphone').val(),
            text             : jQuery('#text').val(),
            cfrm             : jQuery('#cfrm').val(),
            cfrmPartners     : jQuery('#cfrmPartners').val(),
            //Step 5
            taddress         : jQuery('#taddress').val(),
            tapt             : jQuery('#tapt').val(),
            tcity            : jQuery('#tcity').val(),
            sstate           : jQuery('#sstate').val(),
            tzip             : jQuery('#tzip').val()
        };

        //Step 6
        if (jQuery("input[name=raccount]:radio:checked").val() === "business") {
            var step6 = {
                tMedicalType           : jQuery('#tMedicalType').val(),
                tSpecialties           : jQuery('#tSpecialties').val(),
                tPracticeName          : jQuery('#tPracticeName').val(),
                tEducation             : jQuery('#tEducation').val(),
                tSchoolOfGraduate      : jQuery('#tSchoolOfGraduate').val(),
                tInNetworkInsurances   : jQuery('#tInNetworkInsurances').val(),
                tHospitalAffiliations  : jQuery('#tHospitalAffiliations').val(),
                tResidency_1           : jQuery('#tResidency_1').val(),
                tResidency_2           : jQuery('#tResidency_2').val(),
                tProfessionalStatement : jQuery('#tProfessionalStatement').val()
            };
            jQuery.extend(fieldsToSubmit, step6);
        }

        var parameters = {
            fieldToSubmit : JSON.stringify(fieldsToSubmit),
            ajax          : true,
            readyToSubmit : true
        };

        new Ajax.Request(authUrl, {
            method: 'POST',
            parameters: parameters,
            onSuccess: this._createProfileResponse,
            onFailure: this._onFailure.bind(this)
        });
    },

    /**
     *
     * @param transport
     * @private
     */
    _doCreateProfile : function(transport) {
        if (transport && transport.status === 200) {
            var data = transport.responseText.evalJSON(true);
            if ( data.success ) {
                redirectToPage(data.forward);
            }
            else {
                overlayOff();
            }
        }
    },

    /**
     * On the event of failure, browser will come back the original page
     * @private
     */
    _onFailure: function () {
        location.href = encodeURI(window.location.href);
    }
};

jQuery(document).ready(function () {
    new Register();
});