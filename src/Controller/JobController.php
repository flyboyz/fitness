<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\Type\JobType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @Route("/job/create", name="job_create")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request)
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

        return $this->render('job/create.html.twig', [
            'form' => $form->createView(),
            'error' => $form->getErrors()
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

        return $this->render('job/show.html.twig', [
            'job' => $job,
        ]);
    }

    /**
     * @Route("/job/{id}/detele", name="job_delete", requirements={"id"="\d+"})
     *
     * @ParamConverter("job", class="App:Job")
     * @param Job $job
     *
     * @return RedirectResponse
     */
    public function delete(Job $job)
    {
        if (!$job) {
            throw $this->createNotFoundException(
                'No job found for id ' . $job->getId()
            );
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($job);
        $entityManager->flush();

        return $this->redirectToRoute('jobs_list', [], 302);
    }
}
