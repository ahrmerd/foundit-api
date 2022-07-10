<?php


use App\Models\{Location, Item, Category, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->endPoint = 'api/items';
    $this->fields = [
        'name' =>
        [
            'value' => 'Electronics',
            'validations' => ['required', 'unique'],
            'abilities' => ['filter', 'sort']
        ],
        'description' => [
            'value' => 'some description',
            'validations' => [],
            'abilities' => ['sort'],
        ],
        'category_id' => [
            'value' => Category::factory()->create()->id,
            'validations' => ['required'],
            'abilities' => ['filter'],
        ],
    ];
    $this->relationships = [
        'category' => ['class' => Category::class, 'type' => 'belongsTo'],
        'user' => ['class' => User::class, 'type' => 'belongsTo']
    ];
});

it('can create a item', function () {
    Sanctum::actingAs(User::factory()->create());
    creationTests($this->endPoint, $this->fields, 'items');
});

it('validate the field to create a item', function () {
    Sanctum::actingAs(User::factory()->create());
    validationTests($this->endPoint, $this->fields, Item::class);
});

it('can show a item', function () {
    showTests($this->endPoint, Item::class, $this->relationships);
});

it('show 404 when model cant be found', function () {
    $this->get("$this->endPoint/230")->assertStatus(404);
});

it('can return a list of items', function () {
    indexTests($this->endPoint, Item::class, $this->fields, $this->relationships);
});

it('can delete a item', function () {
    deleteTests($this->endPoint, Item::class);
});

it('can update a item', function () {
    updateTests($this->endPoint, 'items', Item::class, $this->fields);
});
