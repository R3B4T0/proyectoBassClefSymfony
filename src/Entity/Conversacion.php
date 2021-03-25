<?php

namespace App\Entity;

use App\Repository\ConversacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConversacionRepository::class)
 */
class Conversacion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Mensaje::class, mappedBy="conversacion", orphanRemoval=true)
     */
    private $ultimo_mensaje_id;

    /**
     * @ORM\ManyToOne(targetEntity=Participante::class, inversedBy="conversacion")
     * @ORM\JoinColumn(nullable=false)
     */
    private $participante;

    /**
     * @ORM\OneToMany(targetEntity=Mensaje::class, mappedBy="conversacion", orphanRemoval=true)
     */
    private $mensajes;

    public function __construct()
    {
        $this->ultimo_mensaje_id = new ArrayCollection();
        $this->mensajes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Mensaje[]
     */
    public function getUltimoMensajeId(): Collection
    {
        return $this->ultimo_mensaje_id;
    }

    public function addUltimoMensajeId(Mensaje $ultimoMensajeId): self
    {
        if (!$this->ultimo_mensaje_id->contains($ultimoMensajeId)) {
            $this->ultimo_mensaje_id[] = $ultimoMensajeId;
            $ultimoMensajeId->setConversacion($this);
        }

        return $this;
    }

    public function removeUltimoMensajeId(Mensaje $ultimoMensajeId): self
    {
        if ($this->ultimo_mensaje_id->removeElement($ultimoMensajeId)) {
            // set the owning side to null (unless already changed)
            if ($ultimoMensajeId->getConversacion() === $this) {
                $ultimoMensajeId->setConversacion(null);
            }
        }

        return $this;
    }

    public function getParticipante(): ?Participante
    {
        return $this->participante;
    }

    public function setParticipante(?Participante $participante): self
    {
        $this->participante = $participante;

        return $this;
    }

    /**
     * @return Collection|Mensaje[]
     */
    public function getMensajes(): Collection
    {
        return $this->mensajes;
    }

    public function addMensaje(Mensaje $mensaje): self
    {
        if (!$this->mensajes->contains($mensaje)) {
            $this->mensajes[] = $mensaje;
            $mensaje->setConversacion($this);
        }

        return $this;
    }

    public function removeMensaje(Mensaje $mensaje): self
    {
        if ($this->mensajes->removeElement($mensaje)) {
            // set the owning side to null (unless already changed)
            if ($mensaje->getConversacion() === $this) {
                $mensaje->setConversacion(null);
            }
        }

        return $this;
    }
}
