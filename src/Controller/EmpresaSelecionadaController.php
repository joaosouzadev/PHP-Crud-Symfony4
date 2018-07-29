<?php 
namespace App\Controller;

use App\Entity\Empresa;
use App\Form\EmpresaType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EmpresaSelecionadaController extends Controller {

	private $userRepository;
	private $entityManager;

	public function __construct(
		UserRepository $userRepository,
		EntityManagerInterface $entityManager
	){
		$this->userRepository = $userRepository;
		$this->entityManager = $entityManager;
	}

	public function EmpresaSelecionada() {

		$session = $this->get('session');

    	$repository = $this->getDoctrine()->getRepository(Empresa::class);

    	$empresa = $session->get('empresa');

		if ($empresa === null) {
			$empresa = '';
		} else {
			$empresa = $repository->find($session->get('empresa'));
		}

		return $this->render(
			'empresa/selecionada.html.twig', ['empresaSelecionada' => $empresa]
		);
	}
}