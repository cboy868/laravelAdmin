<?php

namespace App\Console\Commands;

use App\Entities\Pictures\Repository\PicturesItemRepository;
use App\Entities\Pictures\Repository\PicturesRepository;
use App\Entities\Pictures\Services\FileService;
use Illuminate\Console\Command;

/**
 * php artisan pictures:sync
 * Class ScanPictures
 * @package App\Console\Commands
 */
class ScanPictures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pictures:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '扫描新图片';

    protected $fileService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(FileService $fileService)
    {
        parent::__construct();

        $this->fileService = $fileService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(PicturesRepository $album, PicturesItemRepository $picture)
    {
        $this->fileService->sync($album, $picture);
    }
}
