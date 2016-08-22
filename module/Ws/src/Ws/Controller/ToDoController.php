<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Ws\Controller;

use Ws\Entity\Task;
use Zend\View\Model\JsonModel;

class ToDoController extends ApplicationController
{
    protected $_em;

    /**
     * Hook the onDispatch so we can instantiate our EntityManager
     * @param \Zend\Mvc\MvcEvent $e
     * @return mixed
     */
    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        $this->_em = $this->getEntityManager();
        return parent::onDispatch($e);
    }

    /**
     * Method that will return a list of all available items
     * GET http://localhost/todo_list/public/to-do
     * @return JsonModel
     */
    public function getList()
    {
        $response = array();

        $tasks = $this->_em->getRepository('Ws\Entity\Task')->findBy(array('status' => 1));
        foreach($tasks as $task){
            // Transform the object entity into an array
            $response[] = $task->getArrayCopy();
        }

        return new JsonModel(array('response' => $response));
    }

    /**
     * Method that will find a item
     * GET http://localhost/todo_list/public/to-do/1
     * @param integer $id
     * @return JsonModel
     */
    public function get($id)
    {
        $task = current($this->_em->getRepository('Ws\Entity\Task')->findBy(array('id' => $id, 'status' => 1)));

        if($task){
            // Transform the object entity into an array
            $response = $task->getArrayCopy();
        }else{
            $response = NULL;
        }

        return new JsonModel(array('response' => $response));
    }

    /**
     * Method that will create an item
     * POST http://localhost/todo_list/public/to-do
     * @param array $data
     * @return JsonModel
     */
    public function create($data)
    {
        $task = new Task();

        if(isset($data['text'])){
            $task->setText($data['text']);
        }

        if(isset($data['done'])){
            $task->setDone((bool)$data['done']);
        }

        $task->setCreated(new \DateTime());

        $this->_em->persist($task);
        $this->_em->flush();

        // Transform the object entity into an array
        $response = $task->getArrayCopy();

        return new JsonModel(array('response' => $response));
    }

    /**
     * Method that will update an item
     * PUT http://localhost/todo_list/public/to-do-rest/1
     * @param integer $id
     * @param array $data
     * @return JsonModel
     */
    public function update($id, $data)
    {
        $task = $this->_em->getRepository('Ws\Entity\Task')->find($id);

        if(!is_null($task)){
            if(isset($data['text'])){
                $task->setText($data['text']);
            }

            if(isset($data['done'])){
                $task->setDone((bool)$data['done']);
            }

            $task->setUpdated(new \DateTime());

            $this->_em->persist($task);
            $this->_em->flush();

            // Transform the object entity into an array
            $response = $task->getArrayCopy();
        }else{
            $response = NULL;
        }

        return new JsonModel(array('response' => $response));
    }

    /**
     * Method that will delete an item
     * DELETE http://localhost/todo_list/public/to-do-rest/1
     * @param integer $id
     * @return JsonModel
     */
    public function delete($id)
    {
        $task = $this->_em->getRepository('Ws\Entity\Task')->find($id);

        if(!is_null($task)){

            $task->setUpdated(new \DateTime());
            $task->setStatus(1000);

            $this->_em->persist($task);
            $this->_em->flush();

            $response = TRUE;
        }else{
            $response = NULL;
        }

        return new JsonModel(array('response' => $response));
    }
}