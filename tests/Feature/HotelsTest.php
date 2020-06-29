<?php

    namespace Tests\Feature;

    use App\Model\Hotel;
    use Illuminate\Foundation\Testing\DatabaseMigrations;
    use Tests\TestCase;

    class HotelsTest extends TestCase
    {
        use DatabaseMigrations;

        public const TEST_NAME = 'Test SPA Resort';
        public const TEST_RATING = 5;
        public const TEST_RATING_WRONG = 6;
        public const TEST_TYPE = 'hotels';
        public const TEST_TYPE_WRONG = 'another';

        public function testItReturnsAnHotelAsAResourceObject(): void
        {
            /** @var Hotel $hotel */
            $hotel = factory(Hotel::class)->create([
                'name'   => self::TEST_NAME,
                'rating' => self::TEST_RATING,
            ]);

            $this->getJson('/api/v1/hotels/1')
                ->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'id'         => '1',
                        'type'       => self::TEST_TYPE,
                        'attributes' => [
                            'name'       => $hotel->name,
                            'rating'     => $hotel->rating,
                            'created_at' => $hotel->created_at->toJSON(),
                            'updated_at' => $hotel->updated_at->toJSON(),
                        ]
                    ]
                ]);
        }

        public function testItReturnsAllHotelsAsACollectionOfResourceObjects(): void
        {
            $hotels = factory(Hotel::class, 2)->create();

            $this->getJson('/api/v1/hotels')
                ->assertStatus(200)
                ->assertJson([
                    'data' => [
                        [
                            'id'         => '1',
                            'type'       => self::TEST_TYPE,
                            'attributes' => [
                                'name'       => $hotels[0]->name,
                                'rating'     => $hotels[0]->rating,
                                'created_at' => $hotels[0]->created_at->toJSON(),
                                'updated_at' => $hotels[0]->updated_at->toJSON(),
                            ]
                        ],
                        [
                            'id'         => '2',
                            'type'       => self::TEST_TYPE,
                            'attributes' => [
                                'name'       => $hotels[1]->name,
                                'rating'     => $hotels[1]->rating,
                                'created_at' => $hotels[1]->created_at->toJSON(),
                                'updated_at' => $hotels[1]->updated_at->toJSON(),
                            ]
                        ],
                    ]
                ]);
        }

        public function testItCanCreateAnHotelFromAResourceObject(): void
        {
            $this->postJson('/api/v1/hotels', [
                'data' => [
                    'type'       => self::TEST_TYPE,
                    'attributes' => [
                        'name'   => self::TEST_NAME,
                        'rating' => self::TEST_RATING,
                    ]
                ]
            ])
                ->assertStatus(201)
                ->assertJson([
                    'data' => [
                        'id'         => '1',
                        'type'       => self::TEST_TYPE,
                        'attributes' => [
                            'name'       => self::TEST_NAME,
                            'rating'     => self::TEST_RATING,
                            'created_at' => now()->setMilliseconds(0)->toJSON(),
                            'updated_at' => now()->setMilliseconds(0)->toJSON(),
                        ]
                    ]
                ])
                ->assertHeader('Location', url('/api/v1/hotels/1'));

            $this->assertDatabaseHas('hotels', [
                'id'     => 1,
                'name'   => self::TEST_NAME,
                'rating' => self::TEST_RATING,
            ]);
        }

        public function testItCanUpdateAnHotelFromAResourceObject(): void
        {
            factory(Hotel::class)->create();

            $this->patchJson('/api/v1/hotels/1', [
                'data' => [
                    'type'       => self::TEST_TYPE,
                    'attributes' => [
                        'name'   => self::TEST_NAME,
                        'rating' => self::TEST_RATING,
                    ]
                ]
            ])
                ->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'id'         => '1',
                        'type'       => self::TEST_TYPE,
                        'attributes' => [
                            'name'       => self::TEST_NAME,
                            'rating'     => self::TEST_RATING,
                            'created_at' => now()->setMilliseconds(0)->toJSON(),
                            'updated_at' => now()->setMilliseconds(0)->toJSON(),
                        ],
                    ]
                ]);

            $this->assertDatabaseHas('hotels', [
                'name'   => self::TEST_NAME,
                'rating' => self::TEST_RATING,
            ]);

        }

        public function testItCanDeleteAnHotelThroughADeleteRequest(): void
        {
            $hotel = factory(Hotel::class)->create();

            $this->delete('/api/v1/hotels/1', [], [
                'Accept'       => 'application/vnd.api+json',
                'Content-Type' => 'application/vnd.api+json',
            ])->assertStatus(204);

            $this->assertDatabaseMissing('hotels', [
                'id'     => 1,
                'name'   => $hotel->name,
                'rating' => $hotel->rating,
            ]);
        }

        public function testItValidatesThatTheTypeMemberIsGivenWhenCreatingAnHotel(): void
        {
            factory(Hotel::class)->create();

            $this->postJson('/api/v1/hotels', [
                'data' => [
                    'type'       => '',
                    'attributes' => [
                        'name'   => self::TEST_NAME,
                        'rating' => self::TEST_RATING,
                    ]
                ]
            ])
                ->assertStatus(422)
                ->assertJson([
                    'errors' => [
                        'data.type' => [
                            'The data.type field is required.'
                        ]
                    ]
                ]);

            $this->assertDatabaseMissing('hotels', [
                'name'   => self::TEST_NAME,
                'rating' => self::TEST_RATING,
            ]);
        }

        public function testItValidatesThatTheTypeMemberHasTheValueOfHotelsWhenCreatingAnHotel(): void
        {
            factory(Hotel::class)->create();

            $this->postJson('/api/v1/hotels', [
                'data' => [
                    'type'       => self::TEST_TYPE_WRONG,
                    'attributes' => [
                        'name'   => self::TEST_NAME,
                        'rating' => self::TEST_RATING,
                    ]
                ]
            ])
                ->assertStatus(422)
                ->assertJson([
                    'errors' => [
                        'data.type' => [
                            'The selected data.type is invalid.'
                        ]
                    ]
                ]);

            $this->assertDatabaseMissing('hotels', [
                'name'   => self::TEST_NAME,
                'rating' => self::TEST_RATING,
            ]);

        }

        public function testItValidatesThatTheAttributesMemberHasBeenGivenWhenCreatingAnHotel(): void
        {
            factory(Hotel::class)->create();

            $this->postJson('/api/v1/hotels', [
                'data' => [
                    'type' => self::TEST_TYPE,
                ]
            ])
                ->assertStatus(422)
                ->assertJson([
                    'errors' => [
                        'data.attributes' => [
                            'The data.attributes field is required.'
                        ]
                    ]
                ]);

            $this->assertDatabaseMissing('hotels', [
                'name'   => self::TEST_NAME,
                'rating' => self::TEST_RATING,
            ]);
        }
    }
