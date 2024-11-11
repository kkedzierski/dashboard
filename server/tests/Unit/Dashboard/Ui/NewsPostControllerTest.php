<?php

namespace Unit\Dashboard\Ui;

use App\Dashboard\Application\NewsPostService;
use App\Dashboard\Domain\NewsPostRepositoryInterface;
use App\Dashboard\Ui\DeleteNewsPostDto;
use App\Dashboard\Ui\NewsPostController;
use App\Dashboard\Ui\NewsPostDto;
use App\Kernel\Authorization\AuthManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class NewsPostControllerTest extends TestCase
{
    private MockObject&AuthManagerInterface $authManager;
    private MockObject&NewsPostService $newsPostService;
    private MockObject&NewsPostRepositoryInterface $newsPostRepository;

    private NewsPostController $controller;

    protected function setUp(): void
    {
        $this->authManager = $this->createMock(AuthManagerInterface::class);
        $this->newsPostService = $this->createMock(NewsPostService::class);
        $this->newsPostRepository = $this->createMock(NewsPostRepositoryInterface::class);

        $this->controller = new NewsPostController(
            $this->authManager,
            $this->newsPostService,
            $this->newsPostRepository
        );
    }

    public function testGetNewsPosts(): void
    {
        $this->newsPostRepository
            ->expects($this->once())
            ->method('getAll')
            ->willReturn([]);

        $this->controller->getNewsPosts();
    }

    public function testCreateNewsPost(): void
    {
        $newsPostDto = new NewsPostDto('title', 'content');
        $this->newsPostService
            ->expects($this->once())
            ->method('createNewsPost');

        $this->controller->createNewsPost($newsPostDto);
    }

    public function testUpdateNewsPost(): void
    {
        $newsPostDto = new NewsPostDto('title', 'content');
        $this->newsPostService
            ->expects($this->once())
            ->method('updateNewsPost');

        $this->controller->updateNewsPost($newsPostDto);
    }

    public function testDeleteNewsPost(): void
    {
        $deleteNewsPostDto = new DeleteNewsPostDto('1');
        $this->newsPostService
            ->expects($this->once())
            ->method('deleteNewsPost');

        $this->controller->deleteNewsPost($deleteNewsPostDto);
    }
}
