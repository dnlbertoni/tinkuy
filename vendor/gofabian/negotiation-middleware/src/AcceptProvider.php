<?php
namespace Gofabian\Negotiation;

use Negotiation\Accept;
use Negotiation\AcceptLanguage;
use Negotiation\AcceptEncoding;
use Negotiation\AcceptCharset;

/**
 * The AcceptProvider offers one instance of \Negotiation\Accept for each
 * HTTP accept header. The result will be null if the dedicated accept header
 * is not configured.
 *
 * @see https://github.com/willdurand/Negotiation
 */
class AcceptProvider {

    private $acceptMediaType;
    private $acceptLanguage;
    private $acceptEncoding;
    private $acceptCharset;

    /**
     * Create a new AcceptProvider that provides the given values.
     *
     * @param $acceptMediaType  Accept|null             the negotiated media type
     * @param $acceptLanguage   AcceptLanguage|null     the negotiated language
     * @param $acceptEncoding   AcceptEncoding|null     the negotiated encoding
     * @param $acceptCharset    AcceptCharset|null      the negotiated charset
     */
    public function __construct(Accept $acceptMediaType = null, AcceptLanguage $acceptLanguage = null,
        AcceptEncoding $acceptEncoding = null, AcceptCharset $acceptCharset = null) {
        $this->acceptMediaType = $acceptMediaType;
        $this->acceptLanguage = $acceptLanguage;
        $this->acceptEncoding = $acceptEncoding;
        $this->acceptCharset = $acceptCharset;
    }


    /**
     * @return \Negotiation\Accept|null             the negotiated media type
     */
    public function getAccept() {
        return $this->acceptMediaType;
    }

    /**
     * @return \Negotiation\AcceptLanguage|null     the negotiated language
     */
    public function getAcceptLanguage() {
        return $this->acceptLanguage;
    }

    /**
     * @return \Negotiation\AcceptCharset|null      the negotiated charset
     */
    public function getAcceptCharset() {
        return $this->acceptCharset;
    }

    /**
     * @return \Negotiation\AcceptEncoding|null     the negotiated encoding
     */
    public function getAcceptEncoding() {
        return $this->acceptEncoding;
    }


    /**
     * @return string|null  the negotiated media type
     */
    public function getMediaType() {
        return $this->toText($this->acceptMediaType);
    }

    /**
     * @return string|null  the negotiated language
     */
    public function getLanguage() {
        return $this->toText($this->acceptLanguage);
    }


    /**
     * @return string|null  the negotiated charset
     */
    public function getCharset() {
        return $this->toText($this->acceptCharset);
    }

    /**
     * @return string|null  the negotiated encoding
     */
    public function getEncoding() {
        return $this->toText($this->acceptEncoding);
    }


    private function toText($accept) {
        return empty($accept) ? '' : $accept->getValue();
    }

}
