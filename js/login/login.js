/**
 * Authenticate AJAX login
 */
var Authenticate = Class.create();
Authenticate.prototype = {
    initialize: function () {
        jQuery('#username').focus();
        this._authenticateResonse = this._doAuthenticate.bindAsEventListener(this);

        /**
         *
         * @return {undefined}
         */
        this._loginEventHandler = function () {
            var dataForm = new VarienForm('login-form', true);
            jQuery('#login-form').on('submit', function(e) {
                jQuery('.validation-advice').remove();
                if( dataForm.validator.validate() ) {
                    this._loginInit();
                    this._authenticateHandler();
                }
                return false;
            }.bind(this));
        };

        /**
         * Init login box and handling click event
         * @private
         */
        this._loginInit = function () {
            this._username = jQuery.trim(jQuery("#username").val())
            this._password = jQuery.trim(jQuery("#password").val())
            this._catpcha = (jQuery('#form-group-catpcha').css('display') == 'none' ) ?
                "NA" : jQuery.trim(jQuery("#captcha_code").val());
        };

        /* Bind all events */
        this._bindEventHandlers();
    },

    _bindEventHandlers: function () {
        this._loginEventHandler();
    },

    /**
     * Authenticate
     * @private
     */
    _authenticateHandler: function () {
        var authUrl = jQuery( '#login-form' ).attr( 'action' );
        var parameters = {
            username : this._username,
            password : this._password,
            catpcha  : this._catpcha,
            ajax     : true
        };

        jQuery('#login-submit-button-ajax').show();
        jQuery('#login-submit-button').hide();

        new Ajax.Request(authUrl, {
            method: 'POST',
            parameters: parameters,
            onSuccess: this._authenticateResonse,
            onFailure: this._onFailure.bind(this)
        });
    },

    /**
     * Do authenticate
     * @param transport
     * @private
     */
    _doAuthenticate : function(transport) {
        if (transport && transport.status === 200) {
            var data = transport.responseText.evalJSON(true);
            if (data.success) {
                redirectToPage(data.forward);
            }
            else {
                var msg = '<div class="message-error" role="alert"> ' + data.error + '</div>';
                jQuery('#captcha_image').prop('src', (baseUrl()) + '/app/code/Core/securimage/securimage_show.php?sid=' + Math.random());
                jQuery('#login-submit-button').show();
                jQuery('#login-submit-button-ajax').hide();
                jQuery('#login-message').html(msg);
                jQuery('#captcha_code').val('');
                if(data.failedCounts >= 3) {
                    jQuery('#form-group-catpcha').fadeIn()
                }
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

var Forgotpassword = Class.create();
Forgotpassword.prototype = {
    initialize: function () {
        jQuery('#tuserName').focus();
        this._forgotResonse = this._doSetPassword.bindAsEventListener(this);

        /**
         *
         * @return {undefined}
         */
        this._forgotPasswordEventHandler = function () {
            var dataForm = new VarienForm('forgotPasswordForm', true);
            jQuery('#forgotPasswordForm').on('submit', function(e) {
                jQuery('.validation-advice').remove();
                jQuery('#forgot-password-message').html('');
                if( dataForm.validator.validate() ) {
                    this._forgotInit();
                    this._forgotPasswordHandler();
                }

                return false;
            }.bind(this));
        };

        /**
         * Init login box and handling click event
         * @private
         */
        this._forgotInit = function () {
            this._username = jQuery.trim(jQuery("#tuserName").val());
        };

        /**
         *
         * @private
         */
        this._resetTmpPassword = function () {
            jQuery('#send-new-temp-password-code').on('click', function(e) {
                jQuery( "#forgot-password-message" ).html('');
                jQuery('#forgot-password-submit-button-ajax').show();
                jQuery('#resend-password-link').hide();
                jQuery( "#forgotPasswordForm" ).submit();
                return false;
            }.bind(this));
        };


        /* Bind all events */
        this._bindEventHandlers();
    },

    _bindEventHandlers: function () {
        this._forgotPasswordEventHandler();
        this._resetTmpPassword();
    },

    /**
     *
     * @private
     */
    _forgotPasswordHandler: function () {
        var forgotUrl = jQuery( '#forgotPasswordForm' ).attr( 'action' );
        var parameters = {
            username : this._username,
            ajax     : true
        };

        jQuery('#forgot-password-submit-button-ajax').show();
        jQuery('#forgot-password-submit-button').hide();

        new Ajax.Request(forgotUrl, {
            method: 'POST',
            parameters: parameters,
            onSuccess: this._forgotResonse,
            onFailure: this._onFailure.bind(this)
        });
    },

    /**
     * Do reset password
     * @param transport
     * @private
     */
    _doSetPassword : function(transport) {
        if (transport && transport.status === 200) {
            var data = transport.responseText.evalJSON(true);
            var msgClass = 'message-error';
            var msg = data.error;

            if( data.success ) {
                msgClass = 'message-success';
                var loginUrl = (baseUrl()) + '/login';
                var divLogin =
                    '<div class="btn-wrap-single">' +
                          '<img id="forgot-password-submit-button-ajax" style="display: none" src="'+ (jQuery('#img-url').val()) +'/spinner.gif">' +
                          '<a id="resend-password-link" class="btn btn-primary btn-lg btn-block" href="' + loginUrl + '" tabindex="6" class="btn btn-default btn-no-min btn-lg btn-block" title="Continue">Continue</a>' +
                    '</div>';
                msg =
                    '<div id="send-new-code-msg" style="display: block; color: #218748;">' +
                        '<strong>' + data.successMsg + '</strong>' +
                    '</div>';

                jQuery('#btn-wrap-single-continue').html(divLogin);
                jQuery('#resend-new-code').show();
            }

            jQuery('#forgot-password-submit-button').show();
            jQuery('#forgot-password-submit-button-ajax').hide();
            jQuery('#forgot-password-message').html('<div class="' + msgClass +'" role="alert"> ' + msg + '</div>');
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

var Resetpassword = Class.create();
Resetpassword.prototype = {
    initialize: function () {
        jQuery('#tPassword').focus();
        this._resetResponse = this._doResetPassword.bindAsEventListener(this);

        /**
         *
         * @return {undefined}
         */
        this._forgotPasswordEventHandler = function () {
            var dataForm = new VarienForm('reset-password-form', true);
            jQuery('#reset-password-form').on('submit', function(e) {
                jQuery('.validation-advice').remove();
                jQuery('#response-change-msg').html('');
                if( dataForm.validator.validate() ) {
                    this._resetInit();
                    this._resetPasswordHandler();
                }
                return false;
            }.bind(this));
        };

        /**
         * Init login box and handling click event
         * @private
         */
        this._resetInit = function () {
            this._password = jQuery.trim(jQuery("#tPassword").val());
            this._retype_password = jQuery.trim(jQuery("#tPasswordRetype").val());
        };

        /* Bind all events */
        this._bindEventHandlers();
    },

    _bindEventHandlers: function () {
        this._forgotPasswordEventHandler();
    },

    /**
     * Reset password handler
     * @private
     */
    _resetPasswordHandler: function () {
        var resetUrl = jQuery( '#reset-password-form' ).attr( 'action' );
        var parameters = {
            password : this._password,
            passwordRetype : this._retype_password,
            ajax     : true
        };

        jQuery('#reset-submit-button-ajax').show();
        jQuery('#reset-submit-button').hide();

        new Ajax.Request(resetUrl, {
            method: 'POST',
            parameters: parameters,
            onSuccess: this._resetResponse,
            onFailure: this._onFailure.bind(this)
        });
    },

    /**
     *
     * @param transport
     * @private
     */
    _doResetPassword : function(transport) {
        if (transport && transport.status === 200) {
            var data = transport.responseText.evalJSON(true);
            if (data.success) {
                redirectToPage(data.forward);
            }
            else {
                var msg = '<div class="message-error" role="alert"> ' + data.error + '</div>';
                var errorMsg = data.error.evalJSON(true)
                Object.keys(errorMsg).forEach(function(key, index) {
                    var msg = '<div class="message-error" role="alert"> ' + this[key] + '</div>';
                    jQuery('#' + key).html(msg);
                }, errorMsg);
                jQuery('#reset-submit-button').show();
                jQuery('#reset-submit-button-ajax').hide();
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

var Forgotusername = Class.create();
Forgotusername.prototype = {
    initialize: function () {
        jQuery('#temail').focus();
        this._forgotResponse = this._doSendAssociateEmail.bindAsEventListener(this);

        this._forgotUsernameEventHandler = function () {
            var dataForm = new VarienForm('forgotUsernameForm', true);
            jQuery('#forgotUsernameForm').on('submit', function(e) {

                jQuery('.validation-advice').remove();
                jQuery('#row2-step2').addClass("hidden");

                if( dataForm.validator.validate()   ) {
                    if( this._isValidEmail() ) {
                        jQuery('#error-temail').html('');
                        this._forgotUserInit();
                        this._forgotUserNameHandler();
                    }
                    else {
                        var msg = '<div class="message-error" role="alert">Invalid email format!</div>';
                        jQuery('#error-temail').html(msg);
                    }
                }
                return false;
            }.bind(this));
        };

        /**
         *
         * @type {any}
         * @private
         */
        this._isValidEmail = function () {
            var email = jQuery.trim(jQuery("#temail").val());
            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
            return emailPattern.test(email);
        }.bind(this);

        /**
         * Init email box and handling click event
         * @private
         */
        this._forgotUserInit = function () {
            this._email = jQuery.trim(jQuery("#temail").val());
        };

        this._bindEventHandlers();
    },

    _bindEventHandlers: function () {
        this._forgotUsernameEventHandler();
    },

    /**
     * Reset password handler
     * @private
     */
    _forgotUserNameHandler: function () {
        var forgotUserNameUrl = jQuery( '#forgotUsernameForm' ).attr( 'action' );
        var parameters = {
            email : this._email,
            ajax  : true
        };

        jQuery('#forgot-username-submit-button-ajax').show();
        jQuery('#btn-submit').hide();

        new Ajax.Request(forgotUserNameUrl, {
            method: 'POST',
            parameters: parameters,
            onSuccess: this._forgotResponse,
            onFailure: this._onFailure.bind(this)
        });
    },

    /**
     *
     * @param transport
     * @private
     */
    _doSendAssociateEmail : function(transport) {
        if (transport && transport.status === 200) {
            var data = transport.responseText.evalJSON(true);
            if (data.success) {
                jQuery('#row2-step2').removeClass("hidden").fadeIn();
            }
            else {
                var msg = '<div class="message-error" role="alert"> ' + data.error + '</div>';
                jQuery('#error-temail').html(msg);
                jQuery('#need-to-create-acc').removeClass('hidden').fadeIn();
            }

            jQuery('#btn-submit').show();
            jQuery('#forgot-username-submit-button-ajax').hide();
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