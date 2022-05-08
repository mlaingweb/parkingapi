<?php

namespace App\Controller\Api\V1;

use App\Entity\Bookings;
use App\Entity\CarParks;
use App\Service\CarParkService;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarParkController extends AbstractController {

    protected CarParkService $carParkService;
    protected EntityManagerInterface $em;

    /**
     * @param CarParkService $carParkService
     * @param EntityManagerInterface $em
     */
    public function __construct(
        CarParkService $carParkService,
        EntityManagerInterface $em
    ) {
        $this->carParkService = $carParkService;
        $this->em = $em;
    }


    /**
     * Check Availability
     * @Route(
     *     "/api/v1/availability",
     *     name="check_availability",
     *     methods={"GET"},
     * )
     */
    public function checkAvailability(Request $request): Response {
        $req = json_decode($request->getContent(), true);
        $startDate = Carbon::createFromFormat('d/m/Y', $req['from']);
        $endDate = Carbon::createFromFormat('d/m/Y', $req['to']);
        $carPark = $this->em->getRepository(CarParks::class)->find($req['carPark']);
        if ($carPark instanceof CarParks) {
            // check availability
            $availability = $this->carParkService->getAvailability($startDate, $endDate, $carPark);
            return $this->json([
                'availability' => $availability
            ]);
        }
        return $this->json([
            'message' => 'Invalid Request',
        ]);
    }

    /**
     * Get Prices
     * @Route(
     *     "/api/v1/price",
     *     name="check_price",
     *     methods={"GET"},
     * )
     */
    public function checkPrice(Request $request): Response {
        $req = json_decode($request->getContent(), true);
        $startDate = Carbon::createFromFormat('d/m/Y', $req['from']);
        $endDate = Carbon::createFromFormat('d/m/Y', $req['to']);
        $carPark = $this->em->getRepository(CarParks::class)->find($req['carPark']);
        if ($carPark instanceof CarParks) {
            // check prices
            $prices = $this->carParkService->getPrices($startDate, $endDate, $carPark);
            return $this->json([
                'prices' => $prices
            ]);
        }
        return $this->json([
            'message' => 'Invalid Request',
        ]);
    }

    /**
     * Create Booking
     * @Route(
     *     "/api/v1/booking/create",
     *     name="add_reservation",
     *     methods={"POST"},
     * )
     */
    public function addReservation(Request $request): Response {
        $req = json_decode($request->getContent(), true);
        // add booking
        $booking = $this->carParkService->addReservation($req);
        if ($booking instanceof Bookings) {
            // successfully added booking
            return $this->json([
                'message' => 'Booking confirmed.'
            ]);
        }
        // there has been a problem adding the booking
        return $this->json([
            'message' => 'Invalid Request!'
        ]);
    }

    /**
     * Amend Booking
     * @Route(
     *     "/api/v1/booking/amend",
     *     name="amend_reservation",
     *     methods={"PATCH"},
     * )
     */
    public function amendReservation(Request $request): Response {
        $req = json_decode($request->getContent(), true);
        // amend booking
        $update = $this->carParkService->amendBooking($req);
        if ($update instanceof Bookings) {
            // successfully updated
            return $this->json([
                'message' => 'Booking Updated!'
            ]);
        }
        // there has been an error updating the booking
        return $this->json([
            'message' => 'Invalid Request!'
        ]);
    }

    /**
     * @Route(
     *     "/api/v1/booking/cancel",
     *     name="cancel_reservation",
     *     methods={"DELETE"},
     * )
     */
    public function cancelReservation(Request $request): Response {
        $req = json_decode($request->getContent(), true);
        // cancel booking
        $result = $this->carParkService->cancelBooking($req);
        if ($result) {
            // successfully cancelled booking
            return $this->json([
                'message' => 'Booking cancelled!'
            ]);
        }
        // there has been a problem cancelling the booking
        return $this->json([
            'message' => 'Invalid Request!'
        ]);
    }
}
