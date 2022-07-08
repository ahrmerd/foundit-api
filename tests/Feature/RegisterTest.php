<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{

    use RefreshDatabase;




    protected $payload = ['name' => 'ahmed', 'username' => 'ahrmerd', 'email' => 'ahrmerd@gmail.com', 'password' => 'masmam', 'phone' => '09012345678'];

    /** @test */
    public function a_user_can_register()
    {
        $this->withoutExceptionHandling();
        $res = $this->post('/api/register', $this->payload)->assertStatus(201);
        // dump($res->json());
        $res->assertJson(
            [
                "data" => array_filter($this->payload, fn ($key,) => $key !== 'password', ARRAY_FILTER_USE_KEY),
            ]
        );
        expect($res->json())->toHaveKey('token');
    }

    /** @test */
    public function a_user_is_authenticated_after_registration()
    {
        $this->post('/api/register', $this->payload)->assertStatus(201);
        $this->assertTrue(auth()->check());
    }


    /** @test */
    public function username_is_required()
    {
        $payload = $this->payload;
        $payload['username'] = '';
        $this->post('/api/register', $payload)->assertStatus(422)
            ->assertJson(['message' => 'The username field is required.', 'errors' => ['username' => ["The username field is required."]]]);
    }

    /** @test */
    public function username_and_email_is_unique()
    {
        $this->post('/api/register', $this->payload)->assertStatus(201);
        $res = $this->post('/api/register', $this->payload)->assertStatus(422);
        // dump($res->json());
        $res->assertJson([
            'message' => 'The username has already been taken. (and 1 more error)',
            'errors' => [
                'username' => ["The username has already been taken."],
                'email' => ["The email has already been taken."]
            ]
        ]);
    }

    /** @test */
    public function email_is_required()
    {
        $payload = $this->payload;
        $payload['email'] = '';
        $this->post('/api/register', $payload)->assertStatus(422)
            ->assertJson(['message' => 'The email field is required.', 'errors' => ['email' => ["The email field is required."]]]);
    }

    /** @test */
    public function email_is_valid()
    {

        $payload = $this->payload;
        $payload['email'] = 'abcdefghijklmnop';
        $this->post('/api/register', $payload)->assertStatus(422)
            ->assertJson(['message' => 'The email must be a valid email address.', 'errors' => ['email' => ['The email must be a valid email address.']]]);
    }

    /** @test */
    public function password_is_required()
    {
        $payload = $this->payload;
        $payload['password'] = '';
        $this->post('/api/register', $payload)->assertStatus(422)
            ->assertJson(['message' => 'The password field is required.', 'errors' => ['password' => ["The password field is required."]]]);
    }

    /** @test */
    public function password_is_more_than_four_characters()
    {
        $payload = $this->payload;
        $payload['password'] = 'aa';
        $this->post('/api/register', $payload)->assertStatus(422)
            ->assertJson(['message' => 'The password must be at least 4 characters.', 'errors' => ['password' => ["The password must be at least 4 characters."]]]);
    }

    /** @test */
    public function phone_number_is_saved()
    {
        $payload = $this->payload;
        $payload['phone'] = '09030685318';
        $this->post('/api/register', $payload)->assertStatus(201);
        $this->assertEquals('09030685318', auth()->user()->phone);
    }

    // /** @test */
    public function phone_number_is_valid()
    {
        $payload = $this->payload;
        $payload['phone'] = 'abcds';
        $this->post('/api/register', $payload)->assertStatus(422)
            ->assertJson(['message' => 'please enter a valid phone number.', 'errors' => ['phone' => ["please enter a valid phone number."]]]);
    }
}
