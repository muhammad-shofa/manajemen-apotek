<?php

class insert
{
    function selectTable($table_name, $condition = "")
    {
        return "INSERT INTO $table_name $condition";
    }
}

$insert = new insert();
