<?php


use App\Models\{Location, Item, Category, Report, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $user = User::factory()->create();
    $this->user = $user;
    $this->endPoint = 'api/reports';
    $this->fields = [
        'title' =>
        [
            'value' => 'Abusive content',
            'validations' => ['required'],
            'abilities' => ['filter', 'sort']
        ],
        'description' => [
            'value' => 'some description',
            'validations' => ['required'],
            'abilities' => [],
        ],
        'user_id' => [
            'value' => $user->id,
            'validations' => [],
            'abilities' => ['filter', 'sort'],
        ],
        'status' => [
            'value' => Report::STATUS['new'],
            'validations' => ['required'],
            'abilities' => ['filter', 'sort'],
        ],
        'type' => [
            'value' => Report::TYPE['other'],
            'validations' => ['required'],
            'abilities' => ['filter', 'sort'],
        ],
    ];
    $this->relationships = [
        'user' => ['class' => User::class, 'type' => 'belongsTo']
    ];
});

it('can create a report', function () {
    $user = Sanctum::actingAs($this->user);
    creationTests($this->endPoint, $this->fields, 'reports');
});

it('validate the field to create a report', function () {
    Sanctum::actingAs($this->user);
    validationTests($this->endPoint, $this->fields, Report::class);
});

it('allows only admin to see a report', function () {
    asUser();
    showTests($this->endPoint, Report::class, $this->relationships, 403);
    asAdmin();
    showTests($this->endPoint, Report::class, $this->relationships);
});

it('show 404 when model cant be found', function () {
    asAdmin();
    $this->get("$this->endPoint/230")->assertStatus(404);
});

it('allows only admin to return a list of reports', function () {
    asUser();
    Report::factory()->count(2)->create();
    $this->get($this->endPoint)->assertStatus(403);
    Report::query()->delete();
    asAdmin();
    indexTests($this->endPoint, Report::class, $this->fields, $this->relationships);
});

it('allows only admin to delete a report', function () {
    $user = asUser();
    deleteTests($this->endPoint, Report::class, $user, 403);
    asAdmin();
    deleteTests($this->endPoint, Report::class);
});

it('allows only admin to update a report', function () {
    asUser();
    updateTests($this->endPoint, 'reports', Report::class, $this->fields, null, 403);
    asAdmin();
    updateTests($this->endPoint, 'reports', Report::class, $this->fields);
});
