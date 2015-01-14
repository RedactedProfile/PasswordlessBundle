<?php

namespace Redacted\PasswordlessBundle\Controller;

use Doctrine\ORM\NoResultException;
use Redacted\PasswordlessBundle\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AuthenticateController extends Controller
{
    public function AuthAction(Request $request)
    {
        if(!$request->request->get('email'))
            throw new \Exception('Email missing from post data');

        $cacheProvider = $this->get('redacted_passwordless_cache');
        $tokenProvider = $this->get('redacted_passwordless_token');
        $emailProvider = $this->get('redacted_passwordless_email');
        $now = new \DateTime();

        $data = $request->request->all();
        $email = $data['email'];
        $uid = uniqid();

        $doctrine = $this->getDoctrine();
        $accountRepository = $doctrine->getRepository('RedactedPasswordlessBundle:Account');
        $newAccount = false;

        try {
            $account = $accountRepository->findOneBy(array('email'=>$email));
        } catch(NoResultException $e) {
            $account = new Account();
            $account
                ->setEmail($email)
                ->setUid($uid)
                ->setDateAdded($now)
                ->setDateLastLogin($now)
                ->setDateUpdated($now)
                ->setActive(false);

            $em = $doctrine->getManager();
            $em->persist($account);

            $newAccount = true;
        }

        if(!$account) {
            throw new \Exception('Account could not be retrieved');
        }

        $token = $tokenProvider->generateToken('ThisIsTheConfiguredSecret'); // @todo configurable secret

        $cacheProvider->setToken($email, $token);

        if($newAccount)
            $emailProvider->sendRegistration($email, $token, $uid);
        else
            $emailProvider->sendLogin($email, $token, $uid);

        // @todo set flash data?

        return $this->redirect('/'); // @todo configure return path
    }


    public function VerifyAction($token, $uid)
    {

    }
}