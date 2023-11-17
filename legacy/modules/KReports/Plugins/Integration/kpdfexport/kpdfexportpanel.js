

Ext.define( "SpiceCRM.KReporter.Designer.integrationplugins.kpdfexportpanel", {
   extend: "Ext.panel.Panel",
   border: false,
   style: {
      "padding": "15px"
   },
   items: [
      {
         xtype: "combobox",
         value: "P",
         itemId: "page_orientation",
         store: Ext.create( "Ext.data.Store", {
            fields: [ "value", "text" ],
            data: [
               {value: 'P', text: languageGetText( 'LBL_PDFORIENT_P' )},
               {value: 'L', text: languageGetText( 'LBL_PDFORIENT_L' )}
            ]
         } ),
         valueField: "value",
         displayField: "text",
         editable: false,
         fieldLabel: languageGetText( 'LBL_PDF_ORIENTATION' ),
      },
      {
         xtype: "combobox",
         value: "A4",
         itemId: "page_format",
         store: Ext.create( "Ext.data.Store", {
            fields: [ "value", "text" ],
            data: [
               {value: 'A2', text: 'A2'},
               {value: 'A3', text: 'A3'},
               {value: 'A4', text: 'A4'},
               {value: 'A5', text: 'A5'}
            ]
         } ),
         valueField: "value",
         displayField: "text",
         editable: false,
         fieldLabel: languageGetText( 'LBL_PDF_FORMAT' ),
      },
      {
         xtype: "checkbox",
         value: true,
         itemId: "export_charts",
         fieldLabel: languageGetText( 'LBL_PDF_EXPORT_CHARTS' ),
         listeners: {
            "change": function () {
               if ( !this.getValue() ) {
                  this.ownerCt.getComponent( "separate_charts" ).setDisabled( true );
               } else {
                  this.ownerCt.getComponent( "separate_charts" ).setDisabled( false );
               }
            }
         }
      },
      {
         xtype: "checkbox",
         value: false,
         itemId: "separate_charts",
         fieldLabel: languageGetText( 'LBL_PDF_SEPARATE_CHARTS' ),
      },
   ],
   setPanelData: function ( panelData ) {
      this.down( "#page_orientation" ).setValue( panelData.page_orientation );
      this.down( "#page_format" ).setValue( panelData.page_format );
      this.down( "#export_charts" ).setValue( panelData.export_charts );
      this.down( "#separate_charts" ).setValue( panelData.separate_charts );
   },
   getPanelData: function () {
      var panelData = {
         page_orientation: this.down( "#page_orientation" ).getValue(),
         page_format: this.down( "#page_format" ).getValue(),
         export_charts: this.down( "#export_charts" ).getValue(),
         separate_charts: this.down( "#separate_charts" ).getValue(),
      };
      return panelData
   }
} );