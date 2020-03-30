<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Location;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('locationScope', ChoiceType::class, [
                'label' => 'Geographic Scope',
                'choices' =>
                [
                    'N/A' => 0,
                    'National' => 1,
                    'Regional' => 2,
                    'Local' => 3
                ]
            ])
            ->add('regionalFilter', CountryType::class, [
                'mapped' => false,
                'preferred_choices' => ['US', 'CA'],
            ])
            // ->add('countries')
            // ->add('states')
            // ->add('cities')
                /*

                */
        ;

        $builder
            ->add('filter', Select2EntityType::class, [
                'mapped' => false,
                'multiple' => false,
                'remote_route' => 'location_json',
                'remote_params' => ['lvl' => 0],
                'class' => Location::class,
                'primary_key' => 'code',
                'text_property' => 'name',
                'minimum_input_length' => 1,
                'page_limit' => 10,
                'allow_clear' => true,
                'delay' => 250,
                'cache' => true,
                'transformer' => SelectTagsTransformer::class,
                'cache_timeout' => 60000, // if 'cache' is true
                'language' => 'en',
                'placeholder' => 'FILTER!',
        ]);

        $builder
            ->add('locations', Select2EntityType::class, [
                'multiple' => true,
                'remote_route' => 'location_json',
                'remote_params' => ['lvl' => 0],
                'class' => Location::class,
                'primary_key' => 'code',
                'text_property' => 'name',
                'minimum_input_length' => 1,
                'page_limit' => 10,
                'allow_clear' => true,
                'delay' => 250,
                'cache' => true,
                'transformer' => SelectTagsTransformer::class,
                'cache_timeout' => 60000, // if 'cache' is true
                'language' => 'en',
                'placeholder' => 'Select locations',
                /*
                'query_parameters' => [
                    'start' => new \DateTime(),
                    'end' => (new \DateTime())->modify('+5d'),
                    // any other parameters you want your ajax route request->query to get, that you might want to modify dynamically
                ],
                */
                // 'object_manager' => $objectManager, // inject a custom object / entity manager
            ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
