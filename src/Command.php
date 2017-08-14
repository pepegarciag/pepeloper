<?php namespace Pepeloper;

use Pepeloper\TemplateParser\Template;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class Command extends SymfonyCommand {

  /**
   * The template parser.
   *
   * @var \Pepeloper\TemplateParser\Template
   */
  protected $template;

  /**
   * Create a new Command instance.
   *
   * @param Template $template
   */
  public function __construct(Template $template) {
    $this->template = $template;

    parent::__construct();
  }
}