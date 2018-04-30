<?php
/**
 * Created by PhpStorm.
 * User: macej
 * Date: 03/01/2018
 * Time: 21:33
 */

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Event;
use App\Entity\EventInscription;
use App\Entity\Program;
use App\Services\EhsSendMailService;
use App\Repository\ContactRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;

class AdminController extends BaseAdminController
{

    /**
     * @var \App\Services\EhsSendMailService
     */
    protected $send_mail_service;

    /**
     * AdminController constructor.
     *
     * @param \App\Services\EhsSendMailService $sendMailService
     */
    public function __construct(EhsSendMailService $sendMailService)
    {
        $this->send_mail_service = $sendMailService;
    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function answerAction(ContactRepository $contactRepository)
    {
        $contact = $contactRepository->find($this->request->get('id'));
        return $this->render('easy_admin/contact/contactAnswer.html.twig', array('contact' => $contact));
    }

    /**
     * @param \App\Entity\Event $event
     */
    public function prePersistEventEntity(Event $event)
    {
       $program = new Program();
       $event->setProgram($program);
       parent::prePersistEntity($event);
    }

    /**
     * @param object $entity
     */
    public function preUpdateEntity($entity)
    {
        if ($entity instanceof Article) {
            $entity->setCreateDate(new \DateTime());
            $content = $entity->getContent();
            $content = preg_replace('/\.\.\//', '/', $content);
            $entity->setContent($content);
        }
        if (method_exists($entity, 'setUser'))  $entity->setUser($this->getUser());
        if (method_exists($entity, 'setModificationDate')) $entity->setModificationDate(new \DateTime());

        parent::prePersistEntity($entity);
    }

    /**
     * @param object $entity
     */
    public function prePersistEntity($entity)
    {
        if ($entity instanceof Article) {
            $content = $entity->getContent();
            $content = preg_replace('/\.\.\//', '/', $content);
            $entity->setContent($content);
        }
        if (method_exists($entity, 'setUser')) $entity->setUser($this->getUser());
        parent::prePersistEntity($entity);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function listRegisteredAction()
    {
        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'entity' => 'EventInscription',
            'eventId'     => $this->request->query->get('id'),
        ));
    }

    public function listEventInscriptionAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_LIST);

        $dql_filter = $this->entity['list']['dql_filter'];
        $dql_filter = str_replace('eventId', $this->request->query->get('eventId'), $dql_filter );
        $fields = $this->entity['list']['fields'];
        $paginator = $this->findAll($this->entity['class'], $this->request->query->get('page', 1), $this->config['list']['max_results'], $this->request->query->get('sortField'), $this->request->query->get('sortDirection'), $dql_filter);

        $this->dispatch(EasyAdminEvents::POST_LIST, array('paginator' => $paginator));

        return $this->render($this->entity['templates']['list'], array(
            'paginator' => $paginator,
            'fields' => $fields,
            'delete_form_template' => $this->createDeleteForm($this->entity['name'], '__id__')->createView(),
        ));
    }

    /**
     * @param \App\Entity\EventInscription $entity
     *
     * {@inheritdoc}
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function preUpdateEventInscriptionEntity(EventInscription $entity)
    {
        $validated = $this->request->query->get('property');
        $newValue = $this->request->query->get('newValue');
        if ( (isset($validated) && $validated === 'validated' ) && (isset($newValue) && $newValue === 'true') ){
            $send_from = [$this->getParameter('mailer_user') => $this->getParameter('site')];
            $this->send_mail_service->sendMessage('eventinscription/validatedMail.html.twig',
              ['eventinscription' => $entity], $send_from, $entity->getEmail());
        }
        parent::preUpdateEntity($entity);
    }

}