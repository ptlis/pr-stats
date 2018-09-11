<?php

/**
 * @copyright   (c) 2018-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\PrStats\GitServiceClient;

use ptlis\PrStats\Config\Config;
use ptlis\PrStats\GitServiceClient\Observer\ClientObserver;

/**
 * Factory that creates an appropriate git service client from the provided configuration.
 */
final class ClientFactory
{
    /**
     * Builds a git service client from the provided configuration.
     *
     * @param Config $config
     * @param ClientObserver|null $observer
     * @return GitServiceClient
     */
    public function build(
        Config $config,
        ClientObserver $observer = null
    ) {
        $client = null;
        switch ($config->getService()) {
            case BitbucketClient::SERVICE_NAME:
                $client = new BitbucketClient($config, $observer);
                break;

            default:
                throw new \RuntimeException('Unknown git service "' . $config->getService() . '" encountered');
        }
        return $client;
    }
}