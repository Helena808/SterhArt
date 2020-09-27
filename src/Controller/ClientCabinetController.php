<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use App\Repository\ProjectRepository;
use App\Repository\StageRepository;
use App\Repository\RenewalRepository;

class ClientCabinetController extends AbstractController
{

// П Р О Е К Т Ы

    // ВСЕ АКТИВНЫЕ ПРОЕКТЫ +
    /**
     * @Route("/client-cabinet/{userID}/", name="client_cabinet", requirements={"userID"="\d+"})
     */
    public function clientCabinet($userID, ProjectRepository $pRepository, StageRepository $sRepository, RenewalRepository $rRepository)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser(); // ЮЗЕР

        $data = [
            'user' => $user,
            'containers' => [],
        ]; // сюда положим подборки проект-текущая стадия-last обновление

        $projects = $user->getProject();

        for ($i=0; $i<count($projects); $i++) {
            $project = $projects[$i]; // ПРОЕКТ +
            $projectID = $project->getID(); // +
            
            $arrStage = $sRepository->findActiveByProjectID($projectID); //массив
            $stage = $arrStage[0]; // СТАДИЯ
            $stageID = $stage->getID();

            $arrRenewalID = $rRepository->findLastRenewal($stageID);
            $renewalID = $arrRenewalID[0][1]; //приходит массив с массивом!!!
            $renewal = $rRepository->find($renewalID); // ОБНОВЛЕНИЕ

            $data['containers'][$i] = [
                    'project' => $project, 
                    'stage' => $stage, 
                    'renewal' => $renewal
                    ];
        }
       
        return $this->render('client_cabinet/client_cabinet.html.twig', $data);
    }


// С Т А Д И И   О Д Н О Г О   П Р О Е К Т А
    // ВСЕ СТАДИИ ОДНОГО ПРОЕКТА +
    /**
     * @Route("/client-cabinet/{userID}/{projectID}/", name="client_one_project", requirements={"userID"="\d+", "projectID"="\d+"})
     */
     public function clientOneProject($userID, $projectID, ProjectRepository $pRepository, StageRepository $sRepository)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $project = $pRepository -> find($projectID);
        $stages = $sRepository -> findByProjectID($projectID);

        if (!$project) {
            throw $this->createNotFoundException('Проект не найден');
        };

        $data = [
            'user' => $user,
            'project' => $project,
            'stages' => $stages,
        ];

        return $this->render('client_cabinet/clientOneProject.html.twig', $data);
    }


// О Б Н О В Л Е Н И Я   О Д Н О Й   С Т А Д И И

    // ВСЕ ОБНОВЛЕНИЯ ОДНОЙ СТАДИИ КОНКРЕТНОГО ПРОЕКТА
    /**
     * @Route("/client-cabinet/{userID}/{projectID}/{stageID}", name="client_one_stage", methods = "GET", requirements={"userID"="\d+","projectID"="\d+", "stageID"="\d+"})
     */
    public function clientOneStage($userID, $projectID, $stageID, ProjectRepository $pRepository, StageRepository $sRepository, RenewalRepository $rRepository)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $project = $pRepository -> find($projectID);
        $stage = $sRepository -> find($stageID);
        $renewals = $rRepository -> findByStageID($stageID);

        $data = [
            'user' => $user,
            'project' => $project,
            'stage' => $stage,
            'renewals' => $renewals,
        ];

        return $this->render('client_cabinet/clientOneStage.html.twig', $data);
    }

    // ДОБАВЛЕНИЕ КОММЕНТАРИЯ ЗАКАЗЧИКА
    /**
     * @Route("/client-cabinet/{userID}/{projectID}/{stageID}/{renewalID}/addComment", name="add_comment_open", methods = "GET", requirements={"userID"="\d+","projectID"="\d+", "stageID"="\d+", "renewalID"="\d+"})
     */
    public function addCommentOpen($projectID, $stageID, $renewalID, ProjectRepository $pRepository, StageRepository $sRepository, RenewalRepository $rRepository)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $project = $pRepository -> find($projectID);
        $stage = $sRepository -> find($stageID);
        $renewal = $rRepository -> find($renewalID);

        $data = [
            'user' => $user,
            'project' => $project,
            'stage' => $stage,
            'renewal' => $renewal,
        ];

        return $this->render('client_cabinet/addComment.html.twig', $data);
    }


    /**
     * @Route("/client-cabinet/{userID}/{projectID}/{stageID}/{renewalID}/addComment", name="add_comment_submit", methods = "POST", requirements={"userID"="\d+","projectID"="\d+", "stageID"="\d+", "renewalID"="\d+"})
     */
    public function addCommentSubmit($userID, $projectID, $stageID, $renewalID, Request $request, RenewalRepository $rRepository)
    {    	
    	$renewal = $rRepository->find($renewalID);
        
        $commentClient = $request->get('commentClient');
        $date = new \DateTime();

    	$renewal->setCommentClient($commentClient);
        $renewal->setCommentClientDate($date);

    	$entityManager = $this->getDoctrine()->getManager();
    	$entityManager->persist($renewal);
    	$entityManager->flush();

    	return $this->redirectToRoute('client_one_stage', array('userID'=> $userID, 'projectID'=>$projectID, 'stageID'=>$stageID));
    }
}
