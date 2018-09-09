<?php

/**
 * @copyright   (c) 2018-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\PrStats\Command;

use ptlis\PrStats\Config\ConfigResolver;
use ptlis\PrStats\DTO\PullRequest;
use ptlis\PrStats\GitServiceClient\ClientFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class StatsWeeklyCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('stats:weekly')
            ->setDescription('Generates stats for the specified week');

        CommonOptions::setCommonOptions($this);
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Populate config
        $configResolver = new ConfigResolver($input, $input->getOption(CommonOptions::OPTION_DOTENV_FILE));
        $config = $configResolver->getConfig();

        // Create client
        $clientFactory = new ClientFactory();
        $client = $clientFactory->build($config);

        $repoList = $client->getAllRepositories();

        foreach ($repoList as $repository) {
            $prList = $client->getPullRequests(
                $repository,
                [
                    PullRequest::PR_STATUS_DECLINED,
                    PullRequest::PR_STATUS_MERGED
                ]
            );
        }
        var_dump($prList);
    }
}