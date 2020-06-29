<?php

    namespace Tests\Feature;

    use App\Model\Category;
    use App\Model\Hotel;
    use App\Model\Room;
    use Illuminate\Foundation\Testing\DatabaseMigrations;
    use Tests\TestCase;

    class RoomsTest extends TestCase
    {
        use DatabaseMigrations;

        public const TEST_NUMBER = '13C';
        public const TEST_CAPACITY = 2;
        public const TEST_CAPACITY_WRONG = 20;
        public const TEST_TYPE = 'rooms';
        public const TEST_TYPE_WRONG = 'another';

        public function testItReturnsAnRoomAsAResourceObject(): void
        {
            $category = factory(Category::class)->create();
            $hotel = factory(Hotel::class)->create();

            /** @var Room $room */
            $room = factory(Room::class)->make([
                'number'      => self::TEST_NUMBER,
                'capacity'    => self::TEST_CAPACITY,
                'category_id' => $category->id
            ]);
            $hotel->rooms()->save($room);

            $this->getJson('/api/v1/rooms/1')
                ->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'id'           => $room->id,
                        'type'         => self::TEST_TYPE,
                        'attributes'   => [
                            'category'   => [
                                'id'   => $category->id,
                                'name' => $category->name
                            ],
                            'number'     => $room->number,
                            'capacity'   => $room->capacity,
                            'created_at' => $room->created_at->toJSON(),
                            'updated_at' => $room->updated_at->toJSON(),
                        ],
                        'relationships' => [
                            'hotel' => [
                                'id' => $hotel->id,
                                'type' => 'hotels',
                                'attributes' => [
                                    'name'       => $hotel->name,
                                    'rating'     => $hotel->rating,
                                    'created_at' => $hotel->created_at->toJSON(),
                                    'updated_at' => $hotel->updated_at->toJSON(),
                                ]
                            ]
                        ]
                    ]
                ]);
        }

        public function testItReturnsAllRoomsAsACollectionOfResourceObjects(): void
        {
            $category = factory(Category::class)->create();
            $hotel = factory(Hotel::class)->create();
            $rooms = factory(Room::class, 2)->create([
                'category_id' => $category->id,
                'hotel_id'    => $hotel->id,
            ]);

            $this->getJson('/api/v1/rooms')
                ->assertStatus(200)
                ->assertJson([
                    'data' => [
                        [
                            'id'         => '1',
                            'type'       => self::TEST_TYPE,
                            'attributes' => [
                                'category'   => [
                                    'id'   => $category->id,
                                    'name' => $category->name
                                ],
                                'number'     => $rooms[0]->number,
                                'capacity'   => $rooms[0]->capacity,
                                'created_at' => $rooms[0]->created_at->toJSON(),
                                'updated_at' => $rooms[0]->updated_at->toJSON(),
                            ],
                            'relationships' => [
                                'hotel' => [
                                    'id' => $hotel->id,
                                    'type' => 'hotels',
                                    'attributes' => [
                                        'name'       => $hotel->name,
                                        'rating'     => $hotel->rating,
                                        'created_at' => $hotel->created_at->toJSON(),
                                        'updated_at' => $hotel->updated_at->toJSON(),
                                    ]
                                ]
                            ]
                        ],
                        [
                            'id'         => '2',
                            'type'       => self::TEST_TYPE,
                            'attributes' => [
                                'category'   => [
                                    'id'   => $category->id,
                                    'name' => $category->name
                                ],
                                'number'     => $rooms[1]->number,
                                'capacity'   => $rooms[1]->capacity,
                                'created_at' => $rooms[1]->created_at->toJSON(),
                                'updated_at' => $rooms[1]->updated_at->toJSON(),
                            ],
                            'relationships' => [
                                'hotel' => [
                                    'id' => $hotel->id,
                                    'type' => 'hotels',
                                    'attributes' => [
                                        'name'       => $hotel->name,
                                        'rating'     => $hotel->rating,
                                        'created_at' => $hotel->created_at->toJSON(),
                                        'updated_at' => $hotel->updated_at->toJSON(),
                                    ]
                                ]
                            ]
                        ],
                    ]
                ]);
        }

        public function testItCanCreateAnRoomFromAResourceObject(): void
        {
            $category = factory(Category::class)->create();
            $hotel = factory(Hotel::class)->create();

            $this->postJson('/api/v1/rooms', [
                'data' => [
                    'type'       => self::TEST_TYPE,
                    'attributes' => [
                        'number'      => self::TEST_NUMBER,
                        'capacity'    => self::TEST_CAPACITY,
                        'category_id' => $category->id,
                        'hotel_id'    => $hotel->id,
                    ]
                ]
            ])
                ->assertStatus(201)
                ->assertJson([
                    'data' => [
                        'id'         => '1',
                        'type'       => self::TEST_TYPE,
                        'attributes' => [
                            'category'   => [
                                'id'   => $category->id,
                                'name' => $category->name
                            ],
                            'number'     => self::TEST_NUMBER,
                            'capacity'   => self::TEST_CAPACITY,
                            'created_at' => now()->setMilliseconds(0)->toJSON(),
                            'updated_at' => now()->setMilliseconds(0)->toJSON(),
                        ],
                        'relationships' => [
                            'hotel' => [
                                'id' => $hotel->id,
                                'type' => 'hotels',
                                'attributes' => [
                                    'name'       => $hotel->name,
                                    'rating'     => $hotel->rating,
                                    'created_at' => $hotel->created_at->toJSON(),
                                    'updated_at' => $hotel->updated_at->toJSON(),
                                ]
                            ]
                        ]
                    ]
                ])
                ->assertHeader('Location', url('/api/v1/rooms/1'));

            $this->assertDatabaseHas('rooms', [
                'id'          => 1,
                'number'      => self::TEST_NUMBER,
                'capacity'    => self::TEST_CAPACITY,
                'category_id' => $category->id,
                'hotel_id'    => $hotel->id,
            ]);
        }

        public function testItCanUpdateAnRoomFromAResourceObject(): void
        {
            $category = factory(Category::class)->create();
            $hotel = factory(Hotel::class)->create();
            factory(Room::class)->create([
                'category_id' => $category->id,
                'hotel_id'    => $hotel->id,
            ]);

            $this->patchJson('/api/v1/rooms/1', [
                'data' => [
                    'type'       => self::TEST_TYPE,
                    'attributes' => [
                        'number'   => self::TEST_NUMBER,
                        'capacity' => self::TEST_CAPACITY,
                    ]
                ]
            ])
                ->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'id'         => '1',
                        'type'       => self::TEST_TYPE,
                        'attributes' => [
                            'number'     => self::TEST_NUMBER,
                            'capacity'   => self::TEST_CAPACITY,
                            'created_at' => now()->setMilliseconds(0)->toJSON(),
                            'updated_at' => now()->setMilliseconds(0)->toJSON(),
                        ],
                    ]
                ]);

            $this->assertDatabaseHas('rooms', [
                'number'   => self::TEST_NUMBER,
                'capacity' => self::TEST_CAPACITY,
            ]);

        }

        public function testItCanDeleteAnRoomThroughADeleteRequest(): void
        {
            $category = factory(Category::class)->create();
            $hotel = factory(Hotel::class)->create();
            $room = factory(Room::class)->create([
                'category_id' => $category->id,
                'hotel_id'    => $hotel->id,
            ]);

            $this->delete('/api/v1/rooms/1', [], [
                'Accept'       => 'application/vnd.api+json',
                'Content-Type' => 'application/vnd.api+json',
            ])->assertStatus(204);

            $this->assertDatabaseMissing('rooms', [
                'id'       => 1,
                'number'   => $room->number,
                'capacity' => $room->capacity,
            ]);
        }

        public function testItValidatesThatTheTypeMemberIsGivenWhenCreatingAnRoom(): void
        {
            $category = factory(Category::class)->create();
            $hotel = factory(Hotel::class)->create();
            $room = factory(Room::class)->create([
                'category_id' => $category->id,
                'hotel_id'    => $hotel->id,
            ]);

            $this->postJson('/api/v1/rooms', [
                'data' => [
                    'type'       => '',
                    'attributes' => [
                        'number'   => self::TEST_NUMBER,
                        'capacity' => self::TEST_CAPACITY,
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

            $this->assertDatabaseMissing('rooms', [
                'number'   => self::TEST_NUMBER,
                'capacity' => self::TEST_CAPACITY,
            ]);
        }

        public function testItValidatesThatTheTypeMemberHasTheValueOfRoomsWhenCreatingAnRoom(): void
        {
            $category = factory(Category::class)->create();
            $hotel = factory(Hotel::class)->create();
            $room = factory(Room::class)->create([
                'category_id' => $category->id,
                'hotel_id'    => $hotel->id,
            ]);

            $this->postJson('/api/v1/rooms', [
                'data' => [
                    'type'       => self::TEST_TYPE_WRONG,
                    'attributes' => [
                        'number'   => self::TEST_NUMBER,
                        'capacity' => self::TEST_CAPACITY,
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

            $this->assertDatabaseMissing('rooms', [
                'number'   => self::TEST_NUMBER,
                'capacity' => self::TEST_CAPACITY,
            ]);

        }

        public function testItValidatesThatTheAttributesMemberHasBeenGivenWhenCreatingAnRoom(): void
        {
            $category = factory(Category::class)->create();
            $hotel = factory(Hotel::class)->create();
            $room = factory(Room::class)->create([
                'category_id' => $category->id,
                'hotel_id'    => $hotel->id,
            ]);

            $this->postJson('/api/v1/rooms', [
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

            $this->assertDatabaseMissing('rooms', [
                'number'   => self::TEST_NUMBER,
                'capacity' => self::TEST_CAPACITY,
            ]);
        }
    }
