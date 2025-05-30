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

namespace App\Http\Livewire;

use App\Models\Torrent;
use App\Models\User;
use App\Traits\TorrentMeta;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TopTorrents extends Component
{
    use TorrentMeta;

    public ?User $user = null;

    public string $tab = 'newest';

    final public function mount(): void
    {
        $this->user = auth()->user();
    }

    /**
     * @return \Illuminate\Support\Collection<int, Torrent>
     */
    #[Computed]
    final public function torrents(): \Illuminate\Support\Collection
    {
        $torrents = Torrent::query()
            ->with(['user.group', 'category', 'type', 'resolution'])
            ->withExists([
                'featured as featured',
                'bookmarks'          => fn ($query) => $query->where('user_id', '=', $this->user->id),
                'freeleechTokens'    => fn ($query) => $query->where('user_id', '=', $this->user->id),
                'history as seeding' => fn ($query) => $query->where('user_id', '=', $this->user->id)
                    ->where('active', '=', 1)
                    ->where('seeder', '=', 1),
                'history as leeching' => fn ($query) => $query->where('user_id', '=', $this->user->id)
                    ->where('active', '=', 1)
                    ->where('seeder', '=', 0),
                'history as not_completed' => fn ($query) => $query->where('user_id', '=', $this->user->id)
                    ->where('active', '=', 0)
                    ->where('seeder', '=', 0)
                    ->whereNull('completed_at'),
                'history as not_seeding' => fn ($query) => $query->where('user_id', '=', $this->user->id)
                    ->where('active', '=', 0)
                    ->where(
                        fn ($query) => $query
                            ->where('seeder', '=', 1)
                            ->orWhereNotNull('completed_at')
                    ),
            ])
            ->selectRaw(<<<SQL
                CASE
                    WHEN category_id IN (SELECT id FROM categories WHERE movie_meta = 1) THEN 'movie'
                    WHEN category_id IN (SELECT id FROM categories WHERE tv_meta = 1) THEN 'tv'
                    WHEN category_id IN (SELECT id FROM categories WHERE game_meta = 1) THEN 'game'
                    WHEN category_id IN (SELECT id FROM categories WHERE music_meta = 1) THEN 'music'
                    WHEN category_id IN (SELECT id FROM categories WHERE no_meta = 1) THEN 'no'
                END AS meta
            SQL)
            ->withCount(['thanks', 'comments'])
            ->when($this->tab === 'newest', fn ($query) => $query->orderByDesc('id'))
            ->when($this->tab === 'seeded', fn ($query) => $query->orderByDesc('seeders'))
            ->when(
                $this->tab === 'dying',
                fn ($query) => $query
                    ->where('seeders', '=', 1)
                    ->where('times_completed', '>=', 1)
                    ->orderByDesc('leechers')
            )
            ->when($this->tab === 'leeched', fn ($query) => $query->orderByDesc('leechers'))
            ->when(
                $this->tab === 'dead',
                fn ($query) => $query
                    ->where('seeders', '=', 0)
                    ->orderByDesc('leechers')
            )
            ->take(5)
            ->get();

        // See app/Traits/TorrentMeta.php
        $this->scopeMeta($torrents);

        return $torrents;
    }

    #[Computed]
    final public function personalFreeleech(): bool
    {
        return cache()->get('personal_freeleech:'.$this->user->id) ?? false;
    }

    final public function render(): \Illuminate\Contracts\View\Factory | \Illuminate\Contracts\View\View | \Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.top-torrents', [
            'personal_freeleech' => $this->personalFreeleech,
            'torrents'           => $this->torrents,
        ]);
    }
}
