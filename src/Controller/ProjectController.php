<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Repository\StageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
// ВСЁ ПОРТФОЛИО
    /**
     * @Route("/portfolio", name="show_portfolio")
     */
    public function showPortfolioAll(ProjectRepository $pRepository)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $projects = $pRepository -> findPortfolioProjects();
        $data = [
        	'user' => $user,
            'projects' => $projects,
        ];

        return $this->render('project/portfolio.html.twig', $data);
    }

// ОТДЕЛЬНЫЙ ПРОЕКТ ИЗ ПОРТФОЛИО ПО id
    /**
     * @Route("/portfolio/{id}/", name="show_portfolio_one", requirements={"id"="\d+"})
     */
    public function showPortfolioOne($id, ProjectRepository $pRepository)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $project = $pRepository -> find($id);
        
        if (!$project) {
            throw $this->createNotFoundException('Проект не найден');
        };

        $data = [
            'user' => $user,
            'project' => $project,
        ];

        return $this->render('project/onePortfolio.html.twig', $data);
    }


// ДОБАВИТЬ ПРОЕКТ В ПОРТФОЛИО
     /**
      * @Route("/portfolio/addPortfolio", name="add_portfolio_open", methods = "GET")
      */
    public function addPortfolioOpen()
    {
    	return $this->render('project/addPortfolio.html.twig');
    }
     
 // Обработчик формы
    /**
     * @Route("/portfolio/addPortfolio", name="add_portfolio_submit", methods = "POST")
     */
    public function addPortfolioSubmit(Request $request)
    {
    	$projectTitle = $request->get('projectTitle');
    	$city = $request->get('city');
    	$description = $request->get('description');

    	$project = new Project;
    	$project->setProjectTitle($projectTitle);
    	$project->setCity($city);
    	$project->setDescription($description);

    	$entityManager = $this->getDoctrine()->getManager();
    	$entityManager->persist($project);
    	$entityManager->flush();

    	return $this->redirectToRoute('show_portfolio');
    }
}
