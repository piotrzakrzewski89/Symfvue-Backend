<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Book;
use App\Entity\User;


class UserTest extends ApiTestCase
{
    private function login(){

        $client = self::createClient();

        $response = $client->request('POST', '/authentication_token', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'tester@test.com',
                'password' => 'testtest123',
            ],
        ]);


        $json = $response->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);
    
        return ['auth_bearer' => $json['token']];
    }

    public function testLogin(): void
    {
        $client = self::createClient();

        $user = new User();
        $user->setEmail('tester' . rand(1, 100) . '@test.com');
        $user->setPassword(
            self::getContainer()->get('security.user_password_hasher')->hashPassword($user, 'testtest' . rand(1, 100))
        );

        $manager = self::getContainer()->get('doctrine')->getManager();
        $manager->persist($user);
        $manager->flush();

        // retrieve a token


        // test not authorized
        $client->request('GET', '/users/me');
        $this->assertResponseStatusCodeSame(401);

        // test authorized
        $client->request('GET', '/users/me', $this->login());
        $this->assertResponseIsSuccessful();
    }

    public function testBook(): void
    {

        $client = self::createClient();

        // test not authorized
        $client->request('GET', '/books');
        $this->assertResponseStatusCodeSame(401);

        $client->request('GET', '/books', $this->login());
        $this->assertResponseIsSuccessful();

        $book = new Book();
        $book->setISBN(rand(1, 100));
        $book->setTitle('testtesttest123123123');
        $book->setDescription(
            'testtesttest123123123
            testtesttest123123123
            testtesttest123123123'
        );
        $book->setPublicationDate(new \DateTime());
        $manager = self::getContainer()->get('doctrine')->getManager();
        $manager->persist($book);
        $manager->flush();
    }
}
