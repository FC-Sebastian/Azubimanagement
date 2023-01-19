<?php

class YieldTest
{
    protected static $generator = null;

    public static function getNextYield()
    {
        if (self::$generator === null) {
            self::$generator = self::test();
        }
        $return = self::$generator->current();
        self::generatorNext();
        return $return;
    }

    public static function generatorNext()
    {
        if (self::$generator === null) {
            self::$generator = self::test();
        }
        self::$generator->next();
    }

    protected static function test()
    {
        for ($i = 0; $i < 999; $i++) {
            yield $i;
        }
    }

}