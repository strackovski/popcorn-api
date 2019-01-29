<?php

namespace App\Service\Worker;

/**
 * Interface WorkerInterface
 *
 * @package App\Service\Worker
 * @author  Vladimir Strackovski <vlado@nv3.eu>
 */
interface WorkerInterface
{
    /**
     * @param $args
     *
     * @return mixed
     */
    public function execute($args);
}
