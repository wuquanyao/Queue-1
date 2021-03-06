<?php declare(strict_types=1);

/**
 * This file is part of PMG\Queue
 *
 * Copyright (c) PMG <https://www.pmg.com>
 *
 * For full copyright information see the LICENSE file distributed
 * with this source code.
 *
 * @license     http://opensource.org/licenses/Apache-2.0 Apache-2.0
 */

namespace PMG\Queue\Retry;

use PMG\Queue\Envelope;
use PMG\Queue\RetrySpec;

/**
 * Never retry a message.
 *
 * @since   2.0
 */
final class NeverSpec implements RetrySpec
{
    /**
     * {@inheritdoc}
     */
    public function canRetry(Envelope $env) : bool
    {
        return false;
    }

    public function retryDelay(Envelope $env): int
    {
        return 0;
    }
}
