{$Flash}

{if !$Flash}
   <div class="clear-all-alerts-container">
      <a class="clear-all-alerts-btn btn btn-warning btn-xs">{sugar_translate label="LBL_CLEARALL"}</a>
      {literal}
         <script>
            $('.clear-all-alerts-btn').unbind('click').click(function (event) {
               $('.desktop_notifications:first .alert-dismissible .close').each(function (i, v) {
                  $(v).click();
               });
            });
         </script>
      {/literal}
   </div>
{/if}
{foreach from=$Results item=result}
   <div class="alert alert-{if $result->type != null}{$result->type}{else}info{/if} alert-dismissible module-alert" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="Alerts.prototype.markAsRead('{$result->id}');"><span aria-hidden="true">&times;</span></button>
      {if $result->alert_type == 'custom'}
      {if $result->url_redirect != null}
      <a href="{$result->url_redirect}" onclick="Alerts.prototype.markAsRead('{$result->id}');">
      {/if}
         <h4 class="alert-header">
               <strong class="text-{if $result->type != null}{$result->type}{else}info{/if}">{$result->name|nl2br}</strong>
         </h4>
         <p class="alert-body">
            {$result->description|nl2br}
         </p>
      {if $result->url_redirect != null}
      </a>
      {/if}
      {else}
      {if $result->url_redirect != null && !($result->url_redirect|strstr:"fake_") }
      <a href="index.php?module=Alerts&action=redirect&record={$result->id}" onclick="Alerts.prototype.markAsRead('{$result->id}');">
      {/if}
         <h4 class="alert-header">
               <span class="alert-link text-{if $result->type != null}{$result->type}{else}info{/if}" >
               {if $result->target_module != null }
                    {* Pluralize the module name if necessary. *}
                    <span class="suitepicon suitepicon-module-{$result->target_module|lower|replace:'_':'-'}{if substr($result->target_module, -1) !== 's'}s{/if}"></span>
                    <strong class="text-{if $result->type != null}{$result->type}{else}info{/if}">{$result->target_module}</strong>
               {else}
                  <strong class="text-{if $result->type != null}{$result->type}{else}info{/if}">Alert</strong>
               {/if}
               </span>
         </h4>
        {if $result->url_redirect != null}
         </a>
         {/if}
         <p class="alert-body addReadMore showlesscontent">
            {$result->name|nl2br}<br/>
            {assign var="fulldesc" value=$result->description|nl2br}
            {assign var="firstSet" value=$fulldesc|truncate:200:"...":true}
            {assign var="desc_count" value=$fulldesc|count_characters:true}
            {assign var="secdHalf" value=$fulldesc|@substr:200:$desc_count}
            {$firstSet}
            {if $desc_count > 200}
            <span class='SecSec'>{$secdHalf}</span>
            <span onclick="Alerts.prototype.showHideDescription(event)" class='readMore'  title='{sugar_translate label="LBL_SHOW_MORE"}'>{sugar_translate label="LBL_SHOW_MORE"}</span>
            <span onclick="Alerts.prototype.showHideDescription(event)" class='readLess' title='{sugar_translate label="LBL_SHOW_LESS"}'> {sugar_translate label="LBL_SHOW_LESS"} </span>
            {/if}
         </p>
      {/if}
   </div>
{/foreach}

