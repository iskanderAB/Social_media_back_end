<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    /**
     * @Route("/" , methods={"GET"})
     */
    public function index(PostRepository $postRepository,UserRepository $userRepository): Response
    {
        //dd($this->getUser()->getRoles());
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),
            'users' => $userRepository->findAll(),
            'etudiants' =>  $userRepository->findByRole('ROLE_USER')
        ]);
    }
}
