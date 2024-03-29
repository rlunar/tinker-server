<?php

namespace RedMoon\TinkerServer;

use Psy\Shell;
use Clue\React\Stdio\Stdio;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Socket\ConnectionInterface;
use React\Socket\Server as SocketServer;
use RedMoon\TinkerServer\Shell\ExecutionClosure;
use Symfony\Component\Console\Output\BufferedOutput;

class Server
{
    /** @var string */
    protected $host;

    /** @var LoopInterface */
    protected $loop;

    /** @var BufferedOutput */
    protected $shellOutput;

    /** @var Shell */
    protected $shell;

    /** @var BufferedOutput */
    protected $output;

    /** @var Stdio */
    protected $stdio;

    /**
     * Server constructor.
     *
     * @param string             $host
     * @param Shell              $shell
     * @param BufferedOutput     $output
     * @param LoopInterface|null $loop
     * @param Stdio|null         $stdio
     */
    public function __construct(string $host, Shell $shell, BufferedOutput $output, LoopInterface $loop = null, Stdio $stdio = null)
    {
        $this->host = $host;
        $this->loop = $loop ?? Factory::create();
        $this->shell = $shell;
        $this->output = $output;
        $this->shellOutput = new BufferedOutput();
        $this->stdio = $stdio ?? $this->createStdio();
    }

    /**
     * Start Tinker Server.
     *
     * @return void
     */
    public function start() : void
    {
        $this->shell->setOutput($this->shellOutput);
        $this->createSocketServer();
        $this->loop->run();
    }

    /**
     * Create SocketServer.
     *
     * @return void
     */
    protected function createSocketServer() : void
    {
        $socket = new SocketServer($this->host, $this->loop);

        $socket->on('connection', function (ConnectionInterface $connection) {
            $connection->on('data', function ($data) use ($connection) {
                $unserializedData = unserialize(base64_decode($data));

                $this->shell->setScopeVariables(array_merge($unserializedData, $this->shell->getScopeVariables()));
                $this->stdio->write(PHP_EOL);

                collect($unserializedData)->keys()->map(function ($variableName) {
                    $this->output->writeln('>> $'.$variableName);
                    $this->executeCode('$'.$variableName);
                    $this->output->write($this->shellOutput->fetch());
                    $this->stdio->write($this->output->fetch());
                });
            });
        });
    }

    /**
     * Create Stdio.
     *
     * @return Stdio
     */
    protected function createStdio() : Stdio
    {
        $stdio = new Stdio($this->loop);
        $stdio->getReadline()->setPrompt('>> ');

        $stdio->on('data', function ($line) use ($stdio) {
            $line = rtrim($line, "\r\n");
            $stdio->getReadline()->addHistory($line);
            $this->executeCode($line);
            $this->output->write(PHP_EOL.$this->shellOutput->fetch());
            $this->stdio->write($this->output->fetch());
        });

        return $stdio;
    }

    /**
     * Execute Code.
     *
     * @param  [type] $code
     *
     * @return mixed
     */
    protected function executeCode($code)
    {
        (new ExecutionClosure($this->shell, $code))->execute();
    }
}
