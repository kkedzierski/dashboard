<?php

namespace App\Dashboard\Application;

use App\Dashboard\Application\Exception\NewsPostNotFoundException;
use App\Dashboard\Application\Exception\SaveNewsPostFailedException;
use App\Dashboard\Domain\NewsPost;
use App\Dashboard\Domain\NewsPostFactory;
use App\Dashboard\Domain\NewsPostRepositoryInterface;
use App\Dashboard\Ui\NewsPostDto;
use App\Kernel\Logger\LoggerInterface;
use App\Kernel\Serializer\SerializerInterface;

class NewsPostService
{
    public function __construct(
        private readonly NewsPostRepositoryInterface $newsPostRepository,
        private readonly NewsPostFactory $newsPostFactory,
        private readonly LoggerInterface $logger,
        private readonly SerializerInterface $serializer,
    ) {
    }

    /**
     * @throws SaveNewsPostFailedException
     */
    public function createNewsPost(NewsPostDto $newsPostDto): void
    {
        try {
            $newsPost = $this->newsPostFactory->create($newsPostDto);
            $this->newsPostRepository->save($newsPost);

            return;
        } catch (\Throwable $exception) {
            $this->logger->logException(
                'Save news post failed.',
                $exception,
                ['title' => $newsPostDto->title]
            );

            throw new SaveNewsPostFailedException();
        }
    }

    /**
     * @throws SaveNewsPostFailedException
     * @throws NewsPostNotFoundException
     */
    public function updateNewsPost(NewsPostDto $newsPostDto): void
    {
        $newsPost = $this->newsPostRepository->getById($newsPostDto->id);

        if (empty($newsPost)) {
            throw new NewsPostNotFoundException();
        }

        try {
            /** @var NewsPost $newsPost */
            $newsPost = $this->serializer->denormalize($newsPost[0], NewsPost::class);
            $newsPost->setTitle($newsPostDto->title ?? $newsPost->getTitle());
            $newsPost->setContent($newsPostDto->content ?? $newsPost->getContent());

            $this->newsPostRepository->update($newsPost);

            return;
        } catch (\Throwable $exception) {
            $this->logger->logException(
                'Update news post failed.',
                $exception,
                ['title' => $newsPostDto->title]
            );

            throw new SaveNewsPostFailedException();
        }
    }

    /**
     * @throws SaveNewsPostFailedException
     * @throws NewsPostNotFoundException
     */
    public function deleteNewsPost(string $id): void
    {
        $newsPost = $this->newsPostRepository->getById($id);

        if (empty($newsPost)) {
            throw new NewsPostNotFoundException();
        }

        try {
            $this->newsPostRepository->delete($id);

            return;
        } catch (\Throwable $exception) {
            $this->logger->logException(
                'Delete news post failed.',
                $exception,
                ['id' => $id]
            );

            throw new SaveNewsPostFailedException();
        }
    }
}
