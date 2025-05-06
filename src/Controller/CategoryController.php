<?php


namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryForm;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\New_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController {


	#[Route('/list-categories', name:"list-categories")]
	public function displayListCategories(CategoryRepository $categoryRepository) {
		
		$categories = $categoryRepository->findAll();

		return $this->render('list-categories.html.twig', [
			'categories' => $categories
		]);
	}


	#[Route('/details-category/{id}', name: "details-category")]
	public function displayShowCategory($id, CategoryRepository $categoryRepository) {
		
		$category = $categoryRepository->find($id);

		return $this->render('details-category.html.twig', [
			'category' => $category
		]);

	}

	#[Route('/create-category', name: "create-category")]
	public function displayCreateCategory(Request $request, EntityManagerInterface $entityManager) {

		// je créé une instance de category
		$category = new Category();

		// je créé le formulaire 
		// en utilisant le gabarit de formulaire "CategoryForm" généré avec "make:form"
		// et l'instance de category
		$categoryForm = $this->createForm(CategoryForm::class, $category);

		// je stocke dans la variable du formulaire les données envoyées en POST
		$categoryForm->handleRequest($request);

		// je regarde s'il y a bien des données envoyées en POST
		if ($categoryForm->isSubmitted()) {
			// si oui, je sauvegarde la category
			// dont les propriétés ont été automatiquement remplies 
			// par symfony et le système de formulaire
			$category->setCreatedAt(new \DateTime());
			$entityManager->persist($category);
			$entityManager->flush();
		}


		return $this->render('create-category.html.twig', [
			'categoryForm' => $categoryForm->createView()
		]);

	}

	#[Route("/update-category/{id}", name: "update-category")]
	public function displayUpdateCategory($id, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $entityManager) {
	
		$category = $categoryRepository->find($id);

		$categoryForm = $this->createForm(CategoryForm::class, $category);

		$categoryForm->handleRequest($request);

		if ($categoryForm->isSubmitted()) {
			$entityManager->persist($category);
			$entityManager->flush();
		}
		
		return $this->render('update-category.html.twig', [
			'categoryForm' => $categoryForm->createView()
		]);
	}

}

