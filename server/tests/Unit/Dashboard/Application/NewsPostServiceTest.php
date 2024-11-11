<?php

namespace Unit\Dashboard\Application;

use App\Dashboard\Application\Exception\NewsPostNotFoundException;
use App\Dashboard\Application\Exception\SaveNewsPostFailedException;
use App\Dashboard\Application\NewsPostService;
use App\Dashboard\Domain\NewsPost;
use App\Dashboard\Domain\NewsPostFactory;
use App\Dashboard\Domain\NewsPostRepositoryInterface;
use App\Dashboard\Ui\NewsPostDto;
use App\Kernel\Logger\LoggerInterface;
use App\Kernel\Serializer\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class NewsPostServiceTest extends TestCase
{
    private MockObject&NewsPostRepositoryInterface $newsPostRepository;
    private MockObject&NewsPostFactory $newsPostFactory;
    private MockObject&LoggerInterface $logger;
    private MockObject&SerializerInterface $serializer;

    private NewsPostService $service;

    protected function setUp(): void
    {
        $this->newsPostRepository = $this->createMock(NewsPostRepositoryInterface::class);
        $this->newsPostFactory = $this->createMock(NewsPostFactory::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->serializer = $this->createMock(SerializerInterface::class);

        $this->service = new NewsPostService(
            $this->newsPostRepository,
            $this->newsPostFactory,
            $this->logger,
            $this->serializer
        );
    }

    public function testThrowAndLogExceptionWhenCreatingUserFails(): void
    {
        $newsPostDto = new NewsPostDto('id', 'title', 'content');
        $newsPost = new NewsPost('title', 'content');

        $this->newsPostFactory
            ->expects($this->once())
            ->method('create')
            ->with($newsPostDto)
            ->willReturn($newsPost);
        $this->newsPostRepository
            ->expects($this->once())
            ->method('save')
            ->with($newsPost)
            ->willThrowException($exception = new \Exception('save failed'));
        $this->logger
            ->expects($this->once())
            ->method('logException')
            ->with(
                'Save news post failed.',
                $exception,
                ['title' => $newsPostDto->title]
            );

        $this->expectException(SaveNewsPostFailedException::class);

        $this->service->createNewsPost($newsPostDto);
    }

    public function testThrowExceptionWhenNewsPostNotFoundOnUpdate(): void
    {
        $newsPostDto = new NewsPostDto('id', 'title', 'content');

        $this->newsPostRepository
            ->expects($this->once())
            ->method('getById')
            ->with($newsPostDto->id)
            ->willReturn([]);
        $this->serializer
            ->expects($this->never())
            ->method('denormalize');

        $this->expectException(NewsPostNotFoundException::class);

        $this->service->updateNewsPost($newsPostDto);
    }

    public function testLogAndThrowExceptionWhenNewsPostUpdateFails(): void
    {
        $newsPostDto = new NewsPostDto('id', 'title', 'content');
        $newsPost = new NewsPost('title', 'content');
        $this->newsPostRepository
            ->expects($this->once())
            ->method('getById')
            ->with($newsPostDto->id)
            ->willReturn([[]]);
        $this->serializer
            ->expects($this->once())
            ->method('denormalize')
            ->with([], NewsPost::class)
            ->willReturn($newsPost);
        $this->newsPostRepository
            ->expects($this->once())
            ->method('update')
            ->with($newsPost)
            ->willThrowException($exception = new \Exception('update failed'));
        $this->logger
            ->expects($this->once())
            ->method('logException')
            ->with(
                'Update news post failed.',
                $exception,
                ['title' => $newsPostDto->title]
            );

        $this->expectException(SaveNewsPostFailedException::class);

        $this->service->updateNewsPost($newsPostDto);
    }

    public function testThrowExceptionWhenNewsPostNotFoundOnDelete(): void
    {
        $this->newsPostRepository
            ->expects($this->once())
            ->method('getById')
            ->with('1')
            ->willReturn([]);
        $this->newsPostRepository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(NewsPostNotFoundException::class);

        $this->service->deleteNewsPost('1');
    }

    public function testLogAndThrowExceptionWhenDeleteUserFails(): void
    {
        $this->newsPostRepository
            ->expects($this->once())
            ->method('getById')
            ->with('1')
            ->willReturn([[]]);
        $this->newsPostRepository
            ->expects($this->once())
            ->method('delete')
            ->with('1')
            ->willThrowException($exception = new \Exception('delete failed'));
        $this->logger
            ->expects($this->once())
            ->method('logException')
            ->with(
                'Delete news post failed.',
                $exception,
                ['id' => '1']
            );

        $this->expectException(SaveNewsPostFailedException::class);

        $this->service->deleteNewsPost('1');
    }
}
