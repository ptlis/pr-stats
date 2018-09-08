<?php

/**
 * @copyright   (c) 2018-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\PrStats\Config;

use josegonzalez\Dotenv\Loader;
use ptlis\PrStats\Command\CommonOptions;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Used to resolve config options.
 *
 * Looks first at console arguments, then for a .env file & finally at the environment variables.
 */
final class ConfigResolver
{
    /* Configuration value names */
    const SERVICE = 'SERVICE';
    const ACCOUNT = 'ACCOUNT';
    const OAUTH_KEY = 'OAUTH_KEY';
    const OAUTH_SECRET = 'OAUTH_SECRET';

    /** @var InputInterface */
    private $input;

    /** @var array */
    private $envFileData = [];


    /**
     * @param InputInterface $input
     * @param string $envPath
     */
    public function __construct(
        InputInterface $input,
        $envPath
    ) {
        $this->input = $input;

        // Attempt to load data from .env file (if present)
        if (file_exists($envPath) && !is_dir($envPath)) {
            $loader = new Loader($envPath);
            $this->envFileData = $loader->parse()->toArray();
        }
    }

    /**
     * @return Config
     */
    public function getConfig()
    {

        return new Config(
            $this->resolveValue(self::SERVICE),
            $this->resolveValue(self::ACCOUNT),
            $this->resolveValue(self::OAUTH_KEY),
            $this->resolveValue(self::OAUTH_SECRET)
        );
    }

    /**
     * @param string $configKey
     * @param string $defaultValue
     * @return string
     */
    private function resolveValue($configKey, $defaultValue = '')
    {
        // Grab command argument constants & values
        $configArguments = (new \ReflectionClass(CommonOptions::class))->getConstants();

        // First look in console arguments
        $argumentName = $configArguments['OPTION_' . $configKey];
        $value = $defaultValue;
        switch (true) {
            // First look in console arguments
            case strlen($this->input->getOption($argumentName)) > 0:
                $value = $this->input->getOption($argumentName);
                break;

            // Next look in .env file
            case array_key_exists($configKey, $this->envFileData):
                $value = $this->envFileData[$configKey];
                break;

            // Finally, look in environment
            case strlen(getenv($configKey)) > 0:
                $value = getenv($configKey);
                break;
        }

        return $value;
    }
}