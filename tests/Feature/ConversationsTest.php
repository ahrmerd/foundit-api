<?php

use App\Models\Conversation;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('can return or create a conversation id if it does not exists', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user3 = User::factory()->create();
    $user4 = User::factory()->create();
    Sanctum::actingAs($user1);
    $this->post('api/conversations', ['user_id' => $user2->id])->assertStatus(200);
    $this->post('api/conversations', ['user_id' => $user3->id])->assertStatus(200);

    $this->post('api/conversations', ['user_id' => $user2->id])->assertStatus(200);
    $this->post('api/conversations', ['user_id' => $user3->id])->assertStatus(200);
    Sanctum::actingAs($user2);

    $res1 =  $this->post('api/conversations', ['user_id' => $user1->id])->assertStatus(200);
    $res2 = $this->post('api/conversations', ['user_id' => $user1->id])->assertStatus(200);
    expect(intval($res1->json()))->toBeInt();
    expect($res1->json())->toBe($res2->json());

    Sanctum::actingAs($user4);
    $this->post('api/conversations', ['user_id' => $user3->id]);

    expect(Conversation::query()->count())->toBe(3);

    expect(count($user1->conversations))->toBe(2);
    expect(count($user2->conversations))->toBe(1);
    expect(count($user3->conversations))->toBe(2);
    expect(count($user4->conversations))->toBe(1);
});

it('can return the messages in a conversation', function () {
});

function createTestData($number)
{
    $users1 = User::factory()->count($number)->create()->all();
    $users2 = User::factory()->count($number)->create()->all();
    for ($i = 0; $i < $number; $i++) {
        $user1 = $users1[$i];
        $user2 = $users2[$i];
        Conversation::factory()->create()->users()->attach([$user1->id, $user2->id]);
    }
    $convos = Conversation::with('users')->get()->toArray();
    expect(count($convos))->toBe($number);
    foreach ($convos as $key => $convo) {
        expect(count($convo['users']))->toBe(2);
    }
    return [...$users1, ...$users2];
}

it('can return a list of conversations with its users', function () {
    $number = 6;
    createTestData($number);

    asUser();

    $res = $this->get('api/conversations');
    $data = $res->json()['data'];
    expect(count($data))->toBe($number);
    foreach ($data as $key => $convo) {
        expect(count($convo['users']))->toBe(2);
    }
});

it('can return a users conversations with other users', function () {
    $users = createTestData(7);
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $count = 4;
    for ($i = 0; $i < $count; $i++) {
        $user2 = $users[$i];
        Conversation::factory()->create()->users()->attach([$user->id, $user2->id]);
    }
    $res = $this->get('api/conversations/user');
    $data = $res->json()['data'];
    expect(count($data))->toBe($count);
    foreach ($data as $key => $convo) {
        expect(count($convo['users']))->toBe(2);
    }
});
