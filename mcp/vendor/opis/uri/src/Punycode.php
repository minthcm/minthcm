<?php
/* ============================================================================
 * Copyright 2021 Zindex Software
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================================ */

namespace Opis\Uri;

use Opis\String\UnicodeString;

final class Punycode
{
    private const BASE = 36;
    private const TMIN = 1;
    private const TMAX = 26;
    private const SKEW = 38;
    private const DAMP = 700;
    private const INITIAL_BIAS = 72;
    private const INITIAL_N = 0x80;
    private const PREFIX = 'xn--';
    private const PREFIX_LEN = 4;
    private const DELIMITER = 0x2D;
    private const MAX_INT = 0x7FFFFFFF;
    private const NON_ASCII = '#[^\0-\x7E]#';

    public static function encode(string $input): string
    {
        return implode('.', array_map([self::class, 'encodePart'], explode('.', $input)));
    }

    public static function decode(string $input): string
    {
        return implode('.', array_map([self::class, 'decodePart'], explode('.', $input)));
    }

    public static function normalize(string $input): string
    {
        return implode('.', array_map([self::class, 'normalizePart'], explode('.', $input)));
    }

    public static function encodePart(string $input): string
    {
        if (!preg_match(self::NON_ASCII, $input)) {
            return $input;
        }

        $input = UnicodeString::getCodePointsFromString($input, UnicodeString::LOWER_CASE);
        $input_len = count($input);

        $output = array_filter($input, static function (int $code): bool {
            return $code < 0x80;
        });

        if ($output) {
            $output = array_values($output);
        }

        $delta = 0;
        $n = self::INITIAL_N;
        $bias = self::INITIAL_BIAS;

        $handled = $basic_length = count($output);

        if ($basic_length) {
            $output[] = self::DELIMITER;
        }

        while ($handled < $input_len) {
            $m = self::MAX_INT;

            for ($i = 0;  $i < $input_len;  $i++) {
                if ($input[$i] >= $n && $input[$i] < $m) {
                    $m = $input[$i];
                }
            }

            if (($m - $n) > intdiv(self::MAX_INT - $delta, $handled + 1)) {
                throw new PunycodeException("Punycode overflow");
            }

            $delta += ($m - $n) * ($handled + 1);

            $n = $m;

            for ($i = 0;  $i < $input_len;  $i++) {
                if ($input[$i] < $n && (++$delta === 0)) {
                    throw new PunycodeException("Punycode overflow");
                }

                if ($input[$i] === $n) {
                    $q = $delta;
                    for ($k = self::BASE; ; $k += self::BASE) {
                        $t = self::threshold($k, $bias);
                        if ($q < $t) {
                            break;
                        }

                        $base_minus_t = self::BASE - $t;

                        $q -= $t;

                        $output[] = self::encodeDigit($t + ($q % $base_minus_t));

                        $q = intdiv($q, $base_minus_t);
                    }

                    $output[] = self::encodeDigit($q);

                    $bias = self::adapt($delta, $handled + 1, $handled === $basic_length);
                    $delta = 0;
                    $handled++;
                }
            }

            $delta++; $n++;
        }

        return self::PREFIX . UnicodeString::getStringFromCodePoints($output);
    }

    public static function decodePart(string $input): string
    {
        if (stripos($input, self::PREFIX) !== 0) {
            return $input;
        }

        $input = UnicodeString::getCodePointsFromString(substr($input, self::PREFIX_LEN), UnicodeString::LOWER_CASE);
        $input_len = count($input);

        $pos = array_keys($input, self::DELIMITER, true);
        if ($pos) {
            $pos = end($pos);
        } else {
            $pos = -1;
        }

        /** @var int $pos */

        if ($pos === -1) {
            $output = [];
            $pos = $output_len = 0;
        } else {
            $output = array_slice($input, 0, ++$pos);
            $output_len = $pos;
            for ($i = 0; $i < $pos; $i++) {
                if ($output[$i] >= 0x80) {
                    throw new PunycodeException("Non-basic code point is not allowed: {$output[$i]}");
                }
            }
        }

        $i = 0;
        $n = self::INITIAL_N;
        $bias = self::INITIAL_BIAS;

        while ($pos < $input_len) {
            $old_i = $i;

            for ($w = 1, $k = self::BASE; ; $k += self::BASE) {
                if ($pos >= $input_len) {
                    throw new PunycodeException("Punycode bad input");
                }

                $digit = self::decodeDigit($input[$pos++]);

                if ($digit >= self::BASE || $digit > intdiv(self::MAX_INT - $i, $w)) {
                    throw new PunycodeException("Punycode overflow");
                }

                $i += $digit * $w;

                $t = self::threshold($k, $bias);
                if ($digit < $t) {
                    break;
                }

                $t = self::BASE - $t;

                if ($w > intdiv(self::MAX_INT, $t)) {
                    throw new PunycodeException("Punycode overflow");
                }

                $w *= $t;
            }

            $output_len++;

            if (intdiv($i, $output_len) > self::MAX_INT - $n) {
                throw new PunycodeException("Punycode overflow");
            }

            $n += intdiv($i, $output_len);

            $bias = self::adapt($i - $old_i, $output_len, $old_i === 0);

            $i %= $output_len;

            array_splice($output, $i, 0, $n);

            $i++;
        }

        return UnicodeString::getStringFromCodePoints($output);
    }

    public static function normalizePart(string $input): string
    {
        $input = strtolower($input);

        if (strpos($input, self::DELIMITER) === 0) {
            self::decodePart($input); // just validate
            return $input;
        }

        return self::encodePart($input);
    }

    private static function encodeDigit(int $digit): int
    {
        return $digit + 0x16 + ($digit < 0x1A ? 0x4B: 0x00);
    }

    private static function decodeDigit(int $code): int
    {
        if ($code < 0x3A) {
            return $code - 0x16;
        }
        if ($code < 0x5B) {
            return $code - 0x41;
        }
        if ($code < 0x7B) {
            return $code - 0x61;
        }

        return self::BASE;
    }

    private static function threshold(int $k, int $bias): int
    {
        $d = $k - $bias;

        if ($d <= self::TMIN) {
            return self::TMIN;
        }

        if ($d >= self::TMAX) {
            return self::TMAX;
        }

        return $d;
    }

    private static function adapt(int $delta, int $num_points, bool $first_time = false): int
    {
        $delta = intdiv($delta, $first_time ? self::DAMP : 2);
        $delta += intdiv($delta, $num_points);

        $k = 0;
        $base_tmin_diff = self::BASE - self::TMIN;
        $lim = $base_tmin_diff * self::TMAX / 2;

        while ($delta > $lim) {
            $delta = intdiv($delta, $base_tmin_diff);
            $k += self::BASE;
        }

        $k += intdiv(($base_tmin_diff + 1) * $delta, $delta + self::SKEW);

        return $k;
    }
}