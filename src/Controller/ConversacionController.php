<?php

namespace App\Controller;

use App\Entity\Conversacion;
use App\Entity\Participante;
use App\Repository\ConversacionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

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
     * @Route("/", name="getConversaciones")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index(Request $request, int $id)
    {
        $otroUsuario = $request->get('otherUser', 0);
        $otroUsuario = $this->usuarioRepository->find($id);

        if (is_null($otroUsuario)) {
            throw new \Exception("No se encuentra el usuario.");
        }

        //Prohibir crear una conversación consigo mismo.
        if ($otroUsuario->getId() === $this->getUser()->getId()) {
            throw new \Exception("No puedes crear una conversación contigo mismo.");
        }

        //Comprobar si la conversación existe.
        $conversacion = $this->conversacionRepository->findConversacionByParticipantes(
            $otroUsuario->getId(),
            $this->getUser()->getId()
        );

        if (count($conversacion)) {
            throw new \Exception("La conversación ya existe.");
        }

        $conversacion = new Conversacion();

        $participante = new Participante();
        $participante->setUser($this->getUser());
        $participante->setConversacion($conversacion);

        $otroParticipante = new Participante();
        $otroParticipante->setUser($otroUsuario);
        $otroParticipante->setConversacion($conversacion);

        $this->entityManager->getConnection()->beginTransaction();
        try {
            $this->entityManager->persist($conversacion);
            $this->entityManager->persist($participante);
            $this->entityManager->persist($otroParticipante);

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
        $this->entityManager->commit();

        return $this->json();
    }
}
