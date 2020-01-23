
var icons = {
   LOADING: "k/css/spicecrm-theme/resources/images/tree/loading.gif",
   OPENED: "k/css/spicecrm-theme/resources/images/tree/folder-open.png",
   CLOSED: "k/css/spicecrm-theme/resources/images/tree/folder.png",
   LEAF: "k/css/spicecrm-theme/resources/images/tree/leaf.png",
}

var root_prototype = {
   expanded: false,
   text: "Root",
   id: "root_node",
   nodes_history: [ "root" ],
   values_history: [ "Root" ],
};

var kreporter_dashlet_prepared = false;

Ext.define( "SpiceCRM.KReporter.Viewer.store.reportResults", {
   extend: "Ext.data.TreeStore",
   alias: [ "store.KReportViewer.plugins.reportResults" ],
   reportId: "undefined",
   whereConditions: '',
   sort: 0,
   root: root_prototype,
   allowRender: false,
   buildLinkedFields: function () {},
   allowRendering: function () {
      this.allowRender = true;
      this.getRoot().expand();
   },
   listeners: {
      load: function ( ) {
         if ( window.insideSugarDashlet ) {
            $( '.x-grid-view' ).last().css( 'overflow', 'unset' );

            var current_style = $( '.x-grid-header-ct' ).attr( 'style' );
            $( '.x-grid-header-ct' ).last().attr( 'style', current_style + 'position: absolute !important;' )
            $( '.x-grid-header-ct' ).last().css( 'left', '0px' );

            var topDistance = 0;
            setTimeout( function () {
               $( '#kreportviewer' ).find( '.x-panel.x-panel-default' ).first().css( 'height', '' );
               $( '#kreportviewer' ).find( '.x-panel-body.x-panel-body-default' ).first().css( 'height', '' );

               topDistance = $( '.x-grid-header-ct' ).last().offset().top;
               $( '#kreportviewer' ).find( 'div[id^="KReportViewer-VisualizationContainer"].x-panel-body' ).width( $( window.frameElement ).width() - 50 );
            }.bind( this ), 200 );

            $( window ).scroll( function () {
               if ( $( this ).scrollTop() > topDistance ) {
                  $( '.x-grid-header-ct' ).last().css( {
                     'top': $( this ).scrollTop() - topDistance
                  } );
               } else if ( $( '.x-grid-header-ct' ).last().css( 'top' ) !== '0px' ) {
                  $( '.x-grid-header-ct' ).last().css( {
                     'top': '0px'
                  } );
               }
            } );
         }
      },
      nodebeforeappend: function ( obj, node ) {
         if ( !node.data.leaf ) {
            node.set( 'icon', icons.CLOSED );
         } else {
            node.set( 'icon', icons.LEAF );
         }
      },
      nodebeforeexpand: function ( node ) {
         if ( !this.allowRender )
            return false;
         node.set( 'icon', icons.LOADING );
         if ( node.dataLoaded !== true ) {
            loadNodeData( this.panel, node, this.whereConditions );
         }
      },
      nodeexpand: function ( node ) {
         if ( node.dataLoaded === true ) {
            node.set( 'icon', icons.OPENED );
         }
         refreshDashletHeight();
      },
      nodecollapse: function ( node ) {
         node.set( 'icon', icons.CLOSED );
         refreshDashletHeight( true );
      },
      update: function () {
         refreshDashletHeight();
      }
   }
} );

Ext.define( "SpiceCRM.KReporter.Viewer.controller.plugins.StandardViewerController", {
   extend: "Ext.app.ViewController",
   alias: "controller.KReportViewer.plugins.StandardViewerController",
   whereConfig: {},
   config: {
      listen: {
         global: {
            whereClauseUpdated: function ( a, b ) {
               this.reloadStore( a, b );
            },
         }
      }
   },
   reloadStore: function ( a, b ) {
      var c = this.view.getStore();
      c.whereConditions = Ext.encode( a );
      c.sort = Ext.encode( b );
      c.removeAll();
      c.setRoot( root_prototype );
      c.getRoot().expand();
   },
} );

Ext.define( "SpiceCRM.KReporter.Viewer.plugins.KTreeViewPanel", {
   extend: "Ext.tree.Panel",
   itemId: "KReporterViewerPresentation",
   store: {
      type: "KReportViewer.plugins.reportResults",
   },
   controller: "KReportViewer.plugins.StandardViewerController",
   rootVisible: false,
   reportId: 'undefined',
   columns: [ ],
   scrollable: false,
   initComponent: function () {
      this.callParent();

      this.reportId = this.reportRecord.get( "id" );
      this.store.panel = this;
      this.store.allowRendering();

      this.store.load();
   },
   listeners: {
      columnschanged: function () {
         refreshDashletHeight( true );
      }
   }
} );

function refreshDashletHeight( delay ) {
   if ( window.insideSugarDashlet ) {
      if ( delay ) {
         setTimeout( function () {
            $( '#kreportviewer' ).find( '.x-panel.x-panel-default' ).first().css( 'height', '' );
            $( '#kreportviewer' ).find( '.x-panel-body.x-panel-body-default' ).first().css( 'height', '' );
            $( '#kreportviewer' ).find( 'div[id^="KReportViewer-VisualizationContainer"].x-panel-body' ).width( $( window.frameElement ).width() - 50 );
         }, 500 );
      } else {
         $( '#kreportviewer' ).find( '.x-panel.x-panel-default' ).first().css( 'height', '' );
         $( '#kreportviewer' ).find( '.x-panel-body.x-panel-body-default' ).first().css( 'height', '' );
         $( '#kreportviewer' ).find( 'div[id^="KReportViewer-VisualizationContainer"].x-panel-body' ).width( $( window.frameElement ).width() - 50 );
      }
   }
}

function loadNodeData( panel, parentNode, whereConditions ) {
   var nodesHistory = parentNode.data.nodes_history;
   var valuesHistory = parentNode.data.values_history;
   var node;
   if ( nodesHistory == "root" ) {
      node = panel.store.getRoot();
   } else {
      node = panel.store.getNodeById( parentNode.id );
   }

   Ext.Ajax.request( {
      url: "index.php",
      params: {
         module: 'KReports',
         sugar_body_only: 1,
         record: panel.reportId,
         action: 'loadTreeViewNode',
         nodesHistory: Ext.encode( nodesHistory ),
         valuesHistory: Ext.encode( valuesHistory ),
         whereConditions: whereConditions,
      },
      method: 'POST',
      success: function ( response ) {
         var data = Ext.decode( response.responseText );

         if ( nodesHistory == "root" ) {
            panel.setColumns( data.columns );
         }
         if ( window.insideSugarDashlet && !kreporter_dashlet_prepared ) {
            kreporter_dashlet_prepared = true;
            var sum = 0;
            Ext.each( data.columns, function ( column ) {
               if ( !column.hidden ) {
                  sum += column.width;
               }
            } );
            var min_width = $( window ).width() - 19;
            if ( sum < min_width ) {
               sum = min_width;
            }
            $( '#kreportviewer > div.x-panel' ).first().css( 'max-width', sum + 'px' );
            $( '#kreportviewer' ).find( '.x-panel.x-panel-default' ).first().css( 'height', '' );
            $( '#kreportviewer' ).find( '.x-panel-body.x-panel-body-default' ).first().css( 'height', '' );
         }
         parentNode.dataLoaded = true;
         node.appendChild( data.records );
         node.set( 'icon', icons.OPENED );
         if ( nodesHistory == "root" && typeof SpiceCRM.KReporter.onStoreLoad === 'function' ) {
            SpiceCRM.KReporter.onStoreLoad();
         }
         refreshDashletHeight();
      },
      failure: function () {
         parentNode.dataLoaded = false;
         node.set( 'icon', icons.CLOSED );
      }
   } );
}