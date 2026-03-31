<?php

namespace MintMCP\Tools\Utils;

/**
 * Utility class providing date-time conversion utilities between GMT and user's timezone.
 */
class DateTimeConversion
{
    /**
     * Converts a date string from GMT to the current user's timezone.
     * @param string $date Date string in GMT
     * @return string Date string in user's timezone
     */
    public static function toUserTZ(string $date): string
    {
        global $timedate, $current_user;

        [$dateTime, $outputFormat] = self::parseDate($date, new \DateTimeZone('GMT'));

        return $timedate->tzUser($dateTime, $current_user)->format($outputFormat);
    }

    /**
     * Converts a date string from the current user's timezone to GMT.
     * @param string $date Date string in user's timezone
     * @return string Date string in GMT
     */
    public static function fromUserTZ(string $date): string
    {
        global $timedate, $current_user;

        $userTZ = $timedate::userTimezone($current_user);
        [$dateTime, $outputFormat] = self::parseDate($date, new \DateTimeZone($userTZ));

        return $timedate->tzGMT($dateTime)->format($outputFormat);
    }

    /**
     * Converts a date string to database format without changing timezone.
     * @param string $date Date string in any supported format
     * @return string Date string in database format (preserving timezone)
     */
    public static function formatDate(string $date): string
    {
        [$dateTime, $outputFormat] = self::parseDate($date);

        return $dateTime->format($outputFormat);
    }

    /**
     * Parses a date string using multiple formats and returns the DateTime object and the format used.
     * @param string $date Date string to parse
     * @param \DateTimeZone|null $timezone Timezone to use for parsing
     * @return array [\DateTime $dateTime, string $formatUsed]
     */
    private static function parseDate(string $date, ?\DateTimeZone $timezone = null): array
    {
        global $timedate;

        $formats = [
            ['input' => $timedate->get_db_date_format(), 'output' => $timedate->get_db_date_format()],
            ['input' => $timedate->get_db_date_time_format(), 'output' => $timedate->get_db_date_time_format()],
            ['input' => $timedate->get_date_format(), 'output' => $timedate->get_db_date_format()],
            ['input' => $timedate->get_date_time_format(), 'output' => $timedate->get_db_date_time_format()],
        ];

        foreach ($formats as $format) {
            $dateTime = $timezone 
                ? \DateTime::createFromFormat($format['input'], $date, $timezone)
                : \DateTime::createFromFormat($format['input'], $date);
            
            if ($dateTime !== false) {
                return [$dateTime, $format['output']];
            }
        }

        throw new \InvalidArgumentException("Invalid date format: {$date}");
    }
}