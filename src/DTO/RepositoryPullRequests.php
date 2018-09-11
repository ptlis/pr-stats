<?php

/**
 * @copyright   (c) 2018-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\PrStats\DTO;

/**
 * DTO representing all pull requests for a repository.
 */
final class RepositoryPullRequests
{
    /** @var Repository */
    private $repository;

    /** @var PullRequest[] */
    private $pullRequestList;

    /**
     * @param Repository $repository
     * @param PullRequest[] $pullRequestList
     */
    public function __construct(
        Repository $repository,
        array $pullRequestList
    ) {
        $this->repository = $repository;
        $this->pullRequestList = $pullRequestList;
    }

    /**
     * @return Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return PullRequest[]
     */
    public function getPullRequestList()
    {
        return $this->pullRequestList;
    }
}