{$start_sectionrepository}

/**
{if $repositorySet}
 * @ORM\Entity(repositoryClass="{$repositoryClassPath}")
{else}
 * @ORM\Entity
{/if}
 * @ORM\Table(name="{$table}"{if !empty($indexes)}, indexes={ldelim}
{foreach from=$indexes item=index}
 * @ORM\Index(name="{$index.name}", columns={ldelim}"{$index.columns}"{rdelim}){if !$index@last}, {"\n"}{/if}
{/foreach}
{rdelim}{/if})
{foreach from=$fields item=field}
 * @property mixed ${$field.name}
{/foreach}
 */
{$end_sectionrepository}