<?php

class DepartmentsModel
{
	public static function get()
	{
		$stmt = Database::connect()->prepare("SELECT department_id, department_name FROM departments");
		$stmt->execute();
		$departments = $stmt->fetchAll(PDO::FETCH_OBJ);
		return $departments;
	}

	public static function department($department)
	{
		$stmt = Database::connect()->prepare("SELECT department_name FROM departments
            WHERE department_id = :department");
        $stmt->bindparam(":department",$department);
        $stmt->execute();
        $department = $stmt->fetch(PDO::FETCH_OBJ);
        return $department;
	}

	public static function count()
	{
		$stmt = Database::connect()->prepare("SELECT department_id FROM departments");
        $stmt->execute();
        $count = $stmt->rowCount();
        return $count;
	}

	public static function dept_emp()
	{
		$stmt = Database::connect()->prepare("SELECT d.department_name, COUNT(e.employee_id) as employees 
		FROM employees e 
		INNER JOIN departments d
		ON e.department_id = d.department_id
		GROUP BY d.department_name");
		$stmt->execute();
		$dept_emp = $stmt->fetchAll(PDO::FETCH_OBJ);
		return $dept_emp;
	}
}