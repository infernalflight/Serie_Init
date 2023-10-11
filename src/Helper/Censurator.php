<?php

namespace App\Helper;

class Censurator
{
    private array $forbiddenWords;

    public function __construct() {
        $this->forbiddenWords = file('../data/ban_words.txt');
    }

    public function purify(string $string): string
    {
        foreach ($this->forbiddenWords as $word) {
            $word = str_ireplace(PHP_EOL, "", $word);
            $string = str_ireplace($word, str_repeat('*', strlen($word)), $string);
        }

        return $string;
    }


}