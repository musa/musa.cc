/*
 * 	Plugin Name: WP-Cirrus
 *	Plugin URI: http://www.ga-ap.de/plugins/wp-cirrus/	
 *	Description: A 3d javascript tagcloud inspired by WP Cumulus
 *	Version: 0.5.3
 *	Author: Christian Kramer & Hendrik Thole
 *	Author URI: http://www.ga-ap.de
 *	
 *	Copyright 2010, Christian Kramer & Hendrik Thole
 *	
 *	This file is part of WP-Cirrus Plugin.
 *	
 *	WP-Cirrus is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *   
 *	WP-Cirrus is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *   
 *	You should have received a copy of the GNU General Public License
 *	along with WP-Cirrus. If not, see <http://www.gnu.org/licenses/>.
 */


// need this to avoid overrides through other onload events
if (window.addEventListener){
  window.addEventListener('load', startRotating, false);
} else if (window.attachEvent){
  window.attachEvent('onload', startRotating);
}


// globals
var cirrOffSetTheta = 0;
var cirrOffSetPhi = 0;
var cirrOffSetPsi = 0;
var cirrContainer;
var ySteps = 0.006;
var xSteps = 0.006;
var cirrOffSetTop = 0;
var cirrOffSetLeft = 0;



// if the window is loaded
function startRotating(){
	// get the main container
	cirrContainer = document.getElementById("cirrusCloudWidget");
	if (document.getElementById("cirrusCloudTagBox")){
		cirrContainer = document.getElementById("cirrusCloudTagBox");
	} 

	if (!cirrContainer){
		return;
	}
	
	if(wpcirrusBackgroundColor){
		cirrContainer.style['backgroundColor'] = wpcirrusBackgroundColor; 
	}
		
		
	// set the main containers positioning to relative
	cirrContainer.style['position'] = 'relative';
	
	//determine correct offsetTop and offsetLeft
	cirrOffSetTop = cirrContainer.offsetTop;
	cirrOffSetLeft = cirrContainer.offsetLeft;
	var cirrObj = cirrContainer.offsetParent;
	while(cirrObj){
		if(cirrObj.nodeName == "BODY"){
			break;
		}
		cirrOffSetTop += cirrObj.offsetTop;
		cirrOffSetLeft += cirrObj.offsetLeft;
		
		cirrObj = cirrObj.offsetParent;
	}
		
	
	// if no radius is given, determine one
	var radius = (parseInt(cirrContainer.style['height'])/3);
	if (typeof wpcirrusRadius != "undefined" && wpcirrusRadius != 0){
		radius = wpcirrusRadius;
	}
	
	// if no refreshrate is given use default
	var refreshRate = 30;
	if (typeof wpcirrusRefreshrate != "undefined" && wpcirrusRefreshrate != 0){
		refreshRate = wpcirrusRefreshrate;
	}

	// get the list items
	cirrListItems = cirrContainer.getElementsByTagName("A");
	var itemLength = cirrListItems.length-1;
	
	// for better positioning its neccessary to know the average height and width
	var heightOfAllItems = 0;
	var widthOfAllItems = 0;
	
	var daten = document.createElement("div");
	cirrContainer.appendChild(daten);
	
	
	// theta -> 0 - pi
	// phi -> 0 - 2pi
	// compute sperical coordinates
	for (var i = 1; i <= cirrListItems.length; i++){
		// need to set all items positioning absolute
		cirrListItems[i-1].style['position'] = 'absolute';
		if(wpcirrusFontColor){
			cirrListItems[i-1].style['color'] = wpcirrusFontColor;
		}
		
		
		cirrListItems[i-1].theta = new Number(Math.acos(-1+(2*i-1)/cirrListItems.length)).toFixed(2);
		cirrListItems[i-1].phi = new Number(Math.sqrt(cirrListItems.length*Math.PI)*cirrListItems[i-1].theta).toFixed(2);
		
			
		cirrListItems[i-1].z = radius * Math.cos(cirrListItems[i-1].theta);
		cirrListItems[i-1].y = radius * Math.sin(cirrListItems[i-1].theta) * Math.cos(cirrListItems[i-1].phi);
		cirrListItems[i-1].x = radius * Math.sin(cirrListItems[i-1].theta) * Math.sin(cirrListItems[i-1].phi);
		
		heightOfAllItems += cirrListItems[i-1].offsetHeight;
		widthOfAllItems += cirrListItems[i-1].offsetWidth;
			
		cirrListItems[i-1].origFontSize = parseInt(cirrListItems[i-1].style.fontSize);
		
	}

	// compute the average offsets
	var offsetTop = parseInt(cirrContainer.style['height'])/2 - heightOfAllItems/cirrListItems.length/2;
	var offsetLeft = parseInt(cirrContainer.style['width'])/2 - widthOfAllItems/cirrListItems.length/2;
	
	// start animating
	window.setInterval(function(){
		
		for(var i = 0; i < cirrListItems.length; i++){
			
			var x, y, z;
			
			// some test of increase performance
			var sinThetacosPsi = Math.sin(cirrOffSetTheta)*Math.cos(cirrOffSetPsi);
			var sinThetasinPsi = Math.sin(cirrOffSetTheta)*Math.sin(cirrOffSetPsi);
			
			// rotatation matrix (LUT's are much slower)
			x = cirrListItems[i].x * Math.cos(cirrOffSetTheta)*Math.cos(cirrOffSetPsi) + cirrListItems[i].y * Math.cos(cirrOffSetTheta)*Math.sin(cirrOffSetPsi) - cirrListItems[i].z * Math.sin(cirrOffSetTheta);  		
			y = cirrListItems[i].x * (Math.sin(cirrOffSetPhi)*sinThetacosPsi-Math.cos(cirrOffSetPhi)*Math.sin(cirrOffSetPsi)) + cirrListItems[i].y * (Math.sin(cirrOffSetPhi)*sinThetasinPsi+Math.cos(cirrOffSetPhi)*Math.cos(cirrOffSetPsi)) + cirrListItems[i].z * Math.sin(cirrOffSetPhi)*Math.cos(cirrOffSetTheta);
			z = cirrListItems[i].x * (Math.cos(cirrOffSetPhi)*sinThetacosPsi+Math.sin(cirrOffSetPhi)*Math.sin(cirrOffSetPsi)) + cirrListItems[i].y * (Math.cos(cirrOffSetPhi)*sinThetasinPsi-Math.sin(cirrOffSetPhi)*Math.cos(cirrOffSetPsi)) + cirrListItems[i].z * Math.cos(cirrOffSetPhi)*Math.cos(cirrOffSetTheta);

			// set coordinates
			cirrListItems[i].style['top'] = z  + offsetTop + "px";
			cirrListItems[i].style['left'] = y + offsetLeft + "px";
			
			// fontsize
			// TODO: need to make this cross browser
			cirrListItems[i].style['fontSize'] = cirrListItems[i].origFontSize + ((x/radius)*3) + "pt";
			
			// opacity
			// TODO: need to make this corss browser
			var opacity = 0.7+x/radius/3;
			cirrListItems[i].style['opacity'] = opacity;
			cirrListItems[i].style['-moz-opacity'] = opacity;
			cirrListItems[i].style['-kthml-opacity'] = opacity;
			cirrListItems[i].style['filter'] = "alpha(opacity=" + (opacity)*100+ ")";
			
			//z-index
			// TODO: need to make this cross browser
			var zIndex = Math.round((((x/radius)*20))+29);
			cirrListItems[i].style['zIndex'] = zIndex;
			cirrListItems[i].zIndex = zIndex;
		
			
		}
		
		
		
		cirrOffSetTheta += ySteps;
		cirrOffSetPsi += xSteps;

		// instead of counting to infty reset the rotation angles
		if(cirrOffSetTheta > 2*Math.PI){
			cirrOffSetTheta = 0;
		}
		if(cirrOffSetPsi > 2*Math.PI){
			cirrOffSetPsi = 0;
		}
		
	}, refreshRate);
}

function calcRotationOffset(cirrClientX, cirrClientY, obj){
	if (!cirrContainer){
		return;
	}
	if (cirrContainer.id != obj.id){
		return;
	}
	
	var scrollTopValue = 0;
	if (document.body.scrollTop != 0){
		scrollTopValue = document.body.scrollTop; 
	}
	if (document.documentElement.scrollTop != 0 && scrollTopValue == 0){
		scrollTopValue = document.documentElement.scrollTop; 
	}
	
	var scrollLeftValue = 0;
	if (document.body.scrollLeft != 0){
		scrollLeftValue = document.body.scrollLeft; 
	}
	if (document.documentElement.scrollLeft != 0){
		scrollLeftValue = document.documentElement.scrollLeft; 
	}	
	
	ySteps = -((((cirrClientY+scrollTopValue)-cirrOffSetTop) / cirrContainer.offsetHeight) * 0.2 - 0.1)/2;
	xSteps = (((cirrClientX+scrollLeftValue)-cirrOffSetLeft) / cirrContainer.offsetWidth * 0.2 - 0.1)/2;
	

}

function resetRotationOffset(){
	ySteps = 0.006;
	xSteps = 0.006;
}



/*
 * tested in:
 * 
 * Safari 4.0.5, 5
 * Firefox 3.6.4, 3.0.19
 * Chrome 5
 * Opera 9.64
 * IE 6, 8 
 */


