<?php
namespace common\classes;

use DateTimeImmutable;
use DateTime;
use DateTimeZone;

class TimeService
{
    /** @var DateTimeImmutable */
    private $dateTime;
    
    private function __construct()
    {
        $this->dateTime = new DateTimeImmutable('now', new DateTimeZone('Europe/Moscow'));
    }

    public static function create()
    {
        return new self();
    }

    public function getDateTime()
    {
        return $this->dateTime;
    }

    public function asAtom($modify = null)
    {
        $dt = $this->dateTime;
        if ($modify !== null) {
            $dt->modify($modify);
        }

        return $dt->format('Y-m-d H:i:s+03');
    }

    public function getBeginOfDay()
    {
        return $this->dateTime->format('Y-m-d 00:00:00+03');
    }

    public function getEndOfDay()
    {
        return $this->dateTime->modify('+1 day')->format('Y-m-d 00:00:00+03');
    }

    public function getBeginOfYesterday()
    {
        return $this->dateTime->modify('-1 day')->format('Y-m-d 00:00:00+03');
    }

    public function getBeginOfCurrentWeek()
    {
        return $this->dateTime->modify('this week')->format('Y-m-d 00:00:00+03');
    }

    public function getEndOfCurrentWeek()
    {
        return $this->dateTime->modify('next week')->format('Y-m-d 00:00:00+03');
    }

    public function getBeginOfLastWeek()
    {
        return $this->dateTime->modify('last week')->format('Y-m-d 00:00:00+03');
    }

    public function getBeginOfCurrentMonth()
    {
        return $this->dateTime->modify('this month')->format('Y-m-01 00:00:00+03');
    }

    public function getEndOfCurrentMonth()
    {
        return $this->dateTime->modify('next month')->format('Y-m-01 00:00:00+03');
    }

    public function getBeginOfLastMonth()
    {
        return $this->dateTime->modify('last month')->format('Y-m-01 00:00:00+03');
    }

    public static function getTimeAsAtom($dateTimeString)
    {
        try {
            $dateTime = new DateTime($dateTimeString, new DateTimeZone('Europe/Moscow'));
            $result = $dateTime->format('Y-m-d H:i:s+03');
        } catch (\Exception $e) {
            $result = null;
        }
        return $result;
    }

    public static function prettyDateTimeFormat($dateTimeString)
    {
        $dt = new DateTime($dateTimeString, new DateTimeZone('Europe/Moscow'));
        $result = $dt->format('d.m.Y H:i:s');

        return $result;
    }
}