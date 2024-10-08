<br>

<script type="text/javascript" src="{sugar_getjspath file='include/SubPanel/SubPanelTiles.js'}"></script>

<ul class="noBullet" id="subpanel_list">
    {foreach from=$subpanel_tabs key=i item=subpanel_tab}
        <li class="noBullet useFooTable" id="whole_subpanel_{$subpanel_tab}">
            {$subpanel_tabs_properties.$i.collapse_subpanels}
            <div class="panel panel-default sub-panel">
                <div class="panel-heading panel-heading-collapse">

                    {if $subpanel_tabs_properties.$i.expanded_subpanels == true}
                        <a id="subpanel_title_{$subpanel_tab}" class="in" role="button" data-toggle="collapse" href="#subpanel_{$subpanel_tab}" aria-expanded="false"
                           onclick="toggleSubpanelCookie( '{$subpanel_tab}' );">
                        {else}
                            <a id="subpanel_title_{$subpanel_tab}"
                               {* viewTools #56822 START *}
                               {*class="collapsed{if isset($subpanel_tabs_properties.$i.collapsed_override)} collapsed-override{/if}"*}
                               class="collapsed"
                               {* viewTools #56822 END *}
                               role="button" data-toggle="collapse"
                               href="#subpanel_{$subpanel_tab}" aria-expanded="false"
                               onclick="showSubPanel( '{$subpanel_tab}' ); toggleSubpanelCookie( '{$subpanel_tab}' );">
                            {/if}
                            <div class="col-xs-10 col-sm-11 col-md-11">
                                <div>
                                    <span class="{get_module_icon_class module_name=$subpanel_tabs_properties.$i.module_name} subpanel-icon"></span>
                                    {* viewTools start #36866 *}
                                    {*{$subpanel_tabs_properties.$i.title}*}
                                    <span class="subpanel_title">{$subpanel_tabs_properties.$i.title}</span>
                                    {* viewTools end *}
                                </div>
                            </div>
                        </a>

                </div>

                {if $subpanel_tabs_properties.$i.expanded_subpanels == true}
                    <div class="panel-body panel-collapse collapse in" id="subpanel_{$subpanel_tab}">
                    {else}
                        <div class="panel-body panel-collapse collapse" id="subpanel_{$subpanel_tab}">
                        {/if}
                        {if $subpanel_tabs_properties.$i.dropzone == true}
                            <div id="dZUpload" class="dropzone needsclick dz-clickable">
                                <div class="dz-message needsclick">
                                    <div class="dz-button"><span class="fas fa-plus-circle"></span></div>
                                    <span class="note needsclick">
                                    </span>
                                </div>
                            </div>
                            <link rel="stylesheet" type="text/css" href="{sugar_getjspath file="include/Dropzone/dropzone.min.css"}" />
                            <link rel="stylesheet" type="text/css" href="{sugar_getjspath file="include/Dropzone/files.css"}" />
                            <script type="text/javascript">
                                {literal}
                                    $(document).ready(function() {
                                        const module = $("form[name='DetailView'] input[name='module']").val();
                                        const record = $("form[name='DetailView'] input[name='record']").val();
                                        window.documentFiles = new PhotosFiles(record, module, {}, true);
                                        window.documentFiles.init();
                                        const label = viewTools.language.get('app_strings', 'LBL_DEFAULT_DROPZONE_MESSAGE').replace('{upload_maxsize}', viewTools.formula.getUploadMaxsize);
                                        $('.note.needsclick').html(label);
                                    });
                                {/literal}
                            </script>
                        {else}
                        <div class="tab-content">
                            <div id="list_subpanel_{$subpanel_tab}">
                                {$subpanel_tabs_properties.$i.subpanel_body}
                            </div>
                        </div>
                        {/if}
                    </div>
                </div>
        </li>
		{* viewTools start #56822 *}
        <script>
                {* View Tools start #60164 *}
		var amount=0;
		var counter=$('#whole_subpanel_{$subpanel_tab}').find('.counter').html();

		{literal}
		if(typeof counter !== 'undefined'){
			amount=counter.match(/\d+/g).map(Number)[0];
			if(amount>=1){
		{/literal}

                {* View Tools end #60164 *}
		       showSubPanel('{$subpanel_tab}');
                {* View Tools start #60164 *}
		{literal}			   
			}
		}
		{/literal}
	        {* View Tools end #60164 *}
        </script>
        {* viewTools end #56822 *}
    {/foreach}
</ul>
{if empty($sugar_config.lock_subpanels) || $sugar_config.lock_subpanels == false}
    {*drag and drop code*}
    <script>
        {literal}
            var SubpanelInit = function () {
               SubpanelInitTabNames({/literal}{$tab_names}{literal} );
        {/literal}$( '.sub-panel .table-responsive' ).footable();{literal}
               // collapse subpanels when device is mobile / tablet
               if ( $( window ).width() <= SUGAR.measurements.breakpoints.large ) {
                  $('[id^=subpanel] .panel-collapse').removeClass('in');
                  $( '.panel-heading-collapse a' ).removeClass( 'in' );
                  $( '.panel-heading-collapse a' ).addClass( 'collapsed' );
               }
            }
            var SubpanelInitTabNames = function ( tabNames ) {
               subpanel_dd = new Array();
               j = 0;
               for ( i in tabNames ) {
                  subpanel_dd[j] = new ygDDList( 'whole_subpanel_' + tabNames[i] );
                  subpanel_dd[j].setHandleElId( 'subpanel_title_' + tabNames[i] );
                  subpanel_dd[j].onMouseDown = SUGAR.subpanelUtils.onDrag;
                  subpanel_dd[j].afterEndDrag = SUGAR.subpanelUtils.onDrop;
                  j++;
               }
               YAHOO.util.DDM.mode = 1;
            }
            currentModule = '{/literal}{$module}{literal}';
            SUGAR.util.doWhen(
                    "typeof(SUGAR.subpanelUtils) == 'object' && typeof(SUGAR.subpanelUtils.onDrag) == 'function'" +
                    " && document.getElementById('subpanel_list')",
                    SubpanelInit
                    );
        {/literal}
    </script>
{/if}
<script>
    var ModuleSubPanels = {$module_sub_panels};
    {literal}
        setTimeout( function () {
           if ( typeof SUGAR.subpanelUtils.currentSubpanelGroup !== "undefined" ) {
              SUGAR.subpanelUtils.loadSubpanelGroup( SUGAR.subpanelUtils.currentSubpanelGroup );
           }
        }, 500 );
    {/literal}
</script>


{*{/if}*}