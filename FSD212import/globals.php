<?php
  /*! \file globals.php
\brief Variables global to all functions
  */
	//Configuration.
	//The max to create the thumbnails.
	$GLOBALS['maxwidth'] = 500;
	//The max height to create the thumbnails.
	$GLOBALS['maxheight'] = 200;
	//The maximum width of the generated thumbnails.
	$GLOBALS['maxwidththumb'] = 60;
	//The maximum height of the generated thumbnails.
	$GLOBALS['maxheightthumb'] = 60;
	//Where to store the images.
	$GLOBALS['csvfolder'] = "fileholder";
	//Where to store the images.
	$GLOBALS['imagesfolder'] = "fileholder";
	//Where to create the thumbs.
	$GLOBALS['thumbsfolder'] = "fileholder/thumbs";
	//Allowed file types.
	$GLOBALS['allowedfiletypes'] = array ("jpg","jpeg","gif","png");
	//Allowed file mime types.
        $GLOBALS['allowedmimetypes'] = array ("image/jpeg","image/pjpeg","image/png","image/gif");
	//Number of images per row in the navigation.
	$GLOBALS['maxperrow'] = 7;
        //! Allowed csv file types
        $GLOBALS['allowedcsvfiletypes'] = array ("csv","txt");
        //! Allowed csv mime types
        $GLOBALS['allowedcsvmimetypes'] = array ("text/csv","text/plain","application/vnd.ms-excel");
        //$GLOBALS['uploaderror'];
        //! Remember whether there was an error uploading
        global $uploaderror;

?>