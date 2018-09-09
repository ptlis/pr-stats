<?php

/**
 * @copyright   (c) 2018-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\PrStats\DTO;

/**
 * DTO representing a git repository.
 */
final class Repository
{
    /** @var \DateTimeImmutable */
    private $created;

    /** @var \DateTimeImmutable */
    private $updated;

    /** @var string */
    private $owner;

    /** @var string */
    private $name;

    /** @var string */
    private $url;

    /** @var string[] */
    private $meta;

    /**
     * @param \DateTimeImmutable $created
     * @param \DateTimeImmutable $updated
     * @param string $owner
     * @param string $name
     * @param string $url
     * @param string[] $meta Array of service-specific repository metadata.
     */
    public function __construct(
        \DateTimeImmutable $created,
        \DateTimeImmutable $updated,
        $owner,
        $name,
        $url,
        array $meta
    ) {
        $this->created = $created;
        $this->updated = $updated;
        $this->owner = $owner;
        $this->name = $name;
        $this->url = $url;
        $this->meta = $meta;
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
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get an item from the service-specific metadata.
     *
     * @param string $key
     * @return string
     * @throws \RuntimeException The key was not found.
     */
    public function getMeta($key)
    {
        if (!array_key_exists($key, $this->meta)) {
            throw new \RuntimeException('Could not find metadata with key "' . $key . '"');
        }

        return $this->meta[$key];
    }
}