<?php

namespace EDP\LogTailer;

use InvalidArgumentException;
use UnexpectedValueException;

class Tailer
{
    protected $filename;
    protected $interval = 1;

    protected $file;
    protected $offset;

    public function __construct($filename)
    {
        $this->setFilename($filename);
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setFilename($filename)
    {
        if (empty($filename)) {
            throw new InvalidArgumentException('Arquivo não fornecido');
        }

        $filename = realpath($filename);

        if (empty($filename)) {
            throw new UnexpectedValueException('Arquivo não encontrado');
        }

        if (!is_readable($filename)) {
            $msg = sprintf('Arquivo %s sem permissão de leitura', $filename);
            throw new UnexpectedValueException($msg);
        }

        $this->filename = $filename;
        $this->offset = 0;
    }

    public function setInterval($interval)
    {
        $this->interval = abs(intval($interval));
    }

    public function getInterval()
    {
        return $this->interval;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    public function open()
    {
        $this->file = fopen($this->filename, 'r');

        fseek($this->file, $this->offset, SEEK_END);
        $this->offset = ftell($this->file);
    }

    public function read()
    {
        $buffer = [];

        if (!$this->file) {
            throw new Exception();
        }

        fseek($this->file, $this->offset);

        while (($line = fgets($this->file)) !== false) {
            $buffer[] = trim($line);
        }

        $this->offset = ftell($this->file);

        return $buffer;
    }

    public function sleep()
    {
        sleep($this->interval);
    }
}
