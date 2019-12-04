<?php

namespace App\Form\Type;

use App\Entity\Taxonomy\Taxon;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\TaxonomyBundle\Form\Type\TaxonAutocompleteChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SubcontractorType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'sylius.ui.name'])
            ->add('email', EmailType::class, ['label' => 'sylius.ui.email'])
            ->add('taxons', TaxonAutocompleteChoiceType::class, [
                'label' => 'sylius.ui.taxons',
                'multiple' => true
            ])
        ;
    }
}
