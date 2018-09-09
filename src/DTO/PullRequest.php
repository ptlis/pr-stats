<?php

/**
 * @copyright   (c) 2018-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\PrStats\DTO;

/**
 * DTO representing a pull request.
 */
final class PullRequest
{
    const PR_STATUS_OPEN = 'open';
    const PR_STATUS_MERGED = 'merged';
    const PR_STATUS_DECLINED = 'declined';

    /** @var \DateTimeImmutable */
    private $created;

    /** @var \DateTimeImmutable */
    private $updated;

    /** @var string */
    private $title;

    /** @var string */
    private $state;

    /** @var string */
    private $createdBy;

    /** @var string */
    private $closedBy;

    /** @var string */
    private $url;

    /** @var string */
    private $sourceBranch;

    /** @var string */
    private $destinationBranch;


    /**
     * @param \DateTimeImmutable $created
     * @param \DateTimeImmutable $updated
     * @param string $title
     * @param string $state One of PullRequest::PR_STATUS_* constants
     * @param string $createdBy
     * @param string $closedBy
     * @param string $url
     * @param string $sourceBranch
     * @param string $destinationBranch
     */
    public function __construct(
        \DateTimeImmutable $created,
        \DateTimeImmutable $updated,
        $title,
        $state,
        $createdBy,
        $closedBy,
        $url,
        $sourceBranch,
        $destinationBranch
    ) {
        $this->created = $created;
        $this->updated = $updated;
        $this->title = $title;
        $this->state = $state;
        $this->createdBy = $createdBy;
        $this->closedBy = $closedBy;
        $this->url = $url;
        $this->sourceBranch = $sourceBranch;
        $this->destinationBranch = $destinationBranch;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @return string
     */
    public function getClosedBy()
    {
        return $this->closedBy;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getSourceBranch()
    {
        return $this->sourceBranch;
    }

    /**
     * @return string
     */
    public function getDestinationBranch()
    {
        return $this->destinationBranch;
    }
}