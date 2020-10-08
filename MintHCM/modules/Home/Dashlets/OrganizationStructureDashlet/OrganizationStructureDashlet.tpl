<div id='full-structure-dashlet' style="height:{$height}px;">

<div id='organizational-structure-{$id}' style="height:calc({$height}px - 20px);">
    <link rel="stylesheet" href="modules/Home/Dashlets/OrganizationStructureDashlet/css/organizational-structure.css">
    {if $fullscreen == true}
    <link rel="stylesheet" href="custom/themes/SuiteP/css/Mint/style.css">
    <script src="include/javascript/jquery/jquery-min.js"></script>
    {/if}
    {literal}
        <script src="modules/Home/Dashlets/OrganizationStructureDashlet/js/raphael.js"></script>
        <script src="modules/Home/Dashlets/OrganizationStructureDashlet/js/Treant.js"></script>
        <script src="modules/Home/Dashlets/OrganizationStructureDashlet/js/jquery.easing.js"></script>
        <script>
            if (typeof jQuery != "function") { console.error("jQuery is not loaded"); }
            $(document).ready(function() {
                var chart_config = {
                    chart: {
                        container: "#organizational-structure-{/literal}{$id}{literal}",
                        // scrollbar: "fancy",
                        connectors: {
                            type: 'step'
                        },
                        node: {
                            HTMLclass: 'osNode',
                            collapsable: false
                        },
                        
                    },
                    nodeStructure: {
                        {/literal} {$rootElement} {literal}
                        ,children: {/literal}{$jsonTree}{literal}
                    }
                };
                new Treant(chart_config);
                checkExist = function(){
                if (($('.rootWithImage').length || $('.rootNoImage').length)) {
                        document.querySelector('.rootWithImage, .rootNoImage').scrollIntoView({
                                behavior: 'smooth',
                                block: 'center',
                                inline: 'center'
                                });
                                if((parseFloat($('.rootNoImage').css('left')) == 0 || parseFloat($('.rootWithImage').css('left'))) == 0){
                                    setTimeout(checkExist, 100);
                                }
                                if((parseFloat($('.rootNoImage').css('left')) > 1000 || parseFloat($('.rootWithImage').css('left')) > 1000)){
                                    SUGAR.mySugar.retrieveDashlet( '{/literal}{$id}{literal}', '');
                                }
                    } else {
                        setTimeout(checkExist, 100);
                        }
                }
                setTimeout(checkExist, 100);
            });
        {/literal}
    </script>
</div>
</div>
