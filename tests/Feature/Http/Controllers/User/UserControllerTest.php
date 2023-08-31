<?php
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

use App\Models\Invite;
use App\Models\Peer;
use App\Models\User;

test('accept rules returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $user = User::factory()->create();
    $authUser = User::factory()->create();

    $response = $this->actingAs($authUser)->post(route('users.accept.rules', [$user]), [
        // TODO: send request data
    ]);

    $response->assertOk();

    // TODO: perform additional assertions
});

test('accept rules aborts with a 403', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $user = User::factory()->create();
    $authUser = User::factory()->create();

    // TODO: perform additional setup to trigger `abort_unless(403)`...

    $response = $this->actingAs($authUser)->post(route('users.accept.rules', [$user]), [
        // TODO: send request data
    ]);

    $response->assertForbidden();
});

test('edit returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $user = User::factory()->create();
    $authUser = User::factory()->create();

    $response = $this->actingAs($authUser)->get(route('users.edit', [$user]));

    $response->assertOk();
    $response->assertViewIs('user.profile.edit');
    $response->assertViewHas('user', $user);

    // TODO: perform additional assertions
});

test('edit aborts with a 403', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $user = User::factory()->create();
    $authUser = User::factory()->create();

    // TODO: perform additional setup to trigger `abort_unless(403)`...

    $response = $this->actingAs($authUser)->get(route('users.edit', [$user]));

    $response->assertForbidden();
});

test('show returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $user = User::factory()->create();
    $invite = Invite::factory()->create();
    $peer = Peer::factory()->create();
    $authUser = User::factory()->create();

    $response = $this->actingAs($authUser)->get(route('users.show', [$user]));

    $response->assertOk();
    $response->assertViewIs('user.profile.show');
    $response->assertViewHas('user', $user);
    $response->assertViewHas('followers');
    $response->assertViewHas('history');
    $response->assertViewHas('manualWarnings');
    $response->assertViewHas('autoWarnings');
    $response->assertViewHas('softDeletedWarnings');
    $response->assertViewHas('boughtUpload');
    $response->assertViewHas('invitedBy');
    $response->assertViewHas('clients');
    $response->assertViewHas('achievements');
    $response->assertViewHas('peers');
    $response->assertViewHas('watch');

    // TODO: perform additional assertions
});

test('update returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $user = User::factory()->create();
    $authUser = User::factory()->create();

    $response = $this->actingAs($authUser)->patch(route('users.update', [$user]), [
        // TODO: send request data
    ]);

    $response->assertOk();

    // TODO: perform additional assertions
});

test('update aborts with a 403', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $user = User::factory()->create();
    $authUser = User::factory()->create();

    // TODO: perform additional setup to trigger `abort_unless(403)`...

    $response = $this->actingAs($authUser)->patch(route('users.update', [$user]), [
        // TODO: send request data
    ]);

    $response->assertForbidden();
});

// test cases...