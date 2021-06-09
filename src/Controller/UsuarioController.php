<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Entity\Video;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UsuarioController extends AbstractController
{
    /**
     * @Route("/registrar", name="registrar")
     */
    public function registrar(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $roles = $this->getParameter('security.role_hierarchy.roles');
        $usuario = new Usuario();
        $form = $this->createFormBuilder($usuario)
                ->add('email', TextType::class)
                ->add('password', PasswordType::class, ['attr' => ['minLength' => 4]])
                ->add('nombre', TextType::class)
                ->add('apellidos', TextType::class)
                ->add('telefono', TextType::class)
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
                ->add('roles', ChoiceType::class, array(
                    'attr' => array('class' => 'form-control',
                    'style' => 'margin:10px 10px;'),
                    'choices' => 
                    array
                    (
                        'ROLE_MUSICO' => array
                        (
                            'Músico' => 'ROLE_MUSICO',
                        ),
                        'ROLE_BANDA' => array
                        (
                            'Banda' => 'ROLE_BANDA',
                        ),
                    ),
                    'multiple' => true,
                    'expanded' => true,
                    'required' => true,
                ))
                ->add('registrar', SubmitType::class,
                array(
                    'attr' => array('class' => 'btn btn-primary btn-block', 'label' => 'Registrar')
                ))
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $usuario = $form->getData();
            $foto = $form->get('foto')->getData();
            if ($foto == null || $foto == '') {
                $usuario->setFoto('imagenes/user.png');
            }
            if ($foto) {
                $nuevo_nombre = uniqid() . '.' . $foto->guessExtension();
                try {
                    $foto->move('imagenes/', $nuevo_nombre);
                    $usuario->setFoto($nuevo_nombre);
                } catch (FileException $e) {
                    
                }
            }

            //Codificamos el password
            $usuario->setPassword($encoder->encodePassword($usuario, $usuario->getPassword()));

            //Guardamos el nuevo usuario en la base de datos
            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            $em->flush();
            $this->addFlash(
                'notice',
                'Usuario registrado correctamente!'
            );

            return $this->redirectToRoute('inicio');
        }

        return $this->render('usuario/registrar.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="inicio")
     */
    public function index(): Response
    {
        $musicos = $this->getDoctrine()->getRepository(Usuario::class)->getMusicos();
        return $this->render('usuario/index.html.twig',
                        ['musicos' => $musicos]);
    }

    /**
     * @Route("/bandas", name="bandas")
     */
    public function bandas(): Response
    {
        $bandas = $this->getDoctrine()->getRepository(Usuario::class)->getBandas();
        return $this->render('usuario/bandas.html.twig',
                        ['bandas' => $bandas]);
    }

    /**
     * @Route("/ver_perfil/{id}", name="ver_perfil", requirements={"id"="\d+"})
     * @param int $id
     */
    public function ver_perfil(Usuario $perfil): Response
    {
        $videos = $this->getDoctrine()->getRepository(Video::class)->getVideos($perfil->getId());
        return $this->render('usuario/ver_usuario.html.twig',
                        ['perfil' => $perfil, 'videos' => $videos]);
    }

    /**
     * @Route("/borrar_perfil/{id}", name="borrar_perfil", requirements={"id"="\d+"})
     * @return Response
     * @param int $id
     * @IsGranted("ROLE_USER")
     */
    public function borrar_perfil(Usuario $usuario): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($usuario);
        $em->flush();
        $this->addFlash(
            'error',
            'Perfil borrado correctamente.'
        );
        return $this->redirectToRoute('inicio');
    }

    /**
     * @Route("/editar_perfil/{id}", name="editar_perfil")
     * @IsGranted("ROLE_USER")
     * Method(("GET", "POST"))
     */
    public function editar_perfil(Request $request, int $id): Response
    {
        $perfil = new Usuario();
        $perfil = $this->getDoctrine()->getRepository(Usuario::class)->find($id);
        
        $form = $this->createFormBuilder($perfil)
        ->add('email', TextType::class)
        ->add('nombre', TextType::class)
        ->add('apellidos', TextType::class)
        ->add('telefono', TextType::class)
        /*->add('foto', FileType::class, [
            'label' => 'Selecciona una foto',
            'required' => false,
            'data_class' => null,
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
        ])*/
        ->add('datosInteres', TextareaType::class)
        ->add('Guardar', SubmitType::class,
        array(
            'attr' => array('class' => 'btn btn-primary btn-block', 'label' => 'Editar Perfil')
        ))
        ->getForm();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $perfil = $form->getData();
            /*$foto = $form->get('foto')->getData();
            if ($foto) {
                $nuevo_nombre = uniqid() . '.' . $foto->guessExtension();
                try {
                    $foto->move('imagenes/', $nuevo_nombre);
                    $perfil->setFoto($nuevo_nombre);
                } catch (FileException $e) {
                    
                }
            }*/

            //Guardamos el perfil editado en la base de datos
            $em = $this->getDoctrine()->getManager();
            $em->persist($perfil);
            $em->flush();
            $this->addFlash(
                'notice',
                'Usuario editado correctamente!'
            );

            return $this->redirectToRoute('ver_perfil', ['id' => $perfil->getId()]);
        }

        return $this->render('usuario/editar_perfil.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/sobre-mi", name="sobre-mi")
     */
    public function sobreMi() {
        return $this->render('usuario/sobre_mi.html.twig');
    }
}
