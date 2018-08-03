<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PedidoRepository")
 */
class Pedido
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vendedor", inversedBy="pedido")
     * @ORM\JoinColumn()
     */
    private $vendedor;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PedidoItem", mappedBy="pedido", cascade={"persist"})
     */
    private $pedidoItens;

    public function __construct()
    {
        $this->pedidoItens = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getVendedor(): ?Vendedor
    {
        return $this->vendedor;
    }

    public function setVendedor(?Vendedor $vendedor): self
    {
        $this->vendedor = $vendedor;

        return $this;
    }

    /**
     * @return Collection|PedidoItem[]
     */
    public function getPedidoItens(): Collection
    {
        return $this->pedidoItens;
    }

    public function addPedidoIten(PedidoItem $pedidoIten): self
    {
        if (!$this->pedidoItens->contains($pedidoIten)) {
            $this->pedidoItens[] = $pedidoIten;
            $pedidoIten->setPedido($this);
        }

        return $this;
    }

    public function removePedidoIten(PedidoItem $pedidoIten): self
    {
        if ($this->pedidoItens->contains($pedidoIten)) {
            $this->pedidoItens->removeElement($pedidoIten);
            // set the owning side to null (unless already changed)
            if ($pedidoIten->getPedido() === $this) {
                $pedidoIten->setPedido(null);
            }
        }

        return $this;
    }
}
