<?php

class AddressModel 
{

    public static function add($city, $state, $country, $address, $employee_id)
    {
        $stmt = Database::connect()->prepare("INSERT INTO address(city, state, country, address, employee_id)VALUES(:city, :state, :country, :address, :employee_id)");
        $stmt->bindparam(":city",$city);
        $stmt->bindparam(":state",$state);
        $stmt->bindparam(":country",$country);
        $stmt->bindparam(":address",$address);
        $stmt->bindparam(":employee_id",$employee_id);
        $stmt->execute();
    }

	public static function update($city, $state, $country, $address, $employee_id)
    {
        $stmt = Database::connect()->prepare("UPDATE address SET city = :city, state = :state, country = :country, address = :address WHERE employee_id = :employee_id");

        $stmt->bindparam(":city",$city);
        $stmt->bindparam(":state",$state);
        $stmt->bindparam(":country",$country);
        $stmt->bindparam(":address",$address);
        $stmt->bindparam(":employee_id",$employee_id);
        $stmt->execute();
    }
}