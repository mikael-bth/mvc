<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ProductRepository;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product-home")
     */
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'title' => "Produkt",
            'header' => "Produkt",
        ]);
    }

    /**
     * @Route("/product/create", name="product-create")
     */
    public function createProduct(
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();

        $product = new Product();
        $product->setName('Keyboard_num_' . rand(1, 9));
        $product->setValue(rand(100, 999));

        // tell Doctrine you want to (eventually) save the Product
        // (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id ' . $product->getId());
    }

    /**
    * @Route("/product/show", name="product-show-all")
    */
    public function showAllProduct(
        ProductRepository $productRepository
    ): Response {
        $products = $productRepository
            ->findAll();

        return $this->json($products);
    }

    /**
     * @Route("/product/show/{productId}", name="product-show-id")
     */
    public function showProductById(
        ProductRepository $productRepository,
        int $productId
    ): Response {
        $product = $productRepository
            ->find($productId);

        return $this->json($product);
    }

    /**
     * @Route("/product/delete/{productId}", name="product-delete")
     */
    public function deleteProductById(
        ManagerRegistry $doctrine,
        int $productId
    ): Response {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($productId);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $productId
            );
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('product-show-all');
    }

    /**
     * @Route("/product/update/{productId}/{value}", name="product-update")
     */
    public function updateProduct(
        ManagerRegistry $doctrine,
        int $productId,
        int $value
    ): Response {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($productId);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $productId
            );
        }

        $product->setValue($value);
        $entityManager->flush();

        return $this->redirectToRoute('product-show-all');
    }
}
