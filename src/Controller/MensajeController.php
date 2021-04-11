<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Conversacion;
use App\Entity\Mensaje;
use App\Repository\MensajeRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mensajes", name="mensajes", methods={"GET"})
 */
class MensajeController extends AbstractController
{

    const ATTRIBUTES_TO_SERIALIZE = ['id', 'mensaje', 'fecha', 'mio'];

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var MensajeRepository
     */
    private $mensajeRepository;
    /**
     * @var UsuarioRepository;
     */
    private $usuarioRepository;

    public function __construct(EntityManagerInterface $entityManager, MensajeRepository $mensajeRepository, UsuarioRepository $usuarioRepository)
    {
        $this->entityManager = $entityManager;
        $this->mensajeRepository = $mensajeRepository;
        $this->usuarioRepository = $usuarioRepository;
    }

    /**
     * @Route("/{id}", name="getMensajes")
     * @param Request $request
     * @param Conversacion $conversacion
     * @return Response
     */
    public function index(Request $request, Conversacion $conversacion): Response
    {
        $this->denyAccessUnlessGranted('view', $conversacion);
        
        $mensajes = $this->mensajeRepository->findMensajeByConversacionId(
            $conversacion->getId()
        );

        /**
         * @var $mensaje Mensaje
         */
        array_map(function ($mensaje) {
            $mensaje->setMio(
                $mensaje->getUser()->getId() === $this->getUser()->getId()
                ? true: false
            );
        }, $mensajes);
        
        return $this->json($mensajes, Response::HTTP_OK, [], [
            'attributes' => self::ATTRIBUTES_TO_SERIALIZE
        ]);
    }

    /**
     * @Route("/{id}", name="nuevoMensaje", methods={"POST"})
     * @param Request $request
     * @param Conversacion $conversacion
     * @return JsonResponse
     * @throws \Exception
     */
    public function nuevoMensaje(Request $request, Conversacion $conversacion)
    {
        $usuario = $this->getUser();
        $contenido = $request->get('mensaje', null);

        $mensaje = new Mensaje;
        $mensaje->setConversacion($conversacion);
        $mensaje->setUsuario($this->usuarioRepository->findOneBy(['id' => 1]));
        $mensaje->setMio(true);

        $conversacion->addMensaje($mensaje);
        $conversacion->addUltimoMensajeId($mensaje);

        $this->entityManager->getConnection()->beginTransaction();
        try {
            $this->entityManager->persist($mensaje);
            $this->entityManager->persist($conversacion);
            $this->entityManager->flush();

            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
        return $this->json($mensaje, Response::HTTP_CREATED, [], [
            'attributes' =>self::ATTRIBUTES_TO_SERIALIZE
        ]);
    }
}
