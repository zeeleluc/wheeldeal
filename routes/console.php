<?php

use Illuminate\Console\Scheduling\Schedule;

$schedule = app(Schedule::class);
$schedule->command('reservations:clean-drafts')->everyMinute();
