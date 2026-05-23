<div id='full-structure-dashlet' style="height:{$height}px;">
<link rel="stylesheet" href="modules/Home/Dashlets/OrganizationStructureDashlet/css/organizational-structure.css?v=1">
{if $fullscreen}
    <link rel="stylesheet" href="cache/themes/SuiteP/css/Mint/style.css">
    <script src="include/javascript/jquery/jquery-min.js"></script>
{else}
    <a name="structure_full_screen" id="structure_full_screen" onclick="var myWindow = window.open('index.php?entryPoint=OrganizationalStructure&id={$id}', '_blank');">
        <img src="./modules/Home/Dashlets/OrganizationStructureDashlet/images/fullscreen.png?v=1" alt="Tryb pełnoekranowy">
    </a>
{/if}

<div id='organizational-structure-{$id}' style="height:calc({$height}px - 20px);">
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
    {/literal}
    {if !$fullscreen}{literal}
        setTimeout(() => {
    {/literal}{/if}
    {literal}
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
                    setTimeout(checkExist, 500);
    {/literal}
    {if !$fullscreen}{literal}
        }, 500)
    {/literal}{/if}
    {literal}
            });
        </script>
    {/literal}
</div>
</div>
