mintNews = {
   id: "mintNews",
   init: function () {
      var _this = this;
      var news = _this.getNews();
      if ( undefined === $( '#' + _this.id ).get( 0 ) && news !== '' ) {
         $( 'body' ).append( '<div id="' + _this.id + '">' + _this.getBody( news ) + '</div>' );
      }
      if ( $( 'div.mintNews-boxes div.mintNews-announcement:visible' ).length === 0 ) {
         this.addCloseButton();
      }
   },
   getBody: function ( news ) {
      var body = _.template( '<div class="mintNews-container"><div class="mintNews-header"><%= title %></div><div class="mintNews-boxes"><%= news %></div></div>' );
      return body( {
         title: viewTools.language.get( 'News', 'LBL_NEWS_TITLE' ),
         news: news
      } );
   },
   getNews: function () {
      var _this = this;
      var announcements = '';
      viewTools.api.callCustomApi( {
         module: 'UsersNews',
         action: 'getNewsForUser',
         callback: function ( data ) {
            if ( !_.isEmpty( data ) ) {
               data.forEach( function ( news ) {
                  announcements += _this.getBoxTemplate( news.news_type, news.id, news.name, news.content_of_announcement, viewTools.language.get( 'News', 'LBL_NEWS_' + news.news_type.toUpperCase() + '_BTN' ) );
               } );
            }
         }
      } );
      return announcements;
   },
   getBoxTemplate: function ( type, record_id, name, content_of_announcement, button_text ) {
      var template = '<div class="mintNews-<%= type %>" news-id="<%= record_id %>"><div class="mintNews-box-header"><%= name %></div><div class="mintNews-box-content"><div class="mintNews-box-text"><%= content_of_announcement %></div><div class="mintNews-box-button">';
      if ( type === 'reminder' ) {
         template += '<input type="button" value="' + viewTools.language.get( 'News', 'LBL_NEWS_ANNOUNCEMENT_BTN' ) + '" onclick="mintNews.closeBox(\'' + record_id + '\')" />';
      }
      template += '<input type="button" value="<%= button_text %>" onclick="mintNews.closeBox(\'' + record_id + '\',\'' + type + '\')" /></div></div></div>';
      var body = _.template( template );
      return body( {
         type: type,
         record_id: record_id,
         name: name,
         content_of_announcement: content_of_announcement,
         button_text: button_text
      } );
   },
   closeBox: function ( record_id, type ) {
      if ( typeof type !== 'undefined' ) {
         viewTools.api.callCustomApi( {
            module: 'UsersNews',
            action: 'createOrUpdateUsersNews',
            dataPOST: {
               record_id: record_id
            }
         } );
         if ( type === 'announcement' && $( 'div.mintNews-boxes div.mintNews-announcement:visible' ).length === 1 ) {
            this.addCloseButton();
         }
      }
      $( 'div.mintNews-boxes div[news-id="' + record_id + '"]' ).fadeOut();
      if ( $( 'div.mintNews-boxes > div:visible' ).length === 1 ) {
         this.close();
      }
   },
   addCloseButton: function () {
      $( '#mintNews .mintNews-header' ).append( '<span class="suitepicon suitepicon-action-clear" onclick="mintNews.close();"></span>' );
   },
   close: function () {
      var _this = this;
      $( '#' + _this.id ).fadeOut();
   }
};
