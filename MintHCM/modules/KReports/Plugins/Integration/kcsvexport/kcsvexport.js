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
Ext.define("SpiceCRM.KReporter.Viewer.integrationplugins.csvexport.menuitem",{extend:"Ext.menu.Item",text:languageGetText("LBL_CSV_EXPORT"),icon:"modules/KReports/images/csv.png",handler:function(){var a=(Ext.ComponentQuery.query("#KReportPresentationContainer")[0].child(),[]);"undefined"!=typeof Ext.ComponentQuery.query("#KReportPresentationContainer")[0].child().getDynamicColumns&&(a=Ext.ComponentQuery.query("#KReportPresentationContainer")[0].child().getDynamicColumns()),SpiceCRM.KReporter.Common.download({url:"KREST/KReporter/plugins/action/kcsvexport/export?record="+SpiceCRM.KReporter.Viewer.Application.reportRecord.get("id"),method:"POST",params:{dynamicoptions:Ext.encode(Ext.ComponentQuery.query("#KReportViewqerWherePanel")[0].controller.extractWhereClause()),dynamicols:Ext.encode(a)}})}});