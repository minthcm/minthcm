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

Ext.define( "Mint.KReporter.FieldsStore", {
   extend: "Ext.data.Store",
   fields: [ "value", "text" ],
} );

Ext.define( "SpiceCRM.KReporter.Designer.presentationplugins.ktreeviewpanel", {
   extend: "Ext.grid.Panel",
   store: Ext.data.StoreManager.lookup( "KReportDesignerListFieldsStore" ),
   controller: "KReportDesignerListFieldsController",
   border: false,
   columns: [ {
         text: languageGetText( "LBL_NAME" ),
         dataIndex: "name",
         sortable: false,
         width: 180,
         editor: {
            xtype: "textfield"
         }
      }, {
         text: languageGetText( "LBL_DISPLAY" ),
         readOnly: true,
         dataIndex: "display",
         width: 100,
         sortable: false,
         hidden: false,
         editor: {
            xtype: "kcombo",
            triggerAction: "all",
            lazyRender: true,
            mode: "local",
            store: new Ext.data.ArrayStore( {
               id: 0,
               fields: [ "value", "text" ],
               data: [
                  [ "no", languageGetText( "LBL_NO" ) ],
                  [ "yes", languageGetText( "LBL_YES" ) ]
               ]
            } ),
            valueField: "value",
            displayField: "text"
         },
         renderer: function ( a ) {
            return void 0 !== a ? languageGetText( "LBL_" + a.toUpperCase() ) : a;
         }
      }, {
         text: languageGetText( "LBL_SORTSEQUENCE" ),
         readOnly: true,
         dataIndex: "sort",
         width: 120,
         sortable: false,
         hidden: false,
         editor: {
            xtype: "kcombo",
            triggerAction: "all",
            lazyRender: true,
            mode: "local",
            store: new Ext.data.ArrayStore( {
               id: 0,
               fields: [ "value", "text" ],
               data: [
                  [ "-", "-" ],
                  [ "asc", languageGetText( "LBL_SORT_ASC" ) ],
                  [ "desc", languageGetText( "LBL_SORT_DESC" ) ]
               ]
            } ),
            valueField: "value",
            displayField: "text"
         },
         renderer: function ( a ) {
            return void 0 !== a && "-" != a ? languageGetText( "LBL_SORT_" + a.toUpperCase() ) : a;
         }
      }, {
         text: languageGetText( "LBL_WIDTH" ),
         dataIndex: "width",
         sortable: false,
         width: 120,
         editor: {
            xtype: "numberfield"
         }
      }, {
         text: languageGetText( "LBL_FUNCTION" ),
         dataIndex: "function",
         sortable: false,
         editable: false,
         width: 120,
         editor: {
            xtype: "kcombo",
            triggerAction: "all",
            lazyRender: true,
            mode: "local",
            store: new Ext.data.ArrayStore( {
               fields: [ "value", "text" ],
               data: [
                  [ "-", "-" ],
                  [ "sum", languageGetText( "LBL_FUNCTION_SUM" ) ],
                  [ 'count', languageGetText( "LBL_FUNCTION_COUNT" ) ],
                  [ "avg", languageGetText( "LBL_FUNCTION_AVG" ) ],
                  [ 'min', languageGetText( "LBL_FUNCTION_MIN" ) ],
                  [ 'max', languageGetText( "LBL_FUNCTION_MAX" ) ]
               ]
            } ),
            valueField: "value",
            displayField: "text"
         },
         renderer: function ( a ) {
            if ( !a ) {
               return "-";
            } else {
               return languageGetText( "LBL_FUNCTION_" + a.toUpperCase() );
            }
         }
      } ],
   plugins: [ Ext.create( "Ext.grid.plugin.CellEditing", {
         clicksToEdit: 1
      } ) ],
   sm: new Ext.selection.RowModel,
   viewConfig: {
      markDirty: false,
      stripeRows: true,
      plugins: {
         ptype: "gridviewdragdrop",
         dropGroup: "designerfields",
         dragGroup: "designerfields",
         enableDrag: true
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
               itemId: "groupuntilfield",
               displayField: "text",
               valueField: "value",
               fieldLabel: languageGetText( "LBL_TREEVIEWPROPERTIES_GRPUNTIL" ),
               editable: false,
               modeode: "local",
               triggerAction: "all",
               autoSelect: true,
               store: new Ext.data.Store( {
                  fields: [ "value", "text" ]
               } ),
               listeners: {
                  expand: function ( a ) {
                     a.getBubbleParent().getBubbleParent().reloadGroupUntilFields();
                  }
               }
            } ]
      } ],
   reloadGroupUntilFields: function () {
      var fieldsData = [ ];
      this.store.each( function ( field ) {
         fieldsData.push( {
            value: field.data.fieldid,
            text: field.data.name
         } );
      } );

      var fieldsComboBox = this.down( "#groupuntilfield" );
      fieldsComboBox.store.setData( fieldsData );
   },
   initialize: function ( panelData ) {
      var fieldsComboBox = this.down( "#groupuntilfield" );
      this.reloadGroupUntilFields();

      if ( panelData.kTreeViewProperties && panelData.kTreeViewProperties.groupUntil ) {
         fieldsComboBox.setValue( panelData.kTreeViewProperties.groupUntil );
      }
   },
   getPanelData: function () {
      var panelData = {
         kTreeViewProperties: {
            groupUntil: this.down( "#groupuntilfield" ).getValue()
         }
      };
      return panelData;
   }
} );