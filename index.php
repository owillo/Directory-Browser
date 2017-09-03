
<?php require_once('functions.php'); ?><?php

?>
<?php

function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 

    // Uncomment one of the following alternatives
     $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow)); 

    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 



/*

3) Upload to server

CONFIGURATION
=============
Edit the variables in this section to make the script work as
you require.

Include URL - If you are including this script in another file, 
please define the URL to the Directory Listing script (relative
from the host)
*/
$includeurl = false;

/*
Start Directory - To list the files contained within the current 
directory enter '.', otherwise enter the path to the directory 
you wish to list. The path must be relative to the current 
directory and cannot be above the location of index.php within the 
directory structure.
*/
$startdir = 'archive_files';

if(isset($_GET['clr']) || ($_GET['clr']==1)){
	$_SESSION['search']="";
	unset($_SESSION['search']);
	$_SESSION['searchtype']="";
	unset($_SESSION['searchtype']);
	$logoutGoTo ="index.php";
	header("Location: $logoutGoTo");
	}

if(isset($_POST['search']) && ($_POST['search']<>"")){
	
	$_SESSION['search']=trim($_POST['search']);
	
	}else if(isset($_GET['search']) && ($_GET['search']<>"")){
	
	$_SESSION['search']= $_GET['search'];
	
	}

if(isset($_POST['searchtype']) && ($_POST['searchtype']<>0)){
	
	$_SESSION['searchtype']=$_POST['searchtype'];
	
	}else if(isset($_GET['searchtype']) && ($_GET['searchtype']<>0)){
	
	$_SESSION['searchtype']= $_GET['searchtype'];
	
	}
	
	if(isset($_SESSION['searchtype']) && ($_SESSION['searchtype']==2)){
		$chkd ="checked='checked'"; 
		}else{
			$chkf ="checked='checked'";
		}
		

/*
Show Thumbnails? - Set to true if you wish to use the 
scripts auto-thumbnail generation capabilities.
This requires that GD2 is installed.
*/
$showthumbnails = true; 

/*
Memory Limit - The image processor that creates the thumbnails
may require more memory than defined in your PHP.INI file for 
larger images. If a file is too large, the image processor will
fail and not generate thumbs. If you require more memory, 
define the amount (in megabytes) below
*/
$memorylimit = false; // Integer

/*
Show Directories - Do you want to make subdirectories available?
If not set this to false
*/
$showdirs = true;

/* 
Force downloads - Do you want to force people to download the files
rather than viewing them in their browser?
*/
$forcedownloads = false;

/*
Hide Files - If you wish to hide certain files or directories 
then enter their details here. The values entered are matched
against the file/directory names. If any part of the name 
matches what is entered below then it is not shown.
*/
$hide = array(
				'res',
				'index.php',
				'archive_files',
				'Thumbs',
				'.htaccess',
				'ftpquota',
				'.htpasswd'
			);
			
/* Only Display Files With Extension... - if you only wish the user
to be able to view files with certain extensions, add those extensions
to the following array. If the array is commented out, all file
types will be displayed.
*/
/*$showtypes = array(
					'jpg',
					'png',
					'gif',
					'zip',
					'txt'
				);*/
			 
/* 
Show index files - if an index file is found in a directory
to you want to display that rather than the listing output 
from this script?
*/			
$displayindex = false;

/*
Allow uploads? - If enabled users will be able to upload 
files to any viewable directory. You should really only enable
this if the area this script is in is already password protected.
*/
$allowuploads = false;

/* Upload Types - If you are allowing uploads but only want
users to be able to upload file with specific extensions,
you can specify these extensions below. All other file
types will be rejected. Comment out this array to allow
all file types to be uploaded.
*/
/*$uploadtypes = array(
						'zip',
						'gif',
						'doc',
						'png'
					);*/

/*
Overwrite files - If a user uploads a file with the same
name as an existing file do you want the existing file
to be overwritten?
*/
$overwrite = false;

/*
Index files - The follow array contains all the index files
that will be used if $displayindex (above) is set to true.
Feel free to add, delete or alter these
*/

$indexfiles = array (
				'index.html',
				'index.htm',
				'default.htm',
				'default.html'
			);
			
/*
File Icons - If you want to add your own special file icons use 
this section below. Each entry relates to the extension of the 
given file, in the form <extension> => <filename>. 
These files must be located within the res directory.
*/
$filetypes = array (
				'png' => 'png.png',
				'jpeg' => 'jpg.png',
				'jpg' => 'jpg.png',
				'bmp' => 'bmp.png',
				'gif' => 'gif.gif',
				'zip' => 'archive.png',
				'rar' => 'archive.png',
				'exe' => 'exe.gif',
				'setup' => 'setup.gif',
				'txt' => 'text.png',
				'htm' => 'html.gif',
				'html' => 'html.gif',
				'fla' => 'fla.gif',
				'swf' => 'swf.gif',
				'xls' => 'xls.gif',
				'xlsx' => 'xls.gif',
				'xlsm' => 'xls.gif',
				'csv' => 'xls.gif',
				'doc' => 'doc.gif',
				'docx' => 'doc.gif',
				'pptx' => 'ppt.png',
				'ppt' => 'ppt.png',
				'rtf' => 'doc.gif',
				'sig' => 'sig.gif',
				'fh10' => 'fh10.gif',
				'pdf' => 'pdf.gif',
				'psd' => 'psd.gif',
				'rm' => 'real.gif',
				'mpg' => 'video.png',
				'mpeg' => 'video.png',
				'mov' => 'video.png',
				'avi' => 'video.png',
				'mp4' => 'video.png',
				'eps' => 'eps.gif',
				'ai' => 'ai.png',
				'gz' => 'archive.png',
				'asc' => 'sig.gif',
			);
			
/*
That's it! You are now ready to upload this script to the server.

Only edit what is below this line if you are sure that you know what you
are doing!
*/

if($includeurl)
{
	$includeurl = preg_replace("/^\//", "${1}", $includeurl);
	if(substr($includeurl, strrpos($includeurl, '/')) != '/') $includeurl.='/';
}

error_reporting(0);
if(!function_exists('imagecreatetruecolor')) $showthumbnails = false;
if($startdir) $startdir = preg_replace("/^\//", "${1}", $startdir);
$leadon = $startdir;
if($leadon=='.') $leadon = '';
if((substr($leadon, -1, 1)!='/') && $leadon!='') $leadon = $leadon . '/';
$startdir = $leadon;
//$showthumbnails = false;
if($_GET['dir']) {
	//check this is okay.
	
	if(substr($_GET['dir'], -1, 1)!='/') {
		$_GET['dir'] = strip_tags($_GET['dir']) . '/';
	}
	
	$dirok = true;
	$dirnames = split('/', strip_tags($_GET['dir']));
	for($di=0; $di<sizeof($dirnames); $di++) {
		
		if($di<(sizeof($dirnames)-2)) {
			$dotdotdir = $dotdotdir . $dirnames[$di] . '/';
		}
		
		if($dirnames[$di] == '..') {
			$dirok = false;
		}
	}
	
	if(substr($_GET['dir'], 0, 1)=='/') {
		$dirok = false;
	}
	
	if($dirok) {
		 $leadon = $leadon . strip_tags($_GET['dir']);
	}
}
$upload_dir =$startdir;

if($_GET['download'] && $forcedownloads) {
	$file = str_replace('/', '', $_GET['download']);
	$file = str_replace('..', '', $file);

	if(file_exists($includeurl . $leadon . $file)) {
		header("Content-type: application/x-download");
		header("Content-Length: ".filesize($includeurl . $leadon . $file)); 
		header('Content-Disposition: attachment; filename="'.$file.'"');
		readfile($includeurl . $leadon . $file);
		die();
	}
	die();
}

if($allowuploads && $_FILES['file']) {
	$upload = true;
	if(!$overwrite) {
		if(file_exists($leadon.$_FILES['file']['name'])) {
			$upload = false;
		}
	}
	
	if($uploadtypes)
	{
		if(!in_array(substr($_FILES['file']['name'], strpos($_FILES['file']['name'], '.')+1, strlen($_FILES['file']['name'])), $uploadtypes))
		{
			$upload = false;
			$uploaderror = "<strong>ERROR: </strong> You may only upload files of type ";
			$i = 1;
			foreach($uploadtypes as $k => $v)
			{
				if($i == sizeof($uploadtypes) && sizeof($uploadtypes) != 1) $uploaderror.= ' and ';
				else if($i != 1) $uploaderror.= ', ';
				
				$uploaderror.= '.'.strtoupper($v);
				
				$i++;
			}
		}
	}
	
	if($upload) {
		move_uploaded_file($_FILES['file']['tmp_name'], $includeurl.$leadon . $_FILES['file']['name']);
	}
}

$opendir = $includeurl.$leadon;
if(!$leadon) $opendir = '.';
if(!file_exists($opendir)) {
	$opendir = '.';
	$leadon = $startdir;
}

clearstatcache();
if(!isset($_SESSION['search'])){
	
	if ($handle = opendir($opendir)) {
	while (false !== ($file = readdir($handle))) { 
		//first see if this file is required in the listing
		if ($file == "." || $file == "..")  continue;
		$discard = false;
		for($hi=0;$hi<sizeof($hide);$hi++) {
			if(strpos($file, $hide[$hi])!==false) {
				$discard = true;
			}
		}
		
		if($discard) continue;
		if (@filetype($includeurl.$leadon.$file) == "dir") {
			if(!$showdirs) continue;
		
			$n++;
			if($_GET['sort']=="date") {
				$key = @filemtime($includeurl.$leadon.$file) . ".$n";
			}
			else {
				$key = $n;
			}
			$dirs[$key] = $file . "/";
		}
		else {
			$n++;
			if($_GET['sort']=="date") {
				$key = @filemtime($includeurl.$leadon.$file) . ".$n";
			}
			elseif($_GET['sort']=="size") {
				$key = @filesize($includeurl.$leadon.$file) . ".$n";
			}
			else {
				$key = $n;
			}
			
			if($showtypes && !in_array(substr($file, strpos($file, '.')+1, strlen($file)), $showtypes)) unset($file);
			if($file) $files[$key] = $file;
			
			if($displayindex) {
				if(in_array(strtolower($file), $indexfiles)) {
					header("Location: $leadon$file");
					die();
				}
			}
		}
	}
	closedir($handle); 
}
}

if((isset($_SESSION['search'])) && ($_SESSION['searchtype']==1)){
		$it = new RecursiveDirectoryIterator($startdir);
		$class='b f';
		$added = 0;
		foreach(new RecursiveIteratorIterator($it) as $file)
		{
			$filePart = $file;
			$ext = explode('.', $file);
			$extstr =  end($ext);
			$filenm2_arr = explode('/', $file);
			$filename2 =  end($filenm2_arr);
			if(strpos(strtolower($filename2),strtolower($_SESSION['search'])) !== false){
				$files[] = $file;
				$added = $added + 1;
			}
			$addedstr = $added ." file(s)";
		}
		
	}else if((isset($_SESSION['search'])) && ($_SESSION['searchtype']==2)){
	
		$class='b d';
		$added = 0;
		
		$iterator = new DirectoryIterator($startdir);
foreach ($iterator as $fileinfo) {
    if ($fileinfo->isDir() && !$fileinfo->isDot()) {
      if(strpos(strtolower($fileinfo),strtolower($_SESSION['search'])) !== false){  //echo $fileinfo->getFilename() . "\n";
		$dirs[] = $fileinfo->getFilename();
		$added = $added + 1;
        // recursion goes here.
    }
	$addedstr = $added ." folder(s)";
	}
}
	}



//sort our files
if($_GET['sort']=="date") {
	@ksort($dirs, SORT_NUMERIC);
	@ksort($files, SORT_NUMERIC);
}
elseif($_GET['sort']=="size") {
	@natcasesort($dirs); 
	@ksort($files, SORT_NUMERIC);
}
else {
	@natcasesort($dirs); 
	@natcasesort($files);
}

//order correctly
if($_GET['order']=="desc" && $_GET['sort']!="size") {$dirs = @array_reverse($dirs);}
if($_GET['order']=="desc") {$files = @array_reverse($files);}
$dirs = @array_values($dirs); $files = @array_values($files);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>ARCHIVES</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="res/css/AdminLTE.min.css">
<link rel="stylesheet" type="text/css" href="res/styles.css" />

<?php
if($showthumbnails) {
?>
<script language="javascript" type="text/javascript">
<!--
function o(n, i) {
	document.images['thumb'+n].src = '<?php echo $includeurl; ?>res/i.php?f='+i<?php if($memorylimit!==false) echo "+'&ml=".$memorylimit."'"; ?>;

}

function f(n) {
	document.images['thumb'+n].src = 'res/trans.gif';
}
//-->
</script>
<?php
}
?>
</head>
<body>
<div id="container">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="padding:13px 13px;">
  <tr>
    <td><h1><img src="LOGO.png" width="140" height="47" />&nbsp;<br />
      DOCUMENT ARCHIVES</h1></td>
    <td>&nbsp;</td>
  </tr>
</table>

  <div id="breadcrumbs" style="padding:13px 0px 7px;"><?php  if(isset($_SESSION['search'])){  ?> <a href="<?php echo strip_tags($_SERVER['PHP_SELF']);?>?clr=1">back to browse</a> <?php  }else{  ?> <a href="<?php echo strip_tags($_SERVER['PHP_SELF']);?>">home</a>  <?php  }  ?>
  <?php
 	 $breadcrumbs = split('/', str_replace($startdir, '', $leadon));
  	if(($bsize = sizeof($breadcrumbs))>0) {
  		$sofar = '';
  		for($bi=0;$bi<($bsize-1);$bi++) {
			$sofar = $sofar . $breadcrumbs[$bi] . '/';
			echo ' &gt; <a href="'.strip_tags($_SERVER['PHP_SELF']).'?dir='.urlencode($sofar).'">'.$breadcrumbs[$bi].'</a>';
		}
  	}
  
	$baseurl = strip_tags($_SERVER['PHP_SELF']) . '?dir='.strip_tags($_GET['dir']) . '&amp;';
	$fileurl = 'sort=name&amp;order=asc';
	$sizeurl = 'sort=size&amp;order=asc';
	$dateurl = 'sort=date&amp;order=asc';
	
	switch ($_GET['sort']) {
		case 'name':
			if($_GET['order']=='asc') $fileurl = 'sort=name&amp;order=desc';
			break;
		case 'size':
			if($_GET['order']=='asc') $sizeurl = 'sort=size&amp;order=desc';
			break;
			
		case 'date':
			if($_GET['order']=='asc') $dateurl = 'sort=date&amp;order=desc';
			break;  
		default:
			$fileurl = 'sort=name&amp;order=desc';
			break;
	}
	
	if(isset($_SESSION['search'])){
		
		$fileurl = $fileurl."&search=".$_SESSION['search'];
		$sizeurl = $sizeurl."&search=".$_SESSION['search'];
	$dateurl = $dateurl."&search=".$_SESSION['search'];
		}
	
  ?>
  </div>
  <div id="listingcontainer">
  <div id="formheader"> 
	<div style="width:400px; float:left;padding:12px; background-color:#FF9;" ><form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	  <input name="search" type="text" style="float:left; margin-right:10px;" value="<?php echo $_SESSION['search']; ?>" size="25" />
	  <input name="searchtype" type="radio" id="radio" style="float:left;" value="1" <?php echo $chkf; ?>/>
	  <label for="radio" style="float:left;font-size:12px;margin-right:15px; margin-top:3px; color:#000;">File</label>
      <input type="radio" name="searchtype" id="radio" value="2" style="float:left;" <?php echo $chkd; ?> />
	  <label for="radio" style="float:left; font-size:12px;margin-right:10px; margin-top:3px; color:#000;">Folder</label>
<input name="but" type="submit" value="Search" style="float:left;" /><BR style="clear:both;" /></form></div><?php  if(isset($_SESSION['search'])){  ?><div style="width:330px; float:left; font-size:14px; padding:10px;" >Search Results for: <span class="rStr">"<?php echo $_SESSION['search']; ?>"</span> returns <span class="rStr"><?php echo $addedstr; ?></span></div>
	<div id="rmv"><a href="<?php echo strip_tags($_SERVER['PHP_SELF']);?>?clr=1">clear search</a></div>
	<?php  }  ?>
	<BR style="clear:both;" />
	</div>
  <div id="listingheader"> 
    <div id="headerfile"><a href="<?php echo $baseurl . $fileurl;?>">File</a></div>
	<div id="headersize"><a href="<?php echo $baseurl . $sizeurl;?>">Size</a></div>
	<div id="headermodified"><a href="<?php echo $baseurl . $dateurl;?>">Last Modified</a></div><div style="clear:both; height:1px; margin:0px; padding:0px;" /></div>
	</div>
    <div id="listing">
      <?php
	
	if($dirok) {
	?>
      <div id=""><a href="<?php echo strip_tags($_SERVER['PHP_SELF']).'?dir='.urlencode($dotdotdir);?>" id="headerx"><img src="<?php echo $includeurl; ?>res/dirup.png" alt="Folder" /><strong>..</strong> <em>&nbsp;</em>&nbsp;</a></div>
	<?php
	
	
		//if(($class=='b d')){ $class='w d';}else if($class=='w d') {$class = 'b d';	};
	}
	
		$arsize = sizeof($dirs);
	$class='b d';
	for($i=0;$i<$arsize;$i++) {
		if(($class=='b d')){ $class='w d';}else if($class=='w d') {$class = 'b d';	};
	?>
	<div><a href="<?php echo strip_tags($_SERVER['PHP_SELF']).'?dir='.urlencode(str_replace($startdir,'',$leadon).$dirs[$i]);?>" class="<?php echo $class;?>"><img src="<?php echo $includeurl; ?>res/folder.png" alt="<?php echo $dirs[$i];?>" /><span class="nm"><?php echo str_replace("/","",$dirs[$i]);?></span> <em>-</em> <span class="smaller"><?php echo date ("M d Y h:i:s A", filemtime($includeurl.$leadon.$dirs[$i]));?></span></a><a href="<?php echo strip_tags($_SERVER['PHP_SELF']).'?dir='.urlencode(str_replace($startdir,'',$leadon).$dirs[$i]);?>" class="openfolder <?php echo $class;?>"  target="_blank" >&nbsp;</a></div>
	<?php
	
		
		
	}

	
	
	$arsize = sizeof($files);
	for($i=0;$i<$arsize;$i++) {
		
		if((isset($_SESSION['search'])) && ($_SESSION['searchtype']==1)){
		
		$filenm_arr = explode('/', $files[$i]);
			$filename =  end($filenm_arr);
			$path = str_replace($filename,"",$files[$i]);
			$path2 = str_replace("archive_files/","",$path);  
			$path3 = str_replace("/","%2F",$path2); 
			$path4 = str_replace(" ","+",$path3);
		$fileurl = $files[$i];
		
			}else{
				
				$filename = $files[$i];
				$fileurl = $includeurl . $leadon . $files[$i];
			}
		
		
		
		if(($class=='b d')){ $class='b f';}else if($class=='w d') {$class = 'w f';	};
		if(($class=='b f')){ $class='w f';}else if($class=='w f') {$class = 'b f';	};
		$icon = 'unknown.png';
		$ext = strtolower(substr($files[$i], strrpos($files[$i], '.')+1));
		$supportedimages = array('gif', 'png', 'jpeg', 'jpg');
		$thumb = '';
		
		if($showthumbnails && in_array($ext, $supportedimages)) {
			$thumb = '<span><img src="res/trans.gif" alt="'.$files[$i].'" name="thumb'.$i.'" /></span>';
			if(isset($_SESSION['search'])){
			$thumb2 = ' onmouseover="o('.$i.', \''.urlencode($files[$i]).'\');" onmouseout="f('.$i.');"';
			}else{
				
				$thumb2 = ' onmouseover="o('.$i.', \''.urlencode($leadon . $files[$i]).'\');" onmouseout="f('.$i.');"';
			}
			
		}
		
		if($filetypes[$ext]) {
			$icon = $filetypes[$ext];
		}
		
		
		if(strlen($filename)>73) {
			$filename = substr($files[$i], 0, 70) . '...';
		}
		
		
		if($forcedownloads) {
			$fileurl = $_SESSION['PHP_SELF'] . '?dir=' . urlencode(str_replace($startdir,'',$leadon)) . '&download=' . urlencode($files[$i]);
		}

	?>
	<div><a href="<?php echo $fileurl;?>" class="<?php echo $class;?>" <?php echo $thumb2;?>><img src="<?php echo $includeurl; ?>res/<?php echo $icon;?>" alt="<?php echo $files[$i];?>" /><span class="nm"><?php echo $filename;?></span> <em><?php echo formatBytes(filesize($fileurl),2);?></em> <span class="smaller"><?php echo date ("M d Y h:i:s A", filemtime($fileurl));?></span><?php echo $thumb;?></a>
    
	<?php  if((isset($_SESSION['search'])) && ($_SESSION['searchtype']==1)){  ?><a href="<?php echo "index.php?dir=".urlencode($path2); ?>" class="openfolder <?php echo $class;?>" target="_blank">&nbsp;</a><?php  }else{  ?><a href="<?php echo $fileurl;?>" class="openfile <?php echo $class;?>" target="_new" s>&nbsp;</a><?php  }  ?>
    
    
  <div style="clear:both; height:1px; margin:0px; padding:0px;" /></div></div>
	<?php
			
	}	
	?></div><div style="clear:both; height:1px; margin:0px; padding:0px;" /></div></div>
	
  </div><div style="clear:both; height:1px; margin:0px; padding:0px;" /></div></div>
</div>

</body>
</html>
