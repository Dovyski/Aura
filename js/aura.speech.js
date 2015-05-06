/**
 * API para manipulação Aura através.
 */

AURA.speech = new function() {
	var mRecognizing = false;
	var mIgnoreOnEnd;
	var mStartTimestamp;
	var mRecognition;
	var mSelf = this;

	var onStart = function() {
		mRecognizing = true;
		// Speak now.
		$('#microphone-icon').html('<i class="fa fa-circle-o-notch fa-stack-2x fa-spin"></i><i class="fa fa-microphone fa-stack-1x"></i>');
	};

	var onError = function(theEvent) {
		if (theEvent.error == 'no-speech') {
			$('#microphone-icon').html('<i class="fa fa-circle-o fa-stack-2x fa-spin"></i><i class="fa fa-microphone fa-stack-1x"></i>');

			// No speech was detected. You may need to adjust your <a href="//support.google.com/chrome/bin/answer.py?hl=en&amp;answer=1407892">microphone settings</a>
			mIgnoreOnEnd = true;
		}

		if (theEvent.error == 'audio-capture') {
			$('#microphone-icon').html('<i class="fa fa-circle-o fa-stack-2x fa-spin"></i><i class="fa fa-microphone fa-stack-1x"></i>');
			// No microphone was found
			mIgnoreOnEnd = true;
		}

		if (theEvent.error == 'not-allowed') {
			if (theEvent.timeStamp - mStartTimestamp < 100) {
				// Permission to use microphone is blocked. To change, go to chrome://settings/contentExceptions#media-stream
			} else {
				// Permission to use microphone was denied.
			}
			mIgnoreOnEnd = true;
		}
	};

	var onEnd = function() {
		mRecognizing = false;

		if (mIgnoreOnEnd) {
			return;
		}

		$('#microphone-icon').html('<i class="fa fa-circle-o fa-stack-2x fa-spin"></i><i class="fa fa-microphone fa-stack-1x"></i>');
		// Click on the microphone icon and begin speaking for as long as you like.
	};

	var onResult = function(theEvent) {
		var aInterimTranscript = '';

		if (typeof(theEvent.results) == 'undefined') {
			mRecognition.onend = null;
			mRecognition.stop();
			console.error('Upgrade');

			return;
		}
		for (var i = theEvent.resultIndex; i < theEvent.results.length; ++i) {
			if (theEvent.results[i].isFinal) {
				$('#consoleAura').val(theEvent.results[i][0].transcript).attr('class', 'form-control aura-console-recognized');
				AURA.submitOrder(false, AURA.speech.speak);

			} else {
				aInterimTranscript += theEvent.results[i][0].transcript;
				$('#consoleAura').val(aInterimTranscript).attr('class', 'form-control aura-console-recognizing');
			}
		}
	};

	this.init = function() {
		if (!('webkitSpeechRecognition' in window)) {
			console.error('Speech Recognition not supported!');

		} else {
			mSelf.mRecognition = new webkitSpeechRecognition();
			mSelf.mRecognition.continuous = true;
			mSelf.mRecognition.interimResults = true;

			mSelf.mRecognition.onstart = onStart;
			mSelf.mRecognition.onerror = onError;
			mSelf.mRecognition.onend = onEnd;
			mSelf.mRecognition.onresult = onResult;

			mSelf.mRecognition.lang = 'pt-BR';

			$('#listen-button').click(AURA.speech.startListening);
		}
	};

	this.speak = function(theText) {
		var aVoice = document.createElement('audio');
		aVoice.setAttribute('src', 'aura-speak.php?text=' + encodeURIComponent(theText));
		aVoice.play();
	};

	this.startListening = function(theEvent) {
		if (mRecognizing) {
			mSelf.mRecognition.stop();
			return;
		}

		mSelf.mRecognition.start();
		mSelf.mIgnoreOnEnd = false;

		$('#microphone-icon').html('<i class="fa fa-microphone fa-stack-1x"></i><i class="fa fa-ban fa-stack-2x text-danger"></i>');

		// Click the "Allow" button above to enable your microphone.
		mStartTimestamp = theEvent.timeStamp;
	};
};
