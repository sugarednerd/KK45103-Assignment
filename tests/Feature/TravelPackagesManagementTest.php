<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

class TravelPackagesManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_all_packages()
    {
        $this->actingAs($this->admin);

        Package::factory()->count(3)->create(['user_id' => $this->admin->id]);

        $response = $this->get(route('admin.dashboard.view-packages'));

        $response->assertStatus(200)
                 ->assertViewIs('admin.dashboard.view-packages-ajax')
                 ->assertSee('Packages');
    }

    public function test_admin_can_create_package()
    {
        $this->actingAs($this->admin);
    
        $response = $this->post(route('admin.dashboard.store-package'), [
            'title' => 'Test Package',
            'description' => 'A test travel package.',
            'price' => 500,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(10)->toDateString(),
            'location' => 'Test Location',
            'cover_image' => UploadedFile::fake()->image('cover.jpg')->size(500), // Fake image upload
            'featured' => false,
        ]);
    
        $response->assertRedirect(route('admin.dashboard.index'));
        $this->assertDatabaseHas('packages', [
            'title' => 'Test Package',
            'description' => 'A test travel package.',
            'location' => 'Test Location',
            'user_id' => $this->admin->id,
        ]);
    }
    

    

    public function test_admin_can_edit_package()
    {
        $this->actingAs($this->admin);

        $package = Package::factory()->create(['title' => 'Old Title', 'user_id' => $this->admin->id]);

        $response = $this->put(route('admin.dashboard.update-package', $package->id), [
            'title' => 'Updated Title',
            'description' => 'Updated description',
            'price' => 700,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'location' => 'Updated Location',
            'featured' => true,
        ]);

        $response->assertRedirect(route('admin.dashboard.index'));
        $this->assertDatabaseHas('packages', ['title' => 'Updated Title']);
    }

    public function test_admin_can_delete_package()
    {
        $this->actingAs($this->admin);

        $package = Package::factory()->create(['user_id' => $this->admin->id]);

        $response = $this->delete(route('admin.dashboard.delete-package', $package->id));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('packages', ['id' => $package->id]);
    }

    public function test_admin_can_feature_package()
    {
        $this->actingAs($this->admin);
    
        $package = Package::factory()->create([
            'featured' => false,
            'user_id' => $this->admin->id,
            'start_date' => now()->toDateString(), // Ensure scalar value
            'end_date' => now()->addDays(5)->toDateString(),
        ]);
    
        $response = $this->put(route('admin.dashboard.update-package', $package->id), [
            'title' => $package->title,
            'description' => $package->description,
            'price' => $package->price,
            'start_date' => $package->start_date, // Scalar value
            'end_date' => $package->end_date,     // Scalar value
            'location' => $package->location,
            'featured' => true,
        ]);
    
        $response->assertRedirect(route('admin.dashboard.index')); // Ensure it redirects correctly
        $this->assertDatabaseHas('packages', [
            'id' => $package->id,
            'featured' => true,
        ]);
    }
    

}
