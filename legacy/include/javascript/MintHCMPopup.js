var MintHCMPopup = function (title, body, buttons, options, onShow) {
    var classPopup = new MintHCMPopupClass(title, body, buttons, options, onShow);
    return classPopup.init();
}
MintHCMPopup.close = function () {
    var id = $( '.MintHCMPopup' ).last().attr( 'id' );
    $('#' + id).remove();
};

class MintHCMPopupClass {

    constructor(title, body, buttons, options, onShow, load_id) {
        this.id = load_id || Date.now();
        this.title = title || "";
        this.body = body || "";
        this.buttons = buttons || [];
        this.options = options || {};
        this.onShow = onShow || function () {};
    }

    init() {
        this.init = function () {
            if ( undefined !== $( '#' + this.id ).get( 0 ) ) {
                $( '#' + this.id ).remove();
            }
            $( 'body' ).append( '<div class="MintHCMPopup" id="' + this.id + '">' + this.getBody() + '</div>' );
            setTimeout( function () { //special timeout. Javasript has to wait on finishing append. So, does not remove the TimeOut
                this.onShow();
            }.bind(this), 0 );
            if ( typeof this.options.noCloseButton !== 'undefined' && this.options.noCloseButton ) {
                $( '.MintHCMPopup-header' ).removeClass( 'MintHCMPopup-close' );
            } else {
                $( '#' + this.id + '_close' ).click( this.close );
            }
            if ( typeof this.options.css !== 'undefined' && this.options.css ) {
                $( '#' + this.id + '> .MintHCMPopup-container' ).css( this.options.css );
            }
            this.setButtonsEvents();
        };

        this.getBody = function () {
            var body = _.template( '<div class="MintHCMPopup-container"><div class="MintHCMPopup-header MintHCMPopup-close"><div class="MintHCMPopup-title"><input id="<%= id %>_hidenId" type="hidden" value="<%= id %>"></input><%= title %></div><span id="<%=id %>_close" class="suitepicon suitepicon-action-clear"></span><div style="clear: both;"></div></div><div class="MintHCMPopup-body"><%= body %></div><div class="MintHCMPopup-buttons"><%= buttons %></div></div>' );
            return body( {
                title: this.title,
                body: this.body,
                buttons: this.getButtons(),
                id: this.id
            } );
        };

        this.getButtons = function () {
            const button = _.template( '<input type="button" accesskey="<%= accesskey %>" value="<%= text %>" id="<%= id %>" <% if (primary) { %> class="button primary" <% } %> />' );
            let buttonsLeft = '';
            let buttonsRight = '';
            this.buttons.forEach( function ( btn ) {
                const buttonHtml = button({
                    text: btn.text,
                    id: btn.id ?? btn.text.replace(' ', '_'),
                    primary: !!btn.primary,
                    accesskey: btn.accesskey
                });
                if (btn.left) {
                    buttonsLeft += buttonHtml
                } else {
                    buttonsRight += buttonHtml
                }
            });
            return `<div>${buttonsLeft}</div><div>${buttonsRight}</div>`;
        };

        this.setButtonsEvents = function () {
            const _this = this;
            $( '#' + this.id + ' .MintHCMPopup-buttons input' ).each( function () {
                $( this ).click( _this.buttons.find(btn => btn.text === $(this).val())?.click );
            } );
        };



        this.init();
        return this;
    }
    close() {
        var new_id = this.id.replace( "_close", "_hidenId" );
        var id_close = document.getElementById( new_id ).value;
        $( '#' + id_close ).fadeOut( 400, function () {
            $( '#' + id_close ).remove();
        } );
    }
    ;

};


var showLoadingScreen = function (title, message) {
    var loading;
    if (message) {
        var body = "<div class='MintHCMPopup-load'><img src='themes/default/images/loading.gif' alt='loading'></img><div class='MintHCMPopup-load-message'><span>" + message + "</span></div></div>";
    }
    else {
        var body = "<div class='MintHCMPopup-load'><img src='themes/default/images/loading.gif' alt='loading'></img></div>";
    }
    loading = new MintHCMPopupClass(title, body, '', { noCloseButton: true }, '', 'load');
    loading.init();
    return loading;
};
var closeLoadingScreen = function () {
    $('#load').remove();
    return true;
};

MintHCMPopup.confirm = function (body, options = {}) {
    return new Promise((resolve) => {
        MintHCMPopup(
            viewTools.language.get('app_strings', 'LBL_CONFIRM'),
            body,
            [
                {
                    text: options.customLabels?.noBtn || viewTools.language.get('app_strings', 'LBL_NO'),
                    click: () => {
                        MintHCMPopup.close();
                        resolve(false);
                    },
                    left: true,
                },
                {
                    text: options.customLabels?.yesBtn || viewTools.language.get('app_strings', 'LBL_YES'),
                    click: () => {
                        MintHCMPopup.close();
                        resolve(true);
                    },
                    primary: true,
                },
            ],
            {
                noCloseButton: true,
                css: { whiteSpace: 'break-spaces', maxWidth: '500px' },
            },
        );
    });
}

MintHCMPopup.alert = function (body, options = {}) {
    return new Promise((resolve) => {
        MintHCMPopup(
            '',
            body,
            [
                {
                    text: options.customLabels?.confirmBtn || viewTools.language.get('app_strings', 'LBL_OK'),
                    click: () => {
                        MintHCMPopup.close();
                        resolve(true);
                    },
                    primary: true,
                },
            ],
            {
                noCloseButton: true,
                css: { whiteSpace: 'break-spaces', maxWidth: '500px' },
            },
        );
    });
}
