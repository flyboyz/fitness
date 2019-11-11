<?php

namespace App\Controller;

use App\Entity\Training;
use App\Entity\TrainingJob;
use App\Form\TrainingForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TrainingController extends AbstractController
{
    /**
     * @Route("/training/all", name="training_list")
     */
    public function index()
    {
        $allTraining = $this->getDoctrine()->getRepository(Training::class)
            ->findAll();

        return $this->render('training/index.html.twig', [
            'trainingAll' => $allTraining,
        ]);
    }

    /**
     * @Route("/training/create", name="training_create")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $training = new Training();

        $form = $this->createForm(TrainingForm::class, $training);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $training = $form->getData();
            $jobs = $form->get('jobs')->getData();

            foreach ($jobs as $key => $job) {
                $trainingJobs = (new TrainingJob())
                    ->setTraining($training)
                    ->setJob($job)
                    ->setSort($key);

                $entityManager->persist($trainingJobs);
            }

            $entityManager->persist($training);
            $entityManager->flush();

            return $this->redirectToRoute('training_list');
        }

        return $this->render('training/create.html.twig', [
            'form' => $form->createView(),
            'error' => $form->getErrors()
        ]);
    }

    /**
     * @Route("/training/{id}", name="training_show", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @ParamConverter("training", class="App:Training")
     * @param Training $training
     *
     * @return Response
     */
    public function show(Training $training)
    {
        if (!$training) {
            throw $this->createNotFoundException(
                'No training found for id ' . $training->getId()
            );
        }

        return $this->render('training/show.html.twig', [
            'training' => $training,
        ]);
    }

    /**
     * @Route("/training/{id}/detele", name="training_delete", requirements={"id"="\d+"})
     *
     * @ParamConverter("training", class="App:Training")
     * @param Training $training
     *
     * @return RedirectResponse
     */
    public function delete(Training $training)
    {
        if (!$training) {
            throw $this->createNotFoundException(
                'No job found for id ' . $training->getId()
            );
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($training);
        $entityManager->flush();

        return $this->redirectToRoute('training_list', [], 302);
    }
}
