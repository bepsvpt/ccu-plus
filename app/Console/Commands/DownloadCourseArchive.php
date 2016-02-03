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
     * Execute the console command.
     *
     * @param Client $client
     * @param Filesystem $filesystem
     * @return mixed
     */
    public function handle(Client $client, Filesystem $filesystem)
    {
        $this->init();

        if (! $this->download($client, $filesystem)) {
            $this->error("There is something wrong when downloading the archive: {$this->url}");
        }

        $this->decompress($filesystem);
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
            $now = Carbon::now()->subMonths(0);

            $year = $now->year - (($now->month >= 8) ? 1911 : 1912);

            $semester = $year.(($now->month >= 2 && $now->month <= 7) ? 2 : 1);
        }

        $this->semester = $semester;

        $this->url = "https://kiki.ccu.edu.tw/~ccmisp06/Course/zipfiles/{$semester}.tgz";
    }

    /**
     * Download the course archive.
     *
     * @param Client $client
     * @param Filesystem $filesystem
     * @return bool
     */
    protected function download($client, $filesystem)
    {
        $response = $client->get($this->url, [
            'http_errors' => false,
        ]);

        if (200 !== $response->getStatusCode()) {
            return false;
        }

        $this->saveArchive($response, $filesystem);

        return true;
    }

    /**
     * Save file to disk.
     *
     * @param \GuzzleHttp\Psr7\Response $response
     * @param Filesystem $filesystem
     * @return void
     */
    protected function saveArchive($response, $filesystem)
    {
        $destination = storage_path(file_build_path('app', 'courses', 'archive', $this->semester));

        if (! $filesystem->isDirectory($destination)) {
            $filesystem->makeDirectory($destination, 0770, true);
        }

        $this->archivePath = file_build_path($destination, "{$this->semester}.tgz");

        $filesystem->put($this->archivePath, $response->getBody()->getContents());
    }

    /**
     * Decompress archive and delete non-used files.
     *
     * @param Filesystem $filesystem
     * @return void
     */
    protected function decompress($filesystem)
    {
        $directory = dirname($this->archivePath);

        $this->externalCommand("tar zxf {$this->archivePath}", $directory);

        $filesystem->delete(array_merge(
            $filesystem->glob(file_build_path($directory, '*e.html')),
            [
                file_build_path($directory, 'index.html'),
                file_build_path($directory, 'all_english.html'),
                file_build_path($directory, 'I000.html'),
                $this->archivePath,
            ]
        ));
    }
}
