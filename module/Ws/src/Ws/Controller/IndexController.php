<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Ws\Controller;

use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends ApplicationController
{
    protected $_em;

    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        $this->_em = $this->getEntityManager();

        return parent::onDispatch($e);
    }

    public function indexAction()
    {
        exit;
    }
}