<?php

use App\Models\Publisher;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

describe('Publishers', function () {
    it('"/publishers" returns successful response')->get('/publishers')->assertOk();

    it('"/publishers" route returns correct view')->get('/publishers')->assertViewIs('publishers.list');

    it('return with correct route and has content', function () {
        $firstPublisher = Publisher::factory()->create();
        $secondPublisher = Publisher::factory()->create();

        get('/publishers')
            ->assertViewHas('publishers', function ($publishers) {
                return $publishers->count() > 0;
            })
            ->assertSeeText([
                $firstPublisher->name,
                $secondPublisher->name,
            ]);
    });
});
