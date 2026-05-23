<table style="width: 400px; max-width: 400px; border-spacing: 5px; border-collapse: separate;">
    <tr>
        <th>{$APP.LBL_APPRAISAL_ITEM_NAME}</th>
        <th>{$APP.LBL_APPRAISAL_ITEM_VALUE}</th>
        <th>{$APP.LBL_APPRAISAL_ITEM_SUBJECT}</th>
    </tr>
    {section name=appraisal_item loop=$APPRAISAL_ITEMS}
        <tr>
            <td><a href="index.php?module=AppraisalItems&action=DetailView&record={$APPRAISAL_ITEMS[appraisal_item].id}">{$APPRAISAL_ITEMS[appraisal_item].name}</a></td>
            <td>{assign var="item" value=$APPRAISAL_ITEMS[appraisal_item].value}{$APP_LIST_STRINGS.rating_list.$item}</td>
            <td><a href="index.php?module={$APPRAISAL_ITEMS[appraisal_item].parent_type}&action=DetailView&record={$APPRAISAL_ITEMS[appraisal_item].parent_id}">{$APPRAISAL_ITEMS[appraisal_item].parent_name}</a></td>
        </tr>
    {/section}
</table>
