/**
 * Mint #54881
 ** additional condition in code executed after sorting action
 * Mint #58240
 ** pretty positioning of raport header and footer in Sugar Dashlet
 */
/* * *******************************************************************************
 * This file is part of SpiceCRM FulltextSearch. SpiceCRM FulltextSearch is an enhancement developed
 * by aac services k.s.. All rights are (c) 2016 by aac services k.s.
 *
 * This Version of the SpiceCRM FulltextSearch is licensed software and may only be used in
 * alignment with the License Agreement received with this Software.
 * This Software is copyrighted and may not be further distributed without
 * witten consent of aac services k.s.
 *
 * You can contact us at info@spicecrm.io
 ******************************************************************************* */
Ext.define( "SpiceCRM.KReporter.Viewer.model.reportResult", {
   extend: "Ext.data.Model",
   fields: [ "id" ]
} ),
        Ext.define( "SpiceCRM.KReporter.Viewer.store.reportResults", {
           extend: "Ext.data.Store",
           requires: [ "SpiceCRM.KReporter.Viewer.model.reportResult" ],
           model: "SpiceCRM.KReporter.Viewer.model.reportResult",
           alias: [ "store.KReportViewer.plugins.reportResults" ],
           reportId: void 0,
           filterId: void 0,
           // Mint start #50315
           remoteSort: 1,
           //remoteSort: void 0,
           // Mint end #50315
           dynamicoptions: void 0,
           linkedFields: !1,
           proxy: {
              type: "ajax",
              url: "KREST/KReporter/" + this.reportId + "/presentation",
              method: "GET",
              timeout: 12e4,
              reader: {
                 type: "json",
                 rootProperty: "records",
                 totalProperty: "count"
              }
           },
           listeners: {
              beforeload: function ( a, b, c ) {
                 b._proxy.url = "KREST/KReporter/" + this.reportId + "/presentation",
                         this.filterId && (b._proxy.extraParams = {
                            filter: this.filterId
                         }),
                         b._proxy = SpiceCRM.KReporter.Common.sendParentBeanParams( b._proxy, this.reportId ),
                         _url = SpiceCRM.KReporter.Common.buildDynamicOptionsUrl( this.reportId, "presentation" ),
                         null !== _url && (b._proxy.url = _url)
              },
              load: function ( a ) {
                 // Mint start #58240
                 if ( window.insideSugarDashlet ) {
                    var sum = 0;
                    Ext.each( this.proxy.reader.metaData.gridColumns, function ( column ) {
                       if ( !column.hidden ) {
                          sum += column.width;
                       }
                    } );

                    $( '.x-grid-view' ).last().css( 'overflow', 'unset' );

                    var current_style = $( '.x-grid-header-ct' ).attr( 'style' );
                    $( '.x-grid-header-ct' ).last().attr( 'style', current_style + 'position: absolute !important;' )
                    $( '.x-grid-header-ct' ).last().css( 'left', '0px' );

                    var topDistance = 0;
                    setTimeout( function () {
                       $( '#kreportviewer' ).find( '.x-panel.x-panel-default' ).first().css( 'height', '' );
                       $( '#kreportviewer' ).find( '.x-panel-body.x-panel-body-default' ).first().css( 'height', '' );

                       topDistance = $( '.x-grid-header-ct' ).last().offset().top;
                       var scrollPosition = window.pageYOffset;
                       var windowSize = window.innerHeight;
                       var bodyHeight = document.body.offsetHeight;
                       current_style = $( "div[id^='pagingtoolbar']" ).first().attr( 'style' );
                       $( "div[id^='pagingtoolbar']" ).first().attr( 'style', current_style + 'position: absolute !important' );
                       $( "div[id^='pagingtoolbar']" ).first().css( 'top', 'unset' );
                       $( "div[id^='pagingtoolbar']" ).first().css( 'bottom', Math.max( bodyHeight - (scrollPosition + windowSize), 0 ) );
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
                       var scrollPosition = window.pageYOffset;
                       var windowSize = window.innerHeight;
                       var bodyHeight = document.body.offsetHeight;
                       $( "div[id^='pagingtoolbar']" ).first().css( 'top', 'unset' );
                       $( "div[id^='pagingtoolbar']" ).first().css( {
                          'bottom': Math.max( bodyHeight - (scrollPosition + windowSize), 0 )
                       } );
                    } );

                    var min_width = $( window ).width() - 19;
                    if ( sum < min_width ) {
                       sum = min_width;
                    }
                    this.panel.el.parent().parent().parent().parent().parent().parent().dom.style.maxWidth = sum + 'px';
                 }
                 // Mint end #58240

                 this.proxy.reader.metaData.gridColumns && 0 === this.panel.getColumns().length && this.panel.reconfigure( this.proxy.reader.metaData.gridColumns )
              }
           },
           buildLinkedFields: function () {
              this.linkedFields = {};
              for ( var a = this.proxy.reader.metaData.fields, b = 0; b < a.length; b++ )
                 void 0 === a[b].linkInfo ? this.linkedFields[a[b].name] = null : this.linkedFields[a[b].name] = Ext.JSON.decode( a[b].linkInfo )
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Viewer.controller.plugins.StandardViewerController", {
           extend: "Ext.app.ViewController",
           alias: "controller.KReportViewer.plugins.StandardViewerController",
           whereConfig: {},
           config: {
              listen: {
                 global: {
                    whereClauseUpdated: function ( a, b ) {
                       console.log( "where updated " + Ext.encode( a ) ),
                               this.reloadStore( a, b )
                    },
                    snapshotSelected: function ( a ) {
                       var b = this.view.getStore();
                       b.getProxy().extraParams.snapshotid = a,
                               b.removeAll(),
                               b.load()
                    }
                 }
              }
           },
           showContexMenu: function ( a, b, c, d, e, f ) {
              this.view.up( "panel" ).controller.displayContextMenu( b, e )
           },
           reloadStore: function ( a, b ) {
              var c = this.view.getStore();
              c.getProxy().extraParams.whereConditions = Ext.encode( a ),
                      c.getProxy().extraParams.XDEBUG_SESSION_START = "netbeans-xdebug",
                      c.getProxy().extraParams.sort = Ext.encode( b ),
                      c.removeAll(),
                      c.load()
           },
           renderField: function ( a, b, c, d, e, f, g ) {
              return b && b.column && b.column.fieldrenderer ? Ext.util.Format[b.column.fieldrenderer]( a, b, c, d, e, f, g ) : a
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Viewer.plugins.StandardViewPanel", {
           extend: "Ext.grid.Panel",
           controller: "KReportViewer.plugins.StandardViewerController",
           itemId: "KReporterViewerPresentation",
           store: {
              type: "KReportViewer.plugins.reportResults"
           },
           selType: "rowmodel",
           columns: [ ],
           monitorResize: !0,
           autoHeight: !0,
           minHeight: 400,
           boxMinHeight: 250,
           context: "Viewer",
           reportRecord: void 0,
           remoteSort: !0,
           viewConfig: {
              enableTextSelection: !0
           },
           initComponent: function () {
              this.callParent(),
                      "Viewer" === this.context && this.down( "pagingtoolbar" ).add( {
                 text: languageGetText( "LBL_SAVE_LAYOUT_BUTTON_LABEL" ),
                 handler: function () {
                    var a = [ ]
                            , b = 1;
                    Ext.each( this.up( "grid" ).getColumns(), function ( c ) {
                       a.push( {
                          dataIndex: c.dataIndex,
                          width: c.width,
                          sequence: b,
                          isHidden: c.hidden
                       } ),
                               b++
                    } ),
                            Ext.Ajax.request( {
                               url: "KREST/KReporter/core/savelayout",
                               method: "POST",
                               jsonData: {
                                  record: this.up( "grid" ).reportRecord.get( "id" ),
                                  layout: Ext.encode( a )
                               },
                               success: function ( a, b ) {},
                               failure: function ( a, b ) {
                                  console.log( "server-side failure with status code " + a.status )
                               },
                               scope: this
                            } )
                 }
              } ),
                      this.down( "pagingtoolbar" ).setStore( this.store ),
                      this.store.panel = this,
                      this.store.reportId = this.reportRecord.get( "id" ),
                      this.presentationParams.pluginData && this.presentationParams.pluginData.standardViewProperties && this.presentationParams.pluginData.standardViewProperties.listEntries && (this.store.pageSize = this.presentationParams.pluginData.standardViewProperties.listEntries),
                      this.presentationFilter && (this.store.filterId = this.presentationFilter),
                      this.store.load();
              this.store.on( 'load', function () {
                 if ( typeof SpiceCRM.KReporter.onStoreLoad === 'function' ) {
                    SpiceCRM.KReporter.onStoreLoad();
                 }
              } );
           },
           buildColumns: function ( a ) {
              var b = [ ];
              return Ext.each( a, function ( a ) {
                 b.push( {
                    text: languageGetText( a.name ),
                    readOnly: !0,
                    dataIndex: a.fieldid,
                    width: a.width,
                    sortable: "-" !== a.sort
                 } )
              } ),
                      b
           },
           bbar: {
              xtype: "pagingtoolbar",
              displayInfo: !0
           },
           listeners: {
              itemcontextmenu: "showContexMenu",
              // Mint start #58240
              columnschanged: function () {
                 if ( window.insideSugarDashlet ) {
                    setTimeout( function () {
                       $( '#kreportviewer' ).find( '.x-panel.x-panel-default' ).first().css( 'height', '' );
                       $( '#kreportviewer' ).find( '.x-panel-body.x-panel-body-default' ).first().css( 'height', '' );
                       $( '#kreportviewer' ).find( 'div[id^="KReportViewer-VisualizationContainer"].x-panel-body' ).width( $( window.frameElement ).width() - 50 );
                    }, 50 );
                 }
              },
              // Mint end #58240
              sortchange: function ( a, b ) {
                 _operators = {},
                         Ext.ComponentQuery.query( "#KReportViewqerWherePanel" )
                         // Mint start #54881
                         && Ext.ComponentQuery.query( "#KReportViewqerWherePanel" )[0]
                         // Mint end #54881
                         && (_operators = Ext.ComponentQuery.query( "#KReportViewqerWherePanel" )[0].controller.extractWhereClause()),
                         Ext.globalEvents.fireEvent( "whereClauseUpdated", _operators, [ {
                               direction: b.sortState,
                               property: b.dataIndex
                            } ] )
              }
           }
        } );
