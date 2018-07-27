<?php 

namespace App\Form;

use App\Entity\Produto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProdutoType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options){

		$builder->add('nome', TextType::class)
			->add('Register', SubmitType::class, ['label' => 'Cadastrar Produto']);
	}

	public function configureOptions(OptionsResolver $resolver){

		$resolver->setDefaults([
			'data_class' => Produto::class
		]);
	}
}