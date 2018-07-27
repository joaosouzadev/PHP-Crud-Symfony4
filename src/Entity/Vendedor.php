<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VendedorRepository")
 */
class Vendedor
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

        /**
     * @ORM\Column(type="string")
     */
    private $nome;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Empresa", inversedBy="vendedores")
     * @ORM\JoinColumn()
     */
    private $empresa;

    public function getId()
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getEmpresa(): ?Empresa
    {
        return $this->empresa;
    }

    public function setEmpresa(?Empresa $empresa): self
    {
        $this->empresa = $empresa;

        return $this;
    }
}
