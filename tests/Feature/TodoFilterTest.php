<?php

namespace Tests\Feature;

use App\Models\{Todos, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TodoFilterTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function filtering_ui_is_present()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('todo.liste'));

        $response->assertStatus(200);
        $response->assertSee('Toutes');
        $response->assertSee('En cours');
        $response->assertSee('Terminées');

        // Contient maintenant un appel x-data avec fetchTodos
        $response->assertSee('fetchTodos(newFilter)');
    }

    #[Test]
    public function controller_filters_results_correctly()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->actingAs($user);

        $activeTask = Todos::factory()->create([
            'user_id' => $user->id,
            'texte' => 'Task-Active',
            'termine' => 0,
        ]);

        $completedTask = Todos::factory()->create([
            'user_id' => $user->id,
            'texte' => 'Task-Completed',
            'termine' => 1,
        ]);

        // Test filter 'all'
        $response = $this->get(route('todo.liste', ['filter' => 'all']));
        $response->assertSee('Task-Active');
        $response->assertSee('Task-Completed');

        // Test filter 'active'
        $response = $this->get(route('todo.liste', ['filter' => 'active']));
        $response->assertSee('Task-Active');
        $response->assertSee(route('todo.done', ['id' => $activeTask->id]));
        $response->assertDontSee('Task-Completed');

        // Test filter 'completed'
        $response = $this->get(route('todo.liste', ['filter' => 'completed']));
        $response->assertDontSee('Task-Active');
        $response->assertSee('Task-Completed');
        $response->assertSee(route('todo.delete', ['id' => $completedTask->id]));
    }
}
