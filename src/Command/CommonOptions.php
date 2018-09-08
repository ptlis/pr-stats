<?php

/**
 * @copyright   (c) 2018-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\PrStats\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * Commonly used arguments & options.
 */
final class CommonOptions
{
    /* Option names */
    const OPTION_SERVICE = 'service';
    const OPTION_ACCOUNT = 'account';
    const OPTION_OAUTH_KEY = 'oauth_key';
    const OPTION_OAUTH_SECRET = 'oauth_secret';
    const OPTION_DOTENV_FILE = 'dotenv';

    /**
     * Set commonly used options for a command.
     *
     * @param Command $command
     */
    public static function setCommonOptions(
        Command $command
    ) {
        $command
            ->addOption(self::OPTION_SERVICE, null, InputOption::VALUE_OPTIONAL, 'Service (e.g. github, bitbucket)')
            ->addOption(self::OPTION_ACCOUNT, null, InputOption::VALUE_OPTIONAL, 'Account name')
            ->addOption(self::OPTION_OAUTH_KEY, null, InputOption::VALUE_OPTIONAL, 'The OAuth key to use')
            ->addOption(self::OPTION_OAUTH_SECRET, null, InputOption::VALUE_OPTIONAL, 'The OAuth secret to use')
            ->addOption(self::OPTION_DOTENV_FILE, null, InputOption::VALUE_OPTIONAL, 'The path to a .env file to read configs from (\'./.env\' by default)', './.env');
    }
}