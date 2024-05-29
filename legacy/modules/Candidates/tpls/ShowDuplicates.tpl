<span class="color">{$ERROR}</span>

{$FORM_TITLE}

<form action='index.php' method='post' name='Save'>
<input type="hidden" name="module" value="{$MODULE}">
<input type="hidden" name="return_module" value="{$RETURN_MODULE}">
<input type="hidden" name="return_action" value="{$RETURN_ACTION}">
<input type="hidden" name="return_id" value="{$RETURN_ID}">
<input type="hidden" name="dup_checked" value="true">
<input type="hidden" name="action" value="">
{$INPUT_FIELDS}
<p>
<table class='{$TABLECLASS}' cellpadding="0" cellspacing="0" width="100%" border="0" >
	<tr><td>
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
			<tr>
				<td valign='top' align='left' border='0' class="{$CLASS}">
					<h4 class="{$CLASS}">{$FORMHEADER}</h4>
				</td>
			</tr>
			<tr>
				<td valign='top' align='left'>
					{$FORMBODY}{$FORMFOOTER}{$POSTFORM}
				</td>
			</tr>
		</table>
	</td></tr>
</table>
</p>
