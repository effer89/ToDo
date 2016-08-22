<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Front\Controller;

use Front\Controller\ApplicationController;
use Zend\Debug\Debug;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class HomeController extends ApplicationController
{
    /*
     * First Page
     */
    public function indexAction()
    {
        return new ViewModel();
    }

    /*
     * Get all tasks
     */
    public function getTasksAction()
    {
        $tasks = $this->getWs()->get('to-do');
        return new JsonModel($tasks);
    }

    /*
     * Save changes on a task
     */
    public function saveTaskAction()
    {
        $request = $this->getRequest();

        $task = null;

        if($request->isPost()) {
            $data = $request->getPost();

            $id = (int)$data['id'];

            if($id > 0){
                $task = $this->getWs()->put('to-do/'.$data['id'], array(
                    'text' => $data['description']
                ));
            }else{
                $task = $this->getWs()->post('to-do', array(
                    'text' => $data['description'],
                    'done' => false,
                ));
            }
        }

        return new JsonModel($task);
    }

    /*
     * Change the status of a task
     */
    public function setTaskDoneAction()
    {
        $request = $this->getRequest();

        $task = null;

        if($request->isPost()) {
            $data = $request->getPost();

            $task = $this->getWs()->put('to-do/'.$data['id'], array(
                'done' => (bool)$data['done'],
            ));
        }

        return new JsonModel($task);
    }

    /*
     * Delete a task
     */
    public function excludeTaskAction()
    {
        $request = $this->getRequest();

        $task = null;

        if($request->isPost()) {
            $data = $request->getPost();

            $task = $this->getWs()->delete('to-do/'.$data['id']);
        }

        return new JsonModel($task);
    }
}
