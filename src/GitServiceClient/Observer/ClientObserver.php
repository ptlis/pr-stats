<?php

/**
 * @copyright   (c) 2018-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\PrStats\GitServiceClient\Observer;

/**
 * Observer for git service clients.
 */
interface ClientObserver
{
    /**
     * Notify of status change.
     *
     * @param $message
     */
    public function notify($message);

    /**
     * @param int $currentItem
     * @param int $totalCount
     * @param string $message
     * @param int $done
     */
    public function incrementalNotify(
        $currentItem,
        $totalCount,
        $message,
        $done
    );
}