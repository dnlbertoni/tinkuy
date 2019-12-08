<?php
namespace Gofabian\Negotiation;

use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Negotiation\Accept;
use Negotiation\AcceptLanguage;
use Negotiation\AcceptEncoding;
use Negotiation\AcceptCharset;

class NegotiationMiddlewareTest extends PHPUnit_Framework_TestCase
{

    private $request;
    private $response;

    public function setUp()
    {
        $this->request = new TestRequest;
        $this->response = new TestResponse;
    }

    public function testNegotiation()
    {
        $this->prepareHeaders('text/html', 'de', 'gzip', 'utf-8');
        $this->invokeNegotiator(['application/json', 'text/html'], ['de', 'en'], ['gzip'], ['utf-8']);

        $this->assertAccepted('text/html', 'de', 'gzip', 'utf-8');
    }


    public function test406ResponseBecauseOfMediaType()
    {
        $this->prepareHeaders('text/unknown', 'de', 'gzip', 'utf-8');

        $this->doTest406Response(['text/xml'], ['de', 'en'], ['gzip'], ['utf-8']);
    }

    public function test406ResponseBecauseOfLanguage()
    {
        $this->prepareHeaders('text/xml', 'us', 'gzip', 'utf-8');

        $this->doTest406Response(['text/xml'], ['de'], ['gzip'], ['utf-8']);
    }

    public function test406ResponseBecauseOfEncoding()
    {
        $this->prepareHeaders('text/xml', 'de', 'zap', 'utf-8');

        $this->doTest406Response(['text/xml'], ['de'], ['gzip'], ['utf-8']);
    }

    public function test406ResponseBecauseOfCharset()
    {
        $this->prepareHeaders('text/xml', 'de', 'gzip', 'ascii');

        $this->doTest406Response(['text/xml'], ['de'], ['gzip'], ['utf-8']);
    }

    public function test406ResponseWithNoDefaults()
    {
        $this->prepareHeaders('', 'de', 'gzip', 'utf-8');

        $this->doTest406Response(['text/html'], ['de'], ['gzip'], ['utf-8'], false);
    }

    private function doTest406Response($mediaType, $language, $encoding, $charset, $supplyDefaults = true)
    {
        $response = $this->invokeNegotiator($mediaType, $language, $encoding, $charset, $supplyDefaults);

        $this->assertSame(406, $response->getStatusCode());
    }


    public function testDefaultValuesBecauseOfEmptyHeaders()
    {
        $this->prepareHeaders('', '', '', '');
        $this->invokeNegotiator(['text/xml'], ['de'], ['gzip'], ['utf-8']);

        $this->assertAccepted('text/xml', 'de', 'gzip', 'utf-8');
    }

    public function testEmptyPrioritiesAreSkipped()
    {
        $this->prepareHeaders('text/html', 'de', 'gzip', 'utf-8');
        $response = $this->invokeNegotiator(['text/html'], null, null, ['utf-8']);

        $n = $this->request->getAttribute('negotiation');
        $this->assertNull($n->getAcceptLanguage());
        $this->assertNull($n->getAcceptEncoding());
        $this->assertSame(200, $response->getStatusCode());
    }


    private function prepareHeaders($mediaType, $language, $encoding, $charset)
    {
        $this->request = $this->request
            ->withHeader('accept', $mediaType)
            ->withHeader('accept-language', $language)
            ->withHeader('accept-encoding', $encoding)
            ->withHeader('accept-charset', $charset);
    }

    private function invokeNegotiator($mediaType, $language, $encoding, $charset, $supplyDefaults = true)
    {
        $negotiator = new NegotiationMiddleware(
            [
                'accept' => $mediaType,
                'accept-language' => $language,
                'accept-encoding' => $encoding,
                'accept-charset' => $charset
            ],
            $supplyDefaults,
            'negotiation'
        );

        return $negotiator->__invoke(
            $this->request,
            $this->response,
            function($request, $response) {
                return $response;
            }
        );
    }

    private function assertAccepted($mediaType, $language, $encoding, $charset)
    {
        $n = $this->request->getAttribute('negotiation');
        $this->assertSame($mediaType, $n->getMediaType());
        $this->assertSame($language, $n->getLanguage());
        $this->assertSame($encoding, $n->getEncoding());
        $this->assertSame($charset, $n->getCharset());
    }

}
