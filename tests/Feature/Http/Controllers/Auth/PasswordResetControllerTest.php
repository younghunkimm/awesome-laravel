<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PasswordResetControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testReturnsForgotPasswordView(): void
    {
        $this->get(route('password.request'))
            ->assertOk()
            ->assertViewIs('auth.forgot-password');
    }

    public function testSendEmailForPasswordResets(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post(route('password.email'), [
            'email'=> $user->email,
        ])
        ->assertRedirect()
        ->assertSessionHas('status');

        Notification::assertSentTo(
            $user, ResetPassword::class
        );
    }

    public function testFailToSendEmailForPasswordResets(): void
    {
        Mail::fake();

        $this->post(route('password.email'), [
            'email'=> $this->faker->safeEmail(),
        ])
        ->assertRedirect()
        ->assertSessionHasErrors('email');

        Mail::assertNothingSent();
    }

    public function testReturnsResetPasswordView(): void
    {
        $token = Str::random(32);

        $this->get(route('password.reset', [
            'token'=> $token,
        ]))
        ->assertOk()
        ->assertViewIs('auth.reset-password');
    }

    public function testPasswordResetForValidToken(): void
    {
        Event::fake();

        $this->post(route('password.update'), [
            'email'=> $this->faker->safeEmail(),
            'password' => 'password',
            'password_confirmation' => 'password',
            'token' => Str::random(0),
        ])
        ->assertRedirect()
        ->assertSessionHasErrors('email');

        Event::assertNotDispatched(PasswordReset::class);
    }
}
