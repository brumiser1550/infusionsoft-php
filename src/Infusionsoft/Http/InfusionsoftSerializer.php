<?php

namespace Infusionsoft\Http;

use fXmlRpc\Exception\ExceptionInterface as fXmlRpcException;

class InfusionsoftSerializer implements SerializerInterface {

	/**
	 * @param string          $method
	 * @param string          $uri
	 * @param array           $params
	 * @param ClientInterface $client
	 * @return mixed
	 * @throws HttpException
	 */
	public function request($method, $uri, $params, ClientInterface $client)
	{
		// Although we are using fXmlRpc to handle the XML-RPC formatting, we
		// can still use Guzzle as our HTTP client which is much more robust.
		try
		{
			$transport = $client->getXmlRpcTransport();

			$client = new fXmlRpcClient($uri, $transport);

			list($response,$headers) = $client->call($method, $params);

			return [$response,$headers];
		}
		catch (fXmlRpcException $e)
		{
			throw new HttpException($e->getMessage(), $e->getCode(), $e);
		}
	}

}