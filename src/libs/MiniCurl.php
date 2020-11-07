<?php declare(strict_types=1);

namespace App;

/**
 * Simple CURL wrapper for most used requests as GET and POST requests.
 */
class MiniCurl
{
	const RESPONSE_HEADER_CONTENT_TYPE = 'content-type';

	const CONTENT_TYPE_APPLICATION_JSON = 'application/json';

	private $url;
	private $curlOptions = [
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HEADER => true,
		CURLOPT_CONNECTTIMEOUT => 10,
		CURLOPT_TIMEOUT => 10,
	];
	/** @var string|false */
	private $responseRaw;
	/** @var array Array of response headers with lowercased keys */
	private $responseHeaders = [];
	/** @var string|array */
	private $responseBody;
	/** @var bool Was request already executed? */
	private $wasRequested = false;

	public function __construct(string $url, ?array $getParams = null)
	{
		if (is_array($getParams)) {
			$url .= '?' . http_build_query($getParams);
		}
		$this->url = $url;
	}

	/** @throws \Exception|\JsonException */
	public function run(array $curlOptions = []): self
	{
		$this->curlOptions = $this->curlOptions + $curlOptions;
		$curl = curl_init($this->url);
		if ($curl === false) {
			throw new \Exception('CURL can\'t be initialited.');
		}
		curl_setopt_array($curl, $this->curlOptions);
		$this->responseRaw = curl_exec($curl);
		if ($this->responseRaw === false) {
			$curlErrno = curl_errno($curl);
			throw new \Exception(sprintf('CURL request error %s: "%s"', $curlErrno, curl_error($curl)));
		}
		list($responseHeaders, $this->responseBody) = explode("\r\n\r\n", $this->responseRaw, 2);
		$this->responseHeaders = $this->parseRawHeaders($responseHeaders);
		$this->wasRequested = true;
		if ($this->getResponseHeader(self::RESPONSE_HEADER_CONTENT_TYPE) === self::CONTENT_TYPE_APPLICATION_JSON) {
			$this->responseBody = \json_decode($this->responseBody, true, 512, JSON_THROW_ON_ERROR);
		}
		return $this;
	}

	public function runPost($params = null, array $curlOptions = []): self
	{
		$curlOptions[CURLOPT_POST] = true;
		$curlOptions[CURLOPT_POSTFIELDS] = $params;
		$this->run($curlOptions);
		return $this;
	}

	/** @return string|array */
	public function getResponseRaw()
	{
		if ($this->wasRequested === false) {
			throw new \Exception('Request was not yet performed.');
		}
		return $this->responseRaw;
	}

	public function getResponseHeaders(): array
	{
		if ($this->wasRequested === false) {
			throw new \Exception('Request was not yet performed.');
		}
		return $this->responseHeaders;
	}

	public function getResponseHeader(string $name): ?string
	{
		$headers = $this->getResponseHeaders();
		return $headers[mb_strtolower($name)] ?? null;
	}

	/** @return string|array */
	public function getResponseBody()
	{
		if ($this->wasRequested === false) {
			throw new \Exception('Request was not yet performed.');
		}
		return $this->responseBody;
	}

	private function parseRawHeaders(string $headerStr)
	{
		$headers = explode("\r\n", $headerStr);
		array_shift($headers);  // HTTP/1.1 200 OK
		$result = [];
		foreach ($headers as $header) {
			list($headerName, $headerValue) = explode(': ', $header, 2);
			$result[mb_strtolower($headerName)] = $headerValue;
		}
		return $result;
	}
}
