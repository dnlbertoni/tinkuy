<?php
namespace Gofabian\Negotiation;

use RuntimeException;
use Psr\Http\Message\ServerRequestInterface;
use Negotiation\AbstractNegotiator;
use Negotiation\BaseAccept;

/**
 * The HeaderNegotiator uses a configuration container to negotiate a PSR-7
 * request object.
 */
class HeaderNegotiator
{

    /**
     * Negotiate the PSR-7 request using the given configuration.
     *
     * If the negotiation fails a NegotiationException will be thrown. If the
     * accept header is empty the default value (with highest priority) will
     * be returned (if required by argument).
     *
     * The return value will never be <code>null</code>.
     *
     * @param request           ServerRequestInterface  the PSR-7 request
     * @param $conf             Configuration           negotiation configuration
     * @param $supplyDefault    bool                    whether default value is supplied
     * @return                  BaseAccept              negotiation result
     * @throws                  NegotiationException    negotiation failed
     */
    public function negotiate(ServerRequestInterface $request, Configuration $conf, $supplyDefault)
    {
        $headerLine = $request->getHeaderLine($conf->getHeaderName());

        if (empty($headerLine)) {
            // accept header is empty
            $result = $this->handleNoInput($conf, $supplyDefault);
        } else {
            // accept header is available
            $result = $this->handleInput($conf, $headerLine);
        }

        if (!is_null($result)) {
            // negotiation result available
            return $result;
        }
        throw new NegotiationException('accept header refused');
    }

    /**
     * Supply default value (if required) because the accept header is empty.
     *
     * @param $conf             Configuration       the negotiation configuration
     * @param $supplyDefault    bool                whether to supply default value
     * @return                  BaseAccept|null     default value
     */
    private function handleNoInput(Configuration $conf, $supplyDefault)
    {
        if ($supplyDefault) {
            return $conf->createDefaultAccept();
        }
        return null;
    }

    /**
     * Negotiate the accept header.
     *
     * @param $conf         Configuration           the negotiation configuration
     * @param $headerLine   string                  the accept header
     * @return              BaseAccept|null         negotiation result
     * @return              NegotiationException    negotiation error
     */
    private function handleInput(Configuration $conf, $headerLine)
    {
        $priorities = $conf->getPriorities();
        $negotiator = $conf->getNegotiator();

        try {
            // returns negotiation result or null
            return $negotiator->getBest($headerLine, $priorities);
        } catch (RuntimeException $e) {
            throw new NegotiationException('negotiator error', $e);
        }
    }

}
