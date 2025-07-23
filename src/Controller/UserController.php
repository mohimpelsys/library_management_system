<?php

namespace App\Controller;

use App\Entity\Borrow;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/user')]
//#[IsGranted('ROLE_USER')]
class UserController extends AbstractController
{
    #[Route('/borrows', name: 'user_borrows')]
    public function borrowedBooks(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $borrows = $em->getRepository(Borrow::class)->findBy(['user' => $user]);

        return $this->render('user/borrows.html.twig', [
            'borrows' => $borrows,
        ]);
    }
}
