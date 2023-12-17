<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use Tests\TestCase;
use App\Models\User;
use App\Http\Middleware\RequirePassword;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlogControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testReturnsBlogsDashboardViewForListOfBlog(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withoutMiddleware(RequirePassword::class)
            ->get(route('dashboard.blogs'))
            ->assertOk()
            ->assertViewIs('dashboard.blogs');
    }
}
