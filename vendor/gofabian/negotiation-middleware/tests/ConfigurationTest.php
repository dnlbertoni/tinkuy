<?php
namespace Gofabian\Negotiation;

use InvalidArgumentException;
use PHPUnit_Framework_TestCase;
use Negotiation\LanguageNegotiator;
use Negotiation\Negotiator;
use Negotiation\AcceptCharset;

class ConfigurationTest extends PHPUnit_Framework_TestCase
{

    function testGetters()
    {
        $headerName = 'the name';
        $negotiator = new LanguageNegotiator;
        $priorities = ['one', 'two'];
        $acceptFactory = function(){};
        $c = new Configuration;
        $c->setHeaderName($headerName);
        $c->setPriorities($priorities);
        $c->setNegotiator($negotiator);
        $c->setAcceptFactory($acceptFactory);

        $this->assertSame($headerName, $c->getHeaderName());
        $this->assertSame($negotiator, $c->getNegotiator());
        $this->assertSame($priorities, $c->getPriorities());
    }

    function testDefaultAccept()
    {
        $c = new Configuration;
        $c->setPriorities(['default', 'another', 'third']);
        $c->setAcceptFactory(function($v) {
            return new AcceptCharset($v);
        });

        $defaultAccept = $c->createDefaultAccept();
        $this->assertInstanceOf('\Negotiation\AcceptCharset', $defaultAccept);
        $this->assertSame('default', $defaultAccept->getValue());
    }

    /**
     * @expectedException   \InvalidArgumentException
     */
    function testEmptyPriorities()
    {
        $c = new Configuration();
        $c->setPriorities([]);
    }

}
