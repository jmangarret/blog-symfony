<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;

class PostController extends AbstractController
{
    /**
     * @Route("/posts", name="posts")
     */
    public function index()
    {
        $em   = $this->getDoctrine()->getManager();
        $posts = $em->getRepository(Post::class)->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }
    /**
     * @Route("/post/create", name="post_create")
     */
    public function create(Request $request, FileUploader $fileUploader)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $em   = $this->getDoctrine()->getManager();
        $form->handleRequest($request);
        if (($form->isSubmitted()) && ($form->isValid())) {
            $file = $form['image']->getData();
            if ($file) {
                $imageFileName = $fileUploader->upload($file);
                $post->setImage($imageFileName);
            }

            $user = $em->getRepository(User::class)->find($this->getUser());
            $user->addPost($post);
            //$post->setUser= $this->getUser();
            $post->setCreatedAt(new \DateTime());            
            $em->persist($post);
            $em->flush();

            $this->addFlash('success', Post::REGISTRO_EXITOSO);

            return $this->redirectToRoute('posts');
        }

        return $this->render('post/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/post/show/{id}", name="posts_show")
     */
    public function show($id)
    {
        $em   = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Post::class)->find($id);

        $posts = $em->getRepository(Post::class)->findAll();
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/post/edit/{id}", name="posts_edit")
     */
    public function edit($id, Request $request, FileUploader $fileUploader)
    {
        $em   = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Post::class)->find($id);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if (($form->isSubmitted()) && ($form->isValid())) {
            $file = $form['image']->getData();
            if ($file) {
                $imageFileName = $fileUploader->upload($file);
                $post->setImage($imageFileName);
            }

            $user = $em->getRepository(User::class)->find($this->getUser());
            $user->addPost($post);
            $em->persist($post);
            $em->flush();

            $this->addFlash('success', Post::EDITADO_EXITOSO);

            return $this->redirectToRoute('posts');
        }
        
        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
