<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    #[Route('/calender', name: 'calendar_events')]
    public function events(): Response
    {
        $events = [
            [
                'title' => 'Event 1',
                'start' => '2022-01-01T09:00:00',
                'end' => '2022-01-01T10:00:00',
            ],
            [
                'title' => 'Event 2',
                'start' => '2022-01-02T10:00:00',
                'end' => '2022-01-02T11:00:00',
            ],
            // Add more events here
        ];
       
        return $this->render('vendeur/event.html.twig', [
            $this->json($events),
        ]);
    }
}