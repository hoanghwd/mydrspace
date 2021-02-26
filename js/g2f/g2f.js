var GoogleAuthenticate = Class.create();

GoogleAuthenticate.prototype = {
    initialize: function () {
        jQuery(function () {
            jQuery('[data-toggle="popover"]').popover({
                container: "body",
                trigger: "focus hover",
                placement: "top",
                html: true,
                content: popoverContent
            });
        });

        jQuery('#code').on("touch, keydown", function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode  ;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            else {
                return true;
            }
        }).on("keyup", function () {

        });

        jQuery('#code').focus();
        this._verifyResonse = this._doVerify.bindAsEventListener(this);

        /**
         *
         * @return {undefined}
         */
        this._verifyEventHandler = function () {
            var dataForm = new VarienForm('device-form', true);
            jQuery('#device-form').on('submit', function(e) {
                jQuery('.validation-advice').remove();
                if( dataForm.validator.validate() ) {
                    this._verifyInit();
                    this._verifyHandler();
                }
                return false;
            }.bind(this));
        };

        /**
         * Init login box and handling click event
         * @private
         */
        this._verifyInit = function () {
            this._code = jQuery.trim(jQuery("#code").val());
        };

        /* Bind all events */
        this._bindEventHandlers();
    },

    _bindEventHandlers: function () {
        this._verifyEventHandler();
    },

    /**
     * Authenticate
     * @private
     */
    _verifyHandler: function () {
        var verifyUrl = jQuery( '#device-form' ).attr( 'action' );

        var parameters = {
            code : this._code,
            ajax : true
        };

        jQuery('#device-submit-button-ajax').show();
        jQuery('#g2f-submit-button').hide();

        new Ajax.Request(verifyUrl, {
            method: 'POST',
            parameters: parameters,
            onSuccess: this._verifyResonse,
            onFailure: this._onFailure.bind(this)
        });
    },

    /**
     * Do verify
     * @param transport
     * @private
     */
    _doVerify : function(transport) {
        if (transport && transport.status === 200) {
            var data = transport.responseText.evalJSON(true);
            if (data.success) {

            }
            else {
                var msg = '<div class="message-error" role="alert"> ' + data.error + '</div>';
                jQuery('#g2f-submit-button').show();
                jQuery('#device-submit-button-ajax').hide();
                jQuery('#auth-message').html(msg);
            }

            if( data.forward !== '' ) {
                redirectToPage(data.forward);
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
    new GoogleAuthenticate();
});