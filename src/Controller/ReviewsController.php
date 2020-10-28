<?php

namespace App\Controller;

use App\Entity\Review;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewsController extends AbstractController
{
    /**
     * @Route("/reviews", name="reviews")
     */
    public function review_list(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Review::class);

        $reviews = $repository->findAll();

        return $this->render('reviews/review_list.html.twig', [
            'reviews' => $reviews,
        ]);
    }

    /**
     * @Route("/form", name="form")
     */
    public function review_form(): Response
    {

        return $this->render('reviews/review_form.html.twig', [
            'controller_name' => 'ReviewsController',
        ]);
    }

    /**
     * @Route("/save_review", name="save_review")
     */
    public function save_review(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        if(isset($_POST['review-id'])){
            $review = $entityManager->getRepository(Review::class)->find($_POST['review-id']);
            //var_dump($review);die();
            $review->setUsername($_POST['user']);
            $review->setDescription($_POST['text']);
            $review->setPrice($_POST['rating_0']);
            $review->setQuality($_POST['rating_1']);
            $review->setDelivery($_POST['rating_2']);
            $review->setWorthy($_POST['rating_3']);

        }else{
            $review = new Review($_POST['user'], $_POST['text'], $_POST['rating_0'], $_POST['rating_1'], $_POST['rating_2'], $_POST['rating_3']);
            $entityManager->persist($review);
        }
        $entityManager->flush();


        return $this->redirectToRoute('reviews');
    }

    /**
     * @Route("/review/{id}", name="review_show")
     */
    public function review_show($id)
    {
        $review = $this->getDoctrine()
            ->getRepository(Review::class)
            ->find($id);
        
        //var_dump($review);die();

        if (!$review) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        return $this->render('reviews/review_form.html.twig', ['review' => $review]);
    }

        /**
     * @Route("/delete/{id}", name="review_delete")
     */
    public function review_delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $review = $this->getDoctrine()
            ->getRepository(Review::class)
            ->find($id);

        $entityManager->remove($review);
        $entityManager->flush();

        return $this->redirectToRoute('reviews');
    }
}
