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
Ext.define( "SpiceCRM.KReporter.Designer.presentationplugins.standardviewpanel", {
   extend: "Ext.grid.Panel",
   store: Ext.data.StoreManager.lookup( "KReportDesignerListFieldsStore" ),
   controller: "KReportDesignerListFieldsController",
   border: !1,
   columns: [ {
         text: languageGetText( "LBL_NAME" ),
         dataIndex: "name",
         sortable: !1,
         width: 150,
         editor: {
            xtype: "textfield"
         }
      }, {
         text: languageGetText( "LBL_DISPLAY" ),
         readOnly: !0,
         dataIndex: "display",
         width: 130,
         sortable: !1,
         hidden: !1,
         editor: {
            xtype: "kcombo",
            triggerAction: "all",
            lazyRender: !0,
            mode: "local",
            store: new Ext.data.ArrayStore( {
               id: 0,
               fields: [ "value", "text" ],
               data: [ [ "no", languageGetText( "LBL_NO" ) ], [ "yes", languageGetText( "LBL_YES" ) ], [ "hid", languageGetText( "LBL_HID" ) ] ]
            } ),
            valueField: "value",
            displayField: "text"
         },
         renderer: function ( a ) {
            return void 0 !== a ? languageGetText( "LBL_" + a.toUpperCase() ) : a
         }
      }, {
         text: languageGetText( "LBL_LINK" ),
         readOnly: !0,
         dataIndex: "link",
         width: 100,
         sortable: !1,
         hidden: !1,
         editor: {
            xtype: "kcombo",
            triggerAction: "all",
            lazyRender: !0,
            mode: "local",
            store: new Ext.data.ArrayStore( {
               id: 0,
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
         text: languageGetText( "LBL_SORTSEQUENCE" ),
         readOnly: !0,
         dataIndex: "sort",
         width: 100,
         sortable: !1,
         hidden: !1,
         editor: {
            xtype: "kcombo",
            triggerAction: "all",
            lazyRender: !0,
            mode: "local",
            store: new Ext.data.ArrayStore( {
               id: 0,
               fields: [ "value", "text" ],
               data: [ [ "-", "-" ], [ "asc", languageGetText( "LBL_SORT_ASC" ) ], [ "desc", languageGetText( "LBL_SORT_DESC" ) ], [ "sortable", languageGetText( "LBL_SORT_SORTABLE" ) ] ]
            } ),
            valueField: "value",
            displayField: "text"
         },
         renderer: function ( a ) {
            return void 0 !== a && "-" != a ? languageGetText( "LBL_SORT_" + a.toUpperCase() ) : a
         }
      }, {
         text: languageGetText( "LBL_SORTPRIORITY" ),
         dataIndex: "sortpriority",
         sortable: !1,
         width: 80,
         editor: {
            xtype: "numberfield"
         }
      }, {
         text: languageGetText( "LBL_WIDTH" ),
         dataIndex: "width",
         sortable: !1,
         width: 70,
         editor: {
            xtype: "numberfield"
         }
      }, {
         header: languageGetText( "LBL_OVERRIDETYPE" ),
         width: 160,
         dataIndex: "overridetype",
         editor: {
            xtype: "kcombo",
            triggerAction: "all",
            editable: !1,
            forceSelection: !0,
            lazyRender: !0,
            edtiable: !1,
            store: new Ext.data.ArrayStore( {
               fields: [ "value", "text" ],
               data: [ [ "-", "-" ], [ "none", languageGetText( "LBL_RENDERER_NONE" ) ], [ "currency", languageGetText( "LBL_RENDERER_CURRENCY" ) ], [ "percentage", languageGetText( "LBL_RENDERER_PERCENTAGE" ) ], [ "number", languageGetText( "LBL_RENDERER_NUMBER" ) ], [ "int", languageGetText( "LBL_RENDERER_INT" ) ], [ "float", languageGetText( "LBL_RENDERER_FLOAT" ) ], [ "date", languageGetText( "LBL_RENDERER_DATE" ) ], [ "datetime", languageGetText( "LBL_RENDERER_DATETIME" ) ], [ "datetutc", languageGetText( "LBL_RENDERER_DATETUTC" ) ], [ "bool", languageGetText( "LBL_RENDERER_BOOL" ) ], [ "text", languageGetText( "LBL_RENDERER_TEXT" ) ] ]
            } ),
            valueField: "value",
            displayField: "text"
         },
         renderer: function ( a ) {
            return void 0 !== a && "" !== a ? languageGetText( "LBL_RENDERER_" + a.toUpperCase() ) : a
         }
      }, {
         header: languageGetText( "LBL_OVERRIDEALIGNMENT" ),
         width: 160,
         dataIndex: "overridealignment",
         editor: {
            xtype: "kcombo",
            triggerAction: "all",
            editable: !1,
            forceSelection: !0,
            lazyRender: !0,
            edtiable: !1,
            store: new Ext.data.ArrayStore( {
               fields: [ "value", "text" ],
               data: [ [ "-", "-" ], [ "left", languageGetText( "LBL_ALIGNMENT_LEFT" ) ], [ "right", languageGetText( "LBL_ALIGNMENT_RIGHT" ) ], [ "center", languageGetText( "LBL_ALIGNMENT_CENTER" ) ] ]
            } ),
            valueField: "value",
            displayField: "text"
         },
         renderer: function ( a ) {
            return void 0 !== a && "" !== a ? languageGetText( "LBL_ALIGNMENT_" + a.toUpperCase() ) : a
         }
      } ],
   plugins: [ Ext.create( "Ext.grid.plugin.CellEditing", {
         clicksToEdit: 1
      } ) ],
   sm: new Ext.selection.RowModel,
   viewConfig: {
      markDirty: !1,
      stripeRows: !0,
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
   dockedItems: [ {
         xtype: "toolbar",
         dock: "top",
         items: [ {
               xtype: "combobox",
               itemId: "listtypecountcombo",
               store: new Ext.data.ArrayStore( {
                  fields: [ "value", "text" ],
                  data: [
                     [ "Synchronous", languageGetText( "LBL_STANDARDGRIDPROPERTIES_SYNCHRONOUSCOUNT" ) ],
                     [ "Asynchronous", languageGetText( "LBL_STANDARDGRIDPROPERTIES_ASYNCHRONOUSCOUNT" ) ],
                     [ "None", languageGetText( "LBL_STANDARDGRIDPROPERTIES_NONE" ) ]
                  ]
               } ),
               displayField: "text",
               valueField: "value",
               fieldLabel: languageGetText( "LBL_STANDARDGRIDPROPERTIES_COUNT" ),
               editable: !1,
               modeode: "local",
               triggerAction: "all",
               value: "Synchronous"
            }, {
               xtype: "numberfield",
               itemId: "listtypeentries",
               fieldLabel: languageGetText( "LBL_STANDARDGRIDENTRIES_COUNT" )
            } ]
      } ],
   initialize: function ( a ) {
      var b = this.down( "#listtypecountcombo" );
      b.setValue( "Synchronous" );
      var c = this.down( "#listtypeentries" );
      c.setValue( 25 ),
              void 0 !== a.standardViewProperties && void 0 !== a.standardViewProperties.listEntries && c.setValue( a.standardViewProperties.listEntries )
   },
   getPanelData: function () {
      var a = {
         standardViewProperties: {
            processCount: this.down( "#listtypecountcombo" ).getValue(),
            listEntries: this.down( "#listtypeentries" ).getValue()
         }
      };
      return a
   }
} );
