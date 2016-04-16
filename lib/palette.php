<?php

class Palette
{
	protected $colours;
	protected $colcount;
	
	public function __construct()
	{
		$this->colours = array('#ffffff');
		$this->colcount = 1;
	}
	
	public function count()
	{
		return $this->colcount;
	}
	
	public function random()
	{
		return $this->colours[rand(0, $this->colcount - 1)];
	}
	
	public function set($colours)
	{
		$this->colours = $colours;
		$this->colcount = count($colours);
	}
}
