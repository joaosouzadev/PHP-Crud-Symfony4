<?php

namespace App\DataFixtures;

use App\Entity\Empresa;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private const USERS = [
        [
            'username' => 'johndoe',
            'email' => 'john_doe@doe.com',
            'password' => 'john123',
            'fullName' => 'John Doe',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'robsmith',
            'email' => 'rob_smith@smith.com',
            'password' => 'rob123',
            'fullName' => 'Rob Smith',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'marrygold',
            'email' => 'marry_gold@gold.com',
            'password' => 'marry123',
            'fullName' => 'Marry Gold',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'admin',
            'email' => 'madmin@admin.com',
            'password' => 'admin123',
            'fullName' => 'App Admin',
            'roles' => [User::ROLE_ADMIN]
        ],
    ];

    private const EMPRESAS = [
        [
            'cnpj' => '123456789',
            'razaoSocial' => 'IRMAOS MUFFATO',
            'nomeFantasia' => 'SUPER MUFFATO',
            'situacaoTributaria' => 'ATIVA',
            'uf' => 'PR',
            'cidade' => 'Londrina',
            'cep' => '86050-000'
        ],
        [
            'cnpj' => '9849889-06/148',
            'razaoSocial' => 'MERCEDEZ BENZ',
            'nomeFantasia' => 'MERCEDEZ BENZ',
            'situacaoTributaria' => 'INATIVA',
            'uf' => 'SP',
            'cidade' => 'Campinas',
            'cep' => '13526-150'
        ],
    ];

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadEmpresas($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        foreach (self::USERS as $userData) {
            $user = new User();
            $user->setUsername($userData['username']);
            $user->setFullName($userData['fullName']);
            $user->setEmail($userData['email']);
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $userData['password']
                )
            );
            $user->setRoles($userData['roles']);

            $this->addReference(
                $userData['username'],
                $user
            );

            $manager->persist($user);
        }
        
        $manager->flush();
    }

    private function loadEmpresas(ObjectManager $manager)
    {
        foreach (self::EMPRESAS as $empresaData) {
            $empresa = new Empresa();
            $empresa->setCnpj($empresaData['cnpj']);
            $empresa->setRazaoSocial($empresaData['razaoSocial']);
            $empresa->setNomeFantasia($empresaData['nomeFantasia']);
            $empresa->setSituacaoTributaria($empresaData['situacaoTributaria']);
            $empresa->setUf($empresaData['uf']);
            $empresa->setCidade($empresaData['cidade']);
            $empresa->setCep($empresaData['cep']);

            $manager->persist($empresa);
        }

        $manager->flush();
    }
}