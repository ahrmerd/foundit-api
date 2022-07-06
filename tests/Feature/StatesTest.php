<?php

it('has states page', function () {
    $response = $this->get('/states');

    $response->assertStatus(200);
});
