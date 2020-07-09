<?php

namespace App\Controller;

use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class NoteController extends AbstractFOSRestController
{
    private NoteRepository $noteRepository;
    private EntityManagerInterface $entityManagerInterface;
    public function __construct(NoteRepository $noteRepository, EntityManagerInterface $entityManagerInterface)
    {
        $this->noteRepository = $noteRepository;
        $this->entityManagerInterface = $entityManagerInterface;
    }

    /**
     * GET single note 
     * 
     * return App\Entity\Note
     */
    public function getNotesAction(int $id):\FOS\RestBundle\View\View
    {
        // $userId = $this->getUser()->getId();
        $note = $this->noteRepository->findOneBy(['id' => $id]);
        if (empty($note)){
            return $this->ViewError(Response::HTTP_NOT_FOUND,'Note not found');
        }
        return $this->view($note,Response::HTTP_OK);
    }
    

    /**
     * DELETE delete one note
     * 
     * @param  id $id
     * return null
     */
    public function deleteNotesAction(int $id):\FOS\RestBundle\View\View
    {
        // $userId = $this->getUser()->getId();
        $note = $this->noteRepository->findOneBy(['id' => $id]);
        if (empty($note)) {
            return $this->ViewError(Response::HTTP_NOT_FOUND,'Note not found');
        }
        $this->entityManagerInterface->remove($note);
        $this->entityManagerInterface->flush();
        return $this->view([],Response::HTTP_OK);
    }

    /**
     * PATCH edit title task 
     * 
     * @Rest\RequestParam(name="note",description="Title of the task", nullable=false)
     * @param  id $id
     * return null
     */
    public function patchNotesNoteAction(ParamFetcher $paramFetcher,int $id):\FOS\RestBundle\View\View
    {
        // $userId = $this->getUser()->getId();
        $note = $this->noteRepository->findOneBy(['id' => $id]);
        if (empty($note)) {
            return $this->ViewError(Response::HTTP_NOT_FOUND,'Note not found');
        }
        $noteValue = trim($paramFetcher->get('note'));
        if (empty($noteValue)) {
            return $this->ViewError(Response::HTTP_BAD_REQUEST,'Note can not be null');
        }
        $note->setNote($noteValue);
        $this->entityManagerInterface->persist($note);
        $this->entityManagerInterface->flush();
        return $this->view($note,Response::HTTP_OK);
    }

    private function ViewError(int $statusCode, string $message):\FOS\RestBundle\View\View
    {
        return $this->view([
            'code'=> $statusCode,
            'message'=>$message
        ],$statusCode);
    }
}
