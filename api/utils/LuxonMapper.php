<?php

namespace MintHCM\Utils;

class LuxonMapper
{

    private const REPLACEMENTS = [
        // Day
        'd' => 'dd',
        'D' => 'EEE',
        'j' => 'd',
        'l' => 'EEEE',
        'N' => 'E',
        'z' => 'o',
        // Week
        'W' => 'W',
        // Month
        'F' => 'MMMM',
        'm' => 'MM',
        'M' => 'MMM',
        'n' => 'M',
        // Year
        'Y' => 'yyyy',
        'y' => 'yy',
        // Time
        'a' => 'a',
        'A' => 'a',
        'g' => 'h',
        'G' => 'H',
        'h' => 'hh',
        'H' => 'HH',
        'i' => 'mm',
        's' => 'ss',
        'v' => 'SSS',
        // Timezone
        'e' => 'z',
        'O' => 'ZZZ',
        'P' => 'ZZ',
        // Full Date/Time
        'c' => "yyyy-MM-dd'T'HH:mm:ssZZ",
        'r' => "EEE, dd MMM yyyy HH:mm:ss ZZZ",
    ];

    public static function phpToLuxonFormat(string $format): string
    {
        $result = '';
        $length = strlen($format);
        $i = 0;
        while ($i < $length) {
            $char = $format[$i];
            if (isset(self::REPLACEMENTS[$char])) {
                $result .= self::REPLACEMENTS[$char];
            } else {
                $result .= $char;
            }
            $i++;
        }
        return $result;
    }

}
