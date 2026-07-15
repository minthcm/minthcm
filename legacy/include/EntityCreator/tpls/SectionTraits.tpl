{$start_sectiontraits}
{if !empty($traits)}
{foreach from=$traits item=trait}
    use {$trait};
{/foreach}

{/if}
{$end_sectiontraits}
