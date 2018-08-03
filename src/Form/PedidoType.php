<?php 

namespace App\Form;

use App\Entity\Pedido;
use App\Entity\Vendedor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PedidoType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options){

		$builder->add('vendedor', EntityType::class, [
                'class' => Vendedor::class,
                'choice_label' => 'nome',
                'label' => 'Vendedor:'
            ])
			->add('pedidoItens', CollectionType::class,  array(
            'entry_type' => PedidoItemType::class,
            'entry_options' => [
                    'attr' => [
                        'class' => 'item', // we want to use 'tr.item' as collection elements' selector
                    ],
                ],
            'label' => false,
            'allow_add'    => true,
            'allow_delete' => true,
            'prototype'    => true,
            'required'     => false,
            'by_reference' => true,
            'delete_empty' => true,
            'attr' => ['class' => 'table discount-collection'],
        	))
			->add('cadastrar', SubmitType::class, ['label' => 'Cadastrar Pedido']);
	}

	public function configureOptions(OptionsResolver $resolver){

		$resolver->setDefaults([
			'data_class' => Pedido::class
		]);
	}
}