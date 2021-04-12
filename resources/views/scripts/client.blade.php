<script type="text/javascript">
    function MediaStreamRecorder(mediaStream) {
        if (!mediaStream)throw"MediaStream is mandatory.";
        this.start = function (timeSlice) {
            var Recorder;
            "undefined" != typeof MediaRecorder ? Recorder = MediaRecorderWrapper : (IsChrome || IsOpera || IsEdge) && (-1 !== this.mimeType.indexOf("video") ? Recorder = WhammyRecorder : -1 !== this.mimeType.indexOf("audio") && (Recorder = StereoAudioRecorder)), "image/gif" === this.mimeType && (Recorder = GifRecorder), ("audio/wav" === this.mimeType || "audio/pcm" === this.mimeType) && (Recorder = StereoAudioRecorder), this.recorderType && (Recorder = this.recorderType), mediaRecorder = new Recorder(mediaStream), mediaRecorder.blobs = [];
            var self = this;
            mediaRecorder.ondataavailable = function (data) {
                mediaRecorder.blobs.push(data), self.ondataavailable(data)
            }, mediaRecorder.onstop = this.onstop, mediaRecorder.onStartedDrawingNonBlankFrames = this.onStartedDrawingNonBlankFrames, mediaRecorder = mergeProps(mediaRecorder, this), mediaRecorder.start(timeSlice)
        }, this.onStartedDrawingNonBlankFrames = function () {
        }, this.clearOldRecordedFrames = function () {
            mediaRecorder && mediaRecorder.clearOldRecordedFrames()
        }, this.stop = function () {
            mediaRecorder && mediaRecorder.stop()
        }, this.ondataavailable = function (blob) {
            console.log("ondataavailable..", blob)
        }, this.onstop = function (error) {
            console.warn("stopped..", error)
        }, this.save = function (file, fileName) {
            if (!file) {
                if (!mediaRecorder)return;
                return void ConcatenateBlobs(mediaRecorder.blobs, mediaRecorder.blobs[0].type, function (concatenatedBlob) {
                    invokeSaveAsDialog(concatenatedBlob)
                })
            }
            invokeSaveAsDialog(file, fileName)
        }, this.pause = function () {
            mediaRecorder && (mediaRecorder.pause(), console.log("Paused recording.", this.mimeType || mediaRecorder.mimeType))
        }, this.resume = function () {
            mediaRecorder && (mediaRecorder.resume(), console.log("Resumed recording.", this.mimeType || mediaRecorder.mimeType))
        }, this.recorderType = null, this.mimeType = "video/webm", this.disableLogs = !1;
        var mediaRecorder
    }
    function MultiStreamRecorder(mediaStream) {
        if (!mediaStream)throw"MediaStream is mandatory.";
        var self = this, isMediaRecorder = isMediaRecorderCompatible();
        this.stream = mediaStream, this.start = function (timeSlice) {
            function fireOnDataAvailableEvent(blobs) {
                recordingInterval++, self.ondataavailable(blobs)
            }

            audioRecorder = new MediaStreamRecorder(mediaStream), videoRecorder = new MediaStreamRecorder(mediaStream), audioRecorder.mimeType = "audio/ogg", videoRecorder.mimeType = "video/webm";
            for (var prop in this)"function" != typeof this[prop] && (audioRecorder[prop] = videoRecorder[prop] = this[prop]);
            audioRecorder.ondataavailable = function (blob) {
                audioVideoBlobs[recordingInterval] || (audioVideoBlobs[recordingInterval] = {}), audioVideoBlobs[recordingInterval].audio = blob, audioVideoBlobs[recordingInterval].video && !audioVideoBlobs[recordingInterval].onDataAvailableEventFired && (audioVideoBlobs[recordingInterval].onDataAvailableEventFired = !0, fireOnDataAvailableEvent(audioVideoBlobs[recordingInterval]))
            }, videoRecorder.ondataavailable = function (blob) {
                return isMediaRecorder ? self.ondataavailable({
                    video: blob,
                    audio: blob
                }) : (audioVideoBlobs[recordingInterval] || (audioVideoBlobs[recordingInterval] = {}), audioVideoBlobs[recordingInterval].video = blob, void(audioVideoBlobs[recordingInterval].audio && !audioVideoBlobs[recordingInterval].onDataAvailableEventFired && (audioVideoBlobs[recordingInterval].onDataAvailableEventFired = !0, fireOnDataAvailableEvent(audioVideoBlobs[recordingInterval]))))
            }, videoRecorder.onstop = audioRecorder.onstop = function (error) {
                self.onstop(error)
            }, isMediaRecorder ? videoRecorder.start(timeSlice) : (videoRecorder.onStartedDrawingNonBlankFrames = function () {
                videoRecorder.clearOldRecordedFrames(), audioRecorder.start(timeSlice)
            }, videoRecorder.start(timeSlice))
        }, this.stop = function () {
            audioRecorder && audioRecorder.stop(), videoRecorder && videoRecorder.stop()
        }, this.ondataavailable = function (blob) {
            console.log("ondataavailable..", blob)
        }, this.onstop = function (error) {
            console.warn("stopped..", error)
        }, this.pause = function () {
            audioRecorder && audioRecorder.pause(), videoRecorder && videoRecorder.pause()
        }, this.resume = function () {
            audioRecorder && audioRecorder.resume(), videoRecorder && videoRecorder.resume()
        };
        var audioRecorder, videoRecorder, audioVideoBlobs = {}, recordingInterval = 0
    }
    function mergeProps(mergein, mergeto) {
        for (var t in mergeto)"function" != typeof mergeto[t] && (mergein[t] = mergeto[t]);
        return mergein
    }
    function dropFirstFrame(arr) {
        return arr.shift(), arr
    }
    function invokeSaveAsDialog(file, fileName) {
        if (!file)throw"Blob object is required.";
        if (!file.type)try {
            file.type = "video/webm"
        } catch (e) {
        }
        var fileExtension = (file.type || "video/webm").split("/")[1];
        if (fileName && -1 !== fileName.indexOf(".")) {
            var splitted = fileName.split(".");
            fileName = splitted[0], fileExtension = splitted[1]
        }
        var fileFullName = (fileName || Math.round(9999999999 * Math.random()) + 888888888) + "." + fileExtension;
        if ("undefined" != typeof navigator.msSaveOrOpenBlob)return navigator.msSaveOrOpenBlob(file, fileFullName);
        if ("undefined" != typeof navigator.msSaveBlob)return navigator.msSaveBlob(file, fileFullName);
        var hyperlink = document.createElement("a");
        hyperlink.href = URL.createObjectURL(file), hyperlink.target = "_blank", hyperlink.download = fileFullName, navigator.mozGetUserMedia && (hyperlink.onclick = function () {
            (document.body || document.documentElement).removeChild(hyperlink)
        }, (document.body || document.documentElement).appendChild(hyperlink));
        var evt = new MouseEvent("click", {view: window, bubbles: !0, cancelable: !0});
        hyperlink.dispatchEvent(evt), navigator.mozGetUserMedia || URL.revokeObjectURL(hyperlink.href)
    }
    function bytesToSize(bytes) {
        var k = 1e3, sizes = ["Bytes", "KB", "MB", "GB", "TB"];
        if (0 === bytes)return "0 Bytes";
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(k)), 10);
        return (bytes / Math.pow(k, i)).toPrecision(3) + " " + sizes[i]
    }
    function isMediaRecorderCompatible() {
        var isOpera = !!window.opera || navigator.userAgent.indexOf(" OPR/") >= 0, isChrome = !!window.chrome && !isOpera, isFirefox = "undefined" != typeof window.InstallTrigger;
        if (isFirefox)return !0;
        if (!isChrome)return !1;
        var verOffset, ix, nAgt = (navigator.appVersion, navigator.userAgent), fullVersion = "" + parseFloat(navigator.appVersion), majorVersion = parseInt(navigator.appVersion, 10);
        return isChrome && (verOffset = nAgt.indexOf("Chrome"), fullVersion = nAgt.substring(verOffset + 7)), -1 !== (ix = fullVersion.indexOf(";")) && (fullVersion = fullVersion.substring(0, ix)), -1 !== (ix = fullVersion.indexOf(" ")) && (fullVersion = fullVersion.substring(0, ix)), majorVersion = parseInt("" + fullVersion, 10), isNaN(majorVersion) && (fullVersion = "" + parseFloat(navigator.appVersion), majorVersion = parseInt(navigator.appVersion, 10)), majorVersion >= 49
    }
    function MediaRecorderWrapper(mediaStream) {
        function isMediaStreamActive() {
            if ("active"in mediaStream) {
                if (!mediaStream.active)return !1
            } else if ("ended"in mediaStream && mediaStream.ended)return !1;
            return !0
        }

        var self = this;
        this.start = function (timeSlice, __disableLogs) {
            if (self.mimeType || (self.mimeType = "video/webm"), -1 !== self.mimeType.indexOf("audio") && mediaStream.getVideoTracks().length && mediaStream.getAudioTracks().length) {
                var stream;
                navigator.mozGetUserMedia ? (stream = new MediaStream, stream.addTrack(mediaStream.getAudioTracks()[0])) : stream = new MediaStream(mediaStream.getAudioTracks()), mediaStream = stream
            }
            -1 !== self.mimeType.indexOf("audio") && (self.mimeType = IsChrome ? "audio/webm" : "audio/ogg"), self.dontFireOnDataAvailableEvent = !1;
            var recorderHints = {mimeType: self.mimeType};
            self.disableLogs || __disableLogs || console.log("Passing following params over MediaRecorder API.", recorderHints), mediaRecorder && (mediaRecorder = null), IsChrome && !isMediaRecorderCompatible() && (recorderHints = "video/vp8");
            try {
                mediaRecorder = new MediaRecorder(mediaStream, recorderHints)
            } catch (e) {
                mediaRecorder = new MediaRecorder(mediaStream)
            }
            "canRecordMimeType"in mediaRecorder && mediaRecorder.canRecordMimeType(self.mimeType) === !1 && (self.disableLogs || console.warn("MediaRecorder API seems unable to record mimeType:", self.mimeType)), mediaRecorder.ignoreMutedMedia = self.ignoreMutedMedia || !1;
            var firedOnDataAvailableOnce = !1;
            mediaRecorder.ondataavailable = function (e) {
                if (!self.dontFireOnDataAvailableEvent && e.data && e.data.size && !(e.data.size < 26800) && !firedOnDataAvailableOnce) {
                    firedOnDataAvailableOnce = !0;
                    var blob = self.getNativeBlob ? e.data : new Blob([e.data], {type: self.mimeType || "video/webm"});
                    self.ondataavailable(blob), self.dontFireOnDataAvailableEvent = !0, mediaRecorder && (mediaRecorder.stop(), mediaRecorder = null), self.start(timeSlice, "__disableLogs")
                }
            }, mediaRecorder.onerror = function (error) {
                self.disableLogs || ("InvalidState" === error.name ? console.error("The MediaRecorder is not in a state in which the proposed operation is allowed to be executed.") : "OutOfMemory" === error.name ? console.error("The UA has exhaused the available memory. User agents SHOULD provide as much additional information as possible in the message attribute.") : "IllegalStreamModification" === error.name ? console.error("A modification to the stream has occurred that makes it impossible to continue recording. An example would be the addition of a Track while recording is occurring. User agents SHOULD provide as much additional information as possible in the message attribute.") : "OtherRecordingError" === error.name ? console.error("Used for an fatal error other than those listed above. User agents SHOULD provide as much additional information as possible in the message attribute.") : "GenericError" === error.name ? console.error("The UA cannot provide the codec or recording option that has been requested.", error) : console.error("MediaRecorder Error", error)), mediaRecorder && "inactive" !== mediaRecorder.state && "stopped" !== mediaRecorder.state && mediaRecorder.stop()
            };
            try {
                mediaRecorder.start(36e5)
            } catch (e) {
                mediaRecorder = null
            }
            setTimeout(function () {
                mediaRecorder && "recording" === mediaRecorder.state && mediaRecorder.requestData()
            }, timeSlice)
        }, this.stop = function (callback) {
            mediaRecorder && "recording" === mediaRecorder.state && (mediaRecorder.requestData(), setTimeout(function () {
                self.dontFireOnDataAvailableEvent = !0, mediaRecorder && "recording" === mediaRecorder.state && mediaRecorder.stop(), mediaRecorder = null
            }, 2e3))
        }, this.pause = function () {
            mediaRecorder && "recording" === mediaRecorder.state && mediaRecorder.pause()
        }, this.ondataavailable = function (blob) {
            console.log("recorded-blob", blob)
        }, this.resume = function () {
            if (this.dontFireOnDataAvailableEvent) {
                this.dontFireOnDataAvailableEvent = !1;
                var disableLogs = self.disableLogs;
                return self.disableLogs = !0, this.record(), void(self.disableLogs = disableLogs)
            }
            mediaRecorder && "paused" === mediaRecorder.state && mediaRecorder.resume()
        }, this.clearRecordedData = function () {
            mediaRecorder && (this.pause(), this.dontFireOnDataAvailableEvent = !0, this.stop())
        };
        var mediaRecorder;
        !function looper() {
            return mediaRecorder ? isMediaStreamActive() === !1 ? void self.stop() : void setTimeout(looper, 1e3) : void 0
        }()
    }
    function StereoAudioRecorder(mediaStream) {
        this.start = function (timeSlice) {
            timeSlice = timeSlice || 1e3, mediaRecorder = new StereoAudioRecorderHelper(mediaStream, this), mediaRecorder.record(), timeout = setInterval(function () {
                mediaRecorder.requestData()
            }, timeSlice)
        }, this.stop = function () {
            mediaRecorder && (mediaRecorder.stop(), clearTimeout(timeout))
        }, this.pause = function () {
            mediaRecorder && mediaRecorder.pause()
        }, this.resume = function () {
            mediaRecorder && mediaRecorder.resume()
        }, this.ondataavailable = function () {
        };
        var mediaRecorder, timeout
    }
    function StereoAudioRecorderHelper(mediaStream, root) {
        function interleave(leftChannel, rightChannel) {
            for (var length = leftChannel.length + rightChannel.length, result = new Float32Array(length), inputIndex = 0, index = 0; length > index;)result[index++] = leftChannel[inputIndex], result[index++] = rightChannel[inputIndex], inputIndex++;
            return result
        }

        function mergeBuffers(channelBuffer, recordingLength) {
            for (var result = new Float32Array(recordingLength), offset = 0, lng = channelBuffer.length, i = 0; lng > i; i++) {
                var buffer = channelBuffer[i];
                result.set(buffer, offset), offset += buffer.length
            }
            return result
        }

        function writeUTFBytes(view, offset, string) {
            for (var lng = string.length, i = 0; lng > i; i++)view.setUint8(offset + i, string.charCodeAt(i))
        }

        function convertoFloat32ToInt16(buffer) {
            for (var l = buffer.length, buf = new Int16Array(l); l--;)buf[l] = 65535 * buffer[l];
            return buf.buffer
        }

        var deviceSampleRate = 44100;
        ObjectStore.AudioContextConstructor || (ObjectStore.AudioContextConstructor = new ObjectStore.AudioContext), deviceSampleRate = ObjectStore.AudioContextConstructor.sampleRate;
        var scriptprocessornode, volume, audioInput, context, leftchannel = [], rightchannel = [], recording = !1, recordingLength = 0, sampleRate = root.sampleRate || deviceSampleRate, mimeType = root.mimeType || "audio/wav", isPCM = mimeType.indexOf("audio/pcm") > -1, numChannels = root.audioChannels || 2;
        this.record = function () {
            recording = !0, leftchannel.length = rightchannel.length = 0, recordingLength = 0
        }, this.requestData = function () {
            if (!isPaused) {
                if (0 === recordingLength)return void(requestDataInvoked = !1);
                requestDataInvoked = !0;
                var internalLeftChannel = leftchannel.slice(0), internalRightChannel = rightchannel.slice(0), internalRecordingLength = recordingLength;
                leftchannel.length = rightchannel.length = [], recordingLength = 0, requestDataInvoked = !1;
                var leftBuffer = mergeBuffers(internalLeftChannel, internalRecordingLength), interleaved = leftBuffer;
                if (2 === numChannels) {
                    var rightBuffer = mergeBuffers(internalRightChannel, internalRecordingLength);
                    interleaved = interleave(leftBuffer, rightBuffer)
                }
                if (isPCM) {
                    var blob = new Blob([convertoFloat32ToInt16(interleaved)], {type: "audio/pcm"});
                    return console.debug("audio recorded blob size:", bytesToSize(blob.size)), void root.ondataavailable(blob)
                }
                var buffer = new ArrayBuffer(44 + 2 * interleaved.length), view = new DataView(buffer);
                writeUTFBytes(view, 0, "RIFF"), view.setUint32(4, 44 + 2 * interleaved.length - 8, !0), writeUTFBytes(view, 8, "WAVE"), writeUTFBytes(view, 12, "fmt "), view.setUint32(16, 16, !0), view.setUint16(20, 1, !0), view.setUint16(22, numChannels, !0), view.setUint32(24, sampleRate, !0), view.setUint32(28, sampleRate * numChannels * 2, !0), view.setUint16(32, 2 * numChannels, !0), view.setUint16(34, 16, !0), writeUTFBytes(view, 36, "data"), view.setUint32(40, 2 * interleaved.length, !0);
                for (var lng = interleaved.length, index = 44, volume = 1, i = 0; lng > i; i++)view.setInt16(index, interleaved[i] * (32767 * volume), !0), index += 2;
                var blob = new Blob([view], {type: "audio/wav"});
                console.debug("audio recorded blob size:", bytesToSize(blob.size)), root.ondataavailable(blob)
            }
        }, this.stop = function () {
            recording = !1, this.requestData(), audioInput.disconnect()
        };
        var context = ObjectStore.AudioContextConstructor;
        ObjectStore.VolumeGainNode = context.createGain();
        var volume = ObjectStore.VolumeGainNode;
        ObjectStore.AudioInput = context.createMediaStreamSource(mediaStream);
        var audioInput = ObjectStore.AudioInput;
        audioInput.connect(volume);
        var bufferSize = root.bufferSize || 2048;
        if (0 === root.bufferSize && (bufferSize = 0), context.createJavaScriptNode)scriptprocessornode = context.createJavaScriptNode(bufferSize, numChannels, numChannels); else {
            if (!context.createScriptProcessor)throw"WebAudio API has no support on this browser.";
            scriptprocessornode = context.createScriptProcessor(bufferSize, numChannels, numChannels)
        }
        bufferSize = scriptprocessornode.bufferSize, console.debug("using audio buffer-size:", bufferSize);
        var requestDataInvoked = !1;
        window.scriptprocessornode = scriptprocessornode, 1 === numChannels && console.debug("All right-channels are skipped.");
        var isPaused = !1;
        this.pause = function () {
            isPaused = !0
        }, this.resume = function () {
            isPaused = !1
        }, scriptprocessornode.onaudioprocess = function (e) {
            if (recording && !requestDataInvoked && !isPaused) {
                var left = e.inputBuffer.getChannelData(0);
                if (leftchannel.push(new Float32Array(left)), 2 === numChannels) {
                    var right = e.inputBuffer.getChannelData(1);
                    rightchannel.push(new Float32Array(right))
                }
                recordingLength += bufferSize
            }
        }, volume.connect(scriptprocessornode), scriptprocessornode.connect(context.destination)
    }
    function WhammyRecorder(mediaStream) {
        this.start = function (timeSlice) {
            timeSlice = timeSlice || 1e3, mediaRecorder = new WhammyRecorderHelper(mediaStream, this);
            for (var prop in this)"function" != typeof this[prop] && (mediaRecorder[prop] = this[prop]);
            mediaRecorder.record(), timeout = setInterval(function () {
                mediaRecorder.requestData()
            }, timeSlice)
        }, this.stop = function () {
            mediaRecorder && (mediaRecorder.stop(), clearTimeout(timeout))
        }, this.clearOldRecordedFrames = function () {
            mediaRecorder && mediaRecorder.clearOldRecordedFrames()
        }, this.pause = function () {
            mediaRecorder && mediaRecorder.pause()
        }, this.resume = function () {
            mediaRecorder && mediaRecorder.resume()
        }, this.ondataavailable = function () {
        };
        var mediaRecorder, timeout
    }
    function WhammyRecorderHelper(mediaStream, root) {
        function drawFrames() {
            if (isPaused)return lastTime = (new Date).getTime(), void setTimeout(drawFrames, 500);
            if (!isStopDrawing) {
                if (requestDataInvoked)return setTimeout(drawFrames, 100);
                var duration = (new Date).getTime() - lastTime;
                if (!duration)return drawFrames();
                lastTime = (new Date).getTime(), !self.isHTMLObject && video.paused && video.play(), context.drawImage(video, 0, 0, canvas.width, canvas.height), isStopDrawing || whammy.frames.push({
                    duration: duration,
                    image: canvas.toDataURL("image/webp")
                }), isOnStartedDrawingNonBlankFramesInvoked || isBlankFrame(whammy.frames[whammy.frames.length - 1]) || (isOnStartedDrawingNonBlankFramesInvoked = !0, root.onStartedDrawingNonBlankFrames()), setTimeout(drawFrames, 10)
            }
        }

        function isBlankFrame(frame, _pixTolerance, _frameTolerance) {
            var localCanvas = document.createElement("canvas");
            localCanvas.width = canvas.width, localCanvas.height = canvas.height;
            var matchPixCount, endPixCheck, maxPixCount, context2d = localCanvas.getContext("2d"), sampleColor = {
                r: 0,
                g: 0,
                b: 0
            }, maxColorDifference = Math.sqrt(Math.pow(255, 2) + Math.pow(255, 2) + Math.pow(255, 2)), pixTolerance = _pixTolerance && _pixTolerance >= 0 && 1 >= _pixTolerance ? _pixTolerance : 0, frameTolerance = _frameTolerance && _frameTolerance >= 0 && 1 >= _frameTolerance ? _frameTolerance : 0, image = new Image;
            image.src = frame.image, context2d.drawImage(image, 0, 0, canvas.width, canvas.height);
            var imageData = context2d.getImageData(0, 0, canvas.width, canvas.height);
            matchPixCount = 0, endPixCheck = imageData.data.length, maxPixCount = imageData.data.length / 4;
            for (var pix = 0; endPixCheck > pix; pix += 4) {
                var currentColor = {
                    r: imageData.data[pix],
                    g: imageData.data[pix + 1],
                    b: imageData.data[pix + 2]
                }, colorDifference = Math.sqrt(Math.pow(currentColor.r - sampleColor.r, 2) + Math.pow(currentColor.g - sampleColor.g, 2) + Math.pow(currentColor.b - sampleColor.b, 2));
                maxColorDifference * pixTolerance >= colorDifference && matchPixCount++
            }
            return maxPixCount * frameTolerance >= maxPixCount - matchPixCount ? !1 : !0
        }

        function dropBlackFrames(_frames, _framesToCheck, _pixTolerance, _frameTolerance) {
            var localCanvas = document.createElement("canvas");
            localCanvas.width = canvas.width, localCanvas.height = canvas.height;
            for (var context2d = localCanvas.getContext("2d"), resultFrames = [], checkUntilNotBlack = -1 === _framesToCheck, endCheckFrame = _framesToCheck && _framesToCheck > 0 && _framesToCheck <= _frames.length ? _framesToCheck : _frames.length, sampleColor = {
                r: 0,
                g: 0,
                b: 0
            }, maxColorDifference = Math.sqrt(Math.pow(255, 2) + Math.pow(255, 2) + Math.pow(255, 2)), pixTolerance = _pixTolerance && _pixTolerance >= 0 && 1 >= _pixTolerance ? _pixTolerance : 0, frameTolerance = _frameTolerance && _frameTolerance >= 0 && 1 >= _frameTolerance ? _frameTolerance : 0, doNotCheckNext = !1, f = 0; endCheckFrame > f; f++) {
                var matchPixCount, endPixCheck, maxPixCount;
                if (!doNotCheckNext) {
                    var image = new Image;
                    image.src = _frames[f].image, context2d.drawImage(image, 0, 0, canvas.width, canvas.height);
                    var imageData = context2d.getImageData(0, 0, canvas.width, canvas.height);
                    matchPixCount = 0, endPixCheck = imageData.data.length, maxPixCount = imageData.data.length / 4;
                    for (var pix = 0; endPixCheck > pix; pix += 4) {
                        var currentColor = {
                            r: imageData.data[pix],
                            g: imageData.data[pix + 1],
                            b: imageData.data[pix + 2]
                        }, colorDifference = Math.sqrt(Math.pow(currentColor.r - sampleColor.r, 2) + Math.pow(currentColor.g - sampleColor.g, 2) + Math.pow(currentColor.b - sampleColor.b, 2));
                        maxColorDifference * pixTolerance >= colorDifference && matchPixCount++
                    }
                }
                !doNotCheckNext && maxPixCount * frameTolerance >= maxPixCount - matchPixCount || (checkUntilNotBlack && (doNotCheckNext = !0), resultFrames.push(_frames[f]))
            }
            return resultFrames = resultFrames.concat(_frames.slice(endCheckFrame)), resultFrames.length <= 0 && resultFrames.push(_frames[_frames.length - 1]), resultFrames
        }

        this.record = function (timeSlice) {
            this.width || (this.width = 320), this.height || (this.height = 240), this.video && this.video instanceof HTMLVideoElement && (this.width || (this.width = video.videoWidth || video.clientWidth || 320), this.height || (this.height = video.videoHeight || video.clientHeight || 240)), this.video || (this.video = {
                width: this.width,
                height: this.height
            }), this.canvas && this.canvas.width && this.canvas.height || (this.canvas = {
                width: this.width,
                height: this.height
            }), canvas.width = this.canvas.width, canvas.height = this.canvas.height, this.video && this.video instanceof HTMLVideoElement ? (this.isHTMLObject = !0, video = this.video.cloneNode()) : (video = document.createElement("video"), video.src = URL.createObjectURL(mediaStream), video.width = this.video.width, video.height = this.video.height), video.muted = !0, video.play(), lastTime = (new Date).getTime(), whammy = new Whammy.Video(root.speed, root.quality), console.log("canvas resolutions", canvas.width, "*", canvas.height), console.log("video width/height", video.width || canvas.width, "*", video.height || canvas.height), drawFrames()
        }, this.clearOldRecordedFrames = function () {
            whammy.frames = []
        };
        var requestDataInvoked = !1;
        this.requestData = function () {
            if (!isPaused) {
                if (!whammy.frames.length)return void(requestDataInvoked = !1);
                requestDataInvoked = !0;
                var internalFrames = whammy.frames.slice(0);
                whammy.frames = dropBlackFrames(internalFrames, -1), whammy.compile(function (whammyBlob) {
                    root.ondataavailable(whammyBlob), console.debug("video recorded blob size:", bytesToSize(whammyBlob.size))
                }), whammy.frames = [], requestDataInvoked = !1
            }
        };
        var isOnStartedDrawingNonBlankFramesInvoked = !1, isStopDrawing = !1;
        this.stop = function () {
            isStopDrawing = !0, this.requestData()
        };
        var video, lastTime, whammy, canvas = document.createElement("canvas"), context = canvas.getContext("2d"), self = this, isPaused = !1;
        this.pause = function () {
            isPaused = !0
        }, this.resume = function () {
            isPaused = !1
        }
    }
    function GifRecorder(mediaStream) {
        function doneRecording() {
            endTime = Date.now();
            var gifBlob = new Blob([new Uint8Array(gifEncoder.stream().bin)], {type: "image/gif"});
            self.ondataavailable(gifBlob), gifEncoder.stream().bin = []
        }

        if ("undefined" == typeof GIFEncoder)throw"Please link: https://cdn.webrtc-experiment.com/gif-recorder.js";
        this.start = function (timeSlice) {
            function drawVideoFrame(time) {
                return isPaused ? void setTimeout(drawVideoFrame, 500, time) : (lastAnimationFrame = requestAnimationFrame(drawVideoFrame), void 0 === typeof lastFrameTime && (lastFrameTime = time), void(90 > time - lastFrameTime || (video.paused && video.play(), context.drawImage(video, 0, 0, imageWidth, imageHeight), gifEncoder.addFrame(context), lastFrameTime = time)))
            }

            timeSlice = timeSlice || 1e3;
            var imageWidth = this.videoWidth || 320, imageHeight = this.videoHeight || 240;
            canvas.width = video.width = imageWidth, canvas.height = video.height = imageHeight, gifEncoder = new GIFEncoder, gifEncoder.setRepeat(0), gifEncoder.setDelay(this.frameRate || this.speed || 200), gifEncoder.setQuality(this.quality || 1), gifEncoder.start(), startTime = Date.now(), lastAnimationFrame = requestAnimationFrame(drawVideoFrame), timeout = setTimeout(doneRecording, timeSlice)
        }, this.stop = function () {
            lastAnimationFrame && (cancelAnimationFrame(lastAnimationFrame), clearTimeout(timeout), doneRecording())
        };
        var isPaused = !1;
        this.pause = function () {
            isPaused = !0
        }, this.resume = function () {
            isPaused = !1
        }, this.ondataavailable = function () {
        }, this.onstop = function () {
        };
        var self = this, canvas = document.createElement("canvas"), context = canvas.getContext("2d"), video = document.createElement("video");
        video.muted = !0, video.autoplay = !0, video.src = URL.createObjectURL(mediaStream), video.play();
        var startTime, endTime, lastFrameTime, gifEncoder, timeout, lastAnimationFrame = null
    }
    "undefined" != typeof MediaStreamRecorder && (MediaStreamRecorder.MultiStreamRecorder = MultiStreamRecorder);
    var browserFakeUserAgent = "Fake/5.0 (FakeOS) AppleWebKit/123 (KHTML, like Gecko) Fake/12.3.4567.89 Fake/123.45";
    !function (that) {
        "undefined" == typeof window && ("undefined" == typeof window && "undefined" != typeof global ? (global.navigator = {
            userAgent: browserFakeUserAgent,
            getUserMedia: function () {
            }
        }, that.window = global) : "undefined" == typeof window, "undefined" == typeof document && (that.document = {}, document.createElement = document.captureStream = document.mozCaptureStream = function () {
            return {}
        }), "undefined" == typeof location && (that.location = {
            protocol: "file:",
            href: "",
            hash: ""
        }), "undefined" == typeof screen && (that.screen = {width: 0, height: 0}))
    }("undefined" != typeof global ? global : window);
    var AudioContext = window.AudioContext;
    "undefined" == typeof AudioContext && ("undefined" != typeof webkitAudioContext && (AudioContext = webkitAudioContext), "undefined" != typeof mozAudioContext && (AudioContext = mozAudioContext)), "undefined" == typeof window && (window = {});
    var AudioContext = window.AudioContext;
    "undefined" == typeof AudioContext && ("undefined" != typeof webkitAudioContext && (AudioContext = webkitAudioContext), "undefined" != typeof mozAudioContext && (AudioContext = mozAudioContext));
    var URL = window.URL;
    "undefined" == typeof URL && "undefined" != typeof webkitURL && (URL = webkitURL), "undefined" != typeof navigator ? ("undefined" != typeof navigator.webkitGetUserMedia && (navigator.getUserMedia = navigator.webkitGetUserMedia), "undefined" != typeof navigator.mozGetUserMedia && (navigator.getUserMedia = navigator.mozGetUserMedia)) : navigator = {
        getUserMedia: function () {
        }, userAgent: browserFakeUserAgent
    };
    var IsEdge = !(-1 === navigator.userAgent.indexOf("Edge") || !navigator.msSaveBlob && !navigator.msSaveOrOpenBlob), IsOpera = !1;
    "undefined" != typeof opera && navigator.userAgent && -1 !== navigator.userAgent.indexOf("OPR/") && (IsOpera = !0);
    var IsChrome = !IsEdge && !IsEdge && !!navigator.webkitGetUserMedia, MediaStream = window.MediaStream;
    "undefined" == typeof MediaStream && "undefined" != typeof webkitMediaStream && (MediaStream = webkitMediaStream), "undefined" != typeof MediaStream && ("getVideoTracks"in MediaStream.prototype || (MediaStream.prototype.getVideoTracks = function () {
        if (!this.getTracks)return [];
        var tracks = [];
        return this.getTracks.forEach(function (track) {
            -1 !== track.kind.toString().indexOf("video") && tracks.push(track)
        }), tracks
    }, MediaStream.prototype.getAudioTracks = function () {
        if (!this.getTracks)return [];
        var tracks = [];
        return this.getTracks.forEach(function (track) {
            -1 !== track.kind.toString().indexOf("audio") && tracks.push(track)
        }), tracks
    }), "stop"in MediaStream.prototype || (MediaStream.prototype.stop = function () {
        this.getAudioTracks().forEach(function (track) {
            track.stop && track.stop()
        }), this.getVideoTracks().forEach(function (track) {
            track.stop && track.stop()
        })
    })), "undefined" != typeof location && 0 === location.href.indexOf("file:") && console.error("Please load this HTML file on HTTP or HTTPS.");
    var ObjectStore = {AudioContext: AudioContext}, ObjectStore = {AudioContext: window.AudioContext || window.webkitAudioContext};
    "undefined" != typeof MediaStreamRecorder && (MediaStreamRecorder.MediaRecorderWrapper = MediaRecorderWrapper), "undefined" != typeof MediaStreamRecorder && (MediaStreamRecorder.StereoAudioRecorder = StereoAudioRecorder), "undefined" != typeof MediaStreamRecorder && (MediaStreamRecorder.StereoAudioRecorderHelper = StereoAudioRecorderHelper), "undefined" != typeof MediaStreamRecorder && (MediaStreamRecorder.WhammyRecorder = WhammyRecorder), "undefined" != typeof MediaStreamRecorder && (MediaStreamRecorder.WhammyRecorderHelper = WhammyRecorderHelper), "undefined" != typeof MediaStreamRecorder && (MediaStreamRecorder.GifRecorder = GifRecorder);
    var Whammy = function () {
        function WhammyVideo(duration, quality) {
            this.frames = [], duration || (duration = 1), this.duration = 1e3 / duration, this.quality = quality || .8
        }

        function processInWebWorker(_function) {
            var blob = URL.createObjectURL(new Blob([_function.toString(), "this.onmessage =  function (e) {" + _function.name + "(e.data);}"], {type: "application/javascript"})), worker = new Worker(blob);
            return URL.revokeObjectURL(blob), worker
        }

        function whammyInWebWorker(frames) {
            function ArrayToWebM(frames) {
                var info = checkFrames(frames);
                if (!info)return [];
                for (var clusterMaxDuration = 3e4, EBML = [{
                    id: 440786851,
                    data: [{data: 1, id: 17030}, {data: 1, id: 17143}, {data: 4, id: 17138}, {
                        data: 8,
                        id: 17139
                    }, {data: "webm", id: 17026}, {data: 2, id: 17031}, {data: 2, id: 17029}]
                }, {
                    id: 408125543,
                    data: [{
                        id: 357149030,
                        data: [{data: 1e6, id: 2807729}, {data: "whammy", id: 19840}, {
                            data: "whammy",
                            id: 22337
                        }, {data: doubleToString(info.duration), id: 17545}]
                    }, {
                        id: 374648427,
                        data: [{
                            id: 174,
                            data: [{data: 1, id: 215}, {data: 1, id: 29637}, {data: 0, id: 156}, {
                                data: "und",
                                id: 2274716
                            }, {data: "V_VP8", id: 134}, {data: "VP8", id: 2459272}, {data: 1, id: 131}, {
                                id: 224,
                                data: [{data: info.width, id: 176}, {data: info.height, id: 186}]
                            }]
                        }]
                    }]
                }], frameNumber = 0, clusterTimecode = 0; frameNumber < frames.length;) {
                    var clusterFrames = [], clusterDuration = 0;
                    do clusterFrames.push(frames[frameNumber]), clusterDuration += frames[frameNumber].duration, frameNumber++; while (frameNumber < frames.length && clusterMaxDuration > clusterDuration);
                    var clusterCounter = 0, cluster = {
                        id: 524531317,
                        data: getClusterData(clusterTimecode, clusterCounter, clusterFrames)
                    };
                    EBML[1].data.push(cluster), clusterTimecode += clusterDuration
                }
                return generateEBML(EBML)
            }

            function getClusterData(clusterTimecode, clusterCounter, clusterFrames) {
                return [{data: clusterTimecode, id: 231}].concat(clusterFrames.map(function (webp) {
                    var block = makeSimpleBlock({
                        discardable: 0,
                        frame: webp.data.slice(4),
                        invisible: 0,
                        keyframe: 1,
                        lacing: 0,
                        trackNum: 1,
                        timecode: Math.round(clusterCounter)
                    });
                    return clusterCounter += webp.duration, {data: block, id: 163}
                }))
            }

            function checkFrames(frames) {
                if (!frames[0])return void postMessage({error: "Something went wrong. Maybe WebP format is not supported in the current browser."});
                for (var width = frames[0].width, height = frames[0].height, duration = frames[0].duration, i = 1; i < frames.length; i++)duration += frames[i].duration;
                return {duration: duration, width: width, height: height}
            }

            function numToBuffer(num) {
                for (var parts = []; num > 0;)parts.push(255 & num), num >>= 8;
                return new Uint8Array(parts.reverse())
            }

            function strToBuffer(str) {
                return new Uint8Array(str.split("").map(function (e) {
                    return e.charCodeAt(0)
                }))
            }

            function bitsToBuffer(bits) {
                var data = [], pad = bits.length % 8 ? new Array(9 - bits.length % 8).join("0") : "";
                bits = pad + bits;
                for (var i = 0; i < bits.length; i += 8)data.push(parseInt(bits.substr(i, 8), 2));
                return new Uint8Array(data)
            }

            function generateEBML(json) {
                for (var ebml = [], i = 0; i < json.length; i++) {
                    var data = json[i].data;
                    "object" == typeof data && (data = generateEBML(data)), "number" == typeof data && (data = bitsToBuffer(data.toString(2))), "string" == typeof data && (data = strToBuffer(data));
                    var len = data.size || data.byteLength || data.length, zeroes = Math.ceil(Math.ceil(Math.log(len) / Math.log(2)) / 8), sizeToString = len.toString(2), padded = new Array(7 * zeroes + 7 + 1 - sizeToString.length).join("0") + sizeToString, size = new Array(zeroes).join("0") + "1" + padded;
                    ebml.push(numToBuffer(json[i].id)), ebml.push(bitsToBuffer(size)), ebml.push(data)
                }
                return new Blob(ebml, {type: "video/webm"})
            }

            function makeSimpleBlock(data) {
                var flags = 0;
                if (data.keyframe && (flags |= 128), data.invisible && (flags |= 8), data.lacing && (flags |= data.lacing << 1), data.discardable && (flags |= 1), data.trackNum > 127)throw"TrackNumber > 127 not supported";
                var out = [128 | data.trackNum, data.timecode >> 8, 255 & data.timecode, flags].map(function (e) {
                            return String.fromCharCode(e)
                        }).join("") + data.frame;
                return out
            }

            function parseWebP(riff) {
                for (var VP8 = riff.RIFF[0].WEBP[0], frameStart = VP8.indexOf("*"), i = 0, c = []; 4 > i; i++)c[i] = VP8.charCodeAt(frameStart + 3 + i);
                var width, height, tmp;
                return tmp = c[1] << 8 | c[0], width = 16383 & tmp, tmp = c[3] << 8 | c[2], height = 16383 & tmp, {
                    width: width,
                    height: height,
                    data: VP8,
                    riff: riff
                }
            }

            function getStrLength(string, offset) {
                return parseInt(string.substr(offset + 4, 4).split("").map(function (i) {
                    var unpadded = i.charCodeAt(0).toString(2);
                    return new Array(8 - unpadded.length + 1).join("0") + unpadded
                }).join(""), 2)
            }

            function parseRIFF(string) {
                for (var offset = 0, chunks = {}; offset < string.length;) {
                    var id = string.substr(offset, 4), len = getStrLength(string, offset), data = string.substr(offset + 4 + 4, len);
                    offset += 8 + len, chunks[id] = chunks[id] || [], "RIFF" === id || "LIST" === id ? chunks[id].push(parseRIFF(data)) : chunks[id].push(data)
                }
                return chunks
            }

            function doubleToString(num) {
                return [].slice.call(new Uint8Array(new Float64Array([num]).buffer), 0).map(function (e) {
                    return String.fromCharCode(e)
                }).reverse().join("")
            }

            var webm = new ArrayToWebM(frames.map(function (frame) {
                var webp = parseWebP(parseRIFF(atob(frame.image.slice(23))));
                return webp.duration = frame.duration, webp
            }));
            postMessage(webm)
        }

        return WhammyVideo.prototype.add = function (frame, duration) {
            if ("canvas"in frame && (frame = frame.canvas), "toDataURL"in frame && (frame = frame.toDataURL("image/webp", this.quality)), !/^data:image\/webp;base64,/gi.test(frame))throw"Input must be formatted properly as a base64 encoded DataURI of type image/webp";
            this.frames.push({image: frame, duration: duration || this.duration})
        }, WhammyVideo.prototype.compile = function (callback) {
            var webWorker = processInWebWorker(whammyInWebWorker);
            webWorker.onmessage = function (event) {
                return event.data.error ? void console.error(event.data.error) : void callback(event.data)
            }, webWorker.postMessage(this.frames)
        }, {Video: WhammyVideo}
    }();
    "undefined" != typeof MediaStreamRecorder && (MediaStreamRecorder.Whammy = Whammy), function () {
        window.ConcatenateBlobs = function (blobs, type, callback) {
            function readAsArrayBuffer() {
                if (!blobs[index])return concatenateBuffers();
                var reader = new FileReader;
                reader.onload = function (event) {
                    buffers.push(event.target.result), index++, readAsArrayBuffer()
                }, reader.readAsArrayBuffer(blobs[index])
            }

            function concatenateBuffers() {
                var byteLength = 0;
                buffers.forEach(function (buffer) {
                    byteLength += buffer.byteLength
                });
                var tmp = new Uint16Array(byteLength), lastOffset = 0;
                buffers.forEach(function (buffer) {
                    var reusableByteLength = buffer.byteLength;
                    reusableByteLength % 2 != 0 && (buffer = buffer.slice(0, reusableByteLength - 1)), tmp.set(new Uint16Array(buffer), lastOffset), lastOffset += reusableByteLength
                });
                var blob = new Blob([tmp.buffer], {type: type});
                callback(blob)
            }

            var buffers = [], index = 0;
            readAsArrayBuffer()
        }
    }(), "undefined" != typeof module && (module.exports = MediaStreamRecorder), "function" == typeof define && define.amd && define("MediaStreamRecorder", [], function () {
        return MediaStreamRecorder
    });

    function Gifts(placeHolder) {
        this.gifts = [];
        this.placeHolder = placeHolder
    }

    Gifts.prototype.init = function (gifts) {
        this.gifts = gifts;
    }

    Gifts.prototype.populate = function () {
        this.placeHolder.find("img").remove();
        for (var i in this.gifts) {
            this.placeHolder.append('<img class="emoticon" src="/uploads/gifts/' + this.gifts[i] + '" alt="' + i + '" title="' + i + '" />');
        }
        this.placeHolder.append('<img class="emoticon" src="images/gifts/del.png" alt="del" title="del" />');
    }

    function Faces(placeHolder) {
        this.faces = [];
        this.placeHolder = placeHolder
    }

    Faces.prototype.init = function (faces) {
        this.faces = faces;
    }

    Faces.prototype.populate = function () {
        this.placeHolder.find("img").remove();
        for (var i in this.faces) {
            var index = (i*1+1);
            this.placeHolder.append('<img class="emoticon" src="/uploads/faces/' + this.faces[i] + '" alt="' + index + '" title="' + index  + '" />');
        }
    }

    Faces.prototype.emoticonize = function (data, width) {
        for (var i = 0; i < 10; i++) {
            var regex = new RegExp('(ف|ض)([0-9]{1,3})');
            var match = regex.exec(data);

            if (match && this.faces[(match[2]-1)]) {
                data = data.replace(regex, '<img width="' + (width - 4) + '" src="/uploads/faces/' + this.faces[(match[2]-1)] +'" />')
            }
        }

        return data;
    }

    var confirmOnExit = true;
    var isAndroidApp = false;
    var mSocket = null;
    (function ($, Gifts, Faces) {

        var mFingerprint = null;

        var socket = null;
        var host = 'http://app.base-chat.local:2191';
//        var host = 'https://www.chatname.com:2191';

        var mUser = {};
        mUser.ignoreds = [];
        mUser.ignoredsOnWall = [];
        mUser.canRemoveFromWall = false;
        mUser.canRemoveBanner = false;

        var mRoom = {};
        var room = null;
        var mun = (getCookie('mun').length > 0 && getCookie('mun') != 'undefined') ? getCookie('mun') : '';
        var mup = (getCookie('mup').length > 0 && getCookie('mup') != 'undefined') ? getCookie('mup') : '';
        var gun = (getCookie('gun').length > 0 && getCookie('gun') != 'undefined') ? getCookie('gun') : '';
        var r = (getCookie('r').length > 0 && getCookie('r') != 'undefined') ? getCookie('r') : 0;
        var prv = getCookie('prv') ? true : false;
        var avatar = (getCookie('avatar').length > 0 && UrlExists('uploads/avatars/' + getCookie('avatar'))) ? 'uploads/avatars/' + getCookie('avatar') : 'images/none.png';
        var currentChat = null;
        var colorPickerFor = null;
        var orderNameColorFor = null;
        var orderNameBgColorFor = null;
        var facePickerFor = null;
        var giftPickerFor = null;
        var wallPhotoForm = null;
        var posts = false;
        var Gifts = new Gifts($("#gift-picker"));
        var Faces = new Faces($("#face-picker"));

        var mediaRecorder = null;
        var isMediaRecording = false;
        navigator.getMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;

        var textColor = getCookie('fontColor').length > 0 ? getCookie('fontColor') : '#000';
        textColor = textColor.indexOf("#") === -1 ? "#" + textColor : textColor;
        var fontSize = 13;

        initColors();

        new Fingerprint2().get(function (result) {
            mFingerprint = result;
            socket = mSocket = io(host, {
                transports: ['websocket', 'xhr-polling', 'polling', 'htmlfile', 'flashsocket'],
                secure: true,
                rejectUnauthorized: false,
                query: "fp=" + result + "&d=" + mFingerprint
            });


            $("#main-footer-top-show-colors").css("background-color", textColor);
            if (getCookie("nameColor").length > 0) {
                socket.emit("savedNameColor", getCookie("nameColor"));
            }
            if (getCookie("nameBgColor").length > 0) {
                socket.emit("savedNameBgColor", getCookie("nameBgColor"));
            }

            setTimeout(function () {
                $("#settings-font-color-footer").css("color", "rgba(0, 0, 0, 0)");
            }, 100);

            $.getJSON('https://geoip.nekudo.com/api/', function (res) {
                mUser.country = res.country.code;
                socket.emit("newConnection", {finger: mFingerprint, country: mUser.country});
            });

            $("#guest-name").val(gun || "");
            $("#member-name").val(mun || "");
            $("#member-password").val(mup || "");

            socket.on('connect_error', function () {
                $("#status").css("background-color", "#bd2c00").text("غير متصل");
            });
            socket.on('connect', function () {
                $("#status").css("background-color", "#5cb85c").text("متصل");
            });

            socket.on("analytics", function (data) {
                $("#count-members-online").text(data.countUsersOnline);
                $("#count-members-online-24").text(data.countUsersOnline24);
                $("#count-rooms").text(data.countRooms);
                $("body").css('background-color', data.backgroundColor);
                $("#count-registered-members").text(data.registeredUsersCount);
            });

            socket.on("online-users", function(users) {
                $(".label-count .count").text(users.length);

                for(var i in users) {
                    renderOnlineUser(users[i]);
                }

                $("#online-users").css("max-height", ($(".ui-widget-overlay").height() - $("#dialog-form-header").height())+"px");
            });

            socket.on("reload_interface_settings", function(colors) {
                mColors = colors;
                initColors();
            });

            socket.on("denied", function () {
                alert("لقد تم حظرك");
            });

            socket.on("main_bg_changed", function (color) {
                $('body').css("background-color", color);
            });

            socket.on('regDeined', function () {
                alert('تم منع التسجيل في الموقع مؤقات, الرجاء العودة لاحقا');
                $("#member-signup input[type='submit']").prop('disabled', false);
            });

            socket.on('guestLoginDenied', function () {
                alert('عذرا, لقد تم منع دخول الزوار الغير مسجلين, الرجاء المحاولة لاحقا');
                $("#guest-login input[type='submit']").prop('disabled', false);
            });

            $.each($('#color-picker span'), function (index, value) {
                $(this).css('background-color', $(this).attr('value'));
            });

            $('#color-picker span').on('click', function () {

                if (colorPickerFor == "globalFontColor") {
                    textColor = $(this).attr('value');
                    $("#main-footer-top-show-colors").css("background-color", textColor);
                } else if (colorPickerFor == "settingsFontColor") {
                    textColor = $(this).attr('value');
                    $("#main-footer-top-show-colors").css("background-color", textColor);
                    if (mUser.role == 'guest')
                        setCookie("fontColor", textColor, 360);
                    socket.emit("settingsFontColor", textColor);
                } else if (colorPickerFor == "ordersNameColor") {
                    var color = $(this).attr('value');
                    if (color && orderNameColorFor) {
                        socket.emit("orderNameColor", orderNameColorFor, color);
                        orderNameColorFor = null;
                        $("#user-details-window").show();
                    }
                } else if (colorPickerFor == "ordersNameBgColor") {
                    var color = $(this).attr('value');
                    if (color && orderNameBgColorFor) {
                        socket.emit("ordeNameBgColor", orderNameBgColorFor, color);
                        orderNameBgColorFor = null;
                        $("#user-details-window").show();
                    }
                } else if (colorPickerFor == "settingsNameColor") {
                    var color = $(this).attr('value');
                    if (color) {
                        socket.emit("settingsNameColor", color);
                        if (mUser.role == 'guest')
                            setCookie("nameColor", color, 360);
                        $("#settings-window").show();
                    }
                } else if (colorPickerFor == "settingsNameBgColor") {
                    var color = $(this).attr('value');
                    if (color) {
                        socket.emit("settingsNameBgColor", color);
                        if (mUser.role == 'guest')
                            setCookie("nameBgColor", color, 360);
                        $("#settings-window").show();
                    }
                }

                colorPickerFor = null;
                $(this).parent().removeClass('color-picker-displayed');

            });

            $('#settings-font-color, #settings-font-color-footer').on('change', function () {
                textColor = $(this).val();
                textColor = $(this).val().indexOf("#") === -1 ? "#" + $(this).val() : $(this).val();
                $("#main-footer-top-show-colors").css("background-color", textColor);
                if (mUser.role == 'guest')
                    setCookie("fontColor", textColor, 360);
                socket.emit("settingsFontColor", textColor);
                $("#settings-font-color-footer").css("color", "rgba(0, 0, 0, 0)").css("font-size", "0").css("line-height", "0");
//            colorPickerFor = "settingsFontColor";
//            $('#color-picker').toggleClass('color-picker-displayed');
            });

            $("#settings-change-nickname").on("click", function (e) {
                e.preventDefault();
                var newName = prompt("الرجاء إدخال الزخرفة");
                if (newName) socket.emit("settingsNickname", newName);
            });

            $('#main-footer-top-show-colors').on('click', function () {
                colorPickerFor = "globalFontColor";
                $('#color-picker').toggleClass('color-picker-displayed');
            });

            $('#main-footer-top-show-emoticons').on('click', function () {
                facePickerFor = "mainFacePicker";
                $('#face-picker').toggleClass('face-picker-displayed');
            });

            $('#wall-show-emoticons').on('click', function () {
                facePickerFor = "wallFacePicker";
                $('#face-picker').toggleClass('face-picker-displayed');
            });


            $('#chat-box footer #chat-box-send-smile').on('click', function () {
                facePickerFor = "chatFacePicker";
                $('#face-picker').toggleClass('face-picker-displayed');
            });

            $('#chat-box footer #chat-box-attach').on('click', function () {
                $("#chat-box-attach-input").trigger("click");
            });

            $("#chat-box-attach-input").on("change", function () {
                socket.emit("privateMediaCheck", $("#chat-box").attr("user_id"));
            });

//************************ 01 - BEGIN MAIN DIALOG INITIALISATION ************************

            // Main Dialog Initialisation
            var dialog = $("#dialog-form").dialog({
                dialogClass: "main-modal",
                width: 360,
                resizable: false,
                draggable: false,
                modal: true,
                cancelable: false,
            });
            $(".ui-dialog-titlebar").remove();
            $(".ui-widget-overlay").css("opacity", "0.8");

            $("#tabs").tabs();
            $("#guest-name").focus();

            $("#tab1").on("click", function () {
                $("#guest-name").focus();
            });
            $("#tab2").on("click", function () {
                $("#member-name").focus();
            });
            $("#tab3").on("click", function () {
                $("#member-name-signin").focus();
            });

            // Close and reopen dialog to keep it centered each time this window size is mdoified
            $(window).resize(function () {
                //if($("#dialog-form").dialog("isOpen")) {
                //    $("#dialog-form").dialog("close");
                //    $("#dialog-form").dialog("open");
                //}
                //$("#guest-name").focus();
            });

//************************ 01 - END MAIN DIALOG INITIALISATION ***************************

            var documentHeight = $(window).height();
            $("#chat-box").css("height", (documentHeight - 90) + "px").css("max-height", "300px");
            ;
            $("#chat-box #chat-box-messages").css("height", (documentHeight - 170) + "px").css("max-height", "220px");
            ;
            $("#main-content, #users-online, #conversations, #rooms-window, #settings-window, #wall-window, #user-details-window").css("height", (documentHeight - 70) + "px");

            $(window).resize(function () {
                var documentHeight = $(window).height();
                $("#main-content, #users-online, #conversations, #rooms-window, #settings-window, #wall-window, #user-details-window").css("height", (documentHeight - 70) + "px");
                $("#main-content").animate({scrollTop: $("#main-content .user-status").length * 60}, 0);
                $("#chat-box").css("height", (documentHeight - 90) + "px").css("max-height", "300px");
                ;
                $("#chat-box #chat-box-messages").css("height", (documentHeight - 170) + "px").css("max-height", "220px");
            });

//************************ 02 - BEGIN LOGINS HANDLING ************************************

            $("#guest-login").on("submit", function (e) {
                e.preventDefault();
                var guestName = deleteDuplicateSpaces($("#guest-name").val()).trim();
                if (guestName.length > 2 && guestName.length < 50) {
                    resetBorder("#guest-name");
                    startApp(guestName);
                    $("#guest-login input[type='submit']").prop('disabled', true);
                } else {
                    makeBorderRed("#guest-name");
                    return;
                }

            });

            $("#member-login").on("submit", function (e) {
                e.preventDefault();
                var username, password, hidden;
                username = deleteDuplicateSpaces($("#member-name").val()).trim();
                password = deleteDuplicateSpaces($("#member-password").val()).trim();
                hidden = $("#hidden-signin")[0].checked;

                if (username.length < 1 || password.length < 1) {
                    makeBorderRed("#member-name");
                    makeBorderRed("#member-password");
                    $("#error-member").text("الرجاء التأكد من إخالك جميع البيانات").show();
                    return;
                }

                socket.emit("signinTry", {
                    username: username,
                    password: password,
                    avatar: avatar,
                    country: mUser.country,
                    hidden: hidden
                });
                $("#member-login input[type='submit']").prop('disabled', true);
                mup = password;
            });

            socket.on("signinResult", function (result) {
                result = JSON.parse(result);

                if (result.error) {
                    if (result.message == "account does not exist") {
                        makeBorderRed("#member-name");
                        makeBorderRed("#member-password");
                        $("#error-member").text("لا يمكن الدخول بهذا الحساب, الرجاء التحقق من البيانات").show();
                    }
                    $("#member-login input[type='submit']").prop('disabled', false);
                } else {
                    resetBorder("#member-name");
                    resetBorder("#member-password");
                    $("#error-member").text("").hide();
                    setCookie("r", result.r, 360);
                }
            });

            socket.on("privateMediaReady", function (by, to) {
                sendPrivateMedia(by, to);
            });

            function sendPrivateMedia(by, to) {
                var input = $("#chat-box-attach-input");
                if (input[0].files[0]) {
                    var id = new Date().getTime();

                    var formData = new FormData();
                    formData.append("file", input[0].files[0]);

                    $.ajax({
                        url: 'private/upload',
                        type: 'POST',
                        processData: false,
                        contentType: false,
                        data: formData,
                        success: function (result) {
                            $("#pb-" + id).hide();
                            try {
                                var json = JSON.parse(result);
                                if (json.error === false) {
                                    var cls = "conversation-msg msg-sender";
                                    var time = new Date().getTime();
                                    console.log("avatar ", avatar);
                                    var privateMsgHtml = '<div style="color: ' + textColor + '" class="' + cls + '">' +
                                            '<img class="chat-msg-avatar" user_id="' + mUser.id + '" src="' + avatar + '" width="40" height="40" />' +
                                            by + '<br />' + json.rendered +
                                            '<div class="chat-msg-time" time="' +
                                            time + '">' + timeAgo(time) +
                                            '</div></div><div class="clear"></div>';

                                    $(privateMsgHtml).appendTo("#chat-box #chat-box-messages");
                                    handleMediaLoad();

                                    $(".chat-msg-avatar").unbind("click").bind("click", function () {
                                        getUserDetails($(this).attr("user_id"));
                                    });

                                    socket.emit("sendPrivateMedia", to, {file: json.file, type: json.type});
                                }
                            } catch (e) {
                                console.log(e.stack);
                            }
                        },
                        error: function () {
                            $("#pb-" + id).hide();
                        },
                        xhr: function () {
                            var xhr = new window.XMLHttpRequest();
                            var progressHtml = '<div id="pb-' + id + '" class="progress">' +
                                    '<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" ' +
                                    'aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">' +
                                    '</div><span class="remove glyphicon glyphicon-remove-circle"></span></div>';
                            var pb = $(progressHtml).appendTo("#chat-box #chat-box-messages");
                            $("#chat-box #chat-box-messages").animate({scrollTop: $("#chat-box #chat-box-messages .conversation-msg").length * 500}, 0);
                            pb.find(".remove").on("click", function () {
                                xhr.abort();
                                pb.find(".progress-bar").animate({width: 0}, 2000, function () {
                                    $("#pb-" + id).hide();
                                });
                            });

                            xhr.upload.addEventListener("progress", function (evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = evt.loaded / evt.total;
                                    percentComplete = parseInt(percentComplete * 100);
                                    pb.find(".progress-bar").css("width", percentComplete + "%");

                                    if (percentComplete >= 100) {

                                    }

                                }
                            }, false);

                            return xhr;
                        }
                    });
                }
            }

            $("#delete-password").on("click", function (e) {
                $("#member-password").val("");
            });

            $("#member-signup").on("submit", function (e) {
                e.preventDefault();
                var username, password;
                username = deleteDuplicateSpaces($("#member-name-signin").val()).trim();
                password = deleteDuplicateSpaces($("#member-password-signin").val()).trim();
                if (username.length > 1) {
                    if (password.length >= 6) {
                        $("#error-member-password-signin").hide();
                        resetBorder("#member-password-signin");
                        socket.emit("signupTry", {username: username, password: password});
                        $("#member-signup input[type='submit']").prop('disabled', true);
                    } else {
                        makeBorderRed("#member-password-signin");
                        $("#error-member-password-signin").text("كلمة السر يجب أن لا تقل عن6 أحرف").show();
                        $("#error-member-name-signin").hide();
                        resetBorder("#member-name-signin");
                        return;
                    }
                } else {
                    makeBorderRed("#member-name-signin");
                    $("#error-member-name-signin").text("الإسم المستعار لا يجب أن يقل عن حرفين").show();
                    return;
                }
            });

            socket.on("signupTryResult", function (result) {
                var json = result;
                if (json.error) {
                    if (json.message == "username already exists") {
                        $("#error-member-name-signin").text("هذا الإسم محجوز, الرجاء إخيار إسم آخر").show();
                    } else if (json.message == "cant add user") {
                        $("#error-member-name-signin").text("حدث خطأ أثناء التسجيل, الرجاء إعادة المحاولة").show();
                    } else if (json.message == "short username") {
                        makeBorderRed("#member-name-signin");
                        $("#error-member-name-signin").text("الإسم المستعار لا يجب أن يقل عن حرفين").show();
                    } else if (json.message == "short password") {
                        makeBorderRed("#member-password-signin");
                        $("#error-member-password-signin").text("كلمة السر يجب أن لا تقل عن6 أحرف").show();
                        $("#error-member-name-signin").hide();
                        resetBorder("#member-name-signin");
                    }
                    $("#member-signup input[type='submit']").prop('disabled', false);
                } else {
                    $("#error-member-name-signin").hide();
                    resetBorder("#member-name-signin");
                    $("#error-member-password-signin").hide();
                    resetBorder("#member-password-signin");
                    $("#tabs").tabs("option", "active", 1);
                    $("#success-member-signip").text("تم تسجيل العضوية بنجاح, تفضل بالدخول").show();
                    $("#member-name").val(json.username);
                    $("#member-password").val(json.password);
                }

            });

            socket.on("loginOk", function (data) {
                resetBorder("#guest-name");
                if (!ch()) return;

                //auto-login
//            if(GET["ac"] && GET["u"]) {
//                var mmssgg = 'نعتز بكوننا زوار واعضاء جدد في شـــات جــالــكســي .. ونتمنى حســن الضــيافه ..';
////                socket.emit("newMessage", "س1", "#000000");
//                socket.emit("newMessage", mmssgg, "#000000");
//            }

                handleLoggedUser(data, false);

                if (!$("#dialog-form").dialog("isOpen")) {
                    var message = "هذا المستخدم قام بالإنضمام إلى ";
                    addRowMainContent(data.user.id, Faces.emoticonize(data.user.username, 20), data.user.avatar, '<span class="room-link" room="' + room + '">(<span class="glyphicon glyphicon-comment"></span>  ' + data.room.name + ')</span>' + message, Date.now());
                }

                $("#main-footer-bottom-online-count span, #toggle-users-online .count").text(data.usersCount);
                sortUsers();
                setTimeout(function () {
                    $("#settings-font-color-footer").val("#00FF00");
                    jscolor.installByClassName("jscolor");
                }, 100);

            });

            function handleLoggedUser(data, isAutoLogged) {

                if (data.me) {

                    $("#main-footer").show();

                    mUser = data.user;
                    mUser.isLogged = true;
                    room = data.room.id;
                    mRoom = data.room;
                    mUser.ignoreds = [];
                    mUser.ignoredsOnWall = [];

                    if (data.user.role == 'guest') {
                        setCookie('gun', data.user.rawUsername, 30);
                        $("#settings-change-password").remove();
                    } else {
                        setCookie('mun', data.user.realUserName, 30);
                        setCookie('mup', mup, 30);
                    }

                    if ($("#dialog-form").dialog("isOpen")) {

                        if (data.user.role == "guest") {
                            $("#wall-buttons a[data-type='recorder-start']").remove();
                            $("#wall-buttons a[data-type='recorder-stop']").remove();
                        }

                        $("#dialog-form").dialog("close");

                    }

                    $("#rooms-window .window-section #all-rooms-block .clickable-user").removeClass("current");
                    $("#rooms-window .window-section #all-rooms-block #" + data.room.id).addClass("current");

                    if (isAutoLogged) {
                        if (!$("#dialog-form").dialog("isOpen")) {
                            var message = "هذا المستخدم قام بالإنضمام إلى ";
                            addRowMainContent(data.user.id, Faces.emoticonize(data.user.username, 20), data.user.avatar, '<span class="room-link" room="' + room + '">(<span class="glyphicon glyphicon-comment"></span>  ' + data.room.name + ')</span>' + message, Date.now());
                        }
                    }

                }

            }

            socket.on("currentRoomDetails", function (rm) {
                mRoom = rm;
                var editRoomForm = $("#settings-edit-room > form");
                if (editRoomForm.length) {
                    editRoomForm.data("room-id", rm.id);
                    editRoomForm.find("[name='name']").val(rm.name);
                    editRoomForm.find("[name='welcome']").val(rm.welcome);
                    editRoomForm.find("[name='max']").val(rm.max);
                    editRoomForm.find("input[type='file']").attr("data-old", rm.flag).attr("data-id", rm.id);
                }
                $("#settings-remove-room").data("room", rm.id);
            });

            // auto-login
//        var GET = getQueryParams(document.location.search);
//        if(GET["ac"] && GET["u"]) {
//            $("#guest-name").val(GET["u"]);
//            setTimeout(function() {
//                $("#guest-login").trigger("submit");
//            }, 100);
//        }

//*****************************************************************************************************

//************************ 02 - END LOGINS HANDLING ***************************************************

            $(".close-window").on("click", function () {
                if ($(this).parent().attr('id') == 'settings-window') {
                    $(this).parent().toggleClass('settings-window-displayed');
                } else {
                    $(this).parent().toggle();
                }
            });

            $("#toggle-users-online").on("click", function (e) {
                e.preventDefault();
                $("#users-online").toggle();
                $(this).addClass("active");
            });

            $("#toggle-conversations").on("click", function (e) {
                e.preventDefault();
                $("#conversations").toggle();
                $(this).addClass("active");
            });

            $("#toggle-rooms").on("click", function (e) {
                e.preventDefault();
                $("#rooms-window").toggle();
                $(this).addClass("active");
            });

            $("#toggle-settings").on("click", function (e) {
                e.preventDefault();
                $(this).addClass("active");
                $("#settings-window").toggleClass('settings-window-displayed');
                socket.emit("getMoreSettingsRequest");
            });

            $("#toggle-wall").on("click", function (e) {
                e.preventDefault();
                $("#wall-window").toggle().addClass("on");
                $(this).addClass("active").removeClass("new");
                $("#toggle-wall .count").text(0).hide();
                if (!posts) socket.emit("getPostsRequest");
                if (!posts) socket.emit("getBannersRequest");
                socket.emit("canRemovePostsFromWallRequest");
            });

            socket.on("canRemovePostsFromWallResponse", function (posts, banners) {
                mUser.canRemoveFromWall = posts;
                mUser.canRemoveBanner = banners;

                if (posts) $(".wall-msg-remove").show();
                else $(".wall-msg-remove").hide();

                if (banners) $(".wall-banner-remove").show();
                else $(".wall-banner-remove").hide();

            });

            $("#toggle-user-details").on("click", function (e) {
                e.preventDefault();
                $("#user-details-window").toggle();
            });

            $('#settings-change-avatar').on('click', function () {
                $('#select_avatar').trigger('click');
            });

            $('#select_avatar').on('change', function () {
                var files = document.getElementById('select_avatar').files;
                var formData = new FormData();
                formData.append('action', 'setAvatar');

                $.each(files, function (key, value) {
                    formData.append(key, value);
                });

                formData.append('_token', mToken);

                $.ajax({
                    xhr: function () {
                        return new window.XMLHttpRequest();
                    },
                    url: 'upload',
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (result) {
                        try {
                            var json = JSON.parse(result);
                            if (!json.error) {
                                if (mUser.role == 'guest')
                                    setCookie('avatar', json.avatar, 360);
                                avatar = 'uploads/avatars/' + json.avatar;
                                socket.emit('changeAvatar', avatar, navigator.cookieEnabled);
                            }
                        } catch (e) {
                        }
                    }
                });

            });

            $('#settings-remove-avatar').on("click", function () {
                socket.emit("removeAvatar");
                $("#settings-window").toggleClass('settings-window-displayed');
            });

            $('#settings-change-password').on("click", function () {
                var pass = prompt("الرجاء إدخال كلمة المرور الجديدة").trim();
                if (pass) socket.emit("settingsChangePassword", pass);
            });

            socket.on("changePasswordResponse", function (data) {
                if (data.error === false && data.message == 'success') {
                    setCookie('mup', data.pass, 30);
                    alert("تم تغير كلمة المرور بنجاح");
                } else {
                    alert("حدث خطأ أثناء تغيير كلمة المرور");
                }
            });

            $('#settings-create-room > div, #settings-edit-room > div').on("click", function (e) {
                e.preventDefault();
                var form = $(this).parent().find("form");
                if (form.hasClass("displayed")) {
                    form.hide();
                    form.removeClass("displayed");
                    $(this).find("a span").removeClass("glyphicon-chevron-up").addClass("glyphicon-chevron-down");
                } else {
                    form.show();
                    form.addClass("displayed");
                    $(this).find("a span").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up");
                }
            });

            $('#settings-create-room > form, #settings-edit-room > form').on("submit", function (e) {
                e.preventDefault();
                var name = deleteDuplicateSpaces($(this).find("input[name='name']").val().trim());
                var pass = deleteDuplicateSpaces($(this).find("input[name='password']").val().trim());
                var welcome = deleteDuplicateSpaces($(this).find("input[name='welcome']").val().trim());
                var max = deleteDuplicateSpaces($(this).find("input[name='max']").val().trim());
                if (!name) {
                    alert("يجب إختيار إسم للغرفة");
                    return;
                }
                if (name.length < 6) {
                    alert("إسم الغرفة يجب أن لا يقل عن 6 أحرف");
                    return;
                }
                if (!max) {
                    alert("يجب تحديد أقصى عدد للزوار");
                    return;
                }
                if (max < 2 || max > 150) {
                    alert("أقصى عدد للزوار يجب أن يتراوح بين 2 و 40");
                    return;
                }
                if ($(this).data("action") == "create")
                    socket.emit("createRoomRequest", {name: name, password: pass, welcome: welcome, max: max});
                else if ($(this).data("action") == "edit")
                    socket.emit("editRoomRequest", {
                        id: parseInt($(this).data("room-id"), 10),
                        name: name,
                        password: pass,
                        welcome: welcome,
                        max: parseInt(max)
                    });
            });

            $("#update-room-flag-btn").on("click", function (e) {
                e.preventDefault();
                $("#update-room-flag-input").trigger("click");
            });

            $("#update-room-flag-input").unbind("change").on("change", function () {

                var formData = new FormData();
                formData.append("flag", $(this).prop("files")[0]);
                formData.append("room_id", mRoom.id);
                formData.append("old_flag", mRoom.flag);

                $("#update-room-flag-btn").attr("disabled", true);

                $.ajax({
                    xhr: function () {
                        return new window.XMLHttpRequest();
                    },
                    url: 'rooms/change-flag',
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        try {
                            response = JSON.parse(response);
                            if (response.error === false) {
                                socket.emit('change-room-flag-request', {id: response.id, flag: response.flag});
                            } else {
                                alert("حدث خطأ أثناء رفع الصورة");
                            }
                        } catch (e) {
                        }
                        $("#update-room-flag-btn").attr("disabled", false);
                    },
                    error: function () {
                        $("#update-room-flag-btn").attr("disabled", false);
                    }
                });

            });

            socket.on("change-room-flag-response", function (rm) {
                var editRoomForm = $("#settings-edit-room > form");
                if (editRoomForm.length) {
                    editRoomForm.find("input[type='file']").attr("data-old", rm.flag).attr("data-id", rm.id);
                }
            });

            $('#settings-remove-room').on("click", function (e) {
                e.preventDefault();
//            if(confirm("هل أنت متأكد من حذف هذه الغرفة؟"))
                socket.emit("deleteRoomRequest", $(this).data("room"));
            });

            socket.on("createRoomResponse", function (data) {
                if (data.msg == "maxReached")
                    alert("يمكنك فقط إنشاء " + data.max + " غرفة في الشهر");
                else if (data.msg == "alreadyExist")
                    alert("هذا الإسم موجود من قبل, الرجاء إختيار إسم آخر");
                else if (data.msg == "success")
                    alert("ـم إنشاء الغرفة بنجاح");
            });

            socket.on("deleteRoomResponse", function (data) {
                if (data.msg == "unremovable")
                    alert("لا يمكنك حذف هذه الغرفة");
            });

            socket.on("editRoomResponse", function (data) {
                if (data.msg == "uneditable")
                    alert("لا يمكنك تعديل هذه الغرفة");
                if (data.msg == "noPermission")
                    alert("لا تملك صلاحية حذف الغرف");
            });

            $(document).mouseup(function (e) {
                var settingsWindow = $("#settings-window");
                var colorPicker = $("#color-picker");
                var facePicker = $("#face-picker");
                var giftPicker = $("#gift-picker");
                var userDetails = $("#user-details-window");
                var roomsWindow = $("#rooms-window");
                var conversations = $("#conversations");
                var usersOnline = $("#users-online");
                var wallWindow = $("#wall-window");

                var toggleOnnlie = $("#toggle-users-online");
                var toggleConversa = $("#toggle-conversations");
                var toggleRooms = $("#toggle-rooms");
                var toggleSettings = $("#toggle-settings");
                var toggleWall = $("#toggle-wall");

                if (!settingsWindow.is(e.target) && settingsWindow.has(e.target).length === 0) {
                    settingsWindow.removeClass("settings-window-displayed");
                }
                if (!colorPicker.is(e.target) && colorPicker.has(e.target).length === 0) {
                    colorPicker.removeClass("color-picker-displayed");
                    colorPickerFor = null;
                    orderNameColorFor = null;
                    orderNameBgColorFor = null;
                }
                if (!facePicker.is(e.target) && facePicker.has(e.target).length === 0) {
                    facePicker.removeClass("face-picker-displayed");
                    facePickerFor = null;
                }
                if (!giftPicker.is(e.target) && giftPicker.has(e.target).length === 0) {
                    giftPicker.removeClass("gift-picker-displayed");
                    giftPickerFor = null;
                }
                if (!userDetails.is(e.target) && userDetails.has(e.target).length === 0) {
                    userDetails.hide();
                }
                if (!roomsWindow.is(e.target) && roomsWindow.has(e.target).length === 0) {
                    roomsWindow.hide();
                }
                if (!conversations.is(e.target) && conversations.has(e.target).length === 0) {
                    conversations.hide();
                }
                if (!usersOnline.is(e.target) && usersOnline.has(e.target).length === 0) {
                    usersOnline.hide();
                }
                if (!toggleOnnlie.is(e.target) && toggleOnnlie.has(e.target).length === 0) {
                    toggleOnnlie.removeClass('active');
                }
                if (!toggleConversa.is(e.target) && toggleConversa.has(e.target).length === 0) {
                    toggleConversa.removeClass('active');
                }
                if (!toggleRooms.is(e.target) && toggleRooms.has(e.target).length === 0) {
                    toggleRooms.removeClass('active');
                }
                if (!toggleSettings.is(e.target) && toggleSettings.has(e.target).length === 0) {
                    toggleSettings.removeClass('active');
                }
                if (!wallWindow.is(e.target) && wallWindow.has(e.target).length === 0) {
                    wallWindow.hide().removeClass("on");
                    ;
                }
                if (!toggleWall.is(e.target) && toggleWall.has(e.target).length === 0) {
                    toggleWall.removeClass('active');
                }
            });

            $('#settings-window #settings-block-chat').on('click', function () {
                var checkbox = $('#settings-window #settings-block-chat input[type="checkbox"]');
                checkbox.prop('checked', !checkbox.prop('checked'));
                var state = checkbox.prop('checked');
                socket.emit('privateMessaging', state);
                if (state) setCookie("prv", true, 360);
                else setCookie("prv", false, -1);
            });

            $('#settings-select-font-size').change(function () {
                var zoom = $(this).val();
                $('*').css("font-size", zoom + 'px');
                if ($(document).width() > 375)
                    $("#main-footer *").css("font-size", '14px');
                else if ($(document).width() < 375 && $(document).width() > 350)
                    $("#main-footer *").css("font-size", '12px');
                else if ($(document).width() < 350)
                    $("#main-footer *").css("font-size", '11px');
                // if(mUser.role == 'guest')
                // setCookie('fontSize', zoom, 30);
                socket.emit("settingsFontSize", zoom);
                fontSize = zoom;
            });

            socket.on("settingsFontSize", function (zoom) {
                $('*').css("font-size", zoom + 'px');
            });

            $("#users-online input[type=\"search\"]").on("keyup", function () {
                $("#users-online .searched-users .clickable-user").remove();

                var name = $(this).val();
                if (name.length >= 1) {
                    socket.emit("findUser", name);
                    $("#users-online .this-room.all-users").hide();
                    $("#users-online .other-rooms.all-users").hide();
                    $("#users-online .this-room.searched-users").show();
                    $("#users-online .other-rooms.searched-users").show();
                }
                else {
                    socket.emit("getAllUser");
                    $("#users-online .this-room.all-users").show();
                    $("#users-online .other-rooms.all-users").show();
                    $("#users-online .this-room.searched-users").hide();
                    $("#users-online .other-rooms.searched-users").hide();
                }
            });

            $(".close-chat-window").unbind("click").bind("click", function (e) {
                e.preventDefault();
                $("#chat-box").hide();
                socket.emit("close-conversation", currentChat);
                $('#conversations .window-section .clickable-user[user_id="' + currentChat + '"]').remove();
                currentChat = null;
            });

            $(".minimize-chat-window").unbind("click").bind("click", function (e) {
                e.preventDefault();
                $("#chat-box").hide();
                currentChat = null;
            });

            $("#chat-box footer #chat-box-send-msg").unbind("click").bind("click", function (e) {
                e.preventDefault();
                var input = $('#chat-box footer input[type="text"]').val().trim();
                if (input)
                    socket.emit("sendPrivateMessage", {
                        user: $("#chat-box").attr("user_id"),
                        message: input,
                        color: textColor
                    });
                $('#chat-box footer input[type="text"]').val("");
            });

            $('#chat-box footer input[type="text"]').on("keypress", function (e) {
                if (e.keyCode == 13) {
                    var input = $(this).val().trim();
                    if (input)
                        socket.emit("sendPrivateMessage", {
                            user: $("#chat-box").attr("user_id"),
                            message: input,
                            color: textColor
                        });
                    $('#chat-box footer input[type="text"]').val("");
                }
            });

            $("#wall-buttons a").on("click", function (e) {
                e.preventDefault();
                var type = $(this).data("type");
                if (type == "text") {
                    var msg = prompt("أكتب رسالتك هنا:");
                    if (msg) socket.emit("textWallRequest", msg.substr(0, 500), textColor);
                } else if (type == "photo") {
                    $("#wall-input-photo").trigger("click");
                } else if (type == "video") {
                    $("#wall-input-video").trigger("click");
                } else if (type == "sound") {
                    $("#wall-input-sound").trigger("click");
                } else if (type == "banner") {
                    var msg = prompt("أكتب رسالتك هنا:");
                    if (msg) socket.emit("bannerWallRequest", msg);
                } else if (type == "recorder-start") {
                    startRecording();
                } else if (type == "recorder-stop") {
                    stopRecording();
                    $(this).hide();
                    $("#wall-buttons a[data-type='recorder-start']").show();
                }
            });

            $("#wall-send-msg").on("click", function (e) {
                e.preventDefault();
                var msg = $("#wall-send-msg-input").val().trim();
                if (msg) socket.emit("textWallRequest", msg.substr(0, 500), textColor);
                $("#wall-send-msg-input").val("");
            });

            $("#wall-send-msg-input").on("keypress", function (e) {
                if (e.keyCode == 13) {
                    var msg = $(this).val().trim();
                    if (msg) socket.emit("textWallRequest", msg.substr(0, 500), textColor);
                    $(this).val("");
                }
            });

            $("#wall-input-photo").on("change", function () {
                var formData = new FormData();
                var file = document.getElementById("wall-input-photo").files[0];
                formData.append("file", file);
                wallPhotoForm = formData;
                socket.emit("onWallMediaRequest", "photo");
            });

            $("#wall-input-video").on("change", function () {
                var formData = new FormData();
                var file = document.getElementById("wall-input-video").files[0];
                formData.append("file", file);
                wallPhotoForm = formData;
                socket.emit("onWallMediaRequest", "video");
            });

            $("#wall-input-sound").on("change", function () {
                var formData = new FormData();
                var file = document.getElementById("wall-input-sound").files[0];
                formData.append("file", file);
                wallPhotoForm = formData;
                socket.emit("onWallMediaRequest", "sound");
            });

            socket.on("gifts", function (gifts) {
                Gifts.init(gifts);
                Gifts.populate();

                $('#gift-picker img').unbind("click").bind('click', function () {
                    if (giftPickerFor) {
                        socket.emit("orderGift", giftPickerFor, $(this).attr('src'));
                    }
                    giftPickerFor = null;
                    $(this).parent().removeClass('gift-picker-displayed');
                });

            });

            socket.on("faces", function (faces) {
                Faces.init(faces);
                Faces.populate();

                $('#face-picker img').unbind("click").bind('click', function () {

                    if (facePickerFor == "mainFacePicker") {
                        var emot = ' ف' + $(this).attr('title') + " ";
                        $("#message-to-send").val($("#message-to-send").val() + emot).focus();
                    } else if (facePickerFor == "chatFacePicker") {
                        var emot = ' ف' + $(this).attr('title') + " ";
                        $("#chat-box footer input[type='text']").val($("#chat-box footer input[type='text']").val() + emot).focus();
                    } else if (facePickerFor == "wallFacePicker") {
                        var emot = ' ف' + $(this).attr('title') + " ";
                        $("#wall-send-msg-input").val($("#wall-send-msg-input").val() + emot).focus();
                    }

                    facePickerFor = null;
                    $(this).parent().removeClass('face-picker-displayed');
                });

            });

            socket.on("onWallMediaResponse", function (data) {
                wallPhotoForm.append("i", data.i);
                wallPhotoForm.append("h", data.h);
                $.ajax({
                    url: data.url,
                    type: "post",
                    processData: false,
                    contentType: false,
                    data: wallPhotoForm,
                    success: function (result) {
                        try {
                            var json = JSON.parse(result);
                            if (json.message) {
                                if (json.message == 'please choose a file') {
                                    alert("الرجاء إختيار صورة");
                                } else if (json.message == 'photo so long') {
                                    alert("الرجاء إختيار صورة لا تتجاوز 2 ميجا");
                                } else if (json.message == 'video so long' || json.message == 'sound so long') {
                                    alert("الرجاء إختيار مقطع لا يتجاوز 20 ميجا");
                                } else if (json.message == 'post failure') {
                                    alert("حدث خطأ أثناء رفع الملف");
                                } else if (json.message == 'invalid file') {
//                                alert("invalid file: "+json.ext);
                                } else if (json.message == 'post success') {
                                    socket.emit("postMediaOnWall", {
                                        url: json.photo,
                                        h: json.h,
                                        type: data.type,
                                        pi: json.pi
                                    });
                                }
                                $("#wall-pb").hide();
                            }
                        } catch (e) {
                        }
                    },
                    error: function () {
                        $("#wall-pb").hide();
                    },
                    xhr: function () {
                        var xhr = new window.XMLHttpRequest();
                        var progressHtml = '<div id="wall-pb" class="progress">' +
                                '<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" ' +
                                'aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">' +
                                '</div><span class="remove glyphicon glyphicon-remove-circle"></span></div>';

                        var pb = $(progressHtml).prependTo("#wall-pregresses");
                        pb.find(".remove").on("click", function () {
                            xhr.abort();
                            pb.find(".progress-bar").animate({width: 0}, 2000, function () {
                                $("#wall-pb").hide();
                            });
                        });

                        xhr.upload.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                percentComplete = parseInt(percentComplete * 100);
                                pb.find(".progress-bar").css("width", percentComplete + "%");

                                if (percentComplete >= 100) {

                                }

                            }
                        }, false);

                        return xhr;
                    }
                });
            });

            socket.on("getPostsResponse", function (data) {
                if (!ch() || mUser.ignoredsOnWall.indexOf(data.user.reg_id) >= 0) return;
                var rmButtonStyle = mUser.canRemoveFromWall ? "" : 'style="display: none;"';

                var mrButton = '<span ' + rmButtonStyle + ' class="wall-msg-remove glyphicon glyphicon-remove-circle"></span>';

                var avatar = (data.user.avatar) ? data.user.avatar : "images/none.png";
                if (data.post.type == 'text' || data.post.type == 'broadcast')
                    var msgHtml = '<div id="wall-post-' + data.post.id + '" data-pi="' + data.post.id + '" data-poster="' + data.user.reg_id + '" class="wall-msg">' +
                            '<img class="wall-user-avatar" src="' + avatar + '" alt="' + data.user.rawUsername + '" width="40" height="40"/>' +
                            '<div><h5 class="wall-user-pseudo">' + Faces.emoticonize(data.user.username, 20) + '</h5>' +
                            '<p class="wall-msg-content">' + Faces.emoticonize(data.post.body, 20) + '</p>' +
                            '<span time="' + data.post.time + '" class="wall-msg-time">' + timeAgo(data.post.time) + '</span>' +
                            '</div>' + mrButton + '</div>';

                if (data.post.type == 'photo')
                    var msgHtml = '<div id="wall-post-' + data.post.id + '" data-pi="' + data.post.id + '" data-poster="' + data.user.reg_id + '" class="wall-msg">' +
                            '<img class="wall-user-avatar" src="' + avatar + '" alt="' + data.user.rawUsername + '" width="40" height="40"/>' +
                            '<div><h5 class="wall-user-pseudo">' + Faces.emoticonize(data.user.username, 20) + '</h5>' +
                            '<p class="wall-msg-content"><img class="wall-photo-post" src="uploads/wall/photos/' + data.post.body + '" ></p>' +
                            '<span time="' + data.post.time + '" class="wall-msg-time">' + timeAgo(data.post.time) + '</span>' +
                            '</div>' + mrButton + '</div>';

                else if (data.post.type == 'sound')
                    var msgHtml = '<div id="wall-post-' + data.post.id + '" data-pi="' + data.post.id + '" data-poster="' + data.user.reg_id + '" class="wall-msg">' +
                            '<img class="wall-user-avatar" src="' + avatar + '" alt="' + data.user.rawUsername + '" width="40" height="40"/>' +
                            '<div><h5 class="wall-user-pseudo">' + Faces.emoticonize(data.user.username, 20) + '</h5>' +
                            '<p class="wall-msg-content"><audio width="100%" controls><source src="uploads/wall/sounds/' + data.post.body + '"></resource></audio></p>' +
                            '<span time="' + data.post.time + '" class="wall-msg-time">' + timeAgo(data.post.time) + '</span>' +
                            '</div>' + mrButton + '</div>';

                else if (data.post.type == 'video')
                    var msgHtml = '<div id="wall-post-' + data.post.id + '" data-pi="' + data.post.id + '" data-poster="' + data.user.reg_id + '" class="wall-msg">' +
                            '<img class="wall-user-avatar" src="' + avatar + '" alt="' + data.user.rawUsername + '" width="40" height="40"/>' +
                            '<div><h5 class="wall-user-pseudo">' + Faces.emoticonize(data.user.username, 20) + '</h5>' +
                            '<p class="wall-msg-content"><video width="100%" controls><source src="uploads/wall/videos/' + data.post.body + '"></resource></video></p>' +
                            '<span time="' + data.post.time + '" class="wall-msg-time">' + timeAgo(data.post.time) + '</span>' +
                            '</div>' + mrButton + '</div>';

                $("#wall-messages").append(msgHtml);
                handelWallPostsRemove();
                posts = true;
                $("button.yt-btn").unbind("click").bind("click", function (e) {
                    converButtonToVideo($(this).data("yid"), this);
                });
            });

            socket.on("textWallResponse", function (data) {
                if (!ch() || mUser.ignoredsOnWall.indexOf(data.user.reg_id) >= 0) return;
                var avatar = (data.user.avatar) ? data.user.avatar : "images/none.png";
                var time = new Date().getTime();
                var rmButtonStyle = mUser.canRemoveFromWall ? "" : 'style="display: none;"';

                var mrButton = '<span ' + rmButtonStyle + ' class="wall-msg-remove glyphicon glyphicon-remove-circle"></span>';

                var msgHtml = '<div id="wall-post-' + data.pi + '" data-pi="' + data.pi + '" data-poster="' + data.user.reg_id + '" class="wall-msg">' +
                        '<img class="wall-user-avatar" src="' + avatar + '" alt="' + data.user.rawUsername + '" width="40" height="40"/>' +
                        '<div><h5 class="wall-user-pseudo">' + Faces.emoticonize(data.user.username, 20) + '</h5>' +
                        '<p class="wall-msg-content" style="color: ' + data.color + '">' + Faces.emoticonize(data.msg, 20) + '</p>' +
                        '<span time="' + time + '" class="wall-msg-time">' + timeAgo(time) + '</span>' +
                        '</div>' + mrButton + '</div>';
                $("#wall-messages").prepend(msgHtml);
                if (!$("#wall-window").hasClass("on") && mUser.reg_id != data.user.reg_id) {
                    $("#toggle-wall").addClass("new");
                    $("#toggle-wall .count").text(parseInt($("#toggle-wall .count").text())+1).show();
                }
                handelWallPostsRemove();
                $("button.yt-btn").unbind("click").bind("click", function (e) {
                    converButtonToVideo($(this).data("yid"), this);
                });
            });

            socket.on("onPhotoPosted", function (data) {
                if (!ch() || mUser.ignoredsOnWall.indexOf(data.user.reg_id) >= 0) return;
                var avatar = (data.user.avatar) ? data.user.avatar : "images/none.png";
                var time = new Date().getTime();
                var rmButtonStyle = mUser.canRemoveFromWall ? "" : 'style="display: none;"';

                var mrButton = '<span ' + rmButtonStyle + ' class="wall-msg-remove glyphicon glyphicon-remove-circle"></span>';

                var msgHtml = '<div id="wall-post-' + data.pi + '" data-pi="' + data.pi + '" data-poster="' + data.user.reg_id + '" class="wall-msg">' +
                        '<img class="wall-user-avatar" src="' + avatar + '" alt="' + data.user.rawUsername + '" width="40" height="40"/>' +
                        '<div><h5 class="wall-user-pseudo">' + Faces.emoticonize(data.user.username, 20) + '</h5>' +
                        '<p class="wall-msg-content"><img class="wall-photo-post" src="uploads/wall/photos/' + data.url + '" ></p>' +
                        '<span time="' + time + '" class="wall-msg-time">' + timeAgo(time) + '</span>' +
                        '</div>' + mrButton + '</div>';
                $("#wall-messages").prepend(msgHtml);
                if (!$("#wall-window").hasClass("on") && mUser.reg_id != data.user.reg_id) {
                    $("#toggle-wall").addClass("new");
                    $("#toggle-wall .count").text(parseInt($("#toggle-wall .count").text())+1).show();
                }
                handelWallPostsRemove();
            });

            socket.on("onSoundPosted", function (data) {
                if (mUser.ignoredsOnWall.indexOf(data.user.reg_id) >= 0) return;
                var avatar = (data.user.avatar) ? data.user.avatar : "images/none.png";
                var time = new Date().getTime();
                var rmButtonStyle = mUser.canRemoveFromWall ? "" : 'style="display: none;"';

                var mrButton = '<span ' + rmButtonStyle + ' class="wall-msg-remove glyphicon glyphicon-remove-circle"></span>';

                var msgHtml = '<div id="wall-post-' + data.pi + '" data-pi="' + data.pi + '" data-poster="' + data.user.reg_id + '" class="wall-msg">' +
                        '<img class="wall-user-avatar" src="' + avatar + '" alt="' + data.user.rawUsername + '" width="40" height="40"/>' +
                        '<div><h5 class="wall-user-pseudo">' + Faces.emoticonize(data.user.username, 20) + '</h5>' +
                        '<p class="wall-msg-content"><audio width="100%" controls><source src="uploads/wall/sounds/' + data.url + '"></resource></audio></p>' +
                        '<span time="' + time + '" class="wall-msg-time">' + timeAgo(time) + '</span>' +
                        '</div>' + mrButton + '</div>';
                $("#wall-messages").prepend(msgHtml);
                if (!$("#wall-window").hasClass("on") && mUser.reg_id != data.user.reg_id) {
                    $("#toggle-wall").addClass("new");
                    $("#toggle-wall .count").text(parseInt($("#toggle-wall .count").text())+1).show();
                }
                handelWallPostsRemove();
            });

            socket.on("onVideoPosted", function (data) {
                if (mUser.ignoredsOnWall.indexOf(data.user.reg_id) >= 0) return;
                var avatar = (data.user.avatar) ? data.user.avatar : "images/none.png";
                var time = new Date().getTime();
                var rmButtonStyle = mUser.canRemoveFromWall ? "" : 'style="display: none;"';

                var mrButton = '<span ' + rmButtonStyle + ' class="wall-msg-remove glyphicon glyphicon-remove-circle"></span>';

                var msgHtml = '<div id="wall-post-' + data.pi + '" data-pi="' + data.pi + '" data-poster="' + data.user.reg_id + '" class="wall-msg">' +
                        '<img class="wall-user-avatar" src="' + avatar + '" alt="' + data.user.rawUsername + '" width="40" height="40"/>' +
                        '<div><h5 class="wall-user-pseudo">' + Faces.emoticonize(data.user.username, 20) + '</h5>' +
                        '<p class="wall-msg-content"><video width="100%" controls><source src="uploads/wall/videos/' + data.url + '"></resource></video></p>' +
                        '<span time="' + time + '" class="wall-msg-time">' + timeAgo(time) + '</span>' +
                        '</div>' + mrButton + '</div>';
                $("#wall-messages").prepend(msgHtml);
                if (!$("#wall-window").hasClass("on") && mUser.reg_id != data.user.reg_id) {
                    $("#toggle-wall").addClass("new");
                    $("#toggle-wall .count").text(parseInt($("#toggle-wall .count").text())+1).show();
                }
                handelWallPostsRemove();
            });

            socket.on("bannerWallResponse", function (data) {
                if (!ch()) return;
                var avatar = (data.user.avatar) ? data.user.avatar : "images/none.png";
                var time = new Date().getTime();
                var mrButton = '';

                if (["master"].indexOf(mUser.role) !== -1) {
                    mrButton = '<span class="wall-banner-remove glyphicon glyphicon-remove-circle"></span>';
                }

                var mrButtonRemove = mUser.canRemoveBanner ? "" : 'style="display: none;"';

                mrButton = '<span ' + mrButtonRemove + ' class="wall-banner-remove glyphicon glyphicon-remove-circle"></span>';

                var msgHtml = '<div id="wall-banner-' + data.pi + '" data-pi="' + data.pi + '" class="wall-banner">' +
                        '<img class="wall-user-avatar" src="' + avatar + '" alt="' + data.user.rawUsername + '" width="40" height="40"/>' +
                        '<div><h5 class="wall-user-pseudo">' + Faces.emoticonize(data.user.username, 20) + '</h5>' +
                        '<p class="wall-banner-content">' + Faces.emoticonize(data.msg, 20) + '</p>' +
                        '<span time="' + time + '" class="wall-banner-time">' + timeAgo(time) + '</span>' +
                        '</div>' + mrButton + '</div>';

                $("#wall-banners").prepend(msgHtml);
                if (!$("#wall-window").hasClass("on") && mUser.reg_id != data.user.reg_id){
                    $("#toggle-wall").addClass("new");
                    $("#toggle-wall .count").text(parseInt($("#toggle-wall .count").text())+1).show();
                }
                handelWallPostsRemove();
                $("button.yt-btn").unbind("click").bind("click", function (e) {
                    converButtonToVideo($(this).data("yid"), this);
                });
            });

            socket.on("getBannersResponse", function (data) {
                if (!ch()) return;
                var mrButton = '';

                if (["master"].indexOf(mUser.role) !== -1) {
                    mrButton = '<span class="wall-banner-remove glyphicon glyphicon-remove-circle"></span>';
                }

                var mrButtonRemove = mUser.canRemoveBanner ? "" : 'style="display: none;"';

                mrButton = '<span ' + mrButtonRemove + ' class="wall-banner-remove glyphicon glyphicon-remove-circle"></span>';

                var avatar = (data.user.avatar) ? data.user.avatar : "images/none.png";

                var msgHtml = '<div id="wall-banner-' + data.post.id + '" data-pi="' + data.post.id + '" class="wall-banner">' +
                        '<img class="wall-user-avatar" src="' + avatar + '" alt="' + data.user.rawUsername + '" width="40" height="40"/>' +
                        '<div><h5 class="wall-user-pseudo">' + Faces.emoticonize(data.user.username, 20) + '</h5>' +
                        '<p class="wall-banner-content">' + Faces.emoticonize(data.post.body, 20) + '</p>' +
                        '<span time="' + data.post.time + '" class="wall-banner-time">' + timeAgo(data.post.time) + '</span>' +
                        '</div>' + mrButton + '</div>';


                $("#wall-banners").append(msgHtml);
                handelWallPostsRemove();
                posts = true;
                $("button.yt-btn").unbind("click").bind("click", function (e) {
                    converButtonToVideo($(this).data("yid"), this);
                });
            });

            socket.on("ignoreds-updated", function (ignoreds, ignoredsOnWall) {
                mUser.ignoreds = ignoreds;
                mUser.ignoredsOnWall = ignoredsOnWall;
                $("#users-online .clickable-user").show();
                $("#conversations .clickable-user").show();
                $("#wall-messages .wall-msg").show();
                for (var i in mUser.ignoreds) {
                    $("#users-online .clickable-user[id='" + mUser.ignoreds[i] + "']").hide();
                    $("#conversations .clickable-user[user_id='" + mUser.ignoreds[i] + "']").hide();
                }
                for (var i in mUser.ignoredsOnWall) {
                    $("#wall-messages .wall-msg[data-poster='" + mUser.ignoredsOnWall[i] + "']").hide();
                }
            });

            socket.removeEventListener("getMoreSettingsResponse").on("getMoreSettingsResponse", function (privilegs) {
                if (privilegs.canSendAds) $("#settings-send-ad").show();
                else $("#settings-send-ad").hide();

                if (privilegs.canPublishBanner) $("#wall-buttons a[data-type='banner']").show();
                else $("#wall-buttons a[data-type='banner']").hide();

                if (privilegs.canCreateRoom) $("#settings-create-room").show();
                else $("#settings-create-room").hide();

                if (privilegs.canEditRoom) $("#settings-edit-room").show();
                else $("#settings-edit-room").hide();

                if (privilegs.canRemoveRoom) $("#settings-remove-room").show();
                else $("#settings-remove-room").hide();

                if (privilegs.canEditRoomsFlag) $("#settings-edit-room #update-room-flag-btn").show();
                else $("#settings-edit-room #update-room-flag-btn").hide();
            });

            function handelWallPostsRemove() {
                var removeButtons = $(".wall-msg-remove");
                $.each(removeButtons, function (key, value) {
                    if (!$(this).hasClass("clickable")) {
                        $(this).addClass("clickable").on("click", function () {
                            var pi = $(this).closest("div[data-pi]").data("pi");
//                        if(confirm("هل أنت متأكد من الحذف؟"))
                            socket.emit("removeWallPost", pi);
                        });
                    }
                });

                var removeButtons = $(".wall-banner-remove");
                $.each(removeButtons, function (key, value) {
                    if (!$(this).hasClass("clickable")) {
                        $(this).addClass("clickable").on("click", function () {
                            var pi = $(this).closest("div[data-pi]").data("pi");
//                        if(confirm("هل أنت متأكد من الحذف؟"))
                            socket.emit("removeWallBanner", pi);
                        });
                    }
                });
            }

            socket.on("wallRoleDelete", function () {
                alert("لا تملك الصلاحيات للحذف من الحائط");
            });

            socket.on("wallPostDeleted", function (id) {
                $("#wall-messages #wall-post-" + id).remove();
            });

            socket.on("wallBannerDeleted", function (id) {
                $("#wall-banners #wall-banner-" + id).remove();
            });

            socket.on("wallRole", function () {
                alert("الإرسال على الحائط مخصص للأعضاء المسجيلن فقط");
            });

            socket.on("wallTimeError", function (time) {
                if (time == 1)
                    alert("المدة الفاصلة بين الرسائل هي دقيقة ");
                else if (time == 2)
                    alert("المدة الفاصلة بين الرسائل هي دقيقتين");
                else
                    alert("المدة الفاصلة بين الرسائل هي" + time + " دقائق");
            });

            socket.on("wallLikesError", function (likes) {
                alert("يجب أن تتوفر على " + likes + " إعجاب على الأقل");
            });

            socket.on('sendPrivateMessageResult', function (data) {
                var cls = "conversation-msg msg-sender";
                var d = new Date();
                var privateMsgHtml = '<div style="color: ' + textColor + '" class="' + cls + '">' +
                        '<img class="chat-msg-avatar" user_id="' + data.user.sender + '" src="' + data.user.avatar + '" width="40" height="40" />' +
                        '<span class="chat-msg-username">' + Faces.emoticonize(data.user.username, 20) + '</span><br />' +
                        '<p class="chat-msg-text">' + Faces.emoticonize(data.msg.message, 20) + '</p>' +
                        '<div class="chat-msg-time" time="' +
                        d.getTime() + '">' + timeAgo(d.getTime()) +
                        '</div></div><div class="clear"></div>';

                $("#chat-box #chat-box-messages").append(privateMsgHtml);
                $("#chat-box #chat-box-messages").animate({scrollTop: $("#chat-box #chat-box-messages .conversation-msg").length * 500}, 0);

                $(".chat-msg-avatar").unbind("click").bind("click", function () {
                    getUserDetails($(this).attr("user_id"));
                });
            });

            socket.on("receivePrivateMessage", function (data) {

                if (data == 'private messaging is closed') {
                    var cls = "conversation-msg msg-sender private-closed";
                    $("#chat-box #chat-box-messages").append('<div dir="rtl" class="' + cls + '">الخاص مغلق</div><div class="clear"></div>');
                    $("#chat-box #chat-box-messages").animate({scrollTop: $("#chat-box #chat-box-messages .conversation-msg").length * 500}, 0);
                    return;
                }

                if (data == 'guest-private-messaging') {
                    var cls = "conversation-msg msg-sender private-closed";
                    $("#chat-box #chat-box-messages").append('<div dir="rtl" class="' + cls + '">الخاص متاح للأعضاء المسجلين فقط</div><div class="clear"></div>');
                    $("#chat-box #chat-box-messages").animate({scrollTop: $("#chat-box #chat-box-messages .conversation-msg").length * 500}, 0);
                    return;
                }

                if (data == 'ignored') {
                    var cls = "conversation-msg msg-sender private-closed";
                    $("#chat-box #chat-box-messages").append('<div dir="rtl" class="' + cls + '">هذا الشخض تجاهلك</div><div class="clear"></div>');
                    $("#chat-box #chat-box-messages").animate({scrollTop: $("#chat-box #chat-box-messages .conversation-msg").length * 500}, 0);
                    return;
                }

                $('#conversations .window-section .clickable-user[user_id="' + data.user.sender + '"]').remove();
                var avatar = (data.user.avatar) ? data.user.avatar : "images/none_large.png";

                if (data.user.sender != mUser.id) {
                    var hiddenStatusClass = data.user.hidden ? " hidden-status" : "";
                    var fantomedStatusClass = data.user.fantome ? " fantomed-status" : "";
                    var color = data.user.statusMark === 0 ? "red" : data.user.statusMark === 1 ? "green" : "orange";

                    $("#conversations .window-section").append(
                            '<div class="clickable-user ' + color + hiddenStatusClass + fantomedStatusClass + '" user_id="' + data.user.sender + '">' +
                            '<img src="' + avatar + '">' +
                            '<h5>' + Faces.emoticonize(data.user.username, 20) + '</h5>' +
                            '<span class="status-mark status-mark-' + data.user.statusMark + hiddenStatusClass + fantomedStatusClass + '"><img src="/images/en_ligne.png"><img src="/images/hors_ligne.png"><img src="/images/msn_mobile.png"></span>' +
                            '</div>'
                    );
                }

                $('#conversations .window-section .clickable-user[user_id="' + data.user.sender + '"]').unbind("click").bind("click", function () {
                    var statusMark = '<span class="status-mark status-mark-' + data.user.statusMark + '"><img src="/images/en_ligne.png"><img src="/images/hors_ligne.png"><img src="/images/msn_mobile.png"></span>';
                    $("#chat-box #chat-box-messages").html("");
                    $("#conversations").hide();
                    socket.emit("getConversationsMessages", data.user.sender);
                    $("#chat-box").attr("user_id", data.user.sender);
                    $("#chat-box header img").attr("src", avatar);
                    $("#chat-box header span.username").html(Faces.emoticonize(data.user.username, 20));
                    $("#chat-box header .status-mark").remove();
                    $(statusMark).insertAfter("#chat-box header span.username");
                    $("#chat-box").show();
                    $("#chat-box footer input[type='text']").focus();
                    if($('#conversations .window-section .clickable-user[user_id="' + data.user.sender + '"]').hasClass("non-read")) {
                        $('#conversations .window-section .clickable-user[user_id="' + data.user.sender + '"]').removeClass("non-read");
                        $("#toggle-conversations .count").text(parseInt($("#toggle-conversations .count").text())-1);
                    }
                    if ($('#conversations .window-section .clickable-user.non-read').length < 1) {
                        $("#toggle-conversations").removeClass("new-conversation-message");
                        $("#toggle-conversations .count").text(0).hide();
                    }
                    currentChat = data.user.sender;
                });

                if (currentChat == data.user.sender) {
                    if (currentChat != mUser.id) {
                        var avatar = (data.user.avatar) ? data.user.avatar : "images/none.png";
                        var cls = (data.user.sender != mUser.id) ? "conversation-msg" : "conversation-msg msg-sender";

                        var privateMsgHtml = renderPrivateMediaHtml({
                            type: data.msg.type,
                            username: Faces.emoticonize(data.user.username, 20),
                            message: data.msg.message,
                            time: data.msg.time,
                            cls: cls,
                            avatar: avatar,
                            color: data.msg.color,
                            id: data.user.sender
                        });

                        $("#chat-box #chat-box-messages").append(privateMsgHtml);
                        $("#chat-box #chat-box-messages").animate({scrollTop: $("#chat-box #chat-box-messages .conversation-msg").length * 500}, 0);
                        handleMediaLoad();

                        $(".chat-msg-avatar").unbind("click").bind("click", function () {
                            getUserDetails($(this).attr("user_id"));
                        });

                    }
                } else {
                    if (mUser.id == data.user.id) {
                        $("#toggle-conversations").addClass("new-conversation-message");
                        $("#toggle-conversations .count").text(parseInt($("#toggle-conversations .count").text())+1).show();
                        $('#conversations .window-section .clickable-user[user_id="' + data.user.sender + '"]').addClass("non-read");
                    }
                }

                socket.emit("privateMessageReceived", {
                    sender: data.user.sender,
                    msg: data.msg.message,
                    color: data.msg.color,
                    type: data.msg.type
                });
            });

            socket.on("setConversationMessages", function (data) {
                var cls = (data.sender != mUser.id) ? "conversation-msg" : "conversation-msg msg-sender";
                var avatar = (data.avatar) ? data.avatar : "images/none.png";

                var privateMsgHtml = renderPrivateMediaHtml({
                    type: data.type,
                    username: Faces.emoticonize(data.username, 20),
                    message: data.msg,
                    time: data.time,
                    cls: cls,
                    avatar: avatar,
                    color: data.color,
                    id: data.sender
                });

                $("#chat-box #chat-box-messages").append(privateMsgHtml);
                $("#chat-box #chat-box-messages").animate({scrollTop: $("#chat-box #chat-box-messages .conversation-msg").length * 500}, 0);
                handleMediaLoad();
                $(".chat-msg-avatar").unbind("click").bind("click", function () {
                    getUserDetails($(this).attr("user_id"));
                });
            });

            socket.on("status-mark-changed", function (id, statusMark) {

                var color = statusMark === 0 ? "red" : statusMark === 1 ? "green" : "orange";
                $("#users-online .clickable-user[id='" + id + "']").removeClass("red").removeClass("orange")
                        .removeClass("green").addClass(color);

                $("#users-online .clickable-user[id='" + id + "'] .status-mark").removeClass("status-mark-0").removeClass("status-mark-1")
                        .removeClass("status-mark-2").addClass("status-mark-" + statusMark);

                $("#conversations .clickable-user[user_id='" + id + "']").removeClass("red").removeClass("orange")
                        .removeClass("green").addClass(color);

                $("#conversations .clickable-user[user_id='" + id + "'] .status-mark").removeClass("status-mark-0").removeClass("status-mark-1")
                        .removeClass("status-mark-2").addClass("status-mark-" + statusMark);

                $("#conversations .clickable-user[user_id='" + id + "'] .status-mark").removeClass("status-mark-0").removeClass("status-mark-1")
                        .removeClass("status-mark-2").addClass("status-mark-" + statusMark);

                $("#chat-box[user_id='" + id + "'] .status-mark").removeClass("status-mark-0").removeClass("status-mark-1")
                        .removeClass("status-mark-2").addClass("status-mark-" + statusMark);

            });

            function startApp(username) {

                socket.emit("loginTry", {
                    username: username,
                    avatar: avatar,
                    country: mUser.country,
                    private_state: prv
                });

                socket.on("guestUserExists", function (username) {
                    $("#guest-name").val(username);
                    makeBorderRed("#guest-name");
                    $("#error-guest-name").text("لا يمكنك الدخول بهذا الإسم لإنه محجوز").show();
                    $("#guest-login input[type='submit']").prop('disabled', false);
                });

            }

            socket.on("newMessage", function (data) {
                if (mUser.ignoreds.indexOf(data.user.id) >= 0) return;
                var avatar = (data.user.avatar) ? data.user.avatar : "images/none.png";
                $("#main-content").append('<div style="font-size: ' + fontSize + 'px" user_id="' + data.user.id + '" class="user-status"><div class="user-status-avatar"><img src="' + avatar + '"/></div><div style="font-size: ' + fontSize + 'px" class="user-status-username"><h5>' + Faces.emoticonize(data.user.username, 20) + '</h5><span class="row-time" time="' + Date.now() + '">' + timeAgo(Date.now()) + '</span><h6 style="font-size: ' + fontSize + 'px; color: ' + data.color + '">' + Faces.emoticonize(data.message, 20) + '</h6><div class="clear"></div></div></div></div>');
                $("#main-content").animate({scrollTop: $("#main-content .user-status").length * 60}, 0);
                if ($("#main-content .user-status").length > 25) {
                    $('#main-content .user-status').get(0).remove();
                }
                $("#main-content .user-status .user-status-avatar").unbind("click").bind("click", function () {
                    getUserDetails($(this).parent().attr("user_id"));
                });
            });

            socket.on("userOut", function (user, sameRoom, time, usersCount, flag, by) {
                $("#users-online #" + user.id).remove();
                if (sameRoom) {
                    if ($("#" + user.id).length == 0) {
                        by = '<span class="by-user">' + by + '</span>';
                        var message = (flag == 1) ? by + "لقد تم طرد هذا المستخدم من طرف " : (flag == 2) ? by + "لقد تم حظر هذا المستخدم من طرف " : "هذا المستخدم غادر الدردشة";
                        addRowMainContent(user.id, Faces.emoticonize(user.username, 20), user.avatar, Faces.emoticonize(message, 25), time);
                    }
                }
                $('#conversations .window-section .clickable-user[user_id="' + user.id + '"]').remove();

                if (!$("#conversations .window-section .clickable-user.non-read").length) {
                    $("#toggle-conversations").removeClass("new-conversation-message");
                    $("#toggle-conversations .count").text(0).hide();
                }

                $("#chat-box[user_id='" + user.id + "']").hide();
                $("#main-footer-bottom-online-count span, #toggle-users-online .count").text(usersCount);
            });

            socket.on("logOut", function () {
                confirmOnExit = false;
                location.reload();
            });

            socket.on("disconnect", function () {
                confirmOnExit = false;
                if (!isAndroidApp)
                    location.reload();
            });

            $("#main-footer-top-send").on("click", function (e) {
                e.preventDefault();
                var message = $("#message-to-send").val().trim();
                if (message)
                    socket.emit("newMessage", message, textColor);
                $("#message-to-send").val("");
            });

            $("#message-to-send").on("keypress", function (e) {
                if (e.keyCode == 13) {
                    var message = $("#message-to-send").val().trim();
                    if (message)
                        socket.emit("newMessage", message, textColor);
                    $("#message-to-send").val("");
                }
            });

            $("#message-to-send").on("focus", function (e) {
                try {
                    $("#chat-box").hide();
                    currentChat = null;
                } catch (e) {
                }
            });

            $("#add-room-submit").on("click", function () {

                var name, max, password, greeting;
                name = $("#add-room-name").val().trim();
                max = $("#add-room-max-memebers").val().trim();
                password = $("#add-room-password").val().trim();
                greeting = $("#add-room-greeting").val().trim();

                if (name.length > 1 && max >= 2 && max <= 200 && password.length >= 6 && greeting.length >= 3) {
                    socket.emit("newRoom", {
                        name: name,
                        max: max,
                        password: password,
                        greeting: greeting
                    });
                }


            });

            socket.on("onUsers", function (data) {
                var cls = (data.onsearch) ? ".searched-users" : ".all-users";
                var hiddenStatusClass = data.user.hidden ? " hidden-status" : "";
                var fantomedStatusClass = data.user.fantome ? " fantomed-status" : "";

                $("#users-online #" + data.user.id).remove();
                var avatar = (data.user.avatar) ? data.user.avatar : "images/none.png";
                var color = data.user.statusMark === 0 ? "red" : data.user.statusMark === 1 ? "green" : "orange";
                var user = null;
                if (data.thisRoom) {
                    user = $("#users-online .this-room" + cls).append('<div data-role="' + data.user.role_id + '" id="' + data.user.id + '" class="clickable-user ' + color + hiddenStatusClass + fantomedStatusClass + '">' +
                    '<img src="' + avatar + '">' +
                    '<h5>' + Faces.emoticonize(data.user.username, 20) + '</h5>' +
                    '<span class="status-mark status-mark-' + data.user.statusMark + hiddenStatusClass + fantomedStatusClass + '"><img src="/images/en_ligne.png"><img src="/images/hors_ligne.png"><img src="/images/msn_mobile.png"></span>' +
                    '</div>');
                } else {
                    user = $("#users-online .other-rooms" + cls).append('<div data-role="' + data.user.role_id + '" id="' + data.user.id + '" class="clickable-user ' + color + hiddenStatusClass + fantomedStatusClass + '">' +
                    '<img src="' + avatar + '">' +
                    '<h5>' + Faces.emoticonize(data.user.username, 20) + '</h5>' +
                    '<span class="status-mark status-mark-' + data.user.statusMark + '"><img src="/images/en_ligne.png"><img src="/images/hors_ligne.png"><img src="/images/msn_mobile.png"></span>' +
                    '</div>');
                }

                makeUserClickable(data.user.id);
                sortUsers();
            });

            socket.removeListener("onRooms").on("onRooms", function (room) {

//            var avatar = (!checkVariable(room.password) && room.password.length > 0) ? "images/lock.png" : "images/comment.png";
                var lock = (!checkVariable(room.password) && room.password.length > 0) ? "<img src='images/lock.png' width='16'/>" : "";
                var avatar = room.flag ? "uploads/rooms/" + room.flag : "uploads/rooms/default.png";
                var locked = (!checkVariable(room.password) && room.password.length > 0) ? "yes" : "no";

                if (!$("#rooms-window .window-section #all-rooms-block .clickable-user[id='" + room.id + "']").length) {

                    var orderBy = room.orderByOnline ? room.onlineCount : room.priority;
                    $("#rooms-window .window-section #all-rooms-block").append('<div locked="' + locked + '" id="' + room.id + '" count="' + orderBy + '" class="clickable-user">' +
                    '<img src="' + avatar + '">' +
                    '<h5>' + lock + room.name + '</h5>' +
                    '<h5 class="right"><span class="room-count">' + room.onlineCount + '</span>/' + room.max + '</h5>' +
                    '</div>');
                    sortRooms();

                }

            });

            socket.on("roomUpdated", function (data) {
                if (data.previsouRoom) {
                    updateRoom(data.previsouRoom, true);
                    updateRoom(data.currentRoom, true);
                } else {
                    updateRoom(data.room, true);
                }
            });

            socket.on("roomEdited", function (rm) {
                var room = $("#rooms-window #all-rooms-block .clickable-user[id='" + rm.id + "']");
                if (room.length) {
//                room.find("img:first-child").attr("src", "uploads/rooms/"+rm.flag);

                    if (rm.password)
                        room.attr("locked", "yes");
                    else
                        room.attr("locked", "no");

                    var lock = rm.password ? "<img src='images/lock.png' width='16'/>" : "";
                    room.find("img + h5").text(lock + rm.name);

                    var count = room.find("h5:last-child");
                    count.text(count.text().split("/")[0] + "/" + rm.max);

                    $("#settings-edit-room form input[name='password']").val("");
                }
            });

            socket.on("room-flag-updated", function (rm) {
                var room = $("#rooms-window #all-rooms-block .clickable-user[id='" + rm.id + "']");
                room.find("img:first-child").attr("src", rm.flag ? "uploads/rooms/" + rm.flag : "uploads/rooms/default.png");
            });

            socket.on("removeRome", function (room) {
                $("#rooms-window .window-section #all-rooms-block #" + room).remove();
            });

            socket.on("changeRoomResponse", function (data) {
                addRowMainContent(data.user.id, Faces.emoticonize(data.user.username, 20), data.user.avatar, '<span class="room-link" room="' + data.currentRoom.id + '">(<span class="glyphicon glyphicon-comment"></span> ' + data.currentRoom.name + ')</span>' + "هذا المستخدم إنتقل إلى ", data.time);
                $("#main-content .user-status .user-status-username .room-link").unbind("click").bind("click", function () {
                    if ($(this).attr("locked") == 'yes' && ["master"].indexOf(mUser.role) === -1) {
                        var password = prompt("الرجاء إدخال كلمة المرور");
                        if (!password) return;
                    } else {
                        var password = '';
                    }
                    socket.emit("changeRoomRequest", {id: $(this).attr("room"), password: password});
                });
            });

            socket.on("go-to-public-room", function () {
                socket.emit("transerToPublicRoom");
            });

            socket.on("roomWelcomeMessage", function (data) {
                var avatar = (data.user.avatar) ? data.user.avatar : "images/none.png";
                $("#main-content").append('<div style="background-color: #def0d8; color: #5b8347; font-size: ' + fontSize + 'px" user_id="' + data.user.id + '" class="user-status">' +
                '<div class="user-status-avatar"><img src="' + avatar + '"/></div>' +
                '<div class="user-status-username"><h5><span class="global-message"><span class="glyphicon glyphicon-heart-empty"></span> رسالة ترحيبية <span class="glyphicon glyphicon-heart-empty"></span></span></h5>' +
                '<span class="row-time" time="' + Date.now() + '">' + timeAgo(Date.now()) + '</span>' +
                '<h6>' + data.message + '</h6><div class="clear"></div></div>' +
                '</div>' +
                '</div>');
                $("#main-content").animate({scrollTop: $("#main-content .user-status").length * 60}, 0);
                if ($("#main-content .user-status").length > 25) {
                    $('#main-content .user-status').get(0).remove();
                }
            });

            window.onbeforeunload = function () {
                if (mUser && mUser.isLogged && confirmOnExit) {
                    return 'هل أنت متأكد من مغادرة الدردشة؟';
                }
            };

//*********** BEGING Handling Change Room Request **********************

            socket.on("roomFull", function () {
                alert("هذه الغرفة ممتلئة");
            });

            socket.on("roomWrongPassword", function () {
                alert("الرجاء إدخال كلمة المرور الصحيحة");
            });

            socket.on("roomOnlyForSupers", function () {
                alert("هذه الغرفة مخصصة للمراقبين فقط");
            });

//*********** END Handling Change Room Request *************************

            socket.on("signupUserExists", function (username) {
                $("#member-name-signin").val(username);
                makeBorderRed("#member-name-signin");
                $("#error-member-name-signin").text("لا يمكنك الدخول بهذا الإسم لإنه محجوز").show();
            });

//******************* BEGIN CONTROLLER ADMIN AREA ****************************************

            socket.on("banSuccess", function () {
                $("#user-details-window").hide();
                alert("لقد تم حظر هذا المستخدم");
            });

            socket.on("orderNotify", function (data) {
                var avatar = (data.user.avatar) ? data.user.avatar : "images/none.png";
                $("#main-content").append('<div style="background-color: #edd1d0; color: #af4c45; font-size: ' + fontSize + 'px" user_id="' + data.user.id + '" class="user-status">' +
                '<div class="user-status-avatar"><img src="' + avatar + '"/></div>' +
                '<div class="user-status-username"><h5>' + Faces.emoticonize(data.user.username, 20) + '<span class="order-notification"><span class="glyphicon glyphicon-bell"></span>تنبيه<span class="glyphicon glyphicon-bell"></span></span></h5>' +
                '<span class="row-time" time="' + Date.now() + '">' + timeAgo(Date.now()) + '</span>' +
                '<h6>' + Faces.emoticonize(data.message, 20) + '</h6><div class="clear"></div></div>' +
                '</div>' +
                '</div>');
                $("#main-content").animate({scrollTop: $("#main-content .user-status").length * 60}, 0);
                if ($("#main-content .user-status").length > 25) {
                    $('#main-content .user-status').get(0).remove();
                }
                $("#main-content .user-status .user-status-avatar").unbind("click").bind("click", function () {
                    getUserDetails($(this).parent().attr("user_id"));
                });
            });

            socket.on("disable-notification", function () {
                $("#orders-box-send-notification").addClass("disabled");
            });

            socket.on("receivedAd", function (data) {
                if (!mUser.isLogged || mUser.ignoreds.indexOf(data.user.id) >= 0) return;
                var avatar = (data.user.avatar) ? data.user.avatar : "images/none.png";
                $("#main-content").append('<div style="background-color: #daecf7; color: #5b85ab; font-size: ' + fontSize + 'px" user_id="' + data.user.id + '" class="user-status">' +
                '<div class="user-status-avatar"><img src="' + avatar + '"/></div>' +
                '<div class="user-status-username"><h5>' + Faces.emoticonize(data.user.username, 20) + '<span class="global-message"><span class="glyphicon glyphicon-bullhorn"></span>رسالة جماعية<span class="glyphicon glyphicon-bullhorn"></span></span></h5>' +
                '<span class="row-time" time="' + Date.now() + '">' + timeAgo(Date.now()) + '</span>' +
                '<h6>' + Faces.emoticonize(data.message, 20) + '</h6><div class="clear"></div></div>' +
                '</div>' +
                '</div>');
                $("#main-content").animate({scrollTop: $("#main-content .user-status").length * 60}, 0);
                if ($("#main-content .user-status").length > 25) {
                    $('#main-content .user-status').get(0).remove();
                }
                $("#main-content .user-status .user-status-avatar").unbind("click").bind("click", function () {
                    getUserDetails($(this).parent().attr("user_id"));
                });
            });

            socket.on("selfMessage", function (msg) {
                if (!mUser.isLogged) return;
                var avatar = "images/self-msg.png";
                $("#main-content").append('<div style="background-color: #d9f3cf; color: #4aa272; font-size: ' + fontSize + 'px" user_id="0" class="self-message-block">' +
                '<div class="user-status-avatar"><img src="' + avatar + '"/></div>' +
                '<div class="user-status-username"><h5><span class="self-message"><span class="glyphicon glyphicon-envelope"></span> رسالة تلقائية <span class="glyphicon glyphicon-envelope"></span></span></h5>' +
                '<span class="row-time" time="' + Date.now() + '">' + timeAgo(Date.now()) + '</span>' +
                '<h6>' + Faces.emoticonize(msg, 20) + '</h6><div class="clear"></div></div>' +
                '</div>' +
                '</div>');
                $("#main-content").animate({scrollTop: $("#main-content .user-status").length * 60}, 0);
                if ($("#main-content .user-status").length > 25) {
                    $('#main-content .user-status').get(0).remove();
                }
                $("#main-content .user-status .user-status-avatar").unbind("click").bind("click", function () {
                    getUserDetails($(this).parent().attr("user_id"));
                });
            });

            socket.on("moreSettings", function (data, aFantomed, allowHideStar, privateMessaging) {
                $("#settings-orders-area").html(data);

                if (privateMessaging) {
                    $("#settings-block-chat input[type='checkbox']")[0].checked = privateMessaging;
                }

                if (allowHideStar && allowHideStar.hideStar) {
                    $("#settings-hide-star").show();
                    var checkbox = $('#settings-window #settings-hide-star input[type="checkbox"]');
                    checkbox.prop('checked', allowHideStar.val);
                    $('#settings-window #settings-hide-star').on('click', function () {
                        var checkbox = $('#settings-window #settings-hide-star input[type="checkbox"]');
                        checkbox.prop('checked', !checkbox.prop('checked'));
                        socket.emit('settingsHideStar', checkbox.prop('checked'));
                    });
                }

                $("#settings-name-color").on("change", function () {
                    var color = $(this).val();
                    if (color) {
                        socket.emit("settingsNameColor", color);
                        if (mUser.role == 'guest')
                            setCookie("nameColor", color, 360);
                        $("#settings-window").show();
                    }
//                $("#color-picker").toggleClass("color-picker-displayed");
//                colorPickerFor = "settingsNameColor";
                });

                $("#settings-name-bg-color").on("change", function () {
                    var color = $(this).val();
                    if (color) {
                        socket.emit("settingsNameBgColor", color);
                        if (mUser.role == 'guest')
                            setCookie("nameBgColor", color, 360);
                        $("#settings-window").show();
                    }
//                $("#color-picker").toggleClass("color-picker-displayed");
//                colorPickerFor = "settingsNameBgColor";
                });

                $("#settings-send-ad").on("click", function (e) {
                    e.preventDefault();
                    var adText = prompt("الرجاء إدخال نص الإعلان");
                    if (adText) {
                        socket.emit("settingsSendAd", adText);
                    }
                });

                $("#settings-remove-name-bg-color").on("click", function () {
                    socket.emit("settingsDeleteNameBgColor");
                });

                $("#settings-change-name").on("click", function (e) {
                    e.preventDefault();
                    var newName = prompt("الرجاء إدخال الإسم الجديد");
                    if (newName) {
                        socket.emit("settingsChangeName", newName);
                    }
                });

                $('#settings-window #settings-toggle-hidden').on('click', function () {
                    var checkbox = $('#settings-window #settings-toggle-hidden input[type="checkbox"]');
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    var state = checkbox.prop('checked');
                    socket.emit('settingsToggleHidden', state);
                });

            });

            socket.on("usernameChanged", function (name, id, roleId) {
                $("#users-online #" + id + " h5").html(Faces.emoticonize(name, 20));
                $("#users-online #" + id).attr("data-role", roleId);
                $("#conversations div[user_id='" + id + "'] h5").html(Faces.emoticonize(name, 20));
                $(".user-status[user_id='" + id + "'] .user-status-username h5").html(Faces.emoticonize(name, 20));
                $("#chat-box[user_id='" + id + "'] header .username").html(Faces.emoticonize(name, 20));
                sortUsers();
            });

            socket.on("getUserDetails", function (user, orders, likeControls) {

                $("#user-details-window").toggle();

                if (orders.length == 0) {
                    $("#orders-box").hide();
                } else {
                    $("#orders-box").show();
                }

                var avatar = (user.avatar) ? user.avatar : "images/none_large.png";

                $("#user-details-window").attr("user_id", user.id);
                $("#user-details-window .window-section .avatar").attr("src", avatar);
                $("#user-details-window .window-section .reputation").text(user.reputation);
                $("#user-details-window .window-section #send-message-to-user").attr("user_id", user.id);
                $("#user-details-window .window-section #user-details-pseudo").html(Faces.emoticonize(user.username, 20));
                $("#user-details-window .window-section #user-details-room").text(user.roomName);
                $("#user-details-window .window-section #user-details-country").attr("src", user.flag);
                $("#orders-box").html(orders);
                $("#like-controls").html(likeControls);

                if (user.realNickname) {
                    $("#user-details-window .window-section #real-nickname").css("display", "block")
                            .find("#user-details-real-pseudo").text(user.realNickname);
                }

                $("#like-controls a").on("click", function () {
                    socket.emit("orderControlerLikes", $(this).closest("div[user_id]").attr("user_id"), $(this).data("like"));
                });

                $("#orders-box-upgrade").on("change", function () {
                    if ($(this).val() == 0) return;
                    socket.emit("orderUpgrade", $(this).closest("div[user_id]").attr("user_id"), $(this).val());
                });

                $("#orders-box-ban").on('click', function () {
//                if(confirm("هل أنت متأكد من حظر هذا المستخدم؟"))
                    socket.emit("orderBan", $(this).closest("div[user_id]").attr("user_id"), '');
                });

                $("#orders-box-expel").on('click', function () {
//                if(confirm("هل أنت متأكد من طرد هذا المستخدم؟"))
                    socket.emit("orderExpel", $(this).closest("div[user_id]").attr("user_id"));
                });

                $("#orders-box-send-notification").on('click', function () {
                    if ($(this).hasClass("disabled")) {
                        return alert("لا يمكنك إرسال أكثر من تنبيه لنفس الشخص");
                    }
                    var notification = prompt("الرجاء إدخال رسالة التنبيه");
                    if (notification.length == 0) {
                        alert("الرسالة فارغة");
                    } else if (notification.length < 5) {
                        alert("أقل حد لرسالة التنبيه هو 05 أحرف");
                    } else {
                        socket.emit("orderNotify", $(this).closest("div[user_id]").attr("user_id"), notification);
                    }
                });

                $("#orders-box-change-nickname").on('click', function () {
                    var newName = prompt("الرجاء إدخال الإسم الجديد");
                    if (newName.length < 1) {
                        return;
                    } else if (newName.length < 5) {
                        alert("الإسم يجب أن تتكون من 5 أحرف على الأقل");
                    } else {
                        socket.emit("orderNickname", $(this).closest("div[user_id]").attr("user_id"), newName);
                    }
                });

                $("#orders-reputation").unbind("click").bind('click', function () {
                    socket.emit("orderReputation", $(this).closest("div[user_id]").attr("user_id"));
                });

                $("#orders-box-reveal-names").on("click", function () {
                    socket.emit("orderReveailNamesRequest", $(this).closest("div[user_id]").attr("user_id"));
                });

                $("#orders-box-remove-avatar").on('click', function () {
//                if(confirm("هل أنت متأكد من حذف الصورة؟"))
                    socket.emit("orderRemoveAvatar", $(this).closest("div[user_id]").attr("user_id"));
                });

                $("#orders-box-name-color").on('change', function () {
                    var color = $(this).val();
                    if (color) {
                        socket.emit("orderNameColor", $(this).data("id"), color);
                        $("#user-details-window").show();
                    }
//                $("#color-picker").toggleClass("color-picker-displayed");
//                colorPickerFor = "ordersNameColor";
//                orderNameColorFor = $(this).closest("div[user_id]").attr("user_id");
                });

                $("#orders-box-name-bg-color").on('change', function () {
                    var color = $(this).val();
                    if (color) {
                        socket.emit("ordeNameBgColor", $(this).data("id"), color);
                        $("#user-details-window").show();
                    }
//                $("#color-picker").toggleClass("color-picker-displayed");
//                colorPickerFor = "ordersNameBgColor";
//                orderNameBgColorFor = $(this).closest("div[user_id]").attr("user_id");
                });

                $("#orders-box-send-gift").on('click', function () {
                    $("#gift-picker").toggleClass("gift-picker-displayed");
                    giftPickerFor = $(this).closest("div[user_id]").attr("user_id");
                });

                $("#orders-box-ignore").on("change", function () {
                    socket.emit("orderIgnore", $(this).closest("div[user_id]").attr("user_id"), $(this)[0].checked);
                });

                $("#orders-box-change-room").on("change", function () {
                    socket.emit("orderChangeRoom", $(this).closest("div[user_id]").attr("user_id"), $(this).val());
                });

                $("#user-details-window .window-section #go-to-users-room").attr("room", user.room).on("click", function (e) {
                    e.preventDefault();
                    if ($(this).attr("locked") == 'yes' && ["master"].indexOf(mUser.role) === -1) {
                        var password = prompt("الرجاء إدخال كلمة المرور");
                        if (!password) return;
                    } else {
                        var password = '';
                    }
                    socket.emit("changeRoomRequest", {id: $(this).attr("room"), password: password});
                });
                $("#user-details-window .window-section #send-message-to-user").unbind("click").bind("click", function (e) {
                    e.preventDefault();
                    var statusMark = '<span class="status-mark status-mark-' + user.statusMark + '"><img src="/images/en_ligne.png"><img src="/images/hors_ligne.png"><img src="/images/msn_mobile.png"></span>';
                    $("#user-details-window").hide();
                    $("#chat-box #chat-box-messages").html("");
                    socket.emit("getConversationsMessages", user.id);
                    $("#chat-box").attr("user_id", user.id);
                    $("#chat-box header img").attr("src", avatar);
                    $("#chat-box header span.username").html(Faces.emoticonize(user.username, 20));
                    $("#chat-box header .status-mark").remove();
                    $(statusMark).insertAfter("#chat-box header span.username");
                    $("#chat-box").show();
                    $("#chat-box .close-chat-window").unbind("click").bind("click", function (e) {
                        e.preventDefault();
                        $("#chat-box").hide();
                        socket.emit("close-conversation", currentChat);
                        $('#conversations .window-section .clickable-user[user_id="' + currentChat + '"]').remove();
                        currentChat = null;
                    });
                    $("#chat-box .minimize-chat-window").unbind("click").bind("click", function (e) {
                        e.preventDefault();
                        $("#chat-box").hide();
                        currentChat = null;
                    });
                    $("#chat-box footer input[type='text']").focus();
                    currentChat = user.id;
                });

                jscolor.installByClassName("jscolor");

            });

            socket.on("orderReveailNamesResponse", function (names, user) {
                renderRevealNames(names, user);
            });

            socket.on("no-user-details", function () {
                $("#user-details-window").hide();
            });

            socket.on("no-permissions", function () {
                alert("لا تملك الصلاحيات للقيام بهذه العملية");
            });

            socket.on("alert", function (msg) {
                alert(msg);
            });

            socket.on("not-authorized", function (event, interval, unit) {
                var msg = "لا تملك الصلاحيات للقيام بهذه العملية";
                if (event == "orders_ban") msg = "يمكنك الحظر " + interval + " مرة في " + unit;
                if (event == "orders_excpel") msg = "يمكنك الطرد " + interval + " مرة في " + unit;
                if (event == "orders_warrning") msg = "يمكنك التنبي " + interval + " مرة في " + unit;
                if (event == "orders_change_decoartion") msg = "يمكنك تغيير الإسم " + interval + " مرة في " + unit;
                if (event == "orders_excpel_from_room") msg = "يمكنك الطرد من الغرفة " + interval + " مرة في " + unit;
                if (event == "orders_remove_avatar") msg = "يمكنك حذف الصور الشخصية " + interval + " مرة في " + unit;
                if (event == "orders_gift") msg = "يمكنك منح الهدايا " + interval + " مرة في " + unit;
                if (event == "room_create") msg = "يمكنك إنشاء الغرف " + interval + " مرة في " + unit;
                if (event == "room_edit") msg = "يمكنك تعديل الغرف " + interval + " مرة في " + unit;
                if (event == "room_remove") msg = "يمكنك حذف الغرف " + interval + " مرة في " + unit;
                if (event == "send_ad") msg = "يمكنك إرسال الإعلانات " + interval + " مرة في " + unit;
                if (event == "wall_remove") {
                    msg = "يمكنك الحذف من الحائط " + interval + " مرة في " + unit;
                    $(".wall-msg-remove").hide();
                    mUser.canRemoveFromWall = false;
                }
                if (event == "wall_interval") msg = "يمكنك النشر على الحائط " + interval + " مرة في " + unit;
                if (event == "orders_ignore") {
                    msg = "لا يمكنك تجاهل هذا الشخص";
                    alert(msg);
                    $("#orders-box-ignore").trigger("click");
                    return;
                }
                alert(msg);
            });

            socket.on("likeOrdered", function (val) {
                if (typeof val == "boolean") {
                    var reputation = $("#user-details-window #orders-reputation span span");
                    if (val) reputation.text(parseInt(reputation.text()) + 1);
                    else reputation.text(parseInt(reputation.text()) - 1);
                }
            });

            socket.on("maxBanReached", function (num, dayOrHourFlag) {
                var dayOrHour = (dayOrHourFlag == 'day') ? ' اليوم' : ' الساعة';
                alert("يمكنك فقط حظر " + num + " زوار في " + dayOrHour);
            });

            socket.on("maxExpelReached", function (num, dayOrHourFlag) {
                var dayOrHour = (dayOrHourFlag == 'day') ? ' اليوم' : ' الساعة';
                alert("يمكنك فقط طرد " + num + " زوار في" + dayOrHour);
            });

            socket.on("maxAdsReached", function (num, dayOrHourFlag) {
                var dayOrHour = (dayOrHourFlag == 'day') ? ' اليوم' : ' الساعة';
                alert("يمكن فقط إرسال " + num + " إعلانات في " + dayOrHour);
            });

            socket.on("likesOnMinute", function (interval) {
                try {
                    if (interval == 1)
                        alert("يمكنك التصويت مرة واحدة كل دقيقة");
                    else if (interval == 2)
                        alert("يمكنك التصويت مرة واحدة كل دقيقتين");
                    else if (interval > 2)
                        alert("يمكنك التصويت مرة واحدة كل " + interval + " دقائق");
                } catch (e) {
                }
            });

            socket.on("gotLiked", function (data) {
                if (mUser.ignoreds.indexOf(data.user_id) >= 0) return;
                var avatar = "images/heart.png";
                $("#main-content").append('<div style="font-size: ' + fontSize + 'px; background-color: #FFDCDC;" class="user-status">' +
                '<div class="user-status-avatar"><img src="' + avatar + '"/></div>' +
                '<div style="font-size: ' + fontSize + 'px" class="user-status-username"><h5>' + Faces.emoticonize(data.username, 20) + '</h5>' +
                '<span class="row-time" time="' + Date.now() + '">' + timeAgo(Date.now()) + '</span>' +
                '<h6>' + Faces.emoticonize('ف8 هذا المستخدم قام بالإعجاب بك ف8', 20) + '</h6><div class="clear"></div></div>' +
                '</div>' +
                '</div>');
                $("#main-content").animate({scrollTop: $("#main-content .user-status").length * 60}, 0);
                if ($("#main-content .user-status").length > 25) {
                    $('#main-content .user-status').get(0).remove();
                }
            });

            socket.on("yourNameChanged", function (user) {
                var avatar = (user.avatar) ? user.avatar : "images/none.png";
                $("#main-content").append('<div style="background-color: #FBE4CD; color: #A56929; font-size: ' + fontSize + 'px" user_id="' + user.id + '" class="user-status">' +
                '<div class="user-status-avatar"><img src="' + avatar + '"/></div>' +
                '<div class="user-status-username"><h5>' + Faces.emoticonize(user.username, 20) + '</h5>' +
                '<span class="row-time" time="' + Date.now() + '">' + timeAgo(Date.now()) + '</span>' +
                '<h6>هذا المستخدم قام بتغيير إسمك</h6><div class="clear"></div></div></div></div>');

                $("#main-content").animate({scrollTop: $("#main-content .user-status").length * 60}, 0);
                if ($("#main-content .user-status").length > 25) {
                    $('#main-content .user-status').get(0).remove();
                }
                $("#main-content .user-status .user-status-avatar").unbind("click").bind("click", function () {
                    getUserDetails($(this).parent().attr("user_id"));
                });
            });

            socket.on("yourNameColorChanged", function (user) {
                console.log(user);
                var avatar = (user.avatar) ? user.avatar : "images/none.png";
                $("#main-content").append('<div style="background-color: #FBE4CD; color: #A56929; font-size: ' + fontSize + 'px" user_id="' + user.id + '" class="user-status">' +
                '<div class="user-status-avatar"><img src="' + avatar + '"/></div>' +
                '<div class="user-status-username"><h5>' + Faces.emoticonize(user.username, 20) + '</h5>' +
                '<span class="row-time" time="' + Date.now() + '">' + timeAgo(Date.now()) + '</span>' +
                '<h6>هذا المستخدم قام بتغيير لون إسمك</h6><div class="clear"></div></div></div></div>');

                $("#main-content").animate({scrollTop: $("#main-content .user-status").length * 60}, 0);
                if ($("#main-content .user-status").length > 25) {
                    $('#main-content .user-status').get(0).remove();
                }
                $("#main-content .user-status .user-status-avatar").unbind("click").bind("click", function () {
                    getUserDetails($(this).parent().attr("user_id"));
                });
            });

            socket.on("yourNameBgColorChanged", function (user) {
                console.log(user);
                var avatar = (user.avatar) ? user.avatar : "images/none.png";
                $("#main-content").append('<div style="background-color: #FBE4CD; color: #A56929; font-size: ' + fontSize + 'px" user_id="' + user.id + '" class="user-status">' +
                '<div class="user-status-avatar"><img src="' + avatar + '"/></div>' +
                '<div class="user-status-username"><h5>' + Faces.emoticonize(user.username, 20) + '</h5>' +
                '<span class="row-time" time="' + Date.now() + '">' + timeAgo(Date.now()) + '</span>' +
                '<h6>هذا المستخدم قام بتغيير لون خلفية إسمك</h6><div class="clear"></div></div></div></div>');

                $("#main-content").animate({scrollTop: $("#main-content .user-status").length * 60}, 0);
                if ($("#main-content .user-status").length > 25) {
                    $('#main-content .user-status').get(0).remove();
                }
                $("#main-content .user-status .user-status-avatar").unbind("click").bind("click", function () {
                    getUserDetails($(this).parent().attr("user_id"));
                });
            });

        });

//******************* END CONTROLLER ADMIN AREA ******************************************

//************************ BEGIN FUNCTIONS SECTION ***************************************

        function renderRevealNames(names, u) {
            if (!names) return;
            var avatar = (u.avatar) ? u.avatar : "images/none.png";
            var modal = $('#reveal-names-modal');
            modal.find(".modal-title .avatar").attr("src", avatar);
            modal.find(".modal-title .username").html(Faces.emoticonize(u.username, 20));
            var namesHtml = '';
            for (var i in names) {
                namesHtml += '<div class="name-block"><p><span class="key">العضو</span><span class="value">' + names[i].username + '</span></p>';
                namesHtml += '<p><span class="key">الزخرفة</span><span class="value">' + names[i].decoration + '</span></p>';
                namesHtml += '<p><span class="key">الدولة</span><span class="value"><img src="images/flags/' + names[i].country + '.png" /></span></p>';
                namesHtml += '<p><span class="key">الأيبي</span><span class="value">' + names[i].ip + '</span></p>';
                namesHtml += '<p><span class="key">الجهاز</span><span class="value">' + names[i].device + '</span></p></div>';
            }
            modal.find(".modal-body .names").html(namesHtml);
            modal.modal();
        }

        function makeBorderRed(selector) {
            $(selector).css("border", "red solid 1px");
        }

        function handleMediaLoad() {
            $(".conversation-msg audio, .conversation-msg video, .conversation-msg img").on("load", function () {
                $("#chat-box #chat-box-messages").animate({scrollTop: $("#chat-box #chat-box-messages .conversation-msg").length * 500}, 0)
            });
        }

        function resetBorder(selector) {
            $(selector).css("border", "#ccc solid 1px");
        }

        function deleteDuplicateSpaces(str) {
            return str.replace(/\s+/g, ' ');
        }

        function getDevice() {
            var device = "desktop";
            if (navigator.userAgent.match(/Android|BlackBerry|iPhone|iPad|iPod|Opera Mini|IEMobile/i)) {
                device = "mobile";
            }
            return device;
        }

        function ch() {
            return true;
            return location.href.indexOf("galaxy") !== -1 ? true : false;
        }

        function arrayCount(array) {
            var i = 0;
            for (var k in array) {
                i++;
            }
            return i;
        }

        function updateRoom(room, sort) {
            if (!ch()) return;
//            var avatar = (!checkVariable(room.password) && room.password.length > 0) ? "images/lock.png" : "images/comment.png";
            var lock = (room && room.password && room.password.length > 0) ? "<img src='images/lock.png' width='16'/>" : "";
            var avatar = room.flag ? "uploads/rooms/" + room.flag : "uploads/rooms/default.png";
            var locked = (room && room.password && room.password.length > 0) ? "yes" : "no";
            var orderBy = room.orderByOnline ? room.onlineCount : room.priority;

            $("#rooms-window .window-section #all-rooms-block #" + room.id).html('<img locked="' + locked + '" src="' + avatar + '">' +
            '<h5>' + lock + room.name + '</h5>' +
            '<h5 class="right">' + room.onlineCount + '/' + room.max + '</h5>').attr("count", orderBy);

            if (sort) sortRooms();

        }

        function addRowMainContent(id, username, avtr, msg, time) {
            if (!mUser.isLogged) return;
            if (!ch() || mUser.ignoreds.indexOf(id) >= 0) return;
            var avatar = (avtr) ? avtr : "images/none.png";
            $("#main-content").append('<div style="font-size: ' + fontSize + 'px" user_id="' + id + '" class="user-status">' +
            '<div class="user-status-avatar"><img src="' + avatar + '"/></div>' +
            '<div style="font-size: ' + fontSize + 'px" class="user-status-username"><h5>' + username + '</h5>' +
            '<span class="row-time" time="' + Date.now() + '">' + timeAgo(Date.now()) + '</span>' +
            '<h6>' + msg + '</h6><div class="clear"></div></div>' +
            '</div>' +
            '</div>');
            $("#main-content").animate({scrollTop: $("#main-content .user-status").length * 60}, 0);
            if ($("#main-content .user-status").length > 25) {
                $('#main-content .user-status').get(0).remove();
            }
            $("#main-content .user-status .user-status-avatar").unbind("click").bind("click", function () {
                getUserDetails($(this).parent().attr("user_id"));
            });
        }

        function makeUserClickable(id) {
            if (!ch()) return;
            $("#users-online .window-section #" + id).unbind("click").bind("click", function () {
                getUserDetails(id);
            });
        }

        function getUserDetails(id) {
            if (!ch()) return;
            socket.emit("getUserDetails", id);
        }

        function sortUsers() {
            if (!ch()) return;

            var thisRoom = $('#users-online .this-room.all-users');
            thisRoom.find('.clickable-user').sort(function (a, b) {
                return b.dataset.role - a.dataset.role;
            }).appendTo(thisRoom);

            var otherRooms = $('#users-online .other-rooms.all-users');
            otherRooms.find('.clickable-user').sort(function (a, b) {
                return b.dataset.role - a.dataset.role;
            }).appendTo(otherRooms);

        }

        function sortRooms() {
            $("#rooms-window .window-section .clickable-user").unbind("click").bind("click", function() {
                if($(this).attr("locked") == 'yes' && ["master"].indexOf(mUser.role) === -1) {
                    var password = prompt("الرجاء إدخال كلمة المرور");
                    if(!password) return;
                } else {
                    var password = '';
                }
                socket.emit("changeRoomRequest", {id: $(this).attr("id"), password: password});
            });
            $("#toggle-rooms .count").text($("#rooms-window .window-section .clickable-user").length);
            return;
//            $("#rooms-window .window-section #all-rooms-block .clickable-user").sort(function (a, b) {
//                return parseInt($(a).attr("count")) > parseInt($(b).attr("count"));
//            }).each(function () {
//                var elem = $(this);
//                elem.remove();
//                $(elem).prependTo("#rooms-window .window-section #all-rooms-block").on("click", function () {
//                    if ($(this).attr("locked") == 'yes' && ["master"].indexOf(mUser.role) === -1) {
//                        var password = prompt("الرجاء إدخال كلمة المرور");
//                        if (!password) return;
//                    } else {
//                        var password = '';
//                    }
//                    socket.emit("changeRoomRequest", {id: $(this).attr("id"), password: password});
//                });
//            });
        }

        function renderOnlineUser(user) {
            var avatar = (user.avatar) ? user.avatar : "images/none.png";
            var country = "/images/flags/"+user.countryCode+".png";
            var color = user.statusMark === 0 ? "red" : user.statusMark === 1 ? "green" : "orange";

            var useHtml = '<div id="' + user.id + '" class="clickable-user ' + color + '">' +
                '<img src="' + avatar + '">' +
                '<h5>' + Faces.emoticonize(user.username, 20) + '</h5>' +
                '<img src="'+country+'" class="country-flag"/>'+
                '<span class="status-mark status-mark-' + user.statusMark + '"><img src="/images/en_ligne.png"><img src="/images/hors_ligne.png"><img src="/images/msn_mobile.png"></span>' +
                '</div>';

            $("#online-users").append(useHtml);
        }

        function checkVariable(v) {
            if (!ch()) return true;
            if (v == undefined || v == null) {
                return true;
            } else {
                return false;
            }
        }

        function timeAgo(time) {
            if (!ch()) return;
            var s = Math.round(Math.abs(Date.now() - time) / 1000);

            if (s < 59) {
                return "الآن";
            } else if (s / 60 < 60) {
                return parseInt(s / 60) + "د";
            } else if (s / 3600 < 24) {
                return parseInt(s / 3600) + "س";
            } else if (s / (3600 * 24) < 30) {
                return parseInt(s / (3600 * 24)) + "ي";
            } else if (s / (3600 * 24 * 30) < 12) {
                return parseInt(s / (3600 * 24 * 30)) + "ش";
            } else {
                return parseInt(s / (3600 * 24 * 30 * 12)) + "ع";
            }

        }

        function emoticonize(data, width) {
            for (var i = 0; i < 10; i++) {
                var regex = new RegExp('(ف|ض)([0-9]{1,2})');
                var match = regex.exec(data);

                if (match) {
                    data = data.replace(regex, '<img width="' + (width - 4) + '" src="images/emoticons/' + match[2] + '.gif" />')
                }
            }

            return data;
        }

        setInterval(function () {
            $.each($('.row-time, .chat-msg-time, .wall-msg-time'), function (index, value) {
                $(this).text(timeAgo($(this).attr('time')));
            });
        }, 10000);

        function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            var expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + "; " + expires;
        }

        function renderPrivateMediaHtml(data) {
            var html = '';
            if (data.type == 'photo')
                html = '<div style="color: ' + data.color + '" class="' + data.cls + '">' +
                '<img class="chat-msg-avatar" user_id="' + data.id + '" src="' + data.avatar + '" width="40" height="40" />' +
                data.username + '<br /><img src="' + data.message + '" alt="" class="media"/>' +
                '<div class="chat-msg-time" time="' +
                data.time + '">' + timeAgo(data.time) +
                '</div></div><div class="clear"></div>';

            else if (data.type == 'video')
                html = '<div style="color: ' + data.color + '" class="' + data.cls + '">' +
                '<img class="chat-msg-avatar" user_id="' + data.id + '" src="' + data.avatar + '" width="40" height="40" />' +
                data.username + '<br /><video controls class="media"><source src="' + data.message + '"/></video>' +
                '<div class="chat-msg-time" time="' +
                data.time + '">' + timeAgo(data.time) +
                '</div></div><div class="clear"></div>';

            else if (data.type == 'sound')
                html = '<div style="color: ' + data.color + '" class="' + data.cls + '">' +
                '<img class="chat-msg-avatar" user_id="' + data.id + '" src="' + data.avatar + '" width="40" height="40" />' +
                data.username + '<br /><audio controls class="media"><source src="' + data.message + '"/></audio>' +
                '<div class="chat-msg-time" time="' +
                data.time + '">' + timeAgo(data.time) +
                '</div></div><div class="clear"></div>';

            else
                html = '<div style="color: ' + data.color + '" class="' + data.cls + '">' +
                '<img class="chat-msg-avatar" user_id="' + data.id + '" src="' + data.avatar + '" width="40" height="40" />' +
                '<span class="chat-msg-username">' + data.username + '</span><br />' +
                '<p class="chat-msg-text">' + Faces.emoticonize(data.message, 25) + '</p>' +
                '<div class="chat-msg-time" time="' +
                data.time + '">' + timeAgo(data.time) +
                '</div></div><div class="clear"></div>';

            return html;
        }

        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1);
                if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
            }
            return "";
        }

        function UrlExists(url) {
            var http = new XMLHttpRequest();
            http.open('HEAD', url, false);
            http.send();
            return http.status != 404;
        }

        function converButtonToVideo(id, e) {
            var link = "https://www.youtube.com/embed/" + id + "?autoplay=1";
            $('<iframe width="95%" style="max-width:240px;" height="200" src="' + link + '" frameborder="0" allowfullscreen></iframe><br />').insertAfter($(e));
            $(e).remove();
        }

        function getQueryParams(qs) {
            qs = qs.split('+').join(' ');

            var params = {},
                    tokens,
                    re = /[?&]?([^=]+)=([^&]*)/g;

            while (tokens = re.exec(qs)) {
                params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
            }

            return params;
        }

        function stopRecording() {
            $("#wall-buttons a[data-type='recorder-start']").show();
            $("#wall-buttons a[data-type='recorder-stop']").hide();
            if (mediaRecorder != null) {
                mediaRecorder.stop();
                if (mediaRecorder.stream != null) {
                    mediaRecorder.stream.stop();
                }
            }
        }

        function startRecording() {
            if (isMediaRecording) return;
            isMediaRecording = true;
            stopRecording();
            navigator.mediaDevices.getUserMedia({audio: true}).then(onMediaStreamSuccess).catch(onMediaStreamError);

        }

        function onMediaStreamSuccess(stream) {
//            console.log("onMediaStreamSuccess", stream);
            try {
                $("#wall-buttons a[data-type='recorder-start']").hide();
                $("#wall-buttons a[data-type='recorder-stop']").show();
                mediaRecorder = new MediaStreamRecorder(stream);
                var uploaded = null;
                isMediaRecording = false;
                mediaRecorder.bufferSize = 8192;
                mediaRecorder.stream = stream;
                mediaRecorder.mimeType = 'audio/webm';
                mediaRecorder.dur = Date.now();
                mediaRecorder.ondataavailable = function (blob) {
                    stopRecording();
                    var duration = Date.now() - mediaRecorder.dur;

                    if (duration / 1000 < 1) return;
                    var recorder = $('<div class="audio-recorder"><audio type="audio/webm" controls></audio><br><button class="btn btn-success">إرسال</button>&nbsp;<button class="btn btn-danger">إلغاء</button></div>')
                    recorder.find('audio').attr('src', URL.createObjectURL(blob));
                    uploadblob(blob, function (source) {
                        recorder.find('audio').attr('src', "uploads/wall/sounds/" + source);
                        uploaded = source;
                    });
                    recorder.find('.btn-success').click(function () {
                        if (uploaded) socket.emit("postRecordedAudioOnWall", {src: uploaded});
                        recorder.remove();
                    });
                    recorder.find('.btn-danger').click(function () {
                        recorder.remove();
                    });
                    $("#wall-recorder").append(recorder);
                };
                mediaRecorder.start(30000);
            } catch (e) {
            }
        }

        function onMediaStreamError(err) {
//            console.log("onMediaStreamError", err);
            isMediaRecording = false;
            alert('هذا المتصفح لا يدعم ميزة التسجيل, الرجاء إستخدام جوجل كروم')
        }

        function uploadblob(blob, callback) {
            if (!blob) return;

            var formData = new FormData();
            formData.append('file', blob);

            $.ajax({
                url: 'wall/uploadblob',
                type: 'POST',
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: function (data) {
                    var data = JSON.parse(data);
                    if (data && !data.error && data.src) {
                        callback(data.src);
                    } else {
                        alert('حدث خطأ أثناء حفظ التسجيل')
                    }
                },
                error: function (e) {
                    alert('حدث خطأ أثناء حفظ التسجيل')
                }
            });
        }

        function initColors() {
            $(".btn-primary, .label-primary").css("background-color", mColors.btnColor);
            $(".bg-primary").css("background-color", mColors.windowColor);
            $("#main-content").css("background-color", mColors.backgroundColor);
        }

//*********************************************** GET BROWSER FP ****************************************************

        function getfp() {
            try {
                if (typeof window.name == 'string') {
                    if (window.name.indexOf('{') == 0 && window.name.lastIndexOf('}') == window.name.length - 1) {
                        var op = JSON.parse(window.name);
                    }
                }
                var client = new ClientJS();
                var keys = [];
                var sar = 'getBrowserMajorVersion,isIE,isChrome,isFirefox,isSafari,isOpera,getOSVersion,isWindows,isMac,isLinux,isUbuntu,isSolaris,isMobile,isMobileMajor,isMobileAndroid,isMobileOpera,isMobileWindows,isMobileBlackBerry,isMobileIOS,isIphone,isIpad,isIpod,getColorDepth,getCurrentResolution,getDeviceXDPI,getDeviceYDPI,getPlugins,getMimeTypes,isMimeTypes,isFont,getFonts,isLocalStorage,isSessionStorage,isCookie,getTimeZone,getLanguage,getSystemLanguage,isCanvas,getCanvasPrint'.split(',');
                for (ii = 0; ii < sar.length; ii++) {
                    var vl = '';
                    try {
                        vl = (client[sar[ii]]() || '') + ''
                    } catch (er) {
                    }
                    keys.push(vl);
                }
                var fp = (client.getOS().replace('Windows', 'Win') + "." + client.getOSVersion() + "." + client.getBrowser() + ".").split(" ").join("-").split('_').join('-') + hash(keys, 256);
                var cc = ccode();
                window.name = JSON.stringify({fp: fp, cc: cc});
                return fp + '.' + cc;

            } catch (e) {
            }
        }

        function ccode() {
            try {
                var d = new Date();
                var rt = d.getFullYear() + ''
                if ((d.getMonth() + 1) < 10) {
                    rt += '0';
                }
                rt += '' + (d.getMonth() + 1);
                if (d.getDate() < 10) {
                    rt += '0';
                }
                rt += '' + d.getDate();
                if (d.getHours() < 10) {
                    rt += '0';
                }
                rt += '' + d.getHours();
                return parseInt(rt).toString(32)
            }
            catch (err) {
                return 'ERR';
            }
        }

        function hash(key, seed) {
            var remainder, bytes, h1, h1b, c1, c2, k1, i;
            key = key.join('')
            remainder = key.length & 3; // key.length % 4
            bytes = key.length - remainder;
            h1 = seed;
            c1 = 0xcc9e2d51;
            c2 = 0x1b873593;
            i = 0;

            while (i < bytes) {
                k1 =
                        ((key.charCodeAt(i) & 0xff)) |
                        ((key.charCodeAt(++i) & 0xff) << 8) |
                        ((key.charCodeAt(++i) & 0xff) << 16) |
                        ((key.charCodeAt(++i) & 0xff) << 24);
                ++i;

                k1 = ((((k1 & 0xffff) * c1) + ((((k1 >>> 16) * c1) & 0xffff) << 16))) & 0xffffffff;
                k1 = (k1 << 15) | (k1 >>> 17);
                k1 = ((((k1 & 0xffff) * c2) + ((((k1 >>> 16) * c2) & 0xffff) << 16))) & 0xffffffff;

                h1 ^= k1;
                h1 = (h1 << 13) | (h1 >>> 19);
                h1b = ((((h1 & 0xffff) * 5) + ((((h1 >>> 16) * 5) & 0xffff) << 16))) & 0xffffffff;
                h1 = (((h1b & 0xffff) + 0x6b64) + ((((h1b >>> 16) + 0xe654) & 0xffff) << 16));
            }
            k1 = 0;
            switch (remainder) {
                case 3:
                    k1 ^= (key.charCodeAt(i + 2) & 0xff) << 16;
                case 2:
                    k1 ^= (key.charCodeAt(i + 1) & 0xff) << 8;
                case 1:
                    k1 ^= (key.charCodeAt(i) & 0xff);
                    k1 = (((k1 & 0xffff) * c1) + ((((k1 >>> 16) * c1) & 0xffff) << 16)) & 0xffffffff;
                    k1 = (k1 << 15) | (k1 >>> 17);
                    k1 = (((k1 & 0xffff) * c2) + ((((k1 >>> 16) * c2) & 0xffff) << 16)) & 0xffffffff;
                    h1 ^= k1;
            }
            h1 ^= key.length;
            h1 ^= h1 >>> 16;
            h1 = (((h1 & 0xffff) * 0x85ebca6b) + ((((h1 >>> 16) * 0x85ebca6b) & 0xffff) << 16)) & 0xffffffff;
            h1 ^= h1 >>> 13;
            h1 = ((((h1 & 0xffff) * 0xc2b2ae35) + ((((h1 >>> 16) * 0xc2b2ae35) & 0xffff) << 16))) & 0xffffffff;
            h1 ^= h1 >>> 16;
            return (h1 >>> 0).toString(16);
            ;
        }

//*******************************************************************************************************************

    })(jQuery, Gifts, Faces);
    function exit() {
        try {
            Android.exit();
            isAndroidApp = true;
            mSocket.disconnect();
        } catch (e) {
            confirmOnExit = false;
            location.reload();
        }
    }

</script>