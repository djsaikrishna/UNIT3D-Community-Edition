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

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserSetting.
 *
 * @property int                             $id
 * @property int                             $user_id
 * @property bool                            $censor
 * @property bool                            $chat_hidden
 * @property string                          $locale
 * @property int                             $style
 * @property int                             $torrent_layout
 * @property bool                            $torrent_filters
 * @property ?string                         $custom_css
 * @property ?string                         $standalone_css
 * @property bool                            $show_poster
 * @property bool                            $unbookmark_torrents_on_completion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class UserSetting extends Model
{
    /** @use HasFactory<\Database\Factories\UserSettingFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array{
     *     censor: 'bool',
     *     chat_hidden: 'bool',
     *     torrent_filters: 'bool',
     *     show_poster: 'bool',
     *     unbookmark_torrents_on_completion: 'bool',
     * }
     */
    protected function casts(): array
    {
        return [
            'censor'                            => 'bool',
            'chat_hidden'                       => 'bool',
            'torrent_filters'                   => 'bool',
            'show_poster'                       => 'bool',
            'unbookmark_torrents_on_completion' => 'bool',
        ];
    }

    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, $this>
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
