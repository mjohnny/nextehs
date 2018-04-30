<?php
/**
 * Created by PhpStorm.
 * User: macej
 * Date: 03/03/2018
 * Time: 11:12
 */

namespace App\Services;

/**
 * Class EhsSendMailService
 *
 * @package App\Services
 */
class EhsSendMailService {

  /** @var \Swift_Mailer $mailer */
  private $mailer;

  /** @var \Twig_Environment $twig */
  private $twig;

  /**
   * EhsSendMailService constructor.
   *
   * @param \Swift_Mailer $mailer
   * @param \Twig_Environment $twig
   */
  public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig) {
    $this->mailer = $mailer;
    $this->twig = $twig;
  }

  /**
   * @param $templateName
   * @param $context
   * @param $fromEmail
   * @param $toEmail
   *
   * @throws \Exception
   * @throws \Throwable
   * @throws \Twig_Error_Loader
   * @throws \Twig_Error_Runtime
   * @throws \Twig_Error_Syntax
   */
  public function sendMessage($templateName, $context, $fromEmail, $toEmail)
  {
    $template = $this->twig->load($templateName);
    $subject = $template->renderBlock('subject', $context);
    $textBody = $template->renderBlock('body_text', $context);

    $htmlBody = '';

    if ($template->hasBlock('body_html', $context)) {
      $htmlBody = $template->renderBlock('body_html', $context);
    }

    $message = (new \Swift_Message())
      ->setSubject($subject)
      ->setFrom($fromEmail)
      ->setTo($toEmail);

    if (!empty($htmlBody)) {
      $message->setBody($htmlBody, 'text/html')
        ->addPart($textBody, 'text/plain');
    } else {
      $message->setBody($textBody);
    }

    $this->mailer->send($message);
  }
}