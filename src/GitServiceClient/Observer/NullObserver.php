<?php

/**
 * @copyright   (c) 2018-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\PrStats\GitServiceClient\Observer;

/**
 * Observer that does nothing with messages.
 */
final class NullObserver implements ClientObserver
{
    /**
     * @inheritdoc
     */
    public function notify($message)
    {
        // Do nothing
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
        // Do nothing
    }
}