<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
?>
<?php
if ( isset( $_REQUEST ) )
  $postArray = &$_REQUEST ;                  // 4.1.0 or later, use $_POST
else
  $postArray = &$HTTP_GET_VARS ; // prior to 4.1.0, use HTTP_POST_VARS

foreach ( $postArray as $sForm => $value )
{
/*
        if ( get_magic_quotes_gpc() )
		$postedValue = htmlspecialchars( stripslashes( $value ) ) ;
	else
		$postedValue = htmlspecialchars( $value ) ;

*/
?>
<?php echo $value?>
	<?php
}
?>
