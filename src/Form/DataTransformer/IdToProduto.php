<?php 

namespace App\Form\DataTransformer;

use App\Entity\Produto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToProduto implements DataTransformerInterface {

	 private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

	/**
	* Transforms an object (issue) to a string (number).
	*/
    public function transform($produto)
    {
        if (null === $produto) {
            return '';
        }

        return $produto->getId();
    }

	/**
	* Transforms a string (number) to an object (issue).
	* @throws TransformationFailedException if object (issue) is not found.
	*/
    public function reverseTransform($produtoNome)
    {
        // no issue number? It's optional, so that's ok
        if (!$produtoNome) {
            return;
        }

        $produto = $this->entityManager
            ->getRepository(Produto::class)
            // query for the issue with this id
            ->findOneBy(array('nome' => $produtoNome))
        ;

        if (null === $produto) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'Produto com nome "%s" não existe!',
                $produtoNome
            ));
        }

        return $produto;
    }
}


