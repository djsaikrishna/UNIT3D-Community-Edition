<?php

declare(strict_types=1);

/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\DonationExpired;
use App\Services\Unit3dAnnounce;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Throwable;

class AutoRemoveExpiredDonors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:remove_expired_donors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically remove expired donors.';

    /**
     * Execute the console command.
     *
     * @throws Exception|Throwable If there is an error during the execution of the command.
     */
    final public function handle(): void
    {
        $expiredDonors = User::where('is_donor', '=', true)
            ->where('is_lifetime', '=', false)
            ->whereHas('donations')
            ->whereDoesntHave('donations', function ($query): void {
                $query->where('ends_at', '>', Carbon::now());
            })->get();

        Notification::send($expiredDonors, new DonationExpired());

        foreach ($expiredDonors as $user) {
            $user->is_donor = false;
            $user->save();

            cache()->forget('user:'.$user->passkey);
            Unit3dAnnounce::addUser($user);
        }

        $this->info('Updated '.$expiredDonors->count().' users.');
    }
}
