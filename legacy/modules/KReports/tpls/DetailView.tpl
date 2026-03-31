{* * *******************************************************************************
* This file is part of KReporter. KReporter is an enhancement developed
* by aac services k.s.. All rights are (c) 2016 by aac services k.s.
*
* This Version of the KReporter is licensed software and may only be used in
* alignment with the License Agreement received with this Software.
* This Software is copyrighted and may not be further distributed without
* witten consent of aac services k.s.
*
* You can contact us at info@kreporter.org
******************************************************************************* *}


{* Mint start #42089 *}
<style>
    {literal}
        #tab-actions {
            display: none;
        }
        .detail-view .tab-content {
            display: none;
        }
        .panel-content {
            display: none;
        }
        {* MintHCM #110809 START *}
        #KReportViewer-PresentationContainer-1036 div.top-scroll-wrapper{
            overflow-x: auto; 
        }
        #KReportViewer-PresentationContainer-1036 div.top-scroll-wrapper > div {
            height: 1px;
        }
        #KReportViewer-PresentationContainer-1036 #KReportViewer-PresentationContainer-1036-innerCt .x-panel-default
        {
            max-height:70vh;
            overflow:auto;
        }
        #KReportViewer-PresentationContainer-1036 #KReportViewer-PresentationContainer-1036-innerCt .x-panel-body-default
        {
            width: max-content !important;
            top:unset !important;
        }
        #KReportViewer-PresentationContainer-1036 #KReportViewer-PresentationContainer-1036-innerCt .x-docked-bottom
        {
            position:sticky !important;
            top:unset !important;
            bottom:0px !important;
        }
        #KReportViewer-PresentationContainer-1036 #KReportViewer-PresentationContainer-1036-innerCt .x-docked-top
        {
            position: sticky !important;
            top: 0px !important;
            bottom: unset !important;
            width: max-content !important;
        }
        #KReportViewer-PresentationContainer-1036 #KReportViewer-PresentationContainer-1036-innerCt .x-grid-view-default {
            overflow: visible !important;
            width: max-content !important;
        }
        {* MintHCM #110809 END *}
    {/literal}
</style>
{* Mint end #42089 *}
<script type="text/javascript" src="modules/KReports/js/ext-all.js"></script>
<link rel="stylesheet" type="text/css" href="k/css/spicecrm-theme/resources/spicecrm-theme-all-debug.css" />
<link rel="stylesheet" type="text/css" href="k/css/ext6_override.css">
<!--script type="text/javascript" src="k/extjs6/resources/theme-gray/theme-gray-debug.js"></script-->
<script type="text/javascript" src="modules/KReports/js/KReporterCommon.js?{$smarty.now}"></script>
<script type="text/javascript" src="modules/KReports/js/KReporterViewer.js?{$smarty.now}"></script>
<div style="width: 100%" id="kreportviewer"></div>
{* MintHCM #110809 START *}
<script type="text/javascript" src="modules/KReports/js/KReportsScrollManager.js"></script>
{* MintHCM #110809 END *}
