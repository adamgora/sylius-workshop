<?php


namespace App\Form\Type;


use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

class WeightBasedShippingCalculatorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('limit_weight', NumberType::class)
            ->add('above_or_equal', MoneyType::class, [
                'currency' => 'USD',
            ])
            ->add('below', MoneyType::class, [
                'currency' => 'USD'
            ]);
    }
}
