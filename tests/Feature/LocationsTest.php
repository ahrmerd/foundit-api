<?php

use App\Models\{State, Location};
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * it can create a location
 * it validates a location creation request
 * it can list location
 * it can filter and sort locations by name
 * it can update a location
 * it can delete a location
 */


uses(RefreshDatabase::class);


beforeEach(function () {
    $this->endPoint = 'api/locations';
    $state_id = State::factory()->create()->id;
    $this->fields = [
        'name' =>
        ['value' => 'borno', 'validations' => ['required', 'unique'], 'abilities' => ['filter', 'sort', 'include']],
        'state_id' =>
        ['value' => $state_id, 'validations' => ['required'], 'abilities' => ['filter', 'sort', 'include']]
    ];
    $this->relationships = ['state' => ['class' => State::class, 'type' => 'belongsTo']];
});

it('can create a location', function () {
    creationTests($this->endPoint, $this->fields, 'locations');
});

it('validate the fields to create a location', function () {
    validationTests($this->endPoint, $this->fields, Location::class);
});

it('can show a location', function () {
    showTests($this->endPoint, Location::class, $this->relationships);
});

it('show 404 when model cant be found', function () {
    $this->get("$this->endPoint/230")->assertStatus(404);
});

it('can return a list of locations', function () {
    indexTests($this->endPoint, Location::class, $this->fields, $this->relationships);
});

it('can delete a location', function () {
    deleteTests($this->endPoint, Location::class);
});

it('can update a location', function () {
    updateTests($this->endPoint, 'locations', Location::class, $this->fields);
});
