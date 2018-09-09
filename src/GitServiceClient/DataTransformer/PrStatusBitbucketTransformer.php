<?php

/**
 * @copyright   (c) 2018-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\PrStats\GitServiceClient\DataTransformer;

use ptlis\PrStats\DTO\PullRequest;

/**
 * Transforms between the Bitbucket's & the internal representations of PR statuses.
 */
final class PrStatusBitbucketTransformer implements DataTransformer
{
    /**
     * @inheritdoc
     */
    public function transform($internalPrStatus)
    {
        switch ($internalPrStatus) {
            case PullRequest::PR_STATUS_OPEN:
                $servicePrStatus = 'OPEN';
                break;
            case PullRequest::PR_STATUS_MERGED:
                $servicePrStatus = 'MERGED';
                break;
            case PullRequest::PR_STATUS_DECLINED:
                $servicePrStatus = 'DECLINED';
                break;
            default:
                throw new \RuntimeException('Invalid internal PR status "' . $internalPrStatus . '" encountered');
        }
        return $servicePrStatus;
    }

    /**
     * @inheritdoc
     */
    public function reverseTransform($servicePrStatus)
    {
        switch ($servicePrStatus) {
            case 'OPEN':
                $servicePrStatus = PullRequest::PR_STATUS_OPEN;
                break;
            case 'MERGED':
                $servicePrStatus = PullRequest::PR_STATUS_MERGED;
                break;
            case 'DECLINED':
                $servicePrStatus = PullRequest::PR_STATUS_DECLINED;
                break;
            default:
                throw new \RuntimeException('Invalid service PR status "' . $servicePrStatus . '" encountered');
        }
        return $servicePrStatus;
    }
}