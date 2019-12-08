<?php
namespace Gofabian\Negotiation;

use PHPUnit_Framework_TestCase;
use Negotiation\Accept;
use Negotiation\AcceptLanguage;
use Negotiation\AcceptEncoding;
use Negotiation\AcceptCharset;

class AcceptProviderTest extends PHPUnit_Framework_TestCase
{

    const MEDIA_TYPE = 'text/html';
    const LANGUAGE = 'de-DE';
    const ENCODING = 'gzip';
    const CHARSET = 'UTF-8';

    private $accept;
    private $acceptLanguage;
    private $acceptEncoding;
    private $acceptCharset;

    public function setUp()
    {
        $this->accept = new Accept(self::MEDIA_TYPE);
        $this->acceptLanguage = new AcceptLanguage(self::LANGUAGE);
        $this->acceptEncoding = new AcceptEncoding(self::ENCODING);
        $this->acceptCharset = new AcceptCharset(self::CHARSET);
    }

    public function testGetAccepts()
    {
        $p = new AcceptProvider(
            $this->accept,
            $this->acceptLanguage,
            $this->acceptEncoding,
            $this->acceptCharset
        );

        $this->assertSame($this->accept, $p->getAccept());
        $this->assertSame($this->acceptLanguage, $p->getAcceptLanguage());
        $this->assertSame($this->acceptEncoding, $p->getAcceptEncoding());
        $this->assertSame($this->acceptCharset, $p->getAcceptCharset());
    }

    public function testGetValues() {
        $p = new AcceptProvider(
            $this->accept,
            $this->acceptLanguage,
            $this->acceptEncoding,
            $this->acceptCharset
        );

        $this->assertSame(self::MEDIA_TYPE, $p->getMediaType());
        $this->assertSame(self::LANGUAGE, $p->getLanguage());
        $this->assertSame(self::ENCODING, $p->getEncoding());
        $this->assertSame(self::CHARSET, $p->getCharset());
    }

    public function testGetEmptyAccepts() {
        $p = new AcceptProvider(null, null, null, null);

        $this->assertNull($p->getAccept());
        $this->assertNull($p->getAcceptLanguage());
        $this->assertNull($p->getAcceptEncoding());
        $this->assertNull($p->getAcceptCharset());
    }

    public function testEmptyValues() {
        $p = new AcceptProvider(null, null, null, null);

        $this->assertSame('', $p->getMediaType());
        $this->assertSame('', $p->getLanguage());
        $this->assertSame('', $p->getEncoding());
        $this->assertSame('', $p->getCharset());
    }

}
