<?php

class AzubiSite extends BaseController
{
    protected $view = "azubiView";

    public function timeSince()
    {
        $date = ((time() - strtotime(($this->getRequestData())->getEmploymentstart())) / 60 / 60 / 24);
        $yrs = 0;
        $mnths = 0;
        $days = 0;
        $output = "";
        while ($date >= 1) {
            if ($date / 365 >= 1) {
                $date -= 365;
                $yrs += 1;
            } elseif ($date / 30 >= 1) {
                $date -= 30;
                $mnths += 1;
            } elseif ($date >= 1) {
                $date -= 1;
                $days += 1;
            }
        }
        if ($days > 0) {
            $output .= "Bei Fatchip angestellt seit " . $days . " Tagen";
        }
        if ($mnths > 0) {
            $output .= ", " . $mnths . " Monaten";
        }
        if ($yrs > 0) {
            $output .= " und " . $yrs . " Jahren";
        }
        return $output;
    }

    public function getRequestData()
    {
        $azubi = new azubi();
        $azubi->load($this->getRequestParameter("id", 1));
        return $azubi;
    }

    public function getTitle()
    {
        return $this->getRequestData()->getName();
    }
}