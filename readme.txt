
PHP Mandelbrot Class
====================

Intro
-----
	A class to visualize the Mandelbrot and Julia fractals using arbitary precision math.

Examples
--------
	Some examples provided showing basic usage.

Public Methods
--------------
	setBounds($min_x,$max_x,$min_y,$max_y)	Complex plane dimensions
	setBoundsAlternate($mid_x,$mid_y,$size)	Complex plane dimensions
	setIterations($i)			Maximum iterations
	setImageSize($i)			Image size	
	setJulia($i,$j)				Julia point
	loadParameterFile($filename)		Load parameter file
	save()					Save image and parameter file

Notes
-----
	Class constructor can take a true|false parameter which controls verbose mode.
	Verbose mode outputs current horizontal (y-axis) line that is being computed.
	Precision is auto-magically controlled using a hand-rolled algorithm.
	Precision has been tested to deep zoom levels but extreme depth requires more
	RAM and CPU power.
	Complex plane bound methods expect strings as input parameters, e.g. "2.5"
	No method to override colour map. If you need to then you must edit class source.
	Best advice for creating stunning fractals is to use a standalone fractal program
	for example fractint to get rough idea of complex plane dimensions or find a fractal
	parameter file archive and then use this class to produce high-definition images.
	Of course, leave your computer running for a night or two ;)

2005-03-17	Zafar Iqbal
