<?php

namespace Tests\CodeQuality\GrumPHP;

use GrumPHP\Runner\TaskResult;
use GrumPHP\Runner\TaskResultInterface;
use GrumPHP\Task\AbstractExternalTask;
use GrumPHP\Task\Context\ContextInterface;
use GrumPHP\Task\Context\GitPreCommitContext;
use GrumPHP\Task\Context\RunContext;
use NunoMaduro\PhpInsights\Application\Console\Contracts\Formatter;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhpInsightsTask extends AbstractExternalTask
{
    public static function getConfigurableOptions(): OptionsResolver
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'config-path' => null,
            'min-architecture' => 100,
            'min-complexity' => 80,
            'min-quality' => 100,
            'min-style' => 100,
        ]);

        $resolver->addAllowedTypes('config-path', ['null', 'string']);
        $resolver->addAllowedTypes('min-architecture', ['null', 'int']);
        $resolver->addAllowedTypes('min-complexity', ['null', 'int']);
        $resolver->addAllowedTypes('min-quality', ['null', 'int']);
        $resolver->addAllowedTypes('min-style', ['null', 'int']);

        return $resolver;
    }

    public function canRunInContext(ContextInterface $context): bool
    {
        return $context instanceof GitPreCommitContext || $context instanceof RunContext;
    }

    public function run(ContextInterface $context): TaskResultInterface
    {
        $config = $this->getConfig()->getOptions();

        $arguments = $this->processBuilder->createArgumentsForCommand('php');
        $arguments->addSeparatedArgumentArray('artisan', ['insights', '-v', '--no-interaction']);
        $arguments->addOptionalArgument('--min-architecture=%s', $config['min-architecture']);
        $arguments->addOptionalArgument('--min-complexity=%s', $config['min-complexity']);
        $arguments->addOptionalArgument('--min-quality=%s', $config['min-quality']);
        $arguments->addOptionalArgument('--min-style=%s', $config['min-style']);

        $process = $this->processBuilder->buildProcess($arguments);
        $process->run();

        if (!$process->isSuccessful()) {
            return TaskResult::createFailed($this, $context, $this->formatter->format($process));
        }

        return TaskResult::createPassed($this, $context);
    }
}
