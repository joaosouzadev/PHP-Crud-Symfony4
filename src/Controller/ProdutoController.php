<?php

namespace App\Controller;

use App\Entity\Empresa;
use App\Entity\Produto;
use App\Form\ProdutoType;
use App\Repository\ProdutoRepository;
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


class ProdutoController extends AbstractController {


	private $twig;

	private $produtoRepository;

	private $formFactory;

	private $entityManager;

	private $router;

	private $flashBag;

	private $authorizationChecker;

	public function __construct(
		\Twig_Environment $twig, 
		ProdutoRepository $produtoRepository, 
		FormFactoryInterface $formFactory, 
		EntityManagerInterface $entityManager, 
		RouterInterface $router, 
		FlashBagInterface $flashBag,
		AuthorizationCheckerInterface $authorizationChecker
	){

		$this->twig = $twig;
		$this->produtoRepository = $produtoRepository;
		$this->formFactory = $formFactory;
		$this->entityManager = $entityManager;
		$this->router = $router;
		$this->flashBag = $flashBag;
		$this->authorizationChecker = $authorizationChecker;
	}

	/**
	* @Route("/produto", name="produtos")
	*/
	public function index(TokenStorageInterface $tokenStorage){

		$user = $tokenStorage->getToken()->getUser();

		$html = $this->twig->render('produto/index.html.twig', [
			'produtos' => $this->produtoRepository->findByEmpresa($user->getEmpresas()),
		]);

		return new Response($html);
	}

	/**
	* @Route("/cadastro", name="produto_cadastro")
	*/
	public function cadastrar(Request $request, TokenStorageInterface $tokenStorage){

		$user = $tokenStorage->getToken()->getUser();

		$produto = new Produto();

		$form = $this->formFactory->create(ProdutoType::class, $produto);
		$form->add('empresa', EntityType::class, [
                'class' => Empresa::class,
                'choice_label' => 'razaoSocial',
                'choice_value' => 'id',
                'label' => 'Company:',
                'choices' => $user->getEmpresas()
            ]);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()){
			$this->entityManager->persist($produto);
			$this->entityManager->flush();

			return new RedirectResponse($this->router->generate('index'));
		}

		return new Response(
			$this->twig->render(
				'produto/cadastro.html.twig',
				['form' => $form->createView()]
		)
		);
	}



	/**
	* @Route("/edit/{id}", name="produto_editar")
	*/
	public function edit(Produto $produto, Request $request){

		// $this->denyUnlessGranted('edit', $microPost); outro jeito de validar autorização, caso a classe extenda Controller
		// if (!$this->authorizationChecker->isGranted('edit', $produto)) {
		// 	throw new UnauthorizedHttpException("Acesso Negado");
		// }

		$form = $this->formFactory->create(ProdutoType::class, $produto);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()){
			$this->entityManager->flush();

			return new RedirectResponse($this->router->generate('index'));
		}

		return new Response(
			$this->twig->render(
				'produto/cadastro.html.twig',
				['form' => $form->createView()]
		)
		);
	}
}