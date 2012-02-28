<?php
/**
 * Tariff CPT
 * A basic Tariff from Cape Town with Periodic charges and Usage charges
 *
 * @author Alex Koller <alex@webmolecule.co.za>
 */

require_once('ITariff.php');
class TariffCPT implements ITariff
{
	public function __construct()
	{
		
	}
	
	public function get_periodic_charges()
	{
		return array(
			'Access Charge' => array(
				'frequency' => 'per day',
				'charge'	=> 1.25,
			),
			'Service Charge' => array(
				'frequency' => 'per month',
				'charge'	=> 14.5,
			),
		);
	}
	
	public function get_usage_charges()
	{
		return array(
			'Consumption Charge' => array(
				'uom'		=> 'kwh',
				'charge'	=> 0.55,
			),
			'Demand Charge' => array(
				'uom' 		=> 'kva',
				'charge'	=> 122.5,
			),
		);
	}
	
	public function get_block_charges()
	{
		return array();
	}
	
	public function get_timeofuse_charges()
	{
		return array();
	}
}
?>
