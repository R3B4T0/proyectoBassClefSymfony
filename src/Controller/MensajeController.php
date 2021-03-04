<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MensajeController extends AbstractController
{
    #[Route('/mensaje', name: 'mensaje')]
    public function index(): Response
    {
        return $this->render('mensaje/index.html.twig', [
            'controller_name' => 'MensajeController',
        ]);
    }
}
