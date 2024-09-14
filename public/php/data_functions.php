<?php

function is_competitive(array $data): bool
{
    $mode = $data["meta"]["mode"];
    if ($mode === "Competitive") {
        return true;
    }
    return false;
}

function is_unrated(array $data): bool
{
    $mode = $data["meta"]["mode"];
    if ($mode === "Unrated") {
        return true;
    }
    return false;
}

function get_day_by_timestamp($timestamp)
{
    $date_obj = new DateTime();
    $date_obj->setTimestamp($timestamp);
    $day = $date_obj->format('l');
    return $day;
}
