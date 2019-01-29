<?php

namespace App\Service\Queue;

use SidekiqJob\Client;

/**
 * Trait QueueAwareServiceInterface
 *
 * @package      App\Service\Queue
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
trait QueueAwareServiceTrait
{
    /** @var Client */
    protected $queue;

    /**
     * Push task to queue
     *
     * @param        $class
     * @param array  $args
     * @param bool   $retry
     * @param string $queue
     *
     * @return string
     */
    public function pushToQueue($class, $args = [], $retry = true, $queue = Client::QUEUE): string
    {
        return $this->queue->push($class, $args, $retry, $queue);
    }
}
