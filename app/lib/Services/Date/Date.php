<?php

namespace Plugo\Services\Date;

use DateTime;
use IntlDateFormatter;

class Date
{
    /**
     * @param DateTime $dateTime
     * @return false|string
     */
    public function convertDateInFrench(DateTime $dateTime): false|string
    {
        $intl = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::SHORT);

        return $intl->format($dateTime);
    }
}
