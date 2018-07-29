<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmpresaRepository")
 */
class Empresa
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=18, unique=true)
     */
    private $cnpj;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $razaoSocial;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nomeFantasia;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $situacaoTributaria;

    /**
     * @ORM\Column(type="string", length=9)
     */
    private $cep;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $rua;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $bairro;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $cidade;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $uf;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $pais;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="empresas")
     * @ORM\JoinColumn()
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Produto", mappedBy="empresa")
     */
    private $produtos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vendedor", mappedBy="empresa")
     */
    private $vendedores;

    public function __construct() {
        $this->produtos = new ArrayCollection();
        $this->vendedores = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCnpj(): ?string
    {
        return $this->cnpj;
    }

    public function setCnpj(string $cnpj): self
    {
        $this->cnpj = $cnpj;

        return $this;
    }

    public function getRazaoSocial(): ?string
    {
        return $this->razaoSocial;
    }

    public function setRazaoSocial(string $razaoSocial): self
    {
        $this->razaoSocial = $razaoSocial;

        return $this;
    }

    public function getNomeFantasia(): ?string
    {
        return $this->nomeFantasia;
    }

    public function setNomeFantasia(string $nomeFantasia): self
    {
        $this->nomeFantasia = $nomeFantasia;

        return $this;
    }

    public function getSituacaoTributaria(): ?string
    {
        return $this->situacaoTributaria;
    }

    public function setSituacaoTributaria(string $situacaoTributaria): self
    {
        $this->situacaoTributaria = $situacaoTributaria;

        return $this;
    }

    public function getCep(): ?string
    {
        return $this->cep;
    }

    public function setCep(string $cep): self
    {
        $this->cep = $cep;

        return $this;
    }

    public function getRua(): ?string
    {
        return $this->rua;
    }

    public function setRua(string $rua): self
    {
        $this->rua = $rua;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getBairro(): ?string
    {
        return $this->bairro;
    }

    public function setBairro(string $bairro): self
    {
        $this->bairro = $bairro;

        return $this;
    }

    public function getCidade(): ?string
    {
        return $this->cidade;
    }

    public function setCidade(string $cidade): self
    {
        $this->cidade = $cidade;

        return $this;
    }

    public function getUf(): ?string
    {
        return $this->uf;
    }

    public function setUf(string $uf): self
    {
        $this->uf = $uf;

        return $this;
    }

    public function getPais(): ?string
    {
        return $this->pais;
    }

    public function setPais(string $pais): self
    {
        $this->pais = $pais;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Produto[]
     */
    public function getProdutos(): Collection
    {
        return $this->produtos;
    }

    public function addProduto(Produto $produto): self
    {
        if (!$this->produtos->contains($produto)) {
            $this->produtos[] = $produto;
            $produto->setEmpresa($this);
        }

        return $this;
    }

    public function removeProduto(Produto $produto): self
    {
        if ($this->produtos->contains($produto)) {
            $this->produtos->removeElement($produto);
            // set the owning side to null (unless already changed)
            if ($produto->getEmpresa() === $this) {
                $produto->setEmpresa(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return (string) $this->razaoSocial;
    }

    /**
     * @return Collection|Produto[]
     */
    public function getVendedores(): Collection
    {
        return $this->vendedores;
    }

    public function addVendedor(Produto $vendedor): self
    {
        if (!$this->vendedores->contains($vendedor)) {
            $this->vendedores[] = $vendedor;
            $vendedor->setEmpresa($this);
        }

        return $this;
    }

    public function removeVendedor(Produto $vendedor): self
    {
        if ($this->vendedores->contains($vendedor)) {
            $this->vendedores->removeElement($vendedor);
            // set the owning side to null (unless already changed)
            if ($vendedor->getEmpresa() === $this) {
                $vendedor->setEmpresa(null);
            }
        }

        return $this;
    }
}
