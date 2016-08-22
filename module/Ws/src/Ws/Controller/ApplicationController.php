<?php

namespace Ws\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

class ApplicationController extends AbstractRestfulController
{
    /**
     * @var DoctrineORMEntityManager
     */
    protected $_em;

    /**
     * Return a instance of Doctrine EntityManager
     * @return array|object|DoctrineORMEntityManager
     */
    public function getEntityManager()
    {
        if (null === $this->_em) {
            $this->_em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->_em;
    }
}
