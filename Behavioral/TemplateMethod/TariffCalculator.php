<?php
/**
 * Tariff Calculator
 * Abstract class with the algorythim for calculating graph data from a tariff
 * Makes use of the Template Method pattern
 *
 * @author Alex Koller <alex@webmolecule.co.za>
 */
 
require_once('ITariff.php');
abstract class TariffCalculator
{
	/**
	 * These functions must be overridden by the calculators that extend this class
	 *
	 * @access protected
	 */
	protected abstract function calculate_periodic_charges($tariff, $usage_data, &$cost_data);
	protected abstract function calculate_usage_charges($tariff, $usage_data, &$cost_data);
	protected abstract function calculate_block_charges($tariff, $usage_data, &$cost_data);
	protected abstract function calculate_timeofuse_charges($tariff, $usage_data, &$cost_data);

	/**
	 * 
	 * @return Database object we are going to use throughout our application. Create it if necessary
	 *
	 * @access public
	 */
	public final function calculate_graph($tariff, $usage_data)
	{
		/**
		 * The steps for calculating a tariff:
		 * 1. Get the applicable tariff
		 * 2. Get the usage data
		 * 3. Calculate cost based on usage data and tariff info
		 * 3.1 Calculate Periodic charges
		 * 3.2 Calculate Usage charges
		 * 3.3 Calculate Block charges
		 * 3.4 Calculate Time of Use charges
		 */
		 
		 try
		 {
		 	// 1. Confirm valid Tariff
		 	if ( ! $tariff instanceof ITariff)
		 	{
		 		throw new Exception("Supplied Tariff is not a valid Tariff.");
		 	}
		 	
		 	// 2. Validate usage data
		 	if ( ! $this->validate_usage_data($usage_data))
		 	{
		 		throw new Exception("Supplied usage data is not a valid.");
		 	}
		 	
		 	// 3. Calculate costs
		 	$cost_data = array(
				'day' => array(
					'cost' => array(),
				),
				'week' => array(
					'cost' => array(),
				),
		 	);
		 	
		 	// 3.1 Calculate Periodic charges
		 	$this->calculate_periodic_charges($tariff, $usage_data, $cost_data);
		 	
		 	// 3.2 Calculate Usage charges
		 	$this->calculate_usage_charges($tariff, $usage_data, $cost_data);
		 	
		 	// 3.3 Calculate Block charges
		 	$this->calculate_block_charges($tariff, $usage_data, $cost_data);
		 	
		 	// 3.4 Calculate Time of Use charges
		 	$this->calculate_timeofuse_charges($tariff, $usage_data, $cost_data);
		 	
		 	return $cost_data;
		 }
		 catch (Exception $e)
		 {
		 	echo $e->getMessage() . "\n";
		 }
	}
	
	/**
	 * Validate the format of the usage data array
	 *
	 * @access private
	 * @return bool
	 */
	private function validate_usage_data($usage_data)
	{
		$valid = FALSE;
	 	if (is_array($usage_data))
	 	{
	 		if (isset($usage_data['day']) && isset($usage_data['day']['kwh']) && isset($usage_data['day']['kva'])
	 			&& isset($usage_data['week']) && isset($usage_data['week']['kwh']) && isset($usage_data['week']['kva']))
 			{
 				$valid = TRUE;
 			}
	 	}
	 	
	 	return $valid;
	}
	
	/**
	 * Get the charge multiplier based on timespan and frequency
	 *
	 * @access protected
	 * @return float
	 */
	protected function get_charge_multiplier($timespan, $frequncey)
	{
		//TODO: number of days in a month is dependant on which moneth it is
		$multiplier = 0;
		switch($timespan)
		{
			case 'day':
				if ($frequncey == 'per day')
				{
					$multiplier = 1;
				}
				else if ($frequncey == 'per month')
				{
					$multiplier = 1/30;
				}
				break;
			case 'week':
				if ($frequncey == 'per day')
				{
					$multiplier = 1 * 7;
				}
				else if ($frequncey == 'per month')
				{
					$multiplier = 1/30 * 7;
				}
				break;
			case 'month':
				if ($frequncey == 'per day')
				{
					$multiplier = 1 * 30;
				}
				else if ($frequncey == 'per month')
				{
					$multiplier = 1;
				}
				break;
		}
		
		return $multiplier;
	}
}
?>