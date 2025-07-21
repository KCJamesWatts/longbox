<?php

use App\Models\Publisher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\DomCrawler\Crawler;

use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses(RefreshDatabase::class);

describe('Publishers', function () {
    describe('Routes', function() {
        describe('/publishers', function() {
            it('returns a successful and correct response', function() {
                get(route('publisher.list'))
                    ->assertOk()
                    ->assertViewIs('publisher.list');
            });
        });

        describe('/publishers/add', function() {
            it('returns a successful and correct response', function() {
                $response = get(route('publisher.add'));
                    
                $response->assertOk()
                         ->assertViewIs('publisher.add');

                $crawler = new Crawler($response->getContent());

                expect($crawler->filter('form[method="post"]')->count())->toBe(1);
                expect($crawler->filter('input[name="name"]')->attr('type'))->toBe('text');
            });
        });

        describe('/publishers/{id}', function() {
            it('returns a successful and correct response', function() {
                $publisher = Publisher::factory()->create();

                get(route('publisher.get', ['id' => $publisher->id]))
                    ->assertOk()
                    ->assertViewIs('publisher.show')
                    ->assertSeeText($publisher->name);
            });
        });
    });

    describe('RESTful', function() {
        it('can create a new publisher', function() {
            $data = [
                'name' => fake()->company(),
            ];

            post(route('publisher.post'), $data)
                ->assertRedirect(route('publisher.list'));

            $this->assertDatabaseHas('publishers', $data);
        });

        it('can retrieve publisher list', function() {
            $createdPublisherNames = Publisher::factory()
                ->count(3)
                ->create()
                ->pluck('name')
                ->toArray();

            get(route('publisher.list'))
                ->assertViewHas('publishers', function ($publishers) {
                    return $publishers->count() > 0;
                })
                ->assertSeeText($createdPublisherNames);
        });

        it('can retrieve a publisher', function() {
            $publisher = Publisher::factory()->create();

            get(route('publisher.get', ['id' => $publisher->id]))
                ->assertSeeText($publisher->name);
        });

        it('can update an existing publisher', function() {
            $publisher = Publisher::factory()->create();

            $original_data = [ 'name' => $publisher->name ];
            $data = [ 'name' => 'updated name' ];

            put(route('publisher.put', ['id' => $publisher->id]), $data)
                ->assertRedirect(route('publisher.list'));

            $this->assertDatabaseHas('publishers', $data);
            $this->assertDatabaseMissing('publishers', $original_data);
        });

        it('can delete a publisher', function() {
            $publisher = Publisher::factory()->create();

            $data = [ 'id' => $publisher->id ];

            $this->assertDatabaseHas('publishers', $data);

            delete(route('publisher.delete', ['id' => $publisher->id]))
                ->assertRedirect(route('publisher.list'));

            $this->assertSoftDeleted('publishers', $data);
        });
    });

    describe('Imprints', function() {
        it('can add a new imprint (from modal)', function() {
            $publisher = Publisher::factory()->create();
            $imprint = Publisher::factory()->create();

            $publisher->addImprint($imprint);

            $this->assertDatabaseHas('publishers', ['id'=>$publisher->id])
                 ->assertDatabaseHas('publishers', ['id'=>$imprint->id, 'parent_id'=>$publisher->id]);
        });

        it('can add a new imprint (from array)', function() {
            $publisher = Publisher::factory()->create();
            $imprintData = [ 'name' => 'Imprint from Data' ];

            $imprint = $publisher->addImprint($imprintData);

            $this->assertDatabaseHas('publishers', ['id'=>$publisher->id])
                 ->assertDatabaseHas('publishers', ['id'=>$imprint->id, 'parent_id'=>$publisher->id]);
        });

        it('guards against circular references', function() {
            $publisher = Publisher::factory()->create();

            expect(fn () => $publisher->addImprint($publisher))
                ->toThrow(\InvalidArgumentException::class);
        });
    });
});
