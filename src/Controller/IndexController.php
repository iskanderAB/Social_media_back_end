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
        $filter = function ($user) { 
            return $user->getStat() !== null ; 
        };
        $stats = array_filter($userRepository->findByRole('ROLE_USER') , $filter) ;
        dump($this->getUser()->getPassword());
        return $this->render('post/index.html.twig', [
            'user' => $this->getUser() , 
            'posts' => $postRepository->findAll(),
            'users' => $userRepository->findAll(),
            'etudiants' =>  $userRepository->findByRole('ROLE_USER'),
            'stats' => $stats 
        ]);
    }
}
