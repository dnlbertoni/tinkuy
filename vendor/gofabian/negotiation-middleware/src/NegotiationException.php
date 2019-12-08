<?php
namespace Gofabian\Negotiation;

use Exception;
use RuntimeException;

/**
 * The NegotiationException explains that the PSR-7 request does not contain
 * acceptable 'accept' headers.
 */
class NegotiationException extends RuntimeException
{

    /**
     * Create a new NegotiationException.
     *
     * @param $message      string      the error message
     * @param $previous     Exception   the cause
     */
    public function __construct($message, Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

}
