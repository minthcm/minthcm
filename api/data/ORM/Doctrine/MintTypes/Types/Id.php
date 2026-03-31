<?php
namespace MintHCM\Data\ORM\Doctrine\MintTypes\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;


class Id extends StringType
{
    const ID = 'id';
    
    public function getName()
    {
        return self::ID;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        try {
            return (string) $value;
        } catch (\Exception $e) {
            throw ConversionException::conversionFailedInvalidType(
                $value,
                $this->getName(),
                ['null', 'string'],
            );
        }
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        try {
            return (string) $value;
        } catch (\Exception $e) {
            throw ConversionException::conversionFailedInvalidType(
                $value,
                $this->getName(),
                ['null', 'string'],
            );
        }
    }
}