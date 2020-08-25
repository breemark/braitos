<?php

namespace Tests\Feature\Http\Controllers;

use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TaskController
 */
class TaskControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected()
    {
        $tasks = factory(Task::class, 3)->create();

        $response = $this->get(route('task.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TaskController::class,
            'store',
            \App\Http\Requests\TaskStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves()
    {
        $title = $this->faker->sentence(4);

        $response = $this->post(route('task.store'), [
            'title' => $title,
        ]);

        $tasks = Task::query()
            ->where('title', $title)
            ->get();
        $this->assertCount(1, $tasks);
        $task = $tasks->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected()
    {
        $task = factory(Task::class)->create();

        $response = $this->get(route('task.show', $task));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TaskController::class,
            'update',
            \App\Http\Requests\TaskUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected()
    {
        $task = factory(Task::class)->create();
        $title = $this->faker->sentence(4);

        $response = $this->put(route('task.update', $task), [
            'title' => $title,
        ]);

        $task->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($title, $task->title);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with()
    {
        $task = factory(Task::class)->create();

        $response = $this->delete(route('task.destroy', $task));

        $response->assertOk();

        $this->assertDeleted($task);
    }
}
