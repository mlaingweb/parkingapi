<?php

namespace App\Service;
use App\Entity\Bookings;
use App\Entity\CarParks;
use App\Entity\Customer;
use App\Entity\Prices;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Car Park Service
 */
class CarParkService {
    protected EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    /**
     * Get space availability for date range
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param CarParks $carPark
     * @return array
     */
    public function getAvailability(Carbon $startDate, Carbon $endDate, CarParks $carPark): array {
        $dates = array();
        $days = CarbonPeriod::between($startDate, $endDate);
        foreach ($days as $day) {
            $spaces = $this->em->getRepository(CarParks::class)->getSpaceAvailability($day, $day, $carPark);
            $dates[$day->toDateString()] = ($spaces['spaces'] - $spaces['bookings']);
        }
        return $dates;
    }

    /**
     * Check space has availability for date range
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param CarParks $carPark
     * @return bool
     */
    public function hasAvailability(Carbon $startDate, Carbon $endDate, CarParks $carPark): bool {
        $dates = $this->getAvailability($startDate, $endDate, $carPark);
        foreach ($dates as $date) {
            if ($date === 0) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check prices for date range
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param CarParks $carPark
     * @return array
     */
    public function getPrices(Carbon $startDate, Carbon $endDate, CarParks $carPark): array {
        $dates = array();
        $days = CarbonPeriod::between($startDate, $endDate);
        foreach ($days as $day) {
            $prices = $this->em->getRepository(Prices::class)->getDayPrice($day, $day, $carPark);
            if ($prices instanceof Prices) {
                $dates[$day->toDateString()] = $prices->getPrice();
            }

        }
        return $dates;
    }

    /**
     * Start process of reserving a parking space
     * @param array $data
     * @return Bookings|null
     */
    public function addReservation(array $data): ?Bookings {
        $startDate = Carbon::createFromFormat('d/m/Y', $data['from']);
        $endDate = Carbon::createFromFormat('d/m/Y', $data['to']);
        $carPark = $this->em->getRepository(CarParks::class)->find($data['carParkId']);
        // check car park availability
        $availability = $this->hasAvailability($startDate, $endDate, $carPark);
        if ($availability) {
            // check if customer exists
            $customer = $this->getCustomer($data['customer']['email']);
            if (!$customer instanceof Customer) {
                $customer = $this->createCustomer($data['customer']);
            }

            if ($customer instanceof Customer) {
                // make booking
                $booking = $this->makeBooking($startDate, $endDate, $carPark, $customer, $data['registration']);
                if ($booking instanceof Bookings) {
                    // success
                    return $booking;
                }
            }
        }
        return null;
    }

    /**
     * Create Booking for parking
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param CarParks $carPark
     * @param Customer $customer
     * @param string $carRegistration
     * @return Bookings|null]
     */
    public function makeBooking(Carbon $startDate, Carbon $endDate, CarParks $carPark, Customer $customer, string $carRegistration): ?Bookings {
        try {
            $booking = new Bookings();
            $booking->setStartDate($startDate);
            $booking->setEndDate($endDate);
            $booking->setCarParkId($carPark);
            $booking->setCustomer($customer);
            $booking->setCarRegistration($carRegistration);
            $this->em->persist($booking);
            $this->em->flush();
            return $booking;
        } catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * Create customer from data array
     * @param array $data
     * @return Customer|null
     */
    public function createCustomer(array $data): ?Customer {
        try {
            $customer = new Customer();
            $customer->setFirstname($data['firstname']);
            $customer->setLastname($data['lastname']);
            $customer->setEmail($data['email']);
            $this->em->persist($customer);
            $this->em->flush();
            return $customer;
        } catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * Find customer by email address
     * @param string $email
     * @return Customer|null
     */
    public function getCustomer(string $email): ?Customer {
        return $this->em->getRepository(Customer::class)->findOneByEmail($email);
    }

    /**
     * cancel a booking
     * @param array $data
     * @return bool
     */
    public function cancelBooking(array $data):bool {
        try {
            if (isset($data['bookingId'], $data['email'], $data['registration'])) {
                $booking = $this->em->getRepository(Bookings::class)->find($data['bookingId']);
                if ($booking instanceof Bookings) {
                    // verify customer information
                    $customer = $booking->getCustomer();
                    if ($customer instanceof Customer) {
                        if ($customer->getEmail() === $data['email'] && $booking->getCarRegistration() === $data['registration']) {
                            $this->em->remove($booking);
                            $this->em->flush();
                            return true;
                        }
                    }
                }
            }
            return false;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Amend booking
     * @param array $data
     * @return Bookings|null
     */
    public function amendBooking(array $data): ?Bookings {
        try {
            if (isset($data['bookingId'], $data['email'], $data['registration'])) {
                $booking = $this->em->getRepository(Bookings::class)->find($data['bookingId']);
                if ($booking instanceof Bookings) {
                    // verify customer information
                    $customer = $booking->getCustomer();
                    if ($customer instanceof Customer) {
                        if ($customer->getEmail() === $data['email'] && $booking->getCarRegistration() === $data['registration']) {
                            $startDate = Carbon::createFromFormat('d/m/Y', $data['from']);
                            $endDate = Carbon::createFromFormat('d/m/Y', $data['to']);
                            // check car park availability
                            $availability = $this->hasAvailability($startDate, $endDate, $booking->getCarParkId());
                            if ($availability) {
                                $booking->setStartDate($startDate);
                                $booking->setEndDate($endDate);
                                if (isset($data['new_registration'])) {
                                    $booking->setCarRegistration($data['new_registration']);
                                }
                                $this->em->persist($booking);
                                $this->em->flush();
                                return $booking;
                            }
                        }
                    }
                }
            }
            return null;
        } catch (\Exception $exception) {
            return null;
        }
    }
}
