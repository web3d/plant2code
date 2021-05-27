<?php

namespace Plant2Code;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Convert command
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */
class ConvertCommand extends Command
{

    /**
     * Configure command
     */
    protected function configure()
    {
        $this->setName('puml:convert')
             ->setDescription('Creates PHP classes based on PlantUML description')
             ->addArgument('input', InputArgument::REQUIRED, 'The plantuml file')
             ->addOption(
                 '--output',
                 '-o',
                 InputOption::VALUE_OPTIONAL,
                 'Output directory (defaults to the directory of the input file'
             )
             ->addOption('--lang', '-l', InputOption::VALUE_OPTIONAL, 'Output language (defaults to PHP)')
             ->addOption('root-ns', '-r', InputOption::VALUE_OPTIONAL, 'Root namespace, optional');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $io = new SymfonyStyle($input, $output);
        $io->title('Convert puml to code');

        try {


            $puml = $input->getArgument('input');
            $runPath = run_path();
            $appPath = app_path();
            $fileInfo = pathinfo($puml);

            $outputDir = $input->getOption('output');

            if (!$outputDir) {
                $outputDir = $fileInfo['dirname'];
            }

            $outputDir = $this->adjustOutputDir($outputDir);
            $input->setOption('output', $outputDir);

            $io->section('Creating XMI file.');

            // 追加逻辑，做预处理，将属性的行内注释部分的内容要保留，才能做后续的处理
            $tmpPuml = $outputDir . '/' . $fileInfo['filename'] . '.puml';

            $srcContent = file_get_contents($puml);
            $srcContent = str_replace('/\'', ':', $srcContent);
            $srcContent = str_replace('\'/', '', $srcContent);
            file_put_contents($tmpPuml, $srcContent);

            $command = "java -jar $appPath/plantuml.jar $tmpPuml -xmi:star";
            system($command);

            $io->text('Finished XMI creation.');

            $xmi = $outputDir . '/' . $fileInfo['filename'] . '.xmi';
            $input->setArgument('input', $xmi);

            $io->section('Detecting classes and writing output...');

            $converter = new PlantUmlConverter($input, $output, $io);
            $converter->convertAndWrite();
            
            unlink($tmpPuml);
            unlink($xmi);
//            /** @var StrictCommand $formatter */
//            $formatter = $this->getApplication()
//                              ->find('formatter:use:sort');
//
//            $formatterInput = new ArrayInput([
//                'command'     => 'formatter:use:sort',
//                'path'        => $outputDir,
//                '--sort-type' => 'alph'
//            ]);
//
//            $formatter->run($formatterInput, $output);

            $io->success('Done converting.');

        } catch (\Exception $e) {
            $io->error($e->getMessage() . $e->getTraceAsString());
        }
    }

    /**
     * @param $outputDir
     *
     * @return string
     */
    protected function adjustOutputDir($outputDir): string
    {

        if (!starts_with($outputDir, '/')) {
            // relative path given - find out, where we are...
            $runDir = run_path();
            $outputDir = $runDir . '/' . $outputDir;
        }

        return $outputDir;
    }
}
