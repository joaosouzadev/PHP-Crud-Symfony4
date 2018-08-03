<?php 

namespace App\Form;

use App\Entity\PedidoItem;
use App\Entity\Vendedor;
use App\Entity\Produto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PedidoItemType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options){

		$builder->add('produto', EntityType::class, [
                'class' => Produto::class,
                'choice_label' => 'nome',
                'label' => 'Produto:'
            ])
			->add('quantidade', TextType::class, ['label' => 'Quantidade:']);
	}

	public function configureOptions(OptionsResolver $resolver){

		$resolver->setDefaults([
			'data_class' => PedidoItem::class
		]);
	}

	public function getBlockPrefix()
    {
        return 'PedidoItemType';
    }
}