{$start_sectionuse}
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use MintHCM\Data\ORM\Doctrine\MintEntity\MintEntity;
{if !empty($additionalUseStatements)}
{foreach from=$additionalUseStatements item=useStatement}
{$useStatement};
{/foreach}
{/if}
{$end_sectionuse}