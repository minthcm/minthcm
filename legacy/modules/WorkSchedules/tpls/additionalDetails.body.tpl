<input id="type" type="hidden" value="{$OBJECT_NAME}"/>
{if !empty($FIELD.ID)}
    <input id="id" type="hidden" value="{$FIELD.ID}"/>
{/if}

{if !empty($FIELD.NAME)}
    <div>
        <strong>{$MOD.LBL_SUBJECT}</strong>
        <a href="index.php?action=DetailView&module={$MODULE_NAME}&record={$FIELD.ID}">{$FIELD.NAME}</a>
    </div>
{/if}

{if !empty($FIELD.STATUS)}
    <div data-field="STATUS">
        <strong>{$MOD.LBL_STATUS}</strong>
        {$FIELD.STATUS}
    </div>
{/if}

{if !empty($FIELD.DATE_START)}
    <div data-field="DATE_START">
        <strong>{$MOD.LBL_DATE_START}</strong>
        {$FIELD.DATE_START}
    </div>
{/if}

{if !empty($FIELD.DATE_END)}
    <div data-field="DATE_END">
        <strong>{$MOD.LBL_DATE_END}</strong>
        {$FIELD.DATE_END}
    </div>
{/if}

{if !empty($FIELD.DURATION_HOURS) || !empty($FIELD.DURATION_MINUTES)}
    <div>
        <strong>{$MOD.LBL_DURATION}</strong>
        {$FIELD.DURATION_HOURS} {$MOD.LBL_HOURS_HOURS} {$FIELD.DURATION_MINUTES} {$MOD.LBL_HOURS_MINUTES}
    </div>
{/if}

{if !empty($FIELD.ASSIGNED_USER_NAME)}
    <div>
        <strong>{$MOD.LBL_ASSIGNED_TO_NAME}</strong>
        {$FIELD.ASSIGNED_USER_NAME}
    </div>
{/if}

{if !empty($FIELD.DESCRIPTION)}
    <div>
        <strong>{$MOD.LBL_DESCRIPTION}</strong>
        {$FIELD.DESCRIPTION}
    </div>
{/if}
