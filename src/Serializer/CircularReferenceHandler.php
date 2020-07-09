<?php

namespace App\Serializer;

use App\Entity\Note;
use App\Entity\Task;
use App\Entity\TaskList;
use Symfony\Component\Routing\RouterInterface;

class CircularReferenceHandler
{
    private RouterInterface $routerInterface;
    public function __construct(RouterInterface $routerInterface)
    {
        $this->routerInterface = $routerInterface;
    }

    public function __invoke($object)
    {
        switch ($object) {
            case $object instanceof TaskList:
                return $this->routerInterface->generate('get_list',['id'=>$object->getId()]);
            case $object instanceof Task:
                return $this->routerInterface->generate('get_tasks',['id'=>$object->getId()]);
            case $object instanceof Note:
                return $this->routerInterface->generate('get_notes',['id'=>$object->getId()]);
            
        }
        return $object->getId();
    }
}