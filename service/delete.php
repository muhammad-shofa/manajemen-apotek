<?php

class delete
{
    function select_table($table_name, $condition) : string
    {
        return "DELETE FROM $table_name $condition";
    }
}

$delete = new delete();