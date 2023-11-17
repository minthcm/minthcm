<div onmouseover="this.style.cursor = 'move';" id="dashlet_header_{$DASHLET_ID}" class="hd dashlet">
    <div class="tl"></div>
    <div class="hd-center">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="formHeader h3Row">
            <tr>
                <td class="dashlet-title" colspan="2">
                    <h3>
                        <span class="{get_module_icon_class module_name=$DASHLET_MODULE}"></span>
                        <span>{$DASHLET_TITLE}</span>
                    </h3>
                </td>
                <td style="padding-right: 0px;" nowrap="" width="1%">
                    <div class="dashletToolSet">
                        {* Mint start #52883 *}
                        {if !$is_locked}
                        <a href="javascript:void(0)" title="{$DASHLET_BUTTON_ARIA_EDIT}" aria-label="{$DASHLET_BUTTON_ARIA_EDIT}" onclick="SUGAR.mySugar.configureDashlet( '{$DASHLET_ID}' );
                               return false;">
                            <span class="suitepicon suitepicon-action-edit"></span>
                        </a>
                        {/if}
                        {* Mint end #52883 *}
                        {* viewTools *}
                        {*<a href="javascript:void(0)" title="{$DASHLET_BUTTON_ARIA_REFRESH}" aria-label="{$DASHLET_BUTTON_ARIA_REFRESH}" onclick="SUGAR.mySugar.retrieveCurrentPage();*}
                        <a href="javascript:void(0)" title="{$DASHLET_BUTTON_ARIA_REFRESH}" aria-label="{$DASHLET_BUTTON_ARIA_REFRESH}" onclick="SUGAR.mySugar.retrieveDashlet( '{$DASHLET_ID}', '' );
                               return false;">
                            <span class="suitepicon suitepicon-action-reload"></span>
                        </a>
                        {* viewTools end *}
                        {* Mint start #52883 *}
                        {if !$is_locked}
                        <a href="javascript:void(0)" title="{$DASHLET_BUTTON_ARIA_DELETE}" aria-label="{$DASHLET_BUTTON_ARIA_DELETE}" onclick="SUGAR.mySugar.deleteDashlet( '{$DASHLET_ID}' );
                               return false;">
                            <span class="suitepicon suitepicon-action-clear"></span>
                        </a>
                        {/if}
                        {* Mint end #52883 *}
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="tr"></div>
</div>
<div class="bd">
    <div class="ml"></div>
    <div class="bd-center">
