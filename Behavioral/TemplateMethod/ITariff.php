<?php
/**
 * Tariff Interface
 *
 * @author Alex Koller <alex@webmolecule.co.za>
 */

interface ITariff
{
	public function get_periodic_charges();
	public function get_usage_charges();
	public function get_block_charges();
	public function get_timeofuse_charges();
}
?>
