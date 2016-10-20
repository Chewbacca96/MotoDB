<?php
namespace MotoDB\models;

interface DB 
{
    public static function connectToDB($dbOptions);
}