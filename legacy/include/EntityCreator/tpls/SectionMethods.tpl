{$start_sectionmethods}
    public function __construct()
    {ldelim}
{foreach from=$constructorFields item=constructedField}
        {$constructedField}
{/foreach}
    {rdelim}

{if $generate_custom_entity}
    {if $isCustom}
        {include file="$custom_entity_methods_tpl"}
    {else}
        {include file="$main_entity_methods_tpl"}
    {/if}
{/if}
{foreach from=$additionalMethods item=method}
        {$method}
{/foreach}
    {$end_sectionmethods}