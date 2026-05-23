{{assign var=fieldName value=$vardef.name}}
{{if strval($parentFieldArray->$fieldName) == "1"}}
{{assign var="checked" value="CHECKED"}}
{{else}}
{{assign var="checked" value=""}}
{{/if}}
<input type="checkbox" class="checkbox" disabled="true" {{$checked}}>