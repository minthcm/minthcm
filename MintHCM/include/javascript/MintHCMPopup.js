var MintHCMPopup = function ( title, body, buttons, options ) {

   if ( !(this instanceof MintHCMPopup) ) {
      return new MintHCMPopup( title, body, buttons, options );
   }

   this.id = "MintHCMPopup";
   this.title = title || "";
   this.body = body || "";
   this.buttons = buttons || [ ];
   this.options = options || { };

   this.init = function () {
      var _this = this;
      if ( undefined !== $( '#' + _this.id ).get( 0 ) ) {
         $( '#' + _this.id ).remove();
      }
      $( 'body' ).append( '<div id="' + _this.id + '">' + _this.getBody() + '</div>' );
      if ( typeof _this.options.noCloseButton !== 'undefined' && _this.options.noCloseButton ) {
         $( '.MintHCMPopup-header' ).removeClass( 'MintHCMPopup-close' );
      } else {
         $( '.MintHCMPopup-close' ).click( MintHCMPopup.close );
      }
      _this.setButtonsEvents();
   };

   this.getBody = function () {
      var body = _.template( '<div class="MintHCMPopup-container"><div class="MintHCMPopup-header MintHCMPopup-close"><div class="MintHCMPopup-title"><%= title %></div><span class="suitepicon suitepicon-action-clear"></span><div style="clear: both;"></div></div><div class="MintHCMPopup-body"><%= body %></div><div class="MintHCMPopup-buttons"><%= buttons %></div></div>' );
      return body( {
         title: this.title,
         body: this.body,
         buttons: this.getButtons()
      } );
   };

   this.getButtons = function () {
      var button = _.template( '<input type="button" value="<%= text %>" />' );
      var buttons = "";
      this.buttons.forEach( function ( btn ) {
         buttons += button( {text: btn.text} );
      } );
      return buttons;
   };

   this.setButtonsEvents = function () {
      var _this = this;
      $( '.MintHCMPopup-buttons input' ).each( function ( index ) {
         $( this ).click( _this.buttons[index].click );
      } );
   };

   this.init();
   return this;
};

MintHCMPopup.close = function () {
   $( '#MintHCMPopup' ).fadeOut();
};
