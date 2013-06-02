<?

// Similar to previous example but also using the julia function

require_once("class-mandelbrot.php");

$obj=new Mandelbrot(true);

$obj->setBounds('-1.5','1.5','-1.5','1.5');
$obj->setJulia('-0.39054','-0.58679');

$obj->setIterations(128);
$obj->setImageSize(128);

$obj->save();

?>
