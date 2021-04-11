<?php

namespace App\Security\Voter;

use App\Entity\Conversacion;
use App\Repository\ConversacionRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ConversacionVoter extends Voter
{
    /**
     * @var ConversacionRepository
     */
    private $conversacionRepository;
    
    public function __construct(ConversacionRepository $conversacionRepository)
    {
        $this->conversacionRepository = $conversacionRepository;
    }

    const VIEW = 'view';

    protected function supports(string $attribute, $subject)
    {
        return $attribute == self::VIEW && $subject instanceof Conversacion;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $result = $this->conversacionRepository->checkIfUsuarioesParticipante(
            $subject->getId(),
            $token->getUser()->getId()
        );

        return !!$result;
    }
}