<?php

namespace App\Exception\Request;

/**
 * Class MultipleAssociationException
 *
 * @package App\Exception\Request
 * @author  Vladimir Strackovski <vlado@nv3.eu>
 */
class MultipleAssociationException extends RequestHandlerException
{
    /**
     * @return string
     */
    public function getFormat(): string
    {
        return "%s %s already exists in %s %s.";
    }

    /**
     * @return string
     */
    public function getDefaultMessage(): string
    {
        return "This association already exists.";
    }
}
