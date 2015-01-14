<?php

namespace Redacted\PasswordlessBundle\Controller;

use Doctrine\ORM\NoResultException;
use Redacted\PasswordlessBundle\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

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

        $account = $accountRepository->findOneBy(array('email'=>$email));
        if(!$account) {
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
            $em->flush();

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
        $doctrine = $this->getDoctrine();
        $accountRepo = $doctrine->getRepository('RedactedPasswordlessBundle:Account');

        $account = $accountRepo->findOneBy(array('uid'=>$uid));
        if(!$account) {
            throw new \Exception('User not found');
        }

        $cacheProvider = $this->get('redacted_passwordless_cache');
        $emailProvider = $this->get('redacted_passwordless_email');

        if($cacheProvider->verifyToken($account->getEmail(), $token)) {
            // @todo Symfony Token registration




            // @todo Flash message?

            // @todo Redirect
        }
    }

    public function LoginAction($token, $uid)
    {

    }
}