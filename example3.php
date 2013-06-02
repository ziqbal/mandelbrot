<?

// Similar to previous example

require_once("class-mandelbrot.php");

$obj=new Mandelbrot(true);

$obj->setBounds('-0.95','-0.88333','0.23333','0.3');
$obj->setIterations(765);
$obj->setImageSize(128);

$obj->save();

?>
