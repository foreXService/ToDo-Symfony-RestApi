<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class TaskController extends AbstractFOSRestController
{
    private TaskRepository $taskRepository;
    private EntityManagerInterface $entityManagerInterface;
    public function __construct(TaskRepository $taskRepository, EntityManagerInterface $entityManagerInterface)
    {
        $this->taskRepository = $taskRepository;
        $this->entityManagerInterface = $entityManagerInterface;
    }

    /**
     * GET single task 
     * 
     * return App\Entity\Task
     */
    public function getTasksAction(int $id):\FOS\RestBundle\View\View
    {
        // $userId = $this->getUser()->getId();
        $task = $this->taskRepository->findOneBy(['id' => $id]);
        if (empty($task)){
            return $this->ViewError(Response::HTTP_NOT_FOUND,'Task not found');
        }
        return $this->view($task,Response::HTTP_OK);
    }
    

    /**
     * DELETE delete one task
     * 
     * @param  id $id
     * return null
     */
    public function deleteTasksAction(int $id):\FOS\RestBundle\View\View
    {
        // $userId = $this->getUser()->getId();
        $task = $this->taskRepository->findOneBy(['id' => $id]);
        if (empty($task)) {
            return $this->ViewError(Response::HTTP_NOT_FOUND,'Task not found');
        }
        $this->entityManagerInterface->remove($task);
        $this->entityManagerInterface->flush();
        return $this->view([],Response::HTTP_OK);
    }

    /**
     * PATCH edit title task 
     * 
     * @Rest\RequestParam(name="title",description="Title of the task", nullable=false)
     * @param  id $id
     * return null
     */
    public function patchTasksTitleAction(ParamFetcher $paramFetcher,int $id):\FOS\RestBundle\View\View
    {
        // $userId = $this->getUser()->getId();
        $task = $this->taskRepository->findOneBy(['id' => $id]);
        if (empty($task)) {
            return $this->ViewError(Response::HTTP_NOT_FOUND,'Task not found');
        }
        $title = trim($paramFetcher->get('title'));
        if (empty($title)) {
            return $this->ViewError(Response::HTTP_BAD_REQUEST,'Title can not be null');
        }
        $task->setTitle($title);
        $this->entityManagerInterface->persist($task);
        $this->entityManagerInterface->flush();
        return $this->view($task,Response::HTTP_OK);
    }

    /**
     * GETchange status 
     * 
     * return App\Entity\Task
     */
    public function patchTasksCompleteAction(int $id):\FOS\RestBundle\View\View
    {
        // $userId = $this->getUser()->getId();
        $task = $this->taskRepository->findOneBy(['id' => $id]);
        if (empty($task)){
            return $this->ViewError(Response::HTTP_NOT_FOUND,'Task not found');
        }
        $task->setIsComplete(!$task->getIsComplete());
        $this->entityManagerInterface->persist($task);
        $this->entityManagerInterface->flush();
        return $this->view($task,Response::HTTP_OK);
    }
    /**
     * GET notes in task
     * 
     * return App\Entity\Task
     */
    public function getTasksNotesAction(int $id):\FOS\RestBundle\View\View
    {
        // $userId = $this->getUser()->getId();
        $task = $this->taskRepository->findOneBy(['id' => $id]);
        if (empty($task)){
            return $this->ViewError(Response::HTTP_NOT_FOUND,'Task not found');
        }
        return $this->view($task->getNotes(),Response::HTTP_OK);
    }
    
    /**
     * POST create new note
     * 
     * @Rest\RequestParam(name="note",description="Note for the task", nullable=false)
     * @param   ParamFetcher $paramFetcher
     * @param  id $id
     * return App\Entity\Note
     */
    public function postTasksNotesAction(ParamFetcher $paramFetcher,int $id):\FOS\RestBundle\View\View
    {
        // $userId = $this->getUser()->getId();
        $task = $this->taskRepository->findOneBy(['id' => $id]);
        if (empty($task)){
            return $this->ViewError(Response::HTTP_NOT_FOUND,'Task not found');
        }
        $noteValue = trim($paramFetcher->get('note'));
        if (empty($noteValue)) {
            return $this->ViewError(Response::HTTP_BAD_REQUEST,'Note can not be null');
        }
        $note = new Note();
        $task->addNote($note);
        $note->setNote($noteValue);
        $note->setTask($task);
        $this->entityManagerInterface->persist($note);
        $this->entityManagerInterface->flush();
        return $this->view($note,Response::HTTP_CREATED);
    }

    private function ViewError(int $statusCode, string $message):\FOS\RestBundle\View\View
    {
        return $this->view([
            'code'=> $statusCode,
            'message'=>$message
        ],$statusCode);
    }
}
