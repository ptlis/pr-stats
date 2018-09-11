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
use ptlis\PrStats\DTO\RepositoryPullRequests;
use ptlis\PrStats\GitServiceClient\DataTransformer\PrStatusBitbucketTransformer;
use ptlis\PrStats\GitServiceClient\Observer\ClientObserver;
use ptlis\PrStats\GitServiceClient\Observer\NullObserver;

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

    /** @var ClientObserver */
    private $observer;


    /**
     * @param Config $config
     * @param ClientObserver|null $observer
     */
    public function __construct(
        Config $config,
        ClientObserver $observer = null
    ) {
        $this->config = $config;

        $this->oAuthListener = new OAuthListener([
            'oauth_consumer_key'      => $config->getOAuthKey(),
            'oauth_consumer_secret'   => $config->getOAuthSecret()
        ]);

        $this->prStatusTransformer = new PrStatusBitbucketTransformer();

        // If no observer is passed default to the NullObserver
        if (is_null($observer)) {
            $observer = new NullObserver();
        }
        $this->observer = $observer;
    }

    /**
     * @inheritdoc
     */
    public function getAllRepositories()
    {
        $this->observer->notify('Finding repositories for ' . $this->config->getAccountName() . '...');

        $respositoryList = $this->internalGetAllRepositories();

        $this->observer->notify('  Found ' . count($respositoryList) . ' repositories');

        return $respositoryList;
    }

    /**
     * @inheritdoc
     */
    public function getPullRequests(Repository $repository, array $prStatusList)
    {
        $this->observer->notify('Reading pull requests for ' . $repository->getName());

        // Map to internal representation of Pull Requests
        $prList = $this->internalGetPullRequests($repository, $prStatusList);

        $this->observer->notify('  Found ' . count($prList) . ' pull requests');

        return $prList;
    }

    /**
     * @inheritdoc
     */
    public function getAllRepositoryPullRequests(array $prStatusList)
    {
        $this->observer->notify('Finding repositories for ' . $this->config->getAccountName() . '...');

        $respositoryList = $this->internalGetAllRepositories();
        $repoCount = count($respositoryList);

        $this->observer->notify('  Found ' . $repoCount . ' repositories');
        $this->observer->notify('');


        $repositoryPullRequestsList = [];
        foreach ($respositoryList as $index => $repository) {
            $this->observer->incrementalNotify($index, $repoCount, 'Finding pull requests for ' . $repository->getName(), false);

            $pullRequestList = $this->internalGetPullRequests($repository, $prStatusList);
            $repositoryPullRequestsList[] = new RepositoryPullRequests($repository, $pullRequestList);

            $this->observer->incrementalNotify($index, $repoCount, 'Found ' . count($pullRequestList) . ' pull requests', true);
        }
        return $repositoryPullRequestsList;
    }

    /**
     * Gets all repositories for the account specified in the config.
     *
     * @return Repository[]
     */
    private function internalGetAllRepositories()
    {
        $user = new User();
        $user->getClient()->addListener($this->oAuthListener);
        $rawUserData = $response = json_decode($user->get()->getContent(), true);

        $respositoryList = [];
        foreach ($rawUserData['repositories'] as $rawRepositoryData) {
            $respositoryList[] = new Repository(
                new \DateTimeImmutable($rawRepositoryData['utc_created_on']),
                new \DateTimeImmutable($rawRepositoryData['utc_last_updated']),
                $rawRepositoryData['owner'],
                $rawRepositoryData['name'],
                'https://bitbucket.org/' . $rawRepositoryData['owner'] . '/' . $rawRepositoryData['slug'] . '/src',
                $rawRepositoryData
            );
        }

        return $respositoryList;
    }

    /**
     * Gets all pull requests for the provided repository & the account specified in the config.
     *
     * @param Repository $repository
     * @param string[] $prStatusList
     * @return PullRequest[]
     */
    private function internalGetPullRequests(
        Repository $repository,
        array $prStatusList
    ) {
        // Map from internal to bitbucket's representation of PR statuses
        $mappedStatuses = array_map(function($internalPrStatus) {
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