<?php

declare(strict_types=1);

use App\Jobs\SendTaskDueReminders;

Schedule::job(new SendTaskDueReminders())->dailyAt('9:00');
