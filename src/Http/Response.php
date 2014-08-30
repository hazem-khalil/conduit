<?php
namespace Phly\Conduit\Http;

use Psr\Http\Message\ResponseInterface as BaseResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Response decorator
 *
 * Adds in write, end, and isComplete from RequestInterface in order
 * to provide a common interface for all PSR HTTP implementations.
 */
class Response implements
    BaseResponseInterface,
    ResponseInterface
{
    /**
     * @var bool
     */
    private $complete = false;

    /**
     * @var BaseResponseInterface
     */
    private $psrResponse;

    /**
     * @param BaseResponseInterface $response
     */
    public function __construct(BaseResponseInterface $response)
    {
        $this->psrResponse = $response;
    }

    /**
     * Return the original PSR response object
     *
     * @return BaseResponseInterface
     */
    public function getOriginalResponse()
    {
        return $this->psrResponse;
    }

    /**
     * Write data to the response body
     *
     * Proxies to the underlying stream and writes the provided data to it.
     *
     * @param string $data
     */
    public function write($data)
    {
        if ($this->complete) {
            return;
        }

        $this->getBody()->write($data);
    }

    /**
     * Mark the response as complete
     *
     * A completed response should no longer allow manipulation of either
     * headers or the content body.
     *
     * If $data is passed, that data should be written to the response body
     * prior to marking the response as complete.
     *
     * @param string $data
     */
    public function end($data = null)
    {
        if ($this->complete) {
            return;
        }

        if ($data) {
            $this->write($data);
        }

        $this->complete = true;
    }

    /**
     * Indicate whether or not the response is complete.
     *
     * I.e., if end() has previously been called.
     *
     * @return bool
     */
    public function isComplete()
    {
        return $this->complete;
    }

    /**
     * Proxy to ResponseInterface::
     *
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion()
    {
        return $this->psrResponse->getProtocolVersion();
    }

    /**
     * Proxy to ResponseInterface::getBody()
     *
     * @return StreamInterface|null Returns the body, or null if not set.
     */
    public function getBody()
    {
        return $this->psrResponse->getBody();
    }

    /**
     * Proxy to ResponseInterface::setBody()
     *
     * @param StreamInterface|null $body Body.
     * @throws \InvalidArgumentException When the body is not valid.
     */
    public function setBody(StreamInterface $body = null)
    {
        if ($this->complete) {
            return;
        }

        return $this->psrResponse->setBody($body);
    }

    /**
     * Proxy to ResponseInterface::getHeaders()
     *
     * @return array Returns an associative array of the message's headers.
     */
    public function getHeaders()
    {
        return $this->psrResponse->getHeaders();
    }

    /**
     * Proxy to ResponseInterface::hasHeader()
     *
     * @param string $header Case-insensitive header name.
     * @return bool Returns true if any header names match the given header
     *     name using a case-insensitive string comparison. Returns false if
     *     no matching header name is found in the message.
     */
    public function hasHeader($header)
    {
        return $this->psrResponse->hasHeader($header);
    }

    /**
     * Proxy to ResponseInterface::getHeader()
     *
     * @param string $header Case-insensitive header name.
     * @return string
     */
    public function getHeader($header)
    {
        return $this->psrResponse->getHeader($header);
    }

    /**
     * Proxy to ResponseInterface::getHeaderAsArray()
     *
     * @param string $header Case-insensitive header name.
     * @return string[]
     */
    public function getHeaderAsArray($header)
    {
        return $this->psrResponse->getHeaderAsArray($header);
    }

    /**
     * Proxy to ResponseInterface::setHeader()
     *
     * @param string $header Header name
     * @param string|string[] $value  Header value(s)
     */
    public function setHeader($header, $value)
    {
        if ($this->complete) {
            return;
        }

        return $this->psrResponse->setHeader($header, $value);
    }

    /**
     * Proxy to ResponseInterface::setHeaders()
     *
     * @param array $headers Headers to set.
     */
    public function setHeaders(array $headers)
    {
        if ($this->complete) {
            return;
        }

        return $this->psrResponse->setHeaders($headers);
    }

    /**
     * Proxy to ResponseInterface::addHeader()
     *
     * @param string $header Header name to add
     * @param string $value  Value of the header
     */
    public function addHeader($header, $value)
    {
        if ($this->complete) {
            return;
        }

        return $this->psrResponse->addHeader($header, $value);
    }

    /**
     * Proxy to ResponseInterface::addHeaders()
     *
     * @param array $headers Associative array of headers to add to the message
     */
    public function addHeaders(array $headers)
    {
        if ($this->complete) {
            return;
        }

        return $this->psrResponse->addHeaders($headers);
    }

    /**
     * Proxy to ResponseInterface::removeHeader()
     *
     * @param string $header HTTP header to remove
     */
    public function removeHeader($header)
    {
        if ($this->complete) {
            return;
        }

        return $this->psrResponse->removeHeader($header);
    }

    /**
     * Proxy to ResponseInterface::getStatusCode()
     *
     * @return integer Status code.
     */
    public function getStatusCode()
    {
        return $this->psrResponse->getStatusCode();
    }

    /**
     * Proxy to ResponseInterface::setStatusCode()
     *
     * @param integer $code The 3-digit integer result code to set.
     */
    public function setStatusCode($code)
    {
        if ($this->complete) {
            return;
        }

        return $this->psrResponse->setStatusCode($code);
    }

    /**
     * Proxy to ResponseInterface::getReasonPhrase()
     *
     * @return string|null Reason phrase, or null if unknown.
     */
    public function getReasonPhrase()
    {
        return $this->psrResponse->getReasonPhrase();
    }

    /**
     * Proxy to ResponseInterface::setReasonPhrase()
     *
     * @param string $phrase The Reason-Phrase to set.
     */
    public function setReasonPhrase($phrase)
    {
        if ($this->complete) {
            return;
        }

        return $this->psrResponse->setReasonPhrase($phrase);
    }
}