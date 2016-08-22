<?php

namespace Front\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Front\Resource\WebService;

class ApplicationController extends AbstractRestfulController
{
    private $_ws;

    /**
     * Hook the dispatch so we can instantiate our WebService Class
     * @param \Zend\Mvc\MvcEvent $e
     * @return mixed
     */
    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        $wsParams = $this->getWsParams();
        $this->_ws = new WebService($wsParams);

        return parent::onDispatch($e);
    }

    /**
     * @return WebService class
     */
    public function getWs()
    {
        return $this->_ws;
    }

    /**
     * Get params so we can initiate WebService
     * @return array
     */
    private function getWsParams()
    {
        $uri = $this->getRequest()->getUri();
        $scheme = $uri->getScheme();
        $host = $uri->getHost();
        $domain = sprintf('%s://%s', $scheme, $host);

        $viewHelperManager = $this->getServiceLocator()->get('ViewHelperManager');
        $url = $viewHelperManager->get('url');

        return array(
            'host' => $domain.$url('ws')
        );
    }
}
