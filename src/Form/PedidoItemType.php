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
use App\Form\DataTransformer\IdToProduto;

class PedidoItemType extends AbstractType {

	private $transformer;

    public function __construct(IdToProduto $transformer)
    {
        $this->transformer = $transformer;
    }

	public function buildForm(FormBuilderInterface $builder, array $options){

		$builder->add('codigo', TextType::class, [
                'label' => false,
                'mapped' => false,
            ])
			->add('produto', TextType::class, [
                'label' => false,
                'invalid_message' => 'ID inválido',
            ])
			->add('quantidade', TextType::class, [
				'label' => false
			]);

		$builder->get('produto')->addModelTransformer($this->transformer);
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