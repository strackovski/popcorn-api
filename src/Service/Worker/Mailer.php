<?php

namespace App\Service\Worker;

use App\Service\Mailer\Send;
use Psr\Log\LoggerInterface;

/**
 * Class Mailer
 *
 * @package App\Service\Worker
 * @author  Vladimir Strackovski <vlado@nv3.eu>
 */
class Mailer implements WorkerInterface
{
    /** @var Send */
    protected $send;

    /** @var LoggerInterface */
    protected $logger;

    /**
     * @param Send            $send
     * @param LoggerInterface $logger
     */
    public function __construct(Send $send, LoggerInterface $logger)
    {
        $this->send = $send;
        $this->logger = $logger;
    }

    /**
     * @param array $args
     *
     * @throws \Twig\Error\Error
     */
    public function execute($args)
    {
        $args = $args[0];
        $response = $this->send->message(
            $args['from'],
            $args['to'],
            $args['subject'],
            $args['template'],
            $args['templateArgs']
        );
        $this->logger->info($response->getMessage());
    }
}
