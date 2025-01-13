<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\TravelTip;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TravelTipControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_view_all_travel_tips()
    {
        TravelTip::factory()->create([
            'title' => 'Travel Tip Title',
            'content' => 'This is a travel tip.'
        ]);

        $response = $this->get(route('travel-tips.index'));

        $response->assertStatus(200)
                 ->assertSee('Travel Tip Title');
    }

    public function test_user_can_create_travel_tip()
    {
        // Arrange: Create a regular user and log them in
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        // Act: Post the travel tip data
        $response = $this->post(route('user.travel-tips.store'), [
            'title' => 'My Travel Tip',
            'content' => 'Useful travel tip content.',
            'categories' => 'Adventure'
        ]);

        // Assert: Check redirect and database
        $response->assertRedirect(route('travel-tips.index'));
        $this->assertDatabaseHas('travel_tips', [
            'title' => 'My Travel Tip',
            'content' => 'Useful travel tip content.',
            'categories' => 'Adventure',
            'user_id' => $user->id
        ]);
    }

    public function test_admin_can_delete_travel_tip()
    {
        // Arrange: Create an admin user and log them in
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);
    
        // Arrange: Create a travel tip
        $travelTip = TravelTip::factory()->create();
    
        // Act: Attempt to delete the travel tip
        $response = $this->delete(route('admin.travel-tips.destroy', $travelTip->id));
    
        // Assert: Check for a redirect and that the travel tip is missing from the database
        $response->assertRedirect(route('travel-tips.index')); // Adjust if needed
        $this->assertDatabaseMissing('travel_tips', ['id' => $travelTip->id]);
    }
}
