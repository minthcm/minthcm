{$start_sectionmethods}
    public function __construct()
    {ldelim}
{foreach from=$constructorFields item=constructedField}
        {$constructedField}
{/foreach}
    {rdelim}
{literal}
    public function getIdentifier(): string
    {
        return $this->id;
    }
{/literal}
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
{literal}
    /**
     * Check that password matches existing hash
     * @param string $password Plaintext password
     */
    public function checkPassword(string $password): bool
    {
        if (empty($this->user_hash)) {
            return false;
        }

        $passwordMd5 = md5($password);
        if ($this->user_hash[0] !== '$' && strlen($this->user_hash) === 32) {
            // Legacy md5 password
            return strtolower($passwordMd5) === $this->user_hash;
        }

        return password_verify(strtolower($passwordMd5), $this->user_hash);
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