<div style='width: 500px'>
    <form name='configure_{$id}' action="index.php" method="post" onSubmit='return SUGAR.dashlets.postForm("configure_{$id}", SUGAR.mySugar.uncoverPage);'>
        <input type='hidden' name='id' value='{$id}'>
        <input type='hidden' name='action' value='ConfigureDashlet'>
        <input type='hidden' name='configure' value='true'>
        <table width="400" cellpadding="0" cellspacing="0" border="0" class="edit view" align="center">
            <tr>
                <td valign='top' nowrap class='dataLabel'>{$title_label}</td>
                <td valign='top' class='dataField'>
                    <input type="text" class="text" name="title" size='20' value='{$title}'>
                </td>
            </tr>
            <tr>
                <td valign='top' nowrap class='dataLabel'>{$kanban_module_label}</td>
                <td valign='top' class='dataField'>
                    <select name="kanban_module" value='{$kanban_module}'>
                    {foreach from=$kanban_module_list item=item key=key}
                        {if $kanban_module == $key}
                            <option value={$key} selected>{$item}</option>   
                        {else}                 
                            <option value={$key}>{$item}</option>
                        {/if}               
                    {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right" colspan="2">
                    <input type='submit' class='button' value='{$save_label}'>
                </td>
            </tr>
        </table>
    </form>
</div>