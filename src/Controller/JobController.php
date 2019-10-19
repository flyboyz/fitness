<?php

namespace App\Controller;

use App\Entity\Job;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{
    /**
     * @Route("/jobs", name="jobs_list")
     */
    public function index()
    {
        return $this->render('job/index.html.twig', [
            'controller_name' => 'JobController',
        ]);
    }

    /**
     * @Route("/job/{id}", name="job_show", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @param $id
     *
     * @return Response
     */
    public function show($id)
    {
        $job = $this->getDoctrine()
            ->getRepository(Job::class)
            ->find($id);

        if (!$job) {
            throw $this->createNotFoundException(
                'No job found for id ' . $id
            );
        }

        return new Response('Check out this great product: ' . $job->getTitle());
    }

    /**
     * @Route("/job/create", name="job_create", methods={"POST"})
     */
    public function create()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $job = (new Job())
            ->setTitle('Test')
            ->setDescription('Ergonomic and stylish!')
            ->setMuscles(1)
            ->setType(1)
        ;

        $entityManager->persist($job);
        $entityManager->flush();

        return new Response('Check out this great product: '.$job->getTitle());
    }
}
