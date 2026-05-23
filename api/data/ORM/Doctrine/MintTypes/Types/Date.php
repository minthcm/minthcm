<?php
namespace MintHCM\Data\ORM\Doctrine\MintTypes\Types;

use DateTimeInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateType;
use MintHCM\Data\MintDateTime;
use MintHCM\Data\ORM\Doctrine\MintTypes\MintTypeInterface;

/**
 * Type that maps an SQL DATETIME/TIMESTAMP to a PHP MintDateTime object.
 */
class Date extends DateType implements MintTypeInterface
{

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof MintDateTime) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            $value = $value->format($platform->getDateFormatString());
        }

        $dateTime = MintDateTime::getMintDateTimeFromString($value);
        if ($dateTime !== false) {
            return $dateTime;
        }

        throw ConversionException::conversionFailedFormat(
            $value,
            $this->getName(),
            $platform->getDateFormatString(),
        );
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return '';
        }

        if ($value instanceof MintDateTime) {
            return $value->toDatabaseDateFormat(true);
        }

        if ($value instanceof DateTimeInterface) {
            $value =  MintDateTime::createFromFormat(
                $platform->getDateFormatString(), 
                $value->format($platform->getDateFormatString())
            );
            if ($value instanceof MintDateTime) {
                return $value->toDatabaseDateFormat(true);
            }
        }

        if (is_string($value)) {
            $dt = MintDateTime::getMintDateTimeFromString($value);
            if ($dt !== null) {
                return $dt->toDatabaseDateFormat(true);
            }
        }

        throw ConversionException::conversionFailedInvalidType(
            $value,
            $this->getName(),
            ['null', 'string', \DateTime::class, MintDateTime::class],
        );
    }

    public function convertToExternalFormat($value)
    {
        if ($value instanceof MintDateTime) {
            return $value->toFrontendFormat();
        }

        return null;
    }
}
