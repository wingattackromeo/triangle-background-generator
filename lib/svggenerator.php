<?php
	
require_once(dirname(__FILE__) . '/palette.php');
	
class SVGGenerator
{
	public $palette;
	
	protected $width;
	protected $height;
	protected $size;
	protected $vert;
	
	public function __construct()
	{
		$this->palette = new Palette();
		$this->setDimensions(1920, 1080);
		$this->setSize(32);
	}
	
	public function setDimensions($width, $height)
	{
		$this->width = $width;
		$this->height = $height;
	}
	
	public function setSize($size)
	{
		$this->size = $size;
		$this->vert = (sqrt(3) / 2) * $size;
	}
	
	public function generate($file)
	{
		fprintf($file, '<?xml version="1.0" standalone="no"?>' . "\n");
		fprintf($file, '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">' . "\n");
		fprintf($file, '<svg viewBox="0 0 %d %d" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:bevel;strike-linecap=square;stroke-miterlimit:1.41421;">' . "\n", $this->width, $this->height);
		$row = 0;
		for($y = 0; $y <= $this->height; $y = $nexty)
		{
			$nexty = floor($y + $this->vert);
			$downvert = ceil($this->vert);
			$uppath = 'M' . ($this->size / 2) . ',' . $this->vert . 'L-' . ($this->size / 2) . ',' . $this->vert . 'L0,0L' . ($this->size / 2) . ',' . $this->vert . 'Z';

			fprintf($file, "\t" . '<g id="row-%d">' . "\n", $row);
			$n = 0;
			for($x = ($row % 2 ? -($this->size * 1.5) : -($this->size * 2)); $x <= $this->width + ($row %2 ? ($this->size * 1.5) : $this->size); $x += $this->size)
			{
				$col = $this->palette->random();
				fprintf($file, "\t\t" . '<rect transform="matrix(1,0,0,1,%f,%f)" width="%f" height="%f" style="fill:%s" />' . "\n",
					$x, $y, ($this->size + 0.5), $downvert, $col);
			}
			for($x = ($row % 2 ? -($this->size * 1.5) : -($this->size * 2)); $x <= $this->width + ($row %2 ? ($this->size * 1.5) : $this->size); $x += $this->size)
			{
				$col = $this->palette->random();
				fprintf($file, "\t\t" . '<g transform="matrix(1,0,0,1,%f,%f)">' . "\n", $x, $y);
				fprintf($file, "\t\t\t" . '<path d="%s" style="fill:%s"/>' . "\n", $uppath, $col);
				fprintf($file, "\t\t" . '</g>' . "\n");
			}
			fprintf($file, "\t" . '</g>' . "\n");
			$row++;
		}
		fprintf($file, '</svg>' . "\n");
	}
}	

