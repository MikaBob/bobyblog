<?php

namespace App\Controller;

use App\Form\Type\PostType;
use App\Entity\Post;
use App\Entity\Image;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController {

    /**
     * @Route("/" , methods={"GET"}, name="view")
     */
    public function indexAction(): Response {
        $posts = $this->getDoctrine()
                ->getRepository(Post::class)
                ->findAll();

        return $this->render('blog/view.html.twig', [
                    'posts' => $posts,
        ]);
    }

    /**
     * @Route("/post/create", name="create")
     * @param string $uploadDir
     * @param string $album_dir
     */
    public function createPost(Request $request, string $uploadDir, string $album_dir): Response {
        $post = new Post();
        $post->setCreationDate(new \DateTime());

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            foreach (explode(";", $form->get('imageIds')->getData()) as $imageId) {
                $image = $entityManager->find(Image::Class, $imageId);
                if ($image !== null) {
                    if (file_exists($uploadDir . $image->getFilenameWithExtension())) {
                        $image = $post->addImageToAlbum($uploadDir, $image, $album_dir);
                        $entityManager->persist($image);
                        $post->addImage($image);
                    } else {
                        $post->removeImage($image);
                    }
                }
            }

            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('view');
        }

        return $this->render('blog/create_post.html.twig', [
                    'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/post/upload_image")
     * @param string $uploadDir
     */
    public function uploadImage(Request $request, string $uploadDir): Response {
        $uploadedImage = $request->files->get('image');
        $filename = "tmp_" . uniqid(mt_rand(), true);
        $extension = '.' . $uploadedImage->guessExtension();

        try {
            $uploadedImage->move($uploadDir, $filename . $extension);
        } catch (FileException $e) {
            throw $e->getMessage();
        }

        $image = new Image();
        $image->setCreationDate(new \DateTime());
        $image->setFilename($filename);
        $image->setExtension($extension);
        $image->setWidth(getimagesize($uploadDir . $filename . $extension)[0]);
        $image->setHeight(getimagesize($uploadDir . $filename . $extension)[1]);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($image);
        $entityManager->flush();

        return new JsonResponse(["id" => $image->getId(), "src" => $image->getFilenameWithExtension()], Response::HTTP_OK);
    }

    /**
     * @Route("/post/update_image")
     */
    public function updateImage(Request $request): Response {
        $imgId = $request->get('id');

        $image = $this->getDoctrine()
                ->getRepository(Image::class)
                ->find($imgId);


        $image->setCaption($request->get('caption'));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($image);
        $entityManager->flush();

        return new JsonResponse(["Ok"], Response::HTTP_OK);
    }

    /**
     * @Route("/post/get" , methods={"GET"}, name="getPost")
     */
    public function getPostAction(Request $request): Response {
        $orderBy = $request->get('orderBy');
        $direction = $request->get('direction');
        $offset = $request->get('offset');
        $filter = $request->get('filter');

        $posts = $this->getDoctrine()->getRepository(Post::class)->findAllBy($filter, $orderBy, $direction, 10, $offset);

        return $this->render('blog/card_post.html.twig', [
                    'posts' => $posts
        ]);
    }

}
