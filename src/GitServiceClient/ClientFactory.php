<?php

/**
 * @copyright   (c) 2018-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\PrStats\GitServiceClient;

use ptlis\PrStats\Config\Config;

/**
 * Factory that creates an appropriate git service client from the provided configuration.
 */
final class ClientFactory
{
    /**
     * Builds a git service client from the provided configuration.
     *
     * @param Config $config
     * @return GitServiceClient
     */
    public function build(Config $config)
    {
        $client = null;
        switch ($config->getService()) {
            case BitbucketClient::SERVICE_NAME:
                $client = new BitbucketClient($config);
                break;

            default:
                throw new \RuntimeException('Unknown git service "' . $config->getService() . '" encountered');
        }
        return $client;
    }
}