<?php

  /*!  \file functions.php

    \brief General Ajax functions

  */

  //This function will dump the cache of your browser, thereby ensuring the proper image loads,
  //not one that is simply stuck in the cache's memory.
  //! Clear the browser cache
function clearcache (){
  //Clear the cache.
  header("Cache-control: private, no-cache");
  header("Expires: Mon, 26 Jun 1997 05:00:00 GMT");
  header("Pragma: no-cache");
  }
	
/* //! A function to create an array of all the images in the folder. */
/* function getImages (){ */
/*   $imgarr = array (); */
/*   if (is_dir ($GLOBALS['imagesfolder'])){ */
/*     $mydir = scandir ($GLOBALS['imagesfolder']); */
/*     //Loop through and find any valid images. */
/*     for ($i = 0; $i < count ($mydir); $i++){ */
/*       //make sure it's a file */
/*       if (is_file ($GLOBALS['imagesfolder'] . "/" . $mydir[$i])){ */
/* 	//Check for a valid file type. */
/* 	$thepath = pathinfo ($GLOBALS['imagesfolder'] . "/" . $mydir[$i]); */
/* 	if (in_array ($thepath['extension'], $GLOBALS['allowedfiletypes'])){ */
/* 	  $imgarr[] = $mydir[$i]; */
/* 	} */
/*       } */
/*     } */
/*   } */
/*   return ($imgarr); */
/* } */

/* //! A function to retrieve a total number of images. */
/* function getImageCount ($imgarr){ */
/*   $totimages = 0; */
/*   $totimages = count ($imgarr); */
/*   return $totimages; */
/* } */

/* //! A function to go through and retrieve an image location at a given index (numerical directory index). */
/* function getImageAtIndex ($imgarr, $index){ */
/*   //Return the file. */
/*   return ($imgarr[$index]); */
/* } */
	
/* //! Set the width and height */
/* function setWidthHeight($width, $height, $maxWidth, $maxHeight){ */
/*   $ret = array($width, $height); */
/*   $ratio = $width / $height; */
/*   if ($width > $maxWidth || $height > $maxHeight) { */
/*     $ret[0] = $maxWidth; */
/*     $ret[1] = $ret[0] / $ratio; */
		
/*     if ($ret[1] > $maxHeight) { */
/*       $ret[1] = $maxHeight; */
/*       $ret[0] = $ret[1] * $ratio; */
/*     } */
/*   } */
/*   return $ret; */
/* } */
		
/* //! A function to change the size of an image. */
/* function createthumb ($img, $mwidth, $mheight, $ext="_th"){ */
				
/*   //First, check for a valid file. */
/*   if (is_file ($GLOBALS['imagesfolder'] . "/" . $img)){ */
/*     //Now, get the current file size. */
/*     if ($cursize = getimagesize ($GLOBALS['imagesfolder'] . "/" . $img)){ */
				
/*       $currentWidth = $cursize[0]; */
/*       $currentHeight = $cursize[1]; */
				
/*       $newsize = setWidthHeight ($currentWidth,$currentHeight,$mwidth, $mheight); */
				
/*       //Now that we have the size constraints, let's find the file type. */
/*       $thepath = pathinfo ($GLOBALS['imagesfolder'] . "/" . $img); */
/*       //Setup our thumbnail. */
/*       $dst = imagecreatetruecolor ($newsize[0],$newsize[1]); */
/*       //Make a file name. */
/*       $filename = str_replace (".".$thepath['extension'],"",$img); */
				
/*       $types = array('jpg' => array('imagecreatefromjpeg', 'imagejpeg'), 'jpeg' => array('imagecreatefromjpeg', 'imagejpeg'), 'gif' => array('imagecreatefromgif', 'imagegif'), 'png' => array('imagecreatefrompng', 'imagepng')); */
/*       $src = $types[$thepath['extension']][0] ($GLOBALS['imagesfolder'] . "/" . $img); */
/*       //Create the copy. */
/*       imagecopyresampled ($dst,$src,0,0,0,0,$newsize[0],$newsize[1],$cursize[0],$cursize[1]); */
/*       $retfile = $GLOBALS['thumbsfolder'] . "/" . $filename.$ext.".".$thepath['extension'].""; */
/*       //Create the thumbnail. */
/*       $types[$thepath['extension']][1] ($dst,$retfile); */
								
/*       //Return the image. */
/*       return $retfile; */
				
/*     } else { */
/*       echo "No image found."; */
/*     } */
			
/*   } else { */
/*     echo "No image found."; */
/*   } */
/* } */
?>