<?php
/**
 * Created by PhpStorm.
 * User: pepegarciag
 * Date: 15/8/17
 * Time: 1:25
 */

namespace Pepeloper\CsvParser;

/**
 * Class Csv
 * @package Pepeloper\CsvParser
 */
class Csv
{
    protected $file;

    protected $data = [];

    /**
     * Set a file.
     *
     * @param $file
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function setFile($file)
    {
        // Take care of this.
        if (!$this->file = @file($file)) {
            throw new \Exception("File failed to open");
        }

        return $this;
    }

    public function parse($callback)
    {
        foreach ($this->file as $line) {
            if ($result = call_user_func($callback, str_getcsv($line))) {
                $this->data[] = $result;
            }
        }

        return $this->data;
    }
}