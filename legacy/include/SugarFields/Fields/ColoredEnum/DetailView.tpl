<span style="{ {{sugarvar key='options_colors' string=true}}[{{sugarvar key='value' string=true}}] }">
{if is_string({{sugarvar key='options' string=true}})}
<input type="hidden" class="sugar_field" id="{{sugarvar key='name'}}" value="{ {{sugarvar key='options' string=true}} }">
{ {{sugarvar key='options' string=true}} } 
{else}
<input type="hidden" class="sugar_field" id="{{sugarvar key='name'}}" value="{ {{sugarvar key='value' string=true}} }">
{ {{sugarvar key='options' string=true}}[{{sugarvar key='value' string=true}}]}
{/if}
</span>

{{if !empty($displayParams.enableConnectors)}}
{{sugarvar_connector view='DetailView'}}
{{/if}}

