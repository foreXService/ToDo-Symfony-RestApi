<?php 

namespace App\Serializer\Normalizer;

use App\Entity\Task;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class TaskNormalizer implements NormalizerInterface
{
    private ObjectNormalizer $objectNormalizer;
    public function __construct(ObjectNormalizer $objectNormalizer){
        $this->objectNormalizer = $objectNormalizer;
    }

    public function normalize($object, $format = null, array $context = []){
        $context['ignored_attributes'] = ['notes','list'];
        $data = $this->objectNormalizer->normalize($object,$format,$context);
        return $data;
    }

    public function supportsNormalization($data, $format = null){
        return $data instanceof Task;
    }

}