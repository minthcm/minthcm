{$start_sectionmethods}
{literal}
    public function getContentsAsArray(): array
    {
        if (!is_string($this->contents)) {
            return [];
        }
        
        $contents = unserialize(base64_decode($this->contents) ?: '');
        if (!is_array($contents)) {
            $contents = [];
        }
        return $contents;
    }
{/literal}
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