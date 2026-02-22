<?php

namespace Tests\Feature\Settings;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        if (! Features::canUpdateProfileInformation()) {
            $this->markTestSkipped('Profile update feature is disabled.');
        }

        $this->actingAs($user = User::factory()->create());

        $this->get(route('profile.edit'))->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        if (! Features::canUpdateProfileInformation()) {
            $this->markTestSkipped('Profile update feature is disabled.');
        }

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = Livewire::test('pages::settings.profile')
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->call('updateProfileInformation');

        $response->assertHasNoErrors();

        $user->refresh();

        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_email_address_is_unchanged(): void
    {
        if (! Features::canUpdateProfileInformation()) {
            $this->markTestSkipped('Profile update feature is disabled.');
        }

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = Livewire::test('pages::settings.profile')
            ->set('name', 'Test User')
            ->set('email', $user->email)
            ->call('updateProfileInformation');

        $response->assertHasNoErrors();

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        // Deleting account is usually part of profile but let's check if we have a feature for it.
        // In Fortify it's often linked to account deletion features if exists. 
        // For now let's assume if profile is off, settings are off.
        if (! Features::canUpdateProfileInformation()) {
            $this->markTestSkipped('Profile update feature is disabled.');
        }

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = Livewire::test('pages::settings.delete-user-form')
            ->set('password', 'password')
            ->call('deleteUser');

        $response
            ->assertHasNoErrors()
            ->assertRedirect('/');

        $this->assertNull($user->fresh());
        $this->assertFalse(\Illuminate\Support\Facades\Auth::check());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        if (! Features::canUpdateProfileInformation()) {
            $this->markTestSkipped('Profile update feature is disabled.');
        }

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = Livewire::test('pages::settings.delete-user-form')
            ->set('password', 'wrong-password')
            ->call('deleteUser');

        $response->assertHasErrors(['password']);

        $this->assertNotNull($user->fresh());
    }
}
