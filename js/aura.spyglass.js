

AURA = AURA || {};

AURA.spyglass = new function() {
	var mDeviceHash;
	var mDeviceName;
	var mConfig;
	var mActive;
	var mMouse = {x: 0, y: 0};
	var mInteractions = [];
	var mSelf = this;

	var sendInteractionsToServer = function(theInteractions) {
		$.ajax({
			url:  "./spyglass-api.php",
			data: {
				action: 'save-web-interactions',
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
		if(mActive) {
	    	$('#screenCanvas').attr('src', 'spyglass-api.php?hash=' + mDeviceHash + '&action=feedrand=' + Math.random());
		} else {
			// Connection is not active just yet (client is probably connecting)
			// Let's show a nice loading message
			$('#loading').html('Establishing connection, please wait.' + ' ' + Math.random());

			// Get client's connection info
			$.ajax({
				url:  "./spyglass-api.php",
				data: { action: 'client-connection-info', hash: mDeviceHash},

				success: function(theData){
					var aDelay = theData.current_timestamp - theData.last_timestamp;

					// If the difference between the last package received and the current
					// timestamp is lass than 10, it means the client is active and
					// broadcasting.
					if(aDelay <= 10) {
						mActive = true;
						initInteractionsSaver();
					}
				},
				error: function() {
					// TODO: do something
				}
			});
		}
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

	var initInteractionsSaver = function() {
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

		setInterval(saveInteraction, mConfig.saveInterval);
	};

	var startSpyglassOnDevice = function(theDeviceHash, theDeviceName) {
		// TODO: get all command hard-coded values from config files
		var aCmd = 'call java -jar {@AURA_HOME}spyglass/spyglass.jar http://dev.local.com/ncc.cc.uffs.edu.br/spyglass-api.php ' + theDeviceHash + '  ' + mConfig.refreshInterval;
		AURA.sendCommand('Rode o comando "' + aCmd + '" no computador ' + theDeviceName);
	};

	this.init = function(theDeviceHash, theDeviceName) {
		mDeviceHash = theDeviceHash;
		mDeviceName = theDeviceName;
		mActive = false;

		mConfig = {
			'refreshInterval': 	getURLParamByName('refreshInterval') 	|| 100,
			'saveInterval': 	getURLParamByName('saveInterval') 		|| 1000
		};

		startSpyglassOnDevice(mDeviceHash, mDeviceName);
		setInterval(refreshScreenCanvas, mConfig.refreshInterval);
	};
};
