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

namespace App\Http\Requests;

use App\Enums\ModerationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdatePlaylistSuggestionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, \Illuminate\Validation\Rules\Enum|\Illuminate\Validation\Rules\RequiredIf|string>>
     */
    public function rules(Request $request): array
    {
        return [
            'status' => [
                'required',
                Rule::enum(ModerationStatus::class)->only([ModerationStatus::APPROVED, ModerationStatus::REJECTED]),
            ],
            'rejection_message' => [
                Rule::requiredIf($request->integer('status') === ModerationStatus::REJECTED->value),
                'max:65535',
            ],
        ];
    }
}
