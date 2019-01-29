<?php

namespace App\Exception\Request;

use Throwable;

/**
 * Class RequestHandlerException
 *
 * @package App\Exception\Request
 * @author  Vladimir Strackovski <vlado@nv3.eu>
 */
abstract class RequestHandlerException extends \Exception
{
    protected $guruId;

    /**
     * RequestHandlerException constructor.
     *
     * @param array          $messageArgs
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(array $messageArgs, $code = 100, Throwable $previous = null)
    {
        $args = [];

        foreach ($messageArgs as $messageArg) {
            if (strpos($messageArg, "\\") !== false) {
                $args[] = substr($messageArg, strrpos($messageArg, "\\") + 1);
            } else {
                $args[] = $messageArg;
            }
        }

        $message = (substr_count($this->getFormat(), "%s") === count($args)) ? vsprintf(
            $this->getFormat(),
            $args
        ) : $this->getDefaultMessage();

        parent::__construct($message, $code, $previous);
        $this->guruId = uniqid(time(), true);
    }

    /**
     * @return string
     */
    abstract public function getFormat(): string;

    /**
     * @return string
     */
    abstract public function getDefaultMessage(): string;

    /**
     * @return string
     */
    public function getGuruId()
    {
        return $this->guruId;
    }
}
