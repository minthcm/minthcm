<div>
    <table id="{$question.id}List" style="display: none; width:33%" class="table table-bordered">
        <tr>
            <th>
                {$mod.LBL_RESPONSE_ANSWER}
            </th>
            <th>
                {$mod.LBL_RESPONSE_EMPLOYEE}
            </th>
            <th>
                {$mod.LBL_RESPONSE_TIME}
            </th>
        </tr>
        {foreach from=$question.responses item=response}
            <tr>
                <td>
                    {$response.answer}
                </td>
                <td>
                {if $response.employee}
                    <a href="index.php?module=Employees&action=DetailView&record={$response.employee.id}">
                        {$response.employee.id}
                    </a>
                {/if}
                </td>
                <td>
                    {$response.time}
                </td>
            </tr>
        {/foreach}
    </table>
    <a href="#" class="showHideResponses" data-question-id="{$question.id}">{$mod.LBL_SHOW_RESPONSES}</a>
</div>