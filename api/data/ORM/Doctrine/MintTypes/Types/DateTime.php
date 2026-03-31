<?php
namespace MintHCM\Data\ORM\Doctrine\MintTypes\Types;

use DateTimeInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;
use MintHCM\Data\MintDateTime;
use MintHCM\Data\ORM\Doctrine\MintTypes\MintTypeInterface;

/**
 * Type that maps an SQL DATETIME/TIMESTAMP to a PHP MintDateTime object.
 */
class DateTime extends DateTimeType implements MintTypeInterface
{

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof MintDateTime) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            $value = $value->format($platform->getDateTimeFormatString());
        }

        $dt = MintDateTime::getMintDateTimeFromString($value);

        if ($dt !== false) {
            return $dt;
        }

        throw ConversionException::conversionFailedFormat(
            $value,
            $this->getName(),
            $platform->getDateTimeFormatString()
        );
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof MintDateTime) {
            return $value->toDatabaseFormat(true);
        }

        if ($value instanceof DateTimeInterface) {
            $value =  MintDateTime::createFromFormat(
                $platform->getDateTimeFormatString(), 
                $value->format($platform->getDateTimeFormatString())
            );
            if ($value instanceof MintDateTime) {
                return $value->toDatabaseFormat(true);
            }
        }

        if (is_string($value)) {
            $dt = MintDateTime::getMintDateTimeFromString($value);
            if ($dt !== null) {
                return $dt->toDatabaseFormat(true);
            }
        }


        throw ConversionException::conversionFailedInvalidType(
            $value,
            $this->getName(),
            ['null', 'string', \DateTime::class, MintDateTime::class],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function convertToExternalFormat($value)
    {
        if ($value instanceof MintDateTime) {
            return $value->toFrontendFormat();
        }

        return null;
    }

}