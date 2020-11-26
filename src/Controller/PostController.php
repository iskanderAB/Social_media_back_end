<?php

namespace App\Controller;


use App\Entity\Post;
use App\Service\converter;
use App\Repository\PosteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/addPost", name="addPost",methods={"POST"})
     */
    public function addPost(Request  $request , EntityManagerInterface $manager  ,SerializerInterface $serializer , ValidatorInterface $validator ){
        $data = $request->getContent();
        try{
            if(!$request->headers->get('Content-Type') === 'application/json'){
                return $this->json(["message" => "bad request content type !"],401);
            }
            $post = $serializer->deserialize($data , Post::class ,'json');
            /**
             * @var  $post Post
             */
            $post->setCreatedBy($this->getUser());
            $post->setCreatedAt(new \DateTime());
            $converter = new converter();
            // $file = $converter->base64ToImage($user->getImage(),uniqid().'.jpg',$this->getParameter('UplodImageUser'));
            $post->setImage($converter->base64ToImage($post->getImage(),uniqid().'.jpg',$this->getParameter('UploadPosUser')));
            $error = $validator->validate($post);
            if (count($error)>0){
                return $this->json(['message' => 'bad request body !'],401);
            }
            $manager->persist($post);
            $manager->flush();
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
    public function updateBook(Post $post = null,Request $request, EntityManagerInterface $manager)
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

}


