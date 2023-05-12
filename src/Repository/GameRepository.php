<?php

namespace App\Repository;

use App\Entity\Game;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function save(Game $entity, bool $flush = false): void
    {

        $entity->setStatus('EN_COURS');
        $entity->setCreatedAt(new DateTimeImmutable(date('d-m-y h:i:s')));
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function surrender(Game $entity,  UserRepository $userRepository, string $player1Id, string $player2Id, string $cowardId, bool $flush = false): void
    {


        $user1 = $userRepository->findById($player1Id);
        $user2 = $userRepository->findById($player2Id);

        if ($user1 && $player1Id === $cowardId) {
            $user1->setLooseCount($user1->getLooseCount() + 1);
            $user2->setWinCount($user2->getWinCount() + 1);
        }

        if ($user2 && $player2Id === $cowardId) {
            $user2->setLooseCount($user2->getLooseCount() + 1);
            $user1->setWinCount($user1->getWinCount() + 1);
        }


        $entity->setStatus('TERMINE');
        $entity->setFinishedAt(new DateTimeImmutable(date('d-m-y h:i:s')));



        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Game[] Returns an array of Game objects
     */
    public function findByStatus($value): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.status = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Game[] Returns an array of Game objects
     */
    public function findUserGamesPending($userId): array
    {

        $qb = $this->createQueryBuilder('g');
        $qb->andWhere(
            $qb->expr()->andX(
                $qb->expr()->isNull('g.winnerId'),
                $qb->expr()->eq('g.status', ':status'),
                $qb->expr()->orX(
                    $qb->expr()->eq('g.Player2Id', ':userId'),
                    $qb->expr()->eq('g.Player1Id', ':userId')
                )
            )
        );


        return $qb->orderBy('g.id', 'ASC')
            ->setParameter('userId', $userId)
            ->setParameter('status', 'EN_COURS')
            ->getQuery()
            ->getResult();
    }

    public function findOneById($value): ?Game
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
