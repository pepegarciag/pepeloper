<?php namespace Pepeloper;

use Pepeloper\CsvParser\Csv;
use Pepeloper\TemplateParser\Template;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class Command extends SymfonyCommand
{

    /**
     * The template parser.
     *
     * @var \Pepeloper\TemplateParser\Template
     */
    protected $template;

    /**
     * @var \Pepeloper\CsvParser\Csv
     */
    protected $csv;

    /**
     * Create a new Command instance.
     *
     * @param Template $template
     * @param Csv $csv
     */
    public function __construct(Template $template, Csv $csv)
    {
        $this->template = $template;
        $this->csv = $csv;

        parent::__construct();
    }
}