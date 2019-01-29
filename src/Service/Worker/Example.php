<?php

namespace App\Service\Worker;

use Psr\Log\LoggerInterface;

/**
 * Class Example
 *
 * @package App\Service\Worker
 * @author  Vladimir Strackovski <vlado@nv3.eu>
 */
class Example implements WorkerInterface
{
    protected $logger;

    /**
     * Example constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param $args
     */
    public function execute($args)
    {
        $this->logger->info('WORKER EXAMPLE TEST');
        $this->logger->info(print_r($args, true));
    }
}
