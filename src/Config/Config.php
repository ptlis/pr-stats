<?php

/**
 * @copyright   (c) 2018-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\PrStats\Config;

/**
 * Simple DTO storing config values.
 */
final class Config
{
    /** @var string */
    private $service;

    /** @var string */
    private $accountName;

    /** @var string */
    private $oAuthKey;

    /** @var string */
    private $oAuthSecret;

    /**
     * @param string $service
     * @param string $accountName
     * @param string $oAuthKey
     * @param string $oAuthSecret
     */
    public function __construct(
        $service,
        $accountName,
        $oAuthKey,
        $oAuthSecret
    ) {
        $this->service = $service;
        $this->accountName = $accountName;
        $this->oAuthKey = $oAuthKey;
        $this->oAuthSecret = $oAuthSecret;
    }

    /**
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return string
     */
    public function getAccountName()
    {
        return $this->accountName;
    }

    /**
     * @return string
     */
    public function getOAuthKey()
    {
        return $this->oAuthKey;
    }

    /**
     * @return string
     */
    public function getOAuthSecret()
    {
        return $this->oAuthSecret;
    }
}