<?php


namespace App\Form;

use phpDocumentor\Reflection\Types\Parent_;
use Tetranz\Select2EntityBundle\Form\DataTransformer\EntitiesToPropertyTransformer;

/**
 * Data transformer for multiple mode (i.e., multiple = true)
 *
 * Class EntitiesToPropertyTransformer
 */
class SelectLocationsTransformer extends EntitiesToPropertyTransformer
{
    public function transform($entities)
    {
        $transformedEntities =  parent::transform($entities);
        if ($entities) {
            dd($entities, $transformedEntities);
        }
    }

    /**
     * Transform array to a collection of entities
     *
     * @param array $values
     * @return array
     */
    public function reverseTransform($values)
    {
        if (!is_array($values) || empty($values)) {
            return array();
        }

        $values = array_filter($values, function($value) {
            return $value !== "";
        });


//        $transformedValues = array_map(fn($entity) => $this->accessor->getValue($entity, $this->primaryKey), $values);
        $transformedValues =  parent::reverseTransform($values);
        dd($values, $transformedValues);
        return $transformedValues;
    }



}
