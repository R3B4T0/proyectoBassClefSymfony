<?php

namespace App\Controller;

use App\Entity\Conversacion;
use App\Entity\Mensaje;
use App\Repository\MensajeRepository;
use App\Repository\UsuarioRepository;
use App\Repository\ParticipanteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/mensajes", name="mensajes")
 */
class MensajeController extends AbstractController
{
    const ATTRIBUTES_TO_SERIALIZE = ['id', 'contenido', 'creadoEl', 'mio'];

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var MensajeRepository
     */
    private $mensajeRepository;

    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository;

    /**
     * @var ParticipanteRepository
     */
    private $participanteRepository;

    /**
     * @var PublisherInterface
     */
    private $publisher;

    public function __construct(EntityManagerInterface $entityManager, 
                                MensajeRepository $mensajeRepository, 
                                UsuarioRepository $usuarioRepository,
                                ParticipanteRepository $participanteRepository, 
                                PublisherInterface $publisher) 
    {
        $this->entityManager = $entityManager;
        $this->mensajeRepository = $mensajeRepository;
        $this->usuarioRepository = $usuarioRepository;
        $this->participanteRepository = $participanteRepository;
        $this->publisher = $publisher;
    }

    /**
     * @Route("/{id}", name="getMensajes", methods={"GET"}, requirements={"id":"\d+"})
     * @param Request $request
     * @param Conversacion $conversacion
     * @return Response
     */
    public function index(Request $request, Conversacion $conversacion)
    {
        //can i view the conversation
        $this->denyAccessUnlessGranted('view', $conversacion);

        $mensajes = $this->mensajeRepository->findMensajeByConversacionId(
            $conversacion->getId()
        );

        /**
         * @var Mensaje $mensaje
         */
        array_map(function($mensaje){
            $mensaje->setMio(
                $mensaje->getUsuario()->getId() === $this->getUser()->getId()
                    ? true : false
            );
        }, $mensajes);

        return $this->json($mensajes, Response::HTTP_OK, [], [
            'attributes' => self::ATTRIBUTES_TO_SERIALIZE
        ]);
    }

    /**
     * @Route("/{id}", name="newMensaje", methods={"POST"})
     * @param Request $request
     * @param Conversacion $conversacion
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @throws \Exception
     */
    public function newMensaje(Request $request, Conversacion $conversacion, SerializerInterface $serializer)
    {
        $usuario = $this->getUser();

        $recipiente = $this->participanteRepository->findParticipanteByConversacionIdAndUsuarioId(
            $conversacion->getId(),
            $usuario->getId()
        );

        $contenido = $request->get('content', null);
        $mensaje = new Mensaje();
        $mensaje->setContenido($contenido);
        $mensaje->setUsuario($usuario);

        $conversacion->addMensaje($mensaje);
        $conversacion->setUltimoMensaje($mensaje);

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
        $mensaje->setMio(false);
        $mensajeSerialized = $serializer->serialize($mensaje, 'json', [
            'attributes' => ['id', 'contenido', 'creadoEl', 'mio', 'conversacion' => ['id']]
        ]);
        $update = new Update(
            [
                sprintf("/conversacion/%s", $conversacion->getId()),
                sprintf("/conversacion/%s", $recipiente->getUsuario()->getUsername()),
            ],
            $mensajeSerialized,
            [
                sprintf("/%s", $recipiente->getUsuario()->getUsername())
            ]
        );

        $this->publisher->__invoke($update);

        $mensaje->setMio(true);
        return $this->json($mensaje, Response::HTTP_CREATED, [], [
            'attributes' => self::ATTRIBUTES_TO_SERIALIZE
        ]);
    }
}
