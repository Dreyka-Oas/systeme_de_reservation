<?php

namespace App\Tests\Controller;

use App\Entity\Coach;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CoachControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/coach/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Coach::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Coach index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'coach[email]' => 'Testing',
            'coach[password]' => 'Testing',
            'coach[name]' => 'Testing',
            'coach[lastName]' => 'Testing',
            'coach[roles]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Coach();
        $fixture->setEmail('My Title');
        $fixture->setPassword('My Title');
        $fixture->setName('My Title');
        $fixture->setLastName('My Title');
        $fixture->setRoles('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Coach');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Coach();
        $fixture->setEmail('Value');
        $fixture->setPassword('Value');
        $fixture->setName('Value');
        $fixture->setLastName('Value');
        $fixture->setRoles('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'coach[email]' => 'Something New',
            'coach[password]' => 'Something New',
            'coach[name]' => 'Something New',
            'coach[lastName]' => 'Something New',
            'coach[roles]' => 'Something New',
        ]);

        self::assertResponseRedirects('/coach/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getPassword());
        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getLastName());
        self::assertSame('Something New', $fixture[0]->getRoles());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Coach();
        $fixture->setEmail('Value');
        $fixture->setPassword('Value');
        $fixture->setName('Value');
        $fixture->setLastName('Value');
        $fixture->setRoles('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/coach/');
        self::assertSame(0, $this->repository->count([]));
    }
}
