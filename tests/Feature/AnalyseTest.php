<?php

it('has analyse page', function () {
    $response = $this->get('/analyse');

    $response->assertStatus(200);
});
