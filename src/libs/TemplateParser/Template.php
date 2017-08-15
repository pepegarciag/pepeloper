<?php

namespace Pepeloper\TemplateParser;

/**
 * Class Template
 *
 * @package Pepeloper\TemplateParser
 */
class Template
{

    /**
     * The open tag for variables
     *
     * @var string
     */
    protected $oTag = '{{';

    /**
     * The close tag for variables
     *
     * @var string
     */
    protected $cTag = '}}';

    /**
     * Array with keys as names variables and values to be replaced.
     *
     * @var array
     */
    protected $values;

    /**
     * The template file to be used.
     *
     * @var string
     */
    protected $template;

    /**
     * Set values.
     *
     * @param $values
     *
     * @return $this
     */
    public function setValues($values)
    {
        $this->values = $values;

        return $this;
    }

    /**
     * Set a template.
     *
     * @param $template
     *
     * @return $this
     */
    public function setTemplate($template, $values)
    {
        //@TODO: Take care if this template doesn't exist.
        $this->$template = $this->getPath() . "/templates/{$template}.txt";
        $this->setValues($values);
        return $this;
    }

    /**
     * Output the content according to the template used.
     *
     * @return mixed
     */
    public function output()
    {
        $file = file_get_contents($this->template);
        $data = array_values($this->values);
        $keys = $this->formatKeys(array_keys($this->values));

        return str_replace($keys, $data, $file);
    }

    /**
     * Get the current file path.
     *
     * @return bool|string
     */
    protected function getPath()
    {
        return realpath(dirname(__FILE__));
    }

    /**
     * Format keys to match the tags used.
     * ºº
     * @param $keys
     *
     * @return array
     */
    protected function formatKeys($keys)
    {
        $format = "{$this->oTag}%s{$this->cTag}";

        $keys = array_map(function ($key) use ($format) {
            return sprintf($format, $key);
        }, $keys);

        return $keys;
    }
}