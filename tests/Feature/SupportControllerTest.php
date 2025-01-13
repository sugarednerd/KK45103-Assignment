<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\SupportMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_support_messages()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        SupportMessage::factory()->create([
            'user_id' => $user->id,
            'message' => 'Test message'
        ]);

        $response = $this->get(route('user.support.index'));

        $response->assertStatus(200)
                 ->assertSee('Test message');
    }

    public function test_user_can_create_support_message()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = $this->post(route('user.support.store'), [
            'name' => 'Test User',
            'message' => 'This is a support message.'
        ]);

        $response->assertRedirect(route('user.support.index'));
        $this->assertDatabaseHas('support_messages', [
            'name' => 'Test User',
            'message' => 'This is a support message.',
            'user_id' => $user->id
        ]);
    }
}

