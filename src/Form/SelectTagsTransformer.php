<?php


namespace App\Form;


use Tetranz\Select2EntityBundle\Form\DataTransformer\EntitiesToPropertyTransformer;

/**
 * Data transformer for multiple mode (i.e., multiple = true)
 *
 * Class EntitiesToPropertyTransformer
 */
class SelectTagsTransformer extends EntitiesToPropertyTransformer
{

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
        return parent::reverseTransform($values);
    }



}