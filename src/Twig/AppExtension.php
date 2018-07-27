<?php 

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AppExtension extends AbstractExtension implements GlobalsInterface {

	private $em;
	private $tokenStorage;
	private $user;

	public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface  $em) {
		$this->em = $em;
		$this->tokenStorage = $tokenStorage;
	}

	public function getGlobals() {

		return [
			// 'minhasEmpresas' => $this->em->getRepository('App:User')->findByEmpresa(),
		];
	}

	public function getName()
   {
      return "App:AppExtension";
   }
}