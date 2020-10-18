<?php
###############################################################################
#  MPTPatterns class
#  (c)opyleft 2009,2011 cubaxd
###############################################################################

class MPTPatterns {

	var $env=array();	// Settings
	var $attr=array();	// Attributes
	var $mod=array();	// Pattern data
	var $seq=array();	// Input data

	/**++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 * Pattern($input, $args, $parser)
	 *
	 * 'main' function
	 */
	function Pattern($input, $args, $parser) {
		// Read attributes
		$this->readAttributes($args);
		
		// The pattern data split at the new line char
		$this->seq = explode("\n", htmlspecialchars($input));
		
		// Determine module format
		$this->getFormat();
		
		// Write input data into an array for HTML conversion
		$this->readPattern();

		// Convert data to HTML
		return $this->printPattern();
	}

	/**++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 * readAttributes()
	 *
	 * Reads all attributes of the pattern tag.
	 * If an attribute X is empty/not set, the corresponding
	 * attr['X'] variable is set to null by the function getAttribute(),
	 * otherwise it will be set to the attribute's value, whith
	 * argument 2 ($option) of getAttribute() determining
	 * how to assign the value:
	 *  0 (null):       assign value unchanged,
	 *  1 ($numeric):   assign value only if numeric
	 *  2 ($lowercase): convert to lowercase letters
	 *  3 ($uppercase): convert to uppercase letters
	 */
	private function readAttributes($args) {
		$numeric=1; $lowercase=2; $uppercase=3;

		$this->attr['title']     = $this->getAttribute($args[$this->env['attribute']['title']] ?? null);
		$this->attr['format']    = $this->getAttribute($args[$this->env['attribute']['format']] ?? null, $uppercase);
		$this->attr['identifier']= $this->getAttribute($args[$this->env['attribute']['identifier']] ?? null, $lowercase);
		$this->attr['float']     = $this->getAttribute($args[$this->env['attribute']['float']] ?? null, $lowercase);
		$this->attr['highlight'] = $this->getAttribute($args[$this->env['attribute']['highlight']] ?? null, $numeric);
		$this->attr['width']     = $this->getAttribute($args[$this->env['attribute']['width']] ?? null, $numeric);
		$this->attr['css']       = $this->getAttribute($args[$this->env['attribute']['css']] ?? null);
	}

	/**++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 * getAttribute($val, $option)
	 *
	 * Checks an attribute of the pattern tag, converts its value if needed
	 * and returns the data
	 *
	 * @param any     $val
	 *                value of the given attribute
	 * @param integer $option
	 *                0: off
	 *                1: check if numeric
	 *                2: return value in lowercase letters
	 *                3: return value in uppercase letters
	 */
	private function getAttribute($val, $option=null) {
		$numeric=1; $lowercase=2; $uppercase=3;
		if (isset($val)) {
			switch ($option) {
				case $numeric:
					return ( is_numeric($val) ) ? $val : null;
				case $lowercase:
					return strtolower(htmlspecialchars($val));
				case $uppercase:
					return strtoupper(htmlspecialchars($val));
			}
			return htmlspecialchars($val);
		}
		// Attribute not set
		return null;
	}

	/**++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 * getFormat()
	 *
	 * Search for identifier string in pattern, or if the search was
	 * unsuccessful, try to read the 'format' attribute.
	 * This is neccessary as we must know which effect commands to highlight
	 * in which color. If the format was not determined, the standard format
	 * will be used, which defaults to the Impulse Tracker format, but can be
	 * changed in the settings to any other format in the list.
	 *
	 * $attr['format'] will contain the short identifier (i.e: "S3M"), and
	 * $mod['format'] the long one ("ModPlug Tracker S3M")
	 */
	private function getFormat() {
		// try to read Identifier string
		// (which usually begins with "ModPlug Tracker ")
		$this->mod['format']="";

		for ($i=0; $i<count($this->seq); $i++) {

			// compare current $input line with ID strings
			for ($j=0; $j<count($this->env['format_long']); $j++) {
				if ($this->seq[$i] == $this->env['format_long'][$j]) {
					$this->mod['format']=$this->env['format_long'][$j];
					break;
				}
			}

			// Identifier found, we can abort the loop now
			if ($this->mod['format'] && $this->mod['format']!="")
				break;
			
			// as soon as the first '|' character appears, it is unlikely for
			// the identifier string to appear since it is usually at the top
			// of the copied pattern.
			if (substr($this->seq[$i], 0, 1) == $this->env['divider'])
				break;
		}

		// ... the identifier string couldn't be found in the pattern.
		// Let's try it via the format attribute of the <pattern> tag
		if (!$this->mod['format'] || $this->mod['format']=="") {
		
			// Look if the "format" attribute is set.
			if (!is_null($this->attr['format']) && $this->attr['format']>'') {
			
				// compare the attribute value with all ID strings
				for ($i=0; $i<count($this->env['format_long']); $i++) {

					// format string valid
					if ($this->attr['format'] == $this->env['format_short'][$i]) {
						// assign
						$this->mod['format']=$this->env['format_long'][$i];
						break;
					}
				}
			}
			// neither identifier string found, nor format attribute set
			// We're going to use the standard format in this case
			else
				$this->mod['format'] = $this->getLongIdentifier($this->env['standardformat']);

		}
		// set the short name of the identifier
		$this->attr['format'] = $this->getShortIdentifier($this->mod['format']);
	}


	/**++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 * getLongIdentifier()
	 * returns the long ID of a short one
	 * (i.e. "IT" --> "ModPlug Tracker  IT"  )
	 */
	private function getLongIdentifier($short) {
		for ($i=0; $i<$this->env['format_short']; $i++)
			if ($short == $this->env['format_short'][$i])
				return $this->env['format_long'][$i];

		return null;
	}

	/**++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 * getShortIdentifier()
	 *
	 * the opposite of getLongIdentifier()
	 */
	private function getShortIdentifier($long) {
		for ($i=0; $i<$this->env['format_long']; $i++)
			if ($long == $this->env['format_long'][$i])
				return $this->env['format_short'][$i];

		return null;
	}

	/**++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 * isSwitchedOff($val)
	 *
	 * returns true if an attribute's value is 'off'
	 */
	private function isSwitchedOff($val) {
		if ($val == $this->env['txt']['off'])
			return true;
		return false;
	}

	/**++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 * isSwitchedOn($val)
	 *
	 * returns true if an attribute's value is 'on'
	 */
	private function isSwitchedOn($val) {
		if ($val == $this->env['txt']['on'])
			return true;
		return false;
	}

	/**++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 * space2Nbsp($str)
	 *
	 * returns string $str with the first of two successive space characters
	 * converted to a non-breaking space.
	 */
	private function space2Nbsp($str) {
		return str_replace('  ', "\x26nbsp; ", $str);
	}

	/**++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	* readPattern()
	*
	* writes the pattern data into an array
	*/
	private function readPattern() {
		// Read every line
		// $zeile ist a line in the input array $this->seq
		// $row is a line in the output array $mod['dat']
		$row=0;

		$count_seq=count($this->seq);
		for ($zeile=0; $zeile<$count_seq; $zeile++) {
			// Lines shorter than 12 chars and/or lines not beginning with `|'
			// are ignored

			# limit rows
			if ($row>=$this->env['maxrows'])
				break;

			if ( (strlen($this->seq[$zeile]) >= $this->env['bytesperchannel'] )
			  && (substr($this->seq[$zeile], 0, 1) == $this->env['divider'])  )
			{
				// each channel consists of 'bytesperchannel' bytes
				$len_row=strlen($this->seq[$zeile]);
				for ($pos=0, $channel=0;
					$pos<$len_row;
					$pos+=$this->env['bytesperchannel'], $channel++)
				{
					# limit the number of channels
					if ($channel>=$this->env['maxchannels'])
						break;

					// Check if a channel's first byte is the divider char ('|'),
					// This way we avoid having nonsense in the output array.
					if (substr($this->seq[$zeile], $pos, strlen($this->env['divider']))
					== $this->env['divider'])
					{
						$p=1; // strlen($this->env['divider']);
						
						# Note (3 chars: 1-3 (first char is 0))
						$this->mod['dat'][$row][$channel]['note']
						 = substr($this->seq[$zeile], $pos+$p, $this->env['lennote']);

						$p+=$this->env['lennote'];
						# Instrument (2 chars: 4-5)
						$this->mod['dat'][$row][$channel]['instr']
						 = substr($this->seq[$zeile], $pos+$p, $this->env['leninstr']);

						$p+=$this->env['leninstr'];
						# Volume (3 chars: 6-8)
						$this->mod['dat'][$row][$channel]['vol']
						 = substr($this->seq[$zeile], $pos+$p, $this->env['lenvol']);

						$p+=$this->env['lenvol'];
						# Effect (3 chars: 9-11)
						// if there is an effect without param, convert '..' to '00'
						$this->mod['dat'][$row][$channel]['eff'] =
						 preg_replace('/^([0-9A-Z\\#])\.\./', '${1}00',
						 substr($this->seq[$zeile], $pos+$p, $this->env['lenfx']));

					}
				}
				// $row is the index of the output array. If we would use $zeile
				// as its index, every empty or invalid entry of the input array
				// would increase the counter and thus give us "false positives"
				$row++;
			}
		}
	}

	/**++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	* spanTag()
	* returns a <span> tag with style and data
	*/
	private function spanTag($dat, $class) {
		//return "<span class=\"$class\">$dat</span>";
        return "<p-$class>$dat</p-$class>";
	}

	/**++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 * getEffectColor($eff)
	 *
	 * returns the color for a particular effect
	 */
	private function getEffectColor($eff, $background='') {

		$categs=count($this->env['categories']);
		for ($i=0; $i<$categs; $i++)
			if (preg_match("/".substr($eff,0,1)."/",
			  $this->env['fx'][$this->attr['format']][$this->env['categories'][$i]] ) )
				return $this->env['class'][$this->env['categories'][$i]].$background;

		// Couldn't find effect - use default color
		return $this->env['class']['default'].$background;
	}

	/**++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	* convPattern2Html()
	*
	* converts the pattern data to html
	*/
	private function convPattern2Html() {
		$ret=null;

		// use standard highlight, if attribute highlight was not specified
		if ($this->attr['highlight']===null)
			$this->attr['highlight']=$this->env['stdhighlight'];

		// merge data array
		$row_count=count($this->mod['dat']);
		for ($row=0; $row<$row_count; $row++) {

			$channel_count=count($this->mod['dat'][$row]);
			for ($channel=0; $channel<$channel_count; $channel++) {

				// Attribute highlight="X": Highlight every Xth line
				if ($this->attr['highlight']>0) {
					$background = ($row % $this->attr['highlight']==0)
							? ' '.$this->env['class']['highlight']
							: '';
				} else $background = '';

				// some shortcuts
				$note = $this->mod['dat'][$row][$channel]['note'];
				$instr= $this->mod['dat'][$row][$channel]['instr'];
				$vol  = $this->mod['dat'][$row][$channel]['vol'];
				$eff  = $this->mod['dat'][$row][$channel]['eff'];

				# Divider
				$ret.= $this->spanTag($this->env['divider'],
					$this->env['class']['divider'].$background);

				# Note
				$ret.= $this->spanTag($note,
					$this->env['class']['note'].$background);
		
				# Instrument
				$ret.= $this->spanTag($instr,
					(substr($instr,0,1) == '.')
					? $this->env['class']['default'].$background
					: $this->env['class']['instr'].$background);

				# Volume Column
				$ret.= $this->spanTag($vol,
					(substr($vol,0,1) == '.')
					? $this->env['class']['default'].$background
					: $this->getEffectColor($vol, $background) );

				# Effect Column
				$ret.= $this->spanTag($eff,
					(substr($eff,0,1) == '.')
					? $this->env['class']['default'].$background
					: $this->getEffectColor($eff, $background) );
			}
			$ret.="<br />";
		} # for ($row ...
		return $ret;
	}

	/**++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	 * printPattern()
	 */
	private function printPattern() {

		$with_title = (is_null($this->attr['title']) || $this->attr['title']=='') ? false : true;

		# float
		if ($this->attr['float']!==null) {
			if ($this->attr['float']!=$this->env['txt']['left']
			 && $this->attr['float']!=$this->env['txt']['right'])
				$this->attr['float']=null;
			else
				# add fixed prefix "mpt_float_" to class name
				$this->attr['float']=' mpt_float_'.$this->attr['float'];
		}

		# width
		if ($this->attr['width']!==null)
			$width=" style=\"max-width:{$this->attr['width']}px;overflow:auto;\"";
		else
			$width='';

		# frame
		if ($this->attr['css']===null)
			$ret='<div class="mpt_'.$this->env['class']['frame'].$this->attr['float'].'" '.$width.'>';
		else
			$ret='<div class="mpt_'.$this->attr['css'].$this->attr['float'].'"'.$width.'>';

		if ($with_title)
			$ret.='<div class="'.$this->env['class']['title'].'">'.$this->attr['title'].'</div>';

		# add identifier to the top if 'id' is not switched off.
		if (!$this->isSwitchedOff($this->attr['identifier']) )
			$ret.='<span class="'.$this->env['class']['id'].'">'.
				$this->space2Nbsp($this->mod['format']).'</span><br />';

		# add the pattern data itself
		$ret.=$this->convPattern2Html();

		# close div
		$ret.='</div>';
		if (MPT_COMMENT_OUT_PHP_WARNINGS) echo " -->\n"; // comment out php error messages
		return $ret;

	} // private function printPattern()

} // class MPTPatterns
?>
