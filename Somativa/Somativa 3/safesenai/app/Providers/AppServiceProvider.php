<?php

namespace App\Providers;

use App\Events\StudentReleased;
use App\Listeners\SendReleaseNotification;
use App\Models\EarlyRelease;
use App\Models\LateEntry;
use App\Models\Student;
use App\Policies\EarlyReleasePolicy;
use App\Policies\LateEntryPolicy;
use App\Policies\StudentPolicy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Event::listen(StudentReleased::class, SendReleaseNotification::class);

        Gate::policy(Student::class, StudentPolicy::class);
        Gate::policy(EarlyRelease::class, EarlyReleasePolicy::class);
        Gate::policy(LateEntry::class, LateEntryPolicy::class);
    }
}
