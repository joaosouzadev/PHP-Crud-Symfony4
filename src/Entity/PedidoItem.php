<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PedidoItemRepository")
 */
class PedidoItem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantidade;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $preco;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Produto", inversedBy="pedidoItens")
     * @ORM\JoinColumn()
     */
    private $produto;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pedido", inversedBy="pedidoItens")
     * @ORM\JoinColumn()
     */
    private $pedido;

    public function getId()
    {
        return $this->id;
    }

    public function getQuantidade(): ?int
    {
        return $this->quantidade;
    }

    public function setQuantidade(int $quantidade): self
    {
        $this->quantidade = $quantidade;

        return $this;
    }

    public function getProduto(): ?Produto
    {
        return $this->produto;
    }

    public function setProduto(?Produto $produto): self
    {
        $this->produto = $produto;

        return $this;
    }

    public function getPedido(): ?Pedido
    {
        return $this->pedido;
    }

    public function setPedido(?Pedido $pedido): self
    {
        $this->pedido = $pedido;

        return $this;
    }

    public function getPreco(): ?float
    {
        return $this->preco;
    }

    public function setPreco(float $preco): self
    {
        $this->preco = $preco;

        return $this;
    }
}
