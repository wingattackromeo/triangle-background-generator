<?php

class Palette
{
	protected $colours;
	protected $colcount;
	protected $seed;
	protected $seeded;
	protected $name = '';
	
	public function __construct()
	{
		$this->colours = array('#ffffff');
		$this->colcount = 1;
	}
	
	public function count()
	{
		return $this->colcount;
	}

	public function seed()
	{
		global $stderr, $argv;
		
		if($this->seed === null)
		{
			list($usec, $sec) = explode(' ', microtime());
			$this->seed = round((float) $sec + ((float) $usec * 100000));
			fprintf($stderr, "%s: random seed set to %d\n", $argv[0], $this->seed);
		}
		if(!$this->seeded)
		{
			srand($this->seed);
			$this->seeded = true;
		}
		return $this->seed;
	}
	
	public function name()
	{
		return $this->name;
	}
	
	public function random()
	{
		$this->seed();
		return $this->colours[rand(0, $this->colcount - 1)];
	}
	
	public function parseSeed($str)
	{
		global $stderr, $argv;
		
		$seed = intval($str);
		if($seed < 1)
		{
			fprintf($stderr, "%s: random seed must be a positive integer\n", $argv[0]);
			exit(1);
		}
		$this->seed = $seed;
	}
	
	public function set($colours)
	{
		$this->colours = $colours;
		$this->colcount = count($colours);
	}
	
	public function load($path)
	{
		global $stderr, $argv;
		
		if(strncmp($path, '/', 1))
		{
			$path = dirname(__FILE__) . '/../palettes/' . $path;
		}
		$path .= '.txt';
		$buf = file_get_contents($path);
		if(!is_string($buf))
		{
			fprintf($stderr, "%s: failed to load '%s'\n", $argv[0], $path);
			exit(1);
		}
		$colours = explode("\n", $buf);
		foreach($colours as $k => $col)
		{
			$col = strtolower(trim(trim($col, '#')));
			if(!strlen($col))
			{
				unset($colours[$k]);
				continue;
			}
			if(!ctype_xdigit($col))	
			{
				fprintf($stderr, "%s: '%s' is not a valid RRGGBB hex colour value\n", $argv[0], $col);
				exit(1);
			}
			$colours[$k] = '#' . $col;
		}
		if(!count($colours))
		{
			fprintf($stderr, "%s: '%s' does not contain any colours\n", $argv[0], $path);
			exit(1);
		}
		$this->name = basename($path, '.txt');
		$this->colours = $colours;
		$this->colcount = count($colours);
	}
}
