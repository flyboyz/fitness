<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\Type\JobType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{
    /**
     * @Route("/jobs", name="jobs_list")
     */
    public function index()
    {
        $jobs = $this->getDoctrine()->getRepository(Job::class)
            ->findAll();

        return $this->render('job/index.html.twig', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * @Route("/job/{id}", name="job_show", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @ParamConverter("job", class="App:Job")
     * @param Job $job
     *
     * @return Response
     */
    public function show(Job $job)
    {
        if (!$job) {
            throw $this->createNotFoundException(
                'No job found for id ' . $job->getId()
            );
        }

        return new Response('Check out this great product: ' . $job->getTitle());
    }

    /**
     * @Route("/job/new", name="job_new")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request)
    {
        $job = new Job();

        $form = $this->createForm(JobType::class, $job);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $job = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($job);
            $entityManager->flush();

            return $this->redirectToRoute('jobs_list');
        }

        return $this->render('job/new.html.twig', [
            'form' => $form->createView(),
            'error' => $form->getErrors()
        ]);
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
