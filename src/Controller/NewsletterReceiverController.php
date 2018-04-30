<?php

namespace App\Controller;

use App\Entity\NewsletterReceiver;
use App\Form\NewsletterReceiverType;
use App\Services\EhsNewsletterService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class NewsletterReceiverController.
 *
 * @Route("newsletterreceiver")
 * @package App\Controller
 */
class NewsletterReceiverController extends Controller
{

    /**
     * Creates a new newsletterReceiver entity.
     *
     * @Route("/new", name="newsletterreceiver_new")
     * @Method({"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Services\EhsNewsletterService $newsletterService
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function new(Request $request, EhsNewsletterService $newsletterService): RedirectResponse
    {
        $newsletterReceiver = new Newsletterreceiver();
        $form = $this->createForm(
          NewsletterReceiverType::class,
          $newsletterReceiver,
          ['action' => $this->generateUrl('newsletterreceiver_new')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $verif = $em->getRepository(NewsletterReceiver::class)
              ->findOneBy(['email' => $newsletterReceiver->getEmail()]);

            if (!$verif) {
                $em->persist($newsletterReceiver);
                $em->flush();
            }
            $newsletterService->addNewReceiver($newsletterReceiver->getEmail());
        }

        return $this->redirectToRoute('homepage');
    }

    /**
     * Stop newsletter receiver.
     *
     * @Route("/stop", name="newsletter_stop")
     * @Method({"GET","POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Services\EhsNewsletterService $newsletterService
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function stop(Request $request, EhsNewsletterService $newsletterService): Response
    {
        $newsletterReceiver = new Newsletterreceiver();
        $form = $this->createForm(
          NewsletterReceiverType::class, $newsletterReceiver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->remove($newsletterReceiver->getEmail(), $newsletterService);
        }

        return $this->render('newsletterreceiver/stop.html.twig',
        [
          'form' =>$form->createView(),
        ]
          );

    }

    /**
     * Remove neseletter receiver.
     *
     * @Route("/stop/{email}", name="newsletter_remove")
     * @Method("GET")
     *
     * @param $email
     * @param \App\Services\EhsNewsletterService $newsletterService
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function remove($email, EhsNewsletterService $newsletterService): RedirectResponse
    {
        $newsletterService->removeReceiver($email);
        return $this->redirectToRoute('homepage');
    }
}
