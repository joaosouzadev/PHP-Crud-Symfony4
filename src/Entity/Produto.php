<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProdutoRepository")
 */
class Produto
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
     * @ORM\Column(type="string")
     */
    private $codigo;

    /**
     * @ORM\Column(type="float")
     */
    private $preco;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Empresa", inversedBy="produtos")
     * @ORM\JoinColumn()
     */
    private $empresa;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PedidoItem", mappedBy="produto")
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
            $pedidoIten->setProduto($this);
        }

        return $this;
    }

    public function removePedidoIten(PedidoItem $pedidoIten): self
    {
        if ($this->pedidoItens->contains($pedidoIten)) {
            $this->pedidoItens->removeElement($pedidoIten);
            // set the owning side to null (unless already changed)
            if ($pedidoIten->getProduto() === $this) {
                $pedidoIten->setProduto(null);
            }
        }

        return $this;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

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
