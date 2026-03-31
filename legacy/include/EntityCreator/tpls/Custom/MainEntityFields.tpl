{literal}
    /**
     * @ORM\OneToOne(
     *     targetEntity="{/literal}{$className}{literal}_cstm",
     *     mappedBy="main_entity",
     *     cascade={"persist", "remove"},
     *     fetch="EAGER"
     * )
     */
    protected $custom_entity;
{/literal}