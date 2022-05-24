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
     * @Route("/library/show/{bookId}", name="library-show-id")
     */
    public function showBookId(
        LibraryRepository $libraryRepository,
        int $bookId
    ): Response {
        $book = $libraryRepository
            ->find($bookId);
        if (!$book) {
            throw $this->createNotFoundException(
                'No book found with id ' . $bookId
            );
        }

        return $this->render('library/book.html.twig', [
            'title' => "Bok",
            'header' => "Här är boken med ID: ${bookId}",
            'book' => $book
        ]);
    }

    /**
     * @Route("/library/create", name="library-create")
     */
    public function createBookForm(): Response
    {
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
    public function createBookProcess(Request $request, ManagerRegistry $doctrine): Response
    {
        $title  = $request->request->get('title');
        $author = $request->request->get('author');
        $isbn   = $request->request->get('isbn');
        $img    = $request->request->get('img');
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

        return $this->redirectToRoute("library-show-all");
        ;
    }

    /**
     * @Route("/library/update/{bookId}", name="library-update")
     */
    public function updateBookForm(
        LibraryRepository $libraryRepository,
        int $bookId
    ): Response {
            $book = $libraryRepository
                ->find($bookId);

            return $this->render('library/update.html.twig', [
                'title' => "Uppdatera bok",
                'header' => "Uppdatera boken med ID: ${bookId}",
                'book' => $book
            ]);
    }

    /**
     * @Route(
     *      "/library/update/process/{bookId}",
     *      name="library-update-process",
     *      methods={"POST"}
     * )
     */
    public function updateBookProcess(
        Request $request,
        ManagerRegistry $doctrine,
        int $bookId
    ): Response {
        $title  = $request->request->get('title');
        $author = $request->request->get('author');
        $isbn   = $request->request->get('isbn');
        $img    = $request->request->get('img');
        $entityManager = $doctrine->getManager();
        $library = $entityManager->getRepository(Library::class)->find($bookId);
        if (!$library) {
            throw $this->createNotFoundException(
                'No book found with id ' . $bookId
            );
        }
        $library->setBookTitle($title);
        $library->setAuthor($author);
        $library->setBookIsbn($isbn);
        if ($img != null) {
            $library->setImg($img);
        }

        $entityManager->flush();

        return $this->redirectToRoute('library-show-id', array('id' => $bookId));
    }

    /**
     * @Route("/library/delete/{bookId}", name="library-delete")
     */
    public function deleteBookForm(
        LibraryRepository $libraryRepository,
        int $bookId
    ): Response {
        $book = $libraryRepository
            ->find($bookId);

        return $this->render('library/delete.html.twig', [
            'title' => "Ta bort bok",
            'header' => "Ta bort boken med ID: ${bookId}",
            'book' => $book
        ]);
    }

    /**
     * @Route(
     *      "/library/delete/process/{bookId}",
     *      name="library-delete-process",
     *      methods={"POST"}
     * )
     */
    public function deleteBookProcess(
        ManagerRegistry $doctrine,
        int $bookId
    ): Response {
        $entityManager = $doctrine->getManager();
        $library = $entityManager->getRepository(Library::class)->find($bookId);
        if (!$library) {
            throw $this->createNotFoundException(
                'No book found with id ' . $bookId
            );
        }

        $entityManager->remove($library);
        $entityManager->flush();

        return $this->redirectToRoute('library-show-all');
    }
}
