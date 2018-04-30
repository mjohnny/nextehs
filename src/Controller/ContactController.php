<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Services\EhsSendMailService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ContactController.
 *
 * @Route("contact")
 * @package App\Controller
 */
class ContactController extends Controller
{

    /** @var \App\Services\EhsSendMailService $mailerService */
    protected $mailerService;

    /**
     * ContactController constructor.
     *
     * @param \App\Services\EhsSendMailService $mailService
     */
    public function __construct(EhsSendMailService $mailService)
    {
        $this->mailerService = $mailService;
    }

    /**
     * Lists all contact entities.
     *
     * @Route("/", name="contact_index")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ContactRepository $contactRepository): Response
    {
        $contacts = $contactRepository->findAll();

        return $this->render(
          'contact/index.html.twig',
          [
            'contacts' => $contacts,
          ]
        );
    }

    /**
     * Creates a new contact entity.
     *
     * @Route("/new", name="contact_new")
     * @Method({"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function new(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(
          ContactType::class,
          $contact,
          ['action' => $this->generateUrl('contact_new')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $contact->setMessageDate(new \DateTime());
            $em->persist($contact);
            $em->flush();
            $template = 'contact/contactEmail.html.twig';
            $context = [
              'contact' => $contact,
            ];
            $toEmail = $this->getParameter('mailer_contact');
            $this->mailerService->sendMessage(
              $template,
              $context,
              $contact->getEmail(),
              $toEmail
            );

            // on tente de rediriger vers la page d'origine
            $url = $request->headers->get('referer');
            if (empty($url)) {
                $url = $this->generateUrl('homepage');
            }

            return new RedirectResponse($url);
        }

        return $this->redirectToRoute('homepage');
    }

    /**
     * Finds and displays a contact entity.
     *
     * @Route("/{id}", name="contact_show")
     * @Method("GET")
     *
     * @param \App\Entity\Contact $contact
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Contact $contact): Response
    {

        return $this->render(
          'contact/show.html.twig',
          [
            'contact' => $contact,
          ]
        );
    }

    /**
     * Answer contact.
     *
     * @Route("/answer/{id}", name="contact_answer")
     * @Method("POST")
     *
     * @IsGranted("ROLE_ADMIN")
     * @param \App\Entity\Contact $contact
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * {@inheritdoc}
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function answer(Contact $contact): Response
    {
        $context = [
          'contact' => $contact,
          'response' => $_POST['response'],
        ];
        $sendFrom = [$this->getParameter('mailer_user') => $this->getParameter('site')];
        $this->mailerService->sendMessage('contact/response.html.twig', $context,$sendFrom, $contact->getEmail() );

        return $this->redirectToRoute(
          'easyadmin',
          [
            'action' => 'list',
            'entity' => 'Contact',
          ]
        );
    }
}
