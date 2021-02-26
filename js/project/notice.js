var notice = Class.create();
notice.prototype = {
    initialize: function (type, msg) {
        var acknowledged = jQuery.Deferred();
        this.imgUrl = baseImgUrl();
        this._msg = msg;
        var div;

        if( type === 'error' ) {
            div = this._error();
        }
        else if(type === 'notice') {
            div = this._notice();
        }
        else if(type === 'inactive') {
            div = this._inactive();
        }

        var buttons = {
            'OK': function() {
                jQuery(this).dialog('close').empty().remove();
                acknowledged.resolve();
            },
        };

        var title = jQuery('#app-name').val();
        var dialogOpts = {'closeOnEscape':false,'width':550,'height':170,'buttons':buttons,'modal':true,'autoOpen':true,'dialogClass':'vboxDialogContent','title':title};
        jQuery(div).dialog(dialogOpts);
    },

    /**
     * Error
     * @returns {*}
     * @private
     */
    _error : function() {
        var msg = '<img src="' + this.imgUrl + '/50px-Warning_icon.svg.png" class="alert-img"/><div class="alert-message">'+ this._msg + '</div>';
        var div = jQuery('<div />').attr({'class':'vboxDialogContent vboxAlert'}).html(msg);

        return div;
    },

    /**
     * Notice
     * @returns {*}
     * @private
     */
    _notice : function() {
        var msg = '<div class="alert-message">'+this._msg + '</div>';
        var div = jQuery('<div />').attr({'class':'vboxDialogContent vboxAlert'}).html(msg);

        return div;
    },
    
};