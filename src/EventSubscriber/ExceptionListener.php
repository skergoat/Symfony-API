<?php

namespace App\EventSubscriber;

use App\Normalizer\NormalizerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ExceptionListener implements EventSubscriberInterface
{
    private $serializer;
    private $normalizers;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function processException(GetResponseForExceptionEvent $event)
    {
        $result = null;

        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->supports($event->getException())) {
                $result = $normalizer->normalize($event->getException());
                
                break;
            }
        }
        
        if (null == $result) {
            $result['code'] = Response::HTTP_BAD_REQUEST;

            $result['body'] = [
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $event->getException()->getMessage()
            ];
        }

        $body = $this->serializer->serialize($result['body'], 'json');

        $event->setResponse(new Response($body, $result['code'], ['Content-Type' => 'application/json']));
    }

    public function addNormalizer(NormalizerInterface $normalizer)
    {
        $this->normalizers[] = $normalizer;
    }
    
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [['processException', 255]]
        ];
    }
}