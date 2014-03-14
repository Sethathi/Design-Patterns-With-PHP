<?php
require_once('SimpleTariffCalculator.php');
require_once('TariffCPT.php');

$calc = new SimpleTariffCalculator();
$tariff = new TariffCPT();

//create some test data
$usage_data = array(
	'day' => array(
		'kwh' => array(),
		'kva' => array(),
	),
	'week' => array(
		'kwh' => array(),
		'kva' => array(),
	),
);
for($i=0; $i<24; $i++)
{
	$usage_data['day']['kwh'][$i] = rand(20, 120)/100;
	$usage_data['day']['kva'][$i] = rand(50, 500)/1000;
}
for($i=0; $i<7; $i++)
{
	$usage_data['week']['kwh'][$i] = rand(20, 120)/100;
	$usage_data['week']['kva'][$i] = rand(50, 500)/1000;
}

//use the simple calculator
$cost_data = $calc->calculate_graph($tariff, $usage_data);

//display some meaningful output
foreach($usage_data as $timespan => $timespan_data)
{
	foreach($timespan_data as $unit => $unit_data)
	{
		foreach($unit_data as $key => $usage)
		{
			$usage = number_format($usage, 4);
			echo "Usage for $timespan [$key]: $usage $unit";
			if ($unit == 'kwh' && isset($cost_data[$timespan]['cost'][$key]))
			{
				echo " => R" . number_format($cost_data[$timespan]['cost'][$key], 2);
			}
			echo "\n";
		}
	}
}
?>
