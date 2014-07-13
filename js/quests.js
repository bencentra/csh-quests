// Variables and such
var svgns = "http://www.w3.org/2000/svg"; // SVG Namespace (in case we need it)
var slices = []; // Array of wheel slice objects
var isSpinning = false; // Is the arrow spinning?
var rotation = 0; // Arrow rotation
var currentSlice = 0; // Current slice the arrow is over
var wheel; // DOM Object for the spinner board
var arrow; // DOM Object for the spinner arrow
var spinButton; // DOM Object for the spin wheel <button>
var questName; // DOM Object for the quest name <span>
var questInfo; // DOM Object for the quest info <p>
var quests = []; // Array of quest data
var numSlices;  // Size of the circle slices

// Basic wheel "slice" object for drawing SVG and storing quest data
function Slice(num, parent, quest) {
	// Set instance vars
	this.parent = parent;
	this.size = 360/numSlices;
	this.offset = num * this.size;
	this.id = "slice_"+num;
	this.quest = quest;
	// Draw the object
	this.object = this.create();
	this.parent.appendChild(this.object);
}

Slice.prototype = {
	create:function() {
		// Create a group to store the slice in
		var g = document.createElementNS(svgns, "g");
		// Create the slice object
		var slice = document.createElementNS(svgns, "path");
		slice.setAttributeNS(null, "id", this.id);
		var x1 = 200 + 180 * Math.cos(Math.PI*(-90+this.offset)/180);
		var y1 = 200 + 180 * Math.sin(Math.PI*(-90+this.offset)/180);
		var x2 = 200 + 180 * Math.cos(Math.PI*(-90+this.size+this.offset)/180);
		var y2 = 200 + 180 * Math.sin(Math.PI*(-90+this.size+this.offset)/180);
		slice.setAttributeNS(null, "d", "M 200 200 L "+x1+" "+y1+" A 180 180 0 0 1 "+x2+" "+y2+"  Z");
		// Randomize the color of the slice and finish styling
		var red = Math.floor(Math.random() * 215) + 20;
		var green = Math.floor(Math.random() * 215) + 20;
		var blue = Math.floor(Math.random() * 215) + 20;
		slice.setAttributeNS(null, "fill", "rgb("+red+","+green+","+blue+")");
		slice.setAttributeNS(null, "stroke", "#222222");
		slice.setAttributeNS(null, "style", "stroke-width:2px");
		// Add the slice to the group
		g.appendChild(slice);
		// Create the highlight for the slice
		var overlay = document.createElementNS(svgns, "path");
		overlay.setAttributeNS(null, "d", "M 200 200 L "+x1+" "+y1+" A 180 180 0 0 1 "+x2+" "+y2+"  Z");
		overlay.setAttributeNS(null, "fill", "#FFFFFF");
		overlay.setAttributeNS(null, "stroke", "#222222");
		overlay.setAttributeNS(null, "style", "stroke-width:1px");
		overlay.setAttributeNS(null, "opacity", "0");
		// Add the highlight for the slice to the group
		g.appendChild(overlay);
		return g;
	},
	toggleOverlay:function() {
		var overlay = this.object.childNodes[1];
		if (overlay.getAttribute("opacity") == 0) {
			overlay.setAttributeNS(null, "opacity", 1);
		}
		else {
			overlay.setAttributeNS(null, "opacity", 0);
		}
	}
};

// Toggle the spinning of the quest wheel
function toggleSpinning() {
	// Toggle the spinning animation
	if (isSpinning) {
		// Stop the arrow
		isSpinning = false;
		clearInterval(toggleSpinning.spinInt);
		spinButton.removeAttribute("disabled");
		// Display the current quest
		questName.innerHTML = slices[currentSlice].quest["name"];
		questInfo.innerHTML = slices[currentSlice].quest["info"];
	}
	else {
		// Start spinning the arrow
		isSpinning = true;
		toggleSpinning.spinInt = setInterval(spinWheel, 1000/60);
		// Set how long the wheel will be spinning
		var duration = Math.floor(Math.random() * 2000) + 1000;
		setTimeout(toggleSpinning, duration);
		// Disable the spin button
		spinButton.setAttribute("disabled", "true");
		// Hide the quest info
		questName.innerHTML = "???";
		questInfo.innerHTML = "???";
	}
}

// Animation step for spinning the wheel arrow
function spinWheel() {
	// Rotate the spinner arrow
	rotation = (rotation + 12) % 360;
	arrow.setAttributeNS(null, "transform", "rotate("+rotation+",200,200)");
	// Highlight the slice the arrow is above
	var newSlice = Math.floor(rotation / (360/numSlices));
	if (newSlice != currentSlice) {
		slices[currentSlice].toggleOverlay();
		slices[newSlice].toggleOverlay();
		currentSlice = newSlice;
	}
}

function generateNewQuests() {
	var newQuests = document.getElementById("newQuestCount").value;
	window.location = "http://members.csh.rit.edu/~bencentra/quests/index.php?n="+newQuests;
}