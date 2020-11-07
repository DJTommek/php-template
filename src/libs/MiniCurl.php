<?php declare(strict_types=1);

namespace App;

/**
 * Simple CURL wrapper for most used requests as GET and POST requests.
 */
class MiniCurl
{
	const RESPONSE_HEADER_CONTENT_TYPE = 'content-type';

	const CONTENT_TYPE_APPLICATION_JSON = 'application/json';

	/** @var string */
	private $url;
	/** @var array<int,mixed> $curlOptions */
	private $curlOptions = [
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HEADER => true,
		CURLOPT_CONNECTTIMEOUT => 10,
		CURLOPT_TIMEOUT => 10,
	];
	/** @var string */
	private $responseRaw;
	/** @var array<string,string> Array of response headers with lowercased keys */
	private $responseHeaders = [];
	/** @var string|array<mixed,mixed> */
	private $responseBody;
	/** @var bool Was request already executed? */
	private $wasRequested = false;

	/**
	 * @param ?array<string,mixed> $getParams
	 */
	public function __construct(string $url, ?array $getParams = null)
	{
		if (is_array($getParams)) {
			$url .= '?' . http_build_query($getParams);
		}
		$this->url = $url;
	}

	/**
	 * @param array<int,mixed> $curlOptions
	 * @throws \Exception|\JsonException
	 */
	public function run(array $curlOptions = []): self
	{
		$this->curlOptions = $this->curlOptions + $curlOptions;
		$curl = curl_init($this->url);
		if ($curl === false) {
			throw new \Exception('CURL can\'t be initialited.');
		}
		curl_setopt_array($curl, $this->curlOptions);
		/** @var string|false $curlResponse */
		$curlResponse = curl_exec($curl);
		if ($curlResponse === false) {
			$curlErrno = curl_errno($curl);
			throw new \Exception(sprintf('CURL request error %s: "%s"', $curlErrno, curl_error($curl)));
		}
		list($responseHeaders, $responseBody) = explode("\r\n\r\n", $curlResponse, 2);
		$this->wasRequested = true;
		$this->responseHeaders = $this->parseRawHeaders($responseHeaders);
		if ($this->getResponseHeader(self::RESPONSE_HEADER_CONTENT_TYPE) === self::CONTENT_TYPE_APPLICATION_JSON) {
			$responseBody = \json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);
		}
		$this->responseBody = $responseBody;
		$this->responseRaw = $curlResponse;
		return $this;
	}

	/**
	 * @param string|array<mixed,mixed> $params POST data
	 * Use string for raw body but dont forget set correct HTTP header, for example 'Content-Type: text/plain'
	 * Use array for send as form, then default header is used. See https://ec.haxx.se/http/http-post#content-type
	 * @param array<int,mixed> $curlOptions
	 */
	public function runPost($params = null, array $curlOptions = []): self
	{
		$curlOptions[CURLOPT_POST] = true;
		$curlOptions[CURLOPT_POSTFIELDS] = $params;
		$this->run($curlOptions);
		return $this;
	}

	/** @return string */
	public function getResponseRaw()
	{
		if ($this->wasRequested === false) {
			throw new \Exception('Request was not yet performed.');
		}
		return $this->responseRaw;
	}

	/** @return array<mixed,mixed> */
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

	/** @return string|array<mixed,mixed> */
	public function getResponseBody()
	{
		if ($this->wasRequested === false) {
			throw new \Exception('Request was not yet performed.');
		}
		return $this->responseBody;
	}

	/**
	 * @param string $headerStr
	 * @return array<string,string>
	 */
	private function parseRawHeaders(string $headerStr): array
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
