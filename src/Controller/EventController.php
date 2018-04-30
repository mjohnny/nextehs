<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventInscription;
use App\Form\EventInscriptionType;
use App\Repository\EventRepository;
use App\Services\EhsSendMailService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class EventController.
 *
 * @Route("event")
 * @package App\Controller
 */
class EventController extends Controller
{

    /**
     * Lists all event entities.
     *
     * @Route("/", name="event_index")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findBy(
          [],
          ['startDate' => 'DESC']
        );

        return $this->render(
          'event/index.html.twig',
          [
            'events' => $events,
          ]
        );
    }

    /**
     * Finds and displays a event entity.
     *
     * @Route("/{id}", name="event_show")
     * @Method("GET")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Event $event
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request, Event $event): Response
    {
        $eventInscription = new EventInscription;
        $form = $this->createForm(
          EventInscriptionType::class,
          $eventInscription,
          [
            'action' => $this->generateUrl(
              'event_eventinscription_new',
              ['id' => $event->getId()]
            ),
            'method' => 'POST',
          ]
        );

        $url = $request->headers->get('referer');
        if (!$url) {
            $url = $this->generateUrl('homepage');
        }

        return $this->render(
          'event/show.html.twig',
          [
            'bachUrl' => $url,
            'event' => $event,
            'form' => $form->createView(),
          ]
        );
    }

    /**
     * Finds and displays a program entity.
     *
     * @Route("/{id}/program", name="program_show")
     * @Method("GET")
     *
     * @param \App\Entity\Event $event
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function program(Event $event): Response
    {
        return $this->render(
          'program/show.html.twig',
          [
            'event' => $event,
          ]
        );
    }

    /**
     * Creates a new eventInscription entity.
     *
     * @Route("{id}/eventinscription", name="event_eventinscription_new")
     * @Method({"GET", "POST"})
     *
     *{@inheritdoc}
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Services\EhsSendMailService $sendMailService
     * @param \App\Entity\Event $event
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function newEventInscription(
      Request $request,
      EhsSendMailService $sendMailService,
      Event $event
    ) {
        $eventInscription = new Eventinscription();
        $form = $this->createForm(
          EventInscriptionType::class,
          $eventInscription
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $eventInscription->setEvent($event);
            $em->persist($eventInscription);
            $em->flush();

            $context = [
              'event' => $event,
              'eventinscription' => $eventInscription,
            ];
            $sendFrom = [$this->getParameter('mailer_user') => $this->getParameter('site')];
            $sendMailService->sendMessage(
              'eventinscription/registrationMail.html.twig', $context, $sendFrom, $eventInscription->getEmail());

            $this->addFlash(
              'success',
              $this->get('translator')->trans('event.inscription ok')
            );

            return $this->redirectToRoute(
              'event_show',
              ['id' => $event->getId()]
            );
        }

        return $this->render(
          'eventinscription/new.html.twig',
          [
            'eventInscription' => $eventInscription,
            'event' => $event,
            'form' => $form->createView(),
          ]
        );
    }
}
