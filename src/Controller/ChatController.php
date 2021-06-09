<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use DateInterval;
use DateTime;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Builder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Cookie;

class ChatController extends AbstractController
{
    /**
     * @Route("/chat", name="chat")
     */
    public function chat()
    {
        $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText('koshensx'));
        $username = $this->getUser()->getUsername();
        $token = $config->builder()
            ->withClaim('mercure', ['subscribe' => [sprintf("/%s", $username)]])
            ->getToken($config->signer(), $config->signingKey())
        ;
        
        $response = $this->render('conversacion/index.html.twig', [
            'controller_name' => 'UsuarioController'
        ]);

        $response->headers->setCookie(
            new Cookie(
                'mercureAuthorization',
                $token->toString(),
                (new \DateTime())
                ->add(new \DateInterval('PT2H')),
                '/.well-known/mercure',
                null,
                false,
                true,
                false,
                'strict'
            )
        );

        return $response;
    }
}
