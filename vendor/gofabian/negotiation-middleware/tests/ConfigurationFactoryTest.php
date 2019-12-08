<?php
namespace Gofabian\Negotiation;

use InvalidArgumentException;
use PHPUnit_Framework_TestCase;
use Negotiation\Negotiator;
use Negotiation\LanguageNegotiator;
use Negotiation\EncodingNegotiator;
use Negotiation\CharsetNegotiator;
use Negotiation\Accept;
use Negotiation\AcceptLanguage;
use Negotiation\AcceptEncoding;
use Negotiation\AcceptCharset;

class ConfigurationFactoryTest extends PHPUnit_Framework_TestCase
{

    private $configurationFactory;

    public function setUp() {
        $this->configurationFactory = new ConfigurationFactory;
    }

    public function testCreateMediaTypeConfiguration()
    {
        $priorities = ['text/html'];
        $c = $this->configurationFactory->create('accept', $priorities);

        $this->assertSame('accept', $c->getHeaderName());
        $this->assertInstanceOf('\Negotiation\Negotiator', $c->getNegotiator());
        $this->assertSame($priorities, $c->getPriorities());
        $this->assertInstanceOf('\Negotiation\Accept', $c->createDefaultAccept());
    }

    public function testCreateLanguageConfiguration()
    {
        $c = $this->configurationFactory->create('accept-language', ['en']);

        $this->assertInstanceOf('\Negotiation\LanguageNegotiator', $c->getNegotiator());
        $this->assertInstanceOf('\Negotiation\AcceptLanguage', $c->createDefaultAccept());
    }

    public function testCreateEncodingConfiguration()
    {
        $c = $this->configurationFactory->create('accept-encoding', ['gzip']);

        $this->assertInstanceOf('\Negotiation\EncodingNegotiator', $c->getNegotiator());
        $this->assertInstanceOf('\Negotiation\AcceptEncoding', $c->createDefaultAccept());
    }

    public function testCreateCharsetConfiguration()
    {
        $c = $this->configurationFactory->create('accept-charset', ['utf-8']);

        $this->assertInstanceOf('\Negotiation\CharsetNegotiator', $c->getNegotiator());
        $this->assertInstanceOf('\Negotiation\AcceptCharset', $c->createDefaultAccept());
    }

    /**
     * @expectedException   \InvalidArgumentException
     */
    public function testCreateInvalidConfiguration()
    {
        $c = $this->configurationFactory->create('accept-unknown123', ['prio']);
    }

}
