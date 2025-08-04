<?php
// src/Controller/TestController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test-flash', name: 'test_flash')]
    public function testFlash(Request $request): Response
    {
        $session = $request->getSession();

        // Add flash messages
        $this->addFlash('success', '✅ This is a SUCCESS flash message!');
        $this->addFlash('error', '❌ This is an ERROR flash message!');
        $this->addFlash('warning', '⚠️ This is a WARNING flash message!');
        $this->addFlash('info', 'ℹ️ This is an INFO flash message!');

        // Peek flash messages (for debug only, doesn't consume them)
        $flashPeek = $session->getFlashBag()->peekAll();

        return $this->render('test/flash.html.twig', [
            'sessionId' => $session->getId(),
            'sessionStarted' => $session->isStarted(),
            'flashPeek' => $flashPeek
        ]);
    }

    #[Route('/test-flash-and-redirect', name: 'test_flash_redirect')]
    public function testFlashAndRedirect(): Response
    {
        $this->addFlash('success', 'Flash message before redirect!');
        return $this->redirectToRoute('test_flash_show');
    }

    #[Route('/test-flash-show', name: 'test_flash_show')]
    public function showFlash(): Response
    {
        return $this->render('test/flash.html.twig', [
            'redirectTest' => true
        ]);
    }
}
