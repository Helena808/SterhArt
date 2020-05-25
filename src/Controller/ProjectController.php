<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Project;
use App\Entity\Photo;
use App\Repository\ProjectRepository;
use App\Repository\PhotoRepository;
use App\Form\PortfolioFormType;
use App\Form\PortfolioEditFormType;


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
      * @Route("/portfolio/addPortfolio", name="add_portfolio")
      */
    public function addPortfolio(Request $request)
    {
    	if (!$this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute('index');
        };

        $project = new Project();

        $form = $this->createForm(PortfolioFormType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {            
            //var_dump($request);
            $uploads_dir = $this -> getParameter('photos_directory');
            $photos = $request -> files -> get('portfolio_form')['photos'];

            foreach($photos as $file) 
            {
                $filename = md5(uniqid()) . '.' . $file -> guessExtension();
                $file -> move(
                    $uploads_dir,
                    $filename
                );
                $photo = new Photo();
                $photo -> setName($filename);
                $project -> addPhoto($photo);
            };

            $em = $this -> getDoctrine() -> getManager();
            $em -> persist($project);
            $em -> flush();

            return $this->redirectToRoute('show_portfolio');
        }

        return $this->render('project/addPortfolio.html.twig', array(
            'portfolioForm' => $form->createView()
        ));

    }

// РЕДАКТИРОВАТЬ ПРОЕКТ В ПОРТФОЛИО
    /**
     * @Route("/portfolio/{id}/edit", name="edit_portfolio_one", requirements={"id"="\d+"})
     */
    public function editPortfolioOne($id, Request $request, ProjectRepository $pRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $this->getUser();

        $project = $pRepository -> find($id);

        $form = $this->createForm(PortfolioEditFormType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {            
            $uploads_dir = $this -> getParameter('photos_directory');
            $photos = $request -> files -> get('portfolio_edit_form')['photos'];

            foreach($photos as $file) 
            {
                $filename = md5(uniqid()) . '.' . $file -> guessExtension();
                $file -> move(
                    $uploads_dir,
                    $filename
                );
                $photo = new Photo();
                $photo -> setName($filename);
                $project -> addPhoto($photo);
            };

            $em = $this -> getDoctrine() -> getManager();
            $em -> persist($project);
            $em -> flush();

            
            return $this->redirectToRoute('show_portfolio_one', array('id'=>$id));
            }

        return $this->render('project/editPortfolio.html.twig', array(
            'portfolioEditForm' => $form->createView(),
            'user' => $user,
            'project' => $project,
        ));
    }




// УДАЛИТЬ ПРОЕКТ ИЗ ПОРТФОЛИО
    /**
     * @Route("/portfolio/{id}/delete", name="delete_portfolio_one", requirements={"id"="\d+"})
     */
    public function deletePortfolioOne($id, ProjectRepository $pRepository, PhotoRepository $phRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $project = $pRepository -> find($id);
        $photos = $phRepository -> findByProjectId($id);

        $em = $this -> getDoctrine() -> getManager();

        foreach($photos as $photo)
        {
            $project -> removePhoto($photo);
            $em -> remove($photo);
        };

        $em -> remove($project);
        $em -> flush();

        return $this->redirectToRoute('show_portfolio');
    }

}
