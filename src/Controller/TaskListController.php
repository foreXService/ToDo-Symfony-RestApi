<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TaskList;
use App\Repository\TaskListRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class TaskListController extends AbstractFOSRestController
{
    private TaskListRepository $taskListRepository;
    private EntityManagerInterface $entityManagerInterface;
    public function __construct(TaskListRepository $taskListRepository, EntityManagerInterface $entityManagerInterface)
    {
        $this->taskListRepository = $taskListRepository;
        $this->entityManagerInterface = $entityManagerInterface;
    }

    
    /**
     * GET all task list
     * 
     * return App\Entity\TaskList[]
     */
    public function getListsAction():\FOS\RestBundle\View\View
    {
        // $userId = $this->getUser()->getId();
        $lists = $this->taskListRepository->findAll();
        return $this->view($lists,Response::HTTP_OK);
    }

    /**
     * GET single task list
     * 
     * return App\Entity\TaskList
     */
    public function getListAction(int $id):\FOS\RestBundle\View\View
    {
        // $userId = $this->getUser()->getId();
        $list = $this->taskListRepository->findOneBy(['id' => $id]);
        if (empty($list)){
            return $this->ViewError(Response::HTTP_NOT_FOUND,'List not found');
        }
        return $this->view($list,Response::HTTP_OK);
    }
    
    /**
     * POST create new list
     * 
     * @Rest\RequestParam(name="title",description="Title of the list", nullable=false)
     * @param   ParamFetcher $paramFetcher
     * return App\Entity\TaskList
     */
    public function postListsAction(ParamFetcher $paramFetcher):\FOS\RestBundle\View\View
    {
        // $userId = $this->getUser()->getId();
        $title = trim($paramFetcher->get('title'));
        if (empty($title)) {
            return $this->ViewError(Response::HTTP_BAD_REQUEST,'Title can not be null');
        }
        $list = new TaskList();
        $list->setTitle($title);
        $this->entityManagerInterface->persist($list);
        $this->entityManagerInterface->flush();
        return $this->view($list,Response::HTTP_CREATED);
    }

    /**
     * DELETE delete one list
     * 
     * @param  id $id
     * return []
     */
    public function deleteListsAction(int $id):\FOS\RestBundle\View\View
    {
        // $userId = $this->getUser()->getId();
        $list = $this->taskListRepository->findOneBy(['id' => $id]);
        if (empty($list)) {
            return $this->ViewError(Response::HTTP_NOT_FOUND,'List not found');
        }
        $this->entityManagerInterface->remove($list);
        $this->entityManagerInterface->flush();
        return $this->view([],Response::HTTP_OK);
    }

    /**
     * PATCH edit title list 
     * 
     * @Rest\RequestParam(name="title",description="Title of the list", nullable=false)
     * @param  id $id
     * return App\Entity\TaskList
     */
    public function patchListsTitleAction(ParamFetcher $paramFetcher,int $id):\FOS\RestBundle\View\View
    {
        // $userId = $this->getUser()->getId();
        $list = $this->taskListRepository->findOneBy(['id' => $id]);
        if (empty($list)) {
            return $this->ViewError(Response::HTTP_NOT_FOUND,'List not found');
        }
        $title = trim($paramFetcher->get('title'));
        if (empty($title)) {
            return $this->ViewError(Response::HTTP_BAD_REQUEST,'Title can not be null');
        }
        $list->setTitle($title);
        $this->entityManagerInterface->persist($list);
        $this->entityManagerInterface->flush();
        return $this->view($list,Response::HTTP_OK);
    }

    /**
     * GET tasks in list
     * 
     * return App\Entity\Task[]
     */
    public function getListsTasksAction(int $id):\FOS\RestBundle\View\View
    {
        // $userId = $this->getUser()->getId();
        $list = $this->taskListRepository->findOneBy(['id' => $id]);
        if (empty($list)){
            return $this->ViewError(Response::HTTP_NOT_FOUND,'List not found');
        }
        return $this->view($list->getTasks(),Response::HTTP_OK);
    }
    
    /**
     * POST create new task
     * 
     * @Rest\RequestParam(name="title",description="Title of the task", nullable=false)
     * @param   ParamFetcher $paramFetcher
     * @param  id $id
     * return App\Entity\Task
     */
    public function postListsTasksAction(ParamFetcher $paramFetcher,int $id):\FOS\RestBundle\View\View
    {
        // $userId = $this->getUser()->getId();
        $list = $this->taskListRepository->findOneBy(['id' => $id]);
        if (empty($list)){
            return $this->ViewError(Response::HTTP_NOT_FOUND,'List not found');
        }
        $title = trim($paramFetcher->get('title'));
        if (empty($title)) {
            return $this->ViewError(Response::HTTP_BAD_REQUEST,'Title can not be null');
        }
        $task = new Task();
        $list->addTask($task);
        $task->setTitle($title);
        $task->setList($list);
        $this->entityManagerInterface->persist($task);
        $this->entityManagerInterface->flush();
        return $this->view($task,Response::HTTP_CREATED);
    }

    private function ViewError(int $statusCode, string $message):\FOS\RestBundle\View\View
    {
        return $this->view([
            'code'=> $statusCode,
            'message'=>$message
        ],$statusCode);
    }
}
