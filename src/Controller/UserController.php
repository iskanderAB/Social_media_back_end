<?php

namespace App\Controller;

use App\Repository\UserRepository;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/api")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/users",name="getUsers",methods={"GET"})
     * @param UserRepository $userRepository
     */
    public function getUsers(UserRepository $userRepository){
        $users = $userRepository->findAll();
        return $this->json($users , 200,[] ,['groups' => 'read_user']);
    }
}
