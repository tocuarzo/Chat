<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Mensaje
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $usuario;

    /**
     * @ORM\Column(type="string", length=400)
     */
    private $mensaje;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?string
    {
        return $this->usuario;
    }

    public function setUsuario(string $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
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
    public static function toJson($mensajes){
        $arrayfinal = [];
        foreach ($mensajes as $mensaje){
            $mensajearray = [];
            $mensajearray["usuario"] = $mensaje->getUsuario();
            $mensajearray["mensaje"] = $mensaje->getMensaje();
            $arrayfinal[] = $mensajearray;
        }
        return json_encode($arrayfinal);
    }
}
