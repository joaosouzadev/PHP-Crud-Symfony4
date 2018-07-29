<?php 

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;


class AppExtension extends AbstractExtension implements GlobalsInterface {

	public function getFunctions()
    {
        // Register the function in twig :
        // In your template you can use it as : {{find(123)}}
        return array(
            new \Twig_SimpleFunction('find', array($this, 'find')),
        );
    }

	protected $doctrine;

	public function __construct(RegistryInterface $doctrine) {
		$this->doctrine = $doctrine;
	}

	public function find(){
		$em = $this->doctrine->getManager();
        $myRepo = $em->getRepository('App:Empresa');
        

        return $myRepo->find($session->get('empresa'));
    }

	public function getGlobals() {

		return [
			// 'minhasEmpresas' => $this->em->getRepository('App:User')->findByEmpresa(),
		];
	}

	public function getName()
   {
      return 'Empresa Selecionada Twig Extension';
   }
}