<?php

namespace App\EventListener;


use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Twig\Environment;

class AccessDeniedListener
{
    public function __construct(
        private RouterInterface $router,
        private Environment $twig
    ) {}

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof AccessDeniedException) {
            $html = $this->twig->render('error/access_denied.html.twig');

            $response = new Response($html, Response::HTTP_FORBIDDEN);
            $event->setResponse($response);
        }
    }
}
