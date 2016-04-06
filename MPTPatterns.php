<?php
###############################################################################
#  MPTPatterns
#  (c)opyleft 2009,2011 cubaxd <cubaxd 0x40 yahoo 0x2e de>
#
#  A MediaWiki extension which displays and highlights OpenMPT patterns.
#  Written for the German OpenMPT Wiki (http://wikide.openmpt.org/).
#
#  URL: https://wikide.openmpt.org/OpenMPT-Wiki:Werkstatt/MPTPatterns
#
#  OpenMPT (formerly known as ModPlug Tracker)
#  is a music tracker program for Microsoft Windows. See https://openmpt.org/
#
#  Version: 0.5a2
#
###############################################################################
#  License: GNU General Public License v3
#
#  This program is free software: you can redistribute it and/or modify
#  it under the terms of the GNU General Public License as published by
#  the Free Software Foundation, either version 3 of the License, or
#  (at your option) any later version.
#
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU General Public License for more details.
#
#  // You should have received a copy of the GNU General Public License
#  // along with this program.  If not, see http://www.gnu.org/licenses/
#
#  Read The License text here: http://www.gnu.org/licenses/gpl.txt
#
###############################################################################
#  Changelog:
#  I've changed my mind.
###############################################################################
# notices, warnings and error messages will be enclosed by <!-- -->
# side effect is for each run a set of <!-- --> is produced even without errs
define ('MPT_COMMENT_OUT_PHP_WARNINGS', 0);

if (!defined( 'MEDIAWIKI' ) ) {
	echo( "This is an extension to the MediaWiki package and cannot be run standalone.\n" );
	die(-1);
}

$wgAutoloadClasses['MPTPatterns'] = dirname( __FILE__ ) . '/MPTPatterns.class.php';

//Avoid unstubbing $wgParser on setHook() too early on modern (1.12+) MW versions, as per r35980
if ( defined( 'MW_SUPPORTS_PARSERFIRSTCALLINIT' ) ) {
	$wgHooks['ParserFirstCallInit'][] = 'efMPTPatternsInit';
} else { // Otherwise do things the old fashioned way
	$wgExtensionFunctions[] = 'efMPTPatternsInit';
}

function efMPTPatternsInit(&$parser) {
	$parser->setHook('pattern', 'efMPTPatterns');
	return true;
}

function efMPTPatterns( $input, $args, $parser) {
// 	error_reporting(E_ALL);
	require_once(dirname(__FILE__).'/MPTPatterns.settings.php');
	if (MPT_COMMENT_OUT_PHP_WARNINGS) echo "<!-- "; // comment out php error messages
	$mpt = new MPTPatterns;
	MPTPatterns_settings($mpt);
	return $mpt->Pattern( $input, $args, $parser);
}

?>
