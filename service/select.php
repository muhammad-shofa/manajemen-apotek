<?php

class select
{
    function selectTable($table_name, $fields = '*', $condition = "")
    {
        return "SELECT $fields FROM $table_name $condition";
    }

}

$select = new select();

