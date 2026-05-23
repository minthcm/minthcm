<td{if $column_data.dataIndex == 'tree_column'} class="tree_column"{/if}>
{if $column_data.dataIndex == 'tree_column' && $record_level != 1}
<table style="width: 100%"><tr><td style="width: {$record_level*3}%;"></td><td style="width: {$record_level*-3+100}%;">{$cell_text}</td></tr></table>
{else}
{$cell_text}
{/if}
</td>