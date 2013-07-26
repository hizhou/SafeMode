<?php
function fn_get_unsafe_storage_functions() {
	return array(
		'readDir' => array(
			'opendir' => array('readDir' => 0,),
			'is_dir' => array('readDir' => 0,),
		),
		'readFile' => array(
			'finfo_file' => array('readFile' => 1,),
			'finfo_open' => array('readFile' => 1,),
			'copy' => array('readFile' => 0, 'writeFile' => 1, 'writeFromFile' => 0,),
			'file_exists' => array('readFile' => 0,),
			'file_get_contents' => array('readFile' => 0, 'includePath' => 1,),
			'file' => array('readFile' => 0,),
			'fileatime' => array('readFile' => 0,),
			'filectime' => array('readFile' => 0,),
			'filemtime' => array('readFile' => 0,),
			'filesize' => array('readFile' => 0,),
			'filetype' => array('readFile' => 0,),
			'is_file' => array('readFile' => 0,),
			'is_readable' => array('readFile' => 0,),
			'is_writable' => array('readFile' => 0,),
			'is_writeable' => array('readFile' => 0,),
			'pathinfo' => array('readFile' => 0,),
			'readfile' => array('readFile' => 0, 'includePath' => 1,),
			'realpath' => array('readFile' => 0,),
			'getimagesize' => array('readFile' => 0,),
			'imagecreatefromgif' => array('readFile' => 0,),
			'imagecreatefromjpeg' => array('readFile' => 0,),
			'imagecreatefrompng' => array('readFile' => 0,),
			'imagecreatefromwbmp' => array('readFile' => 0,),
		),
		'writeFile' => array(
			'file_put_contents' => array('writeFile' => 0, 'writeData' => 1,),
			'fopen' => array('writeFile' => 0, 'writeData' => -1, 'includePath' => 2,),
			'fputcsv' => array('writeFile' => -1, 'writeData' => 1,),
			'fputs' => array('writeFile' => -1, 'writeData' => 1,),
			'fwrite' => array('writeFile' => -1, 'writeData' => 1,),
			'move_uploaded_file' => array('writeFile' => 1, 'writeFromFile' => 0,),
			'touch' => array('writeFile' => 0, 'writeData' => -1,),
			'imagegif' => array('writeFile' => 1,),
			'imagejpeg' => array('writeFile' => 1,),
			'imagepng' => array('writeFile' => 1,),
			'imagewbmp' => array('writeFile' => 1,),
		),
		'createDir' => array(
			'mkdir' => array('createDir' => 0, 'createDirRecursive' => 2,),
		),
		'rmDir' => array(
			'rmdir' => array('rmDir' => 0,),
		),
		'rmFile' => array(
			'unlink' => array('rmFile' => 0,),
		),
	);
}
