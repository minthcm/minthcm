function changeNewsStatus( status ) {
   if ( status === 'published' ) {
      var dialog_id = "changeNewsStatus";
      if ( undefined === $( '#' + dialog_id ).get( 0 ) ) {
         $( 'body' ).append( '<div id="' + dialog_id + '" >' + viewTools.language.get( 'News', 'LBL_DIALOG_TEXT' ) + '</div>' );
      }
      $( "#" + dialog_id ).dialog( {
         buttons: [
            {
               text: viewTools.language.get( 'News', 'LBL_DIALOG_YES_BTN' ),
               click: function () {
                  setNewsStatus( status );
                  $( this ).dialog( "close" );
               }
            },
            {
               text: viewTools.language.get( 'News', 'LBL_DIALOG_NO_BTN' ),
               click: function () {
                  $( this ).dialog( "close" );
               }
            }
         ]
      } );
   } else if ( status === 'archived' ) {
      setNewsStatus( status );
   }
}

function setNewsStatus( status ) {
   var news_id = $( 'form[name="DetailView"] input[type="hidden"][name="record"]' ).val();
   if ( typeof news_id !== 'undefined' ) {
      viewTools.api.callCustomApi( {
         module: 'News',
         action: 'setNewsStatus',
         dataPOST: {
            news_id: news_id,
            status: status
         },
         callback: function ( data ) {
            if ( data ) {
               location.reload();
            } else {
               viewTools.GUI.statusBox.showStatus( viewTools.language.get( 'News', 'LBL_' + status.toUpperCase() + '_ERROR' ), 'error', 2000 );
            }
         }
      } );
   } else {
      viewTools.GUI.statusBox.showStatus( viewTools.language.get( 'News', 'LBL_' + status.toUpperCase() + '_ERROR' ), 'error', 2000 );
   }
}