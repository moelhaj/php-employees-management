<?php

class Reports
{

	public static function index()
	{
		$employees = EmployeesModel::count();
		$departments = DepartmentsModel::count();
		$date = date('d') . ' ' . date('M') . ' ' .date('Y');
		$dept_emp = DepartmentsModel::dept_emp();
		$grossWages = PayrollModel::grossWages();
		$net_payables = PayrollModel::netPayables();
		$deduction = PayrollModel::deduction();
		$health = PayrollModel::health();
		$transport = PayrollModel::transport();
		$other_allowance = PayrollModel::other_allowance();
		$total_allowance = $health + $transport + $other_allowance;
		View::render('reports.html',[
			'date' => $date,
			'employees' => $employees,
			'departments' => $departments,
			'dept_emp' => $dept_emp,
			'grossWages' => $grossWages,
            'net_payables' => $net_payables,
            'deduction' => $deduction,
            'health' => $health,
            'transport' => $transport,
            'other_allowance' => $other_allowance,
            'total_allowance' => $total_allowance
		]);
	}
}