<?php

namespace Redmoon\TinkerServer\Console;

use Clue\React\Stdio\Stdio;
use Illuminate\Console\Command;
use Psy\Configuration;
use Psy\Shell;
use React\EventLoop\Factory;
use Symfony\Component\Console\Output\BufferedOutput;

class TinkerServerCommand extends Command
{
    protected $signature = 'tinker-server';

    /** @var BufferedOutput */
    protected $shellOutput;

    public function handle()
    {
        $shellOutput = new BufferedOutput();
        $loop = Factory::create();
        $stdio = new Stdio($loop);

        $shell = $this->getPsyShell();

        $stdio->getReadline()->setPrompt('>> ');

        $stdio->on('data', function ($line) use ($stdio, $shell) {
            $line = rtrim($line, "\r\n");
            $shell->addCode($line);
            $closure = new ExecutionClosure($shell);
            $closure->execute();
            $stdio->write($this->shellOutput->fetch());
        });

        $loop->run();
    }

    /**
     * Get an instance of PsyShell
     *
     * @return Psy\Shell
     */
    protected function getPsyShell() : Shell
    {
        $config = new Configuration([
            'updateCheck' => 'never'
        ]);

        $config->getPresenter()->addCasters(
            $this->getCasters()
        );

        $shell = new Shell($config);
        $shell->setOutput($this->shellOutput);

        return $shell;
    }

    /**
     * Get an array of Laravel tailored casters.
     *
     * @return array
     */
    protected function getCasters() : array
    {
        $casters = [
            'Illuminate\Support\Collection' => 'Laravel\Tinker\TinkerCaster::castCollection',
        ];

        if (class_exists('Illuminate\Database\Eloquent\Model')) {
            $casters['Illuminate\Database\Eloquent\Model'] = 'Laravel\Tinker\TinkerCaster::castModel';
        }

        if (class_exists('Illuminate\Foundation\Application')) {
            $casters['Illuminate\Foundation\Application'] = 'Laravel\Tinker\TinkerCaster::castApplication';
        }

        return $casters;
    }
}
