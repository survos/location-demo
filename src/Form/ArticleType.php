<?php

namespace App\Form;

use App\Entity\Article;
use Bordeux\Bundle\GeoNameBundle\Entity\GeoName;
use Doctrine\ORM\QueryBuilder;
use Survos\LocationBundle\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title');
        if (0)
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
            ->add('filter', Select2EntityType::class, [
                'label' => Location::class,
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
                'transformer' => SelectLocationsTransformer::class,
                'cache_timeout' => 60000, // if 'cache' is true
                'language' => 'en',
                'placeholder' => 'Level 0, not mapped.',
        ]);

            foreach (['locations' => null, 'countries' => 1, 'states' => 2, 'cities' => 3] as $var => $lvl) {
                $builder
                    ->add($var, Select2EntityType::class, [
                            'remote_route' => 'location_json',
                            'remote_params' => ['lvl' => $lvl],

                            'transformer' => SelectLocationsTransformer::class,
                            'class' => Location::class,

                            'mapped' => true,
                            'multiple' => true,
                            'primary_key' => 'code', // Location::KEY?
                            'text_property' => 'name',
                        ]
                    );
            }

//        $builder
//            ->add('states', Select2EntityType::class, [
//                'label' => 'States (lvl 1) mapped.',
//                'mapped' => true,
//                'multiple' => true,
//                'remote_route' => 'location_json',
//                'remote_params' => ['lvl' => 1],
//
//                'class' => Location::class,
//                'primary_key' => 'code',
//                'text_property' => 'name',
//                'minimum_input_length' => 1,
//                'page_limit' => 10,
//                'allow_clear' => true,
//                'delay' => 250,
//                'cache' => true,
//                'transformer' => SelectTagsTransformer::class,
//                'cache_timeout' => 60000, // if 'cache' is true
//                'language' => 'en',
//                'placeholder' => 'Level 1 locations',
//            ]);

        //        $builder
//            ->add('locations', Select2EntityType::class, [
//                'multiple' => true,
//                'remote_route' => 'location_json',
//                'remote_params' => ['lvl' => 0],
//                'class' => GeoName::class,
//                'primary_key' => 'id',
//                'text_property' => 'name',
//                'minimum_input_length' => 1,
//                'page_limit' => 10,
//                'allow_clear' => true,
//                'delay' => 150,
//                'cache' => true,
//                'transformer' => SelectTagsTransformer::class,
//                'cache_timeout' => 60000, // if 'cache' is true
//                'language' => 'en',
//                'placeholder' => 'Select locations',
//                /*
//                'query_parameters' => [
//                    'start' => new \DateTime(),
//                    'end' => (new \DateTime())->modify('+5d'),
//                    // any other parameters you want your ajax route request->query to get, that you might want to modify dynamically
//                ],
//                */
//                // 'object_manager' => $objectManager, // inject a custom object / entity manager
//            ]);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
