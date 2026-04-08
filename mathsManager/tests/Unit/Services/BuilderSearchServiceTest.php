<?php

namespace Tests\Unit\Services;

use App\Services\BuilderSearchService;
use App\Services\FileUploadService;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\TestCase;

class BuilderSearchServiceTest extends TestCase
{
    private FileUploadService $fileUploadService;
    private BuilderSearchService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fileUploadService = $this->createMock(FileUploadService::class);
        $this->service = new BuilderSearchService($this->fileUploadService);
    }

    public function test_withImages_attaches_image_paths_to_items(): void
    {
        $item = new \stdClass();
        $item->id = 42;

        $paginator = $this->makePaginator([$item]);

        $this->fileUploadService
            ->expects($this->once())
            ->method('getFiles')
            ->with('exercises', 'exercise-42/statement', true, 'img-*')
            ->willReturn([
                'exercises/exercise-42/statement/img-1.jpg',
                'exercises/exercise-42/statement/img-2.jpg',
            ]);

        $result = $this->service->withImages(
            $paginator,
            'exercises',
            fn ($e) => 'exercise-' . $e->id . '/statement'
        );

        $items = $result->getCollection();
        $this->assertSame([
            'img-1' => 'exercises/exercise-42/statement/img-1.jpg',
            'img-2' => 'exercises/exercise-42/statement/img-2.jpg',
        ], $items[0]->image_paths);
    }

    public function test_withImages_sets_null_when_no_files_found(): void
    {
        $item = new \stdClass();
        $item->id = 1;

        $paginator = $this->makePaginator([$item]);

        $this->fileUploadService
            ->method('getFiles')
            ->willReturn([]);

        $result = $this->service->withImages($paginator, 'private-exercises', fn ($e) => 'private-exercise-' . $e->id);

        $this->assertNull($result->getCollection()[0]->image_paths);
    }

    public function test_withImages_calls_identifier_per_item(): void
    {
        $items = [
            (object) ['id' => 10],
            (object) ['id' => 20],
        ];
        $paginator = $this->makePaginator($items);

        $this->fileUploadService
            ->expects($this->exactly(2))
            ->method('getFiles')
            ->willReturn([]);

        $this->service->withImages($paginator, 'problems', fn ($p) => 'problem-' . $p->id);
    }

    // ──────────────────────────────────────────────────────────────────────────

    private function makePaginator(array $items): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            collect($items),
            count($items),
            perPage: 20,
            currentPage: 1
        );
    }
}
