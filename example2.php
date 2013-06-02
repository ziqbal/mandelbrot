<?

// Example for setting bounds, iterations and image size

require_once("class-mandelbrot.php");

$obj=new Mandelbrot(true);

$obj->setBounds('-2.25','0.75','-1.5','1.5');
$obj->setIterations(765);
$obj->setImageSize(256);

$obj->save();

?>
