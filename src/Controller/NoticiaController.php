<?php

namespace App\Controller;

use App\Entity\Noticia;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class NoticiaController extends AbstractController
{
    /**
     * @Route("/noticias", name="noticias")
     */
    public function index(): Response
    {
        $noticias = $this->getDoctrine()->getRepository(Noticia::class)->getNoticias();
        return $this->render('noticia/index.html.twig',
                        ['noticias' => $noticias]);
    }

    /**
     * @Route("/nueva_noticia/{id}", name="nueva_noticia")
     * @IsGranted("ROLE_BANDA")
     */
    public function nueva_noticia(Request $request, Usuario $usuario): Response
    {
        $noticia = new Noticia();
        $form = $this->createFormBuilder($noticia)
            ->add('titulo', TextType::class)
            ->add('contenido', TextareaType::class)
            ->add('foto', FileType::class, [
                'label' => 'Selecciona una foto',
                'constraints' => [
                    new File ([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif'
                        ],
                        'mimeTypesMessage' => 'Formato de archivo no válido.',
                    ])
                ]
            ])
            ->add('Insertar', SubmitType::class,
            array(
                'attr' => array('class' => 'btn btn-primary btn-block', 'label' => 'Insertar Noticia')
            ))
            ->getForm()
        ;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $noticia = $form->getData();
            $foto = $form->get('foto')->getData();
            if ($foto) {
                $nuevo_nombre = uniqid() . '.' . $foto->guessExtension();
                try {
                    $foto->move('imagenes/noticias', $nuevo_nombre);
                    $noticia->setFoto($nuevo_nombre);
                } catch (FileException $e) {
                    
                }
            }

            //Guardamos la nueva noticia en la base de datos
            $em = $this->getDoctrine()->getManager();
            $noticia->setUsuario($usuario);
            $em->persist($noticia);
            $em->flush();
            $this->addFlash(
                'notice',
                'Noticia añadida correctamente!'
            );

            return $this->redirectToRoute('noticias');
        }

        return $this->render('noticia/insertar_noticia.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
