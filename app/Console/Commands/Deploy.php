<?php

namespace App\Console\Commands;

use Artisan;
use Cache;
use Carbon\Carbon;
use File;
use GuzzleHttp\Client;
use Log;

class Deploy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy {--isRestart}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy application';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->down();

        if (! $this->option('isRestart')) {
            $this->composerUpdate();

            if ($this->queueNeedRestart()) {
                $this->call('up');

                return;
            }
        }

        $this->migrate();

        $this->up();

        Log::info('Github Webhook', ['status' => 'update successfully']);
    }

    /**
     * composer related update.
     */
    protected function composerUpdate()
    {
        // 取得 composer 路徑
        $path = realpath(base_path().'/../../composer/composer');

        // 設置 composer home 目錄
        $this->setComposerHome();

        // 如果超過 15 天未更新，則先更新 composer 本身
        if (Carbon::now()->diffInDays(Carbon::createFromTimestamp(File::lastModified($path))) > 15) {
            $this->externalCommand("{$path} self-update");
        }

        // 執行 package 更新
        $this->externalCommand("git pull; {$path} install --no-scripts --no-dev -o");
    }

    /**
     * 設置 composer home 環境變數.
     *
     * @return void
     */
    protected function setComposerHome()
    {
        $dir = realpath(base_path().'/../../composer');

        putenv("HOME={$dir}");
        putenv("COMPOSER_HOME={$dir}");
    }

    /**
     * 判斷是否要重新啟動 queue.
     *
     * @return bool
     */
    protected function queueNeedRestart()
    {
        switch (true) {
            case $this->isModified(app_path(file_build_path('Console', 'Commands', 'Deploy.php'))):
            case $this->isModified(base_path('composer.lock')):
                $this->call('queue:restart');

                Artisan::queue('deploy', ['--isRestart' => true]);

                return true;

            default:
                return false;
        }
    }

    /**
     *  Migrate database.
     *
     * @return void
     */
    protected function migrate()
    {
        $migrations = count(File::files(database_path('migrations')));

        if ($migrations > Cache::tags('deploy')->get('migrations', 0)) {
            Cache::tags('deploy')->forever('migrations', $migrations);

            $this->call('migrate', ['--force' => true]);
        }
    }

    /**
     * Put the application into maintenance mode.
     *
     * @return void
     */
    protected function down()
    {
        (new Client())->get(route('opcache-reset'));

        $this->clearCache();

        $this->call('down');
    }

    /**
     * Bring the application out of maintenance mode.
     *
     * @return void
     */
    protected function up()
    {
        $this->call('up');

        $this->setupCache();

        (new Client())->get(route('opcache-reset'));
    }

    /**
     * 清除快取.
     *
     * @return void
     */
    protected function clearCache()
    {
        Cache::tags('course')->flush();
        Cache::tags('ecourse-lite')->flush();
        Cache::tags('resource')->flush();

        $this->call('route:clear');

        $this->call('config:clear');

        $this->call('view:clear');

        $this->call('clear-compiled');
    }

    /**
     * 設置快取.
     *
     * @return void
     */
    protected function setupCache()
    {
        $this->call('route:cache');

        $this->call('config:cache');

        $this->call('clear-compiled');
        $this->call('optimize');
    }

    /**
     * 檢查指定檔案是否更改過.
     *
     * @param string $path
     * @return bool
     */
    protected function isModified($path)
    {
        if (($last = File::lastModified($path)) > Cache::tags('deploy')->get(md5($path), 0)) {
            Cache::tags('deploy')->forever(md5($path), $last);

            return true;
        }

        return false;
    }
}
