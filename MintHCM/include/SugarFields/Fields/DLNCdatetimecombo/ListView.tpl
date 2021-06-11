{if $has_access==1}
    {sugar_fetch object=$parentFieldArray key=$col}
    {else}
    <span>{$APP.LBL_NO_ACCESS}</span>
{/if}