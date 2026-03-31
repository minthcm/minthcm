    /**
     * @ORM\Id
     * @ORM\Column(name="id_c", type="string", length=36)
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $id;

{literal}
    /**
     * @ORM\OneToOne(targetEntity="{/literal}{$className|regex_replace:'/_cstm$/i':''}{literal}", inversedBy="custom_entity")
     * @ORM\JoinColumn(name="id_c", referencedColumnName="id")
     */
    protected $main_entity;
{/literal}

