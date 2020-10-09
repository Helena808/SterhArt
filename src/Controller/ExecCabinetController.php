<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Project;
use App\Entity\Stage;
use App\Entity\Renewal;
use App\Entity\Sketch;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use App\Repository\StageRepository;
use App\Repository\RenewalRepository;
use App\Repository\SketchRepository;
use App\Repository\ConceptRepository;
use App\Form\RenewalFormType;
use App\Form\RenewalEditFormType;


class ExecCabinetController extends AbstractController
{

// П Р О Е К Т Ы

    // ВСЕ АКТИВНЫЕ ПРОЕКТЫ
    /**
     * @Route("/exec-cabinet", name="exec_cabinet")
     */
    public function executorCabinet(ProjectRepository $pRepository, StageRepository $sRepository, RenewalRepository $rRepository, UserRepository $uRepository)
    {
    	$data = [
            'containers' => [],
        ]; // сюда положим подборки проект-текущая стадия-last обновление

        $activeStages = $sRepository->findActiveStages();

        for ($i=0; $i<count($activeStages); $i++) {
            
            $activeStage = $activeStages[$i];
            $activeStageID = $activeStage->getID();

            $arrRenewalID = $rRepository->findLastRenewal($activeStageID);
            $renewalID = $arrRenewalID[0][1];//приходит массив с массивом!!!
            $renewal = $rRepository->find($renewalID);

            $projectID = $activeStage->getProjectID();
            $project = $pRepository->find($projectID);

            $userID = $project->getUser();
            $user = $uRepository->find($userID);

            $data['containers'][$i] = [
                    'user' => $user,
                    'project' => $project, 
                    'stage' => $activeStage, 
                    'renewal' => $renewal
                    ];
        }
        
        return $this->render('exec_cabinet/exec_cabinet.html.twig', $data);
    }

// ДОБАВИТЬ ТЕКУЩИЙ ПРОЕКТ
     /**
      * @Route("/exec-cabinet/addProject", name="add_project_open", methods = "GET")
      */
    public function addProjectOpen(UserRepository $uRepository)
    {
    	$users = $uRepository -> findAll();

        $data = [
            'users' => $users,
        ];

        return $this->render('exec_cabinet/addProject.html.twig', $data);
    }
     
    // Обработчик формы
    /**
     * @Route("/exec-cabinet/addProject", name="add_project_submit", methods = "POST")
     */
    public function addProjectSubmit(Request $request, UserRepository $uRepository, StageRepository $sRepository)
    {
    	$projectTitle = $request->get('projectTitle');
    	$city = $request->get('city');
    	$address = $request->get('address');
        $user_id = $request->get('user_id');

        $user = $uRepository->find($user_id);

    	$project = new Project;
    	$project->setProjectTitle($projectTitle);
    	$project->setCity($city);
    	$project->setAddress($address);
        $project->setUser($user);

    	$entityManager = $this->getDoctrine()->getManager();
    	$entityManager->persist($project);
    	$entityManager->flush();

        $projectID = $project->getID();

    	return $this->redirectToRoute('add_stages', array('projectID'=>$projectID));
    }

// УДАЛИТЬ ПРОЕКТ
    /**
      * @Route("/exec-cabinet/{id}/delete", name="delete_project", requirements={"id"="\d+"})
      */
    public function deleteProject($id, ProjectRepository $pRepository, StageRepository $sRepository, RenewalRepository $rRepository, SketchRepository $skRepository, ConceptRepository $cRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $project = $pRepository -> find($id);
        $stages = $sRepository -> findByProjectID($id);

        $em = $this -> getDoctrine() -> getManager();

        foreach($stages as $stage)
        {
            $stageID = $stage -> getId();
            $renewals = $rRepository -> findByStageID($stageID);

            foreach($renewals as $renewal)
            {
                $renewalID = $renewal -> getId();

                // Удаляем все картинки исполнителя
                $sketches = $skRepository -> findByRenewalId($renewalID);
                foreach($sketches as $sketch)
                {
                    $renewal -> removeSketch($sketch);
                    $em -> remove($sketch);
                };

                // Удаляем все картинки заказчика
                $concepts = $cRepository -> findByRenewalId($renewalID);
                foreach($concepts as $concept)
                {
                    $renewal -> removeConcept($concept);
                    $em -> remove($concept);
                };

                // Удаляем обновление
                $stage -> removeRenewalsID($renewal);
                $em -> remove($renewal);
            };

            // Удаляем стадию
            $project -> removeStagesID($stage);
            $em -> remove($stage);
        };

        // Удаляем проект
        $em -> remove($project);
        $em -> flush();

        return $this->redirectToRoute('exec_cabinet');
    }


// С Т А Д И И   О Д Н О Г О   П Р О Е К Т А

    // ДОБАВИТЬ СТАДИИ В НОВЫЙ ТЕКУЩИЙ ПРОЕКТ
     /**
      * @Route("/exec-cabinet/{projectID}/addStages", name="add_stages", methods = "GET", requirements={"projectID"="\d+"})
      */
    public function addStagesOpen($projectID, ProjectRepository $pRepository, StageRepository $sRepository)
    {
        $project = $pRepository -> find($projectID);

        $stage1 = new Stage;
        $stage1->setStageTitle('Планировочные решения');
        $stage1->setStatus('в работе');
        $stage1->setprojectID($project);

        $stage2 = new Stage;
        $stage2->setStageTitle('3D-визуализация');
        $stage2->setStatus('-');
        $stage2->setprojectID($project);

        $stage3 = new Stage;
        $stage3->setStageTitle('Подготовка рабочего проекта');
        $stage3->setStatus('-');
        $stage3->setprojectID($project);

        $stage4 = new Stage;
        $stage4->setStageTitle('Авторский надзор');
        $stage4->setStatus('-');
        $stage4->setprojectID($project);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($stage1);
        $entityManager->persist($stage2);
        $entityManager->persist($stage3);
        $entityManager->persist($stage4);
        $entityManager->flush();

        $stage1ID = $stage1->getID();
        
        return $this->redirectToRoute('add_renewal', array('projectID'=>$projectID, 'stageID'=>$stage1ID));
    }

    // ВСЕ СТАДИИ ОДНОГО ПРОЕКТА
    /**
     * @Route("/exec-cabinet/{projectID}/", name="exec_one_project", requirements={"projectID"="\d+"})
     */
     public function execOneProject($projectID, ProjectRepository $pRepository, StageRepository $sRepository)
    {
        $project = $pRepository -> find($projectID);
        $stages = $sRepository -> findByProjectID($projectID);
        
        if (!$project) {
            throw $this->createNotFoundException('Проект не найден');
        };

        $data = [
            'project' => $project,
            'stages' => $stages,
        ];

        return $this->render('exec_cabinet/execOneProject.html.twig', $data);
    }
 
// О Б Н О В Л Е Н И Я   О Д Н О Й   С Т А Д И И

    // ВСЕ ОБНОВЛЕНИЯ ОДНОЙ СТАДИИ КОНКРЕТНОГО ПРОЕКТА
    /**
     * @Route("/exec-cabinet/{projectID}/{stageID}", name="exec_one_stage", requirements={"projectID"="\d+", "stageID"="\d+"}, methods = "GET")
     */
     public function execOneStage($projectID, $stageID, ProjectRepository $pRepository, StageRepository $sRepository, RenewalRepository $rRepository)
    {
        $project = $pRepository -> find($projectID);
        $stage = $sRepository -> find($stageID);
        $renewals = $rRepository -> findByStageID($stageID);

        $data = [
            'project' => $project,
            'stage' => $stage,
            'renewals' => $renewals,
        ];

        return $this->render('exec_cabinet/execOneStage.html.twig', $data);
    }

    // ИЗМЕНИТЬ СТАТУС СТАДИИ
    /**
     * @Route("/exec-cabinet/{projectID}/{stageID}", name="change_status", methods = "POST", requirements={"projectID"="\d+", "stageID"="\d+"})
     */
    public function changeStatus($projectID, $stageID, Request $request,ProjectRepository $pRepository, StageRepository $sRepository, RenewalRepository $rRepository)
    {
        $status = $request->get('status');
        dump($status);

        $stage = $sRepository -> find($stageID);
        $stage->setStatus($status);


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($stage);
        $entityManager->flush();

        $project = $pRepository -> find($projectID);
        $renewals = $rRepository -> findByStageID($stageID);

        if ($status === 'в работе') {
            $message = 'Статус стадии изменён. Нажмите кнопку "Новая запись" для активации стадии и добавления первого комментария.';
            $data = [
                'project' => $project,
                'stage' => $stage,
                'renewals' => $renewals,
                'message' => $message,
            ];

            return $this->render('exec_cabinet/execOneStage.html.twig', $data);

        } else {
            $message = 'Статус стадии изменён. Для полноценной работы с проектом необходимо установить статус "в работе" хотя бы одной его стадии.';
            $stages = $sRepository -> findByProjectID($projectID);
            $data = [
                'project' => $project,
                'stages' => $stages,
                'message' => $message,
            ];

            return $this->render('exec_cabinet/execOneProject.html.twig', $data);
        };

    }

    // НОВАЯ ЗАПИСЬ (RENEWAL)
    /**
     * @Route("/exec-cabinet/{projectID}/{stageID}/addRenewal", name="add_renewal")
     */
    public function addRenewal($projectID, $stageID, ProjectRepository $pRepository, StageRepository $sRepository, Request $request)
    {
        if (!$this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute('index');
        };

        $project = $pRepository -> find($projectID);
        $stage = $sRepository -> find($stageID);
        
        $renewal = new Renewal();
        $renewal->setStageID($stage);

        $form = $this->createForm(RenewalFormType::class, $renewal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $uploads_dir = $this -> getParameter('sketches_directory');
            $sketches = $request -> files -> get('renewal_form')['sketches'];

            foreach($sketches as $file) 
            {
                $filename = md5(uniqid()) . '.' . $file -> guessExtension();
                $file -> move(
                    $uploads_dir,
                    $filename
                );
                $sketch = new Sketch();
                $sketch -> setName($filename);
                $renewal -> addSketch($sketch);
            };

            $em = $this -> getDoctrine() -> getManager();
            $em -> persist($renewal);
            $em -> flush();

            return $this->redirectToRoute('exec_one_stage', array('projectID'=>$projectID, 'stageID'=>$stageID));
        }

        return $this->render('exec_cabinet/addRenewal.html.twig', array(
            'renewalForm' => $form->createView(),
            'project' => $project,
            'stage' => $stage,
        ));
    }
    
    // РЕДАКТИРОВАТЬ ЗАПИСЬ (RENEWAL)
    /**
     * @Route("/exec-cabinet/{projectID}/{stageID}/{renewalID}/editRenewal", name="edit_renewal", requirements={"projectID"="\d+", "stageID"="\d+", "renewalID"="\d+"})
     */
    public function editRenewal($projectID, $stageID, $renewalID, ProjectRepository $pRepository, StageRepository $sRepository, RenewalRepository $rRepository, Request $request)
    {
        if (!$this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute('index');
        };

        $project = $pRepository -> find($projectID);
        $stage = $sRepository -> find($stageID);
        $renewal = $rRepository -> find($renewalID);

        $form = $this->createForm(RenewalEditFormType::class, $renewal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $uploads_dir = $this -> getParameter('sketches_directory');
            $sketches = $request -> files -> get('renewal_edit_form')['sketches'];

            foreach($sketches as $file) 
            {
                $filename = md5(uniqid()) . '.' . $file -> guessExtension();
                $file -> move(
                    $uploads_dir,
                    $filename
                );
                $sketch = new Sketch();
                $sketch -> setName($filename);
                $renewal -> addSketch($sketch);
            };

            $em = $this -> getDoctrine() -> getManager();
            $em -> persist($renewal);
            $em -> flush();

            return $this->redirectToRoute('exec_one_stage', array('projectID'=>$projectID, 'stageID'=>$stageID));
        }
        
        return $this->render('exec_cabinet/editRenewal.html.twig', array(
            'renewalEditForm' => $form->createView(),
            'project' => $project,
            'stage' => $stage,
            'renewal' => $renewal,
        ));
    }

     
    

}
