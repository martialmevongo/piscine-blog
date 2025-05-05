<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController {

	#[Route('/create-article', name: "create-article")]
	public function displayCreateArticle(Request $request, EntityManagerInterface $entityManager) {

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

		return $this->render('create-article.html.twig');
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

}