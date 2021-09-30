<?php


namespace App\Form;

use App\Entity\Location;
use Doctrine\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\Parent_;
use Tetranz\Select2EntityBundle\Form\DataTransformer\EntitiesToPropertyTransformer;

/**
 * Data transformer for multiple mode (i.e., multiple = true)
 *
 * Class EntitiesToPropertyTransformer
 */
class SelectLocationsTransformer extends EntitiesToPropertyTransformer
{
    public function transform($entityCodes)
    {
        // get all the valid Location entities, return the string
        $entities = $this->em->getRepository($this->className)->findBy(['code' => $entityCodes]);
        $result = [];
        /** @var Location $entity */
        foreach ($entities as $entity) {
            $result[$entity->getCode()] = $entity->__toString();
        }
        return $result;


        $transformedEntities =  parent::transform($entities);
        if ($entities) {
//            dd($entities, $transformedEntities);
        }
        return $transformedEntities;
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
        return $values; // keep the original array.


//        $transformedValues = array_map(fn($entity) => $this->accessor->getValue($entity, $this->primaryKey), $values);
        $transformedValues =  parent::reverseTransform($values);
        return $transformedValues;
    }



}
