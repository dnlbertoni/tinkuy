<?php
namespace Gofabian\Negotiation;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Negotiation\AbstractNegotiator;
use Negotiation\BaseAccept;

/**
 * The NegotiationMiddleware negotiates media type, language, encoding and
 * charset of a PSR-7 request. The negotiated values will be stored in an
 * attribute of the PSR-7 request. If the negotiation fails the response will
 * contain status 406 "Not Acceptable".
 *
 * @see http://www.php-fig.org/psr/psr-7/
 * @see https://github.com/willdurand/Negotiation
 */
class NegotiationMiddleware
{
    private $configurationFactory;
    private $headerNegotiator;
    private $supplyDefaults;
    private $attributeName;

    private $mediaTypeConfiguration;
    private $languageConfiguration;
    private $encodingConfiguration;
    private $charsetConfiguration;

    /**
     * Create a new negotiation middleware.
     *
     * @param $priorities       array   lists of accepted values
     * @param $supplyDefaults   bool    whether default values are supplied
     * @param $attributeName    string  where to store the negotiation result
     */
    public function __construct(
        array $priorities,
        $supplyDefaults = true,
        $attributeName = 'negotiation'
    ) {
        $this->configurationFactory = new ConfigurationFactory;
        $this->headerNegotiator = new HeaderNegotiator;
        $this->supplyDefaults = $supplyDefaults;
        $this->attributeName = $attributeName;

        $this->mediaTypeConfiguration = $this->createConfiguration($priorities, 'accept');
        $this->languageConfiguration = $this->createConfiguration($priorities, 'accept-language');
        $this->encodingConfiguration = $this->createConfiguration($priorities, 'accept-encoding');
        $this->charsetConfiguration = $this->createConfiguration($priorities, 'accept-charset');
    }

    private function createConfiguration($allPriorities, $headerName)
    {
        if (!empty($allPriorities[$headerName])) {
            $priorities = $allPriorities[$headerName];
            return $this->configurationFactory->create($headerName, $priorities);
        }
        return null;
    }


    /**
     * Negotiate the 'accept' headers of the given PSR-7 request. Attach the
     * negotiation result to the request or respond with 406 "Not Acceptable".
     *
     * @param $request      ServerRequestInterface  PSR-7 request (with accept headers)
     * @param $response     ResponseInterface       PSR-7 response
     * @param $next         callable                the next middleware
     * @return              ResponseInterface       PSR-7 response
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        try {
            $acceptProvider = $this->negotiateRequest($request);
        } catch (NegotiationException $e) {
            return $response->withStatus(406);
        }

        $request = $request->withAttribute($this->attributeName, $acceptProvider);
        return $next($request, $response);
    }

    /**
     * Negotiate the PSR-7 request.
     *
     * @param $request  ServerRequestInterface  the PSR-7 request
     * @return          AcceptProvider          the negotiation result
     * @throws          NegotiationException    negotiation failed
     */
    private function negotiateRequest(ServerRequestInterface $request)
    {
        $mediaType = $this->negotiateHeader($request, $this->mediaTypeConfiguration);
        $language = $this->negotiateHeader($request, $this->languageConfiguration);
        $encoding = $this->negotiateHeader($request, $this->encodingConfiguration);
        $charset = $this->negotiateHeader($request, $this->charsetConfiguration);

        return new AcceptProvider($mediaType, $language, $encoding, $charset);
    }

    /**
     * Negotiate the header configured in <code>$conf</code>.
     *
     * Returns <code>null</code> if the configuration is <code>null</code>.
     *
     * @param $request  ServerRequestInterface  the PSR-7 request
     * @param $conf     Configuration|null      the header configuration
     * @return          BaseAccept|null         the negotiation result
     * @throws          NegotiationException    negotiation failed
     */
    private function negotiateHeader(ServerRequestInterface $request, Configuration $conf = null)
    {
        if (is_null($conf)) {
            // no negotiation configuration
            return null;
        }
        return $this->headerNegotiator->negotiate($request, $conf, $this->supplyDefaults);
    }

}
