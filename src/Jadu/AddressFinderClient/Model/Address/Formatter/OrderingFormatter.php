<?php

namespace Jadu\AddressFinderClient\Model\Address\Formatter;

class OrderingFormatter
{
    /**
     * Sorts an array based on a natural language comparator.
     *
     * @param array $results
     * @param string $property
     */
    public static function order(&$results = [], $property)
    {
        uasort(
            $results,
            function ($a, $b) use ($property) {
                return strnatcmp(
                    $a->$property,
                    $b->$property
                );
            }
        );
    }
}
