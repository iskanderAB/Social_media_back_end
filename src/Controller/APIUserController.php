<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\converter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/api")
 */

class APIUserController extends AbstractController
{

    
    /**
     * @Route("/users",name="getUsers",methods={"GET"})
     * @param UserRepository $userRepository
     */
    public function getUsers(UserRepository $userRepository){
        $users = $userRepository->findAll();
        return $this->json($users , 200,[] ,['groups' => 'read_user']);
    }
    /**
     * @Route("/addUser",name="addUser",methods={"POST"})
     */
    public function addUser(Request $request ,ValidatorInterface $validator , SerializerInterface $serializer, EntityManagerInterface $entityManager , UserPasswordEncoderInterface $encoder){
        $data = $request->getContent();
        try {
            if($request->headers->get('Content-Type') !== 'application/json'){
                return $this->json([
                    'message' => 'bad request content !'
                    ],
                    400
                );
            }
            $user= $serializer->deserialize($data,User::class,'json');
            $converter = new converter();
            // $file = $converter->base64ToImage($user->getImage(),uniqid().'.jpg',$this->getParameter('UplodImageUser'));
            $user->setImage($converter->base64ToImage($user->getImage(),uniqid().'.jpg',$this->getParameter('UplodImageUser')));
            $error = $validator->validate($user);
            if (count($error)>0){
                return $this->json(
                    ['message'=> $error],400);
            }
            $user->setPassword($encoder->encodePassword($user,$user->getPassword()));
            $user->setRoles(['ROLE_USER']);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->json(["message" => "user added ! ", "status" => "201"] , 201);
        }catch (NotEncodableValueException $exception){
            return $this->json(
                ['message' => $exception->getMessage(),
                'status'=>400],
                400
            );
        }
    }
    /**
     * @Route("/user", name="getUser", methods={"GET"})
     */
    public function getOneUser(Request $request)
    {
        // $token = new TokenDecoder($request);
        // $roles = $token->getRoles();
        // if (!in_array('ROLE_ADMIN', $roles, true)) {
        //     return $this->json([
        //         'message' => 'access denied !',
        //         'status' => 403
        //     ], 403);
        // }
        return $this->json($this->getUser(), 200, [], ['groups' => 'read_user']);
    }

}
