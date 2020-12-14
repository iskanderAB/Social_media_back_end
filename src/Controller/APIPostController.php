<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Post;
use App\Service\converter;
use DateTime;
use App\Repository\PosteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @Route("/api")
 */
class APIPostController extends AbstractController
{
    /**
     * @Route("/addPost", name="addPost",methods={"POST"})
     */
    public function addPost(Request  $request , EntityManagerInterface $manager , UserRepository $users ,  SerializerInterface $serializer , ValidatorInterface $validator ,HttpClientInterface $client){
        $data = $request->getContent();
        try{
            if(!$request->headers->get('Content-Type') === 'application/json'){
                return $this->json(["message" => "bad request content type !"],401);
            }
             $converter = new converter();
            // $data = json_decode($data , true);
            // $data = json_encode($data);
            //return $this->json($document, 201);
            $post = $serializer->deserialize($data , Post::class ,'json');
            /**
             * @var  $post Post
             */
            $post->setCreatedBy($this->getUser());
            $post->setCreatedAt(new \DateTime());
            // $file = $converter->base64ToImage($user->getImage(),uniqid().'.jpg',$this->getParameter('UplodImageUser'));
            if($post->getImage())
                $post->setImage($converter->base64ToImage($post->getImage(),uniqid().'.jpg',$this->getParameter('UploadPostUser')));
            //var_dump($post);
            $error = $validator->validate($post);
            if (count($error)>0){
                return $this->json(['message' => 'bad request body !'],401);
            }
            $to = function  ($user){
                    return (["to" => $user->getTokenNotification(),"body"=> $this->getUser()->getNom().' '.$this->getUser()->getPrenom().' '.'crée une nouvelle poste']);
            };
            $AllTokenNotification = array_map($to,$users->findAll());
            
            $manager->persist($post);
            $manager->flush();
            $filter = function($token) {   
                return $token['to'] !== null ;
            };
            print_r(array_filter($AllTokenNotification,$filter));
            // $response = $client->request('POST', 'https://exp.host/--/api/v2/push/send', [
            //     'json' =>array_filter($AllTokenNotification,$filter)
            // ]);
            //$decodedPayload = $response->toArray();
            return $this->json(["message" => "post added !" , "status" => "201"], 201);
        }catch (\Exception $e){
            return $this->json($e->getMessage(),401);
        }
    }
    /**
     * @Route("/post/{id}", name="getPost", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function getPost(Post $post = null)
    {
        if (!$post) {
            return $this->json([
                'message' => 'book not found !',
                'status' => 404
            ], 404);
        }
        return $this->json($post, 200, [],["groups"=>"post_reader"]);
    }
    /**
     * @Route("/posts",name="getPosts", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function getPosts(){
        $posts= $this->getDoctrine()->getRepository(Post::class)->findAll();
        $filteredPosts = array_filter($posts, function (Post $post){
            return true /*$post->getCreatedBy() !== $this->getUser()*/;
        }
        );
        return $this->json($filteredPosts,200,[],["groups"=>"post_reader"]);
    }
    /**
     * @Route("/update/post/{id}", name="update_post", methods={"PUT"})
     */
    public function updatePost(Post $post = null,Request $request, EntityManagerInterface $manager )
    {
        if (!$post) {
            return $this->json([
                'message' => 'Post not found !',
                'status' => 404
            ], 404);
        }
        $user = $this->getUser();
        if ($post->getCreatedBy() !== $user) {
            return $this->json([
                'message' => 'access denied !',
                'status' => 403
            ], 403);
        }
        $postData = json_decode($request->getContent(),true);
        var_dump($postData);
        if(isset($postData['content'])){
            $post->setContent($postData['content']);
            echo 'hello';
        }
        $manager->flush();
        return $this->json([
            'message' => 'post updated',
            'status' => 200
        ],200);
    }

    /**
     * @Route("/post/{id}", name="edit_post", methods={"PUT"})
     */
    public function editOnePost(Post $post = null, ValidatorInterface $validator , Request $request, EntityManagerInterface $manager)
    {
        if (!$post) {
            return $this->json([
                'message' => 'user not found !',
                'status' => 404
            ], 404);
        }

        $tabData = json_decode($request->getContent(), true);
        if ($tabData === null || $request->headers->get('Content-Type') !== 'application/json') {
            return $this->json([
                'message' => 'bad request !',
                'status' => 400
            ], 400);
        }
        if (isset($tabData['content'])) {
            $post->setContent($tabData['content']);
        }
        if (isset($tabData['title'])) {
            $post->setTitle($tabData['title']);
        }
        if (isset($tabData['date'])) {
            $post->setDate(new DateTime($tabData['date']));
        }
        $manager->persist($post);
        $manager->flush();

        return $this->json([
            'message' => 'post updated',
            'status' => 200
        ], 200);
    }


        /**
     * @Route("/post/{id}", name="delete_post", methods={"DELETE"})
     */
    public function deleteOnePost(Post $post = null, Request $request, EntityManagerInterface $manager)
    {
        if (!$post) {
            return $this->json([
                'message' => 'user not found !',
                'status' => 404
            ], 404);
        }
        $manager->remove($post);
        $manager->flush();
        return $this->json([
            'message' => 'post deleted',
            'status' => 200
        ], 200);
    }

}