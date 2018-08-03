<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pedido", mappedBy="vendedor")
     */
    private $pedido;

    public function __construct()
    {
        $this->pedido = new ArrayCollection();
    }

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

    /**
     * @return Collection|Pedido[]
     */
    public function getPedido(): Collection
    {
        return $this->pedido;
    }

    public function addPedido(Pedido $pedido): self
    {
        if (!$this->pedido->contains($pedido)) {
            $this->pedido[] = $pedido;
            $pedido->setVendedor($this);
        }

        return $this;
    }

    public function removePedido(Pedido $pedido): self
    {
        if ($this->pedido->contains($pedido)) {
            $this->pedido->removeElement($pedido);
            // set the owning side to null (unless already changed)
            if ($pedido->getVendedor() === $this) {
                $pedido->setVendedor(null);
            }
        }

        return $this;
    }
}
