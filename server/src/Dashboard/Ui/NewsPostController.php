<?php

namespace App\Dashboard\Ui;

use App\Dashboard\Application\Exception\NewsPostNotFoundException;
use App\Dashboard\Application\NewsPostService;
use App\Dashboard\Domain\NewsPostRepositoryInterface;
use App\Kernel\Authorization\AuthManagerInterface;
use App\Kernel\JsonResponse\JsonResponse;

class NewsPostController
{
    public function __construct(
        private readonly AuthManagerInterface $authManager,
        private readonly NewsPostService $newsPostService,
        private readonly NewsPostRepositoryInterface $newsPostRepository,
    ) {
        if (false === $this->authManager->isAuthorized()) {
            JsonResponse::send(['error' => 'Unauthorized'], 401);
        }
    }

    public function getNewsPosts(): void
    {
        try {
            $newsPosts = $this->newsPostRepository->getAll();

            JsonResponse::send($newsPosts);
        } catch (\Throwable) {
            JsonResponse::send(['error' => 'Failed to get news posts'], 500);
        }
    }

    public function createNewsPost(NewsPostDto $newsPostDto): void
    {
        try {
            $this->newsPostService->createNewsPost($newsPostDto);

            JsonResponse::send(['message' => 'News post created'], 201);
        } catch (\Throwable) {
            JsonResponse::send(['error' => 'Failed to create news post'], 500);
        }
    }

    public function updateNewsPost(NewsPostDto $newsPostDto): void
    {
        try {
            $this->newsPostService->updateNewsPost($newsPostDto);

            JsonResponse::send(['message' => 'News post updated'], 200);
        } catch (NewsPostNotFoundException) {
            JsonResponse::send(['error' => 'News post not found'], 404);
        }  catch (\Throwable) {
            JsonResponse::send(['error' => 'Failed to update news post'], 500);
        }
    }

    public function deleteNewsPost(DeleteNewsPostDto $deleteNewsPostDto): void
    {
        try {
            $this->newsPostService->deleteNewsPost($deleteNewsPostDto->id);

            JsonResponse::send(['message' => 'News post deleted'], 200);
        } catch (NewsPostNotFoundException) {
            JsonResponse::send(['error' => 'News post not found'], 404);
        }  catch (\Throwable) {
            JsonResponse::send(['error' => 'Failed to delete news post'], 500);
        }
    }
}
