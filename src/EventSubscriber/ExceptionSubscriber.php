<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/05
 */

namespace App\EventSubscriber;

use App\Normalizer\NormalizerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RequestContextAwareInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ExceptionSubscriber
 *
 * @package App\EventSubscriber
 */
class ExceptionSubscriber implements EventSubscriberInterface
{

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var
     */
    private $normalizers;

    /**
     * @var RequestContextAwareInterface $routing
     */
    private $routing;

    /**
     * ExceptionSubscriber constructor.
     *
     * @param SerializerInterface          $serializer
     * @param RequestContextAwareInterface $routing
     */
    public function __construct(SerializerInterface $serializer, RequestContextAwareInterface $routing)
    {
        $this->serializer = $serializer;
        $this->routing = $routing;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [['processException', 255]]
        ];
    }

    /**
     * @param GetResponseForExceptionEvent $event
     *
     * @throws \InvalidArgumentException
     */
    public function processException(GetResponseForExceptionEvent $event)
    {
        if (preg_match("%^/api/%", $this->routing->getContext()->getPathInfo())) {

            $result = null;

            foreach ($this->normalizers as $normalizer) {
                /** @var NormalizerInterface $normalizer */
                if ($normalizer->supports($event->getException())) {
                    $result = $normalizer->normalize($event->getException());
                    break;
                }
            }

            if (null == $result) {
                return;
                $result['code'] = Response::HTTP_BAD_REQUEST;

                $result['body'] = [
                    'code'    => Response::HTTP_BAD_REQUEST,
                    'message' => $event->getException()->getMessage()
                ];
            }

            $body = $this->serializer->serialize($result['body'], 'json');

            $event->setResponse(new Response($body, $result['code']));
        }
    }

    /**
     * @param NormalizerInterface $normalizer
     */
    public function addNormalizer(NormalizerInterface $normalizer)
    {
        $this->normalizers[] = $normalizer;
    }
}
