<?php

class JobsModel
{
	public static function get()
	{
		$stmt = Database::connect()->prepare("SELECT job_id, job_title, department_id FROM jobs");
        $stmt->execute();
        $jobs = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $jobs;
	}

	public static function job_level($job_id)
    {
    	$stmt = Database::connect()->prepare(" SELECT employee_level FROM jobs 
    		WHERE job_id = :job_id
    		LIMIT 1
    		");
    	$stmt->bindparam(":job_id",$job_id);
    	$stmt->execute();
        $job_level = $stmt->fetch(PDO::FETCH_OBJ);
        $job_level = $job_level->employee_level;
        return $job_level;
    }
}