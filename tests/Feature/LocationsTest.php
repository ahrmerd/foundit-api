<?php

it('has locations page', function () {
    $response = $this->get('/locations');

    $response->assertStatus(200);
});
