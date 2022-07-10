<?php

use App\Models\Category;
use App\Models\{Location, Item};
use Illuminate\Foundation\Testing\RefreshDatabase;

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

it('can create a category', function () {
    creationTests($this->endPoint, $this->fields, 'categories');
});

it('validate the field to create a category', function () {
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

it('can delete a category', function () {
    deleteTests($this->endPoint, Category::class);
});

it('can update a category', function () {
    updateTests($this->endPoint, 'categories', Category::class, $this->fields);
});
