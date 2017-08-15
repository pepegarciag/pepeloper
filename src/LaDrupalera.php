<?php namespace Pepeloper;

use Pepeloper\TemplateParser\Template;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Goutte\Client;

class LaDrupalera extends Command
{

    /**
     * The client for Goutte Crawler.
     *
     * @var Client
     */
    protected $goutte;

    /**
     * Indicate if we can fetch more pages
     * @var boolean
     */
    protected $hasPages = TRUE;

    /**
     * Current page for fetch posts.
     * @var int
     */
    protected $page = 0;

    /**
     * Host to perform requests.
     * @var string
     */
    protected $host = 'https://www.ladrupalera.com/';

    /**
     * Path to perform requests.
     * @var array
     */
    protected $paths = [
        'drupal/snippet',
        'drupal/consultoria',
        'drupal/desarrollo',
        'drupal/comunidad-drupal',
    ];

    /**
     * LaDrupalera constructor.
     *
     * @param Template $template
     * @param $goutte
     */
    public function __construct(Template $template, $goutte)
    {
        $this->goutte = $goutte;

        parent::__construct($template);
    }

    /**
     * Configure the command.
     */
    public function configure()
    {
        $this->setName('ladrupalera:posts')
            ->setDescription('Set of utility commands to work with La Drupalera.');
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
        foreach ($this->paths as $path) {
            $url = $this->host . $path;

            do {
                $this->fetchPage($url, $output);
            } while ($this->hasPages);

            // Reset variables.
            $this->hasPages = TRUE;
            $this->page = 0;
        }
    }

    /**
     * Fetch a snippet page.
     *
     * @param $url string
     * @param $output OutputInterface
     *
     * @return void
     */
    private function fetchPage($url, OutputInterface $output)
    {
        // Check if we should query for pages
        $query = ($this->page == 0) ? '' : "?page={$this->page}";
        // Perform request.
        $crawler = $this->goutte->request('GET', $url . $query);
        // Fetch all posts.
        $posts = $crawler->filter('h2 > a');

        // Check if we found posts in this request. If not, we assume this page
        // doesn't exists and it is a 404 so we stop requesting posts.
        if ($this->checkPage($posts)) {
            $posts->each(function ($node, $i) use ($output) {
                $output->writeln("\"" . $node->text() . "\"," . $node->link()->getUri());
            });

            $this->page++;
        } else {
            $this->hasPages = FALSE;
        }
    }

    /**
     * Check if the given posts have any of them.
     *
     * @param  array $posts
     *
     * @return boolean
     */
    private function checkPage($posts)
    {
        return (count($posts)) ? TRUE : FAlSE;
    }
}