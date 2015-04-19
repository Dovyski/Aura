

AURA = AURA || {};

AURA.spyglass = new function() {
	var mDeviceHash;
	var mConfig;
	var mMouse = {x: 0, y: 0};
	var mInteractions = [];
	var mSelf = this;

	var sendInteractionsToServer = function(theInteractions) {
		$.ajax({
			url:  "./admin/aura/brain.php",
			data: {
				method: 'spyglass-save-interactions',
				hash: mDeviceHash,
				interactions: theInteractions
			},
			success: function(data){
				// TODO: do something
			},
			error: function() {
				// TODO: do something
			}
		});
	}

	var refreshScreenCanvas = function() {
	    $('#screenCanvas').attr('src', 'admin.spyglass-feed.php?hash=' + mDeviceHash + '&rand=' + Math.random());
	};

	var saveInteraction = function() {
		var aCurrentActions;

		mInteractions.push('mv:' + mMouse.x + ',' + mMouse.y);

		aCurrentActions = mInteractions.join(';') + ';';

		// Clear the interactions buffer.
		mInteractions.splice(0);

		// Send everything to the server.
		sendInteractionsToServer(aCurrentActions);
		console.log('Save interaction', mDeviceHash, aCurrentActions);
	};

	var getURLParamByName = function(theName) {
        theName = theName.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var aRegex = new RegExp("[\\?&]" + theName + "=([^&#]*)"),
            aResults = aRegex.exec(location.search);

		return aResults === null ? "" : decodeURIComponent(aResults[1].replace(/\+/g, " "));
    };

	this.init = function(theDeviceHash) {
		mDeviceHash = theDeviceHash;

		mConfig = {
			'refreshInterval': 	getURLParamByName('refreshInterval') 	|| 2000,
			'saveInterval': 	getURLParamByName('saveInterval') 		|| 3000
		};

		$('body').mousemove(function(theEvent) {
		    mMouse.x = theEvent.pageX;
		    mMouse.y = theEvent.pageY;
		});

		$('body').mousedown(function(theEvent) {
			mInteractions.push('mv:'+mMouse.x+','+mMouse.y+';mp');
		});

		$('body').mouseup(function(theEvent) {
			mInteractions.push('mv:'+mMouse.x+','+mMouse.y+';mr');
		});

		$('body').keydown(function(theEvent) {
			mInteractions.push('kp:'+theEvent.keyCode);
		});

		$('body').keyup(function(theEvent) {
			mInteractions.push('kr:'+theEvent.keyCode);
		});

		refreshScreenCanvas();

		setInterval(refreshScreenCanvas, mConfig.refreshInterval);
		setInterval(saveInteraction, mConfig.saveInterval);
	};
};
