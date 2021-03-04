<?php

namespace App\Entity;

use App\Repository\MensajeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MensajeRepository::class)
 */
class Mensaje
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $mensaje;

    /**
     * @ORM\Column(type="boolean")
     */
    private $leido;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_remitente;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_destinatario;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMensaje(): ?string
    {
        return $this->mensaje;
    }

    public function setMensaje(string $mensaje): self
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    public function getLeido(): ?bool
    {
        return $this->leido;
    }

    public function setLeido(bool $leido): self
    {
        $this->leido = $leido;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getIdRemitente(): ?int
    {
        return $this->id_remitente;
    }

    public function setIdRemitente(int $id_remitente): self
    {
        $this->id_remitente = $id_remitente;

        return $this;
    }

    public function getIdDestinatario(): ?int
    {
        return $this->id_destinatario;
    }

    public function setIdDestinatario(int $id_destinatario): self
    {
        $this->id_destinatario = $id_destinatario;

        return $this;
    }
}
