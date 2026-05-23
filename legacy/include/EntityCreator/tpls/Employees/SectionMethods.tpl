{$start_sectionmethods}
    public function __construct()
    {ldelim}
{foreach from=$constructorFields item=constructedField}
        {$constructedField}
{/foreach}
    {rdelim}
{literal}
    /**
     * Get the fullname 
     *
     * @return string
     */
    public function getFullName(): string
    {
        $names = [];
        if (!empty($this->first_name)) {
            $names[] = $this->first_name;
        }
        if (!empty($this->last_name)) {
            $names[] = $this->last_name;
        }

        return !empty($names) ? implode(' ', $names) : '';
    }
{/literal}
{literal}
    public function getName(): ?string
    {
        return $this->getFullName();
    }
{/literal}
{if $generate_custom_entity}
    {if $isCustom}
        {include file="$custom_entity_methods_tpl"}
    {else}
        {include file="$main_entity_methods_tpl"}
    {/if}
{/if}
{foreach from=$additionalMethods item=method}
        {$method}
{/foreach}
    {$end_sectionmethods}