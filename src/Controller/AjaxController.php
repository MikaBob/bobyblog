<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Image;

class AjaxController extends AbstractController
{
    public const UPLOADED_IMAGES_PATH = "/images/post";

    /**
    * @Route("/image/upload" , methods={"POST"})
    */
    public function uploadImage($name): Response
    {
        $image = new Image();
        $image->setCreationDate(new \DateTime());

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photos = $form->get('photos')->getData();

            if ($photos) {
                foreach($photos as $uploadedImage) {
                    $file = new File();

                    $filename = $post->getHappenedDate()->format('Y.m.d').'_'.uniqid(mt_rand(), true).'.'.$uploadedImage->guessExtension();
                    $path = $this::UPLOADED_IMAGES_PATH.'/'.$post->getHappenedDate()->format('Y/m/d');

                    $uploadedImage->move($this->getUploadRootDir(), $path.'/'.$filename);

                    $file->setName($filename);
                    $file->setPath($path);
                    $file->setSize($uploadedImage->getClientSize());

                    $post->getPhotos()->add($file);

                    unset($uploadedImage);
                }
            }
        }

        return $this->render('blog/view.html.twig', [
            'name' => $name,
        ]);
    }
}