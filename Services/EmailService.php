<?php

namespace Redacted\PasswordlessBundle\Services;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\TwigBundle\TwigEngine;

class EmailService
{
    /**
     * @var Registry
     */
    private $doctrine;
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var TwigEngine
     */
    private $templating;

    public function __construct(Registry $doctrine, \Swift_Mailer $mailer, TwigEngine $templating)
    {
        $this->doctrine = $doctrine;
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function sendRegistration($email, $token, $uid)
    {
        $message = \Swift_Message::newInstance()
            ->setContentType('text/html')
            ->setFrom('noreply@mysite.com')
            ->setSubject('Configured Verify Email Subject line') // @todo add configuration
            ->setTo($email)
            ->setBody($this->templating->render('RedactedPasswordlessBundle:Email:register.html.twig', ["token"=>$token, "uid"=>$uid])) // @todo enable overridable template
        ;

        $this->mailer->send($message);
    }

    public function sendLogin($email, $token, $uid)
    {
        $message = \Swift_Message::newInstance()
            ->setContentType('text/html')
            ->setFrom('noreply@mysite.com')
            ->setSubject('Configured Login Email Subject line') // @todo add configuration
            ->setTo($email)
            ->setBody($this->templating->render('RedactedPasswordlessBundle:Email:login.html.twig', ["token"=>$token, "uid"=>$uid])) // @todo enable overridable template
        ;

        $this->mailer->send($message);
    }
}