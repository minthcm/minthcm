var MintHCMPopup = function (title, body, buttons, options, onShow) {

   if (!(this instanceof MintHCMPopup)) {
      return new MintHCMPopup(title, body, buttons, options, onShow);
   }

   this.id = "MintHCMPopup";
   this.title = title || "";
   this.body = body || "";
   this.buttons = buttons || [];
   this.options = options || {};
   this.onShow = onShow || null;

   this.init = function () {
      if (undefined !== $('#' + this.id).get(0)) {
         $('#' + this.id).remove();
      }
      $('body').append('<div id="' + this.id + '">' + this.getBody() + '</div>');
      if (typeof this.options.noCloseButton !== 'undefined' && this.options.noCloseButton) {
         $('.MintHCMPopup-header').removeClass('MintHCMPopup-close');
      } else {
         $('.MintHCMPopup-close').click(MintHCMPopup.close);
      }
      if (typeof this.options.css !== 'undefined' && this.options.css) {
         $('#MintHCMPopup > .MintHCMPopup-container').css(this.options.css);
      }
      this.setButtonsEvents();
      if (this.onShow) {
         this.onShow();
      }
   };

   this.getBody = function () {
      var body = _.template('<div class="MintHCMPopup-container"><div class="MintHCMPopup-header MintHCMPopup-close"><div class="MintHCMPopup-title"><%= title %></div><span class="suitepicon suitepicon-action-clear"></span><div style="clear: both;"></div></div><div class="MintHCMPopup-body"><%= body %></div><div class="MintHCMPopup-buttons"><%= buttons %></div></div>');
      return body({
         title: this.title,
         body: this.body,
         buttons: this.getButtons()
      });
   };

   this.getButtons = function () {
      var button = _.template('<input type="button" value="<%= text %>" />');
      var buttons = "";
      this.buttons.forEach(function (btn) {
         buttons += button({ text: btn.text });
      });
      return buttons;
   };

   this.setButtonsEvents = function () {
      const _this = this;
      $('.MintHCMPopup-buttons input').each(function (index) {
         $(this).click(_this.buttons[index].click);
      });
   };

   this.init();
   return this;
};

MintHCMPopup.close = function () {
   $('#MintHCMPopup').fadeOut();
};
