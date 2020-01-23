Ext.define( "SpiceCRM.KReporter.Viewer.integrationplugins.pdfexport.TemplatesModel", {
   extend: "Ext.data.Model",
   fields: [
      {name: 'id'},
      {name: 'name'}
   ]
} );

Ext.define( "SpiceCRM.KReporter.Viewer.integrationplugins.pdfexport.TemplatesStore", {
   extend: 'Ext.data.JsonStore',
   model: 'SpiceCRM.KReporter.Viewer.integrationplugins.pdfexport.TemplatesModel',
   autoLoad: true,
   proxy: {
      type: 'ajax',
      url: 'index.php?module=KTemplates&action=getReportPDFTemplates&sugar_body_only=1&kreport_id=' + SpiceCRM.KReporter.Viewer.Application.reportRecord.get( "id" ) + '&plugin=' + Ext.decode( Ext.util.Format.htmlDecode( SpiceCRM.KReporter.Viewer.Application.reportRecord.get( "presentation_params" ) ) ).plugin,
      reader: {
         type: 'json'
      }
   }
} );
var templates_store = Ext.create( "SpiceCRM.KReporter.Viewer.integrationplugins.pdfexport.TemplatesStore", {} );

Ext.define( "SpiceCRM.KReporter.Viewer.integrationplugins.pdfexport.TemplatesWindow", {
   extend: "Ext.window.Window",
   modal: !0,
   width: 400,
   title: languageGetText( "LBL_SELECT_PDF_TEMPLATE" ),
   closeAction: "hide",
   items: [ {
         xtype: "combobox",
         style: {
            "margin": "8px"
         },
         value: "Default",
         text: languageGetText( "LBL_DEFAULT_PDF_TEMPLATE" ),
         store: templates_store,
         valueField: "id",
         displayField: "name",
         editable: false,
      }
   ],
   buttons: [ {
         text: languageGetText( "LBL_OK" ),
         handler: function () {
            downloadPDFFile( this.up( "window" ).down( "combobox" ).getValue() );
         }
      }, {
         text: languageGetText( "LBL_CANCEL_BUTTON" ),
         handler: function () {
            this.up( "window" ).close();
         }
      } ],
} );

Ext.define( "SpiceCRM.KReporter.Viewer.integrationplugins.pdfexport.menuitem", {
   extend: "Ext.menu.Item",
   text: languageGetText( "LBL_PDF_EXPORT" ),
   icon: "modules/KReports/images/pdf.png",
   handler: function () {
      templates_store.load();

      var presentation_params = Ext.decode( Ext.util.Format.htmlDecode( SpiceCRM.KReporter.Viewer.Application.reportRecord.get( "presentation_params" ) ) );
      if ( (presentation_params.plugin == 'standard' || presentation_params.plugin == 'ktreeview') && templates_store.count() > 1 ) {
         Ext.create( "SpiceCRM.KReporter.Viewer.integrationplugins.pdfexport.TemplatesWindow", {} ).show();
      } else {
         downloadPDFFile( "Default" );
      }
   }
} );

function downloadPDFFile( template_id ) {
   if ( !template_id ) {
      template_id = "Default";
   }

   var charts_data = [ ];
   var integration_params = Ext.decode( Ext.util.Format.htmlDecode( SpiceCRM.KReporter.Viewer.Application.reportRecord.get( "integration_params" ) ) );
   if ( integration_params.kpdfexport.export_charts ) {
      var vizualization_container = Ext.ComponentQuery.query( "#KReportVisualizationContainer" )[0];
      if ( vizualization_container && vizualization_container.items && vizualization_container.items ) {
         for ( var idx = 0; idx < vizualization_container.items.items.length; idx++ ) {
            var item = vizualization_container.items.items[idx];
            if ( item.wrapper ) {
               var chart = item.wrapper.getChart();
               charts_data.push( {
                  chart: chart.getImageURI(),
                  width: $( chart.getContainer() ).css( "width" ).replace( 'px', '' )
               } );
            }
         }
      }
   }

   var pdf_data = {
      report_id: SpiceCRM.KReporter.Viewer.Application.reportRecord.get( "id" ),
      dynamicols: (Ext.ComponentQuery.query( "#KReportPresentationContainer" )[0].child(), [ ]),
      dynamicoptions: Ext.ComponentQuery.query( "#KReportViewqerWherePanel" )[0].controller.extractWhereClause(),
      template_id: template_id
   }

   var presentation_container_child = Ext.ComponentQuery.query( "#KReportPresentationContainer" )[0].child();
   if ( typeof presentation_container_child.getDynamicColumns == "function" ) {
      pdf_data.dynamicols = presentation_container_child.getDynamicColumns();
   }
   SpiceCRM.KReporter.Common.download( {
      url: "index.php?module=KReports&action=export_to_pdf&record=" + pdf_data.report_id + "&template_id=" + pdf_data.template_id,
      method: "POST",
      params: {
         dynamicoptions: Ext.encode( pdf_data.dynamicoptions ),
         dynamicols: Ext.encode( pdf_data.dynamicols ),
         charts_data: Ext.encode( charts_data )
      }
   } );
}