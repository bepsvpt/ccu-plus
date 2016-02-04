<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Filesystem\Filesystem;

class DownloadCourseArchive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:download {semester?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download ccu course archive.';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Course semester.
     *
     * @var string
     */
    protected $semester;

    /**
     * Course archive url.
     *
     * @var string
     */
    protected $url;

    /**
     * The downloaded course archive.
     *
     * @var string
     */
    protected $archivePath;

    /**
     * Create a new command instance.
     *
     * @param Client $client
     * @param Filesystem $filesystem
     */
    public function __construct(Client $client, Filesystem $filesystem)
    {
        parent::__construct();

        $this->client = $client;

        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->init();

        if (! $this->download()) {
            $this->error("There is something wrong when downloading the archive: {$this->url}");
        }

        $this->decompress();

        $this->info("{$this->semester} course archive has successfully downloaded and decompressed.");
    }

    /**
     * 初始化學期與網址.
     *
     * @return void
     */
    protected function init()
    {
        $semester = $this->argument('semester');

        if (is_null($semester)) {
            $now = Carbon::now();

            $year = $now->year - (($now->month >= 8) ? 1911 : 1912);

            $semester = $year.(($now->month >= 2 && $now->month <= 7) ? 2 : 1);
        }

        $this->semester = $semester;

        $this->url = "https://kiki.ccu.edu.tw/~ccmisp06/Course/zipfiles/{$semester}.tgz";
    }

    /**
     * Download the course archive.
     *
     * @return bool
     */
    protected function download()
    {
        $response = $this->client->get($this->url, [
            'http_errors' => false,
        ]);

        if (200 !== $response->getStatusCode()) {
            return false;
        }

        $this->saveArchive($response);

        return true;
    }

    /**
     * Save file to disk.
     *
     * @param \GuzzleHttp\Psr7\Response $response
     * @return void
     */
    protected function saveArchive($response)
    {
        $destination = storage_path(file_build_path('app', 'courses', 'archive', $this->semester));

        if (! $this->filesystem->isDirectory($destination)) {
            $this->filesystem->makeDirectory($destination, 0770, true);
        }

        $this->archivePath = file_build_path($destination, "{$this->semester}.tgz");

        $this->filesystem->put($this->archivePath, $response->getBody()->getContents());
    }

    /**
     * Decompress archive and delete non-used files.
     *
     * @return void
     */
    protected function decompress()
    {
        $directory = dirname($this->archivePath);

        $this->externalCommand("tar zxf {$this->archivePath}", $directory);

        $specifics = [
            file_build_path($directory, 'index.html'),
            file_build_path($directory, 'all_english.html'),
            $this->archivePath,
        ];

        $this->filesystem->delete(array_merge(
            $this->filesystem->glob(file_build_path($directory, '*e.html')),
            $this->getOldFiles($directory),
            $specifics
        ));
    }

    /**
     * 取得遺骸檔案列表.
     *
     * @param string $directory
     * @return array
     */
    protected function getOldFiles($directory)
    {
        $semester = intval(substr($this->semester, -1));

        $now = Carbon::createFromDate(
            intval(substr($this->semester, 0, -1)) + ((1 === $semester) ? 1911 : 1912),
            (1 === $semester) ? 10 : 3
        )->timestamp;

        $files = [];

        foreach ($this->filesystem->files($directory) as $file) {
            if ($now - $this->filesystem->lastModified($file) > 12960000) {
                $files[] = $file;
            }
        }

        return $files;
    }
}
