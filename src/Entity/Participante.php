<?php

namespace App\Entity;

use App\Repository\ParticipanteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\OneToMany(targetEntity=Conversacion::class, mappedBy="participante", orphanRemoval=true)
     */
    private $conversacion;

    /**
     * @ORM\OneToOne(targetEntity=Usuario::class, inversedBy="participante", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->conversacion = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Conversacion[]
     */
    public function getConversacion(): Collection
    {
        return $this->conversacion;
    }

    public function addConversacion(Conversacion $conversacion): self
    {
        if (!$this->conversacion->contains($conversacion)) {
            $this->conversacion[] = $conversacion;
            $conversacion->setParticipante($this);
        }

        return $this;
    }

    public function removeConversacion(Conversacion $conversacion): self
    {
        if ($this->conversacion->removeElement($conversacion)) {
            // set the owning side to null (unless already changed)
            if ($conversacion->getParticipante() === $this) {
                $conversacion->setParticipante(null);
            }
        }

        return $this;
    }

    public function getUser(): ?Usuario
    {
        return $this->user;
    }

    public function setUser(Usuario $user): self
    {
        $this->user = $user;

        return $this;
    }
}
