<?php

describe('Root', function () {
    it('returns successful response')->get('/')->assertOk();
});
