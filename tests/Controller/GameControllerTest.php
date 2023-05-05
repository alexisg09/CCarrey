<?php

namespace App\Test\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private GameRepository $repository;
    private string $path = '/game/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Game::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Game index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'game[createdAt]' => 'Testing',
            'game[finishedAt]' => 'Testing',
            'game[Player1Id]' => 'Testing',
            'game[Player2Id]' => 'Testing',
        ]);

        self::assertResponseRedirects('/game/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Game();
        $fixture->setCreatedAt('My Title');
        $fixture->setFinishedAt('My Title');
        $fixture->setPlayer1Id('My Title');
        $fixture->setPlayer2Id('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Game');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Game();
        $fixture->setCreatedAt('My Title');
        $fixture->setFinishedAt('My Title');
        $fixture->setPlayer1Id('My Title');
        $fixture->setPlayer2Id('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'game[createdAt]' => 'Something New',
            'game[finishedAt]' => 'Something New',
            'game[Player1Id]' => 'Something New',
            'game[Player2Id]' => 'Something New',
        ]);

        self::assertResponseRedirects('/game/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getFinishedAt());
        self::assertSame('Something New', $fixture[0]->getPlayer1Id());
        self::assertSame('Something New', $fixture[0]->getPlayer2Id());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Game();
        $fixture->setCreatedAt('My Title');
        $fixture->setFinishedAt('My Title');
        $fixture->setPlayer1Id('My Title');
        $fixture->setPlayer2Id('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/game/');
    }
}
