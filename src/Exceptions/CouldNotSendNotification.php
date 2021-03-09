<?php

namespace Chevgenio\SmsPilot\Exceptions;

use DomainException;
use Exception;

class CouldNotSendNotification extends \Exception
{
    /**
     * Thrown when we're unable to communicate with service.
     *
     * @param DomainException $exception
     *
     * @return static
     */
    public static function serviceRespondedWithAnError(DomainException $exception): self
    {
        return new static(
            "Service responded with an error '{$exception->getCode()}: {$exception->getMessage()}'",
            $exception->getCode(),
            $exception
        );
    }

    /**
     * Thrown when we're unable to communicate with service.
     *
     * @param Exception $exception
     *
     * @return static
     */
    public static function serviceNotAvailable(Exception $exception): self
    {
        return new static(
            "Service not available. Reason: {$exception->getMessage()}",
            $exception->getCode(),
            $exception
        );
    }
}
