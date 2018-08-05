<?php

namespace App\Controller;

use App\Entity\Empresa;
use App\Entity\Pedido;
use App\Entity\Produto;
use App\Entity\PedidoItem;
use App\Form\ProdutoType;
use App\Form\PedidoType;
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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

		$session = $this->get('session');
		$user = $tokenStorage->getToken()->getUser();

		$html = $this->twig->render('produto/index.html.twig', [
			'produtos' => $this->produtoRepository->findByEmpresa($session->get('empresa')),
		]);

		return new Response($html);
	}

	/**
	* @Route("/cadastro", name="produto_cadastro")
	*/
	public function cadastrar(Request $request, TokenStorageInterface $tokenStorage){

		$session = $this->get('session');
    	dump($session->get('empresa'));

    	$repository = $this->getDoctrine()->getRepository(Empresa::class);

		$user = $tokenStorage->getToken()->getUser();

		$produto = new Produto();
		$empresa = $repository->find($session->get('empresa'));

		$form = $this->formFactory->create(ProdutoType::class, $produto);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()){
			$produto->setEmpresa($empresa);
			$this->entityManager->persist($produto);
			$this->entityManager->flush();

			return new RedirectResponse($this->router->generate('produtos'));
		}

		return new Response(
			$this->twig->render(
				'produto/cadastro.html.twig',
				['form' => $form->createView()]
		)
		);
	}



	/**
	* @Route("/produto/edit/{id}", name="produto_editar")
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

			return new RedirectResponse($this->router->generate('produtos'));
		}

		return new Response(
			$this->twig->render(
				'produto/cadastro.html.twig',
				['form' => $form->createView()]
		)
		);
	}

	/**
	* @Route("/pedido", name="produto_pedido")
	*/
	public function pedido(Request $request){

		// $this->denyUnlessGranted('edit', $microPost); outro jeito de validar autorização, caso a classe extenda Controller
		// if (!$this->authorizationChecker->isGranted('edit', $produto)) {
		// 	throw new UnauthorizedHttpException("Acesso Negado");
		// }

		$session = $this->get('session');
    	dump($session->get('empresa'));

    	$repository = $this->getDoctrine()->getRepository(Empresa::class);
		$empresa = $repository->find($session->get('empresa'));

		$pedido = new Pedido();

		$form = $this->formFactory->create(PedidoType::class, $pedido);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()){
			// $pedido->setEmpresa($empresa);
			$pedido->setVendedor($form->get('vendedor')->getData());

			$valor = 0;

			foreach ($form->get('pedidoItens')->getData() as $itens){
				$preco = $itens->getProduto()->getPreco();
				$valor+= $preco * $itens->getQuantidade();
                $itens->setPedido($pedido);
                $itens->setPreco($preco);
                $itens->setQuantidade($itens->getQuantidade());
            }

            $pedido->setValor($valor);
			$this->entityManager->persist($pedido);
			$this->entityManager->flush();

			return new RedirectResponse($this->router->generate('produto_pedido'));
		}

		return new Response(
			$this->twig->render(
				'produto/pedido.html.twig',
				['form' => $form->createView()]
		)
		);
	}

	/**
	* @Route("/pedidos/consulta_codigo", name="lancamento_pedidos_consulta-codigo")
	*/
	public function PedidoConsultaCodigo(Request $request) {
		if (! $request->isXmlHttpRequest()) {
            throw new NotFoundHttpException();
        }

        $session = $this->get('session');
    	$empresa = $session->get('empresa');

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        // Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);

        $serializer = new Serializer($normalizers, $encoders);
        
        // Pega o request do Ajax
        $cod = $request->query->get('produto_cod');

        // Seta o Repositório e Procura pelo Código vindo do Ajax
        $repo = $this->getDoctrine()->getRepository(Produto::class);
        $produtoId = $repo->findByCodigo($cod, $empresa);

        // Pega o produto do array de respostas
        $produto = $produtoId[0];

        // Passa o content pro JSON
        $jsonContent = $serializer->serialize($produto, 'json');

        return new Response($jsonContent);
	}
}