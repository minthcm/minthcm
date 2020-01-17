<div>
    <span class="required validation-message">{$message}</span>
    <table id="questionTable" class="table table-bordered">
        <tr>
            <th>
                {* View Tools START *}
                {*Question*}
                {$MOD.LBL_QUESTION}
                {* View Tools END *}
            </th>
            <th>
                {* View Tools START *}
                {*Text*}
                {$MOD.LBL_TEXT}
                {* View Tools END *}
            </th>
            <th>
                {* View Tools START *}
                {*Type*}
                {$MOD.LBL_TYPE}
                {* View Tools END *}
            </th>
        </tr>
        {foreach from=$questions item=question}
            <tr>
                <td>
                    Q{$question.sort_order+1}
                </td>
                <td>
                    {$question.name}
                </td>
                <td>
                    {* View Tools START *}
                    {*{$question.type}*}
                    {$APP_LIST.surveys_question_type[$question.type]}
                    {* View Tools END *}
                </td>
            </tr>
        {/foreach}
    </table>
</div>
