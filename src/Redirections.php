<?php namespace Pepeloper;

use Pepeloper\CsvParser\Csv;
use Pepeloper\TemplateParser\Template;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Redirections extends Command
{

    /**
     * The CSV file parser.
     *
     * @var \Pepeloper\CsvParser\Csv
     */
    protected $csv;

    public function __construct(Template $template, Csv $csv)
    {
        $this->csv = $csv;

        parent::__construct($template);
    }

    /**
     * Configure the command.
     */
    public function configure()
    {
        $this->setName('redirecciones')
            ->addArgument('data', InputArgument::REQUIRED, 'The data source to work with. Must be a URL or a path to a csv file with two columns with the old and new url')
            ->setDescription('Build a set of redirection based on the provided data.');
    }

    /**
     * Execute the command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('data');
        try {
            $result = $this->csv->setFile($file)->parse(function ($lines) {
                return $this->buildOutput($lines);
            });

            $output->writeln($result);
        } catch (\Exception $e) {
            $output->writeln("<error> {$e->getMessage()} <error>");
        }
    }

    private function buildOutput($lines)
    {
        $count = 0;
        $values = [];

        foreach ($lines as $column) {
            if (preg_match('#^http#', $column) === 1) {
                if ($count % 2 == 0) {
                    // This preg replace should not be done because the data should come in the right way.
                    // anyway if we leave this code we must make it interactive.
                    $values['old'] = preg_replace('/https:\/\/www.ladrupalera.com/', '', $column);
                } else {
                    $values['new'] = $column;
                }
                $count++;
            }
        }

        return !empty($values) ? $this->template->setTemplate("redirections", $values)->output() : '';
    }
}