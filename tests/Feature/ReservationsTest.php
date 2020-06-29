<?php

    namespace Tests\Feature;

    use App\Model\Category;
    use App\Model\Reservation;
    use App\Model\Room;
    use Carbon\Carbon;
    use Illuminate\Foundation\Testing\DatabaseMigrations;
    use Tests\TestCase;

    class ReservationsTest extends TestCase
    {
        use DatabaseMigrations;

        public const TEST_NAME = 'Alex Core';
        public const TEST_PHONE = '(050) 123-45-67';
        public const TEST_PERSONS = 2;
        public const TEST_PERSONS_WRONG = 10;
        public const TEST_ARRIVAL = '2020-07-07 12:00:00';
        public const TEST_DEPARTURE = '2020-07-17 12:00:00';
        public const TEST_TYPE = 'reservations';
        public const TEST_TYPE_WRONG = 'another';

        public function testItReturnsAnReservationAsAResourceObject(): void
        {
            $room = factory(Room::class)->create([
                'category_id' => factory(Category::class)->create()->id,
            ]);

            /** @var Reservation $reservation */
            $reservation = factory(Reservation::class)->create([
                'room_id'   => $room->id,
                'name'      => self::TEST_NAME,
                'phone'     => self::TEST_PHONE,
                'persons'   => self::TEST_PERSONS,
                'arrival'   => self::TEST_ARRIVAL,
                'departure' => self::TEST_DEPARTURE,
            ]);

            $this->getJson('/api/v1/reservations/1')
                ->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'id'         => '1',
                        'type'       => self::TEST_TYPE,
                        'attributes' => [
                            'name'       => $reservation->name,
                            'phone'      => $reservation->phone,
                            'persons'    => $reservation->persons,
                            'arrival'    => $reservation->arrival->toJSON(),
                            'departure'  => $reservation->departure->toJSON(),
                            'created_at' => $reservation->created_at->toJSON(),
                            'updated_at' => $reservation->updated_at->toJSON(),
                        ]
                    ]
                ]);
        }

        public function testItReturnsAllReservationsAsACollectionOfResourceObjects(): void
        {
            $room = factory(Room::class)->create([
                'category_id' => factory(Category::class)->create()->id,
            ]);
            /** @var Reservation $reservation */
            $reservation = factory(Reservation::class, 2)->create([
                'room_id' => $room->id,
            ]);

            $this->getJson('/api/v1/reservations')
                ->assertStatus(200)
                ->assertJson([
                    'data' => [
                        [
                            'id'         => '1',
                            'type'       => self::TEST_TYPE,
                            'attributes' => [
                                'name'       => $reservation[0]->name,
                                'phone'      => $reservation[0]->phone,
                                'persons'    => $reservation[0]->persons,
                                'arrival'    => $reservation[0]->arrival->toJSON(),
                                'departure'  => $reservation[0]->departure->toJSON(),
                                'created_at' => $reservation[0]->created_at->toJSON(),
                                'updated_at' => $reservation[0]->updated_at->toJSON(),
                            ]
                        ],
                        [
                            'id'         => '2',
                            'type'       => self::TEST_TYPE,
                            'attributes' => [
                                'name'       => $reservation[1]->name,
                                'phone'      => $reservation[1]->phone,
                                'persons'    => $reservation[1]->persons,
                                'arrival'    => $reservation[1]->arrival->toJSON(),
                                'departure'  => $reservation[1]->departure->toJSON(),
                                'created_at' => $reservation[1]->created_at->toJSON(),
                                'updated_at' => $reservation[1]->updated_at->toJSON(),
                            ]
                        ],
                    ]
                ]);
        }

        public function testItCanCreateAnReservationFromAResourceObject(): void
        {
            $room = factory(Room::class)->create([
                'category_id' => factory(Category::class)->create()->id,
            ]);

            $this->postJson('/api/v1/reservations', [
                'data' => [
                    'type'       => self::TEST_TYPE,
                    'attributes' => [
                        'room_id'   => $room->id,
                        'name'      => self::TEST_NAME,
                        'phone'     => self::TEST_PHONE,
                        'persons'   => self::TEST_PERSONS,
                        'arrival'   => self::TEST_ARRIVAL,
                        'departure' => self::TEST_DEPARTURE,
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
                            'phone'      => self::TEST_PHONE,
                            'persons'    => self::TEST_PERSONS,
                            'arrival'    => Carbon::parse(self::TEST_ARRIVAL)->toJSON(),
                            'departure'  => Carbon::parse(self::TEST_DEPARTURE)->toJSON(),
                            'created_at' => now()->setMilliseconds(0)->toJSON(),
                            'updated_at' => now()->setMilliseconds(0)->toJSON(),
                        ]
                    ]
                ])
                ->assertHeader('Location', url('/api/v1/reservations/1'));

            $this->assertDatabaseHas('reservations', [
                'id'        => 1,
                'name'      => self::TEST_NAME,
                'phone'     => self::TEST_PHONE,
                'persons'   => self::TEST_PERSONS,
                'arrival'   => self::TEST_ARRIVAL,
                'departure' => self::TEST_DEPARTURE,
            ]);
        }

        public function testItCanUpdateAnReservationFromAResourceObject(): void
        {
            $room = factory(Room::class)->create([
                'category_id' => factory(Category::class)->create()->id,
            ]);
            factory(Reservation::class)->create([
                'room_id' => $room->id,
            ]);

            $this->patchJson('/api/v1/reservations/1', [
                'data' => [
                    'type'       => self::TEST_TYPE,
                    'attributes' => [
                        'name'      => self::TEST_NAME,
                        'phone'     => self::TEST_PHONE,
                        'persons'   => self::TEST_PERSONS,
                        'arrival'   => self::TEST_ARRIVAL,
                        'departure' => self::TEST_DEPARTURE,
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
                            'phone'      => self::TEST_PHONE,
                            'persons'    => self::TEST_PERSONS,
                            'arrival'    => Carbon::parse(self::TEST_ARRIVAL)->toJSON(),
                            'departure'  => Carbon::parse(self::TEST_DEPARTURE)->toJSON(),
                            'created_at' => now()->setMilliseconds(0)->toJSON(),
                            'updated_at' => now()->setMilliseconds(0)->toJSON(),
                        ],
                    ]
                ]);

            $this->assertDatabaseHas('reservations', [
                'name'      => self::TEST_NAME,
                'phone'     => self::TEST_PHONE,
                'persons'   => self::TEST_PERSONS,
                'arrival'   => self::TEST_ARRIVAL,
                'departure' => self::TEST_DEPARTURE,
            ]);

        }

        public function testItCanDeleteAnReservationThroughADeleteRequest(): void
        {
            $room = factory(Room::class)->create([
                'category_id' => factory(Category::class)->create()->id,
            ]);
            $reservation = factory(Reservation::class)->create([
                'room_id' => $room->id,
            ]);

            $this->delete('/api/v1/reservations/1', [], [
                'Accept'       => 'application/vnd.api+json',
                'Content-Type' => 'application/vnd.api+json',
            ])->assertStatus(204);

            $this->assertDatabaseMissing('reservations', [
                'id'        => 1,
                'name'      => $reservation->name,
                'phone'     => $reservation->phone,
                'persons'   => $reservation->persons,
                'arrival'   => $reservation->arrival->toJSON(),
                'departure' => $reservation->departure->toJSON(),
            ]);
        }

        public function testItValidatesThatTheTypeMemberIsGivenWhenCreatingAnReservation(): void
        {
            $room = factory(Room::class)->create([
                'category_id' => factory(Category::class)->create()->id,
            ]);
            $reservation = factory(Reservation::class)->create([
                'room_id' => $room->id,
            ]);

            $this->postJson('/api/v1/reservations', [
                'data' => [
                    'type'       => '',
                    'attributes' => [
                        'name'      => self::TEST_NAME,
                        'phone'     => self::TEST_PHONE,
                        'persons'   => self::TEST_PERSONS,
                        'arrival'   => self::TEST_ARRIVAL,
                        'departure' => self::TEST_DEPARTURE,
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

            $this->assertDatabaseMissing('reservations', [
                'name'      => self::TEST_NAME,
                'phone'     => self::TEST_PHONE,
                'persons'   => self::TEST_PERSONS,
                'arrival'   => self::TEST_ARRIVAL,
                'departure' => self::TEST_DEPARTURE,
            ]);
        }

        public function testItValidatesThatTheTypeMemberHasTheValueOfReservationsWhenCreatingAnReservation(): void
        {
            $room = factory(Room::class)->create([
                'category_id' => factory(Category::class)->create()->id,
            ]);
            $reservation = factory(Reservation::class)->create([
                'room_id' => $room->id,
            ]);

            $this->postJson('/api/v1/reservations', [
                'data' => [
                    'type'       => self::TEST_TYPE_WRONG,
                    'attributes' => [
                        'name'      => self::TEST_NAME,
                        'phone'     => self::TEST_PHONE,
                        'persons'   => self::TEST_PERSONS,
                        'arrival'   => self::TEST_ARRIVAL,
                        'departure' => self::TEST_DEPARTURE,
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

            $this->assertDatabaseMissing('reservations', [
                'name'      => self::TEST_NAME,
                'phone'     => self::TEST_PHONE,
                'persons'   => self::TEST_PERSONS,
                'arrival'   => self::TEST_ARRIVAL,
                'departure' => self::TEST_DEPARTURE,
            ]);

        }

        public function testItValidatesThatTheAttributesMemberHasBeenGivenWhenCreatingAnReservation(): void
        {
            $room = factory(Room::class)->create([
                'category_id' => factory(Category::class)->create()->id,
            ]);
            $reservation = factory(Reservation::class)->create([
                'room_id' => $room->id,
            ]);

            $this->postJson('/api/v1/reservations', [
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

            $this->assertDatabaseMissing('reservations', [
                'name'      => self::TEST_NAME,
                'phone'     => self::TEST_PHONE,
                'persons'   => self::TEST_PERSONS,
                'arrival'   => self::TEST_ARRIVAL,
                'departure' => self::TEST_DEPARTURE,
            ]);
        }
    }
