<?php

namespace App\Controller;

use App\Entity\Empresa;
use App\Form\MicroPostType;
use App\Repository\EmpresaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EmpresaController{


	private $twig;

	private $empresaRepository;

	private $formFactory;

	private $entityManager;

	private $router;

	private $flashBag;

	private $authorizationChecker;

	public function __construct(
		\Twig_Environment $twig, 
		EmpresaRepository $empresaRepository, 
		FormFactoryInterface $formFactory, 
		EntityManagerInterface $entityManager, 
		RouterInterface $router, 
		FlashBagInterface $flashBag,
		AuthorizationCheckerInterface $authorizationChecker
	){

		$this->twig = $twig;
		$this->empresaRepository = $empresaRepository;
		$this->formFactory = $formFactory;
		$this->entityManager = $entityManager;
		$this->router = $router;
		$this->flashBag = $flashBag;
		$this->authorizationChecker = $authorizationChecker;
	}

	/**
	* @Route("/", name="index")
	*/
	public function index(){
		$html = $this->twig->render('empresa/index.html.twig', [
			'empresas' => $this->empresaRepository->findAll()
		]);

		return new Response($html);
	}
}