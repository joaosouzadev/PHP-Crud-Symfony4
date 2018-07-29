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

class HomeController extends AbstractController {


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
	public function index(Request $request, TokenStorageInterface $tokenStorage){

		$session = $this->get('session');
    	dump($session->get('empresa'));

		$user = $tokenStorage->getToken()->getUser();

		$empresas = $user->getEmpresas();

		$form = $this->createFormBuilder()
        ->add('empresa', EntityType::class, [
                'class' => Empresa::class,
                'choice_label' => 'razaoSocial',
                'label' => 'Empresa:',
                'choices' => $user->getEmpresas()
            ])
        ->add('selecionar', SubmitType::class, ['label' => 'Selecionar'])
        ->getForm();

	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $session->set('empresa', $data['empresa']->getId());
            return $this->redirectToRoute('index');
        }

	    return $this->render('home/index.html.twig', [
	    	'form' => $form->createView()]);
	}
}