<?php

namespace MintHCM\AI\Tools\Utils;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * Utility class providing date-time conversion utilities between GMT and user's timezone.
 */
class DateTimeConversion
{
    public static function toUserTZ(string $date): string
    {
        global $timedate, $current_user;

        [$date_time, $output_format] = self::parseDate($date, new \DateTimeZone('GMT'));

        return $timedate->tzUser($date_time, $current_user)->format($output_format);
    }

    public static function fromUserTZ(string $date): string
    {
        global $timedate, $current_user;

        $user_t_z = $timedate::userTimezone($current_user);
        [$date_time, $output_format] = self::parseDate($date, new \DateTimeZone($user_t_z));

        return $timedate->tzGMT($date_time)->format($output_format);
    }

    public static function formatDate(string $date): string
    {
        if ($date === '') {
            return '';
        }

        [$date_time, $output_format] = self::parseDate($date);

        return $date_time->format($output_format);
    }

    private static function parseDate(string $date, ?\DateTimeZone $timezone = null): array
    {
        global $timedate;

        $formats = [
            ['input' => $timedate->get_db_date_format(),      'output' => $timedate->get_db_date_format()],
            ['input' => $timedate->get_db_date_time_format(), 'output' => $timedate->get_db_date_time_format()],
            ['input' => $timedate->get_date_format(),         'output' => $timedate->get_db_date_format()],
            ['input' => $timedate->get_date_time_format(),    'output' => $timedate->get_db_date_time_format()],
        ];

        foreach ($formats as $format) {
            $date_time = $timezone
                ? \DateTime::createFromFormat($format['input'], $date, $timezone)
                : \DateTime::createFromFormat($format['input'], $date);

            if ($date_time !== false) {
                return [$date_time, $format['output']];
            }
        }

        throw new \InvalidArgumentException("Invalid date format: {$date}");
    }
}
