<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController {

	#[Route('/create-article', name: "create-article")]

    //entityManager permet d'interagir et gerer les transactions avec la base de données, il permet aussi de créer, recupérer, modifier ou supprimer les entitées, parmis lesquelles les Articles.
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
            //Cette ligne exécute toutes les opérations en attente, comme les persist, remove, etc.
			$entityManager->flush();

		}

		return $this->render('create-article.html.twig');
	}
    
    #[Route('/list-articles', name: 'list-articles')]
    public function displayListArticles(ArticleRepository $articleRepository) {
        // cette ligne appelle la méthode findAll qui recupère tous les Articles d'un table
        $articles = $articleRepository->findAll();
        // findAll retourne les Article enregistré de la base de donnée au fichier list-articles.html.twig qui permet de les afficher
		return $this->render('list-articles.html.twig', [
			'articles' => $articles
        ]);
    }

}
