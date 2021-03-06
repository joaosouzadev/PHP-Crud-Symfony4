<?php 

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RegisterController extends Controller {

	/**
	* @Route("/register", name="user_register")
	**/
	public function register(UserPasswordEncoderInterface $passwordEncoder, Request $request){

		$user = new User();
		$form = $this->createForm(UserType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$password = $passwordEncoder->encodePassword(
				$user,
				$user->getPlainPassword()
			);
			$user->setPassword($password);

			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($user);
			$entityManager->flush();

			return $this->redirectToRoute('index');
		}

		return $this->render('register/register.html.twig', [
			'form' => $form->createView()
		]);
	}
}