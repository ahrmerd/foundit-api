<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/


use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class)->in('Feature');
uses(RefreshDatabase::class)->in('Feature');
uses()->beforeEach(
    fn () =>
    $this->withHeaders([
        'Accept' => 'application/json',
        'X-Requested-With' => 'XMLHttpRequest'
    ])
)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function creationTests($url, $info, $table)
{
    $it = test();
    $data = getData($info);
    $it->withoutExceptionHandling();
    $response = $it->post($url, $data);
    $response->assertStatus(201);
    $response->assertJsonStructure(array_keys($data));
    $it->assertDatabaseHas($table, $data);
}

function validationTests($url, array $info, $model)
{
    $data = getData($info);
    foreach ($info as $key => $val) {
        $validations = $val['validations'];
        foreach ($validations as $validation) {
            $model::query()->delete();
            if ($validation == 'required') {
                ensureRequiredValidation($url, $key, $data);
            }
            if ($validation == 'unique') {
                ensureUniqueValidation($url, $key, $data);
            }
        }
    }
}

function showTests($url, $model, $relationships)
{
    $it = test();
    $modelInstance = $model::factory()->create();
    $response = $it->get("$url/$modelInstance->id")->assertStatus(200);
    expect($response->json()['data'])->toMatchArray($modelInstance->toArray());
    foreach ($relationships as $relationshipName => $relationshipInfo) {
        ensureRelationship($url, $modelInstance, $relationshipName, $relationshipInfo['class'], $relationshipInfo['type']);
    }
}

function indexTests($url, $model, $info, $relationships)
{
    $it = test();
    $models = $model::factory()->count(2)->create();
    $response = $it->get("$url")->assertStatus(200);
    expect($response->json()['data'])->toMatchArray($models->toArray());
    ensureSortsByLatestAndLimit($url, $model);
    foreach ($info as $key => $value) {
        $abilities = $value['abilities'];
        foreach ($relationships as $relationshipName => $relationshipInfo) {
            ensureIncludeIndex($url, $model, $relationshipName, $relationshipInfo['class'], $relationshipInfo['type']);
        }
        $model::query()->delete();
        foreach ($abilities as $ability) {
            if ($ability == 'filter') {
                ensureFilterIndex($url, $model, $key);
            }
        }
    }
}

function updateTests($url, $table,  $model, $info)
{
    $it = test();
    $data = getData($info);
    $modelInstance = $model::factory()->create();
    $it->assertDatabaseMissing($table, $data);
    // expect($modelInstance)->toHaveProperties($data);
    $it->put("$url/$modelInstance->id", $data)->assertStatus(200);
    $it->assertDatabaseHas($table, $data);
}

function deleteTests($url, $model)
{
    $it = test();
    $modelInstance = $model::factory()->create();
    $it->assertModelExists($modelInstance);
    $it->delete("$url/$modelInstance->id")->assertStatus(200);
    $it->assertModelMissing($modelInstance);
}

function ensureRelationship($url, $modelInstance, $relationshipName, $relationshipClass, $relationshipType)
{
    $it = test();
    $count = 11;
    if ($relationshipType == 'belongsTo') {
    }
    if ($relationshipType == 'hasMany') {
        $relationshipClass::factory()->for($modelInstance)->count($count)->create();
    }

    $response = $it->get("$url/$modelInstance->id/?include=$relationshipName")->assertStatus(200);
    expect($response->json()['data'])->toHaveKey($relationshipName);
}

function ensureUniqueValidation($url, $field, $data)
{
    $it = test();
    $it->post($url, $data)->assertStatus(201);
    $response = $it->post($url, $data);
    $response->assertStatus(422);
    $response->assertExactJson(
        [
            "message" => "The $field has already been taken.", 'errors' => [
                "$field" => ["The $field has already been taken."],
            ],
        ]
    );
}

function ensureRequiredValidation($url, $field, $data)
{
    $data[$field] = '';
    $it = test();
    $sanitizedField = implode(' ', explode('_', $field));
    $response = $it->post($url, $data);
    $response->assertStatus(422);
    $response->assertExactJson(
        [
            "message" => "The $sanitizedField field is required.", 'errors' => [
                "$field" => ["The $sanitizedField field is required."],
            ],
        ]
    );
}

function ensureFilterIndex($url, $model, $field)
{
    $it = test();
    $modelInstance = $model::factory()->create();
    $model::factory()->count(30)->create();
    $fieldVal = $modelInstance->toArray()[$field];
    $response = $it->get("$url/?filter[$field]=$fieldVal")->assertStatus(200);
    foreach ($response->json()['data'] as $data) {
        gettype($fieldVal) == 'integer' ? expect(($data[$field]))->toBe($fieldVal) : expect(($data[$field]))->toContain($fieldVal);
    }
}



function ensureSortsByLatestAndLimit($url, $model)
{
    $it = test();
    $it->withoutExceptionHandling();
    $model::factory()->count(5)->create(['created_at' => Date('Y-m-d H:i:s', rand(1857138920, 1757138920))]);
    $response = $it->get("$url/?sort=-created_at&limit=5")->assertStatus(200);
    $response2 = $it->get("$url/?limit=5")->assertStatus(200);
    $unsortedFromRequest = $response2->json()['data'];
    $sortedFromRequest = $response->json()['data'];
    $sortedFromOrm = $model::query()->latest()->limit(5)->get();
    expect($sortedFromRequest)->toEqual($sortedFromOrm->toArray());
    expect($unsortedFromRequest)->not()->toEqual($sortedFromOrm->toArray());
}



function ensureIncludeIndex($url, $model, $relationshipName, $relationshipClass, $relationshipType)
{
    $it = test();
    $count = 5;
    if ($relationshipType == 'belongsTo') {
        $model::factory()->count(3)->create();
    }
    if ($relationshipType == 'hasMany') {
        $model::factory()->has($relationshipClass::factory()->count($count))->count(4)->create();
    }
    $response = $it->get("$url/?include=$relationshipName")->assertStatus(200);
    foreach ($response->json()['data'] as $key => $item) {
        expect($item)->toHaveKey($relationshipName);
        if ($relationshipType == 'hasMany') {
            expect(count($item[$relationshipName]))->toBe($count);
        }
    }
}



function getData(array $info)
{
    $data = [];
    foreach ($info as $key => $value) {
        $data[$key] = $value['value'];
    }
    return $data;
}
