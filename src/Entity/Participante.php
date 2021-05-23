<?php

namespace App\Entity;

use App\Repository\ParticipanteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParticipanteRepository::class)
 */
class Participante
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="participantes")
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity=Conversacion::class, inversedBy="participantes")
     */
    private $conversacion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getConversacion(): ?Conversacion
    {
        return $this->conversacion;
    }

    public function setConversacion(?Conversacion $conversacion): self
    {
        $this->conversacion = $conversacion;

        return $this;
    }
}
