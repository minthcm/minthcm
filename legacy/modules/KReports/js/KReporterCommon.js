/* * *******************************************************************************
 * This file is part of SpiceCRM FulltextSearch. SpiceCRM FulltextSearch is an enhancement developed
 * by aac services k.s.. All rights are (c) 2016 by aac services k.s.
 *
 * This Version of the SpiceCRM FulltextSearctitlh is licensed software and may only be used in
 * alignment with the License Agreement received with this Software.
 * This Software is copyrighted and may not be further distributed without
 * witten consent of aac services k.s.
 *
 * You can contact us at info@spicecrm.io
 ******************************************************************************* */
if ( Ext.tip.QuickTipManager.disable(),
        Ext.tip.QuickTipManager.destroy(),
        "undefined" == typeof cal_date_format )
   var cal_date_format = "%d.%m.%Y";
var languageGetText = function ( a ) {
   return "undefined" != typeof SUGAR.App ? SUGAR.App.lang.get( a, "KReports" ) : SUGAR.language.get( "KReports", a ) || a
};
Ext.define( "SpiceCRM.KReporter.Common.model.whereOperator", {
   extend: "Ext.data.Model",
   fields: [ "value", "text" ]
} ),
        Ext.define( "SpiceCRM.KReporter.Common.store.whereOperators", {
           extend: "Ext.data.Store",
           requires: [ "SpiceCRM.KReporter.Common.model.whereOperator" ],
           model: "SpiceCRM.KReporter.Common.model.whereOperator",
           proxy: {
              type: "ajax",
              url: "KREST/KReporter/core/whereoperators",
              extraParams: {
                 designer: !1,
                 path: "",
                 grouping: ""
              },
              reader: {
                 type: "json"
              }
           },
           autoLoad: !1,
           listeners: {
              load: function () {
                 this.add( {
                    operator: "parent_assign",
                    values: 1,
                    display: languageGetText( "LBL_OP_PARENT_ASSIGN" )
                 } )
              }
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Common.model.enumoption", {
           extend: "Ext.data.Model",
           fields: [ "value", "text" ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Common.store.enumoptions", {
           extend: "Ext.data.Store",
           requires: [ "SpiceCRM.KReporter.Common.model.enumoption" ],
           model: "SpiceCRM.KReporter.Common.model.enumoption",
           proxy: {
              type: "ajax",
              url: "KREST/KReporter/core/enumoptions",
              extraParams: {
                 path: ""
              },
              reader: {
                 type: "json"
              }
           }
        } ),
        Ext.define( "SpiceCRM.KReporter.Common.model.parentField", {
           extend: "Ext.data.Model",
           fields: [ "field", "description" ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Common.store.parentFields", {
           extend: "Ext.data.Store",
           model: "SpiceCRM.KReporter.Common.model.parentField",
           proxy: {
              type: "ajax",
              url: "KREST/KReporter/core/modulefields",
              extraParams: {
                 module: ""
              },
              reader: {
                 type: "json"
              }
           },
           autoLoad: !1
        } ),
        Ext.define( "SpiceCRM.KReporter.Common.model.autcompleterecord", {
           extend: "Ext.data.Model",
           fields: [ "itemid", "itemtext" ]
        } ),
        Ext.define( "SpiceCRM.KReporter.Common.store.autcompleterecords", {
           extend: "Ext.data.Store",
           requires: [ "SpiceCRM.KReporter.Common.model.autcompleterecord" ],
           model: "SpiceCRM.KReporter.Common.model.autcompleterecord",
           proxy: {
              type: "ajax",
              url: "KREST/KReporter/core/autocompletevalues",
              extraParams: {
                 path: ""
              },
              reader: {
                 type: "json",
                 rootProperty: "data",
                 totalProperty: "total"
              }
           },
           autoLoad: !1,
           listeners: {
              beforeload: function () {
                 if ( "" === this.getProxy().extraParams.path )
                    return !1
              }
           }
        } ),
        Ext.Ajax.on( "beforerequest", function ( a, b, c ) {
           "undefined" != typeof SUGAR.App && (b.headers || (b.headers = {}),
                   0 === b.url.indexOf( "KREST/KReporter" ) && "undefined" != typeof SUGAR.App.api && (b.url = b.url.replace( "KREST/KReporter", "rest/v10/KReports" ),
                   b.headers["OAuth-Token"] = SUGAR.App.api.getOAuthToken()),
                   0 === b.url.indexOf( "KREST" ) && "undefined" != typeof SUGAR.App.api && (b.url = b.url.replace( "KREST", "rest/v10/KREST" ),
                   b.headers["OAuth-Token"] = SUGAR.App.api.getOAuthToken()))
        } ),
        SpiceCRM.KReporter.timeformat = "h:iA",
        SpiceCRM.KReporter.dateformat = "m/d/Y",
        // Mint start
        SpiceCRM.KReporter.versionstring = "",
        SpiceCRM.KReporter.iconstring = "",
        SpiceCRM.KReporter.icon1string = "",
        SpiceCRM.KReporter.icon2string = "",
        // Mint end
        Ext.define( "SpiceCRM.KReporter.kcombo", {
           extend: "Ext.form.field.ComboBox",
           alias: "widget.kcombo",
           lazyRender: !1,
           listeners: {}
        } );
var _dateFormat = "Y-m-d";
"undefined" != typeof SUGAR.App && SUGAR.App.user.attributes.preferences && (_dateFormat = SUGAR.App.user.attributes.preferences.datepref),
        "undefined" != typeof cal_date_format && (_dateFormat = cal_date_format.replace( /%/g, "" )),
        Ext.define( "SpiceCRM.KReporter.common.window.datetimepicker", {
           extend: "Ext.window.Window",
           padding: 10,
           border: !1,
           event: null,
           title: languageGetText( "LBL_PICK_DATETIME" ),
           width: 450,
           modal: !0,
           closeAction: "hide",
           items: [ {
                 xtype: "datefield",
                 fieldLabel: "Date",
                 format: _dateFormat
              }, {
                 xtype: "timefield",
                 fieldLabel: "Time",
                 format: "H:i"
              } ],
           listeners: {
              show: function () {
                 var a = this.event.record.get( this.event.column.itemId + "key" );
                 "" !== a ? (this.down( "timefield" ).setValue( new Date( this.event.record.get( this.event.column.itemId + "key" ) ) ),
                         this.down( "datefield" ).setValue( new Date( this.event.record.get( this.event.column.itemId + "key" ) ) )) : (this.down( "timefield" ).setValue( "00:00" ),
                         this.down( "datefield" ).setValue( new Date ))
              },
              close: function () {
                 var a = "Y-m-d";
                 "undefined" != typeof SUGAR.App && (a = SUGAR.App.user.attributes.preferences.datepref),
                         "undefined" != typeof cal_date_format && (a = cal_date_format.replace( /%/g, "" ));
                 var b = this.down( "datefield" ).getValue()
                         , c = this.down( "timefield" ).getValue();
                 if ( null !== b && null !== c ) {
                    var d = new Date( b )
                            , e = new Date( c );
                    this.event.record.set( this.event.column.itemId + "key", Ext.Date.format( d, "Y-m-d" ) + " " + Ext.Date.format( e, "H:i:s" ) ),
                            this.event.record.set( this.event.column.itemId, Ext.Date.format( d, a ) + " " + Ext.Date.format( e, "H:i" ) )
                 }
              }
           }
        } ),
        SpiceCRM.KReporter.Common = {
           currencies: {
              "-99": {
                 symbol: "€"
              }
           },
           S4: function () {
              return (65536 * (1 + Math.random()) | 0).toString( 16 ).substring( 1 )
           },
           kGuid: function () {
              return "k" + this.S4() + this.S4() + this.S4() + this.S4() + this.S4() + this.S4() + this.S4()
           },
           redirect: function ( a, b ) {
              var c = "KReports";
              if ( b && b.module && (c = b.module),
                      "undefined" != typeof SUGAR.App )
                 switch ( a ) {
                    case "list":
                       location.assign( "#" + c );
                       break;
                    case "detail":
                       b.newtab ? window.open( "#" + c + "/" + b.id ) : location.assign( "#" + c + "/" + b.id );
                       break;
                    case "edit":
                       location.assign( "#" + c + "/" + b.id + "/layout/edit" )
                 }
              // Mint start #40250
              else if ( b && b.module && (c = b.module), "undefined" != typeof top.App ) {
                 switch ( a ) {
                    case "list":
                       top.location.assign( "index.php#" + c );
                       break;
                    case "detail":
                       b.newtab ? window.open( "index.php#" + c + "/" + b.id ) : top.location.assign( "index.php#" + c + "/" + b.id );
                       break;
                    case "edit":
                       top.location.assign( "index.php#" + c + "/" + b.id + "/edit" )
                 }
              }
              // Mint end #40250
              else
                 switch ( a ) {
                    case "list":
                       location.assign( "index.php?module=" + c );
                       break;
                    case "detail":
                       b.newtab ? window.open( "index.php?module=" + c + "&action=DetailView&record=" + b.id ) : location.assign( "index.php?module=" + c + "&action=DetailView&record=" + b.id );
                       break;
                    case "edit":
                       location.assign( "index.php?module=" + c + "&action=EditView&record=" + b.id )
                 }
           },
           download: function ( a ) {
              a = a || {};
              var b = a.url
                      , c = a.method || "POST"
                      , d = a.params || {};
              "undefined" != typeof SUGAR.App && (0 === b.indexOf( "KREST/KReporter" ) && (b = b.replace( "KREST/KReporter", "rest/v10/KReports" ),
                      b = b.replace( "action", "actionraw" )),
                      0 === b.indexOf( "KREST" ) && (b = b.replace( "KREST", "rest/v10/KREST" ),
                      b = b.replace( "action", "actionraw" )));
              var e = Ext.create( "Ext.form.Panel", {
                 standardSubmit: !0,
                 url: b,
                 method: c,
                 jsonSubmit: !0
              } );
              "undefined" != typeof SUGAR.App ? (d["OAuth-Token"] = SUGAR.App.api.getOAuthToken(),
                      e.submit( {
                         params: d,
                         headers: {
                            "OAuth-Token": SUGAR.App.api.getOAuthToken()
                         }
                      } )) : e.submit( {
                 params: d
              } ),
                      Ext.defer( function () {
                         e.close()
                      }, 100 )
           },
           downloadParamsToInputs: function ( a ) {
              var b = [ ];
              for ( var c in a ) {
                 var d = [ ].concat( a[c] );
                 Ext.each( d, function ( a ) {
                    b.push( SpiceCRM.KReporter.Common.downloadCreateInput( c, a ) )
                 } )
              }
              return b
           },
           downloadCreateInput: function ( a, b ) {
              return {
                 name: Ext.htmlEncode( a ),
                 tag: "input",
                 type: "hidden",
                 value: Ext.htmlEncode( b )
              }
           },
           downloadRemoveNode: function ( a ) {
              a.onload = null,
                      a.parentNode.removeChild( a )
           },
           gridSetEditor: function ( a, b, c ) {
              if ( "onoffswitch" == a.column.itemId && ("yo1" == a.record.data.usereditable || "yo2" == a.record.data.usereditable ? a.column.setEditor( new Ext.form.field.ComboBox( {
                 typeAhead: !0,
                 queryMode: "local",
                 store: new Ext.data.ArrayStore( {
                    id: 0,
                    fields: [ "value", "text" ],
                    data: [ [ "yo1", languageGetText( "LBL_ONOFF_YO1" ) ], [ "yo2", languageGetText( "LBL_ONOFF_YO2" ) ] ]
                 } ),
                 displayField: "text",
                 valueField: "value"
              } ) ) : a.column.setEditor( null )),
                      "operator" == a.column.itemId )
                 if ( "yes" == a.record.data.usereditable || SpiceCRM.KReporter.Designer.Application.designMode ) {
                    b.whereOperatorStore.currentPath === a.record.get( "path" ) && b.whereOperatorStore.grouping === a.record.get( "grouping" ) || (b.whereOperatorStore.removeAll(),
                            b.whereOperatorStore.getProxy().extraParams.path = a.record.data.path,
                            b.whereOperatorStore.getProxy().extraParams.designer = c.designMode,
                            b.whereOperatorStore.getProxy().extraParams.grouping = a.record.data.grouping,
                            b.whereOperatorStore.load()),
                            b.whereOperatorStore.currentPath = a.record.get( "path" ),
                            b.whereOperatorStore.grouping = a.record.get( "grouping" ),
                            b.enumOptionsStore.enumpath = null;
                    var d = new SpiceCRM.KReporter.kcombo( {
                       typeAhead: !0,
                       triggerAction: "all",
                       queryMode: "local",
                       editable: !0,
                       store: b.whereOperatorStore,
                       displayField: "display",
                       valueField: "operator"
                    } );
                    a.column.setEditor( d )
                 } else
                    a.column.setEditor( null );
              if ( "value" === a.column.itemId || "valueto" === a.column.itemId ) {
                 var f = b.getOperatorCount( a.record.get( "operator" ) );
                 if ( SpiceCRM.KReporter.designMode || "yo1" != a.record.get( "usereditable" ) && "yo2" != a.record.get( "usereditable" ) || (f = 0),
                         0 === f || "valueto" == a.column.id && 2 != f )
                    return a.cancel = !0,
                            !1;
                 switch ( a.record.get( "operator" ) ) {
                    case "autocomplete":
                       b.autocompleteStore.getProxy().extraParams.path = a.record.data.path,
                               a.column.setEditor( new Ext.form.ComboBox( {
                                  store: b.autocompleteStore,
                                  valueField: "itemid",
                                  displayField: "itemtext",
                                  typeAhead: !0,
                                  mode: "remote",
                                  pageSize: 25,
                                  listConfig: {
                                     minWidth: 250
                                  },
                                  minChars: 1,
                                  triggerAction: "all",
                                  forceSelection: !0,
                                  selectOnFocus: !0
                               } ) );
                       break;
                    case "parent_assign":
                       _paramsObj = {};
                       var g = !1
                               , h = Ext.util.Format.htmlDecode( SpiceCRM.KReporter.Designer.Application.reportRecord.get( "integration_params" ) );
                       if ( h && "" !== h && (_paramsObj = Ext.decode( h ),
                               void 0 === _paramsObj.activePlugins || void 0 === _paramsObj.activePlugins.kpublishing || "1" !== _paramsObj.activePlugins.kpublishing && 1 !== _paramsObj.activePlugins.kpublishing || (g = _paramsObj.kpublishing)),
                               g === !1 || void 0 === g.subpanelModule || "" === g.subpanelModule )
                          return a.cancel = !0,
                                  !1;
                       b.parentFieldsStore.removeAll(),
                               b.parentFieldsStore.getProxy().extraParams.module = g.subpanelModule,
                               b.parentFieldsStore.load(),
                               a.column.setEditor( new Ext.form.ComboBox( {
                                  store: b.parentFieldsStore,
                                  valueField: "field",
                                  displayField: "description",
                                  typeAhead: !0,
                                  queryMode: "local",
                                  triggerAction: "all",
                                  editable: !1,
                                  selectOnFocus: !0,
                                  listWidth: 200
                               } ) );
                       break;
                    case "function":
                       a.column.setEditor( new Ext.form.ComboBox( {
                          store: b.whereFunctionsStore,
                          valueField: "field",
                          displayField: "description",
                          queryMode: "local",
                          triggerAction: "all",
                          editable: !0,
                          selectOnFocus: !0,
                          listWidth: 200,
                          listeners: {
                             focus: function () {}
                          }
                       } ) );
                       break;
                    case "reference":
                       a.column.setEditor( new Ext.form.TextField );
                       break;
                    default:
                       // Mint start
                       var pathArray = a.record.data.path.split( "::" );
                       var fieldName;
                       for ( var i = pathArray.length - 1; i >= 0; i-- ) {
                          var fieldArray = pathArray[i].split( ":" );
                          if ( fieldArray[0] == "field" ) {
                             fieldName = fieldArray[1];
                             break;
                          }
                       }
                       if ( fieldName == 'first_name' || fieldName == 'last_name' ) {
                          a.record.data.type = "name2";
                       }
                       // Mint end
                       switch ( a.record.data.type ) {
                          case "datetimecombo":
                          case "datetime":
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
                                   a.column.setEditor( new Ext.form.NumberField );
                                   break;
                                case "lastnddays":
                                case "nextnddays":
                                case "betwnddays":
                                   K.kreports.EditView ? a.column.setEditor( new Ext.form.NumberField ) : a.column.setEditor( new Ext.form.DateField( {
                                      editable: !1,
                                      value: e.value,
                                      format: cal_date_format.replace( /%/g, "" )
                                   } ) );
                                   break;
                                default:
                                   if ( "date" !== a.record.data.type )
                                      return Ext.create( SpiceCRM.KReporter.common.window.datetimepicker, {
                                         event: a
                                      } ).show(),
                                              a.cancel = !0,
                                              !1;
                                   a.column.setEditor( new Ext.form.DateField( {
                                      editable: !1,
                                      format: cal_date_format.replace( /%/g, "" )
                                   } ) )
                             }
                             break;
                          case "user_name":
                          // Mint start
                          case "username":
                          case "name":
                          case "name2":
                          // Mint end
                          case "assigned_user_name":
                          case "enum":
                          case "parent_type":
                          case "radioenum":
                          case "multienum":
                             if ( "starts" == a.record.data.operator || "notstarts" == a.record.data.operator || "contains" == a.record.data.operator || "notcontains" == a.record.data.operator )
                                a.column.setEditor( new Ext.form.TextField );
                             else {
                                if ( b.enumOptionsStore.enumpath !== a.record.get( "path" ) || b.enumOptionsStore.enumgrouping !== a.record.get( "grouping" ) ) {
                                   b.enumOptionsStore.removeAll(),
                                           b.enumOptionsStore.getProxy().extraParams.path = a.record.get( "path" );
                                   var i = [ ];
                                   b.view.getStore().each( function ( a ) {
                                      i.push( {
                                         fieldid: a.get( "fieldid" ),
                                         path: a.get( "path" ),
                                         operator: a.get( "operator" ),
                                         value: a.get( "value" ),
                                         valuekey: a.get( "valuekey" ),
                                         valueto: a.get( "valueto" ),
                                         valuetokey: a.get( "valuetokey" )
                                      } )
                                   }, this ),
                                           b.enumOptionsStore.getProxy().extraParams.operators = Ext.encode( i ),
                                           b.enumOptionsStore.getProxy().extraParams.grouping = a.record.get( "grouping" ),
                                           b.enumOptionsStore.load()
                                }
                                switch ( b.enumOptionsStore.enumpath = a.record.get( "path" ),
                                        b.enumOptionsStore.enumgrouping = a.record.get( "grouping" ),
                                        a.record.data.operator ) {
                                   case "oneof":
                                   case "oneofnot":
                                   case "oneofnotornull":
                                      a.column.setEditor( new SpiceCRM.KReporter.kcombo( {
                                         editable: !1,
                                         triggerAction: "all",
                                         lazyRender: !1,
                                         multiSelect: !0,
                                         queryMode: "local",
                                         store: b.enumOptionsStore,
                                         displayField: "text",
                                         valueField: "value",
                                         listConfig: {
                                            minWidth: 200,
                                            resizable: !0
                                         },
                                         forceSelection: !1
                                      } ) );
                                      break;
                                   default:
                                      a.column.setEditor( new SpiceCRM.KReporter.kcombo( {
                                         forceSelection: !0,
                                         triggerAction: "all",
                                         multiSelect: !1,
                                         queryMode: "local",
                                         store: b.enumOptionsStore,
                                         displayField: "text",
                                         valueField: "value",
                                         // Mint start
                                         anyMatch: true,
                                         // Mint end
                                         listConfig: {
                                            minWidth: 200,
                                            resizable: !0
                                         }
                                      } ) )
                                }
                             }
                             break;
                          case "bool":
                             a.column.setEditor( new Ext.form.ComboBox( {
                                triggerAction: "all",
                                lazyRender: !0,
                                queryMode: "local",
                                store: new Ext.data.ArrayStore( {
                                   id: 0,
                                   fields: [ "value", "text" ],
                                   data: [ [ "1", languageGetText( "LBL_BOOL_1" ) ], [ "0", languageGetText( "LBL_BOOL_0" ) ] ]
                                } ),
                                displayField: "text",
                                valueField: "value"
                             } ) );
                             break;
                          default:
                          switch ( a.record.data.operator ) {
                             case "oneof":
                             case "oneofnot":
                             case "oneofnotornull":
                                b.enumOptionsStore.enumpath === a.record.get( "path" ) && b.enumOptionsStore.enumgrouping === a.record.get( "grouping" ) || (b.enumOptionsStore.removeAll(),
                                        b.enumOptionsStore.getProxy().extraParams.path = a.record.get( "path" ),
                                        b.enumOptionsStore.getProxy().extraParams.grouping = a.record.get( "grouping" ),
                                        b.enumOptionsStore.load()),
                                        b.enumOptionsStore.enumpath = a.record.get( "path" ),
                                        b.enumOptionsStore.enumgrouping = a.record.get( "grouping" ),
                                        a.column.setEditor( new SpiceCRM.KReporter.kcombo( {
                                           editable: !1,
                                           triggerAction: "all",
                                           lazyRender: !1,
                                           multiSelect: !0,
                                           queryMode: "local",
                                           selectOnFocus: !0,
                                           store: b.enumOptionsStore,
                                           displayField: "text",
                                           valueField: "value",
                                           listConfig: {
                                              minWidth: 200,
                                              resizable: !0
                                           },
                                           renderer: function () {}
                                        } ) );
                                break;
                             case "eqgrouped":
                                b.enumOptionsStore.enumpath === a.record.get( "path" ) && b.enumOptionsStore.enumgrouping === a.record.get( "grouping" ) || (b.enumOptionsStore.removeAll(),
                                        b.enumOptionsStore.getProxy().extraParams.path = a.record.get( "path" ),
                                        b.enumOptionsStore.getProxy().extraParams.grouping = a.record.get( "grouping" ),
                                        b.enumOptionsStore.load()),
                                        b.enumOptionsStore.enumpath = a.record.get( "path" ),
                                        b.enumOptionsStore.enumgrouping = a.record.get( "grouping" ),
                                        a.column.setEditor( new SpiceCRM.KReporter.kcombo( {
                                           forceSelection: !0,
                                           triggerAction: "all",
                                           multiSelect: !1,
                                           queryMode: "local",
                                           store: b.enumOptionsStore,
                                           displayField: "text",
                                           valueField: "value",
                                           listConfig: {
                                              minWidth: 200,
                                              resizable: !0
                                           }
                                        } ) );
                                break;
                             default:
                                a.column.setEditor( new Ext.form.TextField )
                          }
                       }
                 }
              }
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
                                case "betwndays":
                                case "nextnmonthDaily":
                                case "nextnfquarter":
                                case "nextnyear":
                                case "nextnyearDaily":
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
           get_user_datetime_format: function () {
              Ext.Ajax.request( {
                 url: "KREST/KReporter/user/datetimeformat",
                 method: "GET",
                 success: function ( a, b ) {
                    200 == Ext.decode( a.status ) && (_response = Ext.decode( a.responseText ),
                            SpiceCRM.KReporter.timeformat = _response.timef,
                            SpiceCRM.KReporter.dateformat = _response.datef)
                 },
                 listeners: {
                    beforerequest: function ( a, b, c ) {
                       "undefined" != typeof SUGAR.App && (b.headers || (b.headers = {}),
                               0 === b.url.indexOf( "KREST/KReporter" ) && "undefined" != typeof SUGAR.App.api && (b.url = b.url.replace( "KREST/KReporter", "rest/v10/KReports" ),
                               b.headers["OAuth-Token"] = SUGAR.App.api.getOAuthToken()),
                               0 === b.url.indexOf( "KREST" ) && "undefined" != typeof SUGAR.App.api && (b.url = b.url.replace( "KREST", "rest/v10/KREST" ),
                               b.headers["OAuth-Token"] = SUGAR.App.api.getOAuthToken()))
                    }
                 }
              } )
           },
           get_user_prefs: function () {
              Ext.Ajax.request( {
                 url: "KREST/KReporter/user/userprefs",
                 method: "GET",
                 success: function ( a, b ) {
                    200 == Ext.decode( a.status ) && (_response = Ext.decode( a.responseText ),
                            SpiceCRM.KReporter.timeformat = _response.timef,
                            SpiceCRM.KReporter.dateformat = _response.datef,
                            SpiceCRM.KReporter.precision = _response.precision)
                 },
                 listeners: {
                    beforerequest: function ( a, b, c ) {
                       "undefined" != typeof SUGAR.App && (b.headers || (b.headers = {}),
                               0 === b.url.indexOf( "KREST/KReporter" ) && "undefined" != typeof SUGAR.App.api && (b.url = b.url.replace( "KREST/KReporter", "rest/v10/KReports" ),
                               b.headers["OAuth-Token"] = SUGAR.App.api.getOAuthToken()),
                               0 === b.url.indexOf( "KREST" ) && "undefined" != typeof SUGAR.App.api && (b.url = b.url.replace( "KREST", "rest/v10/KREST" ),
                               b.headers["OAuth-Token"] = SUGAR.App.api.getOAuthToken()))
                    }
                 }
              } )
           },
           isSugar6: function () {
              return "undefined" == typeof SUGAR.App
           },
           catchDynamicOptionsFromUrl: function () {
              return _dynamicoptions = null,
                      SpiceCRM.KReporter.Common.isSugar6() ? (_urlParams = Ext.urlDecode( window.location.search ),
                      void 0 !== _urlParams.dynamicoptions && (_dynamicoptions = _urlParams.dynamicoptions)) : (_urlSearch = window.location.href.substr( (window.location.protocol + "//" + window.location.hostname + window.location.pathname).length ),
                      _urlSearchParams = _urlSearch.split( "/" ),
                      _iDynOpts = _urlSearchParams.indexOf( "dynamicoptions" ),
                      _iDynOpts > -1 && (_iDynOpts++,
                              _dynamicoptions = _urlSearchParams[_iDynOpts])),
                      _dynamicoptions
           },
           buildDynamicOptionsUrl: function ( a, b ) {
              return _url = null,
                      _dynamicoptions = this.catchDynamicOptionsFromUrl(),
                      null !== _dynamicoptions && (_url = "KREST/KReporter/" + a + "/" + b + "/dynamicoptions/" + _dynamicoptions),
                      _url
           },
           sendParentBeanParams: function ( a, b ) {
              return "undefined" != typeof currentRecord && "undefined" != typeof currentModule && currentRecord != b && (a.extraParams.parentbeanId = currentRecord,
                      a.extraParams.parentbeanModule = currentModule),
                      a
           },
           getConfig: function () {
              Ext.Ajax.request( {
                 url: "KREST/KReporter/core/config",
                 async: !1,
                 method: "GET",
                 success: function ( a, b ) {
                    200 == Ext.decode( a.status ) && (_response = Ext.decode( a.responseText ),
                            void 0 === SpiceCRM.KReporter && (SpiceCRM.KReporter = {}),
                            void 0 === SpiceCRM.KReporter.config && (SpiceCRM.KReporter.config = {}),
                            _response.KReports && (SpiceCRM.KReporter.config = _response.KReports),
                            SpiceCRM.KReporter.config.korgmanaged = !1,
                            SpiceCRM.KReporter.config.authCheck && "KAuthObjects" == SpiceCRM.KReporter.config.authCheck && (SpiceCRM.KReporter.config.korgmanaged = !0),
                            SpiceCRM.KReporter.config.securitygroups = !1,
                            SpiceCRM.KReporter.config.authCheck && "SecurityGroups" == SpiceCRM.KReporter.config.authCheck && (SpiceCRM.KReporter.config.securitygroups = !0))
                 }
              } )
           },
           getLabels: function () {
              Ext.Ajax.request( {
                 url: "KREST/KReporter/core/labels",
                 method: "GET",
                 success: function ( a, b ) {
                    200 == Ext.decode( a.status ) && (_response = Ext.decode( a.responseText ),
                            SpiceCRM.KReporter.Common.isSugar6() ? SUGAR.language.setLanguage( "KReports", _response ) : SUGAR.App.lang.setLanguage( "KReports", _response ))
                 }
              } )
           }
        },
        Ext.util.Format.kreportLinkBuilder = function ( a, b, c, d, e ) {
           if ( void 0 === a || void 0 === b || void 0 === c || void 0 === d || void 0 === e )
              return a;
           var f = void 0 !== b.get( "unionid" ) ? b.get( "unionid" ) : "root"
                   , g = e.panel.getColumns()[c].dataIndex;
           if ( !e.store.linkedFields || void 0 === e.store.linkedFields[g] || null !== e.store.linkedFields[g] && void 0 === b.get( e.store.linkedFields[g][f].idfield ) ) {
              if ( !e.store.buildLinkedFields )
                 return a;
              e.store.buildLinkedFields()
           }
           return e.store.linkedFields && null !== e.store.linkedFields[g] ? "<a href=\"#\" onclick=\"SpiceCRM.KReporter.Common.redirect('detail', {module:'" + e.store.linkedFields[g][f].module + "', id:'" + b.get( e.store.linkedFields[g][f].idfield ) + '\', newtab: true}); return false;" style="text-align:left">' + a + "</a>" : a
        }
,
        Ext.util.Format.fieldRenderer = function ( a, b, c, d, e, f, g ) {
           return "" === a || null === a ? a : "function" == typeof f.buildLinkedFields ? Ext.util.Format.kreportLinkBuilder( a, c, e, f, g ) : a
        }
,
        Ext.util.Format.ktextRenderer = function ( a, b, c, d, e, f, g ) {
           return "" === a || null === a ? a : (b.style = "white-space: normal;",
                   Ext.util.Format.kreportLinkBuilder( a, c, e, f, g ))
        }
,
        Ext.util.Format.base64Renderer = function ( a ) {
           return "" === a || null === a ? a : Ext.util.Format.htmlEncode( atob( a ) )
        }
,
        Ext.util.Format.kboolRenderer = function ( a, b, c, d, e, f, g ) {
           // Mint start #41967
           if ( !a || a.length == 0 ) {
              a = "0";
           }
           // Mint end #41967
           return "" === a || null === a ? a : Ext.util.Format.kreportLinkBuilder( languageGetText( "LBL_BOOL_" + a ), c, e, f, g )
        }
,
        Ext.util.Format.kcurrencyRenderer = function ( a, b, c, d, e, f, g ) {
           return "" === a || null === a ? a : ("undefined" == typeof dec_sep ? (Ext.util.Format.decimalSeparator = SUGAR.App.user.attributes.preferences.decimal_separator,
                   Ext.util.Format.thousandSeparator = SUGAR.App.user.attributes.preferences.number_grouping_separator) : (Ext.util.Format.decimalSeparator = dec_sep,
                   Ext.util.Format.thousandSeparator = num_grp_sep),
                   Ext.util.Format.currencySign = SpiceCRM.KReporter.Common.currencies[-99].symbol,
                   "object" == typeof c && void 0 !== c.get( g.panel.getColumns()[e].dataIndex + "_curid" ) && SpiceCRM.KReporter.Common.currencies[c.get( g.panel.getColumns()[e].dataIndex + "_curid" )] && (Ext.util.Format.currencySign = SpiceCRM.KReporter.Common.currencies[c.get( g.panel.getColumns()[e].dataIndex + "_curid" )].symbol),
                   Ext.util.Format.currencyPrecision = SpiceCRM.KReporter.precision,
                   f && "function" == typeof f.buildLinkedFields ? Ext.util.Format.kreportLinkBuilder( Ext.util.Format.currency( a ), c, e, f, g ) : Ext.util.Format.currency( a ))
        }
,
        Ext.util.Format.kpercentageRenderer = function ( a, b, c, d, e, f, g ) {
           return "" === a || null === a ? a : Ext.util.Format.kreportLinkBuilder( Ext.util.Format.round( a, 2 ) + "%", c, e, f, g )
        }
,
        Ext.util.Format.knumberRenderer = function ( a, b, c, d, e, f, g ) {
           return "" === a || null === a ? a : ("undefined" == typeof dec_sep ? (Ext.util.Format.decimalSeparator = SUGAR.App.user.attributes.preferences.decimal_separator,
                   Ext.util.Format.thousandSeparator = SUGAR.App.user.attributes.preferences.number_grouping_separator) : (Ext.util.Format.decimalSeparator = dec_sep,
                   Ext.util.Format.thousandSeparator = num_grp_sep),
                   Ext.util.Format.currencyPrecision = SpiceCRM.KReporter.precision,
                   // Mint start #42402
                   //_formatNumber = "0" + Ext.util.Format.thousandSeparator + "000" + Ext.util.Format.decimalSeparator + "00/i",
                   _formatNumber = Ext.util.Format.thousandSeparator + "0" + Ext.util.Format.decimalSeparator + "00/i",
                   // Mint end #42402
                   f && "function" == typeof f.buildLinkedFields ? Ext.util.Format.kreportLinkBuilder( Ext.util.Format.number( a, _formatNumber ), c, e, f, g ) : Ext.util.Format.number( a, _formatNumber ))
        }
,
        Ext.util.Format.kintRenderer = function ( a, b, c, d, e, f, g ) {
           return "" === a || null === a ? a : ("undefined" == typeof dec_sep ? (Ext.util.Format.decimalSeparator = SUGAR.App.user.attributes.preferences.decimal_separator,
                   Ext.util.Format.thousandSeparator = SUGAR.App.user.attributes.preferences.number_grouping_separator) : (Ext.util.Format.decimalSeparator = dec_sep,
                   Ext.util.Format.thousandSeparator = num_grp_sep),
                   _formatNumber = "0" + Ext.util.Format.thousandSeparator + "000/i",
                   Ext.util.Format.kreportLinkBuilder( Ext.util.Format.number( a, _formatNumber ), c, e, f, g ))
        }
,
        Ext.util.Format.kdatetimeRenderer = function ( a, b, c, d, e, f, g ) {
           if ( "" === a || null === a )
              return a;
           var h = "m.d.Y";
           "undefined" != typeof SUGAR.App && (h = SUGAR.App.user.attributes.preferences.datepref),
                   "undefined" != typeof cal_date_format && (h = cal_date_format.replace( /%/g, "" ));
           var i = "m.d.Y";
           return "undefined" != typeof SUGAR.App && (i = SUGAR.App.user.attributes.preferences.tz_offset_sec),
                   "undefined" != typeof time_offset && (i = time_offset),
                   Ext.util.Format.kreportLinkBuilder( Ext.Date.format( Ext.Date.add( new Date( a.replace( /-/g, "/" ) ), Ext.Date.SECOND, i ), SpiceCRM.KReporter.dateformat + " " + SpiceCRM.KReporter.timeformat ), c, e, f, g );
        }
,
        Ext.util.Format.kdatetutcRenderer = function ( a, b, c, d, e, f, g ) {
           if ( "" === a || null === a )
              return a;
           var h = "m.d.Y";
           return "undefined" != typeof SUGAR.App && (h = SUGAR.App.user.attributes.preferences.datepref),
                   "undefined" != typeof cal_date_format && (h = cal_date_format.replace( /%/g, "" )),
                   Ext.util.Format.kreportLinkBuilder( Ext.Date.format( new Date( a.replace( /-/g, "/" ) ), SpiceCRM.KReporter.dateformat + " " + SpiceCRM.KReporter.timeformat ), c, e, f, g )
        }
,
        Ext.util.Format.kdateRenderer = function ( a, b, c, d, e, f, g ) {
           if ( "" === a || null === a )
              return a;
           "object" == typeof a && (a = Ext.Date.format( a, "Y-m-d" ));
           var h = "m.d.Y";
           return "undefined" != typeof SUGAR.App && (h = SUGAR.App.user.attributes.preferences.datepref),
                   "undefined" != typeof cal_date_format && (h = cal_date_format.replace( /%/g, "" )),
                   Ext.util.Format.kreportLinkBuilder( Ext.util.Format.date( a.replace( /-/g, "/" ), h ), c, e, f, g )
        }
;