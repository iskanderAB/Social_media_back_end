<?php
namespace App\Controller;

use App\Entity\Post;
use App\Entity\Stat;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Service\converter;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\VarDumper\VarDumper;

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
            $user->setBlockNotification(false);
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
    
    /**
     * @Route("/love",name="love",methods={"POST"} )
     */
    public function love(Request $request) {
        $data = $request->getContent();
        $id = json_decode($data)->postId;
        $post = $this->getDoctrine()
        ->getRepository(Post::class)
        ->find($id);
        if($this->getUser()->getLoves()->contains($post)){
            $this->getUser()->removeLove($post);   
        }else{
            $this->getUser()->addLove($post);   
        }
        $this->getDoctrine()->getManager()->flush();
        return $this->json(["message" => "love" , "status" => 200] , 200);
    }
    /**
     * @Route("/participate" , name="participate",methods={"POST"})
     */
    public function participate(Request $request){
        $data = $request->getContent();
        $id = json_decode($data)->postId;
        $post = $this->getDoctrine()
        ->getRepository(Post::class)
        ->find($id);
        if($this->getUser()->getInterests()->contains($post)){
            $this->getUser()->removeInterest($post);   
            // var_dump('true');
        }else{
            $this->getUser()->addInterest($post);   
            //var_dump('false ');
        }
        $this->getDoctrine()->getManager()->flush();
        return $this->json(["message" => "interrest" , "status" => 200] , 200);
    }

    /**
     * @Route("/setTokenNotification",name="setToknNotification",methods={"POST"} )
     */
    public function setToknNotification (Request $request){
        $data = $request->getContent();
        $token = json_decode($data)->tokenNotification;
        $this->getUser()->setTokenNotification($token);
        $this->getDoctrine()->getManager()->flush();
        return $this->json(["message" => "TokenNotification" , "status" => 201] , 200);
    }
    //     /**
    //  * @Route("/blockNotification",name="setToknNotification",methods={"POST"} )
    //  */
    // public function setToknNotification (Request $request){
    //     $data = $request->getContent();
    //     $token = json_decode($data)->tokenNotification;
    //     $this->getUser()->setTokenNotification($token);
    //     $this->getDoctrine()->getManager()->flush();
    //     return $this->json(["message" => "TokenNotification" , "status" => 201] , 200);
    // }
    
    /**
     * @Route("/stat" , name="stat" ,methods={"POST","PUT"})
     */
    public function addUpdateStat(Request $request , SerializerInterface $serializer) {
        $data = $request->getContent();
        // var_dump($data);
        try{
            $stat = $serializer->deserialize($data,Stat::class,'json');
            $this->getDoctrine()->getManager()->remove($this->getUser()->getStat());
            $this->getUser()->setStat($stat);
            $this->getDoctrine()->getManager()->persist($stat);
            $this->getDoctrine()->getManager()->flush();
            return $this->json(["message" => "stat added ! " , "status" => 201] , 200);
        }catch(\Exception $e){
            return $this->json(["message" => $e->getMessage() , "status" => 400] , 400);
        }  
    }
}
