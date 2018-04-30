<?php
/**
 * Created by PhpStorm.
 * User: macej
 * Date: 16/03/2018
 * Time: 22:09
 */

namespace App\Services;

use App\Entity\NewsletterReceiver;
use App\Repository\NewsletterReceiverRepository;
use Doctrine\ORM\EntityManagerInterface;
use DrewM\MailChimp\MailChimp;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class EhsNewsletterService.
 *
 * @package App\Services
 */
class EhsNewsletterService
{

    /**
     * @var \DrewM\MailChimp\MailChimp
     */
    private $mailChimp;

    /**
     * @var mixed
     */
    private $listId;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $em;

    /**
     * EhsNewsletterService constructor.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     *
     * @throws \Exception
     */
    public function __construct(
      ContainerInterface $container,
      EntityManagerInterface $entityManager
    ) {
        $this->em = $entityManager;
        $api_key = $container->getParameter('mailchimp_api');
        $this->listId = $container->getParameter('mailchimp_list_id');
        $this->mailChimp = new MailChimp($api_key);
    }

    /**
     * Add new newsletter receiver.
     *
     * @param $email
     * @param bool $verif
     */
    public function addNewReceiver($email, $verif = false)
    {
        if ($verif) {
            $checkMail = $this->em->getRepository(NewsletterReceiver::class)
              ->findOneBy(['email' => $email]);
            if (!$checkMail) {
                $newsletterReceiver = new NewsletterReceiver();
                $newsletterReceiver->setEmail($email);
                $this->em->persist($newsletterReceiver);
                $this->em->flush();
            }
        }
        $this->mailChimp->post(
          "lists/$this->listId/members",
          [
            'email_address' => $email,
            'status' => 'subscribed',
          ]
        );
    }

    /**
     * @param $email
     */
    public function removeReceiver($email)
    {
        $checkMail = $this->em->getRepository(NewsletterReceiver::class)
          ->findOneBy(['email' => $email]);
        if ($checkMail) {
            $this->em->remove($checkMail);
            $this->em->flush();
        }
        $subscriber_hash = $this->mailChimp->subscriberHash($email);

        $this->mailChimp->delete(
          "lists/$this->listId/members/$subscriber_hash"
        );
    }
}