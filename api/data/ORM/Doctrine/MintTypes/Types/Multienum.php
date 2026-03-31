<?php
namespace MintHCM\Data\ORM\Doctrine\MintTypes\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use MintHCM\Data\ORM\Doctrine\MintTypes\MintTypeInterface;

/**
 * Field type mapping for the Doctrine Database Abstraction Layer (DBAL).
 *
 * Multienum fields will be stored as a string in the database and converted back to
 * the array when querying.
 */
class Multienum extends StringType implements MintTypeInterface
{
    const MULTIENUM = 'multienum';
    
    public function getName()
    {
        return self::MULTIENUM;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $this->convertValueToArray($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        if (is_string($value)) {
            return $value;
        }

        if (!is_array($value)) {
            throw new \InvalidArgumentException('Invalid multienum value, array expected');
        }
    
        return $this->convertValueToString($value);
    }

    /**
     * {@inheritDoc}
     */
    public function convertToExternalFormat($value)
    {
        return $value;
    }

    protected function convertValueToArray($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (empty($value)) {
            return [];
        }
        
        if (substr($value, 0, 1) == '^' && substr($value, -1) == '^') {
            $value = substr(substr($value, 1), 0, strlen($value) - 2);
        }
    
        return explode('^,^', $value);
    }

    protected function convertValueToString(array $value): string
    {
        if (empty($value)) {
            return '';
        }
    
        return '^' . implode('^,^', $value) . '^';
    }
}