<?php namespace Pepeloper;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Redirections extends Command {

  /**
   * Configure the command.
   */
  public function configure() {
    $this->setName('redirecciones')
      ->addArgument('data', InputArgument::REQUIRED, 'The data source to work with. Must be a URL or a path to a csv file.')
      ->addArgument('template', InputArgument::OPTIONAL, 'The template to be used.')
      ->setDescription('Build a set of redirection based on the provided data. Data must be a 
          local or web csv file with two columns with the old and new url.');
  }

  /**
   * Execute the command.
   *
   * @param InputInterface $input
   * @param OutputInterface $output
   *
   * @return void
   */
  public function execute(InputInterface $input, OutputInterface $output) {
    $csvFile = file($input->getArgument('data'));
    // TODO: Refactor this shit. Build a small class or something.
    foreach ($csvFile as $line) {

      $data = str_getcsv($line);
      $count = 0;

      foreach ($data as $item) {
        if (preg_match('#^http#', $item) === 1) {

          if ($count % 2 == 0) {
            $old = $item;
          }
          else {
            $new = $item;
          }

          $count++;
        }
      }

      if (!empty($old) && !empty($new)) {
        // This preg replace should not be done because the data should come in the right way.
        // anyway if we leave this code we must make it interactive.
        $old = preg_replace('/https:\/\/www.ladrupalera.com/', '', $old);
        $output->writeln($this->template->setValues(['old' => $old, 'new' => $new])->output());
      }
    }
  }

}