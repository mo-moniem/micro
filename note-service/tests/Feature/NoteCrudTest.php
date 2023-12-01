<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Mocks\UserMicroserviceMock;

class NoteCrudTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $token;
    public function setUp(): void
    {
        parent::setUp();
        $userMicroservice = new UserMicroserviceMock();

        // Fetch the user details using the mock or fake class
        $this->user = $userMicroservice->getUser()['user'];
        $this->token = $userMicroservice->getUser()['token'];
    }

    /** @test */
    public function it_can_create_a_note()
    {
        $user = $this->user;

        $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'Accept' => 'application/json',
            ])
            ->post('/api/notes', [
                'title' => 'Test Note',
                'content' => 'This is a test note',
            ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('notes', [
            'title' => 'Test Note',
            'content' => 'This is a test note',
        ]);
    }

    /** @test */
    public function it_can_update_a_note()
    {
        $user = $this->user;
        $note = Note::factory()->create(['user_id' => $user['id']]);

        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'Accept' => 'application/json',

            ])
            ->put('/api/notes/' . $note->id, [
                'title' => 'Updated Note',
                'content' => 'This note has been updated',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'title' => 'Updated Note',
            'content' => 'This note has been updated',
        ]);
    }

    /** @test */
    public function it_can_delete_a_note()
    {
        $user = $this->user;
        $note = Note::factory()->create(['user_id' => $user['id']]);

        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'Accept' => 'application/json',
            ])
            ->delete('/api/notes/' . $note->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('notes', [
            'id' => $note->id,
        ]);
    }

}
