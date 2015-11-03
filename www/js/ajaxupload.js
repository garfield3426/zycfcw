



(function(){
	
var d = document, w = window;

	
function get(element){
	if (typeof element == "string")
		element = d.getElementById(element);
	return element;
}


function addEvent(el, type, fn){
	if (w.addEventListener){
		el.addEventListener(type, fn, false);
	} else if (w.attachEvent){
		var f = function(){
		  fn.call(el, w.event);
		};			
		el.attachEvent('on' + type, f)
	}
}



var toElement = function(){
	var div = d.createElement('div');
	return function(html){
		div.innerHTML = html;
		var el = div.childNodes[0];
		div.removeChild(el);
		return el;
	}
}();

function hasClass(ele,cls){
	return ele.className.match(new RegExp('(\\s|^)'+cls+'(\\s|$)'));
}
function addClass(ele,cls) {
	if (!hasClass(ele,cls)) ele.className += " "+cls;
}
function removeClass(ele,cls) {
	var reg = new RegExp('(\\s|^)'+cls+'(\\s|$)');
	ele.className=ele.className.replace(reg,' ');
}

// getOffset function copied from jQuery lib (http://jquery.com/)
if (document.documentElement["getBoundingClientRect"]){
	// Get Offset using getBoundingClientRect
	// http://ejohn.org/blog/getboundingclientrect-is-awesome/
	var getOffset = function(el){
		var box = el.getBoundingClientRect(),
		doc = el.ownerDocument,
		body = doc.body,
		docElem = doc.documentElement,
		
		// for ie 
		clientTop = docElem.clientTop || body.clientTop || 0,
		clientLeft = docElem.clientLeft || body.clientLeft || 0,
		
		// In Internet Explorer 7 getBoundingClientRect property is treated as physical,
		// while others are logical. Make all logical, like in IE8.
		
		
		zoom = 1;
		if (body.getBoundingClientRect) {
			var bound = body.getBoundingClientRect();
			zoom = (bound.right - bound.left)/body.clientWidth;
		}
		if (zoom > 1){
			clientTop = 0;
			clientLeft = 0;
		}
		var top = box.top/zoom + (window.pageYOffset || docElem && docElem.scrollTop/zoom || body.scrollTop/zoom) - clientTop,
		left = box.left/zoom + (window.pageXOffset|| docElem && docElem.scrollLeft/zoom || body.scrollLeft/zoom) - clientLeft;
				
		return {
			top: top,
			left: left
		};
	}
	
} else {
	// Get offset adding all offsets 
	var getOffset = function(el){
		if (w.jQuery){
			return jQuery(el).offset();
		}		
			
		var top = 0, left = 0;
		do {
			top += el.offsetTop || 0;
			left += el.offsetLeft || 0;
		}
		while (el = el.offsetParent);
		
		return {
			left: left,
			top: top
		};
	}
}

function getBox(el){
	var left, right, top, bottom;	
	var offset = getOffset(el);
	left = offset.left;
	top = offset.top;
						
	right = left + el.offsetWidth;
	bottom = top + el.offsetHeight;		
		
	return {
		left: left,
		right: right,
		top: top,
		bottom: bottom
	};
}


function getMouseCoords(e){		
	// pageX/Y is not supported in IE
	// http://www.quirksmode.org/dom/w3c_cssom.html			
	if (!e.pageX && e.clientX){
		// In Internet Explorer 7 some properties (mouse coordinates) are treated as physical,
		// while others are logical (offset).
		var zoom = 1;	
		var body = document.body;
		
		if (body.getBoundingClientRect) {
			var bound = body.getBoundingClientRect();
			zoom = (bound.right - bound.left)/body.clientWidth;
		}

		return {
			x: e.clientX / zoom + d.body.scrollLeft + d.documentElement.scrollLeft,
			y: e.clientY / zoom + d.body.scrollTop + d.documentElement.scrollTop
		};
	}
	
	return {
		x: e.pageX,
		y: e.pageY
	};		

}
		
var getUID = function(){
	var id = 0;
	return function(){
		return 'ValumsAjaxUpload' + id++;
	}
}();

function fileFromPath(file){
	return file.replace(/.*(\/|\\)/, "");			
}

function getExt(file){
	return (/[.]/.exec(file)) ? /[^.]+$/.exec(file.toLowerCase()) : '';
}			

// Please use AjaxUpload , Ajax_upload will be removed in the next version
Ajax_upload = AjaxUpload = function(button, options){
	if (button.jquery){
		// jquery object was passed
		button = button[0];
	} else if (typeof button == "string" && /^#.*/.test(button)){					
		button = button.slice(1);				
	}
	button = get(button);	
	
	this._input = null;
	this._button = button;
	this._disabled = false;
	this._submitting = false;
					
	this._settings = {
		// Location of the server-side upload script
		action: 'upload.php',			
		// File upload name
		name: 'userfile',
		// Additional data to send
		data: {},
		// Submit file as soon as it's selected
		autoSubmit: true,
		// The type of data that you're expecting back from the server.
		// Html and xml are detected automatically.
		// Only useful when you are using json data as a response.
		// Set to "json" in that case. 
		responseType: false,
		// When user selects a file, useful with autoSubmit disabled			
		onChange: function(file, extension){},					
		// Callback to fire before file is uploaded
		// You can return false to cancel upload
		onSubmit: function(file, extension){},
		// Fired when file upload is completed
		onComplete: function(file, response) {}
	};

	// Merge the users options with our defaults
	for (var i in options) {
		this._settings[i] = options[i];
	}
	
	this._createInput();
	this._rerouteClicks();
}
			
// assigning methods to our class
AjaxUpload.prototype = {
	setData : function(data){
		this._settings.data = data;
	},
	disable : function(){
		this._disabled = true;
	},
	enable : function(){
		this._disabled = false;
	},
	// use setData instead, set_data will be removed in the next version
	set_data : function(data){
		this.setData(data);
	},
	// removes ajaxupload
	destroy : function(){
		if(this._input){
			if(this._input.parentNode){
				this._input.parentNode.removeChild(this._input);
			}
			this._input = null;
		}
	},				
	
	_createInput : function(){
		var self = this;
		var input = d.createElement("input");
		input.setAttribute('type', 'file');
		input.setAttribute('name', this._settings.name);
		var styles = {
			'position' : 'absolute'
			,'margin': '-5px 0 0 -175px'
			,'padding': 0
			,'width': '220px'
			,'height': '10px'								
			,'opacity': 0
			,'cursor': 'pointer'
			,'display' : 'none'
			,'zIndex' :  2147483583 //Max zIndex supported by Opera 9.0-9.2x 
			// Strange, I expected 2147483647					
		};
		for (var i in styles){
			input.style[i] = styles[i];
		}
		
		// Make sure that element opacity exists
		// (IE uses filter instead)
		if ( ! (input.style.opacity === "0")){
			input.style.filter = "alpha(opacity=0)";
		}					
		d.body.appendChild(input);	
		
		if (window.jQuery && jQuery.ui && jQuery.ui.dialog){
			// make plugin compatible when using with ui-dialogs with modal = true
			jQuery(input)
				.wrap('<div></div>')
				.parent()
				.addClass('ui-dialog-ajaxupload')
				.css({
					'zIndex': styles.zIndex
					,'width': 0
					,'height': 0
					,'padding': 0
					,'margin': 0
				});
		}
		
		addEvent(input, 'change', function(){
			// get filename from input
			var file = fileFromPath(this.value);	
			if(self._settings.onChange.call(self, file, getExt(file)) == false ){
				return;				
			}														
			// Submit form when value is changed
			if (self._settings.autoSubmit){
				self.submit();						
			}						
		});
		
		this._input = input;
	},
	_rerouteClicks : function (){
		var self = this;
	
		// IE displays 'access denied' error when using this method
		// other browsers just ignore click()
		// addEvent(this._button, 'click', function(e){
		//   self._input.click();
		// });				

		var box, over = false;			
		addEvent(self._button, 'mouseover', function(e){
			if (!self._input || over) return;
			over = true;
			box = getBox(self._button);
								
		});
		
		// we can't use mouseout on the button,
		// because invisible input is over it
		addEvent(document, 'mousemove', function(e){
			var input = self._input;
			if (!input || !over) return;
			if (self._disabled){
				removeClass(self._button, 'hover');
				input.style.display = 'none';
				return;
			}	
										
			var c = getMouseCoords(e);

			if ((c.x >= box.left) && (c.x <= box.right) && 
			(c.y >= box.top) && (c.y <= box.bottom)){
			
				input.style.top = c.y + 'px';
				input.style.left = c.x + 'px';
				input.style.display = 'block';
				addClass(self._button, 'hover');				
			} else {		
				// mouse left the button
				over = false;
				input.style.display = 'none';
				removeClass(self._button, 'hover');
			}			
		});			
			
	},
	
	_createIframe : function(){
		// unique name
		// We cannot use getTime, because it sometimes return
		// same value in safari :(
		var id = getUID();
		
		// Remove ie6 "This page contains both secure and nonsecure items" prompt 
		// http://tinyurl.com/77w9wh
		var iframe = toElement('<iframe src="javascript:false;" name="' + id + '" />');
		iframe.id = id;
		iframe.style.display = 'none';
		d.body.appendChild(iframe);			
		return iframe;						
	},
	
	submit : function(){
		var self = this, settings = this._settings;	
					
		if (this._input.value === ''){
			// there is no file
			return;
		}
										
		// get filename from input
		var file = fileFromPath(this._input.value);			

		// execute user event
		if (! (settings.onSubmit.call(this, file, getExt(file)) == false)) {
			// Create new iframe for this submission
			var iframe = this._createIframe();
			
			// Do not submit if user function returns false										
			var form = this._createForm(iframe);
			form.appendChild(this._input);

			form.submit();			
			
			d.body.removeChild(form);				
			form = null;
			this._input = null;
			
			// create new input
			this._createInput();
			
			var toDeleteFlag = false;
			var fired = false;
			
			addEvent(iframe, 'load', function(e){
				if (iframe.src == "about:blank"){						
					// First time around, do not delete.
					if( toDeleteFlag ){
						// Fix busy state in FF3
						setTimeout( function() {
							d.body.removeChild(iframe);
						}, 0);
					}
					return;
				}				
				
				if (fired){
					// Event was already fired
					// fixing Opera 9.64 that do it multiple times
					return;
				}
				
				var doc = iframe.contentDocument ? iframe.contentDocument : frames[iframe.id].document;

				// fixing Opera 9.26
				if (doc.readyState && doc.readyState != 'complete'){
					// Opera fires load event multiple times
					// Even when the DOM is not ready yet
					// this fix should not affect other browsers
					return;
				}
				
				// fixing Opera 9.64
				fired = true;

				if (doc.body && doc.body.innerHTML == "false"){
					// In Opera 9.64 event was fired second time
					// when body.innerHTML changed from false 
					// to server response approx. after 1 sec					
					window.setTimeout(function(){
						onLoad();
					}, 4321);	
				} else {
					onLoad();
				}
				
				function onLoad(){
					var response;
					
					if (doc.XMLDocument){
						// response is a xml document IE property
						response = doc.XMLDocument;
					} else if (doc.body){
						// response is html document or plain text
						response = doc.body.innerHTML;
						if (settings.responseType == 'json'){
							response = window["eval"]("(" + response + ")");
						}
					} else {
						// response is a xml document
						var response = doc;
					}
																			
					settings.onComplete.call(self, file, response);
						
					// Reload blank page, so that reloading main page
					// does not re-submit the post. Also, remember to
					// delete the frame
					toDeleteFlag = true;				
					iframe.src = "about:blank"; //load event fired	
				}				 								
			});
	
		} else {
			// clear input to allow user to select same file
			this._input.value = '';						
		}
	},		
	
	_createForm : function(iframe){
		var settings = this._settings;
		
		// method, enctype must be specified here
		// because changing this attr on the fly is not allowed in IE 6/7		
		var form = toElement('<form method="post" enctype="multipart/form-data"></form>');
		form.style.display = 'none';
		form.action = settings.action;
		form.target = iframe.name;
		d.body.appendChild(form);
		
		// Create hidden input element for each data key
		for (var prop in settings.data){
			var el = d.createElement("input");
			el.type = 'hidden';
			el.name = prop;
			el.value = settings.data[prop];
			form.appendChild(el);
		}			
		return form;
	}	
};
})();