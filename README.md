# MPTPatterns
This is a MediaWiki extension for beautifying ModPlug/OpenMPT pattern clipboard data for display on MediaWiki pages. It was specially built by [cubaxd](https://cubaxd.net/) for the German OpenMPT Wiki.

# Requirements
This extension has been running on MediaWiki for a long time without any modifications. Tested on MediaWiki up to version 1.35. It just works (TM).
Note: This extension does not play well with the Collection extension - patterns won't be rendered in your exported file.

# Installing
* Create the folder `extensions/MPTPatterns` 
* Place the following files in it: `MPTPatterns.php`, `MPTPatterns.class.php`, `MPTPatterns.settings.php`.
* Copy the contents of `MPTPatterns.css` into the wiki page `MediaWiki:Common.css`.
* Add the following line at the bottom of your `LocalSettings.php`: `require_once("$IP/extensions/MPTPatterns/MPTPatterns.php");`

# Usage
Here's an example for using this extension. There are several attributes (see below) that can be used to change the way the pattern is displayed. The wiki administrator may also edit the defaults directly by modifying the file `MPTPatterns.settings.php`.

```
<pattern highlight="4" float="right" title="Example Module" format="IT" id="on" css="buzz">
|C-604...Q01|A#501...XE0|C-602p48A04|G-508v24T70
|........Q..|...........|...........|...........
|........Q..|...........|C-603p24...|...........
|........Q..|...........|...........|...........
|===........|...........|C-602p08...|D#708v32O10
|...........|...........|...........|...........
|...........|C-501......|C-603p24...|...........
|...........|...........|...........|...........
|...........|A#501...S9F|C-602p48...|A-608......
|...........|...........|...........|...........
|...........|...........|C-603p24...|...........
|...........|...........|...........|...........
</pattern>
```

# Attributes

All values are case-insensitive.

* **id**="*on*|*off*": Enable or disable the ID string (i.e. "ModPlug Tracker S3M" or similar)
* **format**="*IT* (default)|*MPT(M)*|*S3M*|*XM*|*MOD*": Used for choosing the appropriate syntax highlighting if the format string cannot be found
* **highlight**="X": Highlight every X rows
* **width**="X": Override the total width of the parent HTML element.
* **css**="*mpt* (default)|*it*|*buzz*": Choose a colour scheme (these are the four default OpenMPT colour schemes). Own values can be used by adding the respective CSS classes in `MediaWiki:Common.css`.
* **title**="Description of the pattern content"
* **float**="*left*|*right*"

By specifying *id="off"*, the format identification string is not displayed. If this string is missing in the clipboard, OpenMPT assumes that the clipboard content has the same format as the currently edited format.
Hence, *id="off"* should be avoided if you want the pattern to be copy-able.
