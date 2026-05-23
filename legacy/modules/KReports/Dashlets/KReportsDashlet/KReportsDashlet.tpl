<div id="KR{$this->id}">
    {if $report_id != ''}
        {if $show_chart == 0 && $show_data == 0}
            <em>{$dashletStrings.LBL_SELECT_DATA}</em>
        {else}
            <iframe src="index.php?module=KReports&action=IframeView&record={$report_id}&show_chart={$show_chart}&show_data={$show_data}&show_filters={$show_filters}" 
                    scrolling="yes" width="100%" height="{$height}" frameBorder="0" ></iframe>
            {/if}
        {else}
        <em>{$dashletStrings.LBL_SELECT_REPORT}</em>
    {/if}
</div>