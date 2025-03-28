<?php

use Illuminate\Support\Facades\Schedule;

// re-populate consolidation order
Schedule::command('consolidated-orders:refresh')
    ->weeklyOn(0, '0:00')->onOneServer()->evenInMaintenanceMode()->runInBackground();

