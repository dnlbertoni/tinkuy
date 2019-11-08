<?php
namespace App\Lib;

class ResponseBootgrid
{
	public $current    = 1;
	public $rowCount   = 10;
	public $rows       = array();
	public $total      = 10;

	
	public function SetResponse($response, $m = '')	{
		$this->rows = $response;
		$this->total = $m;

		if(!$response && $m = '') $this->rows = array();
	}
}