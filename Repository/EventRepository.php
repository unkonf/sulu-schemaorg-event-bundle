<?php
/*
 * This file is part of the Sulu Schema.org Event bundle.
 *
 * (c) bitExpert AG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace UnKonf\Sulu\SchemaOrgEventBundle\Repository;

use UnKonf\Sulu\SchemaOrgEventBundle\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function create(): Event
    {
        $entity = new Event();

        $this->getEntityManager()->persist($entity);

        return $entity;
    }

    /**
     * @throws ORMException
     */
    public function remove(int $id): void
    {
        /** @var object $entity */
        $entity = $this->getEntityManager()->getReference(
            $this->getClassName(),
            $id,
        );

        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    public function save(Event $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id): ?Event
    {
        return $this->find($id);
    }

    public function findByWebspaceKey(string $webspaceKey): ?Event
    {
        return $this->findOneBy(['webspace_key' => $webspaceKey]);
    }
}
