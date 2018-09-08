<?php

/**
 * @copyright   (c) 2018-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\PrStats\Tests;

use PHPUnit\Framework\TestCase;
use ptlis\PrStats\Command\CommonOptions;
use ptlis\PrStats\Config\Config;
use ptlis\PrStats\Config\ConfigResolver;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

final class ConfigResolverTest extends TestCase
{
    private function getDefinition()
    {
        return new InputDefinition([
            new InputOption(CommonOptions::OPTION_SERVICE, null, InputOption::VALUE_OPTIONAL, 'Service (e.g. github, bitbucket)'),
            new InputOption(CommonOptions::OPTION_ACCOUNT, null, InputOption::VALUE_OPTIONAL, 'Account name'),
            new InputOption(CommonOptions::OPTION_OAUTH_KEY, null, InputOption::VALUE_OPTIONAL, 'The OAuth key to use'),
            new InputOption(CommonOptions::OPTION_OAUTH_SECRET, null, InputOption::VALUE_OPTIONAL, 'The OAuth secret to use')
        ]);
    }

    public function testGetFromCommandArguments()
    {
        $input = new ArrayInput([
            '--service' => 'input argument service',
            '--account' => 'input argument account',
            '--oauth_key' => 'input argument oauth key',
            '--oauth_secret' => 'input argument oauth secret',
        ], $this->getDefinition());

        $configResolver = new ConfigResolver($input, './.env.doesnotexist');

        $config = $configResolver->getConfig();

        $this->assertEquals(
            new Config(
                'input argument service',
                'input argument account',
                'input argument oauth key',
                'input argument oauth secret'
            ),
            $config
        );
    }

    public function testGetFromDotEnvFile()
    {
        $input = new ArrayInput([], $this->getDefinition());

        $configResolver = new ConfigResolver($input, realpath(__DIR__ . '/../data/.env'));

        $config = $configResolver->getConfig();

        $this->assertEquals(
            new Config(
                'dotenv service',
                'dotenv account',
                'dotenv oauth key',
                'dotenv oauth secret'
            ),
            $config
        );
    }

    public function testGetFromEnvironment()
    {
        // Store prev environment variables
        $oldService = getenv('SERVICE');
        $oldAccount = getenv('ACCOUNT');
        $oldOAuthKey = getenv('OAUTH_KEY');
        $oldOAuthSecret = getenv('OAUTH_SECRET');

        putenv('SERVICE=environment service');
        putenv('ACCOUNT=environment account');
        putenv('OAUTH_KEY=environment oauth key');
        putenv('OAUTH_SECRET=environment oauth secret');

        $input = new ArrayInput([], $this->getDefinition());

        $configResolver = new ConfigResolver($input, './.env.doesnotexist');

        $config = $configResolver->getConfig();

        $this->assertEquals(
            new Config(
                'environment service',
                'environment account',
                'environment oauth key',
                'environment oauth secret'
            ),
            $config
        );

        putenv('SERVICE=' . $oldService);
        putenv('ACCOUNT=' . $oldAccount);
        putenv('OAUTH_KEY=' . $oldOAuthKey);
        putenv('OAUTH_SECRET=' . $oldOAuthSecret);
    }
}