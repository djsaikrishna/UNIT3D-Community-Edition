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

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Internal.
 *
 * @property int    $id
 * @property string $name
 * @property string $icon
 * @property string $effect
 */
class Internal extends Model
{
    use Auditable;

    /** @use HasFactory<\Database\Factories\InternalFactory> */
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Indicates If The Model Should Be Timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Has Many Users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<User, $this, InternalUser>
     */
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->using(InternalUser::class)
            ->withPivot('id', 'position', 'created_at');
    }
}
