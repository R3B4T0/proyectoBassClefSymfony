<?php

namespace App\Controller;

use App\Entity\Video;
use App\Entity\Usuario;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class VideoController extends AbstractController
{
    /**
     * @Route("/insertar_video/{id}", name="insertar_video")
     * @IsGranted("ROLE_USER")
     */
    public function insertar(Request $request, Usuario $usuario): Response
    {
        $video = new Video();
        $form = $this->createFormBuilder($video)
                ->add('codigo', TextType::class, array('label' => 'Inserte el link de su vídeo de YouTube.'))
                ->add('guardar', SubmitType::class,
                    array
                    (
                        'attr' => array('class' => 'btn btn-primary', 'label' => 'Insertar video')
                    ))
                ->getForm();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $video = $form->getData();
            $url = $video->getCodigo();
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
            $youtube_id = $match[1];
            $em = $this->getDoctrine()->getManager();
            $video->setUsuario($usuario);
            $video->setCodigo($youtube_id);
            $em->persist($video);
            $em->flush();

            return $this->redirectToRoute('ver_perfil', ['id' => $usuario->getId()]);
        }
        return $this->render('video/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
