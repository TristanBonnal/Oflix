<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    private $maintenance;
    public function __construct($maintenance)
    {
        $this->maintenance = $maintenance;
    }
    public function onKernelResponse(ResponseEvent $event)
    {

        if ($this->maintenance) {
            $content = $event->getResponse()->getContent();
            $newContent = str_replace(
                '<body>', 
                '<body><div class="alert alert-danger">Maintenance prévue mercredi 15 février à 17h00</div>',
                $content);
                $event->getResponse()->setContent($newContent);
        }
    }

    public static function getSubscribedEvents()
    {

        return [
            'kernel.response' => 'onKernelResponse',
        ];
    }
}
