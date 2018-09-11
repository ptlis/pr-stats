<?php

/**
 * @copyright   (c) 2018-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\PrStats\GitServiceClient\Observer;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Observer that outputs messages to the terminal.
 */
final class StdOutNotificationObserver implements ClientObserver
{
    /** @var OutputInterface */
    private $output;


    /**
     * @param OutputInterface $output
     */
    public function __construct(
        OutputInterface $output
    ) {
        $this->output = $output;
    }

    /**
     * @inheritdoc
     */
    public function notify($message)
    {
        $this->output->writeln($message);
    }

    /**
     * @inheritdoc
     */
    public function incrementalNotify(
        $currentItem,
        $totalCount,
        $message,
        $done
    ) {
        $outputPadAmount = (2 * strlen($totalCount)) + 1;

        if (!$done) {
            $counter = str_pad($currentItem . '/' . $totalCount, $outputPadAmount);
            $this->output->writeln($counter . $message);
        } else {
            $counterPlaceholder = str_pad('', 4);
            $this->output->writeln($counterPlaceholder . 'Done: ' . $message);
        }
    }
}