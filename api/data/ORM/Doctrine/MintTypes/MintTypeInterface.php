<?php 

namespace MintHCM\Data\ORM\Doctrine\MintTypes;

use Doctrine\DBAL\Platforms\AbstractPlatform;

interface MintTypeInterface
{
    public function getName();

    public function convertToPHPValue($value, AbstractPlatform $platform);

    public function convertToDatabaseValue($value, AbstractPlatform $platform);

    /**
     * Convert the value to an external format suitable for APIs or other external consumers.
     * @param mixed $value The value from php format to be converted.
    */
    public function convertToExternalFormat($value);
}