<?php

/**
 * @copyright   (c) 2018-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\PrStats\GitServiceClient;

use Bitbucket\API\Http\Listener\OAuthListener;
use Bitbucket\API\Http\Response\Pager;
use Bitbucket\API\Repositories\PullRequests;
use Bitbucket\API\User;
use ptlis\PrStats\Config\Config;
use ptlis\PrStats\DTO\PullRequest;
use ptlis\PrStats\DTO\Repository;
use ptlis\PrStats\GitServiceClient\DataTransformer\PrStatusBitbucketTransformer;

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

    /** @var PrStatusBitbucketTransformer */
    private $prStatusTransformer;


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

        $this->prStatusTransformer = new PrStatusBitbucketTransformer();
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

    /**
     * @inheritdoc
     */
    public function getPullRequests(Repository $repository, array $prStatusList)
    {
        // Map from internal to bitbucket's representation of PR statuses
        $mappedStatuses = array_map(function ($internalPrStatus) {
            return $this->prStatusTransformer->transform($internalPrStatus);
        }, $prStatusList);

        // Retrieve raw PR data
        $pull = new PullRequests();
        $pull->getClient()->addListener($this->oAuthListener);
        $page = new Pager(
            $pull->getClient(),
            $pull->all($this->config->getAccountName(), $repository->getMeta('slug'), ['state' => $mappedStatuses])
        );
        $prDataList = json_decode($page->fetchAll()->getContent(), true);

        // Map to internal representation of Pull Requests
        $prList = [];
        foreach ($prDataList['values'] as $rawPrData) {
            $prList[] = new PullRequest(
                new \DateTimeImmutable($rawPrData['created_on']),
                new \DateTimeImmutable($rawPrData['updated_on']),
                $rawPrData['title'],
                $this->prStatusTransformer->reverseTransform($rawPrData['state']),
                $rawPrData['author']['username'],
                $rawPrData['closed_by']['username'],
                'https://bitbucket.org/' . $rawPrData['source']['repository']['full_name'] . '/pull-requests/' . $rawPrData['id'],
                $rawPrData['source']['branch']['name'],
                $rawPrData['destination']['branch']['name']
            );
        }
        return $prList;
    }
}