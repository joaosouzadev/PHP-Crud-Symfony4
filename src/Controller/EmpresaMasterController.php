<?php 

namespace App\Controller;

use App\Entity\Empresa;
use App\Form\EmpresaType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EmpresaMasterController extends Controller {

	private $userRepository;

	private $entityManager;

	public function __construct(
		UserRepository $userRepository,
		EntityManagerInterface $entityManager
	){
		$this->userRepository = $userRepository;
		$this->entityManager = $entityManager;
	}

	public function listaEmpresas(TokenStorageInterface $tokenStorage) {
		$user = $tokenStorage->getToken()->getUser();

		$empresas = $user->getEmpresas();

		return $this->render(
			'produto/dropdown.html.twig', ['listaEmpresas' => $empresas]
		);
	}
}