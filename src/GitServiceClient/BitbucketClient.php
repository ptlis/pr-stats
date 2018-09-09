<?php

/**
 * @copyright   (c) 2018-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\PrStats\GitServiceClient;

use Bitbucket\API\Http\Listener\OAuthListener;
use Bitbucket\API\User;
use ptlis\PrStats\Config\Config;
use ptlis\PrStats\DTO\Repository;

/**
 * Client for Bitbucket.
 */
final class BitbucketClient implements GitServiceClient
{
    const SERVICE_NAME = 'bitbucket';

    /** @var Config */
    private $config;

    /** @var OAuthListener */
    private $oAuthListener;


    /**
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;

        $this->oAuthListener = new OAuthListener([
            'oauth_consumer_key'      => $config->getOAuthKey(),
            'oauth_consumer_secret'   => $config->getOAuthSecret()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getAllRepositories()
    {
        $user = new User();
        $user->getClient()->addListener($this->oAuthListener);
        $rawUserData = $response = json_decode($user->get()->getContent(), true);

        $repoList = [];
        foreach ($rawUserData['repositories'] as $rawRepositoryData) {
            $repoList[] = new Repository(
                new \DateTimeImmutable($rawRepositoryData['utc_created_on']),
                new \DateTimeImmutable($rawRepositoryData['utc_last_updated']),
                $rawRepositoryData['owner'],
                $rawRepositoryData['name'],
                'https://bitbucket.org/' . $rawRepositoryData['owner'] . '/' . $rawRepositoryData['slug'] . '/src',
                $rawRepositoryData
            );
        }

        return $repoList;
    }
}