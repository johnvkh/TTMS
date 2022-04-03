<?php

class CountryModel
{


    function __construct()
    {
    }

    public function getAllCountry($conn)
    {
        $sql = "select * from country order by id desc;";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
