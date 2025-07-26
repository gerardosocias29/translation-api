<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TranslationApiTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
    }

    public function test_can_create_translation()
    {
        $this->authenticate();

        $response = $this->postJson('/api/translations', [
            'key' => 'welcome',
            'value' => 'Welcome!',
            'locale' => 'en',
            'tag' => 'web',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('translations', ['key' => 'welcome']);
    }

    public function test_can_update_translation()
    {
        $this->authenticate();

        $translation = Translation::factory()->create([
            'key' => 'logout',
            'locale' => 'en',
            'tag' => 'web',
        ]);

        $response = $this->putJson("/api/translations/{$translation->id}", [
            'value' => 'Log Out',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('translations', ['id' => $translation->id, 'value' => 'Log Out']);
    }

    public function test_can_search_translations()
    {
        $this->authenticate();

        Translation::factory()->create([
            'key' => 'dashboard.title',
            'locale' => 'en',
            'tag' => 'web',
            'value' => 'Dashboard',
        ]);

        $response = $this->getJson('/api/translations?key=dashboard');

        $response->assertStatus(200)
                 ->assertJsonFragment(['key' => 'dashboard.title']);
    }

    public function test_can_delete_translation()
    {
        $this->authenticate();

        $translation = Translation::factory()->create();

        $response = $this->deleteJson("/api/translations/{$translation->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted($translation);
    }

    public function test_json_export_performance()
    {
        $this->authenticate();

        Translation::factory()->count(env('TRANSLATION_SEED_COUNT', 10000))->create([
            'locale' => 'en',
            'tag' => 'web',
        ]);

        $start = microtime(true);

        $response = $this->getJson('/api/translations/export/en');

        $duration = (microtime(true) - $start) * 1000; // ms

        $response->assertStatus(200);
        $this->assertTrue($duration < 500, "Export took too long: {$duration}ms");
    }

}