<span style="{$style}">
{if !empty($parentFieldArray.$col)}
    {assign var="item" value=$parentFieldArray.$col}
    {if !empty($vardef.options_list)}
        {$vardef.options_list.$item}
    {else}
        {$item}
    {/if}
{/if}
</span>
