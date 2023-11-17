
mintNews = {
   id: "mintNews",
   news: [],
   init: function () {
      this.loadNews();
      if (undefined === $('#' + this.id).get(0) && this.news.length > 0) {
         $('body').append('<div id="' + this.id + '">' + this.getHTMLFromNews() + '</div>');
      }
      if ($('#' + this.id + ' div.mintNews-announcement:visible').length === 0) {
         this.addCloseButton();
      }
   },
   loadNews: function () {
      viewTools.api.callCustomApi({
         module: 'UsersNews',
         action: 'getNewsForUser',
         async: false,
         callback: function (data) {
            if (!_.isEmpty(data)) {
               data.forEach(function (users_news) {
                  this.news.push(new News(
                     users_news.news_type,
                     users_news.id,
                     users_news.name,
                     users_news.content_of_announcement,
                     viewTools.language.get('News', 'LBL_NEWS_' + users_news.news_type.toUpperCase() + '_BTN'),
                     users_news.comments
                  )
                  );
               }.bind(this));
            }
         }.bind(this)
      });
   },
   getHTMLFromNews: function () {
      var html = '';
      this.news.forEach(function (news_object) {
         html += news_object.getNewsBody();
      });
      return html;
   },
   closeBox: function (record_id, type) {
      if (typeof type !== 'undefined') {
         viewTools.api.callCustomApi({
            module: 'UsersNews',
            action: 'createOrUpdateUsersNews',
            async: false,
            dataPOST: {
               record_id: record_id
            }
         });
         if (type === 'announcement' && $('#' + this.id + ' div.mintNews-announcement:visible').length === 1) {
            this.addCloseButton();
         }
         if ($('#' + this.id + ' > div:visible').length <= 6) {
            $('#' + this.id).css('justify-content', 'center');
         }
      }
      $('#' + this.id + ' div[news-id="' + record_id + '"]').fadeOut();
      if ($('#' + this.id + ' > div:visible').length === 1) {
         this.close();
      }
   },
   addCloseButton: function () {
      $('#' + this.id).append('<span class="close-button suitepicon suitepicon-action-clear" onclick="mintNews.close();"></span>');
   },
   close: function () {
      $('#' + this.id).fadeOut();
   }
};
