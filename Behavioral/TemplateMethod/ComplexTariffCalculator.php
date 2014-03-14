<?php
/**
 * Comlpex Tariff Calculator
 * Calculates costs for Tariffs with Block and Time of Use charges
 *
 * @author Alex Koller <alex@webmolecule.co.za>
 */
 
require_once('SimpleTariffCalculator.php');
class ComplexTariffCalculator extends SimpleTariffCalculator
{
	/**
	 * Calculates the Block charges portion of the cost graph
	 *
	 * @access protected
	 */
	protected function calculate_block_charges($tariff, $usage_data, &$cost_data)
	{
		//TODO: use actual block charge structure for calculations
		$charges = $tariff->get_block_charges();
		foreach($charges as $name => $charge)
		{
			//Calculate Day Costs
			$multiplier = 1.5;
			foreach($usage_data['day']['kwh'] as $key => $value)
			{
				if ( ! isset($cost_data['day']['cost'][$key]))
				{
					$cost_data['day']['cost'][$key] = 0;
				}
				$cost_data['day']['cost'][$key] += ($multiplier * $charge['charge'] * $value);
			}
			
			//Calculate Week Costs
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
	
	protected function calculate_timeofuse_charges($tariff, $usage_data, &$cost_data)
	{
		//TODO: implement time of use charges
		return TRUE;
	}
}
?>