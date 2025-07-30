<?php

use App\Models\Publisher;
use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\DomCrawler\Crawler;

use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses(RefreshDatabase::class);

describe('Series', function () {
    describe('Routes', function() {
        describe('/series', function() {
            it('returns a successful and correct response', function() {
                get(route('series.list'))
                    ->assertOk()
                    ->assertViewIs('series.list');
            });
        });

        describe('/series/add', function() {
            it('returns a successful and correct response', function() {
                $response = get(route('series.add'));
                    
                $response->assertOk()
                         ->assertViewIs('series.add');

                $crawler = new Crawler($response->getContent());

                expect($crawler->filter('form[method="post"]')->count())->toBe(1);
                expect($crawler->filter('input[name="name"]')->attr('type'))->toBe('text');
            });
        });

        describe('/series/{id}', function() {
            it('returns a successful and correct response', function() {
                $series = Series::factory()->create();

                get(route('series.get', ['id' => $series->id]))
                    ->assertOk()
                    ->assertViewIs('series.show')
                    ->assertSeeText($series->name);
            });
        });
    });

    describe('RESTful', function() {
        it('can create a new series', function() {
            $data = [
                'name' => fake()->company(),
            ];

            post(route('series.post'), $data)
                ->assertRedirect(route('series.list'));

            $this->assertDatabaseHas('series', $data);
        });

        it('can retrieve series list', function() {
            $createdSeriesNames = Series::factory()
                ->count(3)
                ->create()
                ->pluck('name')
                ->toArray();

            get(route('series.list'))
                ->assertViewHas('series', function ($series) {
                    return $series->count() > 0;
                })
                ->assertSeeText($createdSeriesNames);
        });

        it('can retrieve a series', function() {
            $series = Series::factory()->create();

            get(route('series.get', ['id' => $series->id]))
                ->assertSeeText($series->name);
        });

        it('can update an existing series', function() {
            $series = Series::factory()->create();

            $original_data = [ 'name' => $series->name ];
            $data = [ 'name' => 'updated name' ];

            put(route('series.put', ['id' => $series->id]), $data)
                ->assertRedirect(route('series.list'));

            $this->assertDatabaseHas('series', $data);
            $this->assertDatabaseMissing('series', $original_data);
        });

        it('can delete a series', function() {
            $series = Series::factory()->create();

            $data = [ 'id' => $series->id ];

            $this->assertDatabaseHas('series', $data);

            delete(route('series.delete', ['id' => $series->id]))
                ->assertRedirect(route('series.list'));

            $this->assertSoftDeleted('series', $data);
        });
    });

    describe('Publisher', function() {
        it('can create a series with a publisher', function() {
            $publisher = Publisher::factory()->create();
            $series = Series::factory()->create(['name' => 'Spider-Man', 'publisher_id' => $publisher->id]);

            $this->assertDatabaseHas('series', ['id' => $series->id])
                 ->assertDatabaseHas('publishers', ['id' => $publisher->id])
                 ->assertDatabaseHas('series', ['id' => $series->id, 'publisher_id' => $publisher->id]);
        });

        it('can assign a publisher to a series', function() {
            $publisher = Publisher::factory()->create();
            
            $series = Series::factory()->create();
            $series->publisher = $publisher;
            $series->save();

            $this->assertDatabaseHas('series', ['id' => $series->id])
                 ->assertDatabaseHas('publishers', ['id' => $publisher->id])
                 ->assertDatabaseHas('series', ['id' => $series->id, 'publisher_id' => $publisher->id]);
        });
    });
});
