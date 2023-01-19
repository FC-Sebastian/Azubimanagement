<?php

class AjaxTableGenerator extends JSONController
{
    use YieldAzubiData;
    use ListMethods;

    protected static $generator = null;
    public static $self = null;

    /**
     * starts the generator if not started
     * return current generator value and shifts generator to next value
     * @return mixed
     */
    public static function getNextYield()
    {
        if (self::$generator === null) {
            self::$self = new AjaxTableGenerator();
            self::$generator = self::$self->yieldRequestData();
        }
        $return = self::$generator->current();
        self::generatorNext();
        return $return;
    }

    /**
     * * starts the generator if not started
     * shifts generator to next value
     * @return void
     */
    public static function generatorNext()
    {
        if (self::$generator === null) {
            self::$self = new AjaxTableGenerator();
            self::$generator = self::$self->yieldRequestData();
        }
        self::$generator->next();
    }

    /**
     * generator function
     * sets its own generator to get azubi-objects
     *yields htmlString of azubi-div
     * @return Generator
     */
    public function yieldRequestData()
    {
        $generator = $this->yieldData(
            false,
            "*",
            $this->getRequestParameter("order"),
            $this->getRequestParameter("orderdir"),
            $this->getRequestParameter("search"),
            $this->getNumberOfAzubisLeft(),
            $this->getOffset()
        );
        foreach ($generator as $azubi) {
            $htmlString = $this->getHtmlString($azubi);
            yield $htmlString;
        }
    }

    /**
     * returns azubi-div as string from azubi-object
     * @param $azubi
     * @return string
     */
    protected function getHtmlString($azubi) {
        $htmlString =
            '<div class="col-md-1 col-12">
                    <input class="form-check-input" type="checkbox" name="deletearray[]" value="' . $azubi->getId() . '">
                </div>
                <div class="col-md-3 col-12">
                    <span class="d-md-none fw-bold">Name: </span>
                    <span>' . $azubi->getName() . '</span>
                </div>
                <div class="col-md-3 col-12">
                    <span class="d-md-none fw-bold">Geburtstag: </span>
                    <span>' . $azubi->getBirthday() . '</span>
                </div>
                <div class="col-md-3 col-12">
                    <span class="d-md-none fw-bold">Email: </span>
                    <span>' . $azubi->getEmail() . '</span>
                </div>
                <div class="col-md-2 col-12 pb-1">
                    <a class="text-decoration-none text-center" href="' . $this->getUrl("index.php?controller=Edit") . '&id=' . $azubi->getId() . '">
                        <img src="' . $this->getUrl("pics/iconmonstr-pencil-14.svg") . '">
                    </a>
                    <a id="' . $azubi->getId() . '" class="text-decoration-none text-center trash-can" onclick="deleteAzubi('. $azubi->getId() .', this, ' . $this->getRequestParameter("page") . ')" href="#">
                        <img src="' . $this->getUrl("pics/iconmonstr-trash-can-29.svg") . '">
                    </a>
                </div>';
        return $htmlString;
    }

    /**
     * returns array of azubi-div-strings
     * @return array
     */
    protected function getJsonData()
    {
        $returnArray = [];
        for ($i = 0; $i < $this->getTableLength(); $i++) {
            $returnArray[] = AjaxTableGenerator::getNextYield();
        }
        return $returnArray;
    }
}