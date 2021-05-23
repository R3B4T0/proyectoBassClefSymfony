<?php

namespace App\Entity;

use App\Repository\ConversacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * @ORM\Entity(repositoryClass=ConversacionRepository::class)
 * @ORM\Table(indexes={@Index(name="ultimo_mensaje_id_index", columns={"ultimo_mensaje_id"})})
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
     * @ORM\OneToMany(targetEntity=Participante::class, mappedBy="conversacion")
     */
    private $participantes;

    /**
     * @ORM\OneToOne(targetEntity="Mensaje")
     * @ORM\JoinColumn(name="ultimo_mensaje_id", referencedColumnName="id")
     */
    private $ultimoMensaje;

    /**
     * @ORM\OneToMany(targetEntity=Mensaje::class, mappedBy="conversacion")
     */
    private $mensajes;

    public function __construct()
    {
        $this->participantes = new ArrayCollection();
        $this->mensajes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Participante[]
     */
    public function getParticipantes(): Collection
    {
        return $this->participantes;
    }

    public function addParticipante(Participante $participante): self
    {
        if (!$this->participantes->contains($participante)) {
            $this->participantes[] = $participante;
            $participante->setConversacion($this);
        }

        return $this;
    }

    public function removeParticipante(Participante $participante): self
    {
        if ($this->participantes->contains($participante)) {
            $this->participantes->removeElement($participante);
            // set the owning side to null (unless already changed)
            if ($participante->getConversacion() === $this) {
                $participante->setConversacion(null);
            }
        }

        return $this;
    }

    public function getUltimoMensaje(): ?Mensaje
    {
        return $this->ultimoMensaje;
    }

    public function setUltimoMensaje(?Mensaje $ultimoMensaje): self
    {
        $this->ultimoMensaje = $ultimoMensaje;

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
        if ($this->mensajes->contains($mensaje)) {
            $this->mensajes->removeElement($mensaje);
            // set the owning side to null (unless already changed)
            if ($mensaje->getConversacion() === $this) {
                $mensaje->setConversacion(null);
            }
        }

        return $this;
    }
}
