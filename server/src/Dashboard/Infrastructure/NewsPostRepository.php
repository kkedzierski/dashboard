<?php

namespace App\Dashboard\Infrastructure;

use App\Dashboard\Domain\NewsPost;
use App\Dashboard\Domain\NewsPostRepositoryInterface;
use App\Kernel\Database\QueryBuilder;

class NewsPostRepository implements NewsPostRepositoryInterface
{
    public function __construct(
        private readonly QueryBuilder $queryBuilder
    ) {
    }

    public function getById(string $id): array
    {
        return $this->queryBuilder->createQueryBuilder()
            ->select()
            ->from('news_post')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->getResult();
    }

    public function save(NewsPost $newsPost): void
    {
        $this->queryBuilder->createQueryBuilder()
            ->insert(
                'news_post',
                ['id', 'title', 'content'],
                [':id', ':title', ':content']
            )->setParameters([
                'id' => $newsPost->getId()->toString(),
                'title' => $newsPost->getTitle(),
                'content' => $newsPost->getContent()
            ])
            ->execute();
    }

    public function update(NewsPost $newsPost): void
    {
        $this->queryBuilder->createQueryBuilder()
            ->update(
                'news_post',
                ['title', 'content'],
                [':title', ':content'],
            )
            ->where('id = :id')
            ->setParameters([
                'id' => $newsPost->getId()->toString(),
                'title' => $newsPost->getTitle(),
                'content' => $newsPost->getContent(),
            ])
            ->execute();
    }

    public function delete(string $id): void
    {
        $this->queryBuilder
            ->delete('news_post')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->execute();
    }

    public function getAll(): array
    {
        return $this->queryBuilder->createQueryBuilder()
            ->select(['id', 'title', 'content', 'created_at'])
            ->from('news_post')
            ->getResult();
    }
}
