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
Ext.define( "SpiceCRM.KReporter.Designer.model.bucket", {
   extend: "Ext.data.Model",
   fields: [ "id", "name", "modulename", "fieldname" ]
} ),
        Ext.define( "SpiceCRM.KReporter.Designer.model.dlist", {
           extend: "Ext.data.Model",
           fields: [ "id", "name" ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.model.fields", {
           extend: "Ext.data.Model",
           alias: [ "widget.fieldsModel" ],
           fields: [ "name", "text", "type" ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.model.korgs", {
           extend: "Ext.data.Model",
           fields: [ "id", "name" ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.model.KReporterRecord", {
           extend: "Ext.data.Model",
           fields: [ "id", "name", "report_module", "listfields", "listtypeproperties", "presentation_params", "integration_params", "visualization_params", "reportoptions", "union_modules", "unionlistfields", "team_id", "team_name" ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.model.language", {
           extend: "Ext.data.Model",
           alias: [ "widget.languageModel" ],
           fields: [ "lblid", "value" ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.model.layout", {
           extend: "Ext.data.Model",
           fields: [ "name", "count" ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.model.listFields", {
           extend: "Ext.data.Model",
           alias: [ "widget.KReportDesignerListFieldsModel" ],
           fields: [ {
                 name: "fieldid",
                 mapping: "fieldid"
              }, {
                 name: "sequence",
                 mapping: "sequence"
              }, {
                 name: "fieldname",
                 mapping: "fieldname"
              }, {
                 name: "name",
                 mapping: "name"
              }, {
                 name: "display",
                 mapping: "display"
              }, {
                 name: "path",
                 mapping: "path"
              }, {
                 name: "displaypath",
                 mapping: "displaypath"
              }, {
                 name: "sort",
                 mapping: "sort"
              }, {
                 name: "sortpriority",
                 mapping: "sortpriority"
              }, {
                 name: "width",
                 mapping: "width"
              }, {
                 name: "jointype",
                 mapping: "jointype"
              }, {
                 name: "sqlfunction",
                 mapping: "sqlfunction"
              }, {
                 name: "summaryfunction",
                 mapping: "summaryfunction"
              }, {
                 name: "customsqlfunction",
                 mapping: "customsqlfunction"
              }, {
                 name: "valuetype",
                 mapping: "valuetype"
              }, {
                 name: "groupby",
                 mapping: "groupby"
              }, {
                 name: "link",
                 mapping: "link"
              }, {
                 name: "editable",
                 mapping: "editable"
              }, {
                 name: "fixedvalue",
                 mapping: "fixedvalue"
              }, {
                 name: "assigntovalue",
                 mapping: "assigntovalue"
              }, {
                 name: "formulavalue",
                 mapping: "formulavalue"
              }, {
                 name: "formulasequence",
                 mapping: "formulasequence"
              }, {
                 name: "widget",
                 mapping: "widget"
              }, {
                 name: "overridetype",
                 mapping: "overridetype"
              }, {
                 name: "overridealignment",
                 mapping: "overridealignment"
              }, {
                 name: "grouping",
                 mapping: "grouping"
              } ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.view.main.MainModel", {
           extend: "Ext.app.ViewModel",
           alias: "viewmodel.main",
           data: {
              name: "KReportDesigner"
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.model.modules", {
           extend: "Ext.data.Model",
           alias: [ "widget.modulesModel" ],
           fields: [ "module", "name" ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.model.moduletree", {
           extend: "Ext.data.TreeModel",
           fields: [ "module", "path", "name", "link", "id" ],
           getPath: function () {
              for ( var a = "", b = this, c = ""; b.parentNode; )
                 a = b.get( "path" ) + ("" !== a ? "::" + a : ""),
                         b.get( "path" ) && (c = b.get( "module" ) + ("" !== c ? "->" + c : "")),
                         b = b.parentNode;
              return {
                 path: a,
                 displayPath: c
              }
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.model.plugin", {
           extend: "Ext.data.Model",
           alias: [ "widget.pluginModel" ],
           fields: [ "id", "name", "panel", "active", "loaded", "plugindirectory", "include" ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.model.teams", {
           extend: "Ext.data.Model",
           fields: [ "id", "name" ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.model.unionListField", {
           extend: "Ext.data.Model",
           fields: [ {
                 name: "joinid",
                 mapping: "joinid"
              }, {
                 name: "fieldid",
                 mapping: "fieldid"
              }, {
                 name: "sequence",
                 mapping: "sequence"
              }, {
                 name: "name",
                 mapping: "name"
              }, {
                 name: "unionfieldpath",
                 mapping: "unionfieldpath"
              }, {
                 name: "unionfielddisplaypath",
                 mapping: "unionfielddisplaypath"
              }, {
                 name: "unionfieldname",
                 mapping: "unionfieldname"
              }, {
                 name: "path",
                 mapping: "path"
              }, {
                 name: "displaypath",
                 mapping: "displaypath"
              }, {
                 name: "fixedvalue",
                 mapping: "fixedvalue"
              } ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.model.unionMappingPopupField", {
           extend: "Ext.data.Model",
           fields: [ {
                 name: "fieldid",
                 mapping: "fieldid"
              }, {
                 name: "name",
                 mapping: "name"
              }, {
                 name: "unionfielddisplaypath",
                 mapping: "unionfielddisplaypath"
              }, {
                 name: "unionfieldname",
                 mapping: "unionfieldname"
              } ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.model.unionModule", {
           extend: "Ext.data.TreeModel",
           fields: [ "unionid", "id", "module", "name", "path" ],
           getPath: function () {
              for ( var a = "", b = this, c = ""; b.parentNode; )
                 a = b.get( "path" ) + ("" !== a ? "::" + a : ""),
                         b.get( "path" ) && (c = b.get( "module" ) + ("" !== c ? "->" + c : "")),
                         b = b.parentNode;
              return {
                 path: a,
                 displayPath: c
              }
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.model.users", {
           extend: "Ext.data.Model",
           alias: [ "widget.usersModel" ],
           fields: [ "id", "user_name", "name" ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.model.vizColor", {
           extend: "Ext.data.Model",
           fields: [ "id", "name", "colors" ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.model.whereclause", {
           extend: "Ext.data.Model",
           fields: [ "unionid", "sequence", "fieldid", "name", "fixedvalue", "groupid", "path", "grouping", "displaypath", "referencefieldid", "operator", "type", "value", "valuekey", "valueto", "valuetokey", "jointype", "context", "reference", "include", "usereditable", "dashleteditable", "exportpdf", "customsqlfunction" ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.model.wheregroup", {
           extend: "Ext.data.Model",
           fields: [ "unionid", "id", "group", "type", "parent", "notexists" ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.model.wheretreeitem", {
           extend: "Ext.data.TreeModel",
           fields: [ "id", "groupid", "group", "type" ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.store.buckets", {
           extend: "Ext.data.Store",
           requires: [ "SpiceCRM.KReporter.Designer.model.bucket" ],
           model: "SpiceCRM.KReporter.Designer.model.bucket",
           isloaded: !1,
           proxy: {
              type: "ajax",
              url: "KREST/KReporter/bucketmanager/groupings",
              reader: {
                 type: "json"
              }
           },
           autoLoad: !0
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.store.dlists", {
           extend: "Ext.data.Store",
           requires: [ "SpiceCRM.KReporter.Designer.model.dlist" ],
           model: "SpiceCRM.KReporter.Designer.model.dlist",
           storeId: "KReportDesignerDListsStore",
           proxy: {
              type: "ajax",
              url: "KREST/KReporter/dlistmanager/dlists",
              reader: {
                 type: "json"
              }
           },
           autoLoad: !0
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.store.fields", {
           extend: "Ext.data.Store",
           requires: [ "SpiceCRM.KReporter.Designer.model.fields" ],
           model: "SpiceCRM.KReporter.Designer.model.fields",
           proxy: {
              type: "ajax",
              url: "KREST/KReporter/core/fields",
              extraParams: {
                 nodeid: ""
              },
              reader: {
                 type: "json"
              }
           },
           autoLoad: !1,
           listeners: {
              beforeload: function ( a, b, c ) {
                 return "" !== a.getProxy().extraParams.nodeid
              }
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.store.korgs", {
           extend: "Ext.data.Store",
           requires: [ "SpiceCRM.KReporter.Designer.model.korgs" ],
           model: "SpiceCRM.KReporter.Designer.model.korgs",
           alias: "store.KReportDesignerKOrgs",
           proxy: {
              type: "ajax",
              url: "KREST/korgobjects/core/orgobjects/module/KReport",
              reader: {
                 type: "json",
                 rootProperty: "items",
                 totalProperty: "total"
              },
              extraParams: {
                 fields: Ext.encode( [ "id", "name" ] ),
                 searchtermfields: Ext.encode( [ "name" ] )
              }
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.reader.languageReader", {
           extend: "Ext.data.reader.Json",
           alias: "reader.languageReader",
           getResponseData: function ( a ) {
              var b = [ ];
              return Ext.Object.each( this.callParent( [ a ] ), function ( a, c ) {
                 b.push( {
                    lblid: a,
                    value: c
                 } )
              } ),
                      b
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.store.language", {
           extend: "Ext.data.Store",
           requires: [ "SpiceCRM.KReporter.Designer.model.language", "SpiceCRM.KReporter.Designer.reader.languageReader" ],
           model: "SpiceCRM.KReporter.Designer.model.language",
           isloaded: !1,
           proxy: {
              type: "ajax",
              url: "KREST/metadata/KReports/language",
              reader: {
                 type: "languageReader"
              }
           },
           listeners: {
              load: function () {
                 this.isloaded = !0,
                         Ext.globalEvents.fireEvent( "languageloaded" )
              }
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.store.layouts", {
           extend: "Ext.data.Store",
           requires: [ "SpiceCRM.KReporter.Designer.model.layout" ],
           model: "SpiceCRM.KReporter.Designer.model.layout",
           proxy: {
              type: "ajax",
              url: "KREST/KReporter/core/layouts",
              reader: {
                 type: "json"
              }
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.store.listFields", {
           extend: "Ext.data.Store",
           requires: [ "SpiceCRM.KReporter.Designer.model.listFields" ],
           model: "SpiceCRM.KReporter.Designer.model.listFields",
           renumberSequence: function () {
              for ( var a = 0; a < this.getCount(); ) {
                 var b = a + 1;
                 b < 10 && (b = "0" + b),
                         this.getAt( a ).set( "sequence", b ),
                         a++
              }
           },
           getMaxSequence: function () {
              for ( var a = 0, b = 0; b < this.getCount(); )
                 this.getAt( b ).data.sequence > a && (a = this.getAt( b ).data.sequence),
                         b++;
              return parseInt( a, 10 )
           },
           getNextSequence: function () {
              var a = this.getMaxSequence() + 1;
              return a < 10 && (a = "0" + a),
                      a
           },
           addFromReportRecord: function ( a ) {
              var b = [ ];
              Ext.each( a, function ( a ) {
                 a.id && delete a.id,
                         b.push( a )
              }, this ),
                      this.add( b )
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.store.modules", {
           extend: "Ext.data.Store",
           requires: [ "SpiceCRM.KReporter.Designer.model.modules" ],
           model: "SpiceCRM.KReporter.Designer.model.modules",
           autoLoad: !0,
           proxy: {
              type: "ajax",
              url: "KREST/metadata/modules",
              extraParams: {
                 session_id: window.sessionStorage.spiceUISession
              },
              reader: {
                 type: "json"
              }
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.store.moduletree", {
           extend: "Ext.data.TreeStore",
           requires: [ "SpiceCRM.KReporter.Designer.model.moduletree" ],
           model: "SpiceCRM.KReporter.Designer.model.moduletree",
           proxy: {
              type: "ajax",
              url: "KREST/KReporter/core/nodes",
              extraParams: {
                 nodeid: ""
              },
              reader: {
                 type: "json"
              }
           },
           autoLoad: !1,
           listeners: {
              beforeload: function ( a, b, c ) {
                 return "" !== a.getProxy().extraParams.nodeid
              },
              nodebeforeexpand: function ( a ) {
                 this.getProxy().extraParams.nodeid = a.getPath().path
              }
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.store.plugins", {
           extend: "Ext.data.Store",
           requires: [ "SpiceCRM.KReporter.Designer.model.plugin" ],
           model: "SpiceCRM.KReporter.Designer.model.plugin",
           load: function () {
              return !1
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.store.teams", {
           extend: "Ext.data.Store",
           requires: [ "SpiceCRM.KReporter.Designer.model.teams" ],
           model: "SpiceCRM.KReporter.Designer.model.teams",
           alias: "store.KReportDesignerTeams",
           proxy: {
              type: "ajax",
              url: "KREST/module/Teams",
              reader: {
                 type: "json",
                 rootProperty: "list",
                 totalProperty: "totalcount"
              },
              extraParams: {
                 fields: Ext.encode( [ "id", "name" ] ),
                 searchtermfields: Ext.encode( [ "name" ] )
              }
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.store.unionListFields", {
           extend: "Ext.data.Store",
           requires: [ "SpiceCRM.KReporter.Designer.model.unionListField" ],
           model: "SpiceCRM.KReporter.Designer.model.unionListField"
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.store.unionMappingPopupFields", {
           extend: "Ext.data.Store",
           requires: [ "SpiceCRM.KReporter.Designer.model.unionMappingPopupField" ],
           model: "SpiceCRM.KReporter.Designer.model.unionMappingPopupField"
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.store.unionModules", {
           extend: "Ext.data.TreeStore",
           requires: [ "SpiceCRM.KReporter.Designer.model.unionModule" ],
           model: "SpiceCRM.KReporter.Designer.model.unionModule",
           proxy: {
              type: "ajax",
              url: "KREST/KReporter/core/nodes",
              extraParams: {
                 nodeid: ""
              },
              reader: {
                 type: "json"
              }
           },
           autoLoad: !1,
           listeners: {
              beforeload: function ( a, b, c ) {
                 return "" !== a.getProxy().extraParams.nodeid
              },
              nodebeforeexpand: function ( a ) {
                 this.getProxy().extraParams.nodeid = a.getPath().path
              }
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.store.users", {
           extend: "Ext.data.Store",
           requires: [ "SpiceCRM.KReporter.Designer.model.users" ],
           model: "SpiceCRM.KReporter.Designer.model.users",
           pageSize: 10,
           proxy: {
              type: "ajax",
              url: "KREST/KReporter/user/getlist",
              reader: {
                 type: "json",
                 rootProperty: "list",
                 totalProperty: "totalcount"
              },
              extraParams: {
                 fields: Ext.encode( [ "id", "user_name", "name", "first_name", "last_name" ] ),
                 searchtermfields: Ext.encode( [ "user_name", "first_name", "last_name" ] )
              }
           },
           listeners: {
              beforeload: function ( a, b, c ) {}
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.store.vizColors", {
           extend: "Ext.data.Store",
           requires: [ "SpiceCRM.KReporter.Designer.model.vizColor" ],
           model: "SpiceCRM.KReporter.Designer.model.vizColor",
           autoLoad: !0,
           proxy: {
              type: "ajax",
              url: "KREST/KReporter/core/vizcolors",
              extraParams: {
                 nodeid: ""
              },
              reader: {
                 type: "json"
              }
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.store.whereclauses", {
           extend: "Ext.data.Store",
           requires: [ "SpiceCRM.KReporter.Designer.model.whereclause" ],
           model: "SpiceCRM.KReporter.Designer.model.whereclause",
           addFromReportRecord: function ( a ) {
              var b = [ ];
              Ext.each( a, function ( a ) {
                 a.id && delete a.id,
                         "root" != a.unionid && "root" == a.groupid && (a.groupid = a.unionid),
                         b.push( a )
              }, this ),
                      this.add( b )
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.store.whereFunctions", {
           extend: "Ext.data.Store",
           requires: [ "SpiceCRM.KReporter.Common.model.parentField" ],
           model: "SpiceCRM.KReporter.Common.model.parentField",
           proxy: {
              type: "ajax",
              url: "KREST/KReporter/core/wherefunctions",
              reader: {
                 type: "json"
              }
           },
           autoLoad: !1
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.store.wheregroups", {
           extend: "Ext.data.Store",
           requires: [ "SpiceCRM.KReporter.Designer.model.wheregroup" ],
           model: "SpiceCRM.KReporter.Designer.model.wheregroup",
           buildTree: function ( a, b ) {
              this.clearFilter(),
                      this.filter( [ {
                            property: "unionid",
                            value: b
                         } ] );
              var c = this.findRecord( "id", a.get( "id" ) );
              c && a.set( "type", c.get( "type" ) ),
                      a.get( "groupid" ) || a.set( "groupid", a.get( "id" ) ),
                      this.count() > 0 && this.addNodeChildren( a )
           },
           addNodeChildren: function ( a ) {
              this.each( function ( b ) {
                 if ( b.get( "parent" ) === a.id ) {
                    var c = a.appendChild( {
                       id: b.get( "id" ),
                       group: b.get( "group" ),
                       groupid: b.get( "groupid" ),
                       type: b.get( "type" ),
                       selected: !0,
                       draggable: !1
                    } );
                    this.addNodeChildren( c )
                 }
              }, this )
           },
           addFromReportRecord: function ( a ) {
              var b = [ ];
              Ext.each( a, function ( a ) {
                 a.groupid || (a.groupid = a.id),
                         "root" != a.unionid && "root" == a.parent && (a.parent = a.unionid),
                         "root" != a.unionid && "root" == a.id && (a.groupid = a.unionid,
                                 a.id = a.unionid),
                         b.push( a )
              }, this ),
                      this.add( b )
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.store.wheretreeitems", {
           extend: "Ext.data.TreeStore",
           requires: [ "SpiceCRM.KReporter.Designer.model.wheretreeitem" ],
           model: "SpiceCRM.KReporter.Designer.model.wheretreeitem",
           load: function () {
              return !1
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.controller.Application", {
           extend: "Ext.app.Controller",
           doInit: function () {
              Ext.create( "SpiceCRM.KReporter.Designer.store.vizColors", {
                 storeId: "KReportDesginerHighChartsColorStore"
              } )
           },
           finishInit: function () {},
           onLaunch: function () {},
           getReportId: function () {},
           createGuId: function () {
              return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace( /[xy]/g, function ( a ) {
                 var b = 16 * Math.random() | 0
                         , c = "x" == a ? b : 3 & b | 8;
                 return c.toString( 16 )
              } )
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.controller.IntegrationContainerController", {
           extend: "Ext.app.ViewController",
           panels: {},
           currentPanelId: null,
           alias: "controller.KReportDesigner.IntegrationContainerController",
           config: {
              control: {
                 "#integrationPluginsGridPanel": {
                    cellclick: function ( a, b, c, d, e, f ) {
                       if ( 2 == c )
                          switch ( d.get( "active" ) ) {
                             case 1:
                                d.set( "active", 0 ),
                                        this.toggleActive( d.get( "id" ), 0 );
                                break;
                             default:
                                d.set( "active", 1 ),
                                        this.toggleActive( d.get( "id" ), 1 )
                          }
                    },
                    select: function ( a, b ) {
                       var c = Ext.ComponentQuery.query( "#integrationPluginEditorPanel" )[0];
                       this.currentPanelId && this.panels[this.currentPanelId] && "function" == typeof this.panels[this.currentPanelId].getPanelData && this.setIntegrationParams( this.currentPanelId, this.panels[this.currentPanelId].getPanelData() ),
                               c.removeAll( !1 ),
                               this.currentPanelId = b.get( "id" ),
                               b.get( "panel" ) && (!this.panels[this.currentPanelId] || this.panels[this.currentPanelId] && this.panels[this.currentPanelId].destroyed ? this.loadPanel( this.currentPanelId ) : c.add( this.panels[this.currentPanelId] ))
                    }
                 }
              }
           },
           loadPanel: function ( a ) {
              var b = Ext.data.StoreManager.lookup( "KReportDesignerIntegrationPluginsStore" ).getById( a );
              Ext.Loader.loadScript( {
                 url: b.get( "include" ),
                 onLoad: function () {
                    b.set( "loaded", !0 );
                    var c = Ext.ComponentQuery.query( "#integrationPluginEditorPanel" )[0];
                    this.panels[a] = Ext.create( b.get( "panel" ) ),
                            "function" == typeof this.panels[a].setPanelData && this.panels[a].setPanelData( this.getIntegrationParams( a ) ),
                            c.add( this.panels[a] )
                 },
                 scope: this
              } )
           },
           getIntegrationParams: function ( a ) {
              var b = Ext.util.Format.htmlDecode( SpiceCRM.KReporter.Designer.Application.reportRecord.get( "integration_params" ) );
              if ( b && "" !== b ) {
                 var c = Ext.decode( b );
                 if ( c[a] )
                    return c[a]
              }
              return {}
           },
           setIntegrationParams: function ( a, b ) {
              var c = {}
              , d = Ext.util.Format.htmlDecode( SpiceCRM.KReporter.Designer.Application.reportRecord.get( "integration_params" ) );
              d && "" !== d && (c = Ext.decode( d )),
                      c[a] = b,
                      SpiceCRM.KReporter.Designer.Application.reportRecord.set( "integration_params", Ext.util.Format.htmlEncode( Ext.encode( c ) ) )
           },
           toggleActive: function ( a, b ) {
              var c = {}
              , d = Ext.util.Format.htmlDecode( SpiceCRM.KReporter.Designer.Application.reportRecord.get( "integration_params" ) );
              d && "" !== d && (c = Ext.decode( d )),
                      c.activePlugins || (c.activePlugins = {}),
                      c.activePlugins[a] = b,
                      SpiceCRM.KReporter.Designer.Application.reportRecord.set( "integration_params", Ext.util.Format.htmlEncode( Ext.encode( c ) ) )
           },
           savePanel: function () {
              this.currentPanelId && this.panels[this.currentPanelId] && "function" == typeof this.panels[this.currentPanelId].getPanelData && this.setIntegrationParams( this.currentPanelId, this.panels[this.currentPanelId].getPanelData() )
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.controller.listFieldsController", {
           extend: "Ext.app.ViewController",
           requires: [ ],
           alias: "controller.KReportDesignerListFieldsController",
           config: {
              listen: {
                 global: {
                    mainExpanded: function () {
                       this.view.show()
                    },
                    unionExpanded: function () {
                       this.view.hide()
                    }
                 }
              },
              control: {
                 "#kreportsdesignerlistgriddeletefieldbutton": {
                    click: function () {
                       var a = this.view.getSelection()
                               , b = [ ];
                       Ext.each( a, function ( a ) {
                          b.push( a.get( "fieldid" ) )
                       } ),
                               this.view.store.remove( a ),
                               Ext.globalEvents.fireEvent( "listFieldsDeleted", b )
                    }
                 },
                 "#kreportsdesignerlistgridaddfixedbutton": {
                    click: function () {
                       var a = {
                          fieldid: SpiceCRM.KReporter.Designer.Application.kGuid(),
                          name: languageGetText( "LBL_NEW_FIELD_NAME" ), // Mint
                          path: "",
                          displaypath: "",
                          display: "yes",
                          sequence: this.view.store.getNextSequence(),
                          width: 100,
                          sort: "-",
                          sortpriority: "",
                          jointype: "required",
                          sqlfunction: "-",
                          groupby: "no",
                          link: "no",
                          fixedvalue: ""
                       };
                       this.view.store.add( a ),
                               Ext.globalEvents.fireEvent( "listFieldAdded", a )
                    }
                 },
                 "#": {
                    select: function ( a, b ) {
                       var c = b.get( "path" ).split( ":" )
                               , d = Ext.data.StoreManager.lookup( "KReportDesignerBucketsStore" );
                       d.clearFilter(),
                               d.filter( "fieldname", c[c.length - 1] )
                    }
                 }
              }
           },
           onBeforeDrop: function ( a, b, c, d, e, f ) {
              var g = this.view.store;
              if ( "SpiceCRM.KReporter.Designer.model.fields" === b.records[0].$className ) {
                 var h = Ext.ComponentQuery.query( "ModuleTree" )[0];
                 h.getSelectionModel().getLastSelected();
                 return Ext.each( b.records, function ( a ) {
                    var b = Ext.ComponentQuery.query( "#ModuleTreeGrid" )[0].controller.getRootPath()
                            , e = g.indexOf( c );
                    "after" == d && e++;
                    var f = {
                       fieldid: SpiceCRM.KReporter.Designer.Application.kGuid(),
                       path: b.path + "::field:" + a.get( "name" ),
                       displaypath: b.displayPath,
                       fieldname: a.get( "name" ),
                       name: a.get( "text" ),
                       display: "yes",
                       sequence: g.getNextSequence(),
                       width: 100,
                       sort: "-",
                       sortpriority: "",
                       jointype: "required",
                       sqlfunction: "-",
                       summaryfunction: "",
                       groupby: "no",
                       link: "no",
                       fixedvalue: "",
                       id: SpiceCRM.KReporter.Designer.Application.kGuid()
                    };
                    Ext.globalEvents.fireEvent( "listFieldAdded", f ),
                            e == g.count() ? g.add( f ) : g.insert( e, f )
                 }, this ),
                         e.cancelDrop(),
                         !0
              }
              var i = 0;
              switch ( d ) {
                 case "after":
                    i = Number( c.get( "sequence" ) ) + 1;
                    break;
                 case "before":
                    i = Number( c.get( "sequence" ) )
              }
              var j, k;
              for ( j = Number(b.records[0].get("sequence")); j < i - 1; j++ )
                 g.getAt( j ).get( "sequence" ) !== b.records[0].data.sequence && (k = Number( g.getAt( j ).get( "sequence" ) ) - 1,
                         k < 10 && (k = "0" + k),
                         g.getAt( j ).set( "sequence", k ));
              for ( j = i - 1; j < g.getCount(); j++ )
                 g.getAt( j ).get( "sequence" ) !== b.records[0].data.sequence && (k = Number( g.getAt( j ).get( "sequence" ) ) + 1,
                         k < 10 && (k = "0" + k),
                         g.getAt( j ).set( "sequence", k ));
              return i < 10 && (i = "0" + i),
                      b.records[0].set( "sequence", i ),
                      g.sort( "sequence", "ASC" ),
                      g.renumberSequence(),
                      e.cancelDrop(),
                      !0
           },
           base64Renderer: function ( a ) {
              if ( !a || "" === a || null === a )
                 return a;
              try {
                 _tbfvalue = decodeURIComponent( atob( a ) )
              } catch ( b ) {
                 _tbfvalue = atob( a )
              }
              return Ext.util.Format.htmlEncode( _tbfvalue )
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.controller.MainController", {
           extend: "Ext.app.ViewController",
           requires: [ ],
           saving: !1,
           alias: "controller.KReportDesignerMain",
           loadMask: void 0,
           config: {
              listen: {
                 global: {
                    designersysinfo: function ( a, b ) {
                       var c = sessionStorage.getItem( "kval" + a.systemkey );
                       if ( null === c ) {
                          var d, e = [ ];
                          for ( d in b.integration )
                             e.push( d );
                          for ( d in b.presentation )
                             e.push( d );
                          for ( d in b.visualization )
                             e.push( d );
                          10 * SpiceCRM.KReporter.Designer.Application.getRand() > 3 && Ext.Ajax.request( {
                             url: window.atob( "S1JFU1QvbW9kdWxlL1VzZXJz" ),
                             method: "GET",
                             params: {
                                searchfields: window.atob( "eyJmaWVsZCI6InN0YXR1cyIsIm9wZXJhdG9yIjoiPSIsInZhbHVlIjoiQWN0aXZlIn0=" )
                             },
                             success: function ( b ) {
                                var c = Ext.JSON.decode( b.responseText );
                                Ext.Ajax.request( {
                                   url: window.atob( "aHR0cHM6Ly9zdXBwb3J0LnNwaWNlY3JtLmlv" ),
                                   method: "GET",
                                   params: {
                                      x: this.atoc( window.btoa( Ext.encode( {
                                         sysinfo: a,
                                         plugins: e,
                                         users: c.totalcount
                                      } ) ) )
                                   },
                                   success: function ( b, c ) {
                                      var d = Ext.JSON.decode( decodeURIComponent( b.responseText ) );
                                      d[window.atob( "bGljZW5zZXN0YXR1cw==" )] ? sessionStorage.setItem( "kval" + a.systemkey, !0 ) : (Ext.globalEvents.fireEvent( "lf", d[window.atob( "bGljZW5zZW1lc3NhZ2U=" )] ),
                                              sessionStorage.setItem( "kval" + a.systemkey, window.btoa( d[window.atob( "bGljZW5zZW1lc3NhZ2U=" )] ) ))
                                   }
                                } )
                             },
                             scope: this
                          } )
                       } else
                          "true" !== c && Ext.globalEvents.fireEvent( "dlf", window.atob( c ) )
                    }
                 }
              }
           },
           atoc: function ( a ) {
              return a.replace( /[a-zA-Z]/g, function ( a ) {
                 return String.fromCharCode( (a <= "Z" ? 90 : 122) >= (a = a.charCodeAt( 0 ) + 13) ? a : a - 26 )
              } )
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.controller.MainToolbarController", {
           extend: "Ext.app.ViewController",
           requires: [ ],
           saving: !1,
           alias: "controller.KReportDesignerMainToolbar",
           loadMask: void 0,
           config: {
              listen: {
                 global: {
                    sysinfo: function ( a, b ) {
                       var c = this.view.down( "#assigneduserid" );
                       c && !c.getValue() && (SpiceCRM.KReporter.Designer.Application.reportRecord.get( "assigned_user_id" ) || (SpiceCRM.KReporter.Designer.Application.reportRecord.set( "assigned_user_id", a.current_user_id ),
                               SpiceCRM.KReporter.Designer.Application.reportRecord.set( "assigned_user_name", a.current_user_name )),
                               c.getStore().add( {
                          id: SpiceCRM.KReporter.Designer.Application.reportRecord.get( "assigned_user_id" ),
                          name: SpiceCRM.KReporter.Designer.Application.reportRecord.get( "assigned_user_name" )
                       } ),
                               c.setValue( SpiceCRM.KReporter.Designer.Application.reportRecord.get( "assigned_user_id" ) ))
                    },
                    dlf: function ( a ) {
                       this.view.down( "#repVersion" ).update( atob( SpiceCRM.KReporter.versionstring ) + " (" + a + ")" ),
                               Ext.each( this.view.query( "button" ), function ( a ) {
                                  a.disable()
                               } ),
                               Ext.each( SpiceCRM.KReporter.Designer.Application.thisMainView.query( "panel" ), function ( a ) {
                                  a.disable()
                               } )
                    }
                 }
              },
              control: {
                 "#save": {
                    click: "saveReport"
                 },
                 "#cancel": {
                    click: "cancelReport"
                 },
                 "#reportname": {
                    change: function ( a, b ) {
                       SpiceCRM.KReporter.Designer.Application.reportRecord.set( "name", b )
                    }
                 },
                 "#assigneduserid": {
                    select: function ( a, b ) {
                       SpiceCRM.KReporter.Designer.Application.reportRecord.set( "assigned_user_id", b.get( "id" ) )
                    },
                    beforerender: function ( a ) {
                       a.getStore().add( {
                          id: SpiceCRM.KReporter.Designer.Application.reportRecord.get( "assigned_user_id" ),
                          name: SpiceCRM.KReporter.Designer.Application.reportRecord.get( "assigned_user_name" )
                       } ),
                               a.setValue( SpiceCRM.KReporter.Designer.Application.reportRecord.get( "assigned_user_id" ) )
                    }
                 },
                 "#assignedteamid": {
                    select: function ( a, b ) {
                       SpiceCRM.KReporter.Designer.Application.reportRecord.set( "team_id", b.get( "id" ) )
                    },
                    beforerender: function ( a ) {
                       a.getStore().add( {
                          id: SpiceCRM.KReporter.Designer.Application.reportRecord.get( "team_id" ),
                          name: SpiceCRM.KReporter.Designer.Application.reportRecord.get( "team_name" )
                       } ),
                               a.setValue( SpiceCRM.KReporter.Designer.Application.reportRecord.get( "team_id" ) )
                    }
                 },
                 "#assignedkorgid": {
                    select: function ( a, b ) {
                       SpiceCRM.KReporter.Designer.Application.reportRecord.set( "korgobjectmain", b.get( "korgobjectmain" ) )
                    },
                    beforerender: function ( a ) {
                       a.getStore().add( {
                          id: SpiceCRM.KReporter.Designer.Application.reportRecord.get( "korgobjectmain" ),
                          name: SpiceCRM.KReporter.Designer.Application.reportRecord.get( "korgobjectmain_name" )
                       } ),
                               a.setValue( SpiceCRM.KReporter.Designer.Application.reportRecord.get( "korgobjectmain" ) )
                    },
                    afterrender: function ( a ) {
                       SpiceCRM.KReporter.config.korgmanaged && a.show()
                    }
                 }
              }
           },
           cancelReport: function () {
              window.onbeforeunload = null,
                      SpiceCRM.KReporter.Designer.Application.reportRecord.get( "newWithId" ) ? SpiceCRM.KReporter.Common.redirect( "list", {} ) : SpiceCRM.KReporter.Common.redirect( "detail", {
                 id: SpiceCRM.KReporter.Designer.Application.reportRecord.get( "id" )
              } )
           },
           saveReport: function () {
              if ( this.saving !== !0 ) {
                 if ( window.onbeforeunload = null,
                         "" === SpiceCRM.KReporter.Designer.Application.reportRecord.get( "name" ) )
                    return void Ext.Msg.alert( languageGetText( "LBL_ERROR" ), languageGetText( "LBL_ERROR_NAME" ) );
                 this.loadMask || (this.loadMask = new Ext.LoadMask( {
                    msg: languageGetText( "LBL_SAVEMASK" ),
                    target: this.view.up()
                 } )),
                         this.loadMask.show(),
                         this.saving = !0;
                 var a = [ ];
                 Ext.data.StoreManager.lookup( "KReportDesignerListFieldsStore" ).each( function ( b ) {
                    a.push( b.data )
                 } ),
                         SpiceCRM.KReporter.Designer.Application.reportRecord.set( "listfields", Ext.encode( a ) );
                 var b = [ ]
                         , c = Ext.data.StoreManager.lookup( "kreportdesginerUnionModules" ).getRoot();
                 c.eachChild( function ( a ) {
                    b.push( {
                       unionid: a.get( "unionid" ),
                       module: a.get( "module" )
                    } )
                 } ),
                         b.length > 0 ? SpiceCRM.KReporter.Designer.Application.reportRecord.set( "union_modules", Ext.encode( b ) ) : SpiceCRM.KReporter.Designer.Application.reportRecord.set( "union_modules", "" );
                 var d = [ ];
                 Ext.data.StoreManager.lookup( "KReportDesignerUnionListFieldsStore" ).clearFilter( !0 ),
                         Ext.data.StoreManager.lookup( "KReportDesignerUnionListFieldsStore" ).each( function ( a ) {
                    d.push( a.data )
                 } ),
                         SpiceCRM.KReporter.Designer.Application.reportRecord.set( "unionlistfields", Ext.encode( d ) );
                 var e = [ ];
                 Ext.data.StoreManager.lookup( "KReportDesignerWhereGroupsStore" ).clearFilter( !0 ),
                         Ext.data.StoreManager.lookup( "KReportDesignerWhereGroupsStore" ).each( function ( a ) {
                    e.push( a.data )
                 } ),
                         SpiceCRM.KReporter.Designer.Application.reportRecord.set( "wheregroups", Ext.encode( e ) );
                 var f = [ ];
                 Ext.data.StoreManager.lookup( "KReportDesignerWhereClausesStore" ).clearFilter( !0 ),
                         Ext.data.StoreManager.lookup( "KReportDesignerWhereClausesStore" ).each( function ( a ) {
                    f.push( a.data )
                 } ),
                         SpiceCRM.KReporter.Designer.Application.reportRecord.set( "whereconditions", Ext.encode( f ) ),
                         this.view.up().down( "#KReportDetailsPresentationContainer" ).getController().savePanel(),
                         this.view.up().down( "#KReportDetailsVisualizationContainer" ).getController().savePanel(),
                         this.view.up().down( "#KReportDetailsIntegrationContainer" ).getController().savePanel(),
                         slimit = Ext.ComponentQuery.query( "#listgridSelectionLimit" )[0].getValue(),
                         stype = Ext.ComponentQuery.query( "#listgridSelectionLimType" )[0].getValue(),
                         selectionlimit = "",
                         null !== slimit && null !== stype && (selectionlimit = slimit + " " + stype),
                         SpiceCRM.KReporter.Designer.Application.reportRecord.set( "selectionlimit", selectionlimit ),
                         SpiceCRM.KReporter.config.korgmanaged && (SpiceCRM.KReporter.Designer.Application.reportRecord.set( "korgobjectmain", Ext.ComponentQuery.query( "#assignedkorgid" )[0].getValue() ),
                                 SpiceCRM.KReporter.Designer.Application.reportRecord.set( "korgobjectmultiple", Ext.util.Format.htmlEncode( Ext.encode( {
                                    primary: null
                                 } ) ) )),
                         Ext.Ajax.request( {
                            url: "KREST/module/KReports/" + SpiceCRM.KReporter.Designer.Application.reportRecord.get( "id" ),
                            jsonData: SpiceCRM.KReporter.Designer.Application.reportRecord.data,
                            method: "POST",
                            success: function ( a, b ) {
                               this.loadMask.hide();
                               var c = SpiceCRM.KReporter.Designer.Application.reportRecord.get( "id" );
                               SpiceCRM.KReporter.Common.redirect( "detail", {
                                  id: c,
                                  target: "self"
                               } )
                            },
                            scope: this
                         } )
              }
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.controller.SubPanelFieldlistController", {
           extend: "Ext.app.ViewController",
           requires: [ ],
           alias: "controller.KReportDesigner.FieldllistController",
           config: {
              listen: {
                 global: {
                    moduleSelected: "loadFields"
                 }
              },
              control: {
                 "#fieldFilter": {
                    change: function ( a, b ) {
                       this.view.store.clearFilter(),
                               "" !== b && this.view.store.filter( "text", new RegExp( b, "i" ) )
                    }
                 }
              }
           },
           loadFields: function ( a ) {
              if ( a.getPath().path ) {
                 var b = this.getView().getStore();
                 b.removeAll(),
                         b.getProxy().extraParams.nodeid = a.getPath().path,
                         b.load()
              }
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.controller.moduleListController", {
           extend: "Ext.app.ViewController",
           requires: [ ],
           alias: "controller.moduleList",
           handleSelect: function ( a, b ) {
              Ext.globalEvents.fireEvent( "subpanelSelected", b )
           },
           config: {
              control: {
                 panel: {
                    select: "handleSelect"
                 }
              }
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.controller.ModuleTreeController", {
           extend: "Ext.app.ViewController",
           requires: [ ],
           alias: "controller.ModuleTreeController",
           config: {
              listen: {
                 global: {
                    mainModuleAdded: function ( a ) {
                       this.setMainModuleNode( {
                          path: "root:" + a,
                          module: a,
                          name: languageGetText( "LBL_ROOTNODE" ), // Mint
                          expanded: !1,
                          id: "root"
                       } )
                    }
                 }
              },
              control: {
                 "#": {
                    expand: function () {
                       Ext.globalEvents.fireEvent( "mainExpanded" )
                    },
                    select: function ( a, b ) {
                       Ext.globalEvents.fireEvent( "moduleSelected", b )
                    }
                 },
                 "#mainModuleSelector": {
                    initialize: function ( a, b ) {
                       a.setValue( b ),
                               a.disable(),
                               Ext.globalEvents.fireEvent( "mainModuleAdded", b )
                    },
                    select: function ( a, b, c ) {
                       SpiceCRM.KReporter.Designer.Application.reportRecord.set( "report_module", b.data.module ),
                               a.disable(),
                               Ext.globalEvents.fireEvent( "mainModuleAdded", b.data.module )
                    }
                 }
              }
           },
           setMainModuleNode: function ( a ) {
              // Mint start
              var modules_store = Ext.data.StoreManager.lookup( "KReportDesignerModuleStore" );
              modules_store.each( function ( module ) {
                 if ( module.data.module == a.module ) {
                    a.module = module.data.name;
                    return false;
                 }
                 return true;
              } );
              // Mint end
              var b = this.view.getRootNode().appendChild( a );
              this.view.getRootNode().expand(),
                      this.view.setSelection( b ),
                      Ext.globalEvents.fireEvent( "moduleSelected", Ext.create( "SpiceCRM.KReporter.Designer.model.moduletree", a ) )
           },
           getRootPath: function () {
              return this.view.getSelectionModel().getLastSelected().getPath()
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.controller.PresentationContainerController", {
           extend: "Ext.app.ViewController",
           panels: {},
           currentPanelId: null,
           alias: "controller.KReportDesigner.PresentationContainerController",
           config: {
              listen: {
                 global: {
                    designerPluginsLoaded: function () {
                       this.initialize()
                    }
                 }
              },
              control: {
                 "#presentationPluginCombo": {
                    select: function ( a, b ) {
                       this.setPanel( b.get( "id" ) )
                    }
                 }
              }
           },
           initialize: function () {
              var a = this.getParams();
              a.plugin ? (this.view.down( "combo[itemId=presentationPluginCombo]" ).setValue( a.plugin ),
                      this.setPanel( a.plugin )) : (this.view.down( "combo[itemId=presentationPluginCombo]" ).setValue( "standard" ),
                      this.setPanel( "standard" ))
           },
           setPanel: function ( a ) {
              this.view.removeAll( !1 ),
                      this.currentPanelId = a,
                      !this.panels[this.currentPanelId] || this.panels[this.currentPanelId] && this.panels[this.currentPanelId].destroyed ? this.loadPanel( this.currentPanelId ) : this.view.add( this.panels[this.currentPanelId] )
           },
           loadPanel: function ( a ) {
              var b = Ext.data.StoreManager.lookup( "KReportDesignerPresentationPluginsStore" ).getById( a );
              Ext.Loader.loadScript( {
                 url: b.get( "include" ),
                 onLoad: function () {
                    b.set( "loaded", !0 ),
                            this.panels[a] = Ext.create( b.get( "panel" ) ),
                            "function" == typeof this.panels[a].initialize && this.panels[a].initialize( this.getPresentationParams( a ) ),
                            this.view.add( this.panels[a] )
                 },
                 scope: this
              } )
           },
           getPresentationParams: function ( a ) {
              var b = this.getParams();
              return b.plugin === a ? b.pluginData : {}
           },
           setPresentationParams: function ( a, b ) {
              var c = {
                 plugin: a,
                 pluginData: b
              };
              this.setParams( c )
           },
           getParams: function () {
              var a = {}
              , b = Ext.util.Format.htmlDecode( SpiceCRM.KReporter.Designer.Application.reportRecord.get( "presentation_params" ) );
              return b && "" !== b && (a = Ext.decode( b )),
                      a
           },
           setParams: function ( a ) {
              SpiceCRM.KReporter.Designer.Application.reportRecord.set( "presentation_params", Ext.util.Format.htmlEncode( Ext.encode( a ) ) )
           },
           savePanel: function () {
              this.currentPanelId && this.setPresentationParams( this.currentPanelId, this.panels[this.currentPanelId].getPanelData() )
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.controller.unionListFieldsController", {
           extend: "Ext.app.ViewController",
           mappingWindow: null,
           alias: "controller.KReportDesignerUnionListFieldsController",
           unionid: null,
           config: {
              listen: {
                 global: {
                    mainExpanded: function () {
                       this.view.hide()
                    },
                    unionExpanded: function () {
                       this.view.show()
                    },
                    unionChanged: function ( a ) {
                       this.view.store.filter( "joinid", a ),
                               this.unionid = a
                    },
                    listFieldAdded: function ( a ) {
                       Ext.each( Ext.ComponentQuery.query( "#UnionTree" )[0].getRootNode().childNodes, function ( b ) {
                          this.view.store.add( {
                             joinid: b.get( "unionid" ),
                             fieldid: a.fieldid,
                             sequence: null,
                             name: a.name,
                             unionfieldpath: null,
                             unionfielddisplaypath: null,
                             unionfieldname: null,
                             path: a.path,
                             displaypath: a.displaypath,
                             fixedvalue: null,
                             id: SpiceCRM.KReporter.Designer.Application.kGuid()
                          } )
                       }, this )
                    },
                    listFieldsDeleted: function ( a ) {
                       this.view.store.removeFilter(),
                               this.view.store.each( function ( b ) {
                                  a.indexOf( b.get( "fieldid" ) ) > -1 && this.view.store.remove( b )
                               }, this ),
                               this.view.store.filter( "joinid", this.unionid )
                    }
                 }
              }
           },
           onBeforeDrop: function ( a, b, c, d, e, f ) {
              return this.mappingWindow || (this.mappingWindow = Ext.create( "SpiceCRM.KReporter.Designer.window.KReportDetails.unionMappingPopup" )),
                      this.mappingWindow.unionid = this.unionid,
                      this.mappingWindow.unionfields = this.view.store,
                      this.mappingWindow.mappingrecord = b.records[0],
                      this.mappingWindow.show( this ),
                      e.cancelDrop(),
                      !0
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.controller.UnionTreeController", {
           extend: "Ext.app.ViewController",
           alias: "controller.UnionTreeController",
           currentUnionId: null,
           config: {
              listen: {
                 global: {
                    mainModuleAdded: function ( a ) {
                       this.view.enable()
                    },
                    initialize: function () {
                       SpiceCRM.KReporter.Designer.Application.reportRecord.get( "union_modules" ) && (_unionModules = Ext.decode( Ext.util.Format.htmlDecode( SpiceCRM.KReporter.Designer.Application.reportRecord.get( "union_modules" ) ) ),
                               Ext.each( _unionModules, function ( a ) {
                                  this.view.getRootNode().appendChild( {
                                     id: a.unionid,
                                     unionid: a.unionid,
                                     module: a.module,
                                     path: "union" + a.unionid + ":" + a.module,
                                     selected: !0,
                                     draggable: !1
                                  } ),
                                          this.view.getRootNode().expand()
                               } ))
                    }
                 }
              },
              control: {
                 "#": {
                    expand: function () {
                       _selected = this.view.getSelection()[0],
                               !_selected && this.view.getRootNode().childNodes.length > 0 && (_selected = this.view.getRootNode().childNodes[0],
                               this.view.setSelection( _selected )),
                               Ext.globalEvents.fireEvent( "unionExpanded", _selected ? this.getUnionIdForNode( _selected ) : null )
                    },
                    select: function ( a, b ) {
                       _recordUnionId = this.getUnionIdForNode( b ),
                               this.currentUnionId !== _recordUnionId && (this.currentUnionId = _recordUnionId,
                                       Ext.globalEvents.fireEvent( "unionChanged", this.currentUnionId )),
                               this.view.down( "#unionModuleDelButton" ).enable(),
                               Ext.globalEvents.fireEvent( "moduleSelected", b )
                    }
                 },
                 "#unionModuleSelector": {
                    change: function ( a, b ) {
                       a.getValue() && Ext.ComponentQuery.query( "#unionModuleAddButton" )[0].enable()
                    }
                 },
                 "#unionModuleAddButton": {
                    click: function () {
                       _unionid = SpiceCRM.KReporter.Designer.Application.kGuid(),
                               _newNode = this.view.getRootNode().appendChild( {
                          id: _unionid,
                          unionid: _unionid,
                          module: Ext.ComponentQuery.query( "#unionModuleSelector" )[0].getValue(),
                          path: "unionroot::union" + _unionid + ":" + Ext.ComponentQuery.query( "#unionModuleSelector" )[0].getValue(),
                          selected: !0,
                          draggable: !1
                       } );
                       var a = Ext.data.StoreManager.lookup( "KReportDesignerWhereGroupsStore" );
                       a.add( {
                          id: _unionid,
                          groupid: _unionid,
                          unionid: _unionid,
                          group: "root",
                          type: "AND",
                          parent: "-"
                       } ),
                               this.view.expandNode( this.view.getRootNode() ),
                               Ext.data.StoreManager.lookup( "KReportDesignerListFieldsStore" ).each( function ( a ) {
                          Ext.data.StoreManager.lookup( "KReportDesignerUnionListFieldsStore" ).add( {
                             id: SpiceCRM.KReporter.Designer.Application.kGuid(),
                             joinid: _unionid,
                             fieldid: a.get( "fieldid" ),
                             name: a.get( "name" ),
                             path: a.get( "path" ),
                             displaypath: a.get( "displaypath" )
                          } )
                       }, this ),
                               Ext.ComponentQuery.query( "#unionModuleSelector" )[0].setValue( null ),
                               this.view.setSelection( _newNode )
                    }
                 },
                 "#unionModuleDelButton": {
                    click: function () {
                       _selectedNode = this.view.getSelection()[0],
                               this.view.getRootNode().removeChild( _selectedNode ),
                               Ext.data.StoreManager.lookup( "kreportdesginerUnionModules" ).remove( _selectedNode ),
                               this.view.getSelectionModel().select( 0 ),
                               this.view.getSelection().length <= 0 && (this.view.down( "#unionModuleDelButton" ).disable(),
                               Ext.data.StoreManager.lookup( "KReportDesignerUnionListFieldsStore" ).removeAll(),
                               Ext.data.StoreManager.lookup( "KReportDesignerWhereClausesStore" ).each( function ( a ) {
                          a.get( "unionid" ) == _selectedNode.get( "unionid" ) && Ext.data.StoreManager.lookup( "KReportDesignerWhereClausesStore" ).remove( a )
                       } ),
                               Ext.data.StoreManager.lookup( "KReportDesignerWhereGroupsStore" ).each( function ( a ) {
                          a.get( "groupid" ) == _selectedNode.get( "unionid" ) && Ext.data.StoreManager.lookup( "KReportDesignerWhereGroupsStore" ).remove( a )
                       } ))
                    }
                 }
              }
           },
           doInit: function () {},
           onLaunch: function () {},
           finishInit: function () {},
           getUnionIdForNode: function ( a ) {
              return "uniontreeroot" === a.parentNode.get( "id" ) || "root" === a.parentNode.get( "id" ) ? a.get( "unionid" ) : this.getUnionIdForNode( a.parentNode )
           },
           getUnionPath: function () {
              return this.view.getSelectionModel().getLastSelected().getPath()
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.controller.VisualizationContainerController", {
           extend: "Ext.app.ViewController",
           panels: {},
           currentPanelId: null,
           alias: "controller.KReportDesigner.VisualizationContainerController",
           initializePanel: function () {
              var a = this.getParams();
              a.layout && this.view.down( "combo[itemId=layoutCombo]" ).setValue( a.layout ).fireEvent( "select", this.view.down( "combo[itemId=layoutCombo]" ) ),
                      a.chartheight && this.view.down( "#vizHeightSpinner" ).setValue( a.chartheight )
           },
           config: {
              listen: {
                 global: {
                    designerPluginsLoaded: function () {
                       this.initializePanel()
                    }
                 }
              },
              control: {
                 "#layoutCombo": {
                    select: function ( a ) {
                       "-" != a.getValue() ? (this.view.down( "#vizHeightSpinner" ).enable( !0 ),
                               this.view.down( "#visualizationPluginCombo" ).enable( !0 ),
                               this.view.down( "#chartSpinner" ).enable( !0 ),
                               this.view.down( "#chartSpinner" ).setMaxValue( a.getStore().findRecord( "name", a.getValue() ).get( "count" ) )) : (this.view.down( "#vizHeightSpinner" ).disable( !0 ),
                               this.view.down( "#visualizationPluginCombo" ).disable( !0 ),
                               this.view.down( "#chartSpinner" ).disable( !0 ),
                               this.view.down( "#chartSpinner" ).setMaxValue( 0 ),
                               this.view.removeAll( !1 ));
                       var b = this.getParams();
                       b.layout = a.getValue(),
                               this.setParams( b )
                    }
                 },
                 "#visualizationPluginCombo": {
                    select: function ( a, b ) {
                       var c = this.view;
                       c.removeAll( !1 ),
                               this.currentPanelId = b.get( "id" ),
                               !this.panels[this.currentPanelId] || this.panels[this.currentPanelId] && this.panels[this.currentPanelId].destroyed ? this.loadPanel( this.currentPanelId, {} ) : (c.add( this.panels[this.currentPanelId] ),
                               this.panels[this.currentPanelId].setPanelData( {} ))
                    }
                 },
                 "#vizHeightSpinner": {
                    spinup: function ( a ) {
                       var b = this.getParams();
                       b.chartheight = parseInt( a.getValue() ),
                               this.setParams( b )
                    },
                    spindown: function ( a ) {
                       var b = this.getParams();
                       b.chartheight = parseInt( a.getValue() ),
                               this.setParams( b )
                    }
                 },
                 "#chartSpinner": {
                    spinup: function ( a ) {
                       var b = parseInt( a.getValue() );
                       this.currentPanelId && this.setVisualizationParams( b, this.currentPanelId, this.panels[this.currentPanelId].getPanelData() ),
                               b === a.max ? b = 1 : b += 1,
                               a.setValue( b ),
                               this.switchPanelToIndex( b )
                    },
                    spindown: function ( a ) {
                       var b = parseInt( a.getValue() );
                       this.currentPanelId && this.setVisualizationParams( b, this.currentPanelId, this.panels[this.currentPanelId].getPanelData() ),
                               1 === b || 0 === b ? b = a.max : b -= 1,
                               a.setValue( b ),
                               this.switchPanelToIndex( b )
                    }
                 }
              }
           },
           loadPanel: function ( a, b ) {
              var c = Ext.data.StoreManager.lookup( "KReportDesignerVisualizationPluginsStore" ).getById( a );
              c.get( "loaded" ) === !1 && Ext.Loader.loadScript( {
                 url: c.get( "include" ),
                 onLoad: function () {
                    c.set( "loaded", !0 ),
                            this.panels[a] = Ext.create( c.get( "panel" ) ),
                            this.panels[a].setPanelData( b ),
                            this.view.add( this.panels[a] )
                 },
                 scope: this
              } )
           },
           switchPanelToIndex: function ( a ) {
              var b = this.getVisualizationParams( a );
              if ( this.currentPanelId = "",
                      this.view.removeAll( !1 ),
                      this.view.down( "combobox[itemId=visualizationPluginCombo]" ).clearValue(),
                      b.pluginid ) {
                 if ( this.currentPanelId = b.pluginid,
                         this.panels[this.currentPanelId] )
                    this.panels[this.currentPanelId].setPanelData( b.plugindata ),
                            this.view.add( this.panels[this.currentPanelId] );
                 else {
                    var c = this.view.down( "combo[itemId=visualizationPluginCombo]" ).getStore();
                    c.getById( b.pluginid );
                    this.loadPanel( b.pluginid, b.plugindata )
                 }
                 this.view.down( "combobox[itemId=visualizationPluginCombo]" ).setValue( this.currentPanelId )
              }
           },
           getVisualizationParams: function ( a ) {
              var b = this.getParams();
              return a > 0 && b[a] && b[a].plugin && b[a][b[a].plugin] ? {
                 pluginid: b[a].plugin,
                 plugindata: b[a][b[a].plugin]
              } : a > 0 && b[a] && b[a].plugin ? {
                 plugoinid: b[a].plugin,
                 plugindata: {}
              } : {
                 plugoinid: "",
                 plugindata: {}
              }
           },
           setVisualizationParams: function ( a, b, c ) {
              var d = this.getParams();
              d[a] ? d[a].plugin = b : d[a] = {
                 plugin: b
              },
                      d[a][b] = c,
                      this.setParams( d )
           },
           getParams: function () {
              var a = {}
              , b = Ext.util.Format.htmlDecode( SpiceCRM.KReporter.Designer.Application.reportRecord.get( "visualization_params" ) );
              return b && "" !== b && (a = Ext.decode( b )),
                      a
           },
           setParams: function ( a ) {
              SpiceCRM.KReporter.Designer.Application.reportRecord.set( "visualization_params", Ext.util.Format.htmlEncode( Ext.encode( a ) ) )
           },
           savePanel: function () {
              this.currentPanelId && this.setVisualizationParams( this.view.down( "spinnerfield[itemId=chartSpinner]" ).getValue(), this.currentPanelId, this.panels[this.currentPanelId].getPanelData() )
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.controller.WhereClauseController", {
           extend: "Ext.app.ViewController",
           alias: "controller.KReportDesignerWhereClauseController",
           groupid: null,
           unionid: "root",
           whereClauseLastClick: {},
           whereConfig: {},
           init: function () {
              this.whereOperatorStore = Ext.create( "SpiceCRM.KReporter.Common.store.whereOperators", "kreporterWhereOperatorStore" ),
                      this.enumOptionsStore = Ext.create( "SpiceCRM.KReporter.Common.store.enumoptions", "kreporterEnumoptionsStore" ),
                      this.parentFieldsStore = Ext.create( "SpiceCRM.KReporter.Common.store.parentFields", "kreporterParentFieldsStore" ),
                      this.whereFunctionsStore = Ext.create( "SpiceCRM.KReporter.Designer.store.whereFunctions", "kreporterWhereFunctionsStore" ).load(),
                      this.autocompleteStore = Ext.create( "SpiceCRM.KReporter.Common.store.autcompleterecords", "kreporterAutocmpleteStore" ).load(),
                      Ext.Ajax.request( {
                         url: "KREST/KReporter/core/whereinitialize",
                         method: "GET",
                         success: function ( a, b ) {
                            this.whereConfig = Ext.decode( a.responseText )
                         },
                         scope: this
                      } )
           },
           config: {
              listen: {
                 global: {
                    whereGroupSelected: function ( a ) {
                       this.groupid = a.get( "groupid" ),
                               this.view.store.filter( "groupid", a.get( "groupid" ) ),
                               this.view.setSelection( null )
                    },
                    whereClauseSelected: function ( a ) {
                       this.id = a.get( "id" ),
                               this.view.store.filter( "id", a.get( "id" ) ),
                               this.view.setSelection( null )
                    },
                    mainExpanded: function () {
                       this.unionid = "root"
                    },
                    unionExpanded: function ( a ) {
                       a ? this.unionid = a : this.unionid = null
                    },
                    unionChanged: function ( a ) {
                       this.unionid = a
                    }
                 }
              },
              control: {
                 "#": {
                    beforeedit: function ( a, b, c ) {
                       SpiceCRM.KReporter.Common.gridSetEditor( b, this, SpiceCRM.KReporter.Designer.Application )
                    },
                    edit: function ( a, b ) {
                       this.gridAfterEdit( b )
                    },
                    click: function ( a ) {
                       this.whereClauseLastClick = e.getXY()
                    },
                    select: function ( a, b ) {
                       this.view.down( "#WhereClauseDeleteButton" ).enable();
                       var c = b.get( "path" ).split( ":" )
                               , d = Ext.data.StoreManager.lookup( "KReportDesignerBucketsWhereStore" );
                       d.clearFilter(),
                               d.filter( "fieldname", c[c.length - 1] )
                    }
                 },
                 "#WhereClauseDeleteButton": {
                    click: function () {
                       var a = this.view.getSelection()[0];
                       Ext.data.StoreManager.lookup( "KReportDesignerWhereClausesStore" ).remove( a ),
                               this.view.getSelection().length <= 0 && this.view.down( "#WhereClauseDeleteButton" ).disable()
                    }
                 }
              }
           },
           onBeforeDrop: function ( a, b, c, d, e, f ) {
              var g = Ext.ComponentQuery.query( "ModuleTree" )[0];
              g.getSelectionModel().getLastSelected();
              return Ext.each( b.records, function ( a ) {
                 var b = {};
                 b = "root" === this.unionid ? Ext.ComponentQuery.query( "#ModuleTreeGrid" )[0].controller.getRootPath() : Ext.ComponentQuery.query( "#UnionTree" )[0].controller.getUnionPath();
                 var c = b.path + "::field:" + a.get( "name" )
                         , d = b.displayPath;
                 this.view.store.add( {
                    id: SpiceCRM.KReporter.Designer.Application.kGuid(),
                    unionid: this.unionid,
                    fieldid: SpiceCRM.KReporter.Designer.Application.kGuid(),
                    path: c,
                    name: a.get( "text" ),
                    type: a.get( "type" ),
                    displaypath: d,
                    groupid: this.groupid,
                    referencefieldid: "",
                    operator: "ignore",
                    jointype: "required",
                    usereditable: "no",
                    dashleteditable: "no",
                    exportpdf: "no"
                 } )
              }, this ),
                      e.cancelDrop(),
                      !0
           },
           gridAfterEdit: function ( a ) {
              switch ( a.column.itemId ) {
                 case "value":
                 case "valueto":
                    switch ( a.record.data.operator ) {
                       case "autocomplete":
                          a.record.set( a.column.itemId + "key", a.value );
                          break;
                       case "parent_assign":
                          a.record.set( a.column.itemId + "key", a.value );
                          break;
                       case "function":
                          a.record.set( a.column.itemId + "key", a.value );
                          break;
                       case "reference":
                          break;
                       default:
                       switch ( a.record.data.type ) {
                          case "datetime":
                          case "datetimecombo":
                             break;
                          case "date":
                             switch ( a.record.data.operator ) {
                                case "lastndays":
                                case "lastnfdays":
                                case "lastnweeks":
                                case "notlastnweeks":
                                case "lastnfweeks":
                                case "lastnfmonth":
                                case "lastnmonthDaily":
                                case "lastnfquarter":
                                case "lastnyear":
                                case "lastnyearDaily":
                                case "nextndays":
                                case "nextnweeks":
                                case "notnextnweeks":
                                case "nextnmonth":
                                case "nextnmonthDaily":
                                case "nextnfquarter":
                                case "nextnyear":
                                case "nextnyearDaily":
                                case "betwndays":
                                   break;
                                case "lastnddays":
                                case "nextnddays":
                                case "betwnddays":
                                   break;
                                default:
                                   a.record.set( a.column.itemId + "key", Ext.Date.format( a.value, "Y-m-d" ) ),
                                           a.record.set( a.column.itemId, Ext.Date.format( a.value, cal_date_format.replace( /%/g, "" ) ) )
                             }
                             break;
                          case "user_name":
                          case "assigned_user_name":
                          case "enum":
                          case "radioenum":
                          case "parent_type":
                          case "multienum":
                             switch ( a.record.data.operator ) {
                                case "oneof":
                                case "oneofnot":
                                case "oneofnotornull":
                                   break;
                                case "starts":
                                case "notstarts":
                                case "contains":
                                case "notcontains":
                                   break;
                                default:
                                   a.record.set( a.column.itemId + "key", a.value );
                                   var b = a.column.getEditor().getStore()
                                           , c = b.find( "value", a.value );
                                   c > 0 && a.record.set( a.column.itemId, b.getAt( c ).get( "text" ) )
                             }
                             break;
                          case "bool":
                             a.record.set( a.column.itemId + "key", a.value ),
                                     a.record.set( a.column.itemId, languageGetText( "LBL_BOOL_" + a.value ) )
                       }
                    }
                    break;
                 case "operator":
                    a.record.set( "value", "" ),
                            a.record.set( "valueto", "" ),
                            a.record.set( "valuekey", "" ),
                            a.record.set( "valuetokey", "" )
              }
           },
           getOperatorCount: function ( a, b ) {
              return void 0 !== typeof this.whereConfig.operatorCount[a] ? this.whereConfig.operatorCount[a] : 0
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.controller.WhereTreeController", {
           extend: "Ext.app.ViewController",
           requires: [ ],
           unionid: "root",
           alias: "controller.KReportDesignerWhereTreeController",
           config: {
              listen: {
                 global: {
                    mainExpanded: function () {
                       this.unionid = "root",
                               this.view.setRoot( "root" ),
                               Ext.globalEvents.fireEvent( "whereGroupSelected", this.view.getRootNode() )
                    },
                    unionExpanded: function ( a ) {
                       a && (this.unionid = a,
                               this.view.setRoot( a ),
                               Ext.globalEvents.fireEvent( "whereGroupSelected", this.view.getRootNode() ))
                    },
                    unionChanged: function ( a ) {
                       this.unionid = a,
                               this.view.setRoot( a ),
                               Ext.globalEvents.fireEvent( "whereGroupSelected", this.view.getRootNode() )
                    }
                 }
              },
              control: {
                 "#": {
                    beforeitemexpand: function ( a ) {},
                    select: function ( a, b ) {
                       Ext.globalEvents.fireEvent( "whereGroupSelected", b ),
                               0 === b.childNodes.length && b.get( "id" ) !== this.view.getRootNode().get( "id" ) ? this.view.down( "#WhereGroupDeleteButton" ).enable() : this.view.down( "#WhereGroupDeleteButton" ).disable()
                    }
                 },
                 "#WhereGroupAddButton": {
                    click: function () {
                       this.addNode()
                    }
                 },
                 "#WhereGroupDeleteButton": {
                    click: function () {
                       var a = this.view.getSelection()[0];
                       whereGroupsStore = Ext.data.StoreManager.lookup( "KReportDesignerWhereGroupsStore" ),
                               whereGroupsStore.remove( whereGroupsStore.getById( a.get( "id" ) ) ),
                               Ext.data.StoreManager.lookup( "KReportDesignerWhereClausesStore" ).removeAll(),
                               this.view.setSelection( a.parentNode ),
                               a.remove()
                    }
                 },
                 "#WhereGroupOperator": {
                    change: function ( a, b ) {
                       var c = this.view.getSelection();
                       whereGroupsStore = Ext.data.StoreManager.lookup( "KReportDesignerWhereGroupsStore" );
                       var d = whereGroupsStore.findRecord( "id", c[0].id );
                       d.set( "type", b )
                    }
                 }
              }
           },
           addNode: function () {
              Ext.Msg.prompt( languageGetText( "LBL_ADD_GROUP_NAME" ), languageGetText( "LBL_CHANGE_GROUP_NAME_PROMPT" ), function ( a, b ) {
                 if ( "ok" == a ) {
                    var c = this.view.getSelection()[0];
                    _childId = SpiceCRM.KReporter.Designer.Application.kGuid();
                    var d = c.appendChild( {
                       id: _childId,
                       groupid: _childId,
                       group: b,
                       type: "AND",
                       selected: !0,
                       draggable: !1
                    } );
                    whereGroupsStore = Ext.data.StoreManager.lookup( "KReportDesignerWhereGroupsStore" ),
                            whereGroupsStore.add( {
                               id: d.get( "groupid" ),
                               groupid: d.get( "groupid" ),
                               unionid: this.unionid,
                               parent: c.get( "id" ),
                               group: b,
                               type: "AND"
                            } ),
                            c.isExpanded() || c.expand(),
                            this.view.getSelectionModel().select( d )
                 }
              }, this )
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.fields.korgfield", {
           extend: "Ext.form.field.ComboBox",
           requires: [ "SpiceCRM.KReporter.Designer.model.korgs" ],
           alias: [ "widget.korgfield" ],
           fieldLabel: languageGetText( "LBL_KORGOBJECTS_LABEL" ),
           forceSelection: !0,
           listConfig: {
              minWidth: 250
           },
           typeAhead: !1,
           loadingText: languageGetText( "LBL_SEARCHING" ),
           width: 200,
           labelWidth: 50,
           pageSize: 10,
           hideTrigger: !1,
           triggerAction: "all",
           queryParam: "searchterm"
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.fields.teamfield", {
           extend: "Ext.form.field.ComboBox",
           requires: [ "SpiceCRM.KReporter.Designer.model.teams" ],
           alias: [ "widget.teamfield" ],
           fieldLabel: languageGetText( "LBL_ASSIGNED_TEAM_LABEL" ),
           forceSelection: !0,
           listConfig: {
              minWidth: 250
           },
           typeAhead: !1,
           loadingText: languageGetText( "LBL_SEARCHING" ),
           width: 200,
           labelWidth: 50,
           pageSize: 10,
           hideTrigger: !1,
           triggerAction: "all",
           queryParam: "searchterm"
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.fields.userfield", {
           extend: "Ext.form.field.ComboBox",
           requires: [ "SpiceCRM.KReporter.Designer.model.users" ],
           alias: [ "widget.userfield" ],
           fieldLabel: languageGetText( "LBL_ASSIGNED_USER_LABEL" ),
           forceSelection: !0,
           listConfig: {
              minWidth: 250
           },
           typeAhead: !1,
           loadingText: languageGetText( "LBL_SEARCHING" ),
           width: 250,
           labelWidth: 50,
           hideTrigger: !1,
           triggerAction: "all",
           queryParam: "searchterm",
           pageSize: 10
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.window.KReportDetails.reportOptions", {
           extend: "Ext.window.Window",
           title: languageGetText( "LBL_REPORT_OPTIONS" ),
           width: 450,
           modal: !0,
           closeAction: "hide",
           items: [ {
                 xtype: "fieldset",
                 // Mint start #53238
                 title: languageGetText( "LBL_AUTH_CHECK_TITLE" ),
                 // Mint end #53238
                 collapsible: !1,
                 width: 410,
                 style: {
                    "margin-left": "5px",
                    "margin-right": "5px"
                 },
                 items: [ {
                       xtype: "radiogroup",
                       itemId: "kreportoptionsauthCheck",
                       fieldLabel: languageGetText( "LBL_AUTH_CHECK" ),
                       columns: 1,
                       items: [ {
                             boxLabel: languageGetText( "LBL_AUTH_FULL" ),
                             name: "ac",
                             inputValue: "full"
                          }, {
                             boxLabel: languageGetText( "LBL_AUTH_TOP" ),
                             name: "ac",
                             inputValue: "top"
                          }, {
                             boxLabel: languageGetText( "LBL_AUTH_NONE" ),
                             name: "ac",
                             inputValue: "none"
                          } ]
                    }, {
                       xtype: "checkbox",
                       itemId: "kreportoptionsshowDeleted",
                       fieldLabel: languageGetText( "LBL_SHOW_DELETED" )
                    } ]
              }, {
                 xtype: "fieldset",
                 title: languageGetText( "LBL_FOLDED_PANELS" ),
                 collapsible: !1,
                 width: 410,
                 style: {
                    "margin-left": "5px",
                    "margin-right": "5px"
                 },
                 items: [ {
                       xtype: "radiogroup",
                       itemId: "kreportoptionsdynOptions",
                       fieldLabel: languageGetText( "LBL_DYNOPTIONS" ),
                       items: [ {
                             boxLabel: languageGetText( "LBL_PANEL_OPEN" ),
                             name: "dopt",
                             inputValue: "open"
                          }, {
                             boxLabel: languageGetText( "LBL_PANEL_COLLAPSED" ),
                             name: "dopt",
                             inputValue: "collapsed"
                          } ]
                    },
                    // Mint start #53238
                    {
                       xtype: "checkbox",
                       itemId: "kreportoptionsupdateOnRequest",
                       fieldLabel: languageGetText( "LBL_UPDATE_ON_REQUEST" )
                    },
                 ]
              } ],
           // Mint end #53238
           listeners: {
              show: function () {
                 "" === SpiceCRM.KReporter.Designer.Application.reportRecord.get( "reportoptions" ) ? optionsObject = {} : optionsObject = Ext.decode( Ext.util.Format.htmlDecode( SpiceCRM.KReporter.Designer.Application.reportRecord.get( "reportoptions" ) ) ),
                         void 0 === optionsObject.authCheck && (optionsObject.authCheck = "all"),
                         this.down( "#kreportoptionsauthCheck" ).setValue( {
                    ac: optionsObject.authCheck
                 } ),
                         void 0 !== optionsObject.showDeleted && optionsObject.showDeleted === !0 && this.down( "#kreportoptionsshowDeleted" ).setValue( !0 ),
                         void 0 === optionsObject.optionsFolded || "open" === optionsObject.optionsFolded ? this.down( "#kreportoptionsdynOptions" ).setValue( {
                    dopt: "open"
                 } ) : this.down( "#kreportoptionsdynOptions" ).setValue( {
                    dopt: "collapsed"
                 } ),
                         void 0 !== optionsObject.updateOnRequest && optionsObject.updateOnRequest === !0 && this.down( "#kreportoptionsupdateOnRequest" ).setRawValue( !0 )
              },
              hide: function () {
                 optionsObject = {},
                         optionsObject.authCheck = this.down( "#kreportoptionsauthCheck" ).getValue().ac,
                         optionsObject.showDeleted = this.down( "#kreportoptionsshowDeleted" ).getValue(),
                         optionsObject.optionsFolded = this.down( "#kreportoptionsdynOptions" ).getValue().dopt,
                         optionsObject.updateOnRequest = this.down( "#kreportoptionsupdateOnRequest" ).getValue(),
                         SpiceCRM.KReporter.Designer.Application.reportRecord.set( "reportoptions", Ext.util.Format.htmlEncode( Ext.encode( optionsObject ) ) )
              }
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.window.KReportDetails.unionMappingPopup", {
           extend: "Ext.window.Window",
           title: languageGetText( "LBL_MUTLISELECT_POPUP_TITLE" ),
           layout: "fit",
           width: 500,
           height: 300,
           closeAction: "hide",
           plain: !0,
           draggable: !0,
           modal: !0,
           unionid: null,
           unionfields: null,
           mappingrecord: null,
           items: [ {
                 xtype: "gridpanel",
                 store: Ext.create( "SpiceCRM.KReporter.Designer.store.unionMappingPopupFields" ),
                 columns: [ {
                       hidden: !0,
                       dataIndex: "fieldid"
                    }, {
                       text: languageGetText( "LBL_NAME" ),
                       dataIndex: "name",
                       flex: 1,
                       header: languageGetText( "LBL_MULTISELECT_TEXT_HEADER" )
                    }, {
                       text: languageGetText( "LBL_UNIONFIELDDISPLAYPATH" ),
                       dataIndex: "unionfielddisplaypath",
                       width: 150,
                       sortable: !1
                    }, {
                       text: languageGetText( "LBL_UNIONFIELDNAME" ),
                       dataIndex: "unionfieldname",
                       width: 150,
                       sortable: !1
                    } ],
                 selModel: new Ext.selection.RowModel( {
                    mode: "SINGLE"
                 } ),
                 stripeRows: !0,
                 autoExpandColumn: "name",
                 height: 350,
                 width: 400
              } ],
           buttons: [ {
                 text: languageGetText( "LBL_MUTLISELECT_CANCEL_BUTTON" ),
                 handler: function () {
                    this.up( ".window" ).hide()
                 }
              }, {
                 text: languageGetText( "LBL_MUTLISELECT_CLOSE_BUTTON" ),
                 handler: function () {
                    this.up( ".window" ).hide(),
                            _unionListStore = Ext.data.StoreManager.lookup( "KReportDesignerUnionListFieldsStore" );
                    var a = this.up( ".window" ).down( ".grid" ).getSelectionModel().getSelection()
                            , b = _unionListStore.find( "fieldid", a[0].get( "fieldid" ) )
                            , c = _unionListStore.getAt( b )
                            , d = Ext.ComponentQuery.query( "#UnionTree" )[0].controller
                            , e = d.getUnionPath();
                    c.set( "unionfieldpath", e.path + "::field:" + this.up( ".window" ).mappingrecord.get( "name" ) ),
                            c.set( "unionfieldname", this.up( ".window" ).mappingrecord.get( "name" ) ),
                            c.set( "unionfielddisplaypath", e.displayPath )
                 }
              } ],
           listeners: {
              show: function () {
                 this.down( ".grid" ).store.removeAll(),
                         this.unionfields.each( function ( a ) {
                            this.down( ".grid" ).store.add( {
                               fieldid: a.get( "fieldid" ),
                               name: a.get( "name" ),
                               unionfielddisplaypath: a.get( "unionfielddisplaypath" ),
                               unionfieldname: a.get( "unionfieldname" )
                            } )
                         }, this )
              }
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.view.IntegrationContainer", {
           extend: "Ext.panel.Panel",
           itemId: "integrationPluginContainer",
           requires: [ "SpiceCRM.KReporter.Designer.model.plugin" ],
           alias: [ "widget.KReportDetails.IntegrationContainer" ],
           controller: "KReportDesigner.IntegrationContainerController",
           width: "100%",
           heiht: "100%",
           layout: "hbox",
           defaults: {
              border: !1
           },
           items: [ {
                 xtype: "gridpanel",
                 itemId: "integrationPluginsGridPanel",
                 width: 300,
                 height: "100%",
                 border: "0 1 0 0",
                 overflowY: "scroll",
                 store: Ext.create( "SpiceCRM.KReporter.Designer.store.plugins", {
                    storeId: "KReportDesignerIntegrationPluginsStore"
                 } ),
                 viewConfig: {
                    markDirty: !1
                 },
                 columns: [ {
                       text: "id",
                       dataIndex: "id",
                       hidden: !0
                    }, {
                       text: languageGetText( "LBL_INTEGRATION_PLUGINNAME" ),
                       dataIndex: "name",
                       flex: 1,
                       menuDisabled: !0,
                       resizable: !1,
                       sortable: !1
                    }, {
                       menuDisabled: !0,
                       resizable: !1,
                       sortable: !1,
                       width: 35,
                       dataIndex: "active",
                       renderer: function ( a ) {
                          switch ( a ) {
                             case 1:
                                return '<img src="modules/KReports/images/lightbulb.png"></img>';
                             default:
                                return '<img src="modules/KReports/images/lightbulb_off.png"></img>'
                          }
                       }
                    } ],
                 split: !0
              }, {
                 xtype: "panel",
                 layout: "fit",
                 itemId: "integrationPluginEditorPanel",
                 flex: 1,
                 height: "100%",
                 split: !0
              } ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.view.KReportDetails", {
           extend: "Ext.tab.Panel",
           alias: [ "widget.KReportDetails" ],
           flex: 3,
           activeTab: 1,
           defaults: {
              border: !1
           },
           items: [ {
                 title: languageGetText( "LBL_SELECT" ),
                 icon: "modules/KReports/images/selection.png",
                 xtype: "KReportDetails.WhereContainer"
              }, {
                 title: languageGetText( "LBL_MANIPULATE" ),
                 xtype: "panel",
                 icon: "modules/KReports/images/manipulation.png",
                 layout: "fit",
                 items: [ {
                       xtype: "KReportDetails.listFieldsGrid"
                    }, {
                       xtype: "KReportDetails.unionListFieldsGrid"
                    } ]
              }, {
                 title: languageGetText( "LBL_PRESENTATION" ),
                 icon: "modules/KReports/images/presentation.png",
                 xtype: "KReportDetails.PresentationContainer",
                 itemId: "KReportDetailsPresentationContainer"
              }, {
                 title: languageGetText( "LBL_VISUALIZATION" ),
                 icon: "modules/KReports/images/visualization.png",
                 xtype: "KReportDetails.VisualizationContainer",
                 itemId: "KReportDetailsVisualizationContainer"
              }, {
                 title: languageGetText( "LBL_INTEGRATION" ),
                 icon: "modules/KReports/images/integration.png",
                 xtype: "KReportDetails.IntegrationContainer",
                 itemId: "KReportDetailsIntegrationContainer"
              } ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.view.editorWindow", {
           extend: "Ext.window.Window",
           modal: !0,
           height: 200,
           width: 400,
           layout: "fit",
           record: null,
           editedField: null,
           editedFieldText: null,
           title: languageGetText( "LBL_EDITOR" ), // Mint
           closeAction: "hide",
           items: [ {
                 xtype: "textarea"
              } ],
           buttons: [ {
                 text: languageGetText( "LBL_OK" ), // Mint
                 handler: function () {
                    var a = this.up( "window" );
                    "" !== a.down( "textarea" ).getValue() ? a.record.set( a.editedField, btoa( a.items.items[0].getValue() ) ) : a.record.set( a.editedField, "" ),
                            a.close()
                 }
              }, {
                 text: languageGetText( "LBL_CANCEL_BUTTON" ), // Mint
                 handler: function () {
                    var a = this.up( "window" );
                    a.close()
                 }
              } ],
           listeners: {
              show: function () {
                 if ( this.record.get( this.editedField ) ) {
                    try {
                       _editorvalue = decodeURIComponent( atob( this.record.get( this.editedField ) ) )
                    } catch ( a ) {
                       _editorvalue = atob( this.record.get( this.editedField ) )
                    }
                    this.items.items[0].setValue( _editorvalue )
                 } else
                    this.items.items[0].setValue( "" );
                 this.setTitle( this.record.get( "name" ) + " - " + this.editedFieldText )
              }
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.view.KReportDetails.listFieldsGrid", {
           extend: "Ext.grid.Panel",
           require: [ "SpiceCRM.KReporter.Designer.controller.listFieldsController" ],
           itemId: "KReportDetailsListFieldsGrid",
           controller: "KReportDesignerListFieldsController",
           alias: [ "widget.KReportDetails.listFieldsGrid" ],
           store: Ext.create( "SpiceCRM.KReporter.Designer.store.listFields", {
              storeId: "KReportDesignerListFieldsStore"
           } ),
           flex: 3,
           border: !1,
           height: "100%",
           columns: [ {
                 text: languageGetText( "LBL_SEQUENCE" ),
                 readOnly: !0,
                 dataIndex: "sequence",
                 width: 30,
                 sortable: !0,
                 hidden: !0
              }, {
                 text: languageGetText( "LBL_FULLPATH" ),
                 readOnly: !0,
                 dataIndex: "path",
                 width: 150,
                 sortable: !0,
                 hidden: !0
              }, {
                 text: languageGetText( "LBL_PATH" ),
                 readOnly: !0,
                 dataIndex: "displaypath",
                 width: 150,
                 sortable: !1
              }, {
                 text: languageGetText( "LBL_FIELDNAME" ),
                 dataIndex: "fieldname",
                 sortable: !1,
                 width: 150,
                 hidden: !0
              }, {
                 text: languageGetText( "LBL_NAME" ),
                 dataIndex: "name",
                 sortable: !1,
                 width: 150,
                 editor: {
                    xtype: "textfield"
                 }
              }, {
                 text: languageGetText( "LBL_JOIN_TYPE" ),
                 readOnly: !0,
                 dataIndex: "jointype",
                 width: 170,
                 sortable: !0,
                 hidden: !1,
                 editor: {
                    xtype: "kcombo",
                    listConfig: {
                       minWidth: 170
                    },
                    typeAhead: !0,
                    triggerAction: "all",
                    mode: "local",
                    store: Ext.create( "Ext.data.Store", {
                       fields: [ "value", "text" ],
                       data: [ {
                             value: "optional",
                             text: languageGetText( "LBL_JT_OPTIONAL" )
                          }, {
                             value: "required",
                             text: languageGetText( "LBL_JT_REQUIRED" )
                          } ]
                    } ),
                    valueField: "value",
                    displayField: "text"
                 },
                 renderer: function ( a ) {
                    return void 0 !== a && "-" !== a ? languageGetText( "LBL_JT_" + a.toUpperCase() ) : a
                 }
              }, {
                 text: languageGetText( "LBL_SQLFUNCTION" ),
                 readOnly: !0,
                 dataIndex: "sqlfunction",
                 width: 150,
                 sortable: !1,
                 hidden: !1,
                 editor: {
                    xtype: "kcombo",
                    listConfig: {
                       minWidth: 150
                    },
                    typeAhead: !0,
                    triggerAction: "all",
                    mode: "local",
                    store: new Ext.data.ArrayStore( {
                       fields: [ "value", "text" ],
                       data: [ [ "-", "-" ], [ "SUM", languageGetText( "LBL_FUNCTION_SUM" ) ], [ "COUNT", languageGetText( "LBL_FUNCTION_COUNT" ) ], [ "COUNT_DISTINCT", languageGetText( "LBL_FUNCTION_COUNT_DISTINCT" ) ], [ "MAX", languageGetText( "LBL_FUNCTION_MAX" ) ], [ "MIN", languageGetText( "LBL_FUNCTION_MIN" ) ], [ "AVG", languageGetText( "LBL_FUNCTION_AVG" ) ], [ "GROUP_CONCAT", languageGetText( "LBL_FUNCTION_GROUP_CONCAT" ) ], [ "GROUP_CONASC", languageGetText( "LBL_FUNCTION_GROUP_CONASC" ) ], [ "GROUP_CONDSC", languageGetText( "LBL_FUNCTION_GROUP_CONDSC" ) ] ]
                    } ),
                    valueField: "value",
                    displayField: "text"
                 },
                 renderer: function ( a ) {
                    return void 0 !== a && "-" !== a ? languageGetText( "LBL_FUNCTION_" + a.toUpperCase() ) : a
                 }
              }, {
                 text: languageGetText( "LBL_CUSTOMSQLFUNCTION" ),
                 readOnly: !0,
                 itemId: "customsqlfunction",
                 dataIndex: "customsqlfunction",
                 width: 70,
                 sortable: !1,
                 hidden: !1,
                 editor: {
                    xtype: "textfield"
                 },
                 renderer: function ( a ) {
                    if ( !a || "" === a || null === a )
                       return a;
                    try {
                       _tbfvalue = decodeURIComponent( atob( a ) )
                    } catch ( b ) {
                       _tbfvalue = atob( a )
                    }
                    return Ext.util.Format.htmlEncode( _tbfvalue )
                 }
              }, {
                 text: languageGetText( "LBL_GROUPING" ),
                 readOnly: !0,
                 dataIndex: "grouping",
                 width: 150,
                 sortable: !1,
                 // Mint start #43804
                 hidden: true,
                 //hidden: !1,
                 // Mint end #43804
                 editor: {
                    xtype: "kcombo",
                    store: Ext.create( "SpiceCRM.KReporter.Designer.store.buckets", {
                       storeId: "KReportDesignerBucketsStore"
                    } ),
                    listConfig: {
                       minWidth: 150
                    },
                    typeAhead: !0,
                    triggerAction: "all",
                    mode: "local",
                    valueField: "id",
                    displayField: "name"
                 },
                 renderer: function ( a ) {
                    var b = Ext.data.StoreManager.lookup( "KReportDesignerBucketsStore" );
                    return a && b ? b.getById( a ).get( "name" ) : a
                 }
              }, {
                 text: languageGetText( "LBL_VALUETYPE" ),
                 readOnly: !0,
                 dataIndex: "valuetype",
                 width: 100,
                 sortable: !1,
                 hidden: !1,
                 editor: {
                    xtype: "kcombo",
                    listConfig: {
                       minWidth: 100
                    },
                    typeAhead: !0,
                    triggerAction: "all",
                    lazyRender: !1,
                    mode: "local",
                    store: new Ext.data.ArrayStore( {
                       fields: [ "value", "text" ],
                       data: [ [ "-", "-" ], [ "TOFSUM", languageGetText( "LBL_VALUETYPE_TOFSUM" ) ], [ "POFSUM", languageGetText( "LBL_VALUETYPE_POFSUM" ) ], [ "POFCOUNT", languageGetText( "LBL_VALUETYPE_POFCOUNT" ) ], [ "POFAVG", languageGetText( "LBL_VALUETYPE_POFAVG" ) ], [ "DOFSUM", languageGetText( "LBL_VALUETYPE_DOFSUM" ) ], [ "DOFCOUNT", languageGetText( "LBL_VALUETYPE_DOFCOUNT" ) ], [ "DOFAVG", languageGetText( "LBL_VALUETYPE_DOFAVG" ) ], [ "C", languageGetText( "LBL_VALUETYPE_C" ) ] ]
                    } ),
                    valueField: "value",
                    displayField: "text"
                 },
                 renderer: function ( a ) {
                    return a && void 0 !== a && "-" !== a && "" !== a ? languageGetText( "LBL_VALUETYPE_" + a.toUpperCase() ) : a
                 }
              }, {
                 text: languageGetText( "LBL_GROUPBY" ),
                 readOnly: !0,
                 dataIndex: "groupby",
                 width: 100,
                 sortable: !0,
                 hidden: !1,
                 editor: {
                    xtype: "kcombo",
                    typeAhead: !1,
                    triggerAction: "all",
                    editable: !1,
                    mode: "local",
                    store: new Ext.data.ArrayStore( {
                       fields: [ "value", "text" ],
                       data: [ [ "no", languageGetText( "LBL_NO" ) ], [ "yes", languageGetText( "LBL_YES" ) ] ]
                    } ),
                    valueField: "value",
                    displayField: "text"
                 },
                 renderer: function ( a ) {
                    return void 0 !== a ? languageGetText( "LBL_" + a.toUpperCase() ) : a
                 }
              }, {
                 text: languageGetText( "LBL_FIXEDVALUE" ),
                 dataIndex: "fixedvalue",
                 sortable: !1,
                 width: 80,
                 editor: {
                    xtype: "textfield"
                 }
              }, {
                 text: languageGetText( "LBL_ASSIGNTOVALUE" ),
                 dataIndex: "assigntovalue",
                 sortable: !1,
                 width: 60,
                 editor: {
                    xtype: "textfield"
                 },
                 hidden: !1
              }, {
                 itemId: "formulavalue",
                 text: languageGetText( "LBL_FORMULAVALUE" ),
                 dataIndex: "formulavalue",
                 sortable: !1,
                 width: 170,
                 editor: {
                    xtype: "textfield",
                    listeners: {
                       focus: function ( a ) {
                          void 0 !== a.getValue() && "" !== a.getValue() && a.setValue( K.kreports.decode64( a.getValue() ) )
                       }
                    }
                 },
                 renderer: function ( a ) {
                    if ( !a || "" === a || null === a )
                       return a;
                    try {
                       _tbfvalue = decodeURIComponent( atob( a ) )
                    } catch ( b ) {
                       _tbfvalue = atob( a )
                    }
                    return Ext.util.Format.htmlEncode( _tbfvalue )
                 },
                 hidden: !1
              }, {
                 text: languageGetText( "LBL_FORMULASEQUENCE" ),
                 dataIndex: "formulasequence",
                 sortable: !1,
                 width: 70,
                 editor: {
                    xtype: "numberfield",
                    anchor: "100%",
                    allowDecimals: !1
                 },
                 hidden: !1
              } ],
           viewConfig: {
              markDirty: !1,
              plugins: {
                 ptype: "gridviewdragdrop",
                 dropGroup: "designerfields",
                 dragGroup: "designerfields",
                 enableDrag: !0
              },
              listeners: {
                 beforedrop: "onBeforeDrop",
                 scope: "controller"
              }
           },
           plugins: [ Ext.create( "Ext.grid.plugin.CellEditing", {
                 clicksToEdit: 1,
                 listeners: {
                    beforeedit: function ( a, b ) {
                       if ( "formulavalue" === b.column.itemId || "customsqlfunction" === b.column.itemId )
                          return SpiceCRM.KReporter.Designer.view.KReportDetails.editorWindow.record = b.record,
                                  SpiceCRM.KReporter.Designer.view.KReportDetails.editorWindow.editedField = b.column.itemId,
                                  SpiceCRM.KReporter.Designer.view.KReportDetails.editorWindow.editedFieldText = b.column.text,
                                  SpiceCRM.KReporter.Designer.view.KReportDetails.editorWindow.show(),
                                  !1
                    }
                 }
              } ) ],
           dockedItems: [ {
                 xtype: "toolbar",
                 dock: "top",
                 defaults: {
                    padding: "5px 2px"
                 },
                 items: [ {
                       text: languageGetText( "LBL_ADDEMTPY_BUTTON_LABEL" ),
                       icon: "modules/KReports/images/add.png",
                       itemId: "kreportsdesignerlistgridaddfixedbutton"
                    }, {
                       text: languageGetText( "LBL_DELETE_BUTTON_LABEL" ),
                       icon: "modules/KReports/images/delete.png",
                       itemId: "kreportsdesignerlistgriddeletefieldbutton"
                    }, "-", {
                       xtype: "numberfield",
                       width: 200,
                       allowDecimals: !1,
                       minValue: 0,
                       fieldLabel: languageGetText( "LBL_SELECTION_LIMIT" ),
                       itemId: "listgridSelectionLimit",
                       listeners: {
                          afterrender: function ( a ) {
                             if ( SpiceCRM.KReporter.Designer.Application.reportRecord.get( "selectionlimit" ) ) {
                                var b = SpiceCRM.KReporter.Designer.Application.reportRecord.get( "selectionlimit" ).split( " " );
                                a.setValue( b[0] )
                             }
                          },
                          change: function ( a ) {
                             a.getValue() && this.up().down( "#listgridSelectionLimType" ) ? this.up().down( "#listgridSelectionLimType" ).getValue() && SpiceCRM.KReporter.Designer.Application.reportRecord.set( "selectionlimit", a.getValue() + " " + this.up().down( "#listgridSelectionLimType" ) ) : SpiceCRM.KReporter.Designer.Application.reportRecord.set( "selectionlimit", "" )
                          }
                       }
                    }, {
                       xtype: "combobox",
                       itemId: "listgridSelectionLimType",
                       typeAhead: !0,
                       triggerAction: "all",
                       width: 105,
                       lazyRender: !0,
                       value: "",
                       mode: "local",
                       store: new Ext.data.ArrayStore( {
                          fields: [ "value", "text" ],
                          data: [ [ "", "-" ], [ "r", languageGetText( "LBL_RECORDS" ) ], [ "p", languageGetText( "LBL_PERCENTAGE" ) ] ]
                       } ),
                       valueField: "value",
                       displayField: "text",
                       listeners: {
                          afterrender: function ( a ) {
                             if ( SpiceCRM.KReporter.Designer.Application.reportRecord.get( "selectionlimit" ) ) {
                                var b = SpiceCRM.KReporter.Designer.Application.reportRecord.get( "selectionlimit" ).split( " " );
                                a.setValue( b[1] )
                             }
                          },
                          change: function ( a ) {
                             a.getValue() && this.up().down( "#listgridSelectionLimit" ) && (this.up().down( "#listgridSelectionLimit" ).getValue() ? SpiceCRM.KReporter.Designer.Application.reportRecord.set( "selectionlimit", this.up().down( "#listgridSelectionLimit" ) + " " + a.getValue() ) : SpiceCRM.KReporter.Designer.Application.reportRecord.set( "selectionlimit", "" ))
                          }
                       }
                    } ]
              } ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.view.main.Main", {
           extend: "Ext.container.Container",
           requires: [ "SpiceCRM.KReporter.Designer.controller.MainController" ],
           xtype: "app-main",
           renderTo: "kreportdesigner",
           height: "100%",
           border: !1,
           controller: "KReportDesignerMain",
           layout: {
              type: "border"
           },
           style: {
              "background-color": "transparent"
           },
           items: [ {
                 xtype: "designerMainToolbar",
                 region: "north",
                 margin: "0 0 10 0"
              }, {
                 xtype: "moduleSelector",
                 region: "west",
                 width: 250,
                 margin: "0 0 0 0",
                 split: !0
              }, {
                 xtype: "KReportDetails",
                 region: "center"
              } ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.view.maintoolbar", {
           extend: "Ext.Toolbar",
           optionsWindow: null,
           controller: "KReportDesignerMainToolbar",
           alias: [ "widget.designerMainToolbar" ],
           initialize: function () {},
           lockAll: function () {},
           items: [ {
                 xtype: "button",
                 itemId: "save",
                 text: languageGetText( "LBL_SAVE_BUTTON_LABEL" ),
                 icon: "modules/KReports/images/save.png",
                 disabled: !1
              }, {
                 xtype: "button",
                 itemId: "cancel",
                 text: languageGetText( "LBL_CANCEL_BUTTON_LABEL" ),
                 icon: "modules/KReports/images/cancel.png",
                 disabled: !1
              }, "-", {
                 xtype: "textfield",
                 itemId: "reportname",
                 width: 200,
                 labelWidth: 50,
                 fieldLabel: languageGetText( "LBL_REPORT_NAME_LABEL" ),
                 setName: function ( a, b ) {},
                 listeners: {
                    beforerender: function () {
                       this.setValue( SpiceCRM.KReporter.Designer.Application.reportRecord.get( "name" ) )
                    },
                    change: function () {
                       SpiceCRM.KReporter.Designer.Application.reportRecord.set( "name", this.getValue() )
                    }
                 }
              }, {
                 xtype: "button",
                 icon: "modules/KReports/images/longtext.png",
                 handler: function () {
                    SpiceCRM.KReporter.Designer.view.maintoolbar.descriptionTextArea = new Ext.form.HtmlEditor( {} ),
                            SpiceCRM.KReporter.Designer.view.maintoolbar.descriptionPopupwin = new Ext.Window( {
                               layout: "fit",
                               width: 600,
                               height: 400,
                               closeAction: "close",
                               plain: !0,
                               title: languageGetText( "LBL_DESCRIPTION" ), // Mint
                               items: SpiceCRM.KReporter.Designer.view.maintoolbar.descriptionTextArea,
                               listeners: {
                                  beforeclose: function () {
                                     SpiceCRM.KReporter.Designer.Application.reportRecord.set( "description", SpiceCRM.KReporter.Designer.view.maintoolbar.descriptionTextArea.getValue() )
                                  }
                               }
                            } ),
                            SpiceCRM.KReporter.Designer.view.maintoolbar.descriptionTextArea.setValue( SpiceCRM.KReporter.Designer.Application.reportRecord.get( "description" ) ),
                            SpiceCRM.KReporter.Designer.view.maintoolbar.descriptionPopupwin.show()
                 },
                 disabled: !1
              }, "-", {
                 xtype: "userfield",
                 store: Ext.create( "SpiceCRM.KReporter.Designer.store.users", {
                    storeId: "KReportDesignerUsersStore"
                 } ),
                 displayField: "name",
                 valueField: "id",
                 itemId: "assigneduserid",
                 queryMode: "remote"
              }, {
                 xtype: "teamfield",
                 hidden: "undefined" == typeof SUGAR.App,
                 store: {
                    type: "KReportDesignerTeams",
                    storeId: "KReportDesignerTeamsStore"
                 },
                 displayField: "name",
                 valueField: "id",
                 itemId: "assignedteamid"
              }, {
                 xtype: "korgfield",
                 hidden: !0,
                 store: {
                    type: "KReportDesignerKOrgs",
                    storeId: "KReportDesignerKOrgsStore"
                 },
                 displayField: "name",
                 valueField: "id",
                 itemId: "assignedkorgid"
              }, "-", {
                 text: languageGetText( "LBL_REPORT_OPTIONS" ),
                 icon: "modules/KReports/images/tools.png",
                 handler: function () {
                    var a = this.up( "toolbar" );
                    a.optionsWindow || (a.optionsWindow = Ext.create( "SpiceCRM.KReporter.Designer.window.KReportDetails.reportOptions" )),
                            a.optionsWindow.show()
                 }
              }, "->", {
                 xtype: "tbtext",
                 itemId: "repVersion",
                 text: atob( SpiceCRM.KReporter.versionstring ),
                 style: {
                    fontWeight: "bold",
                    fontStyle: "italic"
                 }
              }, {
                 xtype: "tbitem",
                 html: atob( SpiceCRM.KReporter.icon1string ) + "&nbsp;" + atob( SpiceCRM.KReporter.icon2string ),
                 style: {
                    display: "inline-flex"
                 }
              } ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.view.SubPanelFields.FieldListGrid", {
           extend: "Ext.grid.Panel",
           alias: [ "widget.KreportDesignerFields.FieldListGrid" ],
           requires: [ "SpiceCRM.KReporter.Designer.controller.SubPanelFieldlistController" ],
           controller: "KReportDesigner.FieldllistController",
           store: Ext.create( "SpiceCRM.KReporter.Designer.store.fields", {
              storeId: "KreportDesignerFieldListStore"
           } ),
           flex: 1,
           height: "100%",
           columns: [ {
                 text: languageGetText( "LBL_FIELD" ), // Mint
                 dataIndex: "name",
                 flex: 1
              }, {
                 text: languageGetText( "LBL_FIELDNAME" ), // Mint
                 dataIndex: "text",
                 flex: 1
              } ],
           viewConfig: {
              scrollable: !0,
              stripeRows: !0,
              copy: !0,
              plugins: {
                 ptype: "gridviewdragdrop",
                 dragGroup: "designerfields",
                 enableDrop: !1
              }
           },
           tbar: [ {
                 xtype: "textfield",
                 itemId: "fieldFilter",
                 width: "100%"
              } ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.view.moduleSelector", {
           extend: "Ext.Panel",
           alias: [ "widget.moduleSelector" ],
           layout: "vbox",
           border: !1,
           items: [ {
                 xtype: "panel",
                 layout: "accordion",
                 width: "100%",
                 margin: "0 0 0 0",
                 flex: 2,
                 padding: 0,
                 items: [ {
                       xtype: "ModuleTree"
                    }, {
                       xtype: "UnionTree"
                    } ],
                 split: !0
              }, {
                 xtype: "KreportDesignerFields.FieldListGrid",
                 width: "100%",
                 flex: 2,
                 margin: "0 0 0 0",
                 split: !0
              } ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.view.SubPanelFields.ModuleTreeGrid", {
           extend: "Ext.tree.Panel",
           alias: [ "widget.ModuleTree" ],
           requires: [ "SpiceCRM.KReporter.Designer.controller.ModuleTreeController" ],
           itemId: "ModuleTreeGrid",
           controller: "ModuleTreeController",
           title: languageGetText( "LBL_MODULES" ), // Mint
           store: Ext.create( "SpiceCRM.KReporter.Designer.store.moduletree", {
              storeId: "moduletree"
           } ),
           flex: 1,
           height: "100%",
           width: "100%",
           border: !1,
           columns: [ {
                 xtype: "treecolumn",
                 text: languageGetText( "LBL_LIST_MODULE" ), // Mint
                 dataIndex: "module",
                 flex: 1
              }, {
                 text: languageGetText( "LBL_NAME" ), // Mint
                 dataIndex: "name",
                 flex: 1,
                 hidden: !0
              }, {
                 text: languageGetText( "LBL_LINK_LINKTYPE" ), // Mint
                 dataIndex: "link",
                 flex: 1,
                 hidden: !0
              } ],
           dockedItems: [ {
                 xtype: "toolbar",
                 dock: "top",
                 items: [ {
                       xtype: "combo",
                       width: "100%",
                       store: Ext.create( "SpiceCRM.KReporter.Designer.store.modules", {
                          storeId: "KReportDesignerModuleStore"
                       } ),
                       displayField: "name",
                       valueField: "module",
                       text: "modules",
                       forceSelection: !0,
                       itemId: "mainModuleSelector",
                       editable: !0,
                       selectOnFocus: !0,
                       typeAhead: !0,
                       minChars: 3,
                       allowBlank: !1,
                       emptyText: languageGetText( "LBL_SELECT_MODULE" ),
                       triggerAction: "all",
                       valuePublishEvent: "select",
                       queryMode: "local"
                    } ]
              } ],
           bufferedRenderer: !1,
           rootVisible: !1,
           root: {
              name: "mainrootnode",
              expanded: !1,
              id: "mainrootnode"
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.view.PresentationContainer", {
           extend: "Ext.panel.Panel",
           itemId: "presentationPluginContainer",
           alias: [ "widget.KReportDetails.PresentationContainer" ],
           controller: "KReportDesigner.PresentationContainerController",
           items: [ ],
           width: "100%",
           layout: "fit",
           border: !1,
           dockedItems: [ {
                 xtype: "toolbar",
                 dock: "top",
                 defaults: {
                    padding: "5px 2px"
                 },
                 items: [ {
                       xtype: "combobox",
                       itemId: "presentationPluginCombo",
                       fieldLabel: languageGetText( "LBL_PRESENTATION_PLUGIN" ),
                       triggerAction: "all",
                       width: 300,
                       labelWidth: 80,
                       lazyRender: !1,
                       mode: "local",
                       store: Ext.create( "SpiceCRM.KReporter.Designer.store.plugins", {
                          storeId: "KReportDesignerPresentationPluginsStore"
                       } ),
                       valueField: "id",
                       displayField: "name"
                    } ]
              } ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.view.KReportDetails.unionListFieldsGrid", {
           extend: "Ext.grid.Panel",
           require: [ "SpiceCRM.KReporter.Designer.controller.unionListFieldsController" ],
           controller: "KReportDesignerUnionListFieldsController",
           alias: [ "widget.KReportDetails.unionListFieldsGrid" ],
           hidden: !0,
           itemId: "KReportDetailsUnionListFieldsGrid",
           store: Ext.create( "SpiceCRM.KReporter.Designer.store.unionListFields", {
              storeId: "KReportDesignerUnionListFieldsStore"
           } ),
           title: languageGetText( "LBL_UNIONLISTFIELDS" ), // Mint
           flex: 3,
           height: "100%",
           columns: [ {
                 text: languageGetText( "LBL_PATH" ),
                 dataIndex: "displaypath",
                 width: 150,
                 sortable: !1
              }, {
                 text: languageGetText( "LBL_NAME" ),
                 dataIndex: "fieldid",
                 sortable: !1,
                 width: 100,
                 renderer: function ( a ) {
                    if ( a ) {
                       var b = Ext.data.StoreManager.lookup( "KReportDesignerListFieldsStore" ).findRecord( "fieldid", a );
                       if ( b )
                          return b.get( "name" )
                    }
                    return a
                 }
              }, {
                 text: "unionfieldpath",
                 dataIndex: "unionfieldpath",
                 width: 100
              }, {
                 text: languageGetText( "LBL_UNIONFIELDDISPLAYPATH" ),
                 dataIndex: "unionfielddisplaypath",
                 width: 150,
                 sortable: !1
              }, {
                 text: languageGetText( "LBL_UNIONFIELDNAME" ),
                 dataIndex: "unionfieldname",
                 width: 100,
                 sortable: !1
              }, {
                 text: languageGetText( "LBL_FIXEDVALUE" ),
                 dataIndex: "fixedvalue",
                 sortable: !1,
                 width: 80,
                 editor: {
                    xtype: "textfield"
                 }
              } ],
           viewConfig: {
              markDirty: !1,
              stripeRows: !0,
              plugins: {
                 ptype: "gridviewdragdrop",
                 dropGroup: "designerfields",
                 enableDrag: !1
              },
              listeners: {
                 beforedrop: "onBeforeDrop",
                 scope: "controller"
              }
           },
           plugins: [ Ext.create( "Ext.grid.plugin.CellEditing", {
                 clicksToEdit: 1
              } ) ],
           sm: new Ext.selection.RowModel
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.view.SubPanelFields.UnionTree", {
           extend: "Ext.tree.Panel",
           alias: [ "widget.UnionTree" ],
           disabled: !0,
           requires: [ "SpiceCRM.KReporter.Designer.controller.UnionTreeController" ],
           itemId: "UnionTree",
           controller: "UnionTreeController",
           title: languageGetText( "LBL_UNIONTREE" ), // Mint
           store: Ext.create( "SpiceCRM.KReporter.Designer.store.unionModules", {
              storeId: "kreportdesginerUnionModules"
           } ),
           flex: 1,
           height: "100%",
           width: "100%",
           columns: [ {
                 xtype: "treecolumn",
                 text: languageGetText( "LBL_LIST_MODULE" ), // Mint
                 dataIndex: "module",
                 flex: 1
              }, {
                 text: languageGetText( "LBL_NAME" ), // Mint
                 dataIndex: "name",
                 flex: 1,
                 hidden: !0
              }, {
                 text: languageGetText( "LBL_LINK_LINKTYPE" ), // Mint
                 dataIndex: "link",
                 flex: 1,
                 hidden: !0
              } ],
           dockedItems: [ {
                 xtype: "toolbar",
                 dock: "top",
                 items: [ {
                       xtype: "combo",
                       store: Ext.create( "SpiceCRM.KReporter.Designer.store.modules", {
                          storeId: "KReportDesignerModuleStore"
                       } ),
                       itemId: "unionModuleSelector",
                       displayField: "name",
                       valueField: "module",
                       text: "modules",
                       queryMode: "local",
                       emptyText: languageGetText( "LBL_SELECT_MODULE" )
                    }, {
                       xtype: "button",
                       icon: "modules/KReports/images/add.png",
                       disabled: !0,
                       itemId: "unionModuleAddButton"
                    }, {
                       xtype: "button",
                       icon: "modules/KReports/images/delete.png",
                       disabled: !0,
                       itemId: "unionModuleDelButton"
                    } ]
              } ],
           bufferedRenderer: !1,
           rootVisible: !1,
           root: {
              name: languageGetText( "LBL_ROOTNODE" ), // Mint
              expanded: !0
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.view.VisualizationContainer", {
           extend: "Ext.panel.Panel",
           itemId: "KReportDesginerVisualizationPluginContainer",
           alias: [ "widget.KReportDetails.VisualizationContainer" ],
           controller: "KReportDesigner.VisualizationContainerController",
           items: [ ],
           width: "100%",
           layout: "fit",
           dockedItems: [ {
                 xtype: "toolbar",
                 dock: "top",
                 defaults: {
                    padding: "5px 2px"
                 },
                 items: [ {
                       xtype: "combobox",
                       itemId: "layoutCombo",
                       store: Ext.create( "SpiceCRM.KReporter.Designer.store.layouts", "kreportdesignerLayoutsStore" ).load(),
                       valueField: "name",
                       displayField: "name",
                       width: 170,
                       labelWidth: 70,
                       fieldLabel: languageGetText( "LBL_VISUALIZATIONTOOLBAR_LAYOUT" ),
                       editable: !1,
                       queryMode: "local",
                       triggerAction: "all",
                       value: "-"
                    }, {
                       xtype: "tbseparator"
                    }, {
                       xtype: "spinnerfield",
                       itemId: "vizHeightSpinner",
                       value: 300,
                       max: 1e3,
                       step: 50,
                       min: 100,
                       editable: !1,
                       width: 150,
                       labelWidth: 70,
                       fieldLabel: languageGetText( "LBL_VISUALIZATION_HEIGHT" ),
                       disabled: !0,
                       onSpinUp: function () {
                          var a = this;
                          if ( !a.readOnly ) {
                             var b = parseInt( a.getValue() );
                             b == a.max ? a.setValue( a.min ) : a.setValue( b + a.step )
                          }
                       },
                       onSpinDown: function () {
                          var a = this;
                          if ( !a.readOnly ) {
                             var b = parseInt( a.getValue() );
                             b == a.min ? a.setValue( a.max ) : a.setValue( b - a.step )
                          }
                       }
                    }, {
                       xtype: "spinnerfield",
                       itemId: "chartSpinner",
                       value: 0,
                       max: 0,
                       editable: !1,
                       width: 150,
                       labelWidth: 50,
                       fieldLabel: languageGetText( "LBL_CHART_LABEL" ), // Mint
                       disabled: !0,
                       setMaxValue: function ( a ) {
                          this.max = parseInt( a ),
                                  parseInt( this.getValue() ) > parseInt( a ) && (this.setValue( a ),
                                  this.up( "panel" ).getController().switchPanelToIndex( a )),
                                  0 === parseInt( this.getValue() ) && a > 0 && (this.setValue( 1 ),
                                  this.up( "panel" ).getController().switchPanelToIndex( 1 ))
                       }
                    }, {
                       xtype: "combobox",
                       itemId: "visualizationPluginCombo",
                       fieldLabel: languageGetText( "LBL_PRESENTATION_PLUGIN" ),
                       triggerAction: "all",
                       width: 300,
                       disabled: !0,
                       labelWidth: 80,
                       lazyRender: !1,
                       mode: "local",
                       editable: !1,
                       store: Ext.create( "SpiceCRM.KReporter.Designer.store.plugins", {
                          storeId: "KReportDesignerVisualizationPluginsStore"
                       } ),
                       valueField: "id",
                       displayField: "name"
                    } ]
              } ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.view.KReportDetails.WhereClausesGrid", {
           extend: "Ext.grid.Panel",
           itemId: "KReportDetailsWhereClausesGrid",
           controller: "KReportDesignerWhereClauseController",
           alias: [ "widget.KReportDetails.WhereClauseGrid" ],
           store: Ext.create( "SpiceCRM.KReporter.Designer.store.whereclauses", {
              storeId: "KReportDesignerWhereClausesStore"
           } ),
           flex: 3,
           width: "100%",
           columns: [ {
                 text: languageGetText( "LBL_PATH" ),
                 readOnly: !0,
                 dataIndex: "displaypath",
                 width: 150,
                 sortable: !0,
                 hidden: !1
              }, {
                 text: languageGetText( "LBL_FULLPATH" ),
                 readOnly: !0,
                 dataIndex: "path",
                 width: 150,
                 sortable: !0,
                 hidden: !0
              }, {
                 text: languageGetText( "LBL_SEQUENCE" ),
                 dataIndex: "sequence",
                 sortable: !0,
                 editor: new Ext.form.TextField,
                 width: 70
              }, {
                 text: languageGetText( "LBL_EXPORTPDF" ),
                 dataIndex: "exportpdf",
                 sortable: !0,
                 hidden: !0,
                 renderer: function ( a, b, c, d, e, f ) {
                    return "" !== a ? languageGetText( "LBL_UEOPTION_" + a.toUpperCase() ) : a
                 },
                 editor: new SpiceCRM.KReporter.kcombo( {
                    typeAhead: !0,
                    triggerAction: "all",
                    mode: "local",
                    store: new Ext.data.ArrayStore( {
                       fields: [ "value", "text" ],
                       data: [ [ "no", languageGetText( "LBL_UEOPTION_NO" ) ], [ "yes", languageGetText( "LBL_UEOPTION_YES" ) ] ]
                    } ),
                    displayField: "text",
                    valueField: "value"
                 } ),
                 width: 60
              }, {
                 text: languageGetText( "LBL_NAME" ),
                 dataIndex: "name",
                 sortable: !0,
                 editor: new Ext.form.TextField,
                 width: 150
              }, {
                 text: languageGetText( "LBL_FIXEDVALUE" ),
                 dataIndex: "fixedvalue",
                 sortable: !0,
                 editor: new Ext.form.TextField,
                 width: 150
              }, {
                 itemId: "type",
                 text: languageGetText( "LBL_TYPE" ),
                 dataIndex: "type",
                 sortable: !1,
                 hidden: !0,
                 width: 50
              }, {
                 text: languageGetText( "LBL_GROUPING" ),
                 readOnly: !0,
                 dataIndex: "grouping",
                 width: 150,
                 sortable: !1,
                 // Mint start #43804
                 hidden: true,
                 //hidden: !1,
                 // Mint end #43804
                 editor: {
                    xtype: "kcombo",
                    store: Ext.create( "SpiceCRM.KReporter.Designer.store.buckets", {
                       storeId: "KReportDesignerBucketsWhereStore"
                    } ),
                    listConfig: {
                       minWidth: 150
                    },
                    typeAhead: !0,
                    triggerAction: "all",
                    mode: "local",
                    valueField: "id",
                    displayField: "name"
                 },
                 renderer: function ( a ) {
                    if ( a ) {
                       var b = Ext.data.StoreManager.lookup( "KReportDesignerBucketsWhereStore" );
                       return b.getById( a ).get( "name" )
                    }
                    return a
                 }
              }, {
                 itemId: "operator",
                 text: languageGetText( "LBL_OPERATOR" ),
                 readOnly: !0,
                 dataIndex: "operator",
                 width: 200,
                 sortable: !0,
                 hidden: !1,
                 renderer: function ( a, b, c, d, e, f ) {
                    return languageGetText( "LBL_OP_" + a.toUpperCase() )
                 },
                 editor: new Ext.form.TextField
              }, {
                 itemId: "value",
                 text: languageGetText( "LBL_VALUE_FROM" ),
                 dataIndex: "value",
                 sortable: !0,
                 hidden: !1,
                 width: 200,
                 editor: new Ext.form.TextField
              }, {
                 itemId: "valueto",
                 text: languageGetText( "LBL_VALUE_TO" ),
                 dataIndex: "valueto",
                 sortable: !0,
                 hidden: !1,
                 width: 200,
                 editor: new Ext.form.TextField
              }, {
                 text: languageGetText( "LBL_JOIN_TYPE" ),
                 readOnly: !0,
                 dataIndex: "jointype",
                 width: 80,
                 sortable: !0,
                 hidden: !1,
                 renderer: function ( a, b, c, d, e, f ) {
                    // Mint start #43804
                    var label_map = {
                       required: languageGetText( "LBL_JT_REQUIRED" ),
                       notexisting: languageGetText( "LBL_FILTER_NOTEXISTING" ),
                       optional: languageGetText( "LBL_JT_OPTIONAL" ),
                    };
                    return (typeof label_map[a] !== 'undefined') ? label_map[a] : a;
                    // return a
                    // Mint end #43804
                 },
                 editor: new SpiceCRM.KReporter.kcombo( {
                    typeAhead: !0,
                    triggerAction: "all",
                    mode: "local",
                    store: new Ext.data.ArrayStore( {
                       fields: [ "jointype", "text" ],
                       data: [ [
                             "required",
                             languageGetText( "LBL_JT_REQUIRED" )
                          ], [
                             /* Mint start #43804
                              "notexisting",
                              languageGetText( "LBL_FILTER_NOTEXISTING" )
                              ], [ 
                              Mint end #43804 */
                             "optional",
                             languageGetText( "LBL_JT_OPTIONAL" )
                          ] ]
                    } ),
                    displayField: "text",
                    valueField: "jointype"
                 } )
              }, {
                 text: languageGetText( "LBL_USEREDITABLE" ),
                 readOnly: !0,
                 dataIndex: "usereditable",
                 width: 80,
                 sortable: !0,
                 hidden: !1,
                 renderer: function ( a, b, c, d, e, f ) {
                    return languageGetText( "LBL_UEOPTION_" + a.toUpperCase() )
                 },
                 editor: new SpiceCRM.KReporter.kcombo( {
                    typeAhead: !0,
                    triggerAction: "all",
                    mode: "local",
                    store: new Ext.data.ArrayStore( {
                       fields: [ "value", "text" ],
                       data: [ [ "yes", languageGetText( "LBL_UEOPTION_YES" ) ], [ "yfo", languageGetText( "LBL_UEOPTION_YFO" ) ], /* Mint start #43804 [ "yo1", languageGetText( "LBL_UEOPTION_YO1" ) ], [ "yo2", languageGetText( "LBL_UEOPTION_YO2" ) ], Mint end #43804 */ [ "no", languageGetText( "LBL_UEOPTION_NO" ) ] ]
                    } ),
                    displayField: "text",
                    valueField: "value"
                 } )
              }, {
                 text: languageGetText( "LBL_DASHLETEDITABLE" ),
                 readOnly: !0,
                 hidden: !0,
                 dataIndex: "dashleteditable",
                 width: 50,
                 sortable: !0,
                 renderer: function ( a, b, c, d, e, f ) {
                    return languageGetText( "LBL_DEOPTION_" + a.toUpperCase() )
                 },
                 editor: new SpiceCRM.KReporter.kcombo( {
                    typeAhead: !0,
                    triggerAction: "all",
                    mode: "local",
                    store: new Ext.data.ArrayStore( {
                       fields: [ "value", "text" ],
                       data: [ [ "yes", languageGetText( "LBL_DEOPTION_YES" ) ], [ "no", languageGetText( "LBL_DEOPTION_NO" ) ] ]
                    } ),
                    displayField: "text",
                    valueField: "value"
                 } )
              }, {
                 text: languageGetText( "LBL_QUERYCONTEXT" ),
                 dataIndex: "context",
                 sortable: !0,
                 hidden: !1,
                 width: 150,
                 editor: new Ext.form.TextField
              }, {
                 text: languageGetText( "LBL_QUERYREFERENCE" ),
                 dataIndex: "reference",
                 sortable: !0,
                 hidden: !1,
                 width: 150,
                 editor: new Ext.form.TextField
              } ],
           sm: new Ext.selection.RowModel,
           viewConfig: {
              markDirty: !1,
              stripeRows: !0,
              plugins: {
                 ptype: "gridviewdragdrop",
                 dropGroup: "designerfields",
                 enableDrag: !0
              },
              listeners: {
                 beforedrop: "onBeforeDrop",
                 scope: "controller"
              }
           },
           plugins: [ Ext.create( "Ext.grid.plugin.CellEditing", {
                 clicksToEdit: 1
              } ) ],
           tbar: [ {
                 xtype: "button",
                 text: languageGetText( "LBL_DELETE_BUTTON_LABEL" ),
                 itemId: "WhereClauseDeleteButton",
                 disabled: !0
              } ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.view.WhereContainer", {
           extend: "Ext.panel.Panel",
           itemId: "whereContainer",
           alias: [ "widget.KReportDetails.WhereContainer" ],
           width: "100%",
           heigth: "100%",
           layout: "vbox",
           items: [ {
                 xtype: "WhereTreeGrid",
                 split: !0
              }, {
                 xtype: "KReportDetails.WhereClauseGrid",
                 split: !0
              } ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Designer.view.SubPanelFields.WhereTree", {
           extend: "Ext.tree.Panel",
           alias: [ "widget.WhereTreeGrid" ],
           requires: [ "SpiceCRM.KReporter.Designer.controller.ModuleTreeController" ],
           itemId: "WhereTreeGrid",
           border: !1,
           controller: "KReportDesignerWhereTreeController",
           store: Ext.create( "SpiceCRM.KReporter.Designer.store.wheretreeitems", {
              storeId: "KReportDesignerWhereTreeStore"
           } ),
           flex: 1,
           width: "100%",
           plugins: [ Ext.create( "Ext.grid.plugin.CellEditing", {
                 clicksToEdit: 1
              } ) ],
           viewConfig: {
              markDirty: !1
           },
           columns: {
              items: [ {
                    xtype: "treecolumn",
                    text: languageGetText( "LBL_GROUP" ), // Mint
                    dataIndex: "group"
                 }, {
                    text: languageGetText( "LBL_LOGIC_FUNCTION" ), // Mint
                    dataIndex: "type",
                    sortable: !1,
                    editor: {
                       xtype: "kcombo",
                       editable: !0,
                       triggerAction: "all",
                       itemId: "WhereGroupOperator",
                       mode: "local",
                       store: new Ext.data.ArrayStore( {
                          fields: [ "value", "text" ],
                          data: [ [ "AND", languageGetText( "LBL_AND" ) ], [ "OR", languageGetText( "LBL_OR" ) ] ] // Mint
                       } ),
                       valueField: "value",
                       displayField: "text"
                    },
                    // Mint start
                    renderer: function ( a ) {
                       return void 0 !== a ? languageGetText( "LBL_" + a.toUpperCase() ) : a
                    }
                    // Mint end
                 } ],
              defaults: {
                 flex: 1
              }
           },
           dockedItems: [ ],
           bufferedRenderer: !1,
           rootVisible: !0,
           root: {
              group: "root",
              type: "AND"
           },
           tbar: [ {
                 xtype: "button",
                 text: languageGetText( "LBL_ADD_BUTTON_LABEL" ),
                 itemId: "WhereGroupAddButton"
              }, {
                 xtype: "button",
                 text: languageGetText( "LBL_DELETE_BUTTON_LABEL" ),
                 itemId: "WhereGroupDeleteButton",
                 disabled: !0
              } ],
           setRoot: function ( a ) {
              var b = this.getRootNode();
              b.removeAll(),
                      b.set( "id", a ),
                      b.set( "groupid", a );
              var c = Ext.data.StoreManager.lookup( "KReportDesignerWhereGroupsStore" );
              c.buildTree( b, a ),
                      this.setSelection( b ),
                      b.expand( !0 )
           }
        } ),
        Ext.enableAriaButtons = !1,
        Ext.define( "SpiceCRM.KReporter.Designer.Application", {
           namespaces: [ "SpiceCRM.KReporter.Designer" ],
           controllers: [ "Application" ],
           extend: "Ext.app.Application",
           name: "SpiceCRM.KReporter.Designer",
           reportRecord: Ext.create( "SpiceCRM.KReporter.Designer.model.KReporterRecord" ),
           thisMainView: !1,
           launch: function () {
              SpiceCRM.KReporter.Designer.Application = this,
                      SpiceCRM.KReporter.Designer.Application.designMode = !0,
                      SpiceCRM.KReporter.designMode = !0,
                      this.thisMainView && this.destroyMainView(),
                      SpiceCRM.KReporter.Common.getConfig();
              var a = Ext.create( "SpiceCRM.KReporter.Designer.store.wheregroups", {
                 storeId: "KReportDesignerWhereGroupsStore"
              } )
                      , b = "";
              if ( window.thisKreportRecord ? "new" !== window.thisKreportRecord && (b = window.thisKreportRecord) : "" === b && $( "#EditView" )[0] && $( "#EditView" )[0].record.value && (b = $( "#EditView" )[0].record.value),
                      Ext.data.StoreManager.lookup( "KreportDesignerFieldListStore" ).removeAll(),
                      Ext.data.StoreManager.lookup( "KreportDesignerFieldListStore" ).clearFilter(),
                      Ext.create( "SpiceCRM.KReporter.Designer.store.dlists" ),
                      SpiceCRM.KReporter.Designer.view.KReportDetails.editorWindow = Ext.create( "SpiceCRM.KReporter.Designer.view.editorWindow", {} ),
                      b )
                 Ext.Ajax.request( {
                    url: "KREST/module/KReports/" + b,
                    method: "GET",
                    success: function ( b, c ) {
                       var d = Ext.decode( b.responseText );
                       SpiceCRM.KReporter.Designer.Application.reportRecord = Ext.create( "SpiceCRM.KReporter.Designer.model.KReporterRecord", d ),
                               this.render();
                       var e = Ext.data.StoreManager.lookup( "KReportDesignerListFieldsStore" );
                       e.removeAll(),
                               e.addFromReportRecord( Ext.decode( Ext.util.Format.htmlDecode( d.listfields ) ) );
                       var f = Ext.data.StoreManager.lookup( "KReportDesignerWhereClausesStore" );
                       if ( f.removeAll(),
                               f.addFromReportRecord( Ext.decode( Ext.util.Format.htmlDecode( d.whereconditions ) ) ),
                               a.addFromReportRecord( Ext.decode( Ext.util.Format.htmlDecode( d.wheregroups ) ) ),
                               "" !== d.union_modules ) {
                          var g = Ext.ComponentQuery.query( "UnionTree" )[0];
                          Ext.each( Ext.decode( Ext.util.Format.htmlDecode( d.union_modules ) ), function ( a ) {
                             g.getRootNode().appendChild( {
                                id: a.unionid,
                                unionid: a.unionid,
                                module: a.module,
                                path: "union" + a.unionid + ":" + a.module,
                                selected: !0,
                                draggable: !1
                             } ),
                                     g.getRootNode().expand()
                          } ),
                                  _unionFieldStore = Ext.data.StoreManager.lookup( "KReportDesignerUnionListFieldsStore" ),
                                  _unionFieldStore.removeAll(),
                                  _unionFieldStore.add( Ext.decode( Ext.util.Format.htmlDecode( d.unionlistfields ) ) )
                       }
                       var h = Ext.ComponentQuery.query( "#WhereTreeGrid" )[0];
                       h.setRoot( "root" ),
                               d.report_module && Ext.ComponentQuery.query( "#mainModuleSelector" )[0].fireEvent( "initialize", Ext.ComponentQuery.query( "#mainModuleSelector" )[0], d.report_module ),
                               this.loadPlugins()
                    },
                    failure: function ( a, b ) {
                       console.log( "server-side failure with status code " + a.status )
                    },
                    scope: this
                 } );
              else {
                 a.add( {
                    unionid: "root",
                    id: "root",
                    groupid: "root",
                    type: "AND",
                    parent: "-",
                    notexists: ""
                 } ),
                         SpiceCRM.KReporter.Designer.Application.reportRecord = Ext.create( "SpiceCRM.KReporter.Designer.model.KReporterRecord" ),
                         SpiceCRM.KReporter.Designer.Application.reportRecord.set( "id", Ext.create( Ext.data.identifier.Uuid ).generate() ),
                         SpiceCRM.KReporter.Designer.Application.reportRecord.set( "name", languageGetText( "LBL_DEFAULT_NAME" ) ), // Mint
                         SpiceCRM.KReporter.Designer.Application.reportRecord.set( "newWithId", !0 ),
                         this.render(),
                         Ext.data.StoreManager.lookup( "KReportDesignerListFieldsStore" ).removeAll(),
                         Ext.data.StoreManager.lookup( "KReportDesignerWhereClausesStore" ).removeAll(),
                         Ext.data.StoreManager.lookup( "KReportDesignerUnionListFieldsStore" ).removeAll();
                 var c = Ext.ComponentQuery.query( "#WhereTreeGrid" )[0];
                 c.setRoot( "root" ),
                         this.loadPlugins()
              }
           },
           destroyMainView: function () {
              this.thisMainView.destroy(),
                      this.thisMainView = !1,
                      this.reportRecord = Ext.create( "SpiceCRM.KReporter.Designer.model.KReporterRecord" )
           },
           render: function () {
              this.thisMainView = Ext.create( "SpiceCRM.KReporter.Designer.view.main.Main" );
              // Mint start ref #41887 Podnieść obszar edycji raportu
              $( 'div.moduleTitle' ).hide();
              // Mint end ref #41887 Podnieść obszar edycji raportu
              Ext.get( window ).on( {
                 resize: function () {
                    SpiceCRM.KReporter.Designer.Application.thisMainView && SpiceCRM.KReporter.Designer.Application.thisMainView.updateLayout()
                 }
              } )
           },
           loadPlugins: function () {
              Ext.Ajax.request( {
                 url: "KREST/KReporter/plugins",
                 method: "GET",
                 params: {
                    addData: Ext.encode( [ "sysinfo" ] )
                 },
                 success: function ( a, b ) {
                    var c = Ext.decode( a.responseText );
                    c.addData && c.addData.sysinfo && (this.sysinfo = c.addData.sysinfo,
                            Ext.globalEvents.fireEvent( "designersysinfo", this.sysinfo, c )),
                            this.presentationPluginsStore = Ext.data.StoreManager.lookup( "KReportDesignerPresentationPluginsStore" ),
                            this.presentationPluginsStore.removeAll(),
                            Ext.Object.each( c.presentation, function ( a, b ) {
                               b.metadata.includes.edit && this.presentationPluginsStore.add( {
                                  id: a,
                                  name: languageGetText( b.displayname ),
                                  panel: b.metadata.pluginpanel,
                                  loaded: !1,
                                  plugindirectory: b.plugindirectory,
                                  include: b.plugindirectory + "/" + b.metadata.includes.edit
                               } )
                            }, this ),
                            this.visualizationPluginsStore = Ext.data.StoreManager.lookup( "KReportDesignerVisualizationPluginsStore" ),
                            this.visualizationPluginsStore.removeAll(),
                            Ext.Object.each( c.visualization, function ( a, b ) {
                               this.visualizationPluginsStore.add( {
                                  id: a,
                                  name: languageGetText( b.displayname ),
                                  panel: b.metadata.pluginpanel,
                                  loaded: !1,
                                  plugindirectory: b.plugindirectory,
                                  include: b.plugindirectory + "/" + b.metadata.includes.edit
                               } )
                            }, this ),
                            this.integrationPluginsStore = Ext.data.StoreManager.lookup( "KReportDesignerIntegrationPluginsStore" ),
                            this.integrationPluginsStore.removeAll();
                    var d = {}
                    , e = Ext.util.Format.htmlDecode( SpiceCRM.KReporter.Designer.Application.reportRecord.get( "integration_params" ) );
                    e && "" !== e && (d = Ext.decode( e )),
                            Ext.Object.each( c.integration, function ( a, b ) {
                               this.integrationPluginsStore.add( {
                                  id: a,
                                  name: languageGetText( b.displayname ),
                                  panel: b.metadata && b.metadata.includes && b.metadata.includes.editPanel ? b.metadata.includes.editPanel : void 0,
                                  loaded: !1,
                                  active: !d.activePlugins || "1" !== d.activePlugins[a] && 1 != d.activePlugins[a] ? 0 : 1,
                                  plugindirectory: b.plugindirectory,
                                  include: b.metadata && b.metadata.includes && b.metadata.includes.edit ? b.plugindirectory + "/" + b.metadata.includes.edit : ""
                               } )
                            }, this ),
                            Ext.globalEvents.fireEvent( "designerPluginsLoaded" )
                 },
                 failure: function ( a, b ) {
                    console.log( "server-side failure with status code " + a.status )
                 },
                 scope: this
              } )
           },
           languageGetText: function ( a ) {
              return SUGAR.language.get( "KReports", a )
           },
           getRand: function () {
              return Math.random()
           },
           S4: function () {
              return (65536 * (1 + this.getRand()) | 0).toString( 16 ).substring( 1 )
           },
           kGuid: function () {
              return "k" + this.S4() + this.S4() + this.S4() + this.S4() + this.S4() + this.S4() + this.S4()
           },
           getReportId: function () {
              return this.reportRecord.get( "id" )
           }
        } ),
        Ext.application( {
           extend: "SpiceCRM.KReporter.Designer.Application"
        } ),
        Ext.onReady( function () {} );