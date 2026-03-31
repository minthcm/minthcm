<?php

namespace MintHCM\Data;

use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Api\Entities\Users;
use MintHCM\Api\Entities\UserPreferences;
use MintHCM\Api\Repositories\UserPreferencesRepository;

class MintDateTime extends \DateTime
{
    private const PREFERENCE_DATE_FORMAT_KEY = 'datef';
    private const PREFERENCE_TIME_FORMAT_KEY = 'timef';
    private const PREFERENCE_TIMEZONE_KEY = 'timezone';

    public const DATEBASE_TIMEZONE = 'UTC';
    public const FRONTEND_COMMUNICATION_FORMAT = self::ATOM;
    public const FRONTEND_COMMUNICATION_FORMAT_WITHOUT_TZ = 'Y-m-d\TH:i:s';
    public const FRONTEND_COMMUNICATION_FORMAT_DATE_ONLY = 'Y-m-d';

    private static $users_preferences = [];

    public function __construct(string $datetime = 'now', ?\DateTimeZone $timezone = null)
    {
        if ($timezone === null) {
            $timezone = new \DateTimeZone(self::DATEBASE_TIMEZONE);
        }
        parent::__construct($datetime, $timezone);
    }

    public static function createFromFormat(string $format, string $datetime_str, ?\DateTimeZone $timezone = null): MintDateTime|false
    {
        if ($timezone === null) {
            $timezone = new \DateTimeZone(self::DATEBASE_TIMEZONE);
        }

        $dt = parent::createFromFormat($format, $datetime_str, $timezone);
        if ($dt === false) {
            return false;
        }
        return new MintDateTime($dt->format('Y-m-d H:i:s'), $dt->getTimezone());
    }

    /*
        * Tries to create a MintDateTime from a string using various common formats.
        * Returns null if no format matched.
        * @param string $datetime_str - The datetime string to parse.
        * @param Users|null $user - The user whose preferences to use for parsing.
    */
    public static function getMintDateTimeFromString(string $datetime_str, ?Users $user = null): ?MintDateTime
    {
        $dt = MintDateTime::createFromFrontendFormat($datetime_str);
        if ($dt !== null) {
            return $dt;
        }
        $base = new MintDateTime();
        $formats = [
            $base->getDatabaseDateTimeFormat(),
            $base->getDatabaseDateFormat(),
            $base->getUserDateTimeFormat($user),
            $base->getUserDateFormat($user),
        ];
        foreach ($formats as $format) {
            $dt = self::createFromFormat($format, $datetime_str);
            if ($dt !== false) {
                return $dt;
            }
        }
        return null;
    }

    // Frontend communication methods

    public static function createFromFrontendFormat(string $datetime_str): ?MintDateTime
    {
        $dt = self::createFromFormat(self::FRONTEND_COMMUNICATION_FORMAT, $datetime_str);
        if ($dt !== false) {
            return $dt;
        }
        $dt = self::createFromFormat(self::FRONTEND_COMMUNICATION_FORMAT_WITHOUT_TZ, $datetime_str);
        if ($dt !== false) {
            return $dt;
        }
        $dt = self::createFromFormat(self::FRONTEND_COMMUNICATION_FORMAT_DATE_ONLY, $datetime_str);
        if ($dt !== false) {
            $dt->setTime(0, 0, 0);
            return $dt;
        }
        return null;
    }

    /*
        * Returns the datetime in frontend communication format (ISO 8601)
    */
    public function toFrontendFormat(): string
    {
        return $this->format($this::FRONTEND_COMMUNICATION_FORMAT);
    }

    // End Frontend communication methods

    // Database methods

    public function getDatabaseDateFormat(): string
    {
        $entityManager = $this->getEntityManager();
        return $entityManager->getConnection()->getDatabasePlatform()->getDateFormatString();
    }

    public function getDatabaseTimeFormat(): string
    {
        $entityManager = $this->getEntityManager();
        return $entityManager->getConnection()->getDatabasePlatform()->getTimeFormatString();
    }

    public function getDatabaseDateTimeFormat(): string
    {
        $entityManager = $this->getEntityManager();
        return $entityManager->getConnection()->getDatabasePlatform()->getDateTimeFormatString();
    }

    public function getDatabaseTimezoneName(): string
    {
        return $this::DATEBASE_TIMEZONE;
    }

    /*
        * Returns the date in database date format
        * @param bool $set_timezone - Whether to convert to database timezone. Default true. If false, current timezone is used.
    */
    public function toDatabaseDateFormat($set_timezone = true): string
    {
        if ($set_timezone) {
            $this->setTimezone(new \DateTimeZone($this->getDatabaseTimezoneName()));
        }
        return $this->format($this->getDatabaseDateFormat());
    }

    /*
        * Returns the time in database datetime format
        * @param bool $set_timezone - Whether to convert to database timezone. Default true. If false, current timezone is used.
    */
    public function toDatabaseFormat($set_timezone = true): string
    {
        if ($set_timezone) {
            $this->setTimezone(new \DateTimeZone($this->getDatabaseTimezoneName()));
        }
        return $this->format($this->getDatabaseDateTimeFormat());
    }

    // End Database methods


    // User methods

    public function getUserDateFormat(?Users $user = null): string
    {
        global $sugar_config;

        $date_f = $this->getUserPreferenceValue($this->getUserId($user), $this::PREFERENCE_DATE_FORMAT_KEY);
        return $date_f ?: $sugar_config['default_date_format'] ?: $this->getDatabaseDateFormat();
    }

    public function getUserTimeFormat(?Users $user = null): string
    {
        global $sugar_config;

        $time_f = $this->getUserPreferenceValue($this->getUserId($user), $this::PREFERENCE_TIME_FORMAT_KEY);
        return $time_f ?: $sugar_config['default_time_format'] ?: $this->getDatabaseTimeFormat();
    }

    public function getUserDateTimeFormat(?Users $user = null): string
    {
        return $this->getUserDateFormat($user) . ' ' . $this->getUserTimeFormat($user);
    }

    public function getUserTimezoneName(?Users $user = null): string
    {
        $user_timezone = $this->getUserPreferenceValue($this->getUserId($user), $this::PREFERENCE_TIMEZONE_KEY);
        if (empty($user_timezone)) {
            return $this->getDatabaseTimezoneName();
        }
        return $user_timezone;
    }

    /*
        * Returns the date in user preferred date format
        * @param Users|null $user - The user whose preference to use. If null, current user is used.
        * @param bool $set_timezone - Whether to convert to user's timezone. Default true. If false, current timezone is used.
    */
    public function toUserDateFormat(?Users $user = null, bool $set_timezone = true): string
    {
        if ($set_timezone) {
            $this->setTimezone(new \DateTimeZone($this->getUserTimezoneName($user)));
        }
        return $this->format($this->getUserDateFormat($user));
    }

    /*
        * Returns the date and time in user preferred datetime format
        * @param Users|null $user - The user whose preference to use. If null, current user is used.
        * @param bool $set_timezone - Whether to convert to user's timezone. Default true. If false, current timezone is used.
    */
    public function toUserFormat(?Users $user = null, bool $set_timezone = true): string
    {
        if ($set_timezone) {
            $this->setTimezone(new \DateTimeZone($this->getUserTimezoneName($user)));
        }
        return $this->format($this->getUserDateTimeFormat($user));
    }

    // End User methods


    private function getUserId(?Users $user = null): string
    {
        if (!empty($user)) {
            return $user->id;
        }

        global $current_user;
        if (!empty($current_user->id)) {
            return $current_user->id;
        }

        return '';
    }

    private function getUserPreferenceValue(string $user_id, string $name): ?string
    {
        if (empty($user_id)) {
            return null;
        }

        if (!empty(self::$users_preferences[$user_id])) {
            return !empty(self::$users_preferences[$user_id][$name]) ? self::$users_preferences[$user_id][$name] : null;
        }

        $entityManager = $this->getEntityManager();

        /** @var UserPreferencesRepository */
        $user_preference_repository = $entityManager->getRepository(UserPreferences::class);
        $preferences = $user_preference_repository->getUserPreferencesByCategory($user_id);
        if (!empty($preferences)) {
            self::$users_preferences[$user_id] = $preferences;
            return $preferences[$name] ?: null;
        }

        return null;
    }

    private function getEntityManager(): EntityManagerInterface
    {
        global $mint_app;
        return $mint_app->getContainer()->get(EntityManagerInterface::class);
    }
}
