<?php

namespace App\Console\Commands;

use RuntimeException;
use Symfony\Component\Process\Process;

class Command extends \Illuminate\Console\Command
{
    /**
     * 執行外部程式指令.
     *
     * @param string $command
     * @param string $baseDirectory
     * @return string
     */
    protected function externalCommand($command, $baseDirectory = null)
    {
        $process = new Process($command);

        $process->setWorkingDirectory($baseDirectory ?? base_path());

        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }

        return $process->getOutput();
    }
}
