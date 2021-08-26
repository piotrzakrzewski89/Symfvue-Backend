<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;


final class BookItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Book::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        $book = $this->entityManager->getRepository('App\Entity\Book')->find($id);
        $user = $this->entityManager->getRepository('App\Entity\User')->find($book->getUser());
        $book->setUserObject([$user->getId(), $user->getEmail()]);

        return $book;
    }
}
