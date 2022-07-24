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

it('only admin can create a location', function () {
    asUser();
    creationTests($this->endPoint, $this->fields, 'locations', 403);
    asAdmin();
    creationTests($this->endPoint, $this->fields, 'locations');
});

it('validate the fields to create a location', function () {
    asAdmin();
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

it('only admin can delete a location', function () {
    asUser();
    deleteTests($this->endPoint, Location::class, null, 403);
    asAdmin();
    deleteTests($this->endPoint, Location::class);
});

it('only admin can update a location', function () {
    asUser();
    updateTests($this->endPoint, 'locations', Location::class, $this->fields, null, 403);
    asAdmin();
    updateTests($this->endPoint, 'locations', Location::class, $this->fields);
});
