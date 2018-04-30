<?php
/**
 * Created by PhpStorm.
 * User: macej
 * Date: 25/02/2018
 * Time: 20:19
 */

namespace App\Controller;

use App\Services\EhsNewsletterService;
use App\Form\FormEhsFactory;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class RegistrationController
 *
 * @package App\Controller
 */
class RegistrationController extends Controller
{

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var FactoryInterface
     */
    private $formFactory;

    /**
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * @var \App\Services\EhsNewsletterService
     */
    private $newsletterService;

    /**
     * RegistrationController constructor.
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \FOS\UserBundle\Model\UserManagerInterface $userManager
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     * @param \App\Form\FormEhsFactory $formFactory
     * @param \App\Services\EhsNewsletterService $newsletterService
     */
    public function __construct(
      EventDispatcherInterface $eventDispatcher,
      UserManagerInterface $userManager,
      TokenStorageInterface $tokenStorage,
      FormEhsFactory $formFactory,
      EhsNewsletterService $newsletterService
    ) {
        $this->formFactory = $formFactory;
        $this->userManager = $userManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->newsletterService = $newsletterService;
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request): Response
    {
        /** @var UserInterface $user */
        $user = $this->userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch(
          FOSUserEvents::REGISTRATION_INITIALIZE,
          $event
        );

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $user->setUsername($user->getEmail());
            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $this->eventDispatcher->dispatch(
                  FOSUserEvents::REGISTRATION_SUCCESS,
                  $event
                );

                $password = new \DateTime();
                $user->setPlainPassword($password->getTimestamp());

                $this->userManager->updateUser($user);

                if ($user->getNewsletter()) {
                    $this->newsletterService->addNewReceiver(
                      $user->getEmail(),
                      true
                    );
                }

                if (null === $response = $event->getResponse()) {
                    $url = $this->generateUrl(
                      'fos_user_registration_confirmed'
                    );
                    $response = new RedirectResponse($url);
                }

                $this->eventDispatcher->dispatch(
                  FOSUserEvents::REGISTRATION_COMPLETED,
                  new FilterUserResponseEvent($user, $request, $response)
                );

                return $response;
            }

            $event = new FormEvent($form, $request);
            $this->eventDispatcher->dispatch(
              FOSUserEvents::REGISTRATION_FAILURE,
              $event
            );

            if (null !== $response = $event->getResponse()) {
                return $response;
            }
        }

        return $this->render(
          '@FOSUser/Registration/register.html.twig',
          [
            'form' => $form->createView(),
          ]
        );
    }

}