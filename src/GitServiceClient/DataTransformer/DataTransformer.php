<?php

/**
 * @copyright   (c) 2018-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\PrStats\GitServiceClient\DataTransformer;

/**
 * Data transformer to map between git service and internal representations of data.
 */
interface DataTransformer
{
    /**
     * Transforms an internal value to the service-specific representation.
     *
     * @param mixed $internalValue
     * @return mixed
     */
    public function transform($internalValue);

    /**
     * Transforms an service-specific value to the internal representation.
     *
     * @param mixed $serviceValue
     * @return mixed
     */
    public function reverseTransform($serviceValue);
}