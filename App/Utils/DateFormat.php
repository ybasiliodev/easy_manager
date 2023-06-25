<?php

namespace App\Utils;

class DateFormat
{
    /**
     * @throws \Exception
     */
    public function stringToDatetime(string $stringDate): \DateTime
    {
        $formattedDate = null;
        try {
            if ($stringDate) {
                $formattedDate = new \DateTime($stringDate);
            }
            return $formattedDate;
        } catch (\Exception $e) {
            throw new \Exception("Invalid dateTime format.");
        }
    }
}