<style>
    {literal}
        a {
            color: #E56455;
            text-decoration: none;
        }
        a:hover {
            color: #23527c;
            text-decoration: none;
        }
    {/literal}
</style>
</head>
<body style="width: max-content; height: max-content">
    <form action="index.php" method="post" name="DetailView" id="formDetailView">
        <input type="hidden" name="record" value="{$report_id}" />
    </form>

    <script type="text/javascript" src="modules/KReports/js/ext-all.js"></script>
    <link rel="stylesheet" type="text/css" href="k/css/spicecrm-theme/resources/spicecrm-theme-all-debug.css" />
    <link rel="stylesheet" type="text/css" href="k/css/ext6_override.css">
    <script type="text/javascript" src="modules/KReports/js/KReporterCommon.js"></script>
    <script type="text/javascript" src="modules/KReports/js/KReporterViewer.js"></script>

    <div id="kreportviewer"></div>

    <script>
        {literal}
            window.insideSugarDashlet = true;
            Ext.onReady( function () {
               var kreporterView = SpiceCRM.KReporter.Viewer.Application.thisMainView;
        {/literal}{$show_options}{literal}
               kreporterView.getComponent( 0 ).destroy();
        {/literal}{if $use_autofilter == true}{literal}
               SpiceCRM.KReporter.onStoreLoad = function () {
                  if ( SpiceCRM.KReporter.autofilter_loaded ) {
                     return;
                  }
                  var autofilter_setup = [
                     {
                        fieldid: '{/literal}{$autofilter_fieldid}{literal}',
                        operator: "equals",
                        value: '{/literal}{$autofilter_value}{literal}'
                     }
                  ];
                  Ext.globalEvents.fireEvent( "whereClauseUpdated", autofilter_setup );
                  SpiceCRM.KReporter.autofilter_loaded = true;
               };
        {/literal}{/if}{literal}
            } );

        {/literal}
    </script>
</body>
</html>