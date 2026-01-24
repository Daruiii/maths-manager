<?php

namespace App\Services;

class TimerFormattingService
{
    /**
     * Formate un temps en secondes au format HH:MM:SS
     *
     * @param int $timerInSeconds Temps en secondes
     * @return string Format HH:MM:SS
     */
    public function format(int $timerInSeconds): string
    {
        $hours = floor($timerInSeconds / 3600);
        $minutes = floor(($timerInSeconds - $hours * 3600) / 60);
        $seconds = $timerInSeconds - $hours * 3600 - $minutes * 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    /**
     * Parse un timer au format HH:MM:SS en secondes
     *
     * @param string $timer Format HH:MM:SS
     * @return int Temps en secondes
     */
    public function parseToSeconds(string $timer): int
    {
        $parts = explode(':', $timer);

        return ($parts[0] * 3600) + ($parts[1] * 60) + $parts[2];
    }
}
