<?php

namespace App\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use PHPHtmlParser\Dom;

class ImportCourse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:import {--dir=} {--file=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var Dom
     */
    protected $dom;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Files that should be parsed.
     *
     * @var array
     */
    protected $files = [];

    /**
     * Create a new command instance.
     *
     * @param Filesystem $filesystem
     * @param Dom $dom
     */
    public function __construct(Filesystem $filesystem, Dom $dom)
    {
        parent::__construct();

        $this->dom = $dom;

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

        $this->parse();
    }

    /**
     * 初始化匯入資料.
     *
     * @return void
     */
    protected function init()
    {
        $dir = $this->option('dir') ? realpath($this->option('dir')) : false;

        if (false !== $dir && $this->filesystem->isDirectory($dir)) {
            $this->files = array_merge($this->files, $this->filesystem->glob(file_build_path($dir, '*.html')));
        }

        $file = $this->option('file') ? realpath($this->option('file')) : false;

        if (false !== $file && ends_with($file, '.html') && $this->filesystem->isFile($file)) {
            $this->files[] = $file;
        }
    }

    protected function parse()
    {
        foreach ($this->files as $file) {
            $this->dom->loadFromFile($file);
        }
    }
}
