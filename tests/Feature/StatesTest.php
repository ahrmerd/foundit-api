<?php

use App\Models\{State, Location};
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * it can create a state
 * it validates a state creation request
 * it can list state
 * it can filter and sort states by name
 * it can update a state
 * it can delete a state
 */


uses(RefreshDatabase::class);


beforeEach(function () {
    $this->endPoint = 'api/states';
    $this->fields = ['name' => ['value' => 'borno', 'validations' => ['required', 'unique'], 'abilities' => ['filter', 'sort', 'include']]];
    $this->relationships = ['locations' => ['class' => Location::class, 'type' => 'hasMany']];
});




it('can show a state', function () {
    showTests($this->endPoint, State::class, $this->relationships);
});

it('show 404 when model cant be found', function () {
    $this->get("$this->endPoint/230")->assertStatus(404);
});

it('can return a list of states', function () {
    indexTests($this->endPoint, State::class, $this->fields, $this->relationships);
});
