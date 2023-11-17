{if $record_level == 1 && !$is_first}
<tr class="separator"><td colspan="{$column_count}"></td></tr>
{include file='header_row.tpl'}
{/if}
<tr{if $record_level < 5} class="color-level-{$record_level}{if $has_children && $record_level == 2} has_children{/if}"{/if}>{$record_cells}</tr>