<?php

namespace App\Controller;

use App\Entity\Conversacion;
use App\Entity\Participante;
use App\Repository\ConversacionRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\WebLink\Link;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/conversaciones", name="conversaciones")
 */
class ConversacionController extends AbstractController
{
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ConversacionRepository
     */
    private $conversacionRepository;

    public function __construct(UsuarioRepository $usuarioRepository, EntityManagerInterface $entityManager, ConversacionRepository $conversacionRepository)
    {
        $this->usuarioRepository = $usuarioRepository;
        $this->entityManager = $entityManager;
        $this->conversacionRepository = $conversacionRepository;
    }

    /**
     * @Route("/", name="nuevasConversaciones", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception     
     */
    public function index(Request $request)
    {
        $otroUsuario = $request->get('otroUsuario', 0);
        $otroUsuario = $this->usuarioRepository->find($otroUsuario);

        if (is_null($otroUsuario)) {
            throw new \Exception("No se encuentra el usuario.");
        }

        //Para no crear una conversacion contigo
        if ($otroUsuario->getId() === $this->getUser()->getId()) {
            throw new \Exception("No puedes crear una conversación contigo mismo.");
        }

        //Comprobar si la conversación existe
        $conversacion = $this->conversacionRepository->findConversacionByParticipantes(
            $otroUsuario->getId(),
            $this->getUser()->getId()
        );

        if (count($conversacion)) {
            throw new \Exception("Ya existe esta conversación.");
        }

        $conversacion = new Conversacion();

        $participante =  new Participante();
        $participante->setUsuario($this->getUser());
        $participante->setConversacion($conversacion);

        $otroParticipante =  new Participante();
        $otroParticipante->setUsuario($otroUsuario);
        $otroParticipante->setConversacion($conversacion);

        $this->entityManager->getConnection()->beginTransaction();
        try{
            $this->entityManager->persist($conversacion);
            $this->entityManager->flush($participante);
            $this->entityManager->commit($otroParticipante);
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }

        return $this->json([
            'id' => $conversacion->getId()
        ], Response::HTTP_CREATED, [], []);
    }

    /**
     * @Route("/", name="getConversations", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getConvs(Request $request)
    {
        $conversaciones = $this->conversacionRepository->findConversacionesByUsuario($this->getUser()->getId());

        $hubUrl = $this->getParameter('mercure.default_hub');

        $this->addLink($request, new Link('mercure', $hubUrl));
        return $this->json($conversaciones);
    }
}
