<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;

final class BookCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{

    private $collectionDataProvider;

    public function __construct(CollectionDataProviderInterface $collectionDataProvider, BookRepository $bookRepository,EntityManagerInterface $entityManager)
    {
        $this->collectionDataProvider = $collectionDataProvider;
        $this->bookRepository = $bookRepository;
        $this->entityManager = $entityManager;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Book::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {

        $books = $this->bookRepository->findAll();

        foreach ($books as $book) {
            $user = $this->entityManager->getRepository('App\Entity\User')->find($book->getUser());
            $book->setUserObject([$user->getId(), $user->getEmail()]);
        }

        return $books;
    }
}
