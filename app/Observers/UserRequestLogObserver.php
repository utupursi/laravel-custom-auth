<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserRequestLog;

class UserRequestLogObserver
{
    /**
     * Handle the UserRequestLog "created" event.
     *
     * @param \App\Models\UserRequestLog $userRequestLog
     * @return void
     */
    public function created(UserRequestLog $userRequestLog)
    {
        $user = User::where(['id' => $userRequestLog->user_id])->first();
        if ($user) {
            $user->update(['requests_count' => $user->requests_count += 1]);
        }
    }

    /**
     * Handle the UserRequestLog "updated" event.
     *
     * @param \App\Models\UserRequestLog $userRequestLog
     * @return void
     */
    public function updated(UserRequestLog $userRequestLog)
    {
        //
    }

    /**
     * Handle the UserRequestLog "deleted" event.
     *
     * @param \App\Models\UserRequestLog $userRequestLog
     * @return void
     */
    public function deleted(UserRequestLog $userRequestLog)
    {
        //
    }

    /**
     * Handle the UserRequestLog "restored" event.
     *
     * @param \App\Models\UserRequestLog $userRequestLog
     * @return void
     */
    public function restored(UserRequestLog $userRequestLog)
    {
        //
    }

    /**
     * Handle the UserRequestLog "force deleted" event.
     *
     * @param \App\Models\UserRequestLog $userRequestLog
     * @return void
     */
    public function forceDeleted(UserRequestLog $userRequestLog)
    {
        //
    }
}
