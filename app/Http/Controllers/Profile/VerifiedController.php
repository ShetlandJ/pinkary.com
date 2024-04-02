<?php

declare(strict_types=1);

namespace App\Http\Controllers\Profile;

use App\Jobs\SyncVerifiedUser;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

final readonly class VerifiedController
{
    /**
     * Handles the verified refresh.
     */
    public function update(): RedirectResponse
    {
        $user = request()->user();
        $user = type($user)->as(User::class);

        dispatch_sync(new SyncVerifiedUser($user));

        $user = $user->fresh();
        $user = type($user)->as(User::class);

        $user->is_verified
            ? session()->flash('flash-message', 'Your account has been verified.')
            : session()->flash('flash-message', 'Your account is not verified yet.');

        return redirect()->route('profile.edit');
    }
}
