<?php

namespace App\Controller;

use App\Entity\Empresa;
use App\Entity\Vendedor;
use App\Form\VendedorType;
use App\Repository\VendedorRepository;
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


class VendedorController extends AbstractController {


	private $twig;

	private $vendedorRepository;

	private $formFactory;

	private $entityManager;

	private $router;

	private $flashBag;

	private $authorizationChecker;

	public function __construct(
		\Twig_Environment $twig, 
		VendedorRepository $vendedorRepository, 
		FormFactoryInterface $formFactory, 
		EntityManagerInterface $entityManager, 
		RouterInterface $router, 
		FlashBagInterface $flashBag,
		AuthorizationCheckerInterface $authorizationChecker
	){

		$this->twig = $twig;
		$this->vendedorRepository = $vendedorRepository;
		$this->formFactory = $formFactory;
		$this->entityManager = $entityManager;
		$this->router = $router;
		$this->flashBag = $flashBag;
		$this->authorizationChecker = $authorizationChecker;
	}

	/**
	* @Route("/vendedor", name="vendedor_index")
	*/
	public function index(TokenStorageInterface $tokenStorage){

		$session = $this->get('session');
		$user = $tokenStorage->getToken()->getUser();

		$html = $this->twig->render('vendedor/index.html.twig', [
			'vendedores' => $this->vendedorRepository->findByEmpresa($session->get('empresa')),
		]);

		return new Response($html);
	}

	/**
	* @Route("/cadastro-vendedor", name="vendedor_cadastro")
	*/
	public function cadastrar(Request $request, TokenStorageInterface $tokenStorage){

		$session = $this->get('session');
		$user = $tokenStorage->getToken()->getUser();

		$repository = $this->getDoctrine()->getRepository(Empresa::class);

		$vendedor = new Vendedor();
		$empresa = $repository->find($session->get('empresa'));

		$form = $this->formFactory->create(VendedorType::class, $vendedor);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()){
			$vendedor->setEmpresa($empresa);
			$this->entityManager->persist($vendedor);
			$this->entityManager->flush();

			return new RedirectResponse($this->router->generate('vendedor_index'));
		}

		return new Response(
			$this->twig->render(
				'vendedor/cadastro.html.twig',
				['form' => $form->createView()]
		)
		);
	}



	/**
	* @Route("/vendedor/edit/{id}", name="vendedor_editar")
	*/
	public function edit(Vendedor $vendedor, Request $request){

		// $this->denyUnlessGranted('edit', $microPost); outro jeito de validar autorização, caso a classe extenda Controller
		// if (!$this->authorizationChecker->isGranted('edit', $vendedor)) {
		// 	throw new UnauthorizedHttpException("Acesso Negado");
		// }

		$form = $this->formFactory->create(VendedorType::class, $vendedor);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()){
			$this->entityManager->flush();

			return new RedirectResponse($this->router->generate('index'));
		}

		return new Response(
			$this->twig->render(
				'vendedor/cadastro.html.twig',
				['form' => $form->createView()]
		)
		);
	}
}