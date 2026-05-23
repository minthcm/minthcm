{$start_sectionproperties}

{if $generate_custom_entity}
{if $isCustom}
{include file="$custom_entity_fields_tpl"}
{else}
{include file="$main_entity_fields_tpl"}
{/if}
{/if}
{foreach from=$fields item=field}
    /**
{if $field.isId}
     * @ORM\Id
{if $field.CustomIdGenerator}
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator({$field.CustomIdGenerator})
{/if}
{/if}
{if $field.columnAttributes}
     * @ORM\Column({$field.columnAttributes})
{/if}
{if $field.attributes}
{foreach from=$field.attributes item=attribute}
     * {$attribute}
{/foreach}
{/if}
     */
    protected ${$field.name};

{/foreach}
{foreach from=$relationshipFields item=relationshipField}
    /**
{foreach from=$relationshipField.attributes item=attribute}
     * {$attribute}
{/foreach}
     */
    protected {if $relationshipField.isCollection}Collection {/if}${$relationshipField.name};

{/foreach}
    {$end_sectionproperties}