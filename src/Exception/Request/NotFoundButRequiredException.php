<?php

namespace App\Exception\Request;

/**
 * Class NotFoundButRequiredException
 *
 * @package App\Exception\Request
 * @author  Vladimir Strackovski <vlado@nv3.eu>
 */
class NotFoundButRequiredException extends RequestHandlerException
{
    /**
     * @return string
     */
    public function getFormat(): string
    {
        return "%s with %s %s was not found.";
    }

    /**
     * @return string
     */
    public function getDefaultMessage(): string
    {
        return "The object was not found.";
    }
}
