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
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Console\Commands;

use App\Models\TmdbMovie;
use App\Models\TmdbTv;
use Illuminate\Console\Command;
use Exception;
use Illuminate\Support\Facades\Redis;
use Throwable;

class AutoCacheRandomMediaIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:cache_random_media';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Caches valid media ids for random media component';

    /**
     * Execute the console command.
     *
     * @throws Exception|Throwable If there is an error during the execution of the command.
     */
    final public function handle(): void
    {
        $movieIds = TmdbMovie::query()
            ->select('id')
            ->whereHas('torrents')
            ->whereNotNull('backdrop')
            ->pluck('id');

        $tvIds = TmdbTv::query()
            ->select('id')
            ->whereHas('torrents')
            ->whereNotNull('backdrop')
            ->pluck('id');

        if ($movieIds->isNotEmpty()) {
            $cacheKey = config('cache.prefix').':random-media-movie-ids';

            Redis::connection('cache')->command('SADD', [$cacheKey, ...$movieIds]);
        }

        if ($tvIds->isNotEmpty()) {
            $cacheKey = config('cache.prefix').':random-media-tv-ids';

            Redis::connection('cache')->command('SADD', [$cacheKey, ...$tvIds]);
        }

        $this->comment($movieIds->count().' movie ids and '.$tvIds->count().' tv ids cached.');
    }
}
