<?php
namespace Gofabian\Negotiation;

use InvalidArgumentException;
use Negotiation\AbstractNegotiator;
use Negotiation\BaseAccept;

/**
 * The class Configuration contains a set of negotiation attributes. One
 * attribute set is sufficient for the negotiation of one particular accept
 * header (e. g. 'accept' or 'accept-language').
 */
class Configuration
{
    private $headerName;
    private $priorities;
    private $negotiator;
    private $acceptFactory;

    /**
     * @param $headerName   string  the accept header name
     */
    public function setHeaderName($headerName)
    {
        $this->headerName = $headerName;
    }

    /**
     * @param $priorities   array[string]   list of accepted values sorted
     *                                      by priority (first has highest)
     * @throws InvalidArgumentException     argument is empty
     */
    public function setPriorities(array $priorities)
    {
        if (empty($priorities)) {
            throw new InvalidArgumentException('List of priorities is empty');
        }
        $this->priorities = $priorities;
    }

    /**
     * @param $negotiator   AbstractNegotiator  the negotiator utility
     */
    public function setNegotiator(AbstractNegotiator $negotiator)
    {
        $this->negotiator = $negotiator;
    }

    /**
     * @param $acceptFactory    callable    creator of accept instances
     */
    public function setAcceptFactory(callable $acceptFactory)
    {
        $this->acceptFactory = $acceptFactory;
    }


    /**
     * @return string   the header name, e. g. 'accept', 'accept-language', ...
     */
    public function getHeaderName()
    {
        return $this->headerName;
    }

    /**
     * @return array[string]    list of accepted values sorted by priority
     *                          (first has highest)
     */
    public function getPriorities()
    {
        return $this->priorities;
    }

    /**
     * @return AbstractNegotiator   the negotiator utility
     *
     * @see https://github.com/willdurand/Negotiation
     */
    public function getNegotiator()
    {
        return $this->negotiator;
    }

    /**
     * @return BaseAccept   accept instance of negotiation utility
     *
     * @see https://github.com/willdurand/Negotiation
     */
    public function createDefaultAccept()
    {
        $defaultValue = reset($this->priorities);
        $acceptFactory = $this->acceptFactory;
        return $acceptFactory($defaultValue);
    }

}
