<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Events;
use Symfony\Component\HttpFoundation\Response;


class CalendarController extends AbstractController
{
  
    

    #[Route('/calender', name: 'calendar_events')]
    public function events(): \Symfony\Component\HttpFoundation\Response
    {
        $events = $this->getDoctrine()->getRepository(Events::class)->findAll();
        
        // Convert events to the required format for FullCalendar
        $formattedEvents = [];
        foreach ($events as $event) {
            $formattedEvents[] = [
                'id' => $event->getId(), // Include the ID
                'title' => $event->getTitle(),
                'start' => $event->getStart()->format(\DateTimeInterface::ISO8601),
                'end' => $event->getEnd()->format(\DateTimeInterface::ISO8601),
            ];
        }
        
        return $this->render('vendeur/event.html.twig', [
            'events' => json_encode($formattedEvents), // pass events to the template
        ]);
    }
    
    

    #[Route('/calender/events', name: 'calendar_create_event', methods: ['POST'])]
    public function createEvent(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        $event = new Events();
        $event->setTitle($data['title']);
    
        // Convert string datetime to DateTime objects
        $start = new \DateTime($data['start']);
        $end = new \DateTime($data['end']);
    
        $event->setStart($start);
        $event->setEnd($end);
    
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($event);
        $entityManager->flush();
    
        return $this->json($event);
    }
    

    #[Route('/calender/events/{id}', name: 'calendar_update_event', methods: ['PUT'])]
public function updateEvent(Request $request, Events $event): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    $event->setTitle($data['title']);
    // Update start and end dates if needed
    // $event->setStart(new \DateTime($data['start']));
    // $event->setEnd(new \DateTime($data['end']));

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->flush();

    return $this->json($event);
}

#[Route('/calender/events/{id}', name: 'calendar_delete_event', methods: ['DELETE'])]
public function deleteEvent(Events $event): JsonResponse
{
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($event);
    $entityManager->flush();

    return $this->json(['message' => 'Event deleted successfully']);
}


}