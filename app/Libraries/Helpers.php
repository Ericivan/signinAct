<?php


if (!function_exists('mothdate')) {
    function monthdate($month)
    {
        $interval = collect();

        $start = Carbon\Carbon::now()->month($month)->startOfMonth();

        $end = Carbon\Carbon::now()->month($month)->endOfMonth();

        while ($start <= $end) {
            $interval->push([
                'date' => $start->toDateString(),
            ]);

            $start = $start->addDay();
        }

        return $interval;
    }
}