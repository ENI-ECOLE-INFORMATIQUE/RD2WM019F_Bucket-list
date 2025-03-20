<?php

namespace App\Utils;

class Censurator
{
    const BAN_WORDS = ["joie", "banane", "michel", "brocoli", "/^con($|s$)/"];

    public function purify(string $text): string
    {
        $text = str_ireplace(self::BAN_WORDS, "******", $text);

        return $text;
    }


}