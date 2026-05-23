<script>
    {literal}
        if ( typeof sqs_objects == 'undefined' ) {
           var sqs_objects = new Array;
        }
    {/literal}
</script>
<script src="modules/AppraisalItems/LineItems/LineItemsEditView.js"></script>
<div class="row" id="lineItems">
    <div class="col-xs-12 col-sm-4 edit-view-row-item">{$APP.LBL_APPRAISAL_ITEM_SUBJECT}</div>
    <div class="col-xs-12 col-sm-2 edit-view-row-item">{$APP.LBL_APPRAISAL_ITEM_VALUE}</div>
    <div class="col-xs-12 col-sm-5 edit-view-row-item">{$APP.LBL_APPRAISAL_ITEM_DESCRIPTION}</div>
    <div class="clear"></div>
</div>
<div class="custom_buttons" style="padding-top: 10px; padding-bottom: 10px;">
    <input type="button" tabindex="116" class="button process_button" value="{$APP.LBL_APPRAISAL_ITEM_ADD}" id="addAppraisalItem" onclick="insertAppraisalItem();" />
</div>
<script>
    {section name=appraisal_item loop=$APPRAISAL_ITEMS}
        insertAppraisalItem( '{$APPRAISAL_ITEMS[appraisal_item]}' );
    {/section}
</script>
