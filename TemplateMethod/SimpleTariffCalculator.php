<?php
/**
 * Simple Tariff Calculator
 * Calculates costs for Tariffs with Periodic and Usage charges
 *
 * @author Alex Koller <alex@webmolecule.co.za>
 */
 
require_once('TariffCalculator.php');
class SimpleTariffCalculator extends TariffCalculator
{
	/**
	 * Calculates the Period charges portion of the cost graph
	 *
	 * @access protected
	 */
	protected function calculate_periodic_charges($tariff, $usage_data, &$cost_data)
	{
		$charges = $tariff->get_periodic_charges();
		foreach($charges as $name => $charge)
		{
			//Calculate Day Costs
			$multiplier = $this->get_charge_multiplier('day', $charge['frequency']);
			foreach($usage_data['day']['kwh'] as $key => $value)
			{
				if ( ! isset($cost_data['day']['cost'][$key]))
				{
					$cost_data['day']['cost'][$key] = 0;
				}
				$cost_data['day']['cost'][$key] += ($multiplier * $charge['charge'] * $value);
			}
			
			//Calculate Week Costs
			$multiplier = $this->get_charge_multiplier('week', $charge['frequency']);
			foreach($usage_data['week']['kwh'] as $key => $value)
			{
				if ( ! isset($cost_data['week']['cost'][$key]))
				{
					$cost_data['week']['cost'][$key] = 0;
				}
				$cost_data['week']['cost'][$key] += ($multiplier * $charge['charge'] * $value);
			}
		}
		return TRUE;	
	}
	
	/**
	 * Calculates the Usage charges portion of the cost graph
	 *
	 * @access protected
	 */
	protected function calculate_usage_charges($tariff, $usage_data, &$cost_data)
	{
		$charges = $tariff->get_usage_charges();
		foreach($charges as $name => $charge)
		{
			//ignore non-kwh uom
			if ($charge['uom'] != 'kwh')
			{
				continue;
			}
			//Calculate Day Costs
			foreach($usage_data['day']['kwh'] as $key => $value)
			{
				if ( ! isset($cost_data['day']['cost'][$key]))
				{
					$cost_data['day']['cost'][$key] = 0;
				}
				$cost_data['day']['cost'][$key] += ($charge['charge'] * $value);
			}
			
			//Calculate Week Costs
			foreach($usage_data['week']['kwh'] as $key => $value)
			{
				if ( ! isset($cost_data['week']['cost'][$key]))
				{
					$cost_data['week']['cost'][$key] = 0;
				}
				$cost_data['week']['cost'][$key] += ($charge['charge'] * $value);
			}
		}
		return TRUE;
	}
	
	
	protected function calculate_block_charges($tariff, $usage_data, &$cost_data)
	{
		return TRUE;
	}
	
	protected function calculate_timeofuse_charges($tariff, $usage_data, &$cost_data)
	{
		return TRUE;
	}
}
?>