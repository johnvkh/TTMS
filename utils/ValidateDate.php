<?php

class ValidateDate
{
    function check_date_format($date): bool
    {
        try {
            if (DateTime::createFromFormat('Y-m-d', $date) && $date >=  date("Y-m-d")) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
        }
    }

    function date_format($date): bool
    {
        try {
            if (DateTime::createFromFormat('Y-m-d', $date)) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
        }
    }

    public static function checkDateFormat($date): bool
    {
        try {
            if (DateTime::createFromFormat('Y-m-d', $date)) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
        }
    }

    public static function validateDateCheckString($name): string
    {
        return "test validate date class ${name}";
    }
}
