<?php

namespace HydraForge\VulcanCli\Commands;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class NewProject extends Command
{
    protected function configure()
    {
        $this->setName("new")
            ->setDescription("Create a new Vulcan application.")
            ->addArgument('name', InputArgument::OPTIONAL, 'the name of the project folder')
            ->addOption('stack', 's', InputOption::VALUE_REQUIRED, 'the stack to use', null)
        ;
    }

    protected function process(array $command, $pwd = null)
    {
        $output = new ConsoleOutput();
        $process = new Process($command);
        if ($pwd) {
            $process->setWorkingDirectory($pwd);
        }
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
        $stack = $input->getOption('stack') ?? select(
            label: 'What role should the user have?',
            options: [
                'simple' => 'Simple',
                'vue' => 'Inertia (Vue)',
            ],
            default: 'simple'
        );

        $branch = match ($stack) {
            'simple' => 'main',
            'vue' => 'inertia-vue',
            default => throw new Exception('Unknown stack [' . $stack . ']'),
        };

        $this->process(['git', 'clone', '-b', $branch, 'git@github.com:HydraForge/Vulcan.git', $name]);
        $this->process(['rm', '-r', $name . '/.git']);
        $this->process(['git', 'init',  $name]);
        $this->process(['cp',  '.env.example', '.env'], $name);
        $this->process(['composer',  'install'], $name);
        $this->process(['bun',  'install'], $name);
        $this->process(['bun', 'run', 'build'], $name);

        return 0;
    }
}
