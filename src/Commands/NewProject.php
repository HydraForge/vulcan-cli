<?php

namespace HydraForge\VulcanCli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\text;

class NewProject extends Command
{
    protected function configure()
    {
        $this->setName("new")
            ->setDescription("Create a new Vulcan application.")
            ->addArgument('name', InputArgument::OPTIONAL, 'the name of the project folder')
        ;
    }

    protected function process(array $command)
    {
        $output = new ConsoleOutput();
        $process = new Process($command);
        $process->start();
        $iterator = $process->getIterator(Process::ITER_KEEP_OUTPUT);
        foreach ($iterator as $type => $data) {
            $output->write($data);
        }
        return $process;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name') ?? text("Project directory", default: '.');

        $this->process(['git', 'clone', 'git@github.com:HydraForge/Vulcan.git', $name]);
        $this->process(['rm', '-r', $name . '/.git']);
        $this->process(['git', 'init',  $name]);

        return 0;
    }
}
