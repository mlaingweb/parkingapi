<?php

namespace App\Tests;

use App\Entity\Bookings;
use App\Entity\CarParks;
use App\Entity\Customer;
use App\Entity\Prices;
use App\Entity\Spaces;
use App\Service\CarParkService;
use Carbon\Carbon;

class BookingServiceCest
{

    /**
     * Test check availability functionality without active bookings
     * @group spaceCheck
     * @param UnitTester $I
     * @return void
     */
    public function testCheckAvailabilityWithoutBookings(UnitTester $I) {
        $carParkService = $I->grabService(CarParkService::class);
        $startDate = Carbon::createFromFormat('d/m/Y', '15/10/2020');
        $endDate = Carbon::createFromFormat('d/m/Y','18/10/2020');
        $carPark = $I->grabEntityFromRepository(CarParks::class, ['id' => 1]);
        $result = $carParkService->getAvailability($startDate, $endDate, $carPark);
        $I->assertIsArray($result);
        $I->assertArrayHasKey('2020-10-15', $result);
        $I->assertEquals(10, $result['2020-10-15']);
        $I->assertArrayHasKey('2020-10-16', $result);
        $I->assertEquals(10, $result['2020-10-16']);
        $I->assertArrayHasKey('2020-10-17', $result);
        $I->assertEquals(10, $result['2020-10-17']);
        $I->assertArrayHasKey('2020-10-18', $result);
        $I->assertEquals(10, $result['2020-10-18']);
    }

    /**
     * Test check availability functionality with active bookings
     * @group spaceCheck
     * @param UnitTester $I
     * @return void
     */
    public function testCheckAvailabilityWithBookings(UnitTester $I) {
        $carParkService = $I->grabService(CarParkService::class);
        $startDate = Carbon::createFromFormat('d/m/Y','15/10/2020');
        $endDate = Carbon::createFromFormat('d/m/Y','18/10/2020');
        $carPark = $I->grabEntityFromRepository(CarParks::class, ['id' => 1]);
        $customer = $I->have(Customer::class);
        $booking = $I->have(Bookings::class, ['customer' => $customer, 'start_date' => $startDate->copy()->addDay(), 'end_date' => $startDate->copy()->addDay(), 'car_park_id' => $carPark, 'car_registration' => 'SL21 UWM']);
        $result = $carParkService->getAvailability($startDate, $endDate, $carPark);
        $I->assertIsArray($result);
        $I->assertArrayHasKey('2020-10-15', $result);
        $I->assertEquals(10, $result['2020-10-15']);
        $I->assertArrayHasKey('2020-10-16', $result);
        $I->assertEquals(9, $result['2020-10-16']);
        $I->assertArrayHasKey('2020-10-17', $result);
        $I->assertEquals(10, $result['2020-10-17']);
        $I->assertArrayHasKey('2020-10-18', $result);
        $I->assertEquals(10, $result['2020-10-18']);
    }

    /**
     * Test check winter prices
     * @group priceCheck
     * @param UnitTester $I
     * @return void
     */
    public function testCheckWinterPrices(UnitTester $I) {
        $carParkService = $I->grabService(CarParkService::class);
        $startDate = Carbon::createFromFormat('d/m/Y', '15/10/2020');
        $endDate = Carbon::createFromFormat('d/m/Y','18/10/2020');
        $carPark = $I->grabEntityFromRepository(CarParks::class, ['id' => 1]);
        $winterPrice = $I->have(Prices::class, ['CarParkId' => $carPark, 'price' => 10, 'startDate' => Carbon::createFromFormat('d/m/Y', '01/09/2020'), 'endDate' => Carbon::createFromFormat('d/m/Y', '28/02/2021')]);
        $summerPrice = $I->have(Prices::class, ['CarParkId' => $carPark, 'price' => 15, 'startDate' => Carbon::createFromFormat('d/m/Y', '01/03/2020'), 'endDate' => Carbon::createFromFormat('d/m/Y', '31/08/2020')]);
        $result = $carParkService->getPrices($startDate, $endDate, $carPark);
        codecept_debug($result);
        $I->assertIsArray($result);
        $I->assertArrayHasKey('2020-10-15', $result);
        $I->assertEquals(10, $result['2020-10-15']);
        $I->assertArrayHasKey('2020-10-16', $result);
        $I->assertEquals(10, $result['2020-10-16']);
        $I->assertArrayHasKey('2020-10-17', $result);
        $I->assertEquals(10, $result['2020-10-17']);
        $I->assertArrayHasKey('2020-10-18', $result);
        $I->assertEquals(10, $result['2020-10-18']);
    }

    /**
     * Test check summer prices
     * @group priceCheck
     * @param UnitTester $I
     * @return void
     */
    public function testCheckSummerPrices(UnitTester $I) {
        $carParkService = $I->grabService(CarParkService::class);
        $startDate = Carbon::createFromFormat('d/m/Y', '15/07/2020');
        $endDate = Carbon::createFromFormat('d/m/Y','18/07/2020');
        $carPark = $I->grabEntityFromRepository(CarParks::class, ['id' => 1]);
        $winterPrice = $I->have(Prices::class, ['CarParkId' => $carPark, 'price' => 10, 'startDate' => Carbon::createFromFormat('d/m/Y', '01/09/2020'), 'endDate' => Carbon::createFromFormat('d/m/Y', '28/02/2021')]);
        $summerPrice = $I->have(Prices::class, ['CarParkId' => $carPark, 'price' => 15, 'startDate' => Carbon::createFromFormat('d/m/Y', '01/03/2020'), 'endDate' => Carbon::createFromFormat('d/m/Y', '31/08/2020')]);
        $result = $carParkService->getPrices($startDate, $endDate, $carPark);
        codecept_debug($result);
        $I->assertIsArray($result);
        $I->assertArrayHasKey('2020-07-15', $result);
        $I->assertEquals(15, $result['2020-07-15']);
        $I->assertArrayHasKey('2020-07-16', $result);
        $I->assertEquals(15, $result['2020-07-16']);
        $I->assertArrayHasKey('2020-07-17', $result);
        $I->assertEquals(15, $result['2020-07-17']);
        $I->assertArrayHasKey('2020-07-18', $result);
        $I->assertEquals(15, $result['2020-07-18']);
    }

    /**
     * Test check mixed prices
     * @group priceCheck
     * @param UnitTester $I
     * @return void
     */
    public function testCheckMixedPrices(UnitTester $I) {
        $carParkService = $I->grabService(CarParkService::class);
        $startDate = Carbon::createFromFormat('d/m/Y', '30/08/2020');
        $endDate = Carbon::createFromFormat('d/m/Y','03/09/2020');
        $carPark = $I->grabEntityFromRepository(CarParks::class, ['id' => 1]);
        $winterPrice = $I->have(Prices::class, ['CarParkId' => $carPark, 'price' => 10, 'startDate' => Carbon::createFromFormat('d/m/Y', '01/09/2020'), 'endDate' => Carbon::createFromFormat('d/m/Y', '28/02/2021')]);
        $summerPrice = $I->have(Prices::class, ['CarParkId' => $carPark, 'price' => 15, 'startDate' => Carbon::createFromFormat('d/m/Y', '01/03/2020'), 'endDate' => Carbon::createFromFormat('d/m/Y', '31/08/2020')]);
        $result = $carParkService->getPrices($startDate, $endDate, $carPark);
        codecept_debug($result);
        $I->assertIsArray($result);
        $I->assertArrayHasKey('2020-08-30', $result);
        $I->assertEquals(15, $result['2020-08-30']);
        $I->assertArrayHasKey('2020-09-01', $result);
        $I->assertEquals(10, $result['2020-09-01']);
        $I->assertArrayHasKey('2020-09-02', $result);
        $I->assertEquals(10, $result['2020-09-02']);
        $I->assertArrayHasKey('2020-09-03', $result);
        $I->assertEquals(10, $result['2020-09-03']);

    }

    /**
     * Test make booking where spaces are available for all dates
     * @group makeBooking
     * @param UnitTester $I
     * @return void
     */
    public function testMakeBookingAllDatesAvailable(UnitTester $I) {
        $carParkService = $I->grabService(CarParkService::class);
        $data = [
            "from" => "15/10/2020",
            "to" => "18/10/2020",
            "carParkId" => 1,
            "registration" => "SL21 UWM",
            "customer" => [
                "firstname" => "Martin",
                "lastname" => "Laing",
                "email" => "martinlaing@hotmail.co.uk"
            ]
        ];
        $result = $carParkService->addReservation($data);
        $I->assertInstanceOf(Bookings::class, $result);
    }

    /**
     * Test make booking where spaces are not available for all dates
     * @group makeBooking2
     * @param UnitTester $I
     * @return void
     */
    public function testMakeBookingAllDatesNotAvailable(UnitTester $I) {
        $carParkService = $I->grabService(CarParkService::class);
        $data = [
            "from" => "15/10/2020",
            "to" => "18/10/2020",
            "carParkId" => 1,
            "registration" => "SL21 UWM",
            "customer" => [
                "firstname" => "Martin",
                "lastname" => "Laing",
                "email" => "martinlaing@hotmail.co.uk"
            ]
        ];
        $carPark = $I->grabEntityFromRepository(CarParks::class, ['id' => 1]);
        $customer = $I->have(Customer::class);
        $bookings = $I->haveMultiple(Bookings::class, 10, ['customer' => $customer, 'start_date' => Carbon::createFromFormat('d/m/Y', '16/10/2020'), 'end_date' => Carbon::createFromFormat('d/m/Y', '16/10/2020'), 'car_park_id' => $carPark, 'car_registration' => 'SL21 UWM']);
        $result = $carParkService->addReservation($data);
        $I->assertNull($result);
    }

    /**
     * Test cancel existing booking
     * @group cancelBooking
     * @param UnitTester $I
     * @return void
     */
    public function testCancelExistingBooking(UnitTester $I) {
        $carParkService = $I->grabService(CarParkService::class);
        $carPark = $I->grabEntityFromRepository(CarParks::class, ['id' => 1]);
        $customer = $I->have(Customer::class, ['email' => 'martinlaing@hotmail.co.uk', 'car_registration' => 'SL21 UWM']);
        $booking = $I->have(Bookings::class, ['customer' => $customer, 'start_date' => Carbon::createFromFormat('d/m/Y', '16/10/2020'), 'end_date' => Carbon::createFromFormat('d/m/Y', '16/10/2020'), 'car_park_id' => $carPark, 'car_registration' => 'SL21 UWM']);
        $data = [
            "bookingId" => $booking->getId(),
            "email" => "martinlaing@hotmail.co.uk",
            "registration" => "SL21 UWM"
        ];
        $result = $carParkService->cancelBooking($data);
        $I->assertTrue($result);
    }

    /**
     * Test cancel existing booking with incorrect validation data
     * @group cancelBooking
     * @param UnitTester $I
     * @return void
     */
    public function testCancelExistingBookingWithInvalidData(UnitTester $I) {
        $carParkService = $I->grabService(CarParkService::class);
        $carPark = $I->grabEntityFromRepository(CarParks::class, ['id' => 1]);
        $customer = $I->have(Customer::class, ['email' => 'martinlaing@hotmail.co.uk', 'car_registration' => 'SL21 UWM']);
        $booking = $I->have(Bookings::class, ['customer' => $customer, 'start_date' => Carbon::createFromFormat('d/m/Y', '16/10/2020'), 'end_date' => Carbon::createFromFormat('d/m/Y', '16/10/2020'), 'car_park_id' => $carPark, 'car_registration' => 'SL21 UWM']);
        $data = [
            "bookingId" => $booking->getId(),
            "email" => "wrong@email.com",
            "registration" => "ABC 123"
        ];
        $result = $carParkService->cancelBooking($data);
        $I->assertFalse($result);
    }

    /**
     * Test cancel non-existing booking
     * @group cancelBooking
     * @param UnitTester $I
     * @return void
     */
    public function testCancelNonExistingBooking(UnitTester $I) {
        $carParkService = $I->grabService(CarParkService::class);
        $data = [
            "bookingId" => 1,
            "email" => "martinlaing@hotmail.co.uk",
            "registration" => "SL21 UWM"
        ];
        $result = $carParkService->cancelBooking($data);
        $I->assertFalse($result);
    }

    /**
     * Test update existing booking
     * @group updateBooking
     * @param UnitTester $I
     * @return void
     */
    public function testUpdateExistingBooking(UnitTester $I) {
        $carParkService = $I->grabService(CarParkService::class);
        $carPark = $I->grabEntityFromRepository(CarParks::class, ['id' => 1]);
        $customer = $I->have(Customer::class, ['email' => 'martinlaing@hotmail.co.uk', 'car_registration' => 'SL21 UWM']);
        $booking = $I->have(Bookings::class, ['customer' => $customer, 'start_date' => Carbon::createFromFormat('d/m/Y', '16/10/2020'), 'end_date' => Carbon::createFromFormat('d/m/Y', '16/10/2020'), 'car_park_id' => $carPark, 'car_registration' => 'SL21 UWM']);
        $data = [
            "bookingId" => $booking->getId(),
            "email" => "martinlaing@hotmail.co.uk",
            "registration" => "SL21 UWM",
            "new_registration" => "ABC 123",
            "from" => "28/10/2020",
            "to" => "28/10/2020"
        ];
        $result = $carParkService->amendBooking($data);
        $I->assertInstanceOf(Bookings::class, $result);
        $I->assertEquals('28/10/2020', $result->getStartDate()->format('d/m/Y'));
        $I->assertEquals('28/10/2020', $result->getEndDate()->format('d/m/Y'));
    }

    /**
     * Test update existing booking with invalid validation data
     * @group updateBooking
     * @param UnitTester $I
     * @return void
     */
    public function testUpdateExistingBookingWithInvalidData(UnitTester $I) {
        $carParkService = $I->grabService(CarParkService::class);
        $carPark = $I->grabEntityFromRepository(CarParks::class, ['id' => 1]);
        $customer = $I->have(Customer::class, ['email' => 'martinlaing@hotmail.co.uk', 'car_registration' => 'SL21 UWM']);
        $booking = $I->have(Bookings::class, ['customer' => $customer, 'start_date' => Carbon::createFromFormat('d/m/Y', '16/10/2020'), 'end_date' => Carbon::createFromFormat('d/m/Y', '16/10/2020'), 'car_park_id' => $carPark, 'car_registration' => 'SL21 UWM']);
        $data = [
            "bookingId" => $booking->getId(),
            "email" => "wrong@email.com",
            "registration" => "ABC 123",
            "new_registration" => "SL21 UWM",
            "from" => "28/10/2020",
            "to" => "28/10/2020"
        ];
        $result = $carParkService->amendBooking($data);
        $I->assertNull($result);
    }

    /**
     * Test update existing booking when date is not available
     * @group updateBooking
     * @param UnitTester $I
     * @return void
     */
    public function testUpdateBookingDataUnavailable(UnitTester $I) {
        $carParkService = $I->grabService(CarParkService::class);
        $carPark = $I->grabEntityFromRepository(CarParks::class, ['id' => 1]);
        $customer = $I->have(Customer::class, ['email' => 'martinlaing@hotmail.co.uk', 'car_registration' => 'SL21 UWM']);
        $booking = $I->have(Bookings::class, ['customer' => $customer, 'start_date' => Carbon::createFromFormat('d/m/Y', '16/10/2020'), 'end_date' => Carbon::createFromFormat('d/m/Y', '16/10/2020'), 'car_park_id' => $carPark, 'car_registration' => 'SL21 UWM']);
        $bookings = $I->haveMultiple(Bookings::class, 10, ['customer' => $customer, 'start_date' => Carbon::createFromFormat('d/m/Y', '28/10/2020'), 'end_date' => Carbon::createFromFormat('d/m/Y', '28/10/2020'), 'car_park_id' => $carPark, 'car_registration' => 'SL21 UWM']);
        $data = [
            "bookingId" => $booking->getId(),
            "email" => "martinlaing@hotmail.co.uk",
            "registration" => "SL21 UWM",
            "new_registration" => "ABC 123",
            "from" => "28/10/2020",
            "to" => "28/10/2020"
        ];
        $result = $carParkService->amendBooking($data);
        $I->assertNull($result);
    }
}