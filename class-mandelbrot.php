<?php
/*
*	PHP Mandelbrot Class
*	2005-01-08	Zafar Iqbal
*/
class Mandelbrot
{
	private $mx1;
	private $mx2;
	private $my1;
	private $my2;
	private $jzr;
	private $jzi;
	private $cs;
	private $iterations;
	private $image;
	private $colours;
	private $debug;

	// Constructor with default var values
	public function __construct($debugMode=false) {
		$this->mx1='-2.05';
		$this->mx2='2.05';
		$this->my1='-2.05';
		$this->my2='2.05';
		$this->jzr='0.0';
		$this->jzi='0.0';
		$this->cs='128';
		$this->iterations=16;
		$this->debug=$debugMode;
	}

	// Set bounds for plane using two points
	public function setBounds($x1,$x2,$y1,$y2){
		$this->mx1=$this->rightTrim($x1);
		$this->mx2=$this->rightTrim($x2);
		$this->my1=$this->rightTrim($y1);
		$this->my2=$this->rightTrim($y2);
	}

	// Set bounds for plane using midpoint and size
	public function setBoundsAlternate($a,$b,$r){
		$currentPrecision=max($this->getPrecision($a),$this->getPrecision($b),$this->getPrecision($r));
		$currentPrecision*=2;
		$x1=bcsub($a,$r,$currentPrecision);
		$x2=bcadd($a,$r,$currentPrecision);
		$y1=bcsub($b,$r,$currentPrecision);
		$y2=bcadd($b,$r,$currentPrecision);
		$this->setBounds($x1,$x2,$y1,$y2);
	}

	// Set nuber of iterations
	// The max iterations allowed is the same as max colours in colourmap (766 in default)
	public function setIterations($i){
		$this->iterations=$i;
	}

	// Set image size
	public function setImageSize($i){
		$this->cs=$i;
	}

	// Set julia values
	public function setJulia($i,$j){
		$this->jzr=$i;
		$this->jzi=$j;
	}

	// Load parameter file
	public function loadParameterFile($filename){
		require_once($filename);
	}

	// Trimming of 0's
	private function rightTrim($i){
		if(strpos($i,'.')){
			for($j=strlen($i)-1;$j>-1;--$j){
				$char=substr($i,$j,1);
				if($char!='0') break;
			}
			$newString=substr($i,0,$j+1);
			if(substr($newString,strlen($newString)-1,1)=='.') $newString=$newString.'0';
			return $newString;
		} else {return $i;}
	}

	// Init colour map (766 colours here)
	private function initColours(){
		$this->colours=array();
		$r=255;$g=255;$b=255;
		$this->colours[]=imagecolorallocate($this->image,$r,$g,$b);
		while(true){
			if(($r==0) && ($g==0) && ($b==0)) break;
			$this->colours[]=imagecolorallocate($this->image,--$r,$g,$b);
			$this->colours[]=imagecolorallocate($this->image,$r,--$g,$b);
			$this->colours[]=imagecolorallocate($this->image,$r,$g,--$b);
			if(($r==0) && ($g==0) && ($b==0)) break;
			$this->colours[]=imagecolorallocate($this->image,$r,$g,--$b);
			$this->colours[]=imagecolorallocate($this->image,$r,--$g,$b);
			$this->colours[]=imagecolorallocate($this->image,--$r,$g,$b);
			if(($r==0) && ($g==0) && ($b==0)) break;
			$this->colours[]=imagecolorallocate($this->image,$r,--$g,$b);
			$this->colours[]=imagecolorallocate($this->image,--$r,$g,$b);
			$this->colours[]=imagecolorallocate($this->image,$r,$g,--$b);
			if(($r==0) && ($g==0) && ($b==0)) break;
			$this->colours[]=imagecolorallocate($this->image,$r,$g,--$b);
			$this->colours[]=imagecolorallocate($this->image,--$r,$g,$b);
			$this->colours[]=imagecolorallocate($this->image,$r,--$g,$b);
			if(($r==0) && ($g==0) && ($b==0)) break;
			$this->colours[]=imagecolorallocate($this->image,--$r,$g,$b);
			$this->colours[]=imagecolorallocate($this->image,$r,$g,--$b);
			$this->colours[]=imagecolorallocate($this->image,$r,--$g,$b);
			if(($r==0) && ($g==0) && ($b==0)) break;			
			$this->colours[]=imagecolorallocate($this->image,$r,--$g,$b);
			$this->colours[]=imagecolorallocate($this->image,$r,$g,--$b);
			$this->colours[]=imagecolorallocate($this->image,--$r,$g,$b);
		}
	}	

	// Set precision
	private function getPrecision($i){
		if(strpos($i,'.')){return strlen($i)-strpos($i,'.');}else{return 0;}
	}

	// Calculate precision needed auto-magically
	private function autoPrecision(){
		return (max($this->getPrecision($this->mx1),$this->getPrecision($this->mx2),$this->getPrecision($this->my1),$this->getPrecision($this->my2),0)+strlen($this->cs))*2;
	}

	// Save result image and parameter file
	function save(){
		$this->image=imagecreatetruecolor($this->cs,$this->cs);

		$this->initColours();
		$maxColours=count($this->colours);
		$iterationStep=$maxColours;
		if($this->iterations>0){$iterationStep=$maxColours/$this->iterations;}

		bcscale($this->autoPrecision());
		$stepsx=bcdiv(bcsub($this->mx2,$this->mx1),$this->cs);
		$stepsy=bcdiv(bcsub($this->my2,$this->my1),$this->cs);

		$cy = $this->my2;
		for($y=0;$y<$this->cs;$y++){
			if($this->debug) print("Y = $y\n");
			$cx=$this->mx1;
			$cy=bcsub($cy,$stepsy);
			for($x=0;$x<$this->cs;$x++){
				$cx=bcadd($cx,$stepsx);
				$vzr=$this->jzr;$vzi=$this->jzi;
				for($j=0;$j<$maxColours;$j+=$iterationStep){
					$vtr=bcadd(bcsub(bcmul($vzr,$vzr),bcmul($vzi,$vzi)),$cx);
					$vti=bcadd(bcmul(bcmul($vzr,$vzi),'2'),$cy);
					if((bcmul($vtr,$vtr)+bcmul($vti,$vti))>4) break;
					$vzr=$vtr;$vzi=$vti;
				}
				imagesetpixel($this->image,$x,$y,$this->colours[$j]);
			}
		}
		list($usec,$sec)=explode(' ',microtime());
		$usec*=1000000;
		imagepng($this->image,"brot-$sec-$usec.png");
		$this->image->destroy;

		$handle=fopen("brot-$sec-$usec.php", 'w');
		fwrite($handle,"<?\r\n/*\r\n");
		fwrite($handle,"PHP Mandelbrot Parameter File\r\n");
		fwrite($handle,"AUTO GENERATED ".date("H:i l F jS, Y")."\r\n");
		fwrite($handle,"More Information at http://www.phpclasses.org/mandelbrot\r\n");
		fwrite($handle,"*/\r\n");
		fwrite($handle,"\r\n// X Low\r\n\$this->mx1='".$this->mx1."';\r\n");
		fwrite($handle,"\r\n// X High\r\n\$this->mx2='".$this->mx2."';\r\n");
		fwrite($handle,"\r\n// Y Low\r\n\$this->my1='".$this->my1."';\r\n");
		fwrite($handle,"\r\n// Y High\r\n\$this->my2='".$this->my2."';\r\n");
		fwrite($handle,"\r\n// Initial Real\r\n\$this->jzr='".$this->jzr."';\r\n");
		fwrite($handle,"\r\n// Initial Imaginary\r\n\$this->jzi='".$this->jzi."';\r\n");
		fwrite($handle,"\r\n// Iterations\r\n\$this->iterations='".$this->iterations."';\r\n");
		fwrite($handle,"\r\n// Image Size\r\n\$this->cs='".$this->cs."';\r\n");
		fwrite($handle,"?>\r\n");	
		fclose($handle);
	}
}
?>
