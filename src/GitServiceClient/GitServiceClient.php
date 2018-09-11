<?php

/**
 * @copyright   (c) 2018-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\PrStats\GitServiceClient;

use ptlis\PrStats\DTO\PullRequest;
use ptlis\PrStats\DTO\Repository;
use ptlis\PrStats\DTO\RepositoryPullRequests;

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

    /**
     * Returns a list of PullRequest instances for the provided Repository.
     *
     * @param Repository $repository
     * @param array $prStatusList Array of PullRequest::PR_STATUS_* constants
     *
     * @return PullRequest[]
     */
    public function getPullRequests(Repository $repository, array $prStatusList);

    /**
     * Returns a list of RepositoryPullRequests instances.
     *
     * @param array $prStatusList Array of PullRequest::PR_STATUS_* constants
     * @return RepositoryPullRequests[]
     */
    public function getAllRepositoryPullRequests(array $prStatusList);
}