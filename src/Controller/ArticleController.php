<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController {

	#[Route('/create-article', name: "create-article")]
	public function displayCreateArticle(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository,) {

		if ($request->isMethod("POST")) {

			$title = $request->request->get('title');
			$description = $request->request->get('description');
			$content = $request->request->get('content');
			$image = $request->request->get('image');




			// méthode 1
			// permet de créer un article
			// utiliser les fonctions "set"
			// pour remplir les données de l'instance de classe Article
			//$article = new Article();
			//$article->setTitle($title);
			//$article->setDescription($description);
			//$article->setContent($content);
			//$article->setImage($image);
			//$article->setIsPublished(true);
			//$article->setCreatedAt(new \DateTime());

			// méthode 2
			// avec le constructor
			// permet de faire de l'encapsulation
			$article = new Article($title, $content, $description, $image);

			// récupère les données (les valeurs des propriétés) de la l'instance de classe Article (entité Article)
			// et les insère dans la table Article
			// Symfony peut faire ça directement, car on a utilisé le mapping
			// sur chaque propriété de la classe Article pour les faire correspondre à des 
			// colonnes dans la table article
			$entityManager->persist($article);
			$entityManager->flush();

		}

		$categories = $categoryRepository->findAll();

		return $this->render('create-article.html.twig', [
			'categories' => $categories
		]);
	}

	#[Route('/list-articles', name: 'list-articles')]
	public function displayListArticles(ArticleRepository $articleRepository) {

		// permet de faire une requête SQL SELECT * sur la table article
		$articles = $articleRepository->findAll();

		return $this->render('list-articles.html.twig', [
			'articles' => $articles
		]);

	}


	#[Route('/details-article/{id}', name: "details-article")]
	public function displayDetailsArticle($id, ArticleRepository $articleRepository)
	{
		$article = $articleRepository->find($id);

		// si l'article n'a pas été trouvé pour l'id demandé
		// on envoie l'utilisateur vers la page qui affiche une erreur 404
		if (!$article) {
			return $this->redirectToRoute('404');
		}

		return $this->render('details-article.html.twig', [
			'article' => $article
		]);
	}

	#[Route('/delete-article/{id}', name: "delete-article")]
	public function deleteArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager) 
	{
		// pour supprimer un article, je dois d'abord le récupérer
		$article = $articleRepository->find($id);

		// j'utilise la méthode remove de la classe EntityManager qui prend en parametre l'article à supprimer
		$entityManager->remove($article);
		$entityManager->flush();

		// j'ajoute un message flash pour notifier que l'article est supprimé
		$this->addFlash('success', 'article supprimé');

		// je redirige vers la page de liste
		return $this->redirectToRoute('list-articles');
	}

	#[Route(path: '/update-article/{id}', name: "update-article")]
	public function displayUpdateArticle($id, ArticleRepository $articleRepository, Request $request, EntityManagerInterface $entityManager) {

		$article = $articleRepository->find($id);


		if ($request->isMethod("POST")) {

			$title = $request->request->get('title');
			$description = $request->request->get('description');
			$content = $request->request->get('content');
			$image = $request->request->get('image');
						
			// méthode 1 : mise à jour de l'article avec les fonctions set (setter)
			//$article->setTitle($title);
			//$article->setDescription($description);
			//$article->setContent($content);
			//$article->setImage($image);

			// méthode : mise de l'article avec une méthode update (respecte l'encapsulation)

			//j'utilise la methose setter pour mettre à jour les propriétés de l'entité Article
			$article->setTitle($title);
            $article->setContent($content);
            $article->setDescription($description);
            $article->setImage($image);
            // persist permet d'enregistrer l'entité $article
			$entityManager->persist($article);
			
			$entityManager->flush();

		
		}

		return $this->render('update-article.html.twig', [
			'article' => $article
		]);

	}

}