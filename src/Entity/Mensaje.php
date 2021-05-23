<?php

namespace App\Entity;

use App\Repository\MensajeRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * @ORM\Entity(repositoryClass=MensajeRepository::class)
 * @ORM\Table(indexes={@Index(name="creado_el_index", columns={"creado_el"})})
 * @ORM\HasLifecycleCallbacks()
 */
class Mensaje
{
    use Timestamp;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $contenido;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="mensajes")
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity=Conversacion::class, inversedBy="mensajes")
     */
    private $conversacion;

    private $mio;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenido(): ?String
    {
        return $this->contenido;
    }

    public function setContenido(String $contenido): self
    {
        $this->contenido = $contenido;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function setUsuario(?Usuario $usuario): self
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

    /**
     * @return mixed
     */
    public function getMio()
    {
        return $this->mio;
    }

    /**
     * @param mixed $mio
     */
    public function setMio($mio): void
    {
        $this->mio = $mio;
    }
}
