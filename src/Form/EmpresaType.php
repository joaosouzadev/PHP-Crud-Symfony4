<?php 

namespace App\Form;

use App\Entity\Empresa;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmpresaType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options){

		$builder->add('cnpj', TextType::class, ['label' => 'CNPJ:'])
			->add('razaoSocial', TextType::class, ['label' => 'Razão Social:'])
			->add('nomeFantasia', TextType::class, ['label' => 'Nome Fantasia:'])
			->add('situacaoTributaria', ChoiceType::class, array(
				'choices' => array(
					'Ativa' => 'ATIVA',
					'Inativa' => 'INATIVA'
				)),
				['label' => 'Situação Tributária:']
			)
			->add('cep', TextType::class, ['label' => 'CEP:'])
			->add('rua', TextType::class, ['label' => 'Rua:'])
			->add('numero', TextType::class, ['label' => 'Nº:'])
			->add('bairro', TextType::class, ['label' => 'Bairro:'])
			->add('cidade', TextType::class, ['label' => 'Cidade:'])
			->add('uf', TextType::class, ['label' => 'UF:'])
			->add('pais', TextType::class, ['label' => 'País:'])
			->add('Register', SubmitType::class, ['label' => 'Cadastrar Empresa']);
	}

	public function configureOptions(OptionsResolver $resolver){

		$resolver->setDefaults([
			'data_class' => Empresa::class
		]);
	}
}