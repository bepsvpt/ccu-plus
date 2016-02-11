<?php

namespace App\Console\Commands;

use App\Ccu\Course;
use App\Ccu\General\Category;
use Cache;
use Illuminate\Filesystem\Filesystem;
use Sunra\PhpSimple\HtmlDomParser;

class ImportCourse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:import {--dir=} {--file=} {--semester=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import course data from directory or file.';

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
     * @var int
     */
    protected $numberOfCourses = 0;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

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

        $this->save($this->semesterId(), $this->parse());

        $this->info('');

        Cache::forget('categoriesTable');
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

    protected function semesterId()
    {
        $semester = $this->option('semester') ?? $this->ask('請輸入匯入學年及學期（如：1042）');

        $semester = substr($semester, 0, -1).('1' === substr($semester, -1) ? '上' : '下');

        $id = Category::getCategories('semester', $semester, true);

        if (is_null($id)) {
            $id = Category::create(['category' => 'semester', 'name' => $semester])->getAttribute('id');
        }

        return $id;
    }

    /**
     * 解析檔案.
     *
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function parse()
    {
        $courses = [];

        foreach ($this->files as $file) {
            $content = $this->removeAnnoys($this->filesystem->get($file));

            $rows = HtmlDomParser::str_get_html($content)->find('tr');

            // 移除表格標題列
            array_shift($rows);

            $department = $this->filesystem->name($file);

            foreach ($rows as $row) {
                $courses[$department][] = $this->getColumns($row, $department);
            }

            if (isset($courses[$department])) {
                $this->numberOfCourses += count($courses[$department]);
            }
        }

        return $courses;
    }

    /**
     * 移除無法理解的 html tag.
     *
     * @param string $string
     * @return string
     */
    protected function removeAnnoys($string)
    {
        static $patterns = [
            '<font.*>' => '',
            '</font>' => '',
            '<br.*>' => PHP_EOL,
        ];

        foreach ($patterns as $pattern => $replacement) {
            $string = preg_replace("#{$pattern}#iU", $replacement, $string);
        }

        return $string;
    }

    /**
     * 取得欄位資料.
     *
     * @param \PHPHtmlParser\Dom\HtmlNode $row
     * @param string $department
     * @return array
     */
    protected function getColumns($row, $department)
    {
        static $name = ['domain', 'grade', 'code', 'class', 'name', 'professors', 'hours', 'credit', 'time', 'location'];

        // I001：通識
        $i = ('I001' === $department) ? 0 : 1;

        $size = count($name);

        $result = [];

        foreach ($row->children() as $node) {
            if ($i >= $size) {
                break;
            }

            $result[$name[$i++]] = trim($node->plaintext);
        }

        return $result;
    }

    /**
     * 將課程資料存到資料庫中.
     *
     * @param int $semester
     * @param array $data
     */
    protected function save($semester, $data)
    {
        $bar = $this->output->createProgressBar($this->numberOfCourses);

        foreach ($data as $department => $courses) {
            $departmentId = Category::where('category', 'department')
                ->where('remark', $this->departmentTransform($department))
                ->first()
                ->getAttribute('id');

            foreach ($courses as $course) {
                $model = Course::with(['professors'])
                    ->where('semester_id', $semester)
                    ->where('code', $course['code'])
                    ->first();

                if (! is_null($model)) {
                    $model->professors()->sync(array_merge(
                        $model->getRelation('professors')->pluck('id')->toArray(),
                        $this->professors($course['professors'])->pluck('id')->toArray()
                    ));

                    $bar->advance();

                    continue;
                }

                $model = Course::create([
                    'semester_id' => $semester,
                    'code' => $course['code'],
                    'department_id' => $departmentId,
                    'name' => mb_strstr($course['name'], ' ', true),
                    'series_id' => $this->seriesId($course['code']),
                ]);

                $model->professors()->saveMany($this->professors($course['professors']));

                if ('I001' === $department) {
                    $model->dimension()->save(Category::getCategories('course.dimension', $course['grade']));
                }

                $bar->advance();
            }
        }

        $bar->finish();
    }

    /**
     * 新舊系所轉換，一個無奈.
     *
     * @param string $department
     * @return string
     */
    protected function departmentTransform($department)
    {
        static $transform = [
            '2366' => '2386',
        ];

        return $transform[$department] ?? $department;
    }

    /**
     * 取得群組 id.
     *
     * @param string $code
     * @return int
     */
    protected function seriesId($code)
    {
        $course = Course::orderBy('id')->where('code', $code)->first();

        if (! is_null($course)) {
            return $course->getAttribute('series_id');
        }

        $max = Course::max('series_id');

        return is_null($max) ? 1 : $max + 1;
    }

    /**
     * 取得教授 collection.
     *
     * @param string $professors
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    protected function professors($professors)
    {
        //$professors = explode(' ', str_replace('  ', ' ', str_replace('教師未定', '', $professors)));
        $professors = explode(' ', $professors);

        $result = Category::where('category', 'professor')->whereIn('name', $professors)->get();

        foreach (array_diff($professors, $result->pluck('name')->toArray()) as $name) {
            $result->push(Category::create(['category' => 'professor', 'name' => $name])->fresh());
        }

        return $result;
    }
}
