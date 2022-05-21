<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Library;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\LibraryRepository;

class LibraryController extends AbstractController
{
    /**
     * @Route("/library", name="library-home")
     */
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'title' => "Biblotek",
            'header' => "Välkommen till vårt internet biblotek!",
        ]);
    }

    /**
    * @Route("/library/show", name="library-show-all")
    */
    public function showAllBooks(
        LibraryRepository $libraryRepository
    ): Response {
        $books = $libraryRepository
            ->findAll();

        return $this->render('library/books.html.twig', [
            'title' => "Böcker",
            'header' => "Här är alla våra böcker",
            'books' => $books
        ]);
    }

    /**
     * @Route("/library/create", name="library-create")
     */
    public function createBookForm(): Response {
        return $this->render('library/create.html.twig', [
            'title' => "Lägg till bok",
            'header' => "Lägg till en bok",
        ]);
    }

    /**
     * @Route(
     *      "/library/create/process",
     *      name="library-create-process",
     *      methods={"POST"}
     * )
     */
    public function createBookProcess(Request $request, ManagerRegistry $doctrine): Response {
        $title  = $request->request->get('title');
        $author  = $request->request->get('author');
        $isbn  = $request->request->get('isbn');
        $img  = $request->request->get('img');
        $entityManager = $doctrine->getManager();

        $library = new Library();
        $library->setBookTitle($title);
        $library->setAuthor($author);
        $library->setBookIsbn($isbn);
        if ($img != null) {
            $library->setImg($img);
        }
    
        // tell Doctrine you want to (eventually) save the Product
        // (no queries yet)
        $entityManager->persist($library);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $this->redirectToRoute("library-show-all");;
    }
}
