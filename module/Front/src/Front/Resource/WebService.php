<?php

namespace Front\Resource;

use Zend\Debug\Debug;
use Zend\Http\Request;
use Zend\Http\Client;
use Zend\Stdlib\Parameters;

class WebService
{
    private $_request;

    private $_wsHost;

    public function __construct($params)
    {
        $this->_wsHost = $params['host'].'/';

        $this->_request = new Request();
        $this->_request->getHeaders()->addHeaders(array(
            'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8'
        ));

        return $this;
    }

    public function get($method)
    {
        $this->_request->setUri($this->_wsHost.$method);
        $this->_request->setMethod('GET');

        $response = $this->getResponse();

        return $response;
    }

    public function put($method, $params = array())
    {
        $this->_request->setUri($this->_wsHost.$method);
        $this->_request->setMethod('PUT');

        if(!empty($params)){
            $this->_request->setPost(new Parameters($params));
        }

        $response = $this->getResponse();

        return $response;
    }

    public function post($method, $params = array())
    {
        $this->_request->setUri($this->_wsHost.$method);
        $this->_request->setMethod('POST');

        if(!empty($params)){
            $this->_request->setPost(new Parameters($params));
        }

        $response = $this->getResponse();

        return $response;
    }

    public function delete($method)
    {
        $this->_request->setUri($this->_wsHost.$method);
        $this->_request->setMethod('DELETE');

        $response = $this->getResponse();

        return $response;
    }

    public function getResponse()
    {
        $client = new Client();
        $response = $client->dispatch($this->_request);
        $data = json_decode($response->getBody(), true);

        return $data;
    }
}
