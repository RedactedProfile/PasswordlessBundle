Symfony 2 Passwordless Bundle
=============================

Main Goal: To create a Token based Passwordless authentication system that can be easily inserted into any Symfony2 project.  

Development Goals:  

1. *Small as possible*. The smallest amount of code necessary to make the core functionality work
2. *Do not assume too much*. The goal is to provide the basic core systems necessary to accomplish the workflow
3. *Least Configuration*. Keeping it down to as few thigns to do as possible. This is supposed to be simple!
4. *Secure Tokens as possible while still being quick*
5. *Least Dependencies*. This bundle will make as much use of built in systems as much as possible to reduce vendor clutter and version hell issues.
6. *Overridable templates*. This bundle can include very basic Email templates, but they should be overridable with as little effort as possible.


This bundle is to provide a built in Flow that is to remain as transparent as possible, while assuming as little as possible.  This should be a very quick, lean and mean bundle.  
  
Why Passwordless?
=================

This is an attempt to erradicate Password storage to the least amount of places possible.  Tired of Password Generators? Password Vaults? passwords getting hacked? Remembering and forgetting passwords?  Or do you just want to provide something to your users that introduces the least amount of typing? 

The flow is this:

1. A form on a page includes an Email input and a Submit button
2. On submit, if the email is not found, register it and send an email to the new user asking to verify their email account.
3. Once that link in the email is clicked, the user is verified and the account is activated
4. After a while the token should expire on its own.
5. When the user logs out or session expires, and needs to login again, the user will need to input their email again. 
6. This will send the user another email with a token link, that has a very short lifespan (5 Minutes)
7. Once clicked, the token is killed and the user is reauthentcated with a new session, sent to a configured URL

  
Potential things to consider if using this system
=================================================

1. Passwordless is not an bulletproof system. Just like with classic Email/Password authentication, if your email is compromised, so is your account using this system. 

 
Project Considerations
======================

1. Use SwiftMailer for all email communications
2. Use Twig for Email templating
3. Use Doctrine for Database access to an account entity
4. If available, use Doctrine Cache to as a Token storage. This will allow Redis, APC, Memcache, etc to be usable. 
   If not available, use a separate table in the configured Database



 
Bundle Maintainers
==================
Kyle Harrison <redactedprofile@gmail.com>

