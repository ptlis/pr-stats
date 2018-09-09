<?php

/**
 * @copyright   (c) 2018-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\PrStats\GitServiceClient;

use ptlis\PrStats\DTO\Repository;

/**
 * Interface that git service clients must implement.
 */
interface GitServiceClient
{
    /**
     * Returns a list of Repository instances.
     *
     * @return Repository[]
     */
    public function getAllRepositories();
}