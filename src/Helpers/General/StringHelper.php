<?php

namespace TheBachtiarz\Base\Helpers\General;

class StringHelper
{
    /**
     * Create shuffle from string and number
     */
    public static function shuffleBoth(int $length): string
    {
        $chars = self::shuffleString(100, true) . self::shuffleNumber(100);

        return self::generateShuffleFromString(input: $chars, length: $length);
    }

    /**
     * Create shuffle string
     */
    public static function shuffleString(int $length, bool $withLowerCase = false): string
    {
        $chars = 'QWEASDZXCRTYFGHVBNUIOJKLMP';

        if ($withLowerCase) {
            $chars .= 'qweasdzxcrtyfghvbnuiojklmp';
        }

        return self::generateShuffleFromString(input: $chars, length: $length);
    }

    /**
     * Create shuffle number
     */
    public static function shuffleNumber(int $length): string
    {
        $chars = (string) mt_rand(1000000000, 9999999999);

        return self::generateShuffleFromString(input: $chars, length: $length);
    }

    /**
     * Generate shuffle from string
     */
    protected static function generateShuffleFromString(string $input, int $length): string
    {
        $shuffleChars = str_shuffle($input);
        $charsLength  = mb_strlen($shuffleChars);

        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $shuffleChars[random_int(0, $charsLength - 1)];
        }

        return $result;
    }
}
