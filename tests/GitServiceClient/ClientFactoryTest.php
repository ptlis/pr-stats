<?php

/**
 * @copyright   (c) 2018-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\PrStats\Tests\GitServiceClient;

use PHPUnit\Framework\TestCase;
use ptlis\PrStats\Config\Config;
use ptlis\PrStats\GitServiceClient\BitbucketClient;
use ptlis\PrStats\GitServiceClient\ClientFactory;

final class ClientFactoryTest extends TestCase
{
    public function testSuccessBuildBitbucketClient()
    {
        $config = new Config(
            'bitbucket',
            'my_account',
            'oAuth key',
            'oAuth secret, shhh!'
        );

        $factory = new ClientFactory();
        $client = $factory->build($config);

        $this->assertInstanceOf(BitbucketClient::class, $client);
    }

    public function testFailureBuildUnknownClient()
    {
        $this->setExpectedException('\RuntimeException', 'Unknown git service "wibblehost" encountered');

        $config = new Config(
            'wibblehost',
            'my_account',
            'oAuth key',
            'oAuth secret, shhh!'
        );

        $factory = new ClientFactory();
        $factory->build($config);
    }
}