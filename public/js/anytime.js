/*****************************************************************************
 *  FILE:  anytime.js - The Any+Time(TM) JavaScript Library (source)
 *
 *  VERSION: 4.1112K
 *
 *  Copyright 2008-2011 Andrew M. Andrews III (www.AMA3.com). Some Rights
 *  Reserved. This work licensed under the Creative Commons Attribution-
 *  Noncommercial-Share Alike 3.0 Unported License except in jurisdicitons
 *  for which the license has been ported by Creative Commons International,
 *  where the work is licensed under the applicable ported license instead.
 *  For a copy of the unported license, visit
 *  http://creativecommons.org/licenses/by-nc-sa/3.0/
 *  or send a letter to Creative Commons, 171 Second Street, Suite 300,
 *  San Francisco, California, 94105, USA.  For ported versions of the
 *  license, visit http://creativecommons.org/international/
 *
 *  Alternative licensing arrangements may be made by contacting the
 *  author at http://www.AMA3.com/contact/
 *
 *  The Any+Time(TM) JavaScript Library provides the following ECMAScript
 *  functionality:
 *
 *    AnyTime.Converter
 *      Converts Dates to/from Strings, allowing a wide range of formats
 *      closely matching those provided by the MySQL DATE_FORMAT() function,
 *      with some noteworthy enhancements.
 *
 *    AnyTime.pad()
 *      Pads a value with a specific number of leading zeroes.
 *
 *    AnyTime.noPicker()
 *      Destroys a calendar widget previously added by AnyTime.picker().
 *      Can also be invoked via jQuery using $(selector).AnyTime_noPicker()
 *
 *    AnyTime.picker()
 *      Attaches a calendar widget to a text field for selecting date/time
 *      values with fewer mouse movements than most similar pickers.  Any
 *      format supported by AnyTime.Converter can be used for the text field.
 *      If JavaScript is disabled, the text field remains editable without
 *      any of the picker features.
 *      Can also be invoked via jQuery using $(selector).AnyTime_picker()
 *
 *  IMPORTANT NOTICE:  This code depends upon the jQuery JavaScript Library
 *  (www.jquery.com), currently version 1.4.
 *
 *  The Any+Time(TM) code and styles in anytime.css have been tested (but not
 *  extensively) on Windows Vista in Internet Explorer 8.0, Firefox 3.0, Opera
 *  10.10 and Safari 4.0.  Minor variations in IE6+7 are to be expected, due
 *  to their broken box model. Please report any other problems to the author
 *  (URL above).
 *
 *  Any+Time is a trademark of Andrew M. Andrews III.
 *  Thanks to Chu for help with a setMonth() issue!
 ****************************************************************************/

var AnyTime =
{
  	//=============================================================================
  	//  AnyTime.pad() pads a value with a specified number of zeroes and returns
  	//  a string containing the padded value.
  	//=============================================================================

  	pad: function( val, len )
  	{
  		var str = String(Math.abs(val));
  		while ( str.length < len )
  		str = '0'+str;
  		if ( val < 0 )
  		str = '-'+str;
  		return str;
  	}
};

(function($)
{
	// private members

	var __oneDay = (24*60*60*1000);
	var __daysIn = [ 31,28,31,30,31,30,31,31,30,31,30,31 ];
	var __iframe = null;
	var __initialized = false;
	var __msie6 = ( navigator.userAgent.indexOf('MSIE 6') > 0 );
	var __msie7 = ( navigator.userAgent.indexOf('MSIE 7') > 0 );
  	var __pickers = [];

  	//  Add methods to jQuery to create and destroy pickers using
  	//  the typical jQuery approach.

  	jQuery.prototype.AnyTime_picker = function( options )
  	{
  		return this.each( function(i) { AnyTime.picker( this.id, options ); } );
  	}

  	jQuery.prototype.AnyTime_noPicker = function()
  	{
  		return this.each( function(i) { AnyTime.noPicker( this.id ); } );
  	}

  	//  Add methods to jQuery to change the earliest and latest times using
  	//  the typical jQuery approach.

  	jQuery.prototype.AnyTime_setEarliest = function( options )
    {
  		return this.each( function(i) { AnyTime.setEarliest( this.id, options ); } );
    }

  	jQuery.prototype.AnyTime_setLatest = function( options )
    {
  		return this.each( function(i) { AnyTime.setLatest( this.id, options ); } );
    }

  	//	Add special methods to jQuery to compute the height and width
	//	of picker components differently for Internet Explorer 6.x
	//  This prevents the pickers from being too tall and wide.

  	jQuery.prototype.AnyTime_height = function(inclusive)
  	{
  		return ( __msie6 ?
  					Number(this.css('height').replace(/[^0-9]/g,'')) :
  					this.outerHeight(inclusive) );
  	};

  	jQuery.prototype.AnyTime_width = function(inclusive)
  	{
  		return ( __msie6 ?
  					(1+Number(this.css('width').replace(/[^0-9]/g,''))) :
  					this.outerWidth(inclusive) );
  	};


  	// 	Add a method to jQuery to change the classes of an element to
  	//  indicate whether it's value is current (used by AnyTime.picker),
  	//  and another to trigger the click handler for the currently-
  	//  selected button under an element.

  	jQuery.prototype.AnyTime_current = function(isCurrent,isLegal)
	{
	    if ( isCurrent )
	    {
		  this.removeClass('AnyTime-out-btn ui-state-default ui-state-disabled ui-state-highlight');
	      this.addClass('AnyTime-cur-btn ui-state-default ui-state-highlight');
	    }
	    else
	    {
	      this.removeClass('AnyTime-cur-btn ui-state-highlight');
	      if ( ! isLegal )
	    	  this.addClass('AnyTime-out-btn ui-state-disabled');
	      else
	    	  this.removeClass('AnyTime-out-btn ui-state-disabled');
	    }
	};

	jQuery.prototype.AnyTime_clickCurrent = function()
	{
		this.find('.AnyTime-cur-btn').triggerHandler('click');
	}

  	$(document).ready(
  		function()
		{
			//  IE6 doesn't float popups over <select> elements unless an
			//	<iframe> is inserted between them!  The <iframe> is added to
			//	the page *before* the popups are moved, so they will appear
			//  after the <iframe>.

			if ( __msie6 )
			{
				__iframe = $('<iframe frameborder="0" scrolling="no"></iframe>');
				__iframe.src = "javascript:'<html></html>';";
				$(__iframe).css( {
					display: 'block',
					height: '1px',
					left: '0',
					top: '0',
					width: '1px',
					zIndex: 0
					} );
				$(document.body).append(__iframe);
			}

			//  Move popup windows to the end of the page.  This allows them to
			//  overcome XHTML restrictions on <table> placement enforced by MSIE.

			for ( var id in __pickers )
        if ( ! Array.prototype[id] ) // prototype.js compatibility issue
			    __pickers[id].onReady();

			__initialized = true;

		} ); // document.ready

//=============================================================================
//  AnyTime.Converter
//
//  This object converts between Date objects and Strings.
//
//  To use AnyTime.Converter, simply create an instance for a format string,
//  and then (repeatedly) invoke the format() and/or parse() methods to
//  perform the conversions.  For example:
//
//    var converter = new AnyTime.Converter({format:'%Y-%m-%d'})
//    var datetime = converter.parse('1967-07-30') // July 30, 1967 @ 00:00
//    alert( converter.format(datetime) ); // outputs: 1967-07-30
//
//  Constructor parameter:
//
//  options - an object of optional parameters that override default behaviors.
//    The supported options are:
//
//    baseYear - the number to add to two-digit years if the %y format
//      specifier is used.  By default, AnyTime.Converter follows the
//      MySQL assumption that two-digit years are in the range 1970 to 2069
//      (see http://dev.mysql.com/doc/refman/5.1/en/y2k-issues.html).
//      The most common alternatives for baseYear are 1900 and 2000.
//
//    dayAbbreviations - an array of seven strings, indexed 0-6, to be used
//      as ABBREVIATED day names.  If not specified, the following are used:
//      ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']
//      Note that if the firstDOW option is passed to AnyTime.picker() (see
//      AnyTime.picker()), this array should nonetheless begin with the
//      desired abbreviation for Sunday.
//
//    dayNames - an array of seven strings, indexed 0-6, to be used as
//      day names.  If not specified, the following are used: ['Sunday',
//        'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']
//      Note that if the firstDOW option is passed to AnyTime.picker() (see
//      AnyTime.picker()), this array should nonetheless begin with the
//      desired name for Sunday.
//
//    eraAbbreviations - an array of two strings, indexed 0-1, to be used
//      as ABBREVIATED era names.  Item #0 is the abbreviation for "Before
//      Common Era" (years before 0001, sometimes represented as negative
//      years or "B.C"), while item #1 is the abbreviation for "Common Era"
//      (years from 0001 to present, usually represented as unsigned years
//      or years "A.D.").  If not specified, the following are used:
//      ['BCE','CE']
//
//    format - a string specifying the pattern of strings involved in the
//      conversion.  The parse() method can take a string in this format and
//      convert it to a Date, and the format() method can take a Date object
//      and convert it to a string matching the format.
//
//      Fields in the format string must match those for the DATE_FORMAT()
//      function in MySQL, as defined here:
//      http://tinyurl.com/bwd45#function_date-format
//
//      IMPORTANT:  Some MySQL specifiers are not supported (especially
//      those involving day-of-the-year, week-of-the-year) or approximated.
//      See the code for exact behavior.
//
//      In addition to the MySQL format specifiers, the following custom
//      specifiers are also supported:
//
//        %B - If the year is before 0001, then the "Before Common Era"
//          abbreviation (usually BCE or the obsolete BC) will go here.
//
//        %C - If the year is 0001 or later, then the "Common Era"
//          abbreviation (usually CE or the obsolete AD) will go here.
//
//        %E - If the year is before 0001, then the "Before Common Era"
//          abbreviation (usually BCE or the obsolete BC) will go here.
//          Otherwise, the "Common Era" abbreviation (usually CE or the
//          obsolete AD) will go here.
//
//        %Z - The current four-digit year, without any sign.  This is
//          commonly used with years that might be before (or after) 0001,
//          when the %E (or %B and %C) specifier is used instead of a sign.
//          For example, 45 BCE is represented "0045".  By comparison, in
//          the "%Y" format, 45 BCE is represented "-0045".
//
//        %z - The current year, without any sign, using only the necessary
//          number of digits.  This if the year is commonly used with years
//          that might be before (or after) 0001, when the %E (or %B and %C)
//          specifier is used instead of a sign.  For example, the year
//          45 BCE is represented as "45", and the year 312 CE as "312".
//
//        %# - the timezone offset, with a sign, in minutes.
//
//        %+ - the timezone offset, with a sign, in hours and minutes, in
//          four-digit, 24-hour format with no delimiter (for example, +0530).
//          To remember the difference between %+ and %-, it might be helpful
//          to remember that %+ might have more characters than %-.
//
//        %: - the timezone offset, with a sign, in hours and minutes, in
//          four-digit, 24-hour format with a colon delimiter (for example,
//          +05:30).  This is similar to the %z format used by Java.
//          To remember the difference between %: and %;, it might be helpful
//          to remember that a colon (:) has a period (.) on the bottom and
//          a semicolon (;) has a comma (,), and in English sentence structure,
//          a period represents a more significant stop than a comma, and
//          %: might be a longer string than %; (I know it's a stretch, but
//          it's easier than looking it up every time)!
//
//        %- - the timezone offset, with a sign, in hours and minutes, in
//          three-or-four-digit, 24-hour format with no delimiter (for
//          example, +530).
//
//        %; - the timezone offset, with a sign, in hours and minutes, in
//          three-or-four-digit, 24-hour format with a colon delimiter
//          (for example, +5:30).
//
//        %@ - the timezone offset label.  By default, this will be the
//          string "UTC" followed by the offset, with a sign, in hours and
//          minutes, in four-digit, 24-hour format with a colon delimiter
//          (for example, UTC+05:30).  However, if Any+Time(TM) has been
//          extended with a member named utcLabel (for example, by the
//          anytimetz.js file), then it is assumed to be an array of arrays,
//          where the primary array is indexed by time zone offsets, and
//          each sub-array contains a potential label for that offset.
//          When parsing with %@, the array is scanned for matches to the
//          input string, and if a match is found, the corresponding UTC
//          offset is used.  When formatting, the array is scanned for a
//          matching offset, and if one is found, the first member of the
//          sub-array is used for output (unless overridden with
//          utcFormatOffsetSubIndex or setUtcFormatOffsetSubIndex()).
//          If the array does not exist, or does not contain a sub-array
//          for the offset, then the default format is used.
//
//    monthAbbreviations - an array of twelve strings, indexed 0-6, to be
//      used as ABBREVIATED month names.  If not specified, the following
//      are used: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep',
//        'Oct','Nov','Dec']
//
//    monthNames - an array of twelve strings, indexed 0-6, to be used as
//      month names.  If not specified, the following are used:
//      ['January','February','March','April','May','June','July',
//        'August','September','October','November','December']
//
//    utcFormatOffsetAlleged - the offset from UTC, in minutes, to claim that
//      a Date object represents during formatting, even though it is formatted
//      using local time. Unlike utcFormatOffsetImposed, which actually
//      converts the Date object to the specified different time zone, this
//      option merely reports the alleged offset when a timezone specifier
//      (%#, %+, %-, %:, %; %@) is encountered in the format string.
//      This primarily exists so AnyTime.picker can edit the time as specified
//      (without conversion to local time) and then convert the edited time to
//      a different time zone (as selected using the picker).  Any initial
//      value specified here can be changed by setUtcFormatOffsetAlleged().
//      If a format offset is alleged, one cannot also be imposed (the imposed
//      offset is ignored).
//
//    utcFormatOffsetImposed - the offset from UTC, in minutes, to specify when
//      formatting a Date object.  By default, a Date is always formatted
//      using the local time zone.
//
//    utcFormatOffsetSubIndex - when extending AnyTime with a utcLabel array
//      (for example, by the anytimetz.js file), the specified sub-index is
//      used to choose the Time Zone label for the UTC offset when formatting
//      a Date object.  This primarily exists so AnyTime.picker can specify
//      the label selected using the picker.  Any initial value specified here
//      can be changed by setUtcFormatOffsetSubIndex().
//
//    utcParseOffsetAssumed - the offset from UTC, in minutes, to assume when
//      parsing a String object.  By default, a Date is always parsed using the
//      local time zone, unless the format string includes a timezone
//      specifier (%#, %+, %-, %:, %; or %@), in which case the timezone
//      specified in the string is used. The Date object created by parsing
//      always represents local time regardless of the input time zone.
//
//    utcParseOffsetCapture - if true, any parsed string is always treated as
//      though it represents local time, and any offset specified by the string
//      (or utcParseOffsetAssume) is captured for return by the
//      getUtcParseOffsetCaptured() method.  If the %@ format specifier is
//      used, the sub-index of any matched label is also captured for return
//      by the getUtcParseOffsetSubIndex() method.  This primarily exists so
//      AnyTime.picker can edit the time as specified (without conversion to
//      local time) and then convert the edited time to a different time zone
//      (as selected using the picker).
//=============================================================================

AnyTime.Converter = function(options)
{
  	// private members

  	var _flen = 0;
	var _longDay = 9;
	var _longMon = 9;
	var _shortDay = 6;
	var _shortMon = 3;
	var _offAl = Number.MIN_VALUE; // format time zone offset alleged
	var _offCap = Number.MIN_VALUE; // parsed time zone offset captured
	var _offF = Number.MIN_VALUE; // format time zone offset imposed
	var _offFSI = (-1); // format time zone label subindex
	var _offP = Number.MIN_VALUE; // parsed time zone offset assumed
	var _offPSI = (-1);        // parsed time zone label subindex captured
	var _captureOffset = false;

	// public members

	this.fmt = '%Y-%m-%d %T';
	this.dAbbr = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
	this.dNames = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
	this.eAbbr = ['BCE','CE'];
	this.mAbbr = [ 'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec' ];
	this.mNames = [ 'January','February','March','April','May','June','July','August','September','October','November','December' ];
	this.baseYear = null;

	//-------------------------------------------------------------------------
	//  AnyTime.Converter.dAt() returns true if the character in str at pos
	//  is a digit.
	//-------------------------------------------------------------------------

	this.dAt = function( str, pos )
	{
	    return ( (str.charCodeAt(pos)>='0'.charCodeAt(0)) &&
	            (str.charCodeAt(pos)<='9'.charCodeAt(0)) );
	};

	//-------------------------------------------------------------------------
	//  AnyTime.Converter.format() returns a String containing the value
	//  of a specified Date object, using the format string passed to
	//  AnyTime.Converter().
	//
	//  Method parameter:
	//
	//    date - the Date object to be converted
	//-------------------------------------------------------------------------

	this.format = function( date )
	{
		var d = new Date(date.getTime());
		if ( ( _offAl == Number.MIN_VALUE ) && ( _offF != Number.MIN_VALUE ) )
		  d.setTime( ( d.getTime() + (d.getTimezoneOffset()*60000) ) + (_offF*60000) );

	    var t;
	    var str = '';
	    for ( var f = 0 ; f < _flen ; f++ )
	    {
	      if ( this.fmt.charAt(f) != '%' )
	        str += this.fmt.charAt(f);
	      else
	      {
	    	var ch = this.fmt.charAt(f+1)
	        switch ( ch )
	        {
	          case 'a': // Abbreviated weekday name (Sun..Sat)
	            str += this.dAbbr[ d.getDay() ];
	            break;
	          case 'B': // BCE string (eAbbr[0], usually BCE or BC, only if appropriate) (NON-MYSQL)
	            if ( d.getFullYear() < 0 )
	              str += this.eAbbr[0];
	            break;
	          case 'b': // Abbreviated month name (Jan..Dec)
	            str += this.mAbbr[ d.getMonth() ];
	            break;
	          case 'C': // CE string (eAbbr[1], usually CE or AD, only if appropriate) (NON-MYSQL)
	            if ( d.getFullYear() > 0 )
	              str += this.eAbbr[1];
	            break;
	          case 'c': // Month, numeric (0..12)
	            str += d.getMonth()+1;
	            break;
	          case 'd': // Day of the month, numeric (00..31)
	            t = d.getDate();
	            if ( t < 10 ) str += '0';
	            str += String(t);
	            break;
	          case 'D': // Day of the month with English suffix (0th, 1st,...)
	            t = String(d.getDate());
	            str += t;
	            if ( ( t.length == 2 ) && ( t.charAt(0) == '1' ) )
	              str += 'th';
	            else
	            {
	              switch ( t.charAt( t.length-1 ) )
	              {
	                case '1': str += 'st'; break;
	                case '2': str += 'nd'; break;
	                case '3': str += 'rd'; break;
	                default: str += 'th'; break;
	              }
	            }
	            break;
	          case 'E': // era string (from eAbbr[], BCE, CE, BC or AD) (NON-MYSQL)
	            str += this.eAbbr[ (d.getFullYear()<0) ? 0 : 1 ];
	            break;
	          case 'e': // Day of the month, numeric (0..31)
	            str += d.getDate();
	            break;
	          case 'H': // Hour (00..23)
	            t = d.getHours();
	            if ( t < 10 ) str += '0';
	            str += String(t);
	            break;
	          case 'h': // Hour (01..12)
	          case 'I': // Hour (01..12)
	            t = d.getHours() % 12;
	            if ( t == 0 )
	              str += '12';
	            else
	            {
	              if ( t < 10 ) str += '0';
	              str += String(t);
	            }
	            break;
	          case 'i': // Minutes, numeric (00..59)
	            t = d.getMinutes();
	            if ( t < 10 ) str += '0';
	            str += String(t);
	            break;
	          case 'k': // Hour (0..23)
	            str += d.getHours();
	            break;
	          case 'l': // Hour (1..12)
	            t = d.getHours() % 12;
	            if ( t == 0 )
	              str += '12';
	            else
	              str += String(t);
	            break;
	          case 'M': // Month name (January..December)
	            str += this.mNames[ d.getMonth() ];
	            break;
	          case 'm': // Month, numeric (00..12)
	            t = d.getMonth() + 1;
	            if ( t < 10 ) str += '0';
	            str += String(t);
	            break;
	          case 'p': // AM or PM
	            str += ( ( d.getHours() < 12 ) ? 'AM' : 'PM' );
	            break;
	          case 'r': // Time, 12-hour (hh:mm:ss followed by AM or PM)
	            t = d.getHours() % 12;
	            if ( t == 0 )
	              str += '12:';
	            else
	            {
	              if ( t < 10 ) str += '0';
	              str += String(t) + ':';
	            }
	            t = d.getMinutes();
	            if ( t < 10 ) str += '0';
	            str += String(t) + ':';
	            t = d.getSeconds();
	            if ( t < 10 ) str += '0';
	            str += String(t);
	            str += ( ( d.getHours() < 12 ) ? 'AM' : 'PM' );
	            break;
	          case 'S': // Seconds (00..59)
	          case 's': // Seconds (00..59)
	            t = d.getSeconds();
	            if ( t < 10 ) str += '0';
	            str += String(t);
	            break;
	          case 'T': // Time, 24-hour (hh:mm:ss)
	            t = d.getHours();
	            if ( t < 10 ) str += '0';
	            str += String(t) + ':';
	            t = d.getMinutes();
	            if ( t < 10 ) str += '0';
	            str += String(t) + ':';
	            t = d.getSeconds();
	            if ( t < 10 ) str += '0';
	            str += String(t);
	            break;
	          case 'W': // Weekday name (Sunday..Saturday)
	            str += this.dNames[ d.getDay() ];
	            break;
	          case 'w': // Day of the week (0=Sunday..6=Saturday)
	            str += d.getDay();
	            break;
	          case 'Y': // Year, numeric, four digits (negative if before 0001)
	            str += AnyTime.pad(d.getFullYear(),4);
	            break;
	          case 'y': // Year, numeric (two digits, negative if before 0001)
	            t = d.getFullYear() % 100;
	            str += AnyTime.pad(t,2);
	            break;
	          case 'Z': // Year, numeric, four digits, unsigned (NON-MYSQL)
	            str += AnyTime.pad(Math.abs(d.getFullYear()),4);
	            break;
	          case 'z': // Year, numeric, variable length, unsigned (NON-MYSQL)
	            str += Math.abs(d.getFullYear());
	            break;
	          case '%': // A literal '%' character
	            str += '%';
	            break;
	          case '#': // signed timezone offset in minutes
	        	t = ( _offAl != Number.MIN_VALUE ) ? _offAl :
	        		( _offF == Number.MIN_VALUE ) ? (0-d.getTimezoneOffset()) : _offF;
	        	if ( t >= 0 )
	        		str += '+';
	        	str += t;
	        	break;
	          case '@': // timezone offset label
		        t = ( _offAl != Number.MIN_VALUE ) ? _offAl :
		        	( _offF == Number.MIN_VALUE ) ? (0-d.getTimezoneOffset()) : _offF;
		        if ( AnyTime.utcLabel && AnyTime.utcLabel[t] )
		        {
		          if ( ( _offFSI > 0 ) && ( _offFSI < AnyTime.utcLabel[t].length ) )
		            str += AnyTime.utcLabel[t][_offFSI];
		          else
		            str += AnyTime.utcLabel[t][0];
		          break;
		        }
		        str += 'UTC';
		        ch = ':'; // drop through for offset formatting
	          case '+': // signed, 4-digit timezone offset in hours and minutes
	          case '-': // signed, 3-or-4-digit timezone offset in hours and minutes
	          case ':': // signed 4-digit timezone offset with colon delimiter
	          case ';': // signed 3-or-4-digit timezone offset with colon delimiter
		        t = ( _offAl != Number.MIN_VALUE ) ? _offAl :
		        		( _offF == Number.MIN_VALUE ) ? (0-d.getTimezoneOffset()) : _offF;
		        if ( t < 0 )
		          str += '-';
		        else
		          str += '+';
		        t = Math.abs(t);
		        str += ((ch=='+')||(ch==':')) ? AnyTime.pad(Math.floor(t/60),2) : Math.floor(t/60);
		        if ( (ch==':') || (ch==';') )
		          str += ':';
		        str += AnyTime.pad(t%60,2);
		        break;
	          case 'f': // Microseconds (000000..999999)
	          case 'j': // Day of year (001..366)
	          case 'U': // Week (00..53), where Sunday is the first day of the week
	          case 'u': // Week (00..53), where Monday is the first day of the week
	          case 'V': // Week (01..53), where Sunday is the first day of the week; used with %X
	          case 'v': // Week (01..53), where Monday is the first day of the week; used with %x
	          case 'X': // Year for the week where Sunday is the first day of the week, numeric, four digits; used with %V
	          case 'x': // Year for the week, where Monday is the first day of the week, numeric, four digits; used with %v
	            throw '%'+ch+' not implemented by AnyTime.Converter';
	          default: // for any character not listed above
	            str += this.fmt.substr(f,2);
	        } // switch ( this.fmt.charAt(f+1) )
	        f++;
	      } // else
	    } // for ( var f = 0 ; f < _flen ; f++ )
	    return str;

	}; // AnyTime.Converter.format()

	//-------------------------------------------------------------------------
	//  AnyTime.Converter.getUtcParseOffsetCaptured() returns the UTC offset
	//  last captured by a parsed string (or assumed by utcParseOffsetAssumed).
	//  It returns Number.MIN_VALUE if this object was not constructed with
	//  the utcParseOffsetCapture option set to true, or if an offset was not
	//  specified by the last parsed string or utcParseOffsetAssumed.
	//-------------------------------------------------------------------------

	this.getUtcParseOffsetCaptured = function()
	{
	    return _offCap;
	};

	//-------------------------------------------------------------------------
	//  AnyTime.Converter.getUtcParseOffsetCaptured() returns the UTC offset
	//  last captured by a parsed string (or assumed by utcParseOffsetAssumed).
	//  It returns Number.MIN_VALUE if this object was not constructed with
	//  the utcParseOffsetCapture option set to true, or if an offset was not
	//  specified by the last parsed string or utcParseOffsetAssumed.
	//-------------------------------------------------------------------------

	this.getUtcParseOffsetSubIndex = function()
	{
	    return _offPSI;
	};

	//-------------------------------------------------------------------------
	//  AnyTime.Converter.parse() returns a Date initialized from a specified
	//  string, using the format passed to AnyTime.Converter().
	//
	//  Method parameter:
	//
	//    str - the String object to be converted
	//-------------------------------------------------------------------------

	this.parse = function( str )
	{
		_offCap = _offP;
		_offPSI = (-1);
	    var era = 1;
      var time = new Date(4,0,1,0,0,0,0);//4=leap year bug
	    var slen = str.length;
	    var s = 0;
	    var tzSign = 1, tzOff = _offP;
	    var i, matched, sub, sublen, temp;
	    for ( var f = 0 ; f < _flen ; f++ )
	    {
	      if ( this.fmt.charAt(f) == '%' )
	      {
			var ch = this.fmt.charAt(f+1);
	        switch ( ch )
	        {
	          case 'a': // Abbreviated weekday name (Sun..Sat)
	            matched = false;
	            for ( sublen = 0 ; s + sublen < slen ; sublen++ )
	            {
	              sub = str.substr(s,sublen);
	              for ( i = 0 ; i < 12 ; i++ )
	                if ( this.dAbbr[i] == sub )
	                {
	                  matched = true;
	                  s += sublen;
	                  break;
	                }
	              if ( matched )
	                break;
	            } // for ( sublen ... )
	            if ( ! matched )
	              throw 'unknown weekday: '+str.substr(s);
	            break;
	          case 'B': // BCE string (eAbbr[0]), only if needed. (NON-MYSQL)
	            sublen = this.eAbbr[0].length;
	            if ( ( s + sublen <= slen ) && ( str.substr(s,sublen) == this.eAbbr[0] ) )
	            {
	              era = (-1);
	              s += sublen;
	            }
	            break;
	          case 'b': // Abbreviated month name (Jan..Dec)
	            matched = false;
	            for ( sublen = 0 ; s + sublen < slen ; sublen++ )
	            {
	              sub = str.substr(s,sublen);
	              for ( i = 0 ; i < 12 ; i++ )
	                if ( this.mAbbr[i] == sub )
	                {
	                  time.setMonth( i );
	                  matched = true;
	                  s += sublen;
	                  break;
	                }
	              if ( matched )
	                break;
	            } // for ( sublen ... )
	            if ( ! matched )
	              throw 'unknown month: '+str.substr(s);
	            break;
	          case 'C': // CE string (eAbbr[1]), only if needed. (NON-MYSQL)
	            sublen = this.eAbbr[1].length;
	            if ( ( s + sublen <= slen ) && ( str.substr(s,sublen) == this.eAbbr[1] ) )
	              s += sublen; // note: CE is the default era
	            break;
	          case 'c': // Month, numeric (0..12)
	            if ( ( s+1 < slen ) && this.dAt(str,s+1) )
	            {
	              time.setMonth( (Number(str.substr(s,2))-1)%12 );
	              s += 2;
	            }
	            else
	            {
	              time.setMonth( (Number(str.substr(s,1))-1)%12 );
	              s++;
	            }
	            break;
	          case 'D': // Day of the month with English suffix (0th,1st,...)
	            if ( ( s+1 < slen ) && this.dAt(str,s+1) )
	            {
	              time.setDate( Number(str.substr(s,2)) );
	              s += 4;
	            }
	            else
	            {
	              time.setDate( Number(str.substr(s,1)) );
	              s += 3;
	            }
	            break;
	          case 'd': // Day of the month, numeric (00..31)
	            time.setDate( Number(str.substr(s,2)) );
	            s += 2;
	            break;
	          case 'E': // era string (from eAbbr[]) (NON-MYSQL)
	            sublen = this.eAbbr[0].length;
	            if ( ( s + sublen <= slen ) && ( str.substr(s,sublen) == this.eAbbr[0] ) )
	            {
	              era = (-1);
	              s += sublen;
	            }
	            else if ( ( s + ( sublen = this.eAbbr[1].length ) <= slen ) && ( str.substr(s,sublen) == this.eAbbr[1] ) )
	              s += sublen; // note: CE is the default era
	            else
	              throw 'unknown era: '+str.substr(s);
	            break;
	          case 'e': // Day of the month, numeric (0..31)
	            if ( ( s+1 < slen ) && this.dAt(str,s+1) )
	            {
	              time.setDate( Number(str.substr(s,2)) );
	              s += 2;
	            }
	            else
	            {
	              time.setDate( Number(str.substr(s,1)) );
	              s++;
	            }
	            break;
	          case 'f': // Microseconds (000000..999999)
	            s += 6; // SKIPPED!
	            break;
	          case 'H': // Hour (00..23)
	            time.setHours( Number(str.substr(s,2)) );
	            s += 2;
	            break;
	          case 'h': // Hour (01..12)
	          case 'I': // Hour (01..12)
	            time.setHours( Number(str.substr(s,2)) );
	            s += 2;
	            break;
	          case 'i': // Minutes, numeric (00..59)
	            time.setMinutes( Number(str.substr(s,2)) );
	            s += 2;
	            break;
	          case 'k': // Hour (0..23)
	            if ( ( s+1 < slen ) && this.dAt(str,s+1) )
	            {
	              time.setHours( Number(str.substr(s,2)) );
	              s += 2;
	            }
	            else
	            {
	              time.setHours( Number(str.substr(s,1)) );
	              s++;
	            }
	            break;
	          case 'l': // Hour (1..12)
	            if ( ( s+1 < slen ) && this.dAt(str,s+1) )
	            {
	              time.setHours( Number(str.substr(s,2)) );
	              s += 2;
	            }
	            else
	            {
	              time.setHours( Number(str.substr(s,1)) );
	              s++;
	            }
	            break;
	          case 'M': // Month name (January..December)
	            matched = false;
	            for (sublen=_shortMon ; s + sublen <= slen ; sublen++ )
	            {
	              if ( sublen > _longMon )
	                break;
	              sub = str.substr(s,sublen);
	              for ( i = 0 ; i < 12 ; i++ )
	              {
	                if ( this.mNames[i] == sub )
	                {
	                  time.setMonth( i );
	                  matched = true;
	                  s += sublen;
	                  break;
	                }
	              }
	              if ( matched )
	                break;
	            }
	            break;
	          case 'm': // Month, numeric (00..12)
	            time.setMonth( (Number(str.substr(s,2))-1)%12 );
	            s += 2;
	            break;
	          case 'p': // AM or PM
              if ( time.getHours() == 12 )
              {
	              if ( str.charAt(s) == 'A' )
	                time.setHours(0);
              }
              else if ( str.charAt(s) == 'P' )
	              time.setHours( time.getHours() + 12 );
	            s += 2;
	            break;
	          case 'r': // Time, 12-hour (hh:mm:ss followed by AM or PM)
	            time.setHours(Number(str.substr(s,2)));
	            time.setMinutes(Number(str.substr(s+3,2)));
	            time.setSeconds(Number(str.substr(s+6,2)));
              if ( time.getHours() == 12 )
              {
	              if ( str.charAt(s) == 'A' )
	                time.setHours(0);
              }
              else if ( str.charAt(s) == 'P' )
	              time.setHours( time.getHours() + 12 );
	            s += 10;
	            break;
	          case 'S': // Seconds (00..59)
	          case 's': // Seconds (00..59)
	            time.setSeconds(Number(str.substr(s,2)));
	            s += 2;
	            break;
	          case 'T': // Time, 24-hour (hh:mm:ss)
	            time.setHours(Number(str.substr(s,2)));
	            time.setMinutes(Number(str.substr(s+3,2)));
	            time.setSeconds(Number(str.substr(s+6,2)));
	            s += 8;
	            break;
	          case 'W': // Weekday name (Sunday..Saturday)
	            matched = false;
	            for (sublen=_shortDay ; s + sublen <= slen ; sublen++ )
	            {
	              if ( sublen > _longDay )
	                break;
	              sub = str.substr(s,sublen);
	              for ( i = 0 ; i < 7 ; i++ )
	              {
	                if ( this.dNames[i] == sub )
	                {
	                  matched = true;
	                  s += sublen;
	                  break;
	                }
	              }
	              if ( matched )
	                break;
	            }
	            break;
            case 'w': // Day of the week (0=Sunday..6=Saturday) (ignored)
              s += 1;
              break;
	          case 'Y': // Year, numeric, four digits, negative if before 0001
	            i = 4;
	            if ( str.substr(s,1) == '-' )
	              i++;
	            time.setFullYear(Number(str.substr(s,i)));
	            s += i;
	            break;
	          case 'y': // Year, numeric (two digits), negative before baseYear
	            i = 2;
	            if ( str.substr(s,1) == '-' )
	              i++;
	            temp = Number(str.substr(s,i));
	            if ( typeof(this.baseYear) == 'number' )
	            	temp += this.baseYear;
	            else if ( temp < 70 )
	            	temp += 2000;
	            else
	            	temp += 1900;
	            time.setFullYear(temp);
	            s += i;
	            break;
	          case 'Z': // Year, numeric, four digits, unsigned (NON-MYSQL)
	            time.setFullYear(Number(str.substr(s,4)));
	            s += 4;
	            break;
	          case 'z': // Year, numeric, variable length, unsigned (NON-MYSQL)
	            i = 0;
	            while ( ( s < slen ) && this.dAt(str,s) )
	              i = ( i * 10 ) + Number(str.charAt(s++));
	            time.setFullYear(i);
	            break;
	          case '#': // signed timezone offset in minutes.
	            if ( str.charAt(s++) == '-' )
	            	tzSign = (-1);
	            for ( tzOff = 0 ; ( s < slen ) && (String(i=Number(str.charAt(s)))==str.charAt(s)) ; s++ )
	            	tzOff = ( tzOff * 10 ) + i;
	            tzOff *= tzSign;
	            break;
	          case '@': // timezone label
	        	_offPSI = (-1);
	        	if ( AnyTime.utcLabel )
	        	{
		            matched = false;
		            for ( tzOff in AnyTime.utcLabel )
                  if ( ! Array.prototype[tzOff] ) // prototype.js compatibility issue
		              {
		            	  for ( i = 0 ; i < AnyTime.utcLabel[tzOff].length ; i++ )
  		            	{
	  	            		sub = AnyTime.utcLabel[tzOff][i];
		              		sublen = sub.length;
		              		if ( ( s+sublen <= slen ) && ( str.substr(s,sublen) == sub ) )
		              		{
                        s+=sublen;
		              			matched = true;
		              			break;
		              		}
		              	}
	            		  if ( matched )
	            			  break;
		              }
		            if ( matched )
		            {
		            	_offPSI = i;
		            	tzOff = Number(tzOff);
		            	break; // case
		            }
	        	}
	        	if ( ( s+9 < slen ) || ( str.substr(s,3) != "UTC" ) )
                    throw 'unknown time zone: '+str.substr(s);
	        	s += 3;
	            ch = ':'; // drop through for offset parsing
	          case '-': // signed, 3-or-4-digit timezone offset in hours and minutes
	          case '+': // signed, 4-digit timezone offset in hours and minutes
	          case ':': // signed 4-digit timezone offset with colon delimiter
	          case ';': // signed 3-or-4-digit timezone offset with colon delimiter
	            if ( str.charAt(s++) == '-' )
	            	tzSign = (-1);
	            tzOff = Number(str.charAt(s));
	            if ( (ch=='+')||(ch==':')||((s+3<slen)&&(String(Number(str.charAt(s+3)))!==str.charAt(s+3))) )
	            	tzOff = (tzOff*10) + Number(str.charAt(++s));
                tzOff *= 60;
	        	if ( (ch==':') || (ch==';') )
	        		s++; // skip ":" (assumed)
	        	tzOff = ( tzOff + Number(str.substr(++s,2)) ) * tzSign;
	        	s += 2;
		        break;
	          case 'j': // Day of year (001..366)
	          case 'U': // Week (00..53), where Sunday is the first day of the week
	          case 'u': // Week (00..53), where Monday is the first day of the week
	          case 'V': // Week (01..53), where Sunday is the first day of the week; used with %X
	          case 'v': // Week (01..53), where Monday is the first day of the week; used with %x
	          case 'X': // Year for the week where Sunday is the first day of the week, numeric, four digits; used with %V
	          case 'x': // Year for the week, where Monday is the first day of the week, numeric, four digits; used with %v
	            throw '%'+this.fmt.charAt(f+1)+' not implemented by AnyTime.Converter';
	          case '%': // A literal '%' character
	          default: // for any character not listed above
		        throw '%'+this.fmt.charAt(f+1)+' reserved for future use';
	            break;
	        }
	        f++;
	      } // if ( this.fmt.charAt(f) == '%' )
	      else if ( this.fmt.charAt(f) != str.charAt(s) )
	        throw str + ' is not in "' + this.fmt + '" format';
	      else
	        s++;
	    } // for ( var f ... )
	    if ( era < 0 )
	      time.setFullYear( 0 - time.getFullYear() );
		if ( tzOff != Number.MIN_VALUE )
		{
	       if ( _captureOffset )
	    	 _offCap = tzOff;
	       else
	    	 time.setTime( ( time.getTime() - (tzOff*60000) ) - (time.getTimezoneOffset()*60000) );
		}

	    return time;

	}; // AnyTime.Converter.parse()

	//-------------------------------------------------------------------------
	//  AnyTime.Converter.setUtcFormatOffsetAlleged()  sets the offset from
    //  UTC, in minutes, to claim that a Date object represents during
	//  formatting, even though it is formatted using local time.  This merely
	//  reports the alleged offset when a timezone specifier (%#, %+, %-, %:,
	//  %; or %@) is encountered in the format string--it does not otherwise
	//  affect the date/time value.  This primarily exists so AnyTime.picker
	//  can edit the time as specified (without conversion to local time) and
	//  then convert the edited time to a different time zone (as selected
	//  using the picker).  This method returns the previous value, if any,
	//  set by the utcFormatOffsetAlleged option, or a previous call to
	//  setUtcFormatOffsetAlleged(), or Number.MIN_VALUE if no offset was
	//  previously-alleged.  Call this method with Number.MIN_VALUE to cancel
	//  any prior value.  Note that if a format offset is alleged, any offset
	//  specified by option utcFormatOffsetImposed is ignored.
	//-------------------------------------------------------------------------

	this.setUtcFormatOffsetAlleged = function( offset )
	{
		var prev = _offAl;
	    _offAl = offset;
	    return prev;
	};

	//-------------------------------------------------------------------------
	//  AnyTime.Converter.setUtcFormatOffsetSubIndex() sets the sub-index
	//  to choose from the AnyTime.utcLabel array of arrays when formatting
	//  a Date using the %@ specifier.  For more information, see option
	//  AnyTime.Converter.utcFormatOffsetSubIndex.  This primarily exists so
	//  AnyTime.picker can specify the Time Zone label selected using the
	//  picker).  This method returns the previous value, if any, set by the
	//  utcFormatOffsetSubIndex option, or a previous call to
	//  setUtcFormatOffsetAlleged(), or (-1) if no sub-index was previously-
	//  chosen.  Call this method with (-1) to cancel any prior value.
	//-------------------------------------------------------------------------

	this.setUtcFormatOffsetSubIndex = function( subIndex )
	{
		var prev = _offFSI;
	    _offFSI = subIndex;
	    return prev;
	};

	//-------------------------------------------------------------------------
	//	AnyTime.Converter construction code:
	//-------------------------------------------------------------------------

	(function(_this)
	{
      var i, len;

		options = jQuery.extend(true,{},options||{});

	  	if ( options.baseYear )
			_this.baseYear = Number(options.baseYear);

	  	if ( options.format )
			_this.fmt = options.format;

	  	_flen = _this.fmt.length;

	  	if ( options.dayAbbreviations )
	  		_this.dAbbr = $.makeArray( options.dayAbbreviations );

	  	if ( options.dayNames )
	  	{
	  		_this.dNames = $.makeArray( options.dayNames );
	  		_longDay = 1;
	  		_shortDay = 1000;
	  		for ( i = 0 ; i < 7 ; i++ )
	  		{
          len = _this.dNames[i].length;
				if ( len > _longDay )
					_longDay = len;
				if ( len < _shortDay )
					_shortDay = len;
	  		}
	  	}

	  	if ( options.eraAbbreviations )
	  		_this.eAbbr = $.makeArray(options.eraAbbreviations);

	  	if ( options.monthAbbreviations )
	  		_this.mAbbr = $.makeArray(options.monthAbbreviations);

	  	if ( options.monthNames )
	  	{
	  		_this.mNames = $.makeArray( options.monthNames );
	  		_longMon = 1;
	  		_shortMon = 1000;
	  		for ( i = 0 ; i < 12 ; i++ )
	  		{
	  			len = _this.mNames[i].length;
	  			if ( len > _longMon )
					_longMon = len;
	  			if ( len < _shortMon )
	  				_shortMon = len;
	  		}
	  	}

	  	if ( typeof options.utcFormatOffsetImposed != "undefined" )
	  		_offF = options.utcFormatOffsetImposed;

	  	if ( typeof options.utcParseOffsetAssumed != "undefined" )
	  		_offP = options.utcParseOffsetAssumed;

	  	if ( options.utcParseOffsetCapture )
	  		_captureOffset = true;

	})(this); // AnyTime.Converter construction

}; // AnyTime.Converter =

//=============================================================================
//  AnyTime.noPicker()
//
//  Removes the date/time entry picker attached to a specified text field.
//=============================================================================

AnyTime.noPicker = function( id )
{
	if ( __pickers[id] )
	{
		__pickers[id].cleanup();
		delete __pickers[id];
	}
};

//=============================================================================
//  AnyTime.picker()
//
//  Creates a date/time entry picker attached to a specified text field.
//  Instead of entering a date and/or time into the text field, the user
//  selects legal combinations using the picker, and the field is auto-
//  matically populated.  The picker can be incorporated into the page
//	"inline", or used as a "popup" that appears when the text field is
//  clicked and disappears when the picker is dismissed. Ajax can be used
//  to send the selected value to a server to approve or veto it.
//
//  To create a picker, simply include the necessary files in an HTML page
//  and call the function for each date/time input field.  The following
//  example creates a popup picker for field "foo" using the default
//  format, and a second date-only (no time) inline (always-visible)
//  Ajax-enabled picker for field "bar":
//
//    <link rel="stylesheet" type="text/css" href="anytime.css" />
//    <script type="text/javascript" src="jquery.js"></script>
//    <script type="text/javascript" src="anytime.js"></script>
//    <input type="text" id="foo" tabindex="1" value="1967-07-30 23:45" />
//    <input type="text" id="bar" tabindex="2" value="01/06/90" />
//    <script type="text/javascript">
//      AnyTime.picker( "foo" );
//      AnyTime.picker( "bar", { placement:"inline", format: "%m/%d/%y",
//								ajaxOptions { url: "/some/server/page/" } } );
//    </script>
//
//  The appearance of the picker can be extensively modified using CSS styles.
//  A default appearance can be achieved by the "anytime.css" stylesheet that
//  accompanies this script.  The default style looks better in browsers other
//  than Internet Explorer (before IE8) because older versions of IE do not
//  properly implement the CSS box model standard; however, it is passable in
//  Internet Explorer as well.
//
//  Method parameters:
//
//  id - the "id" attribute of the textfield to associate with the
//    AnyTime.picker object.  The AnyTime.picker will attach itself
//    to the textfield and manage its value.
//
//  options - an object (associative array) of optional parameters that
//    override default behaviors.  The supported options are:
//
//    ajaxOptions - options passed to jQuery's $.ajax() method whenever
//      the user dismisses a popup picker or selects a value in an inline
//      picker.  The input's name (or ID) and value are passed to the
//      server (appended to ajaxOptions.data, if present), and the
//      "success" handler sets the input's value to the responseText.
//      Therefore, the text returned by the server must be valid for the
//      input'sdate/time format, and the server can approve or veto the
//      value chosen by the user. For more information, see:
//      http://docs.jquery.com/Ajax.
//      If ajaxOptions.success is specified, it is used instead of the
//      default "success" behavior.
//
//    askEra - if true, buttons to select the era are shown on the year
//        selector popup, even if format specifier does not include the
//        era.  If false, buttons to select the era are NOT shown, even
//        if the format specifier includes ther era.  Normally, era buttons
//        are only shown if the format string specifies the era.
//
//    askSecond - if false, buttons for number-of-seconds are not shown
//        even if the format includes seconds.  Normally, the buttons
//        are shown if the format string includes seconds.
//
//    earliest - String or Date object representing the earliest date/time
//        that a user can select.  For best results if the field is only
//        used to specify a date, be sure to set the time to 00:00:00.
//        If a String is used, it will be parsed according to the picker's
//        format (see AnyTime.Converter.format()).
//
//    firstDOW - a value from 0 (Sunday) to 6 (Saturday) stating which
//      day should appear at the beginning of the week.  The default is 0
//      (Sunday).  The most common substitution is 1 (Monday).  Note that
//      if custom arrays are specified for AnyTime.Converter's dayAbbreviations
//      and/or dayNames options, they should nonetheless begin with the
//      value for Sunday.
//
//    hideInput - if true, the <input> is "hidden" (the picker appears in
//      its place). This actually sets the border, height, margin, padding
//      and width of the field as small as possivle, so it can still get focus.
//      If you try to hide the field using traditional techniques (such as
//      setting "display:none"), the picker will not behave correctly.
//
//    labelDayOfMonth - the label for the day-of-month "buttons".
//      Can be any HTML!  If not specified, "Day of Month" is assumed.
//
//    labelDismiss - the label for the dismiss "button" (if placement is
//      "popup"). Can be any HTML!  If not specified, "X" is assumed.
//
//    labelHour - the label for the hour "buttons".
//      Can be any HTML!  If not specified, "Hour" is assumed.
//
//    labelMinute - the label for the minute "buttons".
//      Can be any HTML!  If not specified, "Minute" is assumed.
//
//    labelMonth - the label for the month "buttons".
//      Can be any HTML!  If not specified, "Month" is assumed.
//
//    labelTimeZone - the label for the UTC offset (timezone) "buttons".
//      Can be any HTML!  If not specified, "Time Zone" is assumed.
//
//    labelSecond - the label for the second "buttons".
//      Can be any HTML!  If not specified, "Second" is assumed.
//      This option is ignored if askSecond is false!
//
//    labelTitle - the label for the "title bar".  Can be any HTML!
//      If not specified, then whichever of the following is most
//      appropriate is used:  "Select a Date and Time", "Select a Date"
//      or "Select a Time", or no label if only one field is present.
//
//    labelYear - the label for the year "buttons".
//      Can be any HTML!  If not specified, "Year" is assumed.
//
//    latest - String or Date object representing the latest date/time
//        that a user can select.  For best results if the field is only
//        used to specify a date, be sure to set the time to 23:59:59.
//        If a String is used, it will be parsed according to the picker's
//        format (see AnyTime.Converter.format()).
//
//    placement - One of the following strings:
//
//      "popup" = the picker appears above its <input> when the input
//        receives focus, and disappears when it is dismissed.  This is
//        the default behavior.
//
//      "inline" = the picker is placed immediately after the <input>
//        and remains visible at all times.  When choosing this placement,
//        it is best to make the <input> invisible and use only the
//        picker to select dates.  The <input> value can still be used
//        during form submission as it will always reflect the current
//        picker state.
//
//        WARNING: when using "inline" and XHTML and including a day-of-
//        the-month format field, the input may only appear where a <table>
//        element is permitted (for example, NOT within a <p> element).
//        This is because the picker uses a <table> element to arrange
//        the day-of-the-month (calendar) buttons.  Failure to follow this
//        advice may result in an "unknown error" in Internet Explorer.