<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);
$stdout = fopen('php://stdout', 'w');
$stderr = fopen('php://stderr', 'w');
$argv[0] = 'generate';

require_once(dirname(__FILE__) . '/svggenerator.php');

$generator = new SVGGenerator();
$outfile = null;
$palette = 'red';

while(count($argv) > 1)
{
	if(!strcmp($argv[1], '--'))
	{
		break;
	}
	if(strncmp($argv[1], '-', 1))
	{
		break;
	}
	$opt = substr($argv[1], 1, 1);
	if(strlen($argv[1]) > 2)
	{
		$optarg = substr($argv[1], 2);
	}
	else if(isset($argv[2]))
	{
		$optarg = $argv[2];
		unset($argv[2]);
	}
	else
	{
		$optarg = null;
	}
	if(!strlen($optarg) && strpos('dsc', $opt))
	{
		fprintf($stderr, "%s: option '-%s' requires an argument\n", $argv[0], $opt);
		exit(1);
	}
	switch($opt)
	{
	case 'h':
		usage();
		exit(0);
	case 'd':
		$generator->parseDimensions($optarg);
		break;
	case 's':
		$generator->parseSize($optarg);
		break;
	case 'c':
		$palette = $optarg;
		break;
	default:
		fprintf($stderr, "%s: unknown option '-%s'\n", $argv[0], $opt);
		exit(1);
	}
	unset($argv[1]);
	$argv = array_values($argv);
}

$generator->palette->load($palette);
$generator->generate($stdout);

function usage()
{
	global $argv;
	
	printf("Usage: %s [OPTIONS] > file.svg\n\n", $argv[0]);
	printf("OPTIONS is one or more of:\n");
	printf("  -d WIDTHxHEIGHT           Set output dimensions to WIDTHxHEIGHT\n");
	printf("  -s SIZE                   Set triangle edge size to SIZE\n");
	printf("  -c PALETTE                Set colour palette to PALETTE\n");
	printf("\n");
}

/*
$width = intval($argv[1]);
$height = intval($argv[2]);
if(count($argv) >= 4)
{
	if(!isset($palette[$argv[3]]))
	{
		fprintf($stderr, "%s: colour palette '%s' is not supported\n", $argv[0], $argv[3]);
		exit(1);
	}
	$colours = $palette[$argv[3]];
}
else
{
	$colours = $palette['blue'];
}
if(count($argv) >= 5)
{
	$size = intval($argv[4]);
}
else
{
	$size = 32;
}
*/