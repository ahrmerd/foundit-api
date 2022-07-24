<?php

use App\Models\Category;
use App\Models\{Location, Item, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->endPoint = 'api/categories';
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
        ]
    ];
    $this->relationships = ['items' => ['class' => Item::class, 'type' => 'hasMany']];
});


it('only admin create a category', function () {
    asUser();
    creationTests($this->endPoint, $this->fields, 'categories', 403);
    asAdmin();
    creationTests($this->endPoint, $this->fields, 'categories');
});

it('validate the field to create a category', function () {
    asAdmin();
    validationTests($this->endPoint, $this->fields, Category::class);
});

it('can show a category', function () {
    showTests($this->endPoint, Category::class, $this->relationships);
});

it('show 404 when model cant be found', function () {
    $this->get("$this->endPoint/230")->assertStatus(404);
});

it('can return a list of categories', function () {
    indexTests($this->endPoint, Category::class, $this->fields, $this->relationships);
});



it('only admin can delete', function () {
    asUser();
    deleteTests($this->endPoint, Category::class, null, 403);
    asAdmin();
    deleteTests($this->endPoint, Category::class);
});


it('only admin can update a category', function () {
    asUser();
    updateTests($this->endPoint, 'categories', Category::class, $this->fields, null, 403);
    asAdmin();
    updateTests($this->endPoint, 'categories', Category::class, $this->fields);
});
