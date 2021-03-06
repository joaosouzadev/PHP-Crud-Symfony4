<?php

namespace App\Controller;

use App\Entity\Empresa;
use App\Form\EmpresaType;
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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;

class EmpresaController extends AbstractController {


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
	* @Route("/empresa", name="empresa_index")
	*/
	public function index(Request $request, TokenStorageInterface $tokenStorage){

		$session = $this->get('session');
    	dump($session->get('empresa'));

		$user = $tokenStorage->getToken()->getUser();

		$empresas = $user->getEmpresas();

	    return $this->render('empresa/index.html.twig',
	    	[
	    		'empresas' => $this->empresaRepository->findBy(array('user' => $user)),
	    	]
	    );
	}

	/**
	* @Route("/edit/{id}", name="editar_empresa")
	*/
	public function edit(Empresa $empresa, Request $request){

		// $this->denyUnlessGranted('edit', $microPost); outro jeito de validar autorização, caso a classe extenda Controller
		// if (!$this->authorizationChecker->isGranted('edit', $empresa)) {
		// 	throw new UnauthorizedHttpException("Acesso Negado");
		// }

		$form = $this->formFactory->create(EmpresaType::class, $empresa);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()){
			$this->entityManager->flush();

			return new RedirectResponse($this->router->generate('index'));
		}

		return new Response(
			$this->twig->render(
				'empresa/cadastro.html.twig',
				['form' => $form->createView()]
		)
		);
	}

	/**
	* @Route("/cadastrar-empresa", name="empresa_cadastro")
	* @Security("is_granted('ROLE_USER')")
	*/
	public function cadastrar(Request $request, TokenStorageInterface $tokenStorage){

		// FAZER RELACOES USUARIO-EMPRESA
		$user = $tokenStorage->getToken()->getUser();

		$empresa = new Empresa();
		$empresa->setUser($user);

		$form = $this->formFactory->create(EmpresaType::class, $empresa);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()){
			$this->entityManager->persist($empresa);
			$this->entityManager->flush();

			return new RedirectResponse($this->router->generate('empresa_index'));
		}

		return new Response(
			$this->twig->render(
				'empresa/cadastro.html.twig',
				['form' => $form->createView()]
		)
		);
	}
}