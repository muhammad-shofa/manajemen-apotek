<?php

class update
{
    function selectTable($table_name, $condition = "")
    {
        return "UPDATE $table_name SET $condition";
    }
}

$update = new update();