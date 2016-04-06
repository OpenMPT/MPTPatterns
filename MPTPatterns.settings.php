<?php
###############################################################################
#  MPTPatterns settings
#  (c)opyleft 2009,2011 cubaxd
###############################################################################
#
# NOTE: There is in most cases no data check and no error message if you enter
#       flawy values! So be careful.
#
###############################################################################

function MPTPatterns_settings($mpt) {

	# attribute names
	$mpt->env['attribute']['format']      = 'format';
	$mpt->env['attribute']['title']       = 'title';
	$mpt->env['attribute']['css']         = 'css';
	$mpt->env['attribute']['identifier']  = 'id';
	$mpt->env['attribute']['highlight']   = 'highlight';
	$mpt->env['attribute']['float']       = 'float';
	$mpt->env['attribute']['width']       = 'width';

	# attribute values
	$mpt->env['txt']['on']                = 'on';
	$mpt->env['txt']['off']               = 'off';
	$mpt->env['txt']['left']              = 'left';
	$mpt->env['txt']['center']            = 'center';
	$mpt->env['txt']['right']             = 'right';

/// ////////////////////////////////////////////////////////////////////
/// BEHAVIOUR
///

	# limits
	$mpt->env['maxchannels' ] =  8; # limit the number of channels
	$mpt->env['maxrows'     ] = 64; # max number of rows in a pattern

	$mpt->env['stdhighlight'] =  0; # standard: highlight every 'X'th row


/// ////////////////////////////////////////////////////////////////////
/// CSS
///

	# CSS class names
	# parent (div)
	$mpt->env['class']['frame']    = 'mpt';
	# children (span) the shorter the names the better
	$mpt->env['class']['title']    = 'title'; // (div)
	$mpt->env['class']['id']       = 'id';
	$mpt->env['class']['highlight']= 'hig'; // bg only
	$mpt->env['class']['note']     = 'not';
	$mpt->env['class']['instr']    = 'ins';
	$mpt->env['class']['global']   = 'glo';
	$mpt->env['class']['panning']  = 'pan';
	$mpt->env['class']['volume']   = 'vol';
	$mpt->env['class']['pitch']    = 'pit';
	$mpt->env['class']['other']    = 'oth';
	$mpt->env['class']['default']  = 'def';
	$mpt->env['class']['divider']  = 'div';

/// ////////////////////////////////////////////////////////////////////
/// MODULE FORMATS
///

	# This is a list of all module formats fully supported by OpenMPT
	# The array $env['format_long'] holds the identifier strings, which are
	# used when you copy/paste pattern sequences in OpenMPT's pattern editor.
	# One of these identifiers always appears in the first line of a
	# copied sequence.
	$mpt->env['format_long']    = array(
		'ModPlug Tracker  IT',  /* 0 */
		'ModPlug Tracker  XM',  /* 1 */
		'ModPlug Tracker MPT',  /* 2 */
		'ModPlug Tracker S3M',  /* 3 */
		'ModPlug Tracker MOD'); /* 4 */

	# 'format_short' is used if a user doesn't include the identifier string
	# along with the pattern data. In this case one should set one of these
	# identifiers using the "format" attribute (i.e. <pattern format="IT">).
	$mpt->env['format_short']   = array(
		'IT',                   /* 0 */
		'XM',                   /* 1 */
		'MPT',                  /* 2 */
		'S3M',                  /* 3 */
		'MOD');                 /* 4 */

	# if no format was determined, highlight patterns as ...
	$mpt->env['standardformat'] = 'IT';


/// ////////////////////////////////////////////////////////////////////
/// COMMAND SETS
///
	$mpt->env['categories']=array('global','panning','volume','pitch','other');

	# IT Command set
	$mpt->env['fx']['IT'][ 'global' ] = 'ABCTVW';
	$mpt->env['fx']['IT'][ 'panning'] = 'pPXY';
	$mpt->env['fx']['IT'][ 'volume' ] = 'abcdvDKLMNR';
	$mpt->env['fx']['IT'][ 'pitch'  ] = 'efghouEFGHU';
	$mpt->env['fx']['IT'][ 'other'  ] = 'IJOQSZ\\#';

	# MPT Command set
	$mpt->env['fx']['MPT'][ 'global' ] = 'ABCTVW';
	$mpt->env['fx']['MPT'][ 'panning'] = 'pPXY';
	$mpt->env['fx']['MPT'][ 'volume' ] = 'abcdvDKLMNR';
	$mpt->env['fx']['MPT'][ 'pitch'  ] = 'efghouEFGHU';
	$mpt->env['fx']['MPT'][ 'other'  ] = 'IJOQSZ\\#:';

	# S3M Command set
	$mpt->env['fx']['S3M']['global' ] = 'ABCTVW';
	$mpt->env['fx']['S3M']['panning'] = 'pPXY';
	$mpt->env['fx']['S3M']['volume' ] = 'vDKLMNR';
	$mpt->env['fx']['S3M']['pitch'  ] = 'EFGHU';
	$mpt->env['fx']['S3M']['other'  ] = 'IJOQSZ\\#';

	# XM Command set
	$mpt->env['fx']['XM'][ 'global' ] = 'BDFGH';
	$mpt->env['fx']['XM'][ 'panning'] = 'lpr8PY';
	$mpt->env['fx']['XM'][ 'volume' ] = 'abcdv567AC';
	$mpt->env['fx']['XM'][ 'pitch'  ] = 'ghu1234';
	$mpt->env['fx']['XM'][ 'other'  ] = '09EKLRTZ\\#';

	# MOD Command set
	$mpt->env['fx']['MOD']['global' ] = 'BDF';
	$mpt->env['fx']['MOD']['panning'] = '8';
	$mpt->env['fx']['MOD']['volume' ] = '567AC';
	$mpt->env['fx']['MOD']['pitch'  ] = '1234';
	$mpt->env['fx']['MOD']['other'  ] = '09E';



/// ////////////////////////////////////////////////////////////////////
/// STATIC VALUES. NEVER CHANGE THEM!!!
///
	$mpt->env['divider']  = '|'; # divider character
	$mpt->env['lennote']  =  3;
	$mpt->env['leninstr'] =  2;
	$mpt->env['lenvol']   =  3;
	$mpt->env['lenfx']    =  3;
	$mpt->env['bytesperchannel']=12;

}
?>
