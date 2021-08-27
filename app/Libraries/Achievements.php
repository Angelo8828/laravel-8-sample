<?php

/**
 * Library for achievements data
 *
 */
namespace App\Libraries;

class Achievements
{
    public static function lessonsWatched()
    {
        return [
            1  => "First Lesson Watched",
            5  => "5 Lessons Watched",
            10 => "10 Lessons Watched",
            25 => "25 Lessons Watched",
            50 => "50 Lessons Watched",
        ];
    }


    public static function commentsWritten()
    {
        return [
            1  => "First Comment Written",
            3  => "3 Comments Written",
            5  => "5 Comments Written",
            10 => "10 Comments Written",
            20 => "20 Comments Written",
        ];
    }

    public static function badges()
    {
        return [
            0  => "Beginner",
            4  => "Intermediate",
            8  => "Advanced",
            10 => "Master",
        ];
    }
}
