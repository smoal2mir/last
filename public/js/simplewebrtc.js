! function(e) {
    if ("object" == typeof exports && "undefined" != typeof module) module.exports = e();
    else if ("function" == typeof define && define.amd) define([], e);
    else {
        ("undefined" != typeof window ? window : "undefined" != typeof global ? global : "undefined" != typeof self ? self : this).adapter = e()
    }
}(function() {
    return function e(t, n, r) {
        function i(a, s) {
            if (!n[a]) {
                if (!t[a]) {
                    var c = "function" == typeof require && require;
                    if (!s && c) return c(a, !0);
                    if (o) return o(a, !0);
                    var d = new Error("Cannot find module '" + a + "'");
                    throw d.code = "MODULE_NOT_FOUND", d
                }
                var u = n[a] = {
                    exports: {}
                };
                t[a][0].call(u.exports, function(e) {
                    var n = t[a][1][e];
                    return i(n || e)
                }, u, u.exports, e, t, n, r)
            }
            return n[a].exports
        }
        for (var o = "function" == typeof require && require, a = 0; a < r.length; a++) i(r[a]);
        return i
    }({
        1: [function(e, t, n) {
            "use strict";
            var r = {};
            r.generateIdentifier = function() {
                return Math.random().toString(36).substr(2, 10)
            }, r.localCName = r.generateIdentifier(), r.splitLines = function(e) {
                return e.trim().split("\n").map(function(e) {
                    return e.trim()
                })
            }, r.splitSections = function(e) {
                return e.split("\nm=").map(function(e, t) {
                    return (t > 0 ? "m=" + e : e).trim() + "\r\n"
                })
            }, r.matchPrefix = function(e, t) {
                return r.splitLines(e).filter(function(e) {
                    return 0 === e.indexOf(t)
                })
            }, r.parseCandidate = function(e) {
                for (var t, n = {
                        foundation: (t = 0 === e.indexOf("a=candidate:") ? e.substring(12).split(" ") : e.substring(10).split(" "))[0],
                        component: parseInt(t[1], 10),
                        protocol: t[2].toLowerCase(),
                        priority: parseInt(t[3], 10),
                        ip: t[4],
                        port: parseInt(t[5], 10),
                        type: t[7]
                    }, r = 8; r < t.length; r += 2) switch (t[r]) {
                    case "raddr":
                        n.relatedAddress = t[r + 1];
                        break;
                    case "rport":
                        n.relatedPort = parseInt(t[r + 1], 10);
                        break;
                    case "tcptype":
                        n.tcpType = t[r + 1];
                        break;
                    default:
                        n[t[r]] = t[r + 1]
                }
                return n
            }, r.writeCandidate = function(e) {
                var t = [];
                t.push(e.foundation), t.push(e.component), t.push(e.protocol.toUpperCase()), t.push(e.priority), t.push(e.ip), t.push(e.port);
                var n = e.type;
                return t.push("typ"), t.push(n), "host" !== n && e.relatedAddress && e.relatedPort && (t.push("raddr"), t.push(e.relatedAddress), t.push("rport"), t.push(e.relatedPort)), e.tcpType && "tcp" === e.protocol.toLowerCase() && (t.push("tcptype"), t.push(e.tcpType)), "candidate:" + t.join(" ")
            }, r.parseIceOptions = function(e) {
                return e.substr(14).split(" ")
            }, r.parseRtpMap = function(e) {
                var t = e.substr(9).split(" "),
                    n = {
                        payloadType: parseInt(t.shift(), 10)
                    };
                return t = t[0].split("/"), n.name = t[0], n.clockRate = parseInt(t[1], 10), n.numChannels = 3 === t.length ? parseInt(t[2], 10) : 1, n
            }, r.writeRtpMap = function(e) {
                var t = e.payloadType;
                return void 0 !== e.preferredPayloadType && (t = e.preferredPayloadType), "a=rtpmap:" + t + " " + e.name + "/" + e.clockRate + (1 !== e.numChannels ? "/" + e.numChannels : "") + "\r\n"
            }, r.parseExtmap = function(e) {
                var t = e.substr(9).split(" ");
                return {
                    id: parseInt(t[0], 10),
                    direction: t[0].indexOf("/") > 0 ? t[0].split("/")[1] : "sendrecv",
                    uri: t[1]
                }
            }, r.writeExtmap = function(e) {
                return "a=extmap:" + (e.id || e.preferredId) + (e.direction && "sendrecv" !== e.direction ? "/" + e.direction : "") + " " + e.uri + "\r\n"
            }, r.parseFmtp = function(e) {
                for (var t, n = {}, r = e.substr(e.indexOf(" ") + 1).split(";"), i = 0; i < r.length; i++) n[(t = r[i].trim().split("="))[0].trim()] = t[1];
                return n
            }, r.writeFmtp = function(e) {
                var t = "",
                    n = e.payloadType;
                if (void 0 !== e.preferredPayloadType && (n = e.preferredPayloadType), e.parameters && Object.keys(e.parameters).length) {
                    var r = [];
                    Object.keys(e.parameters).forEach(function(t) {
                        r.push(t + "=" + e.parameters[t])
                    }), t += "a=fmtp:" + n + " " + r.join(";") + "\r\n"
                }
                return t
            }, r.parseRtcpFb = function(e) {
                var t = e.substr(e.indexOf(" ") + 1).split(" ");
                return {
                    type: t.shift(),
                    parameter: t.join(" ")
                }
            }, r.writeRtcpFb = function(e) {
                var t = "",
                    n = e.payloadType;
                return void 0 !== e.preferredPayloadType && (n = e.preferredPayloadType), e.rtcpFeedback && e.rtcpFeedback.length && e.rtcpFeedback.forEach(function(e) {
                    t += "a=rtcp-fb:" + n + " " + e.type + (e.parameter && e.parameter.length ? " " + e.parameter : "") + "\r\n"
                }), t
            }, r.parseSsrcMedia = function(e) {
                var t = e.indexOf(" "),
                    n = {
                        ssrc: parseInt(e.substr(7, t - 7), 10)
                    },
                    r = e.indexOf(":", t);
                return r > -1 ? (n.attribute = e.substr(t + 1, r - t - 1), n.value = e.substr(r + 1)) : n.attribute = e.substr(t + 1), n
            }, r.getMid = function(e) {
                var t = r.matchPrefix(e, "a=mid:")[0];
                if (t) return t.substr(6)
            }, r.parseFingerprint = function(e) {
                var t = e.substr(14).split(" ");
                return {
                    algorithm: t[0].toLowerCase(),
                    value: t[1]
                }
            }, r.getDtlsParameters = function(e, t) {
                return {
                    role: "auto",
                    fingerprints: r.matchPrefix(e + t, "a=fingerprint:").map(r.parseFingerprint)
                }
            }, r.writeDtlsParameters = function(e, t) {
                var n = "a=setup:" + t + "\r\n";
                return e.fingerprints.forEach(function(e) {
                    n += "a=fingerprint:" + e.algorithm + " " + e.value + "\r\n"
                }), n
            }, r.getIceParameters = function(e, t) {
                var n = r.splitLines(e);
                return {
                    usernameFragment: (n = n.concat(r.splitLines(t))).filter(function(e) {
                        return 0 === e.indexOf("a=ice-ufrag:")
                    })[0].substr(12),
                    password: n.filter(function(e) {
                        return 0 === e.indexOf("a=ice-pwd:")
                    })[0].substr(10)
                }
            }, r.writeIceParameters = function(e) {
                return "a=ice-ufrag:" + e.usernameFragment + "\r\na=ice-pwd:" + e.password + "\r\n"
            }, r.parseRtpParameters = function(e) {
                for (var t = {
                        codecs: [],
                        headerExtensions: [],
                        fecMechanisms: [],
                        rtcp: []
                    }, n = r.splitLines(e)[0].split(" "), i = 3; i < n.length; i++) {
                    var o = n[i],
                        a = r.matchPrefix(e, "a=rtpmap:" + o + " ")[0];
                    if (a) {
                        var s = r.parseRtpMap(a),
                            c = r.matchPrefix(e, "a=fmtp:" + o + " ");
                        switch (s.parameters = c.length ? r.parseFmtp(c[0]) : {}, s.rtcpFeedback = r.matchPrefix(e, "a=rtcp-fb:" + o + " ").map(r.parseRtcpFb), t.codecs.push(s), s.name.toUpperCase()) {
                            case "RED":
                            case "ULPFEC":
                                t.fecMechanisms.push(s.name.toUpperCase())
                        }
                    }
                }
                return r.matchPrefix(e, "a=extmap:").forEach(function(e) {
                    t.headerExtensions.push(r.parseExtmap(e))
                }), t
            }, r.writeRtpDescription = function(e, t) {
                var n = "";
                n += "m=" + e + " ", n += t.codecs.length > 0 ? "9" : "0", n += " UDP/TLS/RTP/SAVPF ", n += t.codecs.map(function(e) {
                    return void 0 !== e.preferredPayloadType ? e.preferredPayloadType : e.payloadType
                }).join(" ") + "\r\n", n += "c=IN IP4 0.0.0.0\r\n", n += "a=rtcp:9 IN IP4 0.0.0.0\r\n", t.codecs.forEach(function(e) {
                    n += r.writeRtpMap(e), n += r.writeFmtp(e), n += r.writeRtcpFb(e)
                });
                var i = 0;
                return t.codecs.forEach(function(e) {
                    e.maxptime > i && (i = e.maxptime)
                }), i > 0 && (n += "a=maxptime:" + i + "\r\n"), n += "a=rtcp-mux\r\n", t.headerExtensions.forEach(function(e) {
                    n += r.writeExtmap(e)
                }), n
            }, r.parseRtpEncodingParameters = function(e) {
                var t, n = [],
                    i = r.parseRtpParameters(e),
                    o = -1 !== i.fecMechanisms.indexOf("RED"),
                    a = -1 !== i.fecMechanisms.indexOf("ULPFEC"),
                    s = r.matchPrefix(e, "a=ssrc:").map(function(e) {
                        return r.parseSsrcMedia(e)
                    }).filter(function(e) {
                        return "cname" === e.attribute
                    }),
                    c = s.length > 0 && s[0].ssrc,
                    d = r.matchPrefix(e, "a=ssrc-group:FID").map(function(e) {
                        var t = e.split(" ");
                        return t.shift(), t.map(function(e) {
                            return parseInt(e, 10)
                        })
                    });
                d.length > 0 && d[0].length > 1 && d[0][0] === c && (t = d[0][1]), i.codecs.forEach(function(e) {
                    if ("RTX" === e.name.toUpperCase() && e.parameters.apt) {
                        var r = {
                            ssrc: c,
                            codecPayloadType: parseInt(e.parameters.apt, 10),
                            rtx: {
                                ssrc: t
                            }
                        };
                        n.push(r), o && ((r = JSON.parse(JSON.stringify(r))).fec = {
                            ssrc: t,
                            mechanism: a ? "red+ulpfec" : "red"
                        }, n.push(r))
                    }
                }), 0 === n.length && c && n.push({
                    ssrc: c
                });
                var u = r.matchPrefix(e, "b=");
                return u.length && (0 === u[0].indexOf("b=TIAS:") ? u = parseInt(u[0].substr(7), 10) : 0 === u[0].indexOf("b=AS:") && (u = parseInt(u[0].substr(5), 10)), n.forEach(function(e) {
                    e.maxBitrate = u
                })), n
            }, r.parseRtcpParameters = function(e) {
                var t = {},
                    n = r.matchPrefix(e, "a=ssrc:").map(function(e) {
                        return r.parseSsrcMedia(e)
                    }).filter(function(e) {
                        return "cname" === e.attribute
                    })[0];
                n && (t.cname = n.value, t.ssrc = n.ssrc);
                var i = r.matchPrefix(e, "a=rtcp-rsize");
                t.reducedSize = i.length > 0, t.compound = 0 === i.length;
                var o = r.matchPrefix(e, "a=rtcp-mux");
                return t.mux = o.length > 0, t
            }, r.parseMsid = function(e) {
                var t, n = r.matchPrefix(e, "a=msid:");
                if (1 === n.length) return t = n[0].substr(7).split(" "), {
                    stream: t[0],
                    track: t[1]
                };
                var i = r.matchPrefix(e, "a=ssrc:").map(function(e) {
                    return r.parseSsrcMedia(e)
                }).filter(function(e) {
                    return "msid" === e.attribute
                });
                return i.length > 0 ? (t = i[0].value.split(" "), {
                    stream: t[0],
                    track: t[1]
                }) : void 0
            }, r.writeSessionBoilerplate = function() {
                return "v=0\r\no=thisisadapterortc 8169639915646943137 2 IN IP4 127.0.0.1\r\ns=-\r\nt=0 0\r\n"
            }, r.writeMediaSection = function(e, t, n, i) {
                var o = r.writeRtpDescription(e.kind, t);
                if (o += r.writeIceParameters(e.iceGatherer.getLocalParameters()), o += r.writeDtlsParameters(e.dtlsTransport.getLocalParameters(), "offer" === n ? "actpass" : "active"), o += "a=mid:" + e.mid + "\r\n", e.direction ? o += "a=" + e.direction + "\r\n" : e.rtpSender && e.rtpReceiver ? o += "a=sendrecv\r\n" : e.rtpSender ? o += "a=sendonly\r\n" : e.rtpReceiver ? o += "a=recvonly\r\n" : o += "a=inactive\r\n", e.rtpSender) {
                    var a = "msid:" + i.id + " " + e.rtpSender.track.id + "\r\n";
                    o += "a=" + a, o += "a=ssrc:" + e.sendEncodingParameters[0].ssrc + " " + a, e.sendEncodingParameters[0].rtx && (o += "a=ssrc:" + e.sendEncodingParameters[0].rtx.ssrc + " " + a, o += "a=ssrc-group:FID " + e.sendEncodingParameters[0].ssrc + " " + e.sendEncodingParameters[0].rtx.ssrc + "\r\n")
                }
                return o += "a=ssrc:" + e.sendEncodingParameters[0].ssrc + " cname:" + r.localCName + "\r\n", e.rtpSender && e.sendEncodingParameters[0].rtx && (o += "a=ssrc:" + e.sendEncodingParameters[0].rtx.ssrc + " cname:" + r.localCName + "\r\n"), o
            }, r.getDirection = function(e, t) {
                for (var n = r.splitLines(e), i = 0; i < n.length; i++) switch (n[i]) {
                    case "a=sendrecv":
                    case "a=sendonly":
                    case "a=recvonly":
                    case "a=inactive":
                        return n[i].substr(2)
                }
                return t ? r.getDirection(t) : "sendrecv"
            }, r.getKind = function(e) {
                return r.splitLines(e)[0].split(" ")[0].substr(2)
            }, r.isRejected = function(e) {
                return "0" === e.split(" ", 2)[1]
            }, t.exports = r
        }, {}],
        2: [function(e, t, n) {
            (function(n) {
                "use strict";
                var r = e("./adapter_factory.js");
                t.exports = r({
                    window: n.window
                })
            }).call(this, "undefined" != typeof global ? global : "undefined" != typeof self ? self : "undefined" != typeof window ? window : {})
        }, {
            "./adapter_factory.js": 3
        }],
        3: [function(e, t, n) {
            "use strict";
            t.exports = function(t) {
                var n = t && t.window,
                    r = e("./utils"),
                    i = r.log,
                    o = r.detectBrowser(n),
                    a = {
                        browserDetails: o,
                        extractVersion: r.extractVersion,
                        disableLog: r.disableLog
                    },
                    s = e("./chrome/chrome_shim") || null,
                    c = e("./edge/edge_shim") || null,
                    d = e("./firefox/firefox_shim") || null,
                    u = e("./safari/safari_shim") || null;
                switch (o.browser) {
                    case "chrome":
                        if (!s || !s.shimPeerConnection) return i("Chrome shim is not included in this adapter release."), a;
                        i("adapter.js shimming chrome."), a.browserShim = s, s.shimGetUserMedia(n), s.shimMediaStream(n), r.shimCreateObjectURL(n), s.shimSourceObject(n), s.shimPeerConnection(n), s.shimOnTrack(n), s.shimGetSendersWithDtmf(n);
                        break;
                    case "firefox":
                        if (!d || !d.shimPeerConnection) return i("Firefox shim is not included in this adapter release."), a;
                        i("adapter.js shimming firefox."), a.browserShim = d, d.shimGetUserMedia(n), r.shimCreateObjectURL(n), d.shimSourceObject(n), d.shimPeerConnection(n), d.shimOnTrack(n);
                        break;
                    case "edge":
                        if (!c || !c.shimPeerConnection) return i("MS edge shim is not included in this adapter release."), a;
                        i("adapter.js shimming edge."), a.browserShim = c, c.shimGetUserMedia(n), r.shimCreateObjectURL(n), c.shimPeerConnection(n), c.shimReplaceTrack(n);
                        break;
                    case "safari":
                        if (!u) return i("Safari shim is not included in this adapter release."), a;
                        i("adapter.js shimming safari."), a.browserShim = u, u.shimCallbacksAPI(n), u.shimAddStream(n), u.shimOnAddStream(n), u.shimGetUserMedia(n);
                        break;
                    default:
                        i("Unsupported browser!")
                }
                return a
            }
        }, {
            "./chrome/chrome_shim": 4,
            "./edge/edge_shim": 6,
            "./firefox/firefox_shim": 9,
            "./safari/safari_shim": 11,
            "./utils": 12
        }],
        4: [function(e, t, n) {
            "use strict";
            var r = e("../utils.js"),
                i = r.log,
                o = {
                    shimMediaStream: function(e) {
                        e.MediaStream = e.MediaStream || e.webkitMediaStream
                    },
                    shimOnTrack: function(e) {
                        "object" != typeof e || !e.RTCPeerConnection || "ontrack" in e.RTCPeerConnection.prototype || Object.defineProperty(e.RTCPeerConnection.prototype, "ontrack", {
                            get: function() {
                                return this._ontrack
                            },
                            set: function(t) {
                                var n = this;
                                this._ontrack && (this.removeEventListener("track", this._ontrack), this.removeEventListener("addstream", this._ontrackpoly)), this.addEventListener("track", this._ontrack = t), this.addEventListener("addstream", this._ontrackpoly = function(t) {
                                    t.stream.addEventListener("addtrack", function(r) {
                                        var i;
                                        i = e.RTCPeerConnection.prototype.getReceivers ? n.getReceivers().find(function(e) {
                                            return e.track.id === r.track.id
                                        }) : {
                                            track: r.track
                                        };
                                        var o = new Event("track");
                                        o.track = r.track, o.receiver = i, o.streams = [t.stream], n.dispatchEvent(o)
                                    }), t.stream.getTracks().forEach(function(r) {
                                        var i;
                                        i = e.RTCPeerConnection.prototype.getReceivers ? n.getReceivers().find(function(e) {
                                            return e.track.id === r.id
                                        }) : {
                                            track: r
                                        };
                                        var o = new Event("track");
                                        o.track = r, o.receiver = i, o.streams = [t.stream], this.dispatchEvent(o)
                                    }.bind(this))
                                }.bind(this))
                            }
                        })
                    },
                    shimGetSendersWithDtmf: function(e) {
                        if ("object" == typeof e && e.RTCPeerConnection && !("getSenders" in e.RTCPeerConnection.prototype) && "createDTMFSender" in e.RTCPeerConnection.prototype) {
                            e.RTCPeerConnection.prototype.getSenders = function() {
                                return this._senders || []
                            };
                            var t = e.RTCPeerConnection.prototype.addStream,
                                n = e.RTCPeerConnection.prototype.removeStream;
                            e.RTCPeerConnection.prototype.addTrack || (e.RTCPeerConnection.prototype.addTrack = function(t, n) {
                                var r = this;
                                if ("closed" === r.signalingState) throw new DOMException("The RTCPeerConnection's signalingState is 'closed'.", "InvalidStateError");
                                var i = [].slice.call(arguments, 1);
                                if (1 !== i.length || !i[0].getTracks().find(function(e) {
                                        return e === t
                                    })) throw new DOMException("The adapter.js addTrack polyfill only supports a single  stream which is associated with the specified track.", "NotSupportedError");
                                if (r._senders = r._senders || [], r._senders.find(function(e) {
                                        return e.track === t
                                    })) throw new DOMException("Track already exists.", "InvalidAccessError");
                                r._streams = r._streams || {};
                                var o = r._streams[n.id];
                                if (o) o.addTrack(t), r.removeStream(o), r.addStream(o);
                                else {
                                    var a = new e.MediaStream([t]);
                                    r._streams[n.id] = a, r.addStream(a)
                                }
                                var s = {
                                    track: t,
                                    get dtmf() {
                                        return void 0 === this._dtmf && ("audio" === t.kind ? this._dtmf = r.createDTMFSender(t) : this._dtmf = null), this._dtmf
                                    }
                                };
                                return r._senders.push(s), s
                            }), e.RTCPeerConnection.prototype.addStream = function(e) {
                                var n = this;
                                n._senders = n._senders || [], t.apply(n, [e]), e.getTracks().forEach(function(e) {
                                    n._senders.push({
                                        track: e,
                                        get dtmf() {
                                            return void 0 === this._dtmf && ("audio" === e.kind ? this._dtmf = n.createDTMFSender(e) : this._dtmf = null), this._dtmf
                                        }
                                    })
                                })
                            }, e.RTCPeerConnection.prototype.removeStream = function(e) {
                                var t = this;
                                t._senders = t._senders || [], n.apply(t, [e]), e.getTracks().forEach(function(e) {
                                    var n = t._senders.find(function(t) {
                                        return t.track === e
                                    });
                                    n && t._senders.splice(t._senders.indexOf(n), 1)
                                })
                            }
                        }
                    },
                    shimSourceObject: function(e) {
                        var t = e && e.URL;
                        "object" == typeof e && (!e.HTMLMediaElement || "srcObject" in e.HTMLMediaElement.prototype || Object.defineProperty(e.HTMLMediaElement.prototype, "srcObject", {
                            get: function() {
                                return this._srcObject
                            },
                            set: function(e) {
                                var n = this;
                                this._srcObject = e, this.src && t.revokeObjectURL(this.src), e ? (this.src = t.createObjectURL(e), e.addEventListener("addtrack", function() {
                                    n.src && t.revokeObjectURL(n.src), n.src = t.createObjectURL(e)
                                }), e.addEventListener("removetrack", function() {
                                    n.src && t.revokeObjectURL(n.src), n.src = t.createObjectURL(e)
                                })) : this.src = ""
                            }
                        }))
                    },
                    shimPeerConnection: function(e) {
                        var t = r.detectBrowser(e);
                        if (e.RTCPeerConnection) {
                            var n = e.RTCPeerConnection;
                            e.RTCPeerConnection = function(e, t) {
                                if (e && e.iceServers) {
                                    for (var r = [], i = 0; i < e.iceServers.length; i++) {
                                        var o = e.iceServers[i];
                                        !o.hasOwnProperty("urls") && o.hasOwnProperty("url") ? (console.warn("RTCIceServer.url is deprecated! Use urls instead."), (o = JSON.parse(JSON.stringify(o))).urls = o.url, r.push(o)) : r.push(e.iceServers[i])
                                    }
                                    e.iceServers = r
                                }
                                return new n(e, t)
                            }, e.RTCPeerConnection.prototype = n.prototype, Object.defineProperty(e.RTCPeerConnection, "generateCertificate", {
                                get: function() {
                                    return n.generateCertificate
                                }
                            })
                        } else e.RTCPeerConnection = function(t, n) {
                            return i("PeerConnection"), t && t.iceTransportPolicy && (t.iceTransports = t.iceTransportPolicy), new e.webkitRTCPeerConnection(t, n)
                        }, e.RTCPeerConnection.prototype = e.webkitRTCPeerConnection.prototype, e.webkitRTCPeerConnection.generateCertificate && Object.defineProperty(e.RTCPeerConnection, "generateCertificate", {
                            get: function() {
                                return e.webkitRTCPeerConnection.generateCertificate
                            }
                        });
                        var o = e.RTCPeerConnection.prototype.getStats;
                        e.RTCPeerConnection.prototype.getStats = function(e, t, n) {
                            var r = this,
                                i = arguments;
                            if (arguments.length > 0 && "function" == typeof e) return o.apply(this, arguments);
                            if (0 === o.length && (0 === arguments.length || "function" != typeof arguments[0])) return o.apply(this, []);
                            var a = function(e) {
                                    var t = {};
                                    return e.result().forEach(function(e) {
                                        var n = {
                                            id: e.id,
                                            timestamp: e.timestamp,
                                            type: {
                                                localcandidate: "local-candidate",
                                                remotecandidate: "remote-candidate"
                                            } [e.type] || e.type
                                        };
                                        e.names().forEach(function(t) {
                                            n[t] = e.stat(t)
                                        }), t[n.id] = n
                                    }), t
                                },
                                s = function(e) {
                                    return new Map(Object.keys(e).map(function(t) {
                                        return [t, e[t]]
                                    }))
                                };
                            if (arguments.length >= 2) {
                                return o.apply(this, [function(e) {
                                    i[1](s(a(e)))
                                }, arguments[0]])
                            }
                            return new Promise(function(e, t) {
                                o.apply(r, [function(t) {
                                    e(s(a(t)))
                                }, t])
                            }).then(t, n)
                        }, t.version < 51 && ["setLocalDescription", "setRemoteDescription", "addIceCandidate"].forEach(function(t) {
                            var n = e.RTCPeerConnection.prototype[t];
                            e.RTCPeerConnection.prototype[t] = function() {
                                var e = arguments,
                                    t = this,
                                    r = new Promise(function(r, i) {
                                        n.apply(t, [e[0], r, i])
                                    });
                                return e.length < 2 ? r : r.then(function() {
                                    e[1].apply(null, [])
                                }, function(t) {
                                    e.length >= 3 && e[2].apply(null, [t])
                                })
                            }
                        }), t.version < 52 && ["createOffer", "createAnswer"].forEach(function(t) {
                            var n = e.RTCPeerConnection.prototype[t];
                            e.RTCPeerConnection.prototype[t] = function() {
                                var e = this;
                                if (arguments.length < 1 || 1 === arguments.length && "object" == typeof arguments[0]) {
                                    var t = 1 === arguments.length ? arguments[0] : void 0;
                                    return new Promise(function(r, i) {
                                        n.apply(e, [r, i, t])
                                    })
                                }
                                return n.apply(this, arguments)
                            }
                        }), ["setLocalDescription", "setRemoteDescription", "addIceCandidate"].forEach(function(t) {
                            var n = e.RTCPeerConnection.prototype[t];
                            e.RTCPeerConnection.prototype[t] = function() {
                                return arguments[0] = new("addIceCandidate" === t ? e.RTCIceCandidate : e.RTCSessionDescription)(arguments[0]), n.apply(this, arguments)
                            }
                        });
                        var a = e.RTCPeerConnection.prototype.addIceCandidate;
                        e.RTCPeerConnection.prototype.addIceCandidate = function() {
                            return arguments[0] ? a.apply(this, arguments) : (arguments[1] && arguments[1].apply(null), Promise.resolve())
                        }
                    }
                };
            t.exports = {
                shimMediaStream: o.shimMediaStream,
                shimOnTrack: o.shimOnTrack,
                shimGetSendersWithDtmf: o.shimGetSendersWithDtmf,
                shimSourceObject: o.shimSourceObject,
                shimPeerConnection: o.shimPeerConnection,
                shimGetUserMedia: e("./getusermedia")
            }
        }, {
            "../utils.js": 12,
            "./getusermedia": 5
        }],
        5: [function(e, t, n) {
            "use strict";
            var r = e("../utils.js"),
                i = r.log;
            t.exports = function(e) {
                var t = r.detectBrowser(e),
                    n = e && e.navigator,
                    o = function(e) {
                        if ("object" != typeof e || e.mandatory || e.optional) return e;
                        var t = {};
                        return Object.keys(e).forEach(function(n) {
                            if ("require" !== n && "advanced" !== n && "mediaSource" !== n) {
                                var r = "object" == typeof e[n] ? e[n] : {
                                    ideal: e[n]
                                };
                                void 0 !== r.exact && "number" == typeof r.exact && (r.min = r.max = r.exact);
                                var i = function(e, t) {
                                    return e ? e + t.charAt(0).toUpperCase() + t.slice(1) : "deviceId" === t ? "sourceId" : t
                                };
                                if (void 0 !== r.ideal) {
                                    t.optional = t.optional || [];
                                    var o = {};
                                    "number" == typeof r.ideal ? (o[i("min", n)] = r.ideal, t.optional.push(o), (o = {})[i("max", n)] = r.ideal, t.optional.push(o)) : (o[i("", n)] = r.ideal, t.optional.push(o))
                                }
                                void 0 !== r.exact && "number" != typeof r.exact ? (t.mandatory = t.mandatory || {}, t.mandatory[i("", n)] = r.exact) : ["min", "max"].forEach(function(e) {
                                    void 0 !== r[e] && (t.mandatory = t.mandatory || {}, t.mandatory[i(e, n)] = r[e])
                                })
                            }
                        }), e.advanced && (t.optional = (t.optional || []).concat(e.advanced)), t
                    },
                    a = function(e, r) {
                        if ((e = JSON.parse(JSON.stringify(e))) && "object" == typeof e.audio) {
                            var a = function(e, t, n) {
                                t in e && !(n in e) && (e[n] = e[t], delete e[t])
                            };
                            a((e = JSON.parse(JSON.stringify(e))).audio, "autoGainControl", "googAutoGainControl"), a(e.audio, "noiseSuppression", "googNoiseSuppression"), e.audio = o(e.audio)
                        }
                        if (e && "object" == typeof e.video) {
                            var s = e.video.facingMode;
                            s = s && ("object" == typeof s ? s : {
                                ideal: s
                            });
                            var c = t.version < 61;
                            if (s && ("user" === s.exact || "environment" === s.exact || "user" === s.ideal || "environment" === s.ideal) && (!n.mediaDevices.getSupportedConstraints || !n.mediaDevices.getSupportedConstraints().facingMode || c)) {
                                delete e.video.facingMode;
                                var d;
                                if ("environment" === s.exact || "environment" === s.ideal ? d = ["back", "rear"] : "user" !== s.exact && "user" !== s.ideal || (d = ["front"]), d) return n.mediaDevices.enumerateDevices().then(function(t) {
                                    var n = (t = t.filter(function(e) {
                                        return "videoinput" === e.kind
                                    })).find(function(e) {
                                        return d.some(function(t) {
                                            return -1 !== e.label.toLowerCase().indexOf(t)
                                        })
                                    });
                                    return !n && t.length && -1 !== d.indexOf("back") && (n = t[t.length - 1]), n && (e.video.deviceId = s.exact ? {
                                        exact: n.deviceId
                                    } : {
                                        ideal: n.deviceId
                                    }), e.video = o(e.video), i("chrome: " + JSON.stringify(e)), r(e)
                                })
                            }
                            e.video = o(e.video)
                        }
                        return i("chrome: " + JSON.stringify(e)), r(e)
                    },
                    s = function(e) {
                        return {
                            name: {
                                PermissionDeniedError: "NotAllowedError",
                                InvalidStateError: "NotReadableError",
                                DevicesNotFoundError: "NotFoundError",
                                ConstraintNotSatisfiedError: "OverconstrainedError",
                                TrackStartError: "NotReadableError",
                                MediaDeviceFailedDueToShutdown: "NotReadableError",
                                MediaDeviceKillSwitchOn: "NotReadableError"
                            } [e.name] || e.name,
                            message: e.message,
                            constraint: e.constraintName,
                            toString: function() {
                                return this.name + (this.message && ": ") + this.message
                            }
                        }
                    };
                n.getUserMedia = function(e, t, r) {
                    a(e, function(e) {
                        n.webkitGetUserMedia(e, t, function(e) {
                            r(s(e))
                        })
                    })
                };
                var c = function(e) {
                    return new Promise(function(t, r) {
                        n.getUserMedia(e, t, r)
                    })
                };
                if (n.mediaDevices || (n.mediaDevices = {
                        getUserMedia: c,
                        enumerateDevices: function() {
                            return new Promise(function(t) {
                                var n = {
                                    audio: "audioinput",
                                    video: "videoinput"
                                };
                                return e.MediaStreamTrack.getSources(function(e) {
                                    t(e.map(function(e) {
                                        return {
                                            label: e.label,
                                            kind: n[e.kind],
                                            deviceId: e.id,
                                            groupId: ""
                                        }
                                    }))
                                })
                            })
                        },
                        getSupportedConstraints: function() {
                            return {
                                deviceId: !0,
                                echoCancellation: !0,
                                facingMode: !0,
                                frameRate: !0,
                                height: !0,
                                width: !0
                            }
                        }
                    }), n.mediaDevices.getUserMedia) {
                    var d = n.mediaDevices.getUserMedia.bind(n.mediaDevices);
                    n.mediaDevices.getUserMedia = function(e) {
                        return a(e, function(e) {
                            return d(e).then(function(t) {
                                if (e.audio && !t.getAudioTracks().length || e.video && !t.getVideoTracks().length) throw t.getTracks().forEach(function(e) {
                                    e.stop()
                                }), new DOMException("", "NotFoundError");
                                return t
                            }, function(e) {
                                return Promise.reject(s(e))
                            })
                        })
                    }
                } else n.mediaDevices.getUserMedia = function(e) {
                    return c(e)
                };
                void 0 === n.mediaDevices.addEventListener && (n.mediaDevices.addEventListener = function() {
                    i("Dummy mediaDevices.addEventListener called.")
                }), void 0 === n.mediaDevices.removeEventListener && (n.mediaDevices.removeEventListener = function() {
                    i("Dummy mediaDevices.removeEventListener called.")
                })
            }
        }, {
            "../utils.js": 12
        }],
        6: [function(e, t, n) {
            "use strict";
            var r = e("../utils"),
                i = e("./rtcpeerconnection_shim");
            t.exports = {
                shimGetUserMedia: e("./getusermedia"),
                shimPeerConnection: function(e) {
                    var t = r.detectBrowser(e);
                    if (e.RTCIceGatherer && (e.RTCIceCandidate || (e.RTCIceCandidate = function(e) {
                            return e
                        }), e.RTCSessionDescription || (e.RTCSessionDescription = function(e) {
                            return e
                        }), t.version < 15025)) {
                        var n = Object.getOwnPropertyDescriptor(e.MediaStreamTrack.prototype, "enabled");
                        Object.defineProperty(e.MediaStreamTrack.prototype, "enabled", {
                            set: function(e) {
                                n.set.call(this, e);
                                var t = new Event("enabled");
                                t.enabled = e, this.dispatchEvent(t)
                            }
                        })
                    }
                    e.RTCPeerConnection = i(e, t.version)
                },
                shimReplaceTrack: function(e) {
                    !e.RTCRtpSender || "replaceTrack" in e.RTCRtpSender.prototype || (e.RTCRtpSender.prototype.replaceTrack = e.RTCRtpSender.prototype.setTrack)
                }
            }
        }, {
            "../utils": 12,
            "./getusermedia": 7,
            "./rtcpeerconnection_shim": 8
        }],
        7: [function(e, t, n) {
            "use strict";
            t.exports = function(e) {
                var t = e && e.navigator,
                    n = function(e) {
                        return {
                            name: {
                                PermissionDeniedError: "NotAllowedError"
                            } [e.name] || e.name,
                            message: e.message,
                            constraint: e.constraint,
                            toString: function() {
                                return this.name
                            }
                        }
                    },
                    r = t.mediaDevices.getUserMedia.bind(t.mediaDevices);
                t.mediaDevices.getUserMedia = function(e) {
                    return r(e).catch(function(e) {
                        return Promise.reject(n(e))
                    })
                }
            }
        }, {}],
        8: [function(e, t, n) {
            "use strict";

            function r(e) {
                var t = e.filter(function(e) {
                        return "audio" === e.kind
                    }),
                    n = e.filter(function(e) {
                        return "video" === e.kind
                    });
                for (e = []; t.length || n.length;) t.length && e.push(t.shift()), n.length && e.push(n.shift());
                return e
            }

            function i(e, t) {
                var n = !1;
                return (e = JSON.parse(JSON.stringify(e))).filter(function(e) {
                    if (e && (e.urls || e.url)) {
                        var r = e.urls || e.url;
                        e.url && !e.urls && console.warn("RTCIceServer.url is deprecated! Use urls instead.");
                        var i = "string" == typeof r;
                        return i && (r = [r]), r = r.filter(function(e) {
                            return 0 === e.indexOf("turn:") && -1 !== e.indexOf("transport=udp") && -1 === e.indexOf("turn:[") && !n ? (n = !0, !0) : 0 === e.indexOf("stun:") && t >= 14393
                        }), delete e.url, e.urls = i ? r[0] : r, !!r.length
                    }
                    return !1
                })
            }

            function o(e, t) {
                var n = {
                        codecs: [],
                        headerExtensions: [],
                        fecMechanisms: []
                    },
                    r = function(e, t) {
                        e = parseInt(e, 10);
                        for (var n = 0; n < t.length; n++)
                            if (t[n].payloadType === e || t[n].preferredPayloadType === e) return t[n]
                    },
                    i = function(e, t, n, i) {
                        var o = r(e.parameters.apt, n),
                            a = r(t.parameters.apt, i);
                        return o && a && o.name.toLowerCase() === a.name.toLowerCase()
                    };
                return e.codecs.forEach(function(r) {
                    for (var o = 0; o < t.codecs.length; o++) {
                        var a = t.codecs[o];
                        if (r.name.toLowerCase() === a.name.toLowerCase() && r.clockRate === a.clockRate) {
                            if ("rtx" === r.name.toLowerCase() && r.parameters && a.parameters.apt && !i(r, a, e.codecs, t.codecs)) continue;
                            (a = JSON.parse(JSON.stringify(a))).numChannels = Math.min(r.numChannels, a.numChannels), n.codecs.push(a), a.rtcpFeedback = a.rtcpFeedback.filter(function(e) {
                                for (var t = 0; t < r.rtcpFeedback.length; t++)
                                    if (r.rtcpFeedback[t].type === e.type && r.rtcpFeedback[t].parameter === e.parameter) return !0;
                                return !1
                            });
                            break
                        }
                    }
                }), e.headerExtensions.forEach(function(e) {
                    for (var r = 0; r < t.headerExtensions.length; r++) {
                        var i = t.headerExtensions[r];
                        if (e.uri === i.uri) {
                            n.headerExtensions.push(i);
                            break
                        }
                    }
                }), n
            }

            function a(e, t, n) {
                return -1 !== {
                    offer: {
                        setLocalDescription: ["stable", "have-local-offer"],
                        setRemoteDescription: ["stable", "have-remote-offer"]
                    },
                    answer: {
                        setLocalDescription: ["have-remote-offer", "have-local-pranswer"],
                        setRemoteDescription: ["have-local-offer", "have-remote-pranswer"]
                    }
                } [t][e].indexOf(n)
            }
            var s = e("sdp");
            t.exports = function(e, t) {
                var n = function(n) {
                    var r = this,
                        o = document.createDocumentFragment();
                    if (["addEventListener", "removeEventListener", "dispatchEvent"].forEach(function(e) {
                            r[e] = o[e].bind(o)
                        }), this.needNegotiation = !1, this.onicecandidate = null, this.onaddstream = null, this.ontrack = null, this.onremovestream = null, this.onsignalingstatechange = null, this.oniceconnectionstatechange = null, this.onicegatheringstatechange = null, this.onnegotiationneeded = null, this.ondatachannel = null, this.canTrickleIceCandidates = null, this.localStreams = [], this.remoteStreams = [], this.getLocalStreams = function() {
                            return r.localStreams
                        }, this.getRemoteStreams = function() {
                            return r.remoteStreams
                        }, this.localDescription = new e.RTCSessionDescription({
                            type: "",
                            sdp: ""
                        }), this.remoteDescription = new e.RTCSessionDescription({
                            type: "",
                            sdp: ""
                        }), this.signalingState = "stable", this.iceConnectionState = "new", this.iceGatheringState = "new", this.iceOptions = {
                            gatherPolicy: "all",
                            iceServers: []
                        }, n && n.iceTransportPolicy) switch (n.iceTransportPolicy) {
                        case "all":
                        case "relay":
                            this.iceOptions.gatherPolicy = n.iceTransportPolicy
                    }
                    this.usingBundle = n && "max-bundle" === n.bundlePolicy, n && n.iceServers && (this.iceOptions.iceServers = i(n.iceServers, t)), this._config = n || {}, this.transceivers = [], this._localIceCandidatesBuffer = []
                };
                return n.prototype._emitGatheringStateChange = function() {
                    var e = new Event("icegatheringstatechange");
                    this.dispatchEvent(e), null !== this.onicegatheringstatechange && this.onicegatheringstatechange(e)
                }, n.prototype._emitBufferedCandidates = function() {
                    var e = this,
                        t = s.splitSections(e.localDescription.sdp);
                    this._localIceCandidatesBuffer.forEach(function(n) {
                        if (!n.candidate || 0 === Object.keys(n.candidate).length)
                            for (var r = 1; r < t.length; r++) - 1 === t[r].indexOf("\r\na=end-of-candidates\r\n") && (t[r] += "a=end-of-candidates\r\n");
                        else t[n.candidate.sdpMLineIndex + 1] += "a=" + n.candidate.candidate + "\r\n";
                        e.localDescription.sdp = t.join(""), e.dispatchEvent(n), null !== e.onicecandidate && e.onicecandidate(n), n.candidate || "complete" === e.iceGatheringState || e.transceivers.every(function(e) {
                            return e.iceGatherer && "completed" === e.iceGatherer.state
                        }) && "complete" !== e.iceGatheringStateChange && (e.iceGatheringState = "complete", e._emitGatheringStateChange())
                    }), this._localIceCandidatesBuffer = []
                }, n.prototype.getConfiguration = function() {
                    return this._config
                }, n.prototype._createTransceiver = function(e) {
                    var t = this.transceivers.length > 0,
                        n = {
                            track: null,
                            iceGatherer: null,
                            iceTransport: null,
                            dtlsTransport: null,
                            localCapabilities: null,
                            remoteCapabilities: null,
                            rtpSender: null,
                            rtpReceiver: null,
                            kind: e,
                            mid: null,
                            sendEncodingParameters: null,
                            recvEncodingParameters: null,
                            stream: null,
                            wantReceive: !0
                        };
                    if (this.usingBundle && t) n.iceTransport = this.transceivers[0].iceTransport, n.dtlsTransport = this.transceivers[0].dtlsTransport;
                    else {
                        var r = this._createIceAndDtlsTransports();
                        n.iceTransport = r.iceTransport, n.dtlsTransport = r.dtlsTransport
                    }
                    return this.transceivers.push(n), n
                }, n.prototype.addTrack = function(t, n) {
                    for (var r, i = 0; i < this.transceivers.length; i++) this.transceivers[i].track || this.transceivers[i].kind !== t.kind || (r = this.transceivers[i]);
                    return r || (r = this._createTransceiver(t.kind)), r.track = t, r.stream = n, r.rtpSender = new e.RTCRtpSender(t, r.dtlsTransport), this._maybeFireNegotiationNeeded(), r.rtpSender
                }, n.prototype.addStream = function(e) {
                    var n = this;
                    if (t >= 15025) this.localStreams.push(e), e.getTracks().forEach(function(t) {
                        n.addTrack(t, e)
                    });
                    else {
                        var r = e.clone();
                        e.getTracks().forEach(function(e, t) {
                            var n = r.getTracks()[t];
                            e.addEventListener("enabled", function(e) {
                                n.enabled = e.enabled
                            })
                        }), r.getTracks().forEach(function(e) {
                            n.addTrack(e, r)
                        }), this.localStreams.push(r)
                    }
                    this._maybeFireNegotiationNeeded()
                }, n.prototype.removeStream = function(e) {
                    var t = this.localStreams.indexOf(e);
                    t > -1 && (this.localStreams.splice(t, 1), this._maybeFireNegotiationNeeded())
                }, n.prototype.getSenders = function() {
                    return this.transceivers.filter(function(e) {
                        return !!e.rtpSender
                    }).map(function(e) {
                        return e.rtpSender
                    })
                }, n.prototype.getReceivers = function() {
                    return this.transceivers.filter(function(e) {
                        return !!e.rtpReceiver
                    }).map(function(e) {
                        return e.rtpReceiver
                    })
                }, n.prototype._createIceGatherer = function(t, n) {
                    var r = this,
                        i = new e.RTCIceGatherer(r.iceOptions);
                    return i.onlocalcandidate = function(e) {
                        var o = new Event("icecandidate");
                        o.candidate = {
                            sdpMid: t,
                            sdpMLineIndex: n
                        };
                        var a = e.candidate,
                            c = !a || 0 === Object.keys(a).length;
                        c ? void 0 === i.state && (i.state = "completed") : (a.component = 1, o.candidate.candidate = s.writeCandidate(a));
                        var d = s.splitSections(r.localDescription.sdp);
                        d[o.candidate.sdpMLineIndex + 1] += c ? "a=end-of-candidates\r\n" : "a=" + o.candidate.candidate + "\r\n", r.localDescription.sdp = d.join("");
                        var u = (r._pendingOffer ? r._pendingOffer : r.transceivers).every(function(e) {
                            return e.iceGatherer && "completed" === e.iceGatherer.state
                        });
                        switch (r.iceGatheringState) {
                            case "new":
                                c || r._localIceCandidatesBuffer.push(o), c && u && r._localIceCandidatesBuffer.push(new Event("icecandidate"));
                                break;
                            case "gathering":
                                r._emitBufferedCandidates(), c || (r.dispatchEvent(o), null !== r.onicecandidate && r.onicecandidate(o)), u && (r.dispatchEvent(new Event("icecandidate")), null !== r.onicecandidate && r.onicecandidate(new Event("icecandidate")), r.iceGatheringState = "complete", r._emitGatheringStateChange())
                        }
                    }, i
                }, n.prototype._createIceAndDtlsTransports = function() {
                    var t = this,
                        n = new e.RTCIceTransport(null);
                    n.onicestatechange = function() {
                        t._updateConnectionState()
                    };
                    var r = new e.RTCDtlsTransport(n);
                    return r.ondtlsstatechange = function() {
                        t._updateConnectionState()
                    }, r.onerror = function() {
                        Object.defineProperty(r, "state", {
                            value: "failed",
                            writable: !0
                        }), t._updateConnectionState()
                    }, {
                        iceTransport: n,
                        dtlsTransport: r
                    }
                }, n.prototype._disposeIceAndDtlsTransports = function(e) {
                    var t = this.transceivers[e].iceGatherer;
                    t && (delete t.onlocalcandidate, delete this.transceivers[e].iceGatherer);
                    var n = this.transceivers[e].iceTransport;
                    n && (delete n.onicestatechange, delete this.transceivers[e].iceTransport);
                    var r = this.transceivers[e].dtlsTransport;
                    r && (delete r.ondtlssttatechange, delete r.onerror, delete this.transceivers[e].dtlsTransport)
                }, n.prototype._transceive = function(e, n, r) {
                    var i = o(e.localCapabilities, e.remoteCapabilities);
                    n && e.rtpSender && (i.encodings = e.sendEncodingParameters, i.rtcp = {
                        cname: s.localCName,
                        compound: e.rtcpParameters.compound
                    }, e.recvEncodingParameters.length && (i.rtcp.ssrc = e.recvEncodingParameters[0].ssrc), e.rtpSender.send(i)), r && e.rtpReceiver && ("video" === e.kind && e.recvEncodingParameters && t < 15019 && e.recvEncodingParameters.forEach(function(e) {
                        delete e.rtx
                    }), i.encodings = e.recvEncodingParameters, i.rtcp = {
                        cname: e.rtcpParameters.cname,
                        compound: e.rtcpParameters.compound
                    }, e.sendEncodingParameters.length && (i.rtcp.ssrc = e.sendEncodingParameters[0].ssrc), e.rtpReceiver.receive(i))
                }, n.prototype.setLocalDescription = function(t) {
                    var n = this;
                    if (!a("setLocalDescription", t.type, this.signalingState)) {
                        var r = new Error("Can not set local " + t.type + " in state " + this.signalingState);
                        return r.name = "InvalidStateError", arguments.length > 2 && "function" == typeof arguments[2] && e.setTimeout(arguments[2], 0, r), Promise.reject(r)
                    }
                    var i, c;
                    if ("offer" === t.type) this._pendingOffer && (i = s.splitSections(t.sdp), c = i.shift(), i.forEach(function(e, t) {
                        var r = s.parseRtpParameters(e);
                        n._pendingOffer[t].localCapabilities = r
                    }), this.transceivers = this._pendingOffer, delete this._pendingOffer);
                    else if ("answer" === t.type) {
                        i = s.splitSections(n.remoteDescription.sdp), c = i.shift();
                        var d = s.matchPrefix(c, "a=ice-lite").length > 0;
                        i.forEach(function(e, t) {
                            var r = n.transceivers[t],
                                i = r.iceGatherer,
                                a = r.iceTransport,
                                u = r.dtlsTransport,
                                p = r.localCapabilities,
                                f = r.remoteCapabilities;
                            if (!s.isRejected(e) && !r.isDatachannel) {
                                var l = s.getIceParameters(e, c),
                                    h = s.getDtlsParameters(e, c);
                                d && (h.role = "server"), n.usingBundle && 0 !== t || (a.start(i, l, d ? "controlling" : "controlled"), u.start(h));
                                var m = o(p, f);
                                n._transceive(r, m.codecs.length > 0, !1)
                            }
                        })
                    }
                    switch (this.localDescription = {
                        type: t.type,
                        sdp: t.sdp
                    }, t.type) {
                        case "offer":
                            this._updateSignalingState("have-local-offer");
                            break;
                        case "answer":
                            this._updateSignalingState("stable");
                            break;
                        default:
                            throw new TypeError('unsupported type "' + t.type + '"')
                    }
                    var u = arguments.length > 1 && "function" == typeof arguments[1];
                    if (u) {
                        var p = arguments[1];
                        e.setTimeout(function() {
                            p(), "new" === n.iceGatheringState && (n.iceGatheringState = "gathering", n._emitGatheringStateChange()), n._emitBufferedCandidates()
                        }, 0)
                    }
                    var f = Promise.resolve();
                    return f.then(function() {
                        u || ("new" === n.iceGatheringState && (n.iceGatheringState = "gathering", n._emitGatheringStateChange()), e.setTimeout(n._emitBufferedCandidates.bind(n), 500))
                    }), f
                }, n.prototype.setRemoteDescription = function(n) {
                    var r = this;
                    if (!a("setRemoteDescription", n.type, this.signalingState)) {
                        var i = new Error("Can not set remote " + n.type + " in state " + this.signalingState);
                        return i.name = "InvalidStateError", arguments.length > 2 && "function" == typeof arguments[2] && e.setTimeout(arguments[2], 0, i), Promise.reject(i)
                    }
                    var o = {},
                        c = [],
                        d = s.splitSections(n.sdp),
                        u = d.shift(),
                        p = s.matchPrefix(u, "a=ice-lite").length > 0,
                        f = s.matchPrefix(u, "a=group:BUNDLE ").length > 0;
                    this.usingBundle = f;
                    var l = s.matchPrefix(u, "a=ice-options:")[0];
                    switch (this.canTrickleIceCandidates = !!l && l.substr(14).split(" ").indexOf("trickle") >= 0, d.forEach(function(i, a) {
                        var d = s.splitLines(i),
                            l = s.getKind(i),
                            h = s.isRejected(i),
                            m = d[0].substr(2).split(" ")[2],
                            g = s.getDirection(i, u),
                            v = s.parseMsid(i),
                            y = s.getMid(i) || s.generateIdentifier();
                        if ("application" !== l || "DTLS/SCTP" !== m) {
                            var b, w, C, S, k, T, E, P, x, R, O, D = s.parseRtpParameters(i);
                            h || (R = s.getIceParameters(i, u), (O = s.getDtlsParameters(i, u)).role = "client"), E = s.parseRtpEncodingParameters(i);
                            var _ = s.parseRtcpParameters(i),
                                j = s.matchPrefix(i, "a=end-of-candidates", u).length > 0,
                                M = s.matchPrefix(i, "a=candidate:").map(function(e) {
                                    return s.parseCandidate(e)
                                }).filter(function(e) {
                                    return "1" === e.component || 1 === e.component
                                });
                            ("offer" === n.type || "answer" === n.type) && !h && f && a > 0 && r.transceivers[a] && (r._disposeIceAndDtlsTransports(a), r.transceivers[a].iceGatherer = r.transceivers[0].iceGatherer, r.transceivers[a].iceTransport = r.transceivers[0].iceTransport, r.transceivers[a].dtlsTransport = r.transceivers[0].dtlsTransport, r.transceivers[a].rtpSender && r.transceivers[a].rtpSender.setTransport(r.transceivers[0].dtlsTransport), r.transceivers[a].rtpReceiver && r.transceivers[a].rtpReceiver.setTransport(r.transceivers[0].dtlsTransport)), "offer" !== n.type || h ? "answer" !== n.type || h || (w = (b = r.transceivers[a]).iceGatherer, C = b.iceTransport, S = b.dtlsTransport, k = b.rtpReceiver, T = b.sendEncodingParameters, P = b.localCapabilities, r.transceivers[a].recvEncodingParameters = E, r.transceivers[a].remoteCapabilities = D, r.transceivers[a].rtcpParameters = _, (p || j) && M.length && C.setRemoteCandidates(M), f && 0 !== a || (C.start(w, R, "controlling"), S.start(O)), r._transceive(b, "sendrecv" === g || "recvonly" === g, "sendrecv" === g || "sendonly" === g), !k || "sendrecv" !== g && "sendonly" !== g ? delete b.rtpReceiver : (x = k.track, v ? (o[v.stream] || (o[v.stream] = new e.MediaStream), o[v.stream].addTrack(x), c.push([x, k, o[v.stream]])) : (o.default || (o.default = new e.MediaStream), o.default.addTrack(x), c.push([x, k, o.default])))) : ((b = r.transceivers[a] || r._createTransceiver(l)).mid = y, b.iceGatherer || (b.iceGatherer = f && a > 0 ? r.transceivers[0].iceGatherer : r._createIceGatherer(y, a)), !j || f && 0 !== a || b.iceTransport.setRemoteCandidates(M), P = e.RTCRtpReceiver.getCapabilities(l), t < 15019 && (P.codecs = P.codecs.filter(function(e) {
                                return "rtx" !== e.name
                            })), T = [{
                                ssrc: 1001 * (2 * a + 2)
                            }], "sendrecv" !== g && "sendonly" !== g || (x = (k = new e.RTCRtpReceiver(b.dtlsTransport, l)).track, v ? (o[v.stream] || (o[v.stream] = new e.MediaStream, Object.defineProperty(o[v.stream], "id", {
                                get: function() {
                                    return v.stream
                                }
                            })), Object.defineProperty(x, "id", {
                                get: function() {
                                    return v.track
                                }
                            }), o[v.stream].addTrack(x), c.push([x, k, o[v.stream]])) : (o.default || (o.default = new e.MediaStream), o.default.addTrack(x), c.push([x, k, o.default]))), b.localCapabilities = P, b.remoteCapabilities = D, b.rtpReceiver = k, b.rtcpParameters = _, b.sendEncodingParameters = T, b.recvEncodingParameters = E, r._transceive(r.transceivers[a], !1, "sendrecv" === g || "sendonly" === g))
                        } else r.transceivers[a] = {
                            mid: y,
                            isDatachannel: !0
                        }
                    }), this.remoteDescription = {
                        type: n.type,
                        sdp: n.sdp
                    }, n.type) {
                        case "offer":
                            this._updateSignalingState("have-remote-offer");
                            break;
                        case "answer":
                            this._updateSignalingState("stable");
                            break;
                        default:
                            throw new TypeError('unsupported type "' + n.type + '"')
                    }
                    return Object.keys(o).forEach(function(t) {
                        var n = o[t];
                        if (n.getTracks().length) {
                            r.remoteStreams.push(n);
                            var i = new Event("addstream");
                            i.stream = n, r.dispatchEvent(i), null !== r.onaddstream && e.setTimeout(function() {
                                r.onaddstream(i)
                            }, 0), c.forEach(function(t) {
                                var i = t[0],
                                    o = t[1];
                                if (n.id === t[2].id) {
                                    var a = new Event("track");
                                    a.track = i, a.receiver = o, a.streams = [n], r.dispatchEvent(a), null !== r.ontrack && e.setTimeout(function() {
                                        r.ontrack(a)
                                    }, 0)
                                }
                            })
                        }
                    }), e.setTimeout(function() {
                        r && r.transceivers && r.transceivers.forEach(function(e) {
                            e.iceTransport && "new" === e.iceTransport.state && e.iceTransport.getRemoteCandidates().length > 0 && (console.warn("Timeout for addRemoteCandidate. Consider sending an end-of-candidates notification"), e.iceTransport.addRemoteCandidate({}))
                        })
                    }, 4e3), arguments.length > 1 && "function" == typeof arguments[1] && e.setTimeout(arguments[1], 0), Promise.resolve()
                }, n.prototype.close = function() {
                    this.transceivers.forEach(function(e) {
                        e.iceTransport && e.iceTransport.stop(), e.dtlsTransport && e.dtlsTransport.stop(), e.rtpSender && e.rtpSender.stop(), e.rtpReceiver && e.rtpReceiver.stop()
                    }), this._updateSignalingState("closed")
                }, n.prototype._updateSignalingState = function(e) {
                    this.signalingState = e;
                    var t = new Event("signalingstatechange");
                    this.dispatchEvent(t), null !== this.onsignalingstatechange && this.onsignalingstatechange(t)
                }, n.prototype._maybeFireNegotiationNeeded = function() {
                    var t = this;
                    "stable" === this.signalingState && !0 !== this.needNegotiation && (this.needNegotiation = !0, e.setTimeout(function() {
                        if (!1 !== t.needNegotiation) {
                            t.needNegotiation = !1;
                            var e = new Event("negotiationneeded");
                            t.dispatchEvent(e), null !== t.onnegotiationneeded && t.onnegotiationneeded(e)
                        }
                    }, 0))
                }, n.prototype._updateConnectionState = function() {
                    var e, t = this,
                        n = {
                            new: 0,
                            closed: 0,
                            connecting: 0,
                            checking: 0,
                            connected: 0,
                            completed: 0,
                            disconnected: 0,
                            failed: 0
                        };
                    if (this.transceivers.forEach(function(e) {
                            n[e.iceTransport.state]++, n[e.dtlsTransport.state]++
                        }), n.connected += n.completed, e = "new", n.failed > 0 ? e = "failed" : n.connecting > 0 || n.checking > 0 ? e = "connecting" : n.disconnected > 0 ? e = "disconnected" : n.new > 0 ? e = "new" : (n.connected > 0 || n.completed > 0) && (e = "connected"), e !== t.iceConnectionState) {
                        t.iceConnectionState = e;
                        var r = new Event("iceconnectionstatechange");
                        this.dispatchEvent(r), null !== this.oniceconnectionstatechange && this.oniceconnectionstatechange(r)
                    }
                }, n.prototype.createOffer = function() {
                    var n = this;
                    if (this._pendingOffer) throw new Error("createOffer called while there is a pending offer.");
                    var i;
                    1 === arguments.length && "function" != typeof arguments[0] ? i = arguments[0] : 3 === arguments.length && (i = arguments[2]);
                    var o = this.transceivers.filter(function(e) {
                            return "audio" === e.kind
                        }).length,
                        a = this.transceivers.filter(function(e) {
                            return "video" === e.kind
                        }).length;
                    if (i) {
                        if (i.mandatory || i.optional) throw new TypeError("Legacy mandatory/optional constraints not supported.");
                        void 0 !== i.offerToReceiveAudio && (o = !0 === i.offerToReceiveAudio ? 1 : !1 === i.offerToReceiveAudio ? 0 : i.offerToReceiveAudio), void 0 !== i.offerToReceiveVideo && (a = !0 === i.offerToReceiveVideo ? 1 : !1 === i.offerToReceiveVideo ? 0 : i.offerToReceiveVideo)
                    }
                    for (this.transceivers.forEach(function(e) {
                            "audio" === e.kind ? --o < 0 && (e.wantReceive = !1) : "video" === e.kind && --a < 0 && (e.wantReceive = !1)
                        }); o > 0 || a > 0;) o > 0 && (this._createTransceiver("audio"), o--), a > 0 && (this._createTransceiver("video"), a--);
                    var c = r(this.transceivers),
                        d = s.writeSessionBoilerplate();
                    c.forEach(function(r, i) {
                        var o = r.track,
                            a = r.kind,
                            d = s.generateIdentifier();
                        r.mid = d, r.iceGatherer || (r.iceGatherer = n.usingBundle && i > 0 ? c[0].iceGatherer : n._createIceGatherer(d, i));
                        var u = e.RTCRtpSender.getCapabilities(a);
                        t < 15019 && (u.codecs = u.codecs.filter(function(e) {
                            return "rtx" !== e.name
                        })), u.codecs.forEach(function(e) {
                            "H264" === e.name && void 0 === e.parameters["level-asymmetry-allowed"] && (e.parameters["level-asymmetry-allowed"] = "1")
                        });
                        var p = [{
                            ssrc: 1001 * (2 * i + 1)
                        }];
                        o && t >= 15019 && "video" === a && (p[0].rtx = {
                            ssrc: 1001 * (2 * i + 1) + 1
                        }), r.wantReceive && (r.rtpReceiver = new e.RTCRtpReceiver(r.dtlsTransport, a)), r.localCapabilities = u, r.sendEncodingParameters = p
                    }), "max-compat" !== this._config.bundlePolicy && (d += "a=group:BUNDLE " + c.map(function(e) {
                        return e.mid
                    }).join(" ") + "\r\n"), d += "a=ice-options:trickle\r\n", c.forEach(function(e, t) {
                        d += s.writeMediaSection(e, e.localCapabilities, "offer", e.stream), d += "a=rtcp-rsize\r\n"
                    }), this._pendingOffer = c;
                    var u = new e.RTCSessionDescription({
                        type: "offer",
                        sdp: d
                    });
                    return arguments.length && "function" == typeof arguments[0] && e.setTimeout(arguments[0], 0, u), Promise.resolve(u)
                }, n.prototype.createAnswer = function() {
                    var n = s.writeSessionBoilerplate();
                    this.usingBundle && (n += "a=group:BUNDLE " + this.transceivers.map(function(e) {
                        return e.mid
                    }).join(" ") + "\r\n"), this.transceivers.forEach(function(e, r) {
                        if (e.isDatachannel) n += "m=application 0 DTLS/SCTP 5000\r\nc=IN IP4 0.0.0.0\r\na=mid:" + e.mid + "\r\n";
                        else {
                            if (e.stream) {
                                var i;
                                "audio" === e.kind ? i = e.stream.getAudioTracks()[0] : "video" === e.kind && (i = e.stream.getVideoTracks()[0]), i && t >= 15019 && "video" === e.kind && (e.sendEncodingParameters[0].rtx = {
                                    ssrc: 1001 * (2 * r + 2) + 1
                                })
                            }
                            var a = o(e.localCapabilities, e.remoteCapabilities);
                            !a.codecs.filter(function(e) {
                                return "rtx" === e.name.toLowerCase()
                            }).length && e.sendEncodingParameters[0].rtx && delete e.sendEncodingParameters[0].rtx, n += s.writeMediaSection(e, a, "answer", e.stream), e.rtcpParameters && e.rtcpParameters.reducedSize && (n += "a=rtcp-rsize\r\n")
                        }
                    });
                    var r = new e.RTCSessionDescription({
                        type: "answer",
                        sdp: n
                    });
                    return arguments.length && "function" == typeof arguments[0] && e.setTimeout(arguments[0], 0, r), Promise.resolve(r)
                }, n.prototype.addIceCandidate = function(t) {
                    if (t) {
                        var n = t.sdpMLineIndex;
                        if (t.sdpMid)
                            for (var r = 0; r < this.transceivers.length; r++)
                                if (this.transceivers[r].mid === t.sdpMid) {
                                    n = r;
                                    break
                                } var i = this.transceivers[n];
                        if (i) {
                            var o = Object.keys(t.candidate).length > 0 ? s.parseCandidate(t.candidate) : {};
                            if ("tcp" === o.protocol && (0 === o.port || 9 === o.port)) return Promise.resolve();
                            if (o.component && "1" !== o.component && 1 !== o.component) return Promise.resolve();
                            i.iceTransport.addRemoteCandidate(o);
                            var a = s.splitSections(this.remoteDescription.sdp);
                            a[n + 1] += (o.type ? t.candidate.trim() : "a=end-of-candidates") + "\r\n", this.remoteDescription.sdp = a.join("")
                        }
                    } else
                        for (var c = 0; c < this.transceivers.length; c++)
                            if (this.transceivers[c].iceTransport.addRemoteCandidate({}), this.usingBundle) return Promise.resolve();
                    return arguments.length > 1 && "function" == typeof arguments[1] && e.setTimeout(arguments[1], 0), Promise.resolve()
                }, n.prototype.getStats = function() {
                    var t = [];
                    this.transceivers.forEach(function(e) {
                        ["rtpSender", "rtpReceiver", "iceGatherer", "iceTransport", "dtlsTransport"].forEach(function(n) {
                            e[n] && t.push(e[n].getStats())
                        })
                    });
                    var n = arguments.length > 1 && "function" == typeof arguments[1] && arguments[1],
                        r = function(e) {
                            return {
                                inboundrtp: "inbound-rtp",
                                outboundrtp: "outbound-rtp",
                                candidatepair: "candidate-pair",
                                localcandidate: "local-candidate",
                                remotecandidate: "remote-candidate"
                            } [e.type] || e.type
                        };
                    return new Promise(function(i) {
                        var o = new Map;
                        Promise.all(t).then(function(t) {
                            t.forEach(function(e) {
                                Object.keys(e).forEach(function(t) {
                                    e[t].type = r(e[t]), o.set(t, e[t])
                                })
                            }), n && e.setTimeout(n, 0, o), i(o)
                        })
                    })
                }, n
            }
        }, {
            sdp: 1
        }],
        9: [function(e, t, n) {
            "use strict";
            var r = e("../utils"),
                i = {
                    shimOnTrack: function(e) {
                        "object" != typeof e || !e.RTCPeerConnection || "ontrack" in e.RTCPeerConnection.prototype || Object.defineProperty(e.RTCPeerConnection.prototype, "ontrack", {
                            get: function() {
                                return this._ontrack
                            },
                            set: function(e) {
                                this._ontrack && (this.removeEventListener("track", this._ontrack), this.removeEventListener("addstream", this._ontrackpoly)), this.addEventListener("track", this._ontrack = e), this.addEventListener("addstream", this._ontrackpoly = function(e) {
                                    e.stream.getTracks().forEach(function(t) {
                                        var n = new Event("track");
                                        n.track = t, n.receiver = {
                                            track: t
                                        }, n.streams = [e.stream], this.dispatchEvent(n)
                                    }.bind(this))
                                }.bind(this))
                            }
                        })
                    },
                    shimSourceObject: function(e) {
                        "object" == typeof e && (!e.HTMLMediaElement || "srcObject" in e.HTMLMediaElement.prototype || Object.defineProperty(e.HTMLMediaElement.prototype, "srcObject", {
                            get: function() {
                                return this.mozSrcObject
                            },
                            set: function(e) {
                                this.mozSrcObject = e
                            }
                        }))
                    },
                    shimPeerConnection: function(e) {
                        var t = r.detectBrowser(e);
                        if ("object" == typeof e && (e.RTCPeerConnection || e.mozRTCPeerConnection)) {
                            e.RTCPeerConnection || (e.RTCPeerConnection = function(n, r) {
                                if (t.version < 38 && n && n.iceServers) {
                                    for (var i = [], o = 0; o < n.iceServers.length; o++) {
                                        var a = n.iceServers[o];
                                        if (a.hasOwnProperty("urls"))
                                            for (var s = 0; s < a.urls.length; s++) {
                                                var c = {
                                                    url: a.urls[s]
                                                };
                                                0 === a.urls[s].indexOf("turn") && (c.username = a.username, c.credential = a.credential), i.push(c)
                                            } else i.push(n.iceServers[o])
                                    }
                                    n.iceServers = i
                                }
                                return new e.mozRTCPeerConnection(n, r)
                            }, e.RTCPeerConnection.prototype = e.mozRTCPeerConnection.prototype, e.mozRTCPeerConnection.generateCertificate && Object.defineProperty(e.RTCPeerConnection, "generateCertificate", {
                                get: function() {
                                    return e.mozRTCPeerConnection.generateCertificate
                                }
                            }), e.RTCSessionDescription = e.mozRTCSessionDescription, e.RTCIceCandidate = e.mozRTCIceCandidate), ["setLocalDescription", "setRemoteDescription", "addIceCandidate"].forEach(function(t) {
                                var n = e.RTCPeerConnection.prototype[t];
                                e.RTCPeerConnection.prototype[t] = function() {
                                    return arguments[0] = new("addIceCandidate" === t ? e.RTCIceCandidate : e.RTCSessionDescription)(arguments[0]), n.apply(this, arguments)
                                }
                            });
                            var n = e.RTCPeerConnection.prototype.addIceCandidate;
                            e.RTCPeerConnection.prototype.addIceCandidate = function() {
                                return arguments[0] ? n.apply(this, arguments) : (arguments[1] && arguments[1].apply(null), Promise.resolve())
                            };
                            var i = function(e) {
                                    var t = new Map;
                                    return Object.keys(e).forEach(function(n) {
                                        t.set(n, e[n]), t[n] = e[n]
                                    }), t
                                },
                                o = {
                                    inboundrtp: "inbound-rtp",
                                    outboundrtp: "outbound-rtp",
                                    candidatepair: "candidate-pair",
                                    localcandidate: "local-candidate",
                                    remotecandidate: "remote-candidate"
                                },
                                a = e.RTCPeerConnection.prototype.getStats;
                            e.RTCPeerConnection.prototype.getStats = function(e, n, r) {
                                return a.apply(this, [e || null]).then(function(e) {
                                    if (t.version < 48 && (e = i(e)), t.version < 53 && !n) try {
                                        e.forEach(function(e) {
                                            e.type = o[e.type] || e.type
                                        })
                                    } catch (t) {
                                        if ("TypeError" !== t.name) throw t;
                                        e.forEach(function(t, n) {
                                            e.set(n, Object.assign({}, t, {
                                                type: o[t.type] || t.type
                                            }))
                                        })
                                    }
                                    return e
                                }).then(n, r)
                            }
                        }
                    }
                };
            t.exports = {
                shimOnTrack: i.shimOnTrack,
                shimSourceObject: i.shimSourceObject,
                shimPeerConnection: i.shimPeerConnection,
                shimGetUserMedia: e("./getusermedia")
            }
        }, {
            "../utils": 12,
            "./getusermedia": 10
        }],
        10: [function(e, t, n) {
            "use strict";
            var r = e("../utils"),
                i = r.log;
            t.exports = function(e) {
                var t = r.detectBrowser(e),
                    n = e && e.navigator,
                    o = e && e.MediaStreamTrack,
                    a = function(e) {
                        return {
                            name: {
                                InternalError: "NotReadableError",
                                NotSupportedError: "TypeError",
                                PermissionDeniedError: "NotAllowedError",
                                SecurityError: "NotAllowedError"
                            } [e.name] || e.name,
                            message: {
                                "The operation is insecure.": "The request is not allowed by the user agent or the platform in the current context."
                            } [e.message] || e.message,
                            constraint: e.constraint,
                            toString: function() {
                                return this.name + (this.message && ": ") + this.message
                            }
                        }
                    },
                    s = function(e, r, o) {
                        var s = function(e) {
                            if ("object" != typeof e || e.require) return e;
                            var t = [];
                            return Object.keys(e).forEach(function(n) {
                                if ("require" !== n && "advanced" !== n && "mediaSource" !== n) {
                                    var r = e[n] = "object" == typeof e[n] ? e[n] : {
                                        ideal: e[n]
                                    };
                                    if (void 0 === r.min && void 0 === r.max && void 0 === r.exact || t.push(n), void 0 !== r.exact && ("number" == typeof r.exact ? r.min = r.max = r.exact : e[n] = r.exact, delete r.exact), void 0 !== r.ideal) {
                                        e.advanced = e.advanced || [];
                                        var i = {};
                                        "number" == typeof r.ideal ? i[n] = {
                                            min: r.ideal,
                                            max: r.ideal
                                        } : i[n] = r.ideal, e.advanced.push(i), delete r.ideal, Object.keys(r).length || delete e[n]
                                    }
                                }
                            }), t.length && (e.require = t), e
                        };
                        return e = JSON.parse(JSON.stringify(e)), t.version < 38 && (i("spec: " + JSON.stringify(e)), e.audio && (e.audio = s(e.audio)), e.video && (e.video = s(e.video)), i("ff37: " + JSON.stringify(e))), n.mozGetUserMedia(e, r, function(e) {
                            o(a(e))
                        })
                    };
                if (n.mediaDevices || (n.mediaDevices = {
                        getUserMedia: function(e) {
                            return new Promise(function(t, n) {
                                s(e, t, n)
                            })
                        },
                        addEventListener: function() {},
                        removeEventListener: function() {}
                    }), n.mediaDevices.enumerateDevices = n.mediaDevices.enumerateDevices || function() {
                        return new Promise(function(e) {
                            e([{
                                kind: "audioinput",
                                deviceId: "default",
                                label: "",
                                groupId: ""
                            }, {
                                kind: "videoinput",
                                deviceId: "default",
                                label: "",
                                groupId: ""
                            }])
                        })
                    }, t.version < 41) {
                    var c = n.mediaDevices.enumerateDevices.bind(n.mediaDevices);
                    n.mediaDevices.enumerateDevices = function() {
                        return c().then(void 0, function(e) {
                            if ("NotFoundError" === e.name) return [];
                            throw e
                        })
                    }
                }
                if (t.version < 49) {
                    var d = n.mediaDevices.getUserMedia.bind(n.mediaDevices);
                    n.mediaDevices.getUserMedia = function(e) {
                        return d(e).then(function(t) {
                            if (e.audio && !t.getAudioTracks().length || e.video && !t.getVideoTracks().length) throw t.getTracks().forEach(function(e) {
                                e.stop()
                            }), new DOMException("The object can not be found here.", "NotFoundError");
                            return t
                        }, function(e) {
                            return Promise.reject(a(e))
                        })
                    }
                }
                if (!(t.version > 55 && "autoGainControl" in n.mediaDevices.getSupportedConstraints())) {
                    var u = function(e, t, n) {
                            t in e && !(n in e) && (e[n] = e[t], delete e[t])
                        },
                        p = n.mediaDevices.getUserMedia.bind(n.mediaDevices);
                    if (n.mediaDevices.getUserMedia = function(e) {
                            return "object" == typeof e && "object" == typeof e.audio && (e = JSON.parse(JSON.stringify(e)), u(e.audio, "autoGainControl", "mozAutoGainControl"), u(e.audio, "noiseSuppression", "mozNoiseSuppression")), p(e)
                        }, o && o.prototype.getSettings) {
                        var f = o.prototype.getSettings;
                        o.prototype.getSettings = function() {
                            var e = f.apply(this, arguments);
                            return u(e, "mozAutoGainControl", "autoGainControl"), u(e, "mozNoiseSuppression", "noiseSuppression"), e
                        }
                    }
                    if (o && o.prototype.applyConstraints) {
                        var l = o.prototype.applyConstraints;
                        o.prototype.applyConstraints = function(e) {
                            return "audio" === this.kind && "object" == typeof e && (e = JSON.parse(JSON.stringify(e)), u(e, "autoGainControl", "mozAutoGainControl"), u(e, "noiseSuppression", "mozNoiseSuppression")), l.apply(this, [e])
                        }
                    }
                }
                n.getUserMedia = function(e, r, i) {
                    if (t.version < 44) return s(e, r, i);
                    console.warn("navigator.getUserMedia has been replaced by navigator.mediaDevices.getUserMedia"), n.mediaDevices.getUserMedia(e).then(r, i)
                }
            }
        }, {
            "../utils": 12
        }],
        11: [function(e, t, n) {
            "use strict";
            var r = {
                shimAddStream: function(e) {
                    "object" != typeof e || !e.RTCPeerConnection || "addStream" in e.RTCPeerConnection.prototype || (e.RTCPeerConnection.prototype.addStream = function(e) {
                        var t = this;
                        e.getTracks().forEach(function(n) {
                            t.addTrack(n, e)
                        })
                    })
                },
                shimOnAddStream: function(e) {
                    "object" != typeof e || !e.RTCPeerConnection || "onaddstream" in e.RTCPeerConnection.prototype || Object.defineProperty(e.RTCPeerConnection.prototype, "onaddstream", {
                        get: function() {
                            return this._onaddstream
                        },
                        set: function(e) {
                            this._onaddstream && (this.removeEventListener("addstream", this._onaddstream), this.removeEventListener("track", this._onaddstreampoly)), this.addEventListener("addstream", this._onaddstream = e), this.addEventListener("track", this._onaddstreampoly = function(e) {
                                var t = e.streams[0];
                                if (this._streams || (this._streams = []), !(this._streams.indexOf(t) >= 0)) {
                                    this._streams.push(t);
                                    var n = new Event("addstream");
                                    n.stream = e.streams[0], this.dispatchEvent(n)
                                }
                            }.bind(this))
                        }
                    })
                },
                shimCallbacksAPI: function(e) {
                    if ("object" == typeof e && e.RTCPeerConnection) {
                        var t = e.RTCPeerConnection.prototype,
                            n = t.createOffer,
                            r = t.createAnswer,
                            i = t.setLocalDescription,
                            o = t.setRemoteDescription,
                            a = t.addIceCandidate;
                        t.createOffer = function(e, t) {
                            var r = arguments.length >= 2 ? arguments[2] : arguments[0],
                                i = n.apply(this, [r]);
                            return t ? (i.then(e, t), Promise.resolve()) : i
                        }, t.createAnswer = function(e, t) {
                            var n = arguments.length >= 2 ? arguments[2] : arguments[0],
                                i = r.apply(this, [n]);
                            return t ? (i.then(e, t), Promise.resolve()) : i
                        };
                        var s = function(e, t, n) {
                            var r = i.apply(this, [e]);
                            return n ? (r.then(t, n), Promise.resolve()) : r
                        };
                        t.setLocalDescription = s, s = function(e, t, n) {
                            var r = o.apply(this, [e]);
                            return n ? (r.then(t, n), Promise.resolve()) : r
                        }, t.setRemoteDescription = s, s = function(e, t, n) {
                            var r = a.apply(this, [e]);
                            return n ? (r.then(t, n), Promise.resolve()) : r
                        }, t.addIceCandidate = s
                    }
                },
                shimGetUserMedia: function(e) {
                    var t = e && e.navigator;
                    t.getUserMedia || (t.webkitGetUserMedia ? t.getUserMedia = t.webkitGetUserMedia.bind(t) : t.mediaDevices && t.mediaDevices.getUserMedia && (t.getUserMedia = function(e, n, r) {
                        t.mediaDevices.getUserMedia(e).then(n, r)
                    }.bind(t)))
                }
            };
            t.exports = {
                shimCallbacksAPI: r.shimCallbacksAPI,
                shimAddStream: r.shimAddStream,
                shimOnAddStream: r.shimOnAddStream,
                shimGetUserMedia: r.shimGetUserMedia
            }
        }, {}],
        12: [function(e, t, n) {
            "use strict";
            var r = !0,
                i = {
                    disableLog: function(e) {
                        return "boolean" != typeof e ? new Error("Argument type: " + typeof e + ". Please use a boolean.") : (r = e, e ? "adapter.js logging disabled" : "adapter.js logging enabled")
                    },
                    log: function() {
                        if ("object" == typeof window) {
                            if (r) return;
                            "undefined" != typeof console && "function" == typeof console.log && console.log.apply(console, arguments)
                        }
                    },
                    extractVersion: function(e, t, n) {
                        var r = e.match(t);
                        return r && r.length >= n && parseInt(r[n], 10)
                    },
                    detectBrowser: function(e) {
                        var t = e && e.navigator,
                            n = {};
                        if (n.browser = null, n.version = null, void 0 === e || !e.navigator) return n.browser = "Not a browser.", n;
                        if (t.mozGetUserMedia) n.browser = "firefox", n.version = this.extractVersion(t.userAgent, /Firefox\/(\d+)\./, 1);
                        else if (t.webkitGetUserMedia)
                            if (e.webkitRTCPeerConnection) n.browser = "chrome", n.version = this.extractVersion(t.userAgent, /Chrom(e|ium)\/(\d+)\./, 2);
                            else {
                                if (!t.userAgent.match(/Version\/(\d+).(\d+)/)) return n.browser = "Unsupported webkit-based browser with GUM support but no WebRTC support.", n;
                                n.browser = "safari", n.version = this.extractVersion(t.userAgent, /AppleWebKit\/(\d+)\./, 1)
                            }
                        else if (t.mediaDevices && t.userAgent.match(/Edge\/(\d+).(\d+)$/)) n.browser = "edge", n.version = this.extractVersion(t.userAgent, /Edge\/(\d+).(\d+)$/, 2);
                        else {
                            if (!t.mediaDevices || !t.userAgent.match(/AppleWebKit\/(\d+)\./)) return n.browser = "Not a supported browser.", n;
                            n.browser = "safari", n.version = this.extractVersion(t.userAgent, /AppleWebKit\/(\d+)\./, 1)
                        }
                        return n
                    },
                    shimCreateObjectURL: function(e) {
                        var t = e && e.URL;
                        if ("object" == typeof e && e.HTMLMediaElement && "srcObject" in e.HTMLMediaElement.prototype) {
                            var n = t.createObjectURL.bind(t),
                                r = t.revokeObjectURL.bind(t),
                                i = new Map,
                                o = 0;
                            t.createObjectURL = function(e) {
                                if ("getTracks" in e) {
                                    var t = "polyblob:" + ++o;
                                    return i.set(t, e), console.log("URL.createObjectURL(stream) is deprecated! Use elem.srcObject = stream instead!"), t
                                }
                                return n(e)
                            }, t.revokeObjectURL = function(e) {
                                r(e), i.delete(e)
                            };
                            var a = Object.getOwnPropertyDescriptor(e.HTMLMediaElement.prototype, "src");
                            Object.defineProperty(e.HTMLMediaElement.prototype, "src", {
                                get: function() {
                                    return a.get.apply(this)
                                },
                                set: function(e) {
                                    return this.srcObject = i.get(e) || null, a.set.apply(this, [e])
                                }
                            });
                            var s = e.HTMLMediaElement.prototype.setAttribute;
                            e.HTMLMediaElement.prototype.setAttribute = function() {
                                return 2 === arguments.length && "src" === ("" + arguments[0]).toLowerCase() && (this.srcObject = i.get(arguments[1]) || null), s.apply(this, arguments)
                            }
                        }
                    }
                };
            t.exports = {
                log: i.log,
                disableLog: i.disableLog,
                extractVersion: i.extractVersion,
                shimCreateObjectURL: i.shimCreateObjectURL,
                detectBrowser: i.detectBrowser.bind(i)
            }
        }, {}]
    }, {}, [2])(2)
}),
function(e) {
    if ("object" == typeof exports && "undefined" != typeof module) module.exports = e();
    else if ("function" == typeof define && define.amd) define([], e);
    else {
        ("undefined" != typeof window ? window : "undefined" != typeof global ? global : "undefined" != typeof self ? self : this).SimpleWebRTC = e()
    }
}(function() {
    return function e(t, n, r) {
        function i(a, s) {
            if (!n[a]) {
                if (!t[a]) {
                    var c = "function" == typeof require && require;
                    if (!s && c) return c(a, !0);
                    if (o) return o(a, !0);
                    var d = new Error("Cannot find module '" + a + "'");
                    throw d.code = "MODULE_NOT_FOUND", d
                }
                var u = n[a] = {
                    exports: {}
                };
                t[a][0].call(u.exports, function(e) {
                    var n = t[a][1][e];
                    return i(n || e)
                }, u, u.exports, e, t, n, r)
            }
            return n[a].exports
        }
        for (var o = "function" == typeof require && require, a = 0; a < r.length; a++) i(r[a]);
        return i
    }({
        1: [function(e, t, n) {
            function r() {}
            t.exports = function(e, t, n) {
                function i(e, r) {
                    if (i.count <= 0) throw new Error("after called too many times");
                    --i.count, e ? (o = !0, t(e), t = n) : 0 !== i.count || o || t(null, r)
                }
                var o = !1;
                return n = n || r, i.count = e, 0 === e ? t() : i
            }
        }, {}],
        2: [function(e, t, n) {
            t.exports = function(e, t, n) {
                var r = e.byteLength;
                if (t = t || 0, n = n || r, e.slice) return e.slice(t, n);
                if (t < 0 && (t += r), n < 0 && (n += r), n > r && (n = r), t >= r || t >= n || 0 === r) return new ArrayBuffer(0);
                for (var i = new Uint8Array(e), o = new Uint8Array(n - t), a = t, s = 0; a < n; a++, s++) o[s] = i[a];
                return o.buffer
            }
        }, {}],
        3: [function(e, t, n) {
            e("webrtc-adapter");
            t.exports = function(e, t, n) {
                window.URL;
                var r, i = t,
                    o = {
                        autoplay: !0,
                        mirror: !1,
                        muted: !1,
                        audio: !1,
                        disableContextMenu: !1
                    };
                if (n)
                    for (r in n) o[r] = n[r];
                return i ? "audio" === i.tagName.toLowerCase() && (o.audio = !0) : i = document.createElement(o.audio ? "audio" : "video"), o.disableContextMenu && (i.oncontextmenu = function(e) {
                    e.preventDefault()
                }), o.autoplay && (i.autoplay = "autoplay"), o.muted && (i.muted = !0), !o.audio && o.mirror && ["", "moz", "webkit", "o", "ms"].forEach(function(e) {
                    var t = e ? e + "Transform" : "transform";
                    i.style[t] = "scaleX(-1)"
                }), i.srcObject = e, i
            }
        }, {
            "webrtc-adapter": 4
        }],
        4: [function(e, t, n) {
            "use strict";
            ! function() {
                var n = e("./utils").log,
                    r = e("./utils").browserDetails;
                t.exports.browserDetails = r, t.exports.extractVersion = e("./utils").extractVersion, t.exports.disableLog = e("./utils").disableLog;
                var i = e("./chrome/chrome_shim") || null,
                    o = e("./edge/edge_shim") || null,
                    a = e("./firefox/firefox_shim") || null,
                    s = e("./safari/safari_shim") || null;
                switch (r.browser) {
                    case "opera":
                    case "chrome":
                        if (!i || !i.shimPeerConnection) return void n("Chrome shim is not included in this adapter release.");
                        n("adapter.js shimming chrome."), t.exports.browserShim = i, i.shimGetUserMedia(), i.shimMediaStream(), i.shimSourceObject(), i.shimPeerConnection(), i.shimOnTrack();
                        break;
                    case "firefox":
                        if (!a || !a.shimPeerConnection) return void n("Firefox shim is not included in this adapter release.");
                        n("adapter.js shimming firefox."), t.exports.browserShim = a, a.shimGetUserMedia(), a.shimSourceObject(), a.shimPeerConnection(), a.shimOnTrack();
                        break;
                    case "edge":
                        if (!o || !o.shimPeerConnection) return void n("MS edge shim is not included in this adapter release.");
                        n("adapter.js shimming edge."), t.exports.browserShim = o, o.shimGetUserMedia(), o.shimPeerConnection();
                        break;
                    case "safari":
                        if (!s) return void n("Safari shim is not included in this adapter release.");
                        n("adapter.js shimming safari."), t.exports.browserShim = s, s.shimGetUserMedia();
                        break;
                    default:
                        n("Unsupported browser!")
                }
            }()
        }, {
            "./chrome/chrome_shim": 5,
            "./edge/edge_shim": 7,
            "./firefox/firefox_shim": 9,
            "./safari/safari_shim": 11,
            "./utils": 12
        }],
        5: [function(e, t, n) {
            "use strict";
            var r = e("../utils.js").log,
                i = e("../utils.js").browserDetails,
                o = {
                    shimMediaStream: function() {
                        window.MediaStream = window.MediaStream || window.webkitMediaStream
                    },
                    shimOnTrack: function() {
                        "object" != typeof window || !window.RTCPeerConnection || "ontrack" in window.RTCPeerConnection.prototype || Object.defineProperty(window.RTCPeerConnection.prototype, "ontrack", {
                            get: function() {
                                return this._ontrack
                            },
                            set: function(e) {
                                var t = this;
                                this._ontrack && (this.removeEventListener("track", this._ontrack), this.removeEventListener("addstream", this._ontrackpoly)), this.addEventListener("track", this._ontrack = e), this.addEventListener("addstream", this._ontrackpoly = function(e) {
                                    e.stream.addEventListener("addtrack", function(n) {
                                        var r = new Event("track");
                                        r.track = n.track, r.receiver = {
                                            track: n.track
                                        }, r.streams = [e.stream], t.dispatchEvent(r)
                                    }), e.stream.getTracks().forEach(function(t) {
                                        var n = new Event("track");
                                        n.track = t, n.receiver = {
                                            track: t
                                        }, n.streams = [e.stream], this.dispatchEvent(n)
                                    }.bind(this))
                                }.bind(this))
                            }
                        })
                    },
                    shimSourceObject: function() {
                        "object" == typeof window && (!window.HTMLMediaElement || "srcObject" in window.HTMLMediaElement.prototype || Object.defineProperty(window.HTMLMediaElement.prototype, "srcObject", {
                            get: function() {
                                return this._srcObject
                            },
                            set: function(e) {
                                var t = this;
                                this._srcObject = e, this.src && URL.revokeObjectURL(this.src), e ? (this.src = URL.createObjectURL(e), e.addEventListener("addtrack", function() {
                                    t.src && URL.revokeObjectURL(t.src), t.src = URL.createObjectURL(e)
                                }), e.addEventListener("removetrack", function() {
                                    t.src && URL.revokeObjectURL(t.src), t.src = URL.createObjectURL(e)
                                })) : this.src = ""
                            }
                        }))
                    },
                    shimPeerConnection: function() {
                        window.RTCPeerConnection = function(e, t) {
                            r("PeerConnection"), e && e.iceTransportPolicy && (e.iceTransports = e.iceTransportPolicy);
                            var n = new webkitRTCPeerConnection(e, t),
                                i = n.getStats.bind(n);
                            return n.getStats = function(e, t, n) {
                                var r = this,
                                    o = arguments;
                                if (arguments.length > 0 && "function" == typeof e) return i(e, t);
                                var a = function(e) {
                                        var t = {};
                                        return e.result().forEach(function(e) {
                                            var n = {
                                                id: e.id,
                                                timestamp: e.timestamp,
                                                type: e.type
                                            };
                                            e.names().forEach(function(t) {
                                                n[t] = e.stat(t)
                                            }), t[n.id] = n
                                        }), t
                                    },
                                    s = function(e, t) {
                                        var n = new Map(Object.keys(e).map(function(t) {
                                            return [t, e[t]]
                                        }));
                                        return t = t || e, Object.keys(t).forEach(function(e) {
                                            n[e] = t[e]
                                        }), n
                                    };
                                if (arguments.length >= 2) {
                                    return i.apply(this, [function(e) {
                                        o[1](s(a(e)))
                                    }, arguments[0]])
                                }
                                return new Promise(function(t, n) {
                                    1 === o.length && "object" == typeof e ? i.apply(r, [function(e) {
                                        t(s(a(e)))
                                    }, n]) : i.apply(r, [function(e) {
                                        t(s(a(e), e.result()))
                                    }, n])
                                }).then(t, n)
                            }, n
                        }, window.RTCPeerConnection.prototype = webkitRTCPeerConnection.prototype, webkitRTCPeerConnection.generateCertificate && Object.defineProperty(window.RTCPeerConnection, "generateCertificate", {
                            get: function() {
                                return webkitRTCPeerConnection.generateCertificate
                            }
                        }), ["createOffer", "createAnswer"].forEach(function(e) {
                            var t = webkitRTCPeerConnection.prototype[e];
                            webkitRTCPeerConnection.prototype[e] = function() {
                                var e = this;
                                if (arguments.length < 1 || 1 === arguments.length && "object" == typeof arguments[0]) {
                                    var n = 1 === arguments.length ? arguments[0] : void 0;
                                    return new Promise(function(r, i) {
                                        t.apply(e, [r, i, n])
                                    })
                                }
                                return t.apply(this, arguments)
                            }
                        }), i.version < 51 && ["setLocalDescription", "setRemoteDescription", "addIceCandidate"].forEach(function(e) {
                            var t = webkitRTCPeerConnection.prototype[e];
                            webkitRTCPeerConnection.prototype[e] = function() {
                                var e = arguments,
                                    n = this,
                                    r = new Promise(function(r, i) {
                                        t.apply(n, [e[0], r, i])
                                    });
                                return e.length < 2 ? r : r.then(function() {
                                    e[1].apply(null, [])
                                }, function(t) {
                                    e.length >= 3 && e[2].apply(null, [t])
                                })
                            }
                        }), ["setLocalDescription", "setRemoteDescription", "addIceCandidate"].forEach(function(e) {
                            var t = webkitRTCPeerConnection.prototype[e];
                            webkitRTCPeerConnection.prototype[e] = function() {
                                return arguments[0] = new("addIceCandidate" === e ? RTCIceCandidate : RTCSessionDescription)(arguments[0]), t.apply(this, arguments)
                            }
                        });
                        var e = RTCPeerConnection.prototype.addIceCandidate;
                        RTCPeerConnection.prototype.addIceCandidate = function() {
                            return arguments[0] ? e.apply(this, arguments) : (arguments[1] && arguments[1].apply(null), Promise.resolve())
                        }
                    }
                };
            t.exports = {
                shimMediaStream: o.shimMediaStream,
                shimOnTrack: o.shimOnTrack,
                shimSourceObject: o.shimSourceObject,
                shimPeerConnection: o.shimPeerConnection,
                shimGetUserMedia: e("./getusermedia")
            }
        }, {
            "../utils.js": 12,
            "./getusermedia": 6
        }],
        6: [function(e, t, n) {
            "use strict";
            var r = e("../utils.js").log;
            t.exports = function() {
                var e = function(e) {
                        if ("object" != typeof e || e.mandatory || e.optional) return e;
                        var t = {};
                        return Object.keys(e).forEach(function(n) {
                            if ("require" !== n && "advanced" !== n && "mediaSource" !== n) {
                                var r = "object" == typeof e[n] ? e[n] : {
                                    ideal: e[n]
                                };
                                void 0 !== r.exact && "number" == typeof r.exact && (r.min = r.max = r.exact);
                                var i = function(e, t) {
                                    return e ? e + t.charAt(0).toUpperCase() + t.slice(1) : "deviceId" === t ? "sourceId" : t
                                };
                                if (void 0 !== r.ideal) {
                                    t.optional = t.optional || [];
                                    var o = {};
                                    "number" == typeof r.ideal ? (o[i("min", n)] = r.ideal, t.optional.push(o), (o = {})[i("max", n)] = r.ideal, t.optional.push(o)) : (o[i("", n)] = r.ideal, t.optional.push(o))
                                }
                                void 0 !== r.exact && "number" != typeof r.exact ? (t.mandatory = t.mandatory || {}, t.mandatory[i("", n)] = r.exact) : ["min", "max"].forEach(function(e) {
                                    void 0 !== r[e] && (t.mandatory = t.mandatory || {}, t.mandatory[i(e, n)] = r[e])
                                })
                            }
                        }), e.advanced && (t.optional = (t.optional || []).concat(e.advanced)), t
                    },
                    t = function(t, n) {
                        if ((t = JSON.parse(JSON.stringify(t))) && t.audio && (t.audio = e(t.audio)), t && "object" == typeof t.video) {
                            var i = t.video.facingMode;
                            if ((i = i && ("object" == typeof i ? i : {
                                    ideal: i
                                })) && ("user" === i.exact || "environment" === i.exact || "user" === i.ideal || "environment" === i.ideal) && (!navigator.mediaDevices.getSupportedConstraints || !navigator.mediaDevices.getSupportedConstraints().facingMode) && (delete t.video.facingMode, "environment" === i.exact || "environment" === i.ideal)) return navigator.mediaDevices.enumerateDevices().then(function(o) {
                                var a = (o = o.filter(function(e) {
                                    return "videoinput" === e.kind
                                })).find(function(e) {
                                    return -1 !== e.label.toLowerCase().indexOf("back")
                                }) || o.length && o[o.length - 1];
                                return a && (t.video.deviceId = i.exact ? {
                                    exact: a.deviceId
                                } : {
                                    ideal: a.deviceId
                                }), t.video = e(t.video), r("chrome: " + JSON.stringify(t)), n(t)
                            });
                            t.video = e(t.video)
                        }
                        return r("chrome: " + JSON.stringify(t)), n(t)
                    },
                    n = function(e) {
                        return {
                            name: {
                                PermissionDeniedError: "NotAllowedError",
                                ConstraintNotSatisfiedError: "OverconstrainedError"
                            } [e.name] || e.name,
                            message: e.message,
                            constraint: e.constraintName,
                            toString: function() {
                                return this.name + (this.message && ": ") + this.message
                            }
                        }
                    };
                navigator.getUserMedia = function(e, r, i) {
                    t(e, function(e) {
                        navigator.webkitGetUserMedia(e, r, function(e) {
                            i(n(e))
                        })
                    })
                };
                var i = function(e) {
                    return new Promise(function(t, n) {
                        navigator.getUserMedia(e, t, n)
                    })
                };
                if (navigator.mediaDevices || (navigator.mediaDevices = {
                        getUserMedia: i,
                        enumerateDevices: function() {
                            return new Promise(function(e) {
                                var t = {
                                    audio: "audioinput",
                                    video: "videoinput"
                                };
                                return MediaStreamTrack.getSources(function(n) {
                                    e(n.map(function(e) {
                                        return {
                                            label: e.label,
                                            kind: t[e.kind],
                                            deviceId: e.id,
                                            groupId: ""
                                        }
                                    }))
                                })
                            })
                        }
                    }), navigator.mediaDevices.getUserMedia) {
                    var o = navigator.mediaDevices.getUserMedia.bind(navigator.mediaDevices);
                    navigator.mediaDevices.getUserMedia = function(e) {
                        return t(e, function(e) {
                            return o(e).then(function(t) {
                                if (e.audio && !t.getAudioTracks().length || e.video && !t.getVideoTracks().length) throw t.getTracks().forEach(function(e) {
                                    e.stop()
                                }), new DOMException("", "NotFoundError");
                                return t
                            }, function(e) {
                                return Promise.reject(n(e))
                            })
                        })
                    }
                } else navigator.mediaDevices.getUserMedia = function(e) {
                    return i(e)
                };
                void 0 === navigator.mediaDevices.addEventListener && (navigator.mediaDevices.addEventListener = function() {
                    r("Dummy mediaDevices.addEventListener called.")
                }), void 0 === navigator.mediaDevices.removeEventListener && (navigator.mediaDevices.removeEventListener = function() {
                    r("Dummy mediaDevices.removeEventListener called.")
                })
            }
        }, {
            "../utils.js": 12
        }],
        7: [function(e, t, n) {
            "use strict";
            var r = e("sdp"),
                i = e("../utils").browserDetails,
                o = {
                    shimPeerConnection: function() {
                        if (window.RTCIceGatherer) {
                            window.RTCIceCandidate || (window.RTCIceCandidate = function(e) {
                                return e
                            }), window.RTCSessionDescription || (window.RTCSessionDescription = function(e) {
                                return e
                            });
                            var e = Object.getOwnPropertyDescriptor(MediaStreamTrack.prototype, "enabled");
                            Object.defineProperty(MediaStreamTrack.prototype, "enabled", {
                                set: function(t) {
                                    e.set.call(this, t);
                                    var n = new Event("enabled");
                                    n.enabled = t, this.dispatchEvent(n)
                                }
                            })
                        }
                        window.RTCPeerConnection = function(e) {
                            var t = this,
                                n = document.createDocumentFragment();
                            if (["addEventListener", "removeEventListener", "dispatchEvent"].forEach(function(e) {
                                    t[e] = n[e].bind(n)
                                }), this.onicecandidate = null, this.onaddstream = null, this.ontrack = null, this.onremovestream = null, this.onsignalingstatechange = null, this.oniceconnectionstatechange = null, this.onnegotiationneeded = null, this.ondatachannel = null, this.localStreams = [], this.remoteStreams = [], this.getLocalStreams = function() {
                                    return t.localStreams
                                }, this.getRemoteStreams = function() {
                                    return t.remoteStreams
                                }, this.localDescription = new RTCSessionDescription({
                                    type: "",
                                    sdp: ""
                                }), this.remoteDescription = new RTCSessionDescription({
                                    type: "",
                                    sdp: ""
                                }), this.signalingState = "stable", this.iceConnectionState = "new", this.iceGatheringState = "new", this.iceOptions = {
                                    gatherPolicy: "all",
                                    iceServers: []
                                }, e && e.iceTransportPolicy) switch (e.iceTransportPolicy) {
                                case "all":
                                case "relay":
                                    this.iceOptions.gatherPolicy = e.iceTransportPolicy;
                                    break;
                                case "none":
                                    throw new TypeError('iceTransportPolicy "none" not supported')
                            }
                            if (this.usingBundle = e && "max-bundle" === e.bundlePolicy, e && e.iceServers) {
                                var r = JSON.parse(JSON.stringify(e.iceServers));
                                this.iceOptions.iceServers = r.filter(function(e) {
                                    if (e && e.urls) {
                                        var t = e.urls;
                                        return "string" == typeof t && (t = [t]), !!(t = t.filter(function(e) {
                                            return 0 === e.indexOf("turn:") && -1 !== e.indexOf("transport=udp") && -1 === e.indexOf("turn:[") || 0 === e.indexOf("stun:") && i.version >= 14393
                                        })[0])
                                    }
                                    return !1
                                })
                            }
                            this._config = e, this.transceivers = [], this._localIceCandidatesBuffer = []
                        }, window.RTCPeerConnection.prototype._emitBufferedCandidates = function() {
                            var e = this,
                                t = r.splitSections(e.localDescription.sdp);
                            this._localIceCandidatesBuffer.forEach(function(n) {
                                if (!n.candidate || 0 === Object.keys(n.candidate).length)
                                    for (var r = 1; r < t.length; r++) - 1 === t[r].indexOf("\r\na=end-of-candidates\r\n") && (t[r] += "a=end-of-candidates\r\n");
                                else -1 === n.candidate.candidate.indexOf("typ endOfCandidates") && (t[n.candidate.sdpMLineIndex + 1] += "a=" + n.candidate.candidate + "\r\n");
                                e.localDescription.sdp = t.join(""), e.dispatchEvent(n), null !== e.onicecandidate && e.onicecandidate(n), n.candidate || "complete" === e.iceGatheringState || e.transceivers.every(function(e) {
                                    return e.iceGatherer && "completed" === e.iceGatherer.state
                                }) && (e.iceGatheringState = "complete")
                            }), this._localIceCandidatesBuffer = []
                        }, window.RTCPeerConnection.prototype.getConfiguration = function() {
                            return this._config
                        }, window.RTCPeerConnection.prototype.addStream = function(e) {
                            var t = e.clone();
                            e.getTracks().forEach(function(e, n) {
                                var r = t.getTracks()[n];
                                e.addEventListener("enabled", function(e) {
                                    r.enabled = e.enabled
                                })
                            }), this.localStreams.push(t), this._maybeFireNegotiationNeeded()
                        }, window.RTCPeerConnection.prototype.removeStream = function(e) {
                            var t = this.localStreams.indexOf(e);
                            t > -1 && (this.localStreams.splice(t, 1), this._maybeFireNegotiationNeeded())
                        }, window.RTCPeerConnection.prototype.getSenders = function() {
                            return this.transceivers.filter(function(e) {
                                return !!e.rtpSender
                            }).map(function(e) {
                                return e.rtpSender
                            })
                        }, window.RTCPeerConnection.prototype.getReceivers = function() {
                            return this.transceivers.filter(function(e) {
                                return !!e.rtpReceiver
                            }).map(function(e) {
                                return e.rtpReceiver
                            })
                        }, window.RTCPeerConnection.prototype._getCommonCapabilities = function(e, t) {
                            var n = {
                                codecs: [],
                                headerExtensions: [],
                                fecMechanisms: []
                            };
                            return e.codecs.forEach(function(e) {
                                for (var r = 0; r < t.codecs.length; r++) {
                                    var i = t.codecs[r];
                                    if (e.name.toLowerCase() === i.name.toLowerCase() && e.clockRate === i.clockRate) {
                                        i.numChannels = Math.min(e.numChannels, i.numChannels), n.codecs.push(i), i.rtcpFeedback = i.rtcpFeedback.filter(function(t) {
                                            for (var n = 0; n < e.rtcpFeedback.length; n++)
                                                if (e.rtcpFeedback[n].type === t.type && e.rtcpFeedback[n].parameter === t.parameter) return !0;
                                            return !1
                                        });
                                        break
                                    }
                                }
                            }), e.headerExtensions.forEach(function(e) {
                                for (var r = 0; r < t.headerExtensions.length; r++) {
                                    var i = t.headerExtensions[r];
                                    if (e.uri === i.uri) {
                                        n.headerExtensions.push(i);
                                        break
                                    }
                                }
                            }), n
                        }, window.RTCPeerConnection.prototype._createIceAndDtlsTransports = function(e, t) {
                            var n = this,
                                i = new RTCIceGatherer(n.iceOptions),
                                o = new RTCIceTransport(i);
                            i.onlocalcandidate = function(a) {
                                var s = new Event("icecandidate");
                                s.candidate = {
                                    sdpMid: e,
                                    sdpMLineIndex: t
                                };
                                var c = a.candidate,
                                    d = !c || 0 === Object.keys(c).length;
                                d ? (void 0 === i.state && (i.state = "completed"), s.candidate.candidate = "candidate:1 1 udp 1 0.0.0.0 9 typ endOfCandidates") : (c.component = "RTCP" === o.component ? 2 : 1, s.candidate.candidate = r.writeCandidate(c));
                                var u = r.splitSections(n.localDescription.sdp); - 1 === s.candidate.candidate.indexOf("typ endOfCandidates") ? u[s.candidate.sdpMLineIndex + 1] += "a=" + s.candidate.candidate + "\r\n" : u[s.candidate.sdpMLineIndex + 1] += "a=end-of-candidates\r\n", n.localDescription.sdp = u.join("");
                                var p = n.transceivers.every(function(e) {
                                    return e.iceGatherer && "completed" === e.iceGatherer.state
                                });
                                switch (n.iceGatheringState) {
                                    case "new":
                                        n._localIceCandidatesBuffer.push(s), d && p && n._localIceCandidatesBuffer.push(new Event("icecandidate"));
                                        break;
                                    case "gathering":
                                        n._emitBufferedCandidates(), n.dispatchEvent(s), null !== n.onicecandidate && n.onicecandidate(s), p && (n.dispatchEvent(new Event("icecandidate")), null !== n.onicecandidate && n.onicecandidate(new Event("icecandidate")), n.iceGatheringState = "complete")
                                }
                            }, o.onicestatechange = function() {
                                n._updateConnectionState()
                            };
                            var a = new RTCDtlsTransport(o);
                            return a.ondtlsstatechange = function() {
                                n._updateConnectionState()
                            }, a.onerror = function() {
                                a.state = "failed", n._updateConnectionState()
                            }, {
                                iceGatherer: i,
                                iceTransport: o,
                                dtlsTransport: a
                            }
                        }, window.RTCPeerConnection.prototype._transceive = function(e, t, n) {
                            var i = this._getCommonCapabilities(e.localCapabilities, e.remoteCapabilities);
                            t && e.rtpSender && (i.encodings = e.sendEncodingParameters, i.rtcp = {
                                cname: r.localCName
                            }, e.recvEncodingParameters.length && (i.rtcp.ssrc = e.recvEncodingParameters[0].ssrc), e.rtpSender.send(i)), n && e.rtpReceiver && ("video" === e.kind && e.recvEncodingParameters && e.recvEncodingParameters.forEach(function(e) {
                                delete e.rtx
                            }), i.encodings = e.recvEncodingParameters, i.rtcp = {
                                cname: e.cname
                            }, e.sendEncodingParameters.length && (i.rtcp.ssrc = e.sendEncodingParameters[0].ssrc), e.rtpReceiver.receive(i))
                        }, window.RTCPeerConnection.prototype.setLocalDescription = function(e) {
                            var t, n, i = this;
                            if ("offer" === e.type) this._pendingOffer && (t = r.splitSections(e.sdp), n = t.shift(), t.forEach(function(e, t) {
                                var n = r.parseRtpParameters(e);
                                i._pendingOffer[t].localCapabilities = n
                            }), this.transceivers = this._pendingOffer, delete this._pendingOffer);
                            else if ("answer" === e.type) {
                                t = r.splitSections(i.remoteDescription.sdp), n = t.shift();
                                var o = r.matchPrefix(n, "a=ice-lite").length > 0;
                                t.forEach(function(e, t) {
                                    var a = i.transceivers[t],
                                        s = a.iceGatherer,
                                        c = a.iceTransport,
                                        d = a.dtlsTransport,
                                        u = a.localCapabilities,
                                        p = a.remoteCapabilities;
                                    if (!("0" === e.split("\n", 1)[0].split(" ", 2)[1]) && !a.isDatachannel) {
                                        var f = r.getIceParameters(e, n);
                                        if (o) {
                                            var l = r.matchPrefix(e, "a=candidate:").map(function(e) {
                                                return r.parseCandidate(e)
                                            }).filter(function(e) {
                                                return "1" === e.component
                                            });
                                            l.length && c.setRemoteCandidates(l)
                                        }
                                        var h = r.getDtlsParameters(e, n);
                                        o && (h.role = "server"), i.usingBundle && 0 !== t || (c.start(s, f, o ? "controlling" : "controlled"), d.start(h));
                                        var m = i._getCommonCapabilities(u, p);
                                        i._transceive(a, m.codecs.length > 0, !1)
                                    }
                                })
                            }
                            switch (this.localDescription = {
                                type: e.type,
                                sdp: e.sdp
                            }, e.type) {
                                case "offer":
                                    this._updateSignalingState("have-local-offer");
                                    break;
                                case "answer":
                                    this._updateSignalingState("stable");
                                    break;
                                default:
                                    throw new TypeError('unsupported type "' + e.type + '"')
                            }
                            var a = arguments.length > 1 && "function" == typeof arguments[1];
                            if (a) {
                                var s = arguments[1];
                                window.setTimeout(function() {
                                    s(), "new" === i.iceGatheringState && (i.iceGatheringState = "gathering"), i._emitBufferedCandidates()
                                }, 0)
                            }
                            var c = Promise.resolve();
                            return c.then(function() {
                                a || ("new" === i.iceGatheringState && (i.iceGatheringState = "gathering"), window.setTimeout(i._emitBufferedCandidates.bind(i), 500))
                            }), c
                        }, window.RTCPeerConnection.prototype.setRemoteDescription = function(e) {
                            var t = this,
                                n = new MediaStream,
                                i = [],
                                o = r.splitSections(e.sdp),
                                a = o.shift(),
                                s = r.matchPrefix(a, "a=ice-lite").length > 0;
                            switch (this.usingBundle = r.matchPrefix(a, "a=group:BUNDLE ").length > 0, o.forEach(function(o, c) {
                                var d = r.splitLines(o)[0].substr(2).split(" "),
                                    u = d[0],
                                    p = "0" === d[1],
                                    f = r.getDirection(o, a),
                                    l = r.matchPrefix(o, "a=mid:");
                                if (l = l.length ? l[0].substr(6) : r.generateIdentifier(), "application" !== u || "DTLS/SCTP" !== d[2]) {
                                    var h, m, g, v, y, b, w, C, S, k, T, E, P = r.parseRtpParameters(o);
                                    p || (T = r.getIceParameters(o, a), (E = r.getDtlsParameters(o, a)).role = "client"), C = r.parseRtpEncodingParameters(o);
                                    var x, R = r.matchPrefix(o, "a=ssrc:").map(function(e) {
                                        return r.parseSsrcMedia(e)
                                    }).filter(function(e) {
                                        return "cname" === e.attribute
                                    })[0];
                                    R && (x = R.value);
                                    var O = r.matchPrefix(o, "a=end-of-candidates", a).length > 0,
                                        D = r.matchPrefix(o, "a=candidate:").map(function(e) {
                                            return r.parseCandidate(e)
                                        }).filter(function(e) {
                                            return "1" === e.component
                                        });
                                    if ("offer" !== e.type || p) "answer" !== e.type || p || (m = (h = t.transceivers[c]).iceGatherer, g = h.iceTransport, v = h.dtlsTransport, y = h.rtpSender, b = h.rtpReceiver, w = h.sendEncodingParameters, S = h.localCapabilities, t.transceivers[c].recvEncodingParameters = C, t.transceivers[c].remoteCapabilities = P, t.transceivers[c].cname = x, (s || O) && D.length && g.setRemoteCandidates(D), t.usingBundle && 0 !== c || (g.start(m, T, "controlling"), v.start(E)), t._transceive(h, "sendrecv" === f || "recvonly" === f, "sendrecv" === f || "sendonly" === f), !b || "sendrecv" !== f && "sendonly" !== f ? delete h.rtpReceiver : (k = b.track, i.push([k, b]), n.addTrack(k)));
                                    else {
                                        var _ = t.usingBundle && c > 0 ? {
                                            iceGatherer: t.transceivers[0].iceGatherer,
                                            iceTransport: t.transceivers[0].iceTransport,
                                            dtlsTransport: t.transceivers[0].dtlsTransport
                                        } : t._createIceAndDtlsTransports(l, c);
                                        if (O && _.iceTransport.setRemoteCandidates(D), S = RTCRtpReceiver.getCapabilities(u), S.codecs = S.codecs.filter(function(e) {
                                                return "rtx" !== e.name
                                            }), w = [{
                                                ssrc: 1001 * (2 * c + 2)
                                            }], b = new RTCRtpReceiver(_.dtlsTransport, u), k = b.track, i.push([k, b]), n.addTrack(k), t.localStreams.length > 0 && t.localStreams[0].getTracks().length >= c) {
                                            var j;
                                            "audio" === u ? j = t.localStreams[0].getAudioTracks()[0] : "video" === u && (j = t.localStreams[0].getVideoTracks()[0]), j && (y = new RTCRtpSender(j, _.dtlsTransport))
                                        }
                                        t.transceivers[c] = {
                                            iceGatherer: _.iceGatherer,
                                            iceTransport: _.iceTransport,
                                            dtlsTransport: _.dtlsTransport,
                                            localCapabilities: S,
                                            remoteCapabilities: P,
                                            rtpSender: y,
                                            rtpReceiver: b,
                                            kind: u,
                                            mid: l,
                                            cname: x,
                                            sendEncodingParameters: w,
                                            recvEncodingParameters: C
                                        }, t._transceive(t.transceivers[c], !1, "sendrecv" === f || "sendonly" === f)
                                    }
                                } else t.transceivers[c] = {
                                    mid: l,
                                    isDatachannel: !0
                                }
                            }), this.remoteDescription = {
                                type: e.type,
                                sdp: e.sdp
                            }, e.type) {
                                case "offer":
                                    this._updateSignalingState("have-remote-offer");
                                    break;
                                case "answer":
                                    this._updateSignalingState("stable");
                                    break;
                                default:
                                    throw new TypeError('unsupported type "' + e.type + '"')
                            }
                            return n.getTracks().length && (t.remoteStreams.push(n), window.setTimeout(function() {
                                var e = new Event("addstream");
                                e.stream = n, t.dispatchEvent(e), null !== t.onaddstream && window.setTimeout(function() {
                                    t.onaddstream(e)
                                }, 0), i.forEach(function(r) {
                                    var i = r[0],
                                        o = r[1],
                                        a = new Event("track");
                                    a.track = i, a.receiver = o, a.streams = [n], t.dispatchEvent(e), null !== t.ontrack && window.setTimeout(function() {
                                        t.ontrack(a)
                                    }, 0)
                                })
                            }, 0)), arguments.length > 1 && "function" == typeof arguments[1] && window.setTimeout(arguments[1], 0), Promise.resolve()
                        }, window.RTCPeerConnection.prototype.close = function() {
                            this.transceivers.forEach(function(e) {
                                e.iceTransport && e.iceTransport.stop(), e.dtlsTransport && e.dtlsTransport.stop(), e.rtpSender && e.rtpSender.stop(), e.rtpReceiver && e.rtpReceiver.stop()
                            }), this._updateSignalingState("closed")
                        }, window.RTCPeerConnection.prototype._updateSignalingState = function(e) {
                            this.signalingState = e;
                            var t = new Event("signalingstatechange");
                            this.dispatchEvent(t), null !== this.onsignalingstatechange && this.onsignalingstatechange(t)
                        }, window.RTCPeerConnection.prototype._maybeFireNegotiationNeeded = function() {
                            var e = new Event("negotiationneeded");
                            this.dispatchEvent(e), null !== this.onnegotiationneeded && this.onnegotiationneeded(e)
                        }, window.RTCPeerConnection.prototype._updateConnectionState = function() {
                            var e, t = this,
                                n = {
                                    new: 0,
                                    closed: 0,
                                    connecting: 0,
                                    checking: 0,
                                    connected: 0,
                                    completed: 0,
                                    failed: 0
                                };
                            if (this.transceivers.forEach(function(e) {
                                    n[e.iceTransport.state]++, n[e.dtlsTransport.state]++
                                }), n.connected += n.completed, e = "new", n.failed > 0 ? e = "failed" : n.connecting > 0 || n.checking > 0 ? e = "connecting" : n.disconnected > 0 ? e = "disconnected" : n.new > 0 ? e = "new" : (n.connected > 0 || n.completed > 0) && (e = "connected"), e !== t.iceConnectionState) {
                                t.iceConnectionState = e;
                                var r = new Event("iceconnectionstatechange");
                                this.dispatchEvent(r), null !== this.oniceconnectionstatechange && this.oniceconnectionstatechange(r)
                            }
                        }, window.RTCPeerConnection.prototype.createOffer = function() {
                            var e = this;
                            if (this._pendingOffer) throw new Error("createOffer called while there is a pending offer.");
                            var t;
                            1 === arguments.length && "function" != typeof arguments[0] ? t = arguments[0] : 3 === arguments.length && (t = arguments[2]);
                            var n = [],
                                i = 0,
                                o = 0;
                            if (this.localStreams.length && (i = this.localStreams[0].getAudioTracks().length, o = this.localStreams[0].getVideoTracks().length), t) {
                                if (t.mandatory || t.optional) throw new TypeError("Legacy mandatory/optional constraints not supported.");
                                void 0 !== t.offerToReceiveAudio && (i = t.offerToReceiveAudio), void 0 !== t.offerToReceiveVideo && (o = t.offerToReceiveVideo)
                            }
                            for (this.localStreams.length && this.localStreams[0].getTracks().forEach(function(e) {
                                    n.push({
                                        kind: e.kind,
                                        track: e,
                                        wantReceive: "audio" === e.kind ? i > 0 : o > 0
                                    }), "audio" === e.kind ? i-- : "video" === e.kind && o--
                                }); i > 0 || o > 0;) i > 0 && (n.push({
                                kind: "audio",
                                wantReceive: !0
                            }), i--), o > 0 && (n.push({
                                kind: "video",
                                wantReceive: !0
                            }), o--);
                            var a = r.writeSessionBoilerplate(),
                                s = [];
                            n.forEach(function(t, n) {
                                var i = t.track,
                                    o = t.kind,
                                    a = r.generateIdentifier(),
                                    c = e.usingBundle && n > 0 ? {
                                        iceGatherer: s[0].iceGatherer,
                                        iceTransport: s[0].iceTransport,
                                        dtlsTransport: s[0].dtlsTransport
                                    } : e._createIceAndDtlsTransports(a, n),
                                    d = RTCRtpSender.getCapabilities(o);
                                d.codecs = d.codecs.filter(function(e) {
                                    return "rtx" !== e.name
                                }), d.codecs.forEach(function(e) {
                                    "H264" === e.name && void 0 === e.parameters["level-asymmetry-allowed"] && (e.parameters["level-asymmetry-allowed"] = "1")
                                });
                                var u, p, f = [{
                                    ssrc: 1001 * (2 * n + 1)
                                }];
                                i && (u = new RTCRtpSender(i, c.dtlsTransport)), t.wantReceive && (p = new RTCRtpReceiver(c.dtlsTransport, o)), s[n] = {
                                    iceGatherer: c.iceGatherer,
                                    iceTransport: c.iceTransport,
                                    dtlsTransport: c.dtlsTransport,
                                    localCapabilities: d,
                                    remoteCapabilities: null,
                                    rtpSender: u,
                                    rtpReceiver: p,
                                    kind: o,
                                    mid: a,
                                    sendEncodingParameters: f,
                                    recvEncodingParameters: null
                                }
                            }), this.usingBundle && (a += "a=group:BUNDLE " + s.map(function(e) {
                                return e.mid
                            }).join(" ") + "\r\n"), n.forEach(function(t, n) {
                                var i = s[n];
                                a += r.writeMediaSection(i, i.localCapabilities, "offer", e.localStreams[0])
                            }), this._pendingOffer = s;
                            var c = new RTCSessionDescription({
                                type: "offer",
                                sdp: a
                            });
                            return arguments.length && "function" == typeof arguments[0] && window.setTimeout(arguments[0], 0, c), Promise.resolve(c)
                        }, window.RTCPeerConnection.prototype.createAnswer = function() {
                            var e = this,
                                t = r.writeSessionBoilerplate();
                            this.usingBundle && (t += "a=group:BUNDLE " + this.transceivers.map(function(e) {
                                return e.mid
                            }).join(" ") + "\r\n"), this.transceivers.forEach(function(n) {
                                if (n.isDatachannel) t += "m=application 0 DTLS/SCTP 5000\r\nc=IN IP4 0.0.0.0\r\na=mid:" + n.mid + "\r\n";
                                else {
                                    var i = e._getCommonCapabilities(n.localCapabilities, n.remoteCapabilities);
                                    t += r.writeMediaSection(n, i, "answer", e.localStreams[0])
                                }
                            });
                            var n = new RTCSessionDescription({
                                type: "answer",
                                sdp: t
                            });
                            return arguments.length && "function" == typeof arguments[0] && window.setTimeout(arguments[0], 0, n), Promise.resolve(n)
                        }, window.RTCPeerConnection.prototype.addIceCandidate = function(e) {
                            if (e) {
                                var t = e.sdpMLineIndex;
                                if (e.sdpMid)
                                    for (var n = 0; n < this.transceivers.length; n++)
                                        if (this.transceivers[n].mid === e.sdpMid) {
                                            t = n;
                                            break
                                        } var i = this.transceivers[t];
                                if (i) {
                                    var o = Object.keys(e.candidate).length > 0 ? r.parseCandidate(e.candidate) : {};
                                    if ("tcp" === o.protocol && (0 === o.port || 9 === o.port)) return;
                                    if ("1" !== o.component) return;
                                    "endOfCandidates" === o.type && (o = {}), i.iceTransport.addRemoteCandidate(o);
                                    var a = r.splitSections(this.remoteDescription.sdp);
                                    a[t + 1] += (o.type ? e.candidate.trim() : "a=end-of-candidates") + "\r\n", this.remoteDescription.sdp = a.join("")
                                }
                            } else this.transceivers.forEach(function(e) {
                                e.iceTransport.addRemoteCandidate({})
                            });
                            return arguments.length > 1 && "function" == typeof arguments[1] && window.setTimeout(arguments[1], 0), Promise.resolve()
                        }, window.RTCPeerConnection.prototype.getStats = function() {
                            var e = [];
                            this.transceivers.forEach(function(t) {
                                ["rtpSender", "rtpReceiver", "iceGatherer", "iceTransport", "dtlsTransport"].forEach(function(n) {
                                    t[n] && e.push(t[n].getStats())
                                })
                            });
                            var t = arguments.length > 1 && "function" == typeof arguments[1] && arguments[1];
                            return new Promise(function(n) {
                                var r = new Map;
                                Promise.all(e).then(function(e) {
                                    e.forEach(function(e) {
                                        Object.keys(e).forEach(function(t) {
                                            r.set(t, e[t]), r[t] = e[t]
                                        })
                                    }), t && window.setTimeout(t, 0, r), n(r)
                                })
                            })
                        }
                    }
                };
            t.exports = {
                shimPeerConnection: o.shimPeerConnection,
                shimGetUserMedia: e("./getusermedia")
            }
        }, {
            "../utils": 12,
            "./getusermedia": 8,
            sdp: 59
        }],
        8: [function(e, t, n) {
            "use strict";
            t.exports = function() {
                var e = function(e) {
                        return {
                            name: {
                                PermissionDeniedError: "NotAllowedError"
                            } [e.name] || e.name,
                            message: e.message,
                            constraint: e.constraint,
                            toString: function() {
                                return this.name
                            }
                        }
                    },
                    t = navigator.mediaDevices.getUserMedia.bind(navigator.mediaDevices);
                navigator.mediaDevices.getUserMedia = function(n) {
                    return t(n).catch(function(t) {
                        return Promise.reject(e(t))
                    })
                }
            }
        }, {}],
        9: [function(e, t, n) {
            "use strict";
            var r = e("../utils").browserDetails,
                i = {
                    shimOnTrack: function() {
                        "object" != typeof window || !window.RTCPeerConnection || "ontrack" in window.RTCPeerConnection.prototype || Object.defineProperty(window.RTCPeerConnection.prototype, "ontrack", {
                            get: function() {
                                return this._ontrack
                            },
                            set: function(e) {
                                this._ontrack && (this.removeEventListener("track", this._ontrack), this.removeEventListener("addstream", this._ontrackpoly)), this.addEventListener("track", this._ontrack = e), this.addEventListener("addstream", this._ontrackpoly = function(e) {
                                    e.stream.getTracks().forEach(function(t) {
                                        var n = new Event("track");
                                        n.track = t, n.receiver = {
                                            track: t
                                        }, n.streams = [e.stream], this.dispatchEvent(n)
                                    }.bind(this))
                                }.bind(this))
                            }
                        })
                    },
                    shimSourceObject: function() {
                        "object" == typeof window && (!window.HTMLMediaElement || "srcObject" in window.HTMLMediaElement.prototype || Object.defineProperty(window.HTMLMediaElement.prototype, "srcObject", {
                            get: function() {
                                return this.mozSrcObject
                            },
                            set: function(e) {
                                this.mozSrcObject = e
                            }
                        }))
                    },
                    shimPeerConnection: function() {
                        if ("object" == typeof window && (window.RTCPeerConnection || window.mozRTCPeerConnection)) {
                            window.RTCPeerConnection || (window.RTCPeerConnection = function(e, t) {
                                if (r.version < 38 && e && e.iceServers) {
                                    for (var n = [], i = 0; i < e.iceServers.length; i++) {
                                        var o = e.iceServers[i];
                                        if (o.hasOwnProperty("urls"))
                                            for (var a = 0; a < o.urls.length; a++) {
                                                var s = {
                                                    url: o.urls[a]
                                                };
                                                0 === o.urls[a].indexOf("turn") && (s.username = o.username, s.credential = o.credential), n.push(s)
                                            } else n.push(e.iceServers[i])
                                    }
                                    e.iceServers = n
                                }
                                return new mozRTCPeerConnection(e, t)
                            }, window.RTCPeerConnection.prototype = mozRTCPeerConnection.prototype, mozRTCPeerConnection.generateCertificate && Object.defineProperty(window.RTCPeerConnection, "generateCertificate", {
                                get: function() {
                                    return mozRTCPeerConnection.generateCertificate
                                }
                            }), window.RTCSessionDescription = mozRTCSessionDescription, window.RTCIceCandidate = mozRTCIceCandidate), ["setLocalDescription", "setRemoteDescription", "addIceCandidate"].forEach(function(e) {
                                var t = RTCPeerConnection.prototype[e];
                                RTCPeerConnection.prototype[e] = function() {
                                    return arguments[0] = new("addIceCandidate" === e ? RTCIceCandidate : RTCSessionDescription)(arguments[0]), t.apply(this, arguments)
                                }
                            });
                            var e = RTCPeerConnection.prototype.addIceCandidate;
                            if (RTCPeerConnection.prototype.addIceCandidate = function() {
                                    return arguments[0] ? e.apply(this, arguments) : (arguments[1] && arguments[1].apply(null), Promise.resolve())
                                }, r.version < 48) {
                                var t = function(e) {
                                        var t = new Map;
                                        return Object.keys(e).forEach(function(n) {
                                            t.set(n, e[n]), t[n] = e[n]
                                        }), t
                                    },
                                    n = RTCPeerConnection.prototype.getStats;
                                RTCPeerConnection.prototype.getStats = function(e, r, i) {
                                    return n.apply(this, [e || null]).then(function(e) {
                                        return t(e)
                                    }).then(r, i)
                                }
                            }
                        }
                    }
                };
            t.exports = {
                shimOnTrack: i.shimOnTrack,
                shimSourceObject: i.shimSourceObject,
                shimPeerConnection: i.shimPeerConnection,
                shimGetUserMedia: e("./getusermedia")
            }
        }, {
            "../utils": 12,
            "./getusermedia": 10
        }],
        10: [function(e, t, n) {
            "use strict";
            var r = e("../utils").log,
                i = e("../utils").browserDetails;
            t.exports = function() {
                var e = function(e) {
                        return {
                            name: {
                                SecurityError: "NotAllowedError",
                                PermissionDeniedError: "NotAllowedError"
                            } [e.name] || e.name,
                            message: {
                                "The operation is insecure.": "The request is not allowed by the user agent or the platform in the current context."
                            } [e.message] || e.message,
                            constraint: e.constraint,
                            toString: function() {
                                return this.name + (this.message && ": ") + this.message
                            }
                        }
                    },
                    t = function(t, n, o) {
                        var a = function(e) {
                            if ("object" != typeof e || e.require) return e;
                            var t = [];
                            return Object.keys(e).forEach(function(n) {
                                if ("require" !== n && "advanced" !== n && "mediaSource" !== n) {
                                    var r = e[n] = "object" == typeof e[n] ? e[n] : {
                                        ideal: e[n]
                                    };
                                    if (void 0 === r.min && void 0 === r.max && void 0 === r.exact || t.push(n), void 0 !== r.exact && ("number" == typeof r.exact ? r.min = r.max = r.exact : e[n] = r.exact, delete r.exact), void 0 !== r.ideal) {
                                        e.advanced = e.advanced || [];
                                        var i = {};
                                        "number" == typeof r.ideal ? i[n] = {
                                            min: r.ideal,
                                            max: r.ideal
                                        } : i[n] = r.ideal, e.advanced.push(i), delete r.ideal, Object.keys(r).length || delete e[n]
                                    }
                                }
                            }), t.length && (e.require = t), e
                        };
                        return t = JSON.parse(JSON.stringify(t)), i.version < 38 && (r("spec: " + JSON.stringify(t)), t.audio && (t.audio = a(t.audio)), t.video && (t.video = a(t.video)), r("ff37: " + JSON.stringify(t))), navigator.mozGetUserMedia(t, n, function(t) {
                            o(e(t))
                        })
                    };
                if (navigator.mediaDevices || (navigator.mediaDevices = {
                        getUserMedia: function(e) {
                            return new Promise(function(n, r) {
                                t(e, n, r)
                            })
                        },
                        addEventListener: function() {},
                        removeEventListener: function() {}
                    }), navigator.mediaDevices.enumerateDevices = navigator.mediaDevices.enumerateDevices || function() {
                        return new Promise(function(e) {
                            e([{
                                kind: "audioinput",
                                deviceId: "default",
                                label: "",
                                groupId: ""
                            }, {
                                kind: "videoinput",
                                deviceId: "default",
                                label: "",
                                groupId: ""
                            }])
                        })
                    }, i.version < 41) {
                    var n = navigator.mediaDevices.enumerateDevices.bind(navigator.mediaDevices);
                    navigator.mediaDevices.enumerateDevices = function() {
                        return n().then(void 0, function(e) {
                            if ("NotFoundError" === e.name) return [];
                            throw e
                        })
                    }
                }
                if (i.version < 49) {
                    var o = navigator.mediaDevices.getUserMedia.bind(navigator.mediaDevices);
                    navigator.mediaDevices.getUserMedia = function(t) {
                        return o(t).then(function(e) {
                            if (t.audio && !e.getAudioTracks().length || t.video && !e.getVideoTracks().length) throw e.getTracks().forEach(function(e) {
                                e.stop()
                            }), new DOMException("The object can not be found here.", "NotFoundError");
                            return e
                        }, function(t) {
                            return Promise.reject(e(t))
                        })
                    }
                }
                navigator.getUserMedia = function(e, n, r) {
                    if (i.version < 44) return t(e, n, r);
                    console.warn("navigator.getUserMedia has been replaced by navigator.mediaDevices.getUserMedia"), navigator.mediaDevices.getUserMedia(e).then(n, r)
                }
            }
        }, {
            "../utils": 12
        }],
        11: [function(e, t, n) {
            "use strict";
            var r = {
                shimGetUserMedia: function() {
                    navigator.getUserMedia = navigator.webkitGetUserMedia
                }
            };
            t.exports = {
                shimGetUserMedia: r.shimGetUserMedia
            }
        }, {}],
        12: [function(e, t, n) {
            "use strict";
            var r = !0,
                i = {
                    disableLog: function(e) {
                        return "boolean" != typeof e ? new Error("Argument type: " + typeof e + ". Please use a boolean.") : (r = e, e ? "adapter.js logging disabled" : "adapter.js logging enabled")
                    },
                    log: function() {
                        if ("object" == typeof window) {
                            if (r) return;
                            "undefined" != typeof console && "function" == typeof console.log && console.log.apply(console, arguments)
                        }
                    },
                    extractVersion: function(e, t, n) {
                        var r = e.match(t);
                        return r && r.length >= n && parseInt(r[n], 10)
                    },
                    detectBrowser: function() {
                        var e = {};
                        if (e.browser = null, e.version = null, "undefined" == typeof window || !window.navigator) return e.browser = "Not a browser.", e;
                        if (navigator.mozGetUserMedia) e.browser = "firefox", e.version = this.extractVersion(navigator.userAgent, /Firefox\/([0-9]+)\./, 1);
                        else if (navigator.webkitGetUserMedia)
                            if (window.webkitRTCPeerConnection) e.browser = "chrome", e.version = this.extractVersion(navigator.userAgent, /Chrom(e|ium)\/([0-9]+)\./, 2);
                            else {
                                if (!navigator.userAgent.match(/Version\/(\d+).(\d+)/)) return e.browser = "Unsupported webkit-based browser with GUM support but no WebRTC support.", e;
                                e.browser = "safari", e.version = this.extractVersion(navigator.userAgent, /AppleWebKit\/([0-9]+)\./, 1)
                            }
                        else {
                            if (!navigator.mediaDevices || !navigator.userAgent.match(/Edge\/(\d+).(\d+)$/)) return e.browser = "Not a supported browser.", e;
                            e.browser = "edge", e.version = this.extractVersion(navigator.userAgent, /Edge\/(\d+).(\d+)$/, 2)
                        }
                        return e
                    }
                };
            t.exports = {
                log: i.log,
                disableLog: i.disableLog,
                browserDetails: i.detectBrowser(),
                extractVersion: i.extractVersion
            }
        }, {}],
        13: [function(e, t, n) {
            function r(e) {
                e = e || {}, this.ms = e.min || 100, this.max = e.max || 1e4, this.factor = e.factor || 2, this.jitter = e.jitter > 0 && e.jitter <= 1 ? e.jitter : 0, this.attempts = 0
            }
            t.exports = r, r.prototype.duration = function() {
                var e = this.ms * Math.pow(this.factor, this.attempts++);
                if (this.jitter) {
                    var t = Math.random(),
                        n = Math.floor(t * this.jitter * e);
                    e = 0 == (1 & Math.floor(10 * t)) ? e - n : e + n
                }
                return 0 | Math.min(e, this.max)
            }, r.prototype.reset = function() {
                this.attempts = 0
            }, r.prototype.setMin = function(e) {
                this.ms = e
            }, r.prototype.setMax = function(e) {
                this.max = e
            }, r.prototype.setJitter = function(e) {
                this.jitter = e
            }
        }, {}],
        14: [function(e, t, n) {
            ! function(e) {
                "use strict";
                n.encode = function(t) {
                    var n, r = new Uint8Array(t),
                        i = r.length,
                        o = "";
                    for (n = 0; n < i; n += 3) o += e[r[n] >> 2], o += e[(3 & r[n]) << 4 | r[n + 1] >> 4], o += e[(15 & r[n + 1]) << 2 | r[n + 2] >> 6], o += e[63 & r[n + 2]];
                    return i % 3 == 2 ? o = o.substring(0, o.length - 1) + "=" : i % 3 == 1 && (o = o.substring(0, o.length - 2) + "=="), o
                }, n.decode = function(t) {
                    var n, r, i, o, a, s = .75 * t.length,
                        c = t.length,
                        d = 0;
                    "=" === t[t.length - 1] && (s--, "=" === t[t.length - 2] && s--);
                    var u = new ArrayBuffer(s),
                        p = new Uint8Array(u);
                    for (n = 0; n < c; n += 4) r = e.indexOf(t[n]), i = e.indexOf(t[n + 1]), o = e.indexOf(t[n + 2]), a = e.indexOf(t[n + 3]), p[d++] = r << 2 | i >> 4, p[d++] = (15 & i) << 4 | o >> 2, p[d++] = (3 & o) << 6 | 63 & a;
                    return u
                }
            }("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/")
        }, {}],
        15: [function(e, t, n) {
            (function(e) {
                function n(e) {
                    for (var t = 0; t < e.length; t++) {
                        var n = e[t];
                        if (n.buffer instanceof ArrayBuffer) {
                            var r = n.buffer;
                            if (n.byteLength !== r.byteLength) {
                                var i = new Uint8Array(n.byteLength);
                                i.set(new Uint8Array(r, n.byteOffset, n.byteLength)), r = i.buffer
                            }
                            e[t] = r
                        }
                    }
                }

                function r(e, t) {
                    t = t || {};
                    var r = new o;
                    n(e);
                    for (var i = 0; i < e.length; i++) r.append(e[i]);
                    return t.type ? r.getBlob(t.type) : r.getBlob()
                }

                function i(e, t) {
                    return n(e), new Blob(e, t || {})
                }
                var o = e.BlobBuilder || e.WebKitBlobBuilder || e.MSBlobBuilder || e.MozBlobBuilder,
                    a = function() {
                        try {
                            return 2 === new Blob(["hi"]).size
                        } catch (e) {
                            return !1
                        }
                    }(),
                    s = a && function() {
                        try {
                            return 2 === new Blob([new Uint8Array([1, 2])]).size
                        } catch (e) {
                            return !1
                        }
                    }(),
                    c = o && o.prototype.append && o.prototype.getBlob;
                t.exports = a ? s ? e.Blob : i : c ? r : void 0
            }).call(this, "undefined" != typeof global ? global : "undefined" != typeof self ? self : "undefined" != typeof window ? window : {})
        }, {}],
        16: [function(e, t, n) {
            var r = [].slice;
            t.exports = function(e, t) {
                if ("string" == typeof t && (t = e[t]), "function" != typeof t) throw new Error("bind() requires a function");
                var n = r.call(arguments, 2);
                return function() {
                    return t.apply(e, n.concat(r.call(arguments)))
                }
            }
        }, {}],
        17: [function(e, t, n) {
            function r(e) {
                if (e) return i(e)
            }

            function i(e) {
                for (var t in r.prototype) e[t] = r.prototype[t];
                return e
            }
            t.exports = r, r.prototype.on = r.prototype.addEventListener = function(e, t) {
                return this._callbacks = this._callbacks || {}, (this._callbacks[e] = this._callbacks[e] || []).push(t), this
            }, r.prototype.once = function(e, t) {
                function n() {
                    r.off(e, n), t.apply(this, arguments)
                }
                var r = this;
                return this._callbacks = this._callbacks || {}, n.fn = t, this.on(e, n), this
            }, r.prototype.off = r.prototype.removeListener = r.prototype.removeAllListeners = r.prototype.removeEventListener = function(e, t) {
                if (this._callbacks = this._callbacks || {}, 0 == arguments.length) return this._callbacks = {}, this;
                var n = this._callbacks[e];
                if (!n) return this;
                if (1 == arguments.length) return delete this._callbacks[e], this;
                for (var r, i = 0; i < n.length; i++)
                    if ((r = n[i]) === t || r.fn === t) {
                        n.splice(i, 1);
                        break
                    } return this
            }, r.prototype.emit = function(e) {
                this._callbacks = this._callbacks || {};
                var t = [].slice.call(arguments, 1),
                    n = this._callbacks[e];
                if (n)
                    for (var r = 0, i = (n = n.slice(0)).length; r < i; ++r) n[r].apply(this, t);
                return this
            }, r.prototype.listeners = function(e) {
                return this._callbacks = this._callbacks || {}, this._callbacks[e] || []
            }, r.prototype.hasListeners = function(e) {
                return !!this.listeners(e).length
            }
        }, {}],
        18: [function(e, t, n) {
            t.exports = function(e, t) {
                var n = function() {};
                n.prototype = t.prototype, e.prototype = new n, e.prototype.constructor = e
            }
        }, {}],
        19: [function(e, t, n) {
            function r(e) {
                return r.enabled(e) ? function(t) {
                    t = i(t);
                    var n = new Date,
                        o = n - (r[e] || n);
                    r[e] = n, t = e + " " + t + " +" + r.humanize(o), window.console && console.log && Function.prototype.apply.call(console.log, console, arguments)
                } : function() {}
            }

            function i(e) {
                return e instanceof Error ? e.stack || e.message : e
            }
            t.exports = r, r.names = [], r.skips = [], r.enable = function(e) {
                try {
                    localStorage.debug = e
                } catch (e) {}
                for (var t = (e || "").split(/[\s,]+/), n = t.length, i = 0; i < n; i++) "-" === (e = t[i].replace("*", ".*?"))[0] ? r.skips.push(new RegExp("^" + e.substr(1) + "$")) : r.names.push(new RegExp("^" + e + "$"))
            }, r.disable = function() {
                r.enable("")
            }, r.humanize = function(e) {
                return e >= 36e5 ? (e / 36e5).toFixed(1) + "h" : e >= 6e4 ? (e / 6e4).toFixed(1) + "m" : e >= 1e3 ? (e / 1e3 | 0) + "s" : e + "ms"
            }, r.enabled = function(e) {
                for (var t = 0, n = r.skips.length; t < n; t++)
                    if (r.skips[t].test(e)) return !1;
                for (var t = 0, n = r.names.length; t < n; t++)
                    if (r.names[t].test(e)) return !0;
                return !1
            };
            try {
                window.localStorage && r.enable(localStorage.debug)
            } catch (e) {}
        }, {}],
        20: [function(e, t, n) {
            t.exports = e("./lib/")
        }, {
            "./lib/": 21
        }],
        21: [function(e, t, n) {
            t.exports = e("./socket"), t.exports.parser = e("engine.io-parser")
        }, {
            "./socket": 22,
            "engine.io-parser": 33
        }],
        22: [function(e, t, n) {
            (function(n) {
                function r(e, t) {
                    if (!(this instanceof r)) return new r(e, t);
                    if (t = t || {}, e && "object" == typeof e && (t = e, e = null), e && (e = u(e), t.host = e.host, t.secure = "https" == e.protocol || "wss" == e.protocol, t.port = e.port, e.query && (t.query = e.query)), this.secure = null != t.secure ? t.secure : n.location && "https:" == location.protocol, t.host) {
                        var i = t.host.split(":");
                        t.hostname = i.shift(), i.length ? t.port = i.pop() : t.port || (t.port = this.secure ? "443" : "80")
                    }
                    this.agent = t.agent || !1, this.hostname = t.hostname || (n.location ? location.hostname : "localhost"), this.port = t.port || (n.location && location.port ? location.port : this.secure ? 443 : 80), this.query = t.query || {}, "string" == typeof this.query && (this.query = f.decode(this.query)), this.upgrade = !1 !== t.upgrade, this.path = (t.path || "/engine.io").replace(/\/$/, "") + "/", this.forceJSONP = !!t.forceJSONP, this.jsonp = !1 !== t.jsonp, this.forceBase64 = !!t.forceBase64, this.enablesXDR = !!t.enablesXDR, this.timestampParam = t.timestampParam || "t", this.timestampRequests = t.timestampRequests, this.transports = t.transports || ["polling", "websocket"], this.readyState = "", this.writeBuffer = [], this.callbackBuffer = [], this.policyPort = t.policyPort || 843, this.rememberUpgrade = t.rememberUpgrade || !1, this.binaryType = null, this.onlyBinaryUpgrades = t.onlyBinaryUpgrades, this.pfx = t.pfx || null, this.key = t.key || null, this.passphrase = t.passphrase || null, this.cert = t.cert || null, this.ca = t.ca || null, this.ciphers = t.ciphers || null, this.rejectUnauthorized = t.rejectUnauthorized || null, this.open()
                }

                function i(e) {
                    var t = {};
                    for (var n in e) e.hasOwnProperty(n) && (t[n] = e[n]);
                    return t
                }
                var o = e("./transports"),
                    a = e("component-emitter"),
                    s = e("debug")("engine.io-client:socket"),
                    c = e("indexof"),
                    d = e("engine.io-parser"),
                    u = e("parseuri"),
                    p = e("parsejson"),
                    f = e("parseqs");
                t.exports = r, r.priorWebsocketSuccess = !1, a(r.prototype), r.protocol = d.protocol, r.Socket = r, r.Transport = e("./transport"), r.transports = e("./transports"), r.parser = e("engine.io-parser"), r.prototype.createTransport = function(e) {
                    s('creating transport "%s"', e);
                    var t = i(this.query);
                    return t.EIO = d.protocol, t.transport = e, this.id && (t.sid = this.id), new o[e]({
                        agent: this.agent,
                        hostname: this.hostname,
                        port: this.port,
                        secure: this.secure,
                        path: this.path,
                        query: t,
                        forceJSONP: this.forceJSONP,
                        jsonp: this.jsonp,
                        forceBase64: this.forceBase64,
                        enablesXDR: this.enablesXDR,
                        timestampRequests: this.timestampRequests,
                        timestampParam: this.timestampParam,
                        policyPort: this.policyPort,
                        socket: this,
                        pfx: this.pfx,
                        key: this.key,
                        passphrase: this.passphrase,
                        cert: this.cert,
                        ca: this.ca,
                        ciphers: this.ciphers,
                        rejectUnauthorized: this.rejectUnauthorized
                    })
                }, r.prototype.open = function() {
                    if (this.rememberUpgrade && r.priorWebsocketSuccess && -1 != this.transports.indexOf("websocket")) t = "websocket";
                    else {
                        if (0 == this.transports.length) {
                            var e = this;
                            return void setTimeout(function() {
                                e.emit("error", "No transports available")
                            }, 0)
                        }
                        t = this.transports[0]
                    }
                    this.readyState = "opening";
                    var t;
                    try {
                        t = this.createTransport(t)
                    } catch (e) {
                        return this.transports.shift(), void this.open()
                    }
                    t.open(), this.setTransport(t)
                }, r.prototype.setTransport = function(e) {
                    s("setting transport %s", e.name);
                    var t = this;
                    this.transport && (s("clearing existing transport %s", this.transport.name), this.transport.removeAllListeners()), this.transport = e, e.on("drain", function() {
                        t.onDrain()
                    }).on("packet", function(e) {
                        t.onPacket(e)
                    }).on("error", function(e) {
                        t.onError(e)
                    }).on("close", function() {
                        t.onClose("transport close")
                    })
                }, r.prototype.probe = function(e) {
                    function t() {
                        if (f.onlyBinaryUpgrades) {
                            var t = !this.supportsBinary && f.transport.supportsBinary;
                            p = p || t
                        }
                        p || (s('probe transport "%s" opened', e), u.send([{
                            type: "ping",
                            data: "probe"
                        }]), u.once("packet", function(t) {
                            if (!p)
                                if ("pong" == t.type && "probe" == t.data) {
                                    if (s('probe transport "%s" pong', e), f.upgrading = !0, f.emit("upgrading", u), !u) return;
                                    r.priorWebsocketSuccess = "websocket" == u.name, s('pausing current transport "%s"', f.transport.name), f.transport.pause(function() {
                                        p || "closed" != f.readyState && (s("changing transport and sending upgrade packet"), d(), f.setTransport(u), u.send([{
                                            type: "upgrade"
                                        }]), f.emit("upgrade", u), u = null, f.upgrading = !1, f.flush())
                                    })
                                } else {
                                    s('probe transport "%s" failed', e);
                                    var n = new Error("probe error");
                                    n.transport = u.name, f.emit("upgradeError", n)
                                }
                        }))
                    }

                    function n() {
                        p || (p = !0, d(), u.close(), u = null)
                    }

                    function i(t) {
                        var r = new Error("probe error: " + t);
                        r.transport = u.name, n(), s('probe transport "%s" failed because of error: %s', e, t), f.emit("upgradeError", r)
                    }

                    function o() {
                        i("transport closed")
                    }

                    function a() {
                        i("socket closed")
                    }

                    function c(e) {
                        u && e.name != u.name && (s('"%s" works - aborting "%s"', e.name, u.name), n())
                    }

                    function d() {
                        u.removeListener("open", t), u.removeListener("error", i), u.removeListener("close", o), f.removeListener("close", a), f.removeListener("upgrading", c)
                    }
                    s('probing transport "%s"', e);
                    var u = this.createTransport(e, {
                            probe: 1
                        }),
                        p = !1,
                        f = this;
                    r.priorWebsocketSuccess = !1, u.once("open", t), u.once("error", i), u.once("close", o), this.once("close", a), this.once("upgrading", c), u.open()
                }, r.prototype.onOpen = function() {
                    if (s("socket open"), this.readyState = "open", r.priorWebsocketSuccess = "websocket" == this.transport.name, this.emit("open"), this.flush(), "open" == this.readyState && this.upgrade && this.transport.pause) {
                        s("starting upgrade probes");
                        for (var e = 0, t = this.upgrades.length; e < t; e++) this.probe(this.upgrades[e])
                    }
                }, r.prototype.onPacket = function(e) {
                    if ("opening" == this.readyState || "open" == this.readyState) switch (s('socket receive: type "%s", data "%s"', e.type, e.data), this.emit("packet", e), this.emit("heartbeat"), e.type) {
                        case "open":
                            this.onHandshake(p(e.data));
                            break;
                        case "pong":
                            this.setPing();
                            break;
                        case "error":
                            var t = new Error("server error");
                            t.code = e.data, this.emit("error", t);
                            break;
                        case "message":
                            this.emit("data", e.data), this.emit("message", e.data)
                    } else s('packet received with socket readyState "%s"', this.readyState)
                }, r.prototype.onHandshake = function(e) {
                    this.emit("handshake", e), this.id = e.sid, this.transport.query.sid = e.sid, this.upgrades = this.filterUpgrades(e.upgrades), this.pingInterval = e.pingInterval, this.pingTimeout = e.pingTimeout, this.onOpen(), "closed" != this.readyState && (this.setPing(), this.removeListener("heartbeat", this.onHeartbeat), this.on("heartbeat", this.onHeartbeat))
                }, r.prototype.onHeartbeat = function(e) {
                    clearTimeout(this.pingTimeoutTimer);
                    var t = this;
                    t.pingTimeoutTimer = setTimeout(function() {
                        "closed" != t.readyState && t.onClose("ping timeout")
                    }, e || t.pingInterval + t.pingTimeout)
                }, r.prototype.setPing = function() {
                    var e = this;
                    clearTimeout(e.pingIntervalTimer), e.pingIntervalTimer = setTimeout(function() {
                        s("writing ping packet - expecting pong within %sms", e.pingTimeout), e.ping(), e.onHeartbeat(e.pingTimeout)
                    }, e.pingInterval)
                }, r.prototype.ping = function() {
                    this.sendPacket("ping")
                }, r.prototype.onDrain = function() {
                    for (var e = 0; e < this.prevBufferLen; e++) this.callbackBuffer[e] && this.callbackBuffer[e]();
                    this.writeBuffer.splice(0, this.prevBufferLen), this.callbackBuffer.splice(0, this.prevBufferLen), this.prevBufferLen = 0, 0 == this.writeBuffer.length ? this.emit("drain") : this.flush()
                }, r.prototype.flush = function() {
                    "closed" != this.readyState && this.transport.writable && !this.upgrading && this.writeBuffer.length && (s("flushing %d packets in socket", this.writeBuffer.length), this.transport.send(this.writeBuffer), this.prevBufferLen = this.writeBuffer.length, this.emit("flush"))
                }, r.prototype.write = r.prototype.send = function(e, t) {
                    return this.sendPacket("message", e, t), this
                }, r.prototype.sendPacket = function(e, t, n) {
                    if ("closing" != this.readyState && "closed" != this.readyState) {
                        var r = {
                            type: e,
                            data: t
                        };
                        this.emit("packetCreate", r), this.writeBuffer.push(r), this.callbackBuffer.push(n), this.flush()
                    }
                }, r.prototype.close = function() {
                    function e() {
                        r.onClose("forced close"), s("socket closing - telling transport to close"), r.transport.close()
                    }

                    function t() {
                        r.removeListener("upgrade", t), r.removeListener("upgradeError", t), e()
                    }

                    function n() {
                        r.once("upgrade", t), r.once("upgradeError", t)
                    }
                    if ("opening" == this.readyState || "open" == this.readyState) {
                        this.readyState = "closing";
                        var r = this;
                        this.writeBuffer.length ? this.once("drain", function() {
                            this.upgrading ? n() : e()
                        }) : this.upgrading ? n() : e()
                    }
                    return this
                }, r.prototype.onError = function(e) {
                    s("socket error %j", e), r.priorWebsocketSuccess = !1, this.emit("error", e), this.onClose("transport error", e)
                }, r.prototype.onClose = function(e, t) {
                    if ("opening" == this.readyState || "open" == this.readyState || "closing" == this.readyState) {
                        s('socket close with reason: "%s"', e);
                        var n = this;
                        clearTimeout(this.pingIntervalTimer), clearTimeout(this.pingTimeoutTimer), setTimeout(function() {
                            n.writeBuffer = [], n.callbackBuffer = [], n.prevBufferLen = 0
                        }, 0), this.transport.removeAllListeners("close"), this.transport.close(), this.transport.removeAllListeners(), this.readyState = "closed", this.id = null, this.emit("close", e, t)
                    }
                }, r.prototype.filterUpgrades = function(e) {
                    for (var t = [], n = 0, r = e.length; n < r; n++) ~c(this.transports, e[n]) && t.push(e[n]);
                    return t
                }
            }).call(this, "undefined" != typeof global ? global : "undefined" != typeof self ? self : "undefined" != typeof window ? window : {})
        }, {
            "./transport": 23,
            "./transports": 24,
            "component-emitter": 17,
            debug: 30,
            "engine.io-parser": 33,
            indexof: 41,
            parsejson: 49,
            parseqs: 50,
            parseuri: 32
        }],
        23: [function(e, t, n) {
            function r(e) {
                this.path = e.path, this.hostname = e.hostname, this.port = e.port, this.secure = e.secure, this.query = e.query, this.timestampParam = e.timestampParam, this.timestampRequests = e.timestampRequests, this.readyState = "", this.agent = e.agent || !1, this.socket = e.socket, this.enablesXDR = e.enablesXDR, this.pfx = e.pfx, this.key = e.key, this.passphrase = e.passphrase, this.cert = e.cert, this.ca = e.ca, this.ciphers = e.ciphers, this.rejectUnauthorized = e.rejectUnauthorized
            }
            var i = e("engine.io-parser"),
                o = e("component-emitter");
            t.exports = r, o(r.prototype), r.timestamps = 0, r.prototype.onError = function(e, t) {
                var n = new Error(e);
                return n.type = "TransportError", n.description = t, this.emit("error", n), this
            }, r.prototype.open = function() {
                return "closed" != this.readyState && "" != this.readyState || (this.readyState = "opening", this.doOpen()), this
            }, r.prototype.close = function() {
                return "opening" != this.readyState && "open" != this.readyState || (this.doClose(), this.onClose()), this
            }, r.prototype.send = function(e) {
                if ("open" != this.readyState) throw new Error("Transport not open");
                this.write(e)
            }, r.prototype.onOpen = function() {
                this.readyState = "open", this.writable = !0, this.emit("open")
            }, r.prototype.onData = function(e) {
                var t = i.decodePacket(e, this.socket.binaryType);
                this.onPacket(t)
            }, r.prototype.onPacket = function(e) {
                this.emit("packet", e)
            }, r.prototype.onClose = function() {
                this.readyState = "closed", this.emit("close")
            }
        }, {
            "component-emitter": 17,
            "engine.io-parser": 33
        }],
        24: [function(e, t, n) {
            (function(t) {
                var r = e("xmlhttprequest"),
                    i = e("./polling-xhr"),
                    o = e("./polling-jsonp"),
                    a = e("./websocket");
                n.polling = function(e) {
                    var n = !1,
                        a = !1,
                        s = !1 !== e.jsonp;
                    if (t.location) {
                        var c = "https:" == location.protocol,
                            d = location.port;
                        d || (d = c ? 443 : 80), n = e.hostname != location.hostname || d != e.port, a = e.secure != c
                    }
                    if (e.xdomain = n, e.xscheme = a, "open" in new r(e) && !e.forceJSONP) return new i(e);
                    if (!s) throw new Error("JSONP disabled");
                    return new o(e)
                }, n.websocket = a
            }).call(this, "undefined" != typeof global ? global : "undefined" != typeof self ? self : "undefined" != typeof window ? window : {})
        }, {
            "./polling-jsonp": 25,
            "./polling-xhr": 26,
            "./websocket": 28,
            xmlhttprequest: 29
        }],
        25: [function(e, t, n) {
            (function(n) {
                function r() {}

                function i(e) {
                    o.call(this, e), this.query = this.query || {}, s || (n.___eio || (n.___eio = []), s = n.___eio), this.index = s.length;
                    var t = this;
                    s.push(function(e) {
                        t.onData(e)
                    }), this.query.j = this.index, n.document && n.addEventListener && n.addEventListener("beforeunload", function() {
                        t.script && (t.script.onerror = r)
                    }, !1)
                }
                var o = e("./polling"),
                    a = e("component-inherit");
                t.exports = i;
                var s, c = /\n/g,
                    d = /\\n/g;
                a(i, o), i.prototype.supportsBinary = !1, i.prototype.doClose = function() {
                    this.script && (this.script.parentNode.removeChild(this.script), this.script = null), this.form && (this.form.parentNode.removeChild(this.form), this.form = null, this.iframe = null), o.prototype.doClose.call(this)
                }, i.prototype.doPoll = function() {
                    var e = this,
                        t = document.createElement("script");
                    this.script && (this.script.parentNode.removeChild(this.script), this.script = null), t.async = !0, t.src = this.uri(), t.onerror = function(t) {
                        e.onError("jsonp poll error", t)
                    };
                    var n = document.getElementsByTagName("script")[0];
                    n.parentNode.insertBefore(t, n), this.script = t, "undefined" != typeof navigator && /gecko/i.test(navigator.userAgent) && setTimeout(function() {
                        var e = document.createElement("iframe");
                        document.body.appendChild(e), document.body.removeChild(e)
                    }, 100)
                }, i.prototype.doWrite = function(e, t) {
                    function n() {
                        r(), t()
                    }

                    function r() {
                        if (i.iframe) try {
                            i.form.removeChild(i.iframe)
                        } catch (e) {
                            i.onError("jsonp polling iframe removal error", e)
                        }
                        try {
                            var e = '<iframe src="javascript:0" name="' + i.iframeId + '">';
                            o = document.createElement(e)
                        } catch (e) {
                            (o = document.createElement("iframe")).name = i.iframeId, o.src = "javascript:0"
                        }
                        o.id = i.iframeId, i.form.appendChild(o), i.iframe = o
                    }
                    var i = this;
                    if (!this.form) {
                        var o, a = document.createElement("form"),
                            s = document.createElement("textarea"),
                            u = this.iframeId = "eio_iframe_" + this.index;
                        a.className = "socketio", a.style.position = "absolute", a.style.top = "-1000px", a.style.left = "-1000px", a.target = u, a.method = "POST", a.setAttribute("accept-charset", "utf-8"), s.name = "d", a.appendChild(s), document.body.appendChild(a), this.form = a, this.area = s
                    }
                    this.form.action = this.uri(), r(), e = e.replace(d, "\\\n"), this.area.value = e.replace(c, "\\n");
                    try {
                        this.form.submit()
                    } catch (e) {}
                    this.iframe.attachEvent ? this.iframe.onreadystatechange = function() {
                        "complete" == i.iframe.readyState && n()
                    } : this.iframe.onload = n
                }
            }).call(this, "undefined" != typeof global ? global : "undefined" != typeof self ? self : "undefined" != typeof window ? window : {})
        }, {
            "./polling": 27,
            "component-inherit": 18
        }],
        26: [function(e, t, n) {
            (function(n) {
                function r() {}

                function i(e) {
                    if (c.call(this, e), n.location) {
                        var t = "https:" == location.protocol,
                            r = location.port;
                        r || (r = t ? 443 : 80), this.xd = e.hostname != n.location.hostname || r != e.port, this.xs = e.secure != t
                    }
                }

                function o(e) {
                    this.method = e.method || "GET", this.uri = e.uri, this.xd = !!e.xd, this.xs = !!e.xs, this.async = !1 !== e.async, this.data = void 0 != e.data ? e.data : null, this.agent = e.agent, this.isBinary = e.isBinary, this.supportsBinary = e.supportsBinary, this.enablesXDR = e.enablesXDR, this.pfx = e.pfx, this.key = e.key, this.passphrase = e.passphrase, this.cert = e.cert, this.ca = e.ca, this.ciphers = e.ciphers, this.rejectUnauthorized = e.rejectUnauthorized, this.create()
                }

                function a() {
                    for (var e in o.requests) o.requests.hasOwnProperty(e) && o.requests[e].abort()
                }
                var s = e("xmlhttprequest"),
                    c = e("./polling"),
                    d = e("component-emitter"),
                    u = e("component-inherit"),
                    p = e("debug")("engine.io-client:polling-xhr");
                t.exports = i, t.exports.Request = o, u(i, c), i.prototype.supportsBinary = !0, i.prototype.request = function(e) {
                    return e = e || {}, e.uri = this.uri(), e.xd = this.xd, e.xs = this.xs, e.agent = this.agent || !1, e.supportsBinary = this.supportsBinary, e.enablesXDR = this.enablesXDR, e.pfx = this.pfx, e.key = this.key, e.passphrase = this.passphrase, e.cert = this.cert, e.ca = this.ca, e.ciphers = this.ciphers, e.rejectUnauthorized = this.rejectUnauthorized, new o(e)
                }, i.prototype.doWrite = function(e, t) {
                    var n = "string" != typeof e && void 0 !== e,
                        r = this.request({
                            method: "POST",
                            data: e,
                            isBinary: n
                        }),
                        i = this;
                    r.on("success", t), r.on("error", function(e) {
                        i.onError("xhr post error", e)
                    }), this.sendXhr = r
                }, i.prototype.doPoll = function() {
                    p("xhr poll");
                    var e = this.request(),
                        t = this;
                    e.on("data", function(e) {
                        t.onData(e)
                    }), e.on("error", function(e) {
                        t.onError("xhr poll error", e)
                    }), this.pollXhr = e
                }, d(o.prototype), o.prototype.create = function() {
                    var e = {
                        agent: this.agent,
                        xdomain: this.xd,
                        xscheme: this.xs,
                        enablesXDR: this.enablesXDR
                    };
                    e.pfx = this.pfx, e.key = this.key, e.passphrase = this.passphrase, e.cert = this.cert, e.ca = this.ca, e.ciphers = this.ciphers, e.rejectUnauthorized = this.rejectUnauthorized;
                    var t = this.xhr = new s(e),
                        r = this;
                    try {
                        if (p("xhr open %s: %s", this.method, this.uri), t.open(this.method, this.uri, this.async), this.supportsBinary && (t.responseType = "arraybuffer"), "POST" == this.method) try {
                            this.isBinary ? t.setRequestHeader("Content-type", "application/octet-stream") : t.setRequestHeader("Content-type", "text/plain;charset=UTF-8")
                        } catch (e) {}
                        "withCredentials" in t && (t.withCredentials = !0), this.hasXDR() ? (t.onload = function() {
                            r.onLoad()
                        }, t.onerror = function() {
                            r.onError(t.responseText)
                        }) : t.onreadystatechange = function() {
                            4 == t.readyState && (200 == t.status || 1223 == t.status ? r.onLoad() : setTimeout(function() {
                                r.onError(t.status)
                            }, 0))
                        }, p("xhr data %s", this.data), t.send(this.data)
                    } catch (e) {
                        return void setTimeout(function() {
                            r.onError(e)
                        }, 0)
                    }
                    n.document && (this.index = o.requestsCount++, o.requests[this.index] = this)
                }, o.prototype.onSuccess = function() {
                    this.emit("success"), this.cleanup()
                }, o.prototype.onData = function(e) {
                    this.emit("data", e), this.onSuccess()
                }, o.prototype.onError = function(e) {
                    this.emit("error", e), this.cleanup(!0)
                }, o.prototype.cleanup = function(e) {
                    if (void 0 !== this.xhr && null !== this.xhr) {
                        if (this.hasXDR() ? this.xhr.onload = this.xhr.onerror = r : this.xhr.onreadystatechange = r, e) try {
                            this.xhr.abort()
                        } catch (e) {}
                        n.document && delete o.requests[this.index], this.xhr = null
                    }
                }, o.prototype.onLoad = function() {
                    var e;
                    try {
                        var t;
                        try {
                            t = this.xhr.getResponseHeader("Content-Type").split(";")[0]
                        } catch (e) {}
                        e = "application/octet-stream" === t ? this.xhr.response : this.supportsBinary ? "ok" : this.xhr.responseText
                    } catch (e) {
                        this.onError(e)
                    }
                    null != e && this.onData(e)
                }, o.prototype.hasXDR = function() {
                    return void 0 !== n.XDomainRequest && !this.xs && this.enablesXDR
                }, o.prototype.abort = function() {
                    this.cleanup()
                }, n.document && (o.requestsCount = 0, o.requests = {}, n.attachEvent ? n.attachEvent("onunload", a) : n.addEventListener && n.addEventListener("beforeunload", a, !1))
            }).call(this, "undefined" != typeof global ? global : "undefined" != typeof self ? self : "undefined" != typeof window ? window : {})
        }, {
            "./polling": 27,
            "component-emitter": 17,
            "component-inherit": 18,
            debug: 30,
            xmlhttprequest: 29
        }],
        27: [function(e, t, n) {
            function r(e) {
                var t = e && e.forceBase64;
                d && !t || (this.supportsBinary = !1), i.call(this, e)
            }
            var i = e("../transport"),
                o = e("parseqs"),
                a = e("engine.io-parser"),
                s = e("component-inherit"),
                c = e("debug")("engine.io-client:polling");
            t.exports = r;
            var d = null != new(e("xmlhttprequest"))({
                xdomain: !1
            }).responseType;
            s(r, i), r.prototype.name = "polling", r.prototype.doOpen = function() {
                this.poll()
            }, r.prototype.pause = function(e) {
                function t() {
                    c("paused"), n.readyState = "paused", e()
                }
                var n = this;
                if (this.readyState = "pausing", this.polling || !this.writable) {
                    var r = 0;
                    this.polling && (c("we are currently polling - waiting to pause"), r++, this.once("pollComplete", function() {
                        c("pre-pause polling complete"), --r || t()
                    })), this.writable || (c("we are currently writing - waiting to pause"), r++, this.once("drain", function() {
                        c("pre-pause writing complete"), --r || t()
                    }))
                } else t()
            }, r.prototype.poll = function() {
                c("polling"), this.polling = !0, this.doPoll(), this.emit("poll")
            }, r.prototype.onData = function(e) {
                var t = this;
                c("polling got data %s", e);
                a.decodePayload(e, this.socket.binaryType, function(e, n, r) {
                    if ("opening" == t.readyState && t.onOpen(), "close" == e.type) return t.onClose(), !1;
                    t.onPacket(e)
                }), "closed" != this.readyState && (this.polling = !1, this.emit("pollComplete"), "open" == this.readyState ? this.poll() : c('ignoring poll - transport state "%s"', this.readyState))
            }, r.prototype.doClose = function() {
                function e() {
                    c("writing close packet"), t.write([{
                        type: "close"
                    }])
                }
                var t = this;
                "open" == this.readyState ? (c("transport open - closing"), e()) : (c("transport not open - deferring close"), this.once("open", e))
            }, r.prototype.write = function(e) {
                n = this;
                this.writable = !1;
                var t = function() {
                        n.writable = !0, n.emit("drain")
                    },
                    n = this;
                a.encodePayload(e, this.supportsBinary, function(e) {
                    n.doWrite(e, t)
                })
            }, r.prototype.uri = function() {
                var e = this.query || {},
                    t = this.secure ? "https" : "http",
                    n = "";
                return !1 !== this.timestampRequests && (e[this.timestampParam] = +new Date + "-" + i.timestamps++), this.supportsBinary || e.sid || (e.b64 = 1), e = o.encode(e), this.port && ("https" == t && 443 != this.port || "http" == t && 80 != this.port) && (n = ":" + this.port), e.length && (e = "?" + e), t + "://" + this.hostname + n + this.path + e
            }
        }, {
            "../transport": 23,
            "component-inherit": 18,
            debug: 30,
            "engine.io-parser": 33,
            parseqs: 50,
            xmlhttprequest: 29
        }],
        28: [function(e, t, n) {
            function r(e) {
                e && e.forceBase64 && (this.supportsBinary = !1), i.call(this, e)
            }
            var i = e("../transport"),
                o = e("engine.io-parser"),
                a = e("parseqs"),
                s = e("component-inherit"),
                c = e("debug")("engine.io-client:websocket"),
                d = e("ws");
            t.exports = r, s(r, i), r.prototype.name = "websocket", r.prototype.supportsBinary = !0, r.prototype.doOpen = function() {
                if (this.check()) {
                    var e = this.uri(),
                        t = {
                            agent: this.agent
                        };
                    t.pfx = this.pfx, t.key = this.key, t.passphrase = this.passphrase, t.cert = this.cert, t.ca = this.ca, t.ciphers = this.ciphers, t.rejectUnauthorized = this.rejectUnauthorized, this.ws = new d(e, void 0, t), void 0 === this.ws.binaryType && (this.supportsBinary = !1), this.ws.binaryType = "arraybuffer", this.addEventListeners()
                }
            }, r.prototype.addEventListeners = function() {
                var e = this;
                this.ws.onopen = function() {
                    e.onOpen()
                }, this.ws.onclose = function() {
                    e.onClose()
                }, this.ws.onmessage = function(t) {
                    e.onData(t.data)
                }, this.ws.onerror = function(t) {
                    e.onError("websocket error", t)
                }
            }, "undefined" != typeof navigator && /iPad|iPhone|iPod/i.test(navigator.userAgent) && (r.prototype.onData = function(e) {
                var t = this;
                setTimeout(function() {
                    i.prototype.onData.call(t, e)
                }, 0)
            }), r.prototype.write = function(e) {
                var t = this;
                this.writable = !1;
                for (var n = 0, r = e.length; n < r; n++) o.encodePacket(e[n], this.supportsBinary, function(e) {
                    try {
                        t.ws.send(e)
                    } catch (e) {
                        c("websocket closed before onclose event")
                    }
                });
                setTimeout(function() {
                    t.writable = !0, t.emit("drain")
                }, 0)
            }, r.prototype.onClose = function() {
                i.prototype.onClose.call(this)
            }, r.prototype.doClose = function() {
                void 0 !== this.ws && this.ws.close()
            }, r.prototype.uri = function() {
                var e = this.query || {},
                    t = this.secure ? "wss" : "ws",
                    n = "";
                return this.port && ("wss" == t && 443 != this.port || "ws" == t && 80 != this.port) && (n = ":" + this.port), this.timestampRequests && (e[this.timestampParam] = +new Date), this.supportsBinary || (e.b64 = 1), (e = a.encode(e)).length && (e = "?" + e), t + "://" + this.hostname + n + this.path + e
            }, r.prototype.check = function() {
                return !(!d || "__initialize" in d && this.name === r.prototype.name)
            }
        }, {
            "../transport": 23,
            "component-inherit": 18,
            debug: 30,
            "engine.io-parser": 33,
            parseqs: 50,
            ws: 76
        }],
        29: [function(e, t, n) {
            var r = e("has-cors");
            t.exports = function(e) {
                var t = e.xdomain,
                    n = e.xscheme,
                    i = e.enablesXDR;
                try {
                    if ("undefined" != typeof XMLHttpRequest && (!t || r)) return new XMLHttpRequest
                } catch (e) {}
                try {
                    if ("undefined" != typeof XDomainRequest && !n && i) return new XDomainRequest
                } catch (e) {}
                if (!t) try {
                    return new ActiveXObject("Microsoft.XMLHTTP")
                } catch (e) {}
            }
        }, {
            "has-cors": 40
        }],
        30: [function(e, t, n) {
            function r() {
                var e;
                try {
                    e = localStorage.debug
                } catch (e) {}
                return e
            }(n = t.exports = e("./debug")).log = function() {
                return "object" == typeof console && "function" == typeof console.log && Function.prototype.apply.call(console.log, console, arguments)
            }, n.formatArgs = function() {
                var e = arguments,
                    t = this.useColors;
                if (e[0] = (t ? "%c" : "") + this.namespace + (t ? " %c" : " ") + e[0] + (t ? "%c " : " ") + "+" + n.humanize(this.diff), !t) return e;
                var r = "color: " + this.color,
                    i = 0,
                    o = 0;
                return (e = [e[0], r, "color: inherit"].concat(Array.prototype.slice.call(e, 1)))[0].replace(/%[a-z%]/g, function(e) {
                    "%%" !== e && (i++, "%c" === e && (o = i))
                }), e.splice(o, 0, r), e
            }, n.save = function(e) {
                try {
                    null == e ? localStorage.removeItem("debug") : localStorage.debug = e
                } catch (e) {}
            }, n.load = r, n.useColors = function() {
                return "WebkitAppearance" in document.documentElement.style || window.console && (console.firebug || console.exception && console.table) || navigator.userAgent.toLowerCase().match(/firefox\/(\d+)/) && parseInt(RegExp.$1, 10) >= 31
            }, n.colors = ["lightseagreen", "forestgreen", "goldenrod", "dodgerblue", "darkorchid", "crimson"], n.formatters.j = function(e) {
                return JSON.stringify(e)
            }, n.enable(r())
        }, {
            "./debug": 31
        }],
        31: [function(e, t, n) {
            function r() {
                return n.colors[o++ % n.colors.length]
            }(n = t.exports = function(e) {
                function t() {}

                function o() {
                    var e = o,
                        t = +new Date,
                        a = t - (i || t);
                    e.diff = a, e.prev = i, e.curr = t, i = t, null == e.useColors && (e.useColors = n.useColors()), null == e.color && e.useColors && (e.color = r());
                    var s = Array.prototype.slice.call(arguments);
                    s[0] = n.coerce(s[0]), "string" != typeof s[0] && (s = ["%o"].concat(s));
                    var c = 0;
                    s[0] = s[0].replace(/%([a-z%])/g, function(t, r) {
                        if ("%%" === t) return t;
                        c++;
                        var i = n.formatters[r];
                        if ("function" == typeof i) {
                            var o = s[c];
                            t = i.call(e, o), s.splice(c, 1), c--
                        }
                        return t
                    }), "function" == typeof n.formatArgs && (s = n.formatArgs.apply(e, s)), (o.log || n.log || console.log.bind(console)).apply(e, s)
                }
                t.enabled = !1, o.enabled = !0;
                var a = n.enabled(e) ? o : t;
                return a.namespace = e, a
            }).coerce = function(e) {
                return e instanceof Error ? e.stack || e.message : e
            }, n.disable = function() {
                n.enable("")
            }, n.enable = function(e) {
                n.save(e);
                for (var t = (e || "").split(/[\s,]+/), r = t.length, i = 0; i < r; i++) t[i] && ("-" === (e = t[i].replace(/\*/g, ".*?"))[0] ? n.skips.push(new RegExp("^" + e.substr(1) + "$")) : n.names.push(new RegExp("^" + e + "$")))
            }, n.enabled = function(e) {
                var t, r;
                for (t = 0, r = n.skips.length; t < r; t++)
                    if (n.skips[t].test(e)) return !1;
                for (t = 0, r = n.names.length; t < r; t++)
                    if (n.names[t].test(e)) return !0;
                return !1
            }, n.humanize = e("ms"), n.names = [], n.skips = [], n.formatters = {};
            var i, o = 0
        }, {
            ms: 47
        }],
        32: [function(e, t, n) {
            var r = /^(?:(?![^:@]+:[^:@\/]*@)(http|https|ws|wss):\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?((?:[a-f0-9]{0,4}:){2,7}[a-f0-9]{0,4}|[^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/,
                i = ["source", "protocol", "authority", "userInfo", "user", "password", "host", "port", "relative", "path", "directory", "file", "query", "anchor"];
            t.exports = function(e) {
                var t = e,
                    n = e.indexOf("["),
                    o = e.indexOf("]"); - 1 != n && -1 != o && (e = e.substring(0, n) + e.substring(n, o).replace(/:/g, ";") + e.substring(o, e.length));
                for (var a = r.exec(e || ""), s = {}, c = 14; c--;) s[i[c]] = a[c] || "";
                return -1 != n && -1 != o && (s.source = t, s.host = s.host.substring(1, s.host.length - 1).replace(/;/g, ":"), s.authority = s.authority.replace("[", "").replace("]", "").replace(/;/g, ":"), s.ipv6uri = !0), s
            }
        }, {}],
        33: [function(e, t, n) {
            (function(t) {
                function r(e, t) {
                    return t("b" + n.packets[e.type] + e.data.data)
                }

                function i(e, t, r) {
                    if (!t) return n.encodeBase64Packet(e, r);
                    var i = e.data,
                        o = new Uint8Array(i),
                        a = new Uint8Array(1 + i.byteLength);
                    a[0] = v[e.type];
                    for (var s = 0; s < o.length; s++) a[s + 1] = o[s];
                    return r(a.buffer)
                }

                function o(e, t, r) {
                    if (!t) return n.encodeBase64Packet(e, r);
                    var i = new FileReader;
                    return i.onload = function() {
                        e.data = i.result, n.encodePacket(e, t, !0, r)
                    }, i.readAsArrayBuffer(e.data)
                }

                function a(e, t, r) {
                    if (!t) return n.encodeBase64Packet(e, r);
                    if (g) return o(e, t, r);
                    var i = new Uint8Array(1);
                    return i[0] = v[e.type], r(new w([i.buffer, e.data]))
                }

                function s(e, t, n) {
                    for (var r = new Array(e.length), i = f(e.length, n), o = 0; o < e.length; o++) ! function(e, n, i) {
                        t(n, function(t, n) {
                            r[e] = n, i(t, r)
                        })
                    }(o, e[o], i)
                }
                var c = e("./keys"),
                    d = e("has-binary"),
                    u = e("arraybuffer.slice"),
                    p = e("base64-arraybuffer"),
                    f = e("after"),
                    l = e("utf8"),
                    h = navigator.userAgent.match(/Android/i),
                    m = /PhantomJS/i.test(navigator.userAgent),
                    g = h || m;
                n.protocol = 3;
                var v = n.packets = {
                        open: 0,
                        close: 1,
                        ping: 2,
                        pong: 3,
                        message: 4,
                        upgrade: 5,
                        noop: 6
                    },
                    y = c(v),
                    b = {
                        type: "error",
                        data: "parser error"
                    },
                    w = e("blob");
                n.encodePacket = function(e, n, o, s) {
                    "function" == typeof n && (s = n, n = !1), "function" == typeof o && (s = o, o = null);
                    var c = void 0 === e.data ? void 0 : e.data.buffer || e.data;
                    if (t.ArrayBuffer && c instanceof ArrayBuffer) return i(e, n, s);
                    if (w && c instanceof t.Blob) return a(e, n, s);
                    if (c && c.base64) return r(e, s);
                    var d = v[e.type];
                    return void 0 !== e.data && (d += o ? l.encode(String(e.data)) : String(e.data)), s("" + d)
                }, n.encodeBase64Packet = function(e, r) {
                    var i = "b" + n.packets[e.type];
                    if (w && e.data instanceof w) {
                        var o = new FileReader;
                        return o.onload = function() {
                            var e = o.result.split(",")[1];
                            r(i + e)
                        }, o.readAsDataURL(e.data)
                    }
                    var a;
                    try {
                        a = String.fromCharCode.apply(null, new Uint8Array(e.data))
                    } catch (t) {
                        for (var s = new Uint8Array(e.data), c = new Array(s.length), d = 0; d < s.length; d++) c[d] = s[d];
                        a = String.fromCharCode.apply(null, c)
                    }
                    return i += t.btoa(a), r(i)
                }, n.decodePacket = function(e, t, r) {
                    if ("string" == typeof e || void 0 === e) {
                        if ("b" == e.charAt(0)) return n.decodeBase64Packet(e.substr(1), t);
                        if (r) try {
                            e = l.decode(e)
                        } catch (e) {
                            return b
                        }
                        i = e.charAt(0);
                        return Number(i) == i && y[i] ? e.length > 1 ? {
                            type: y[i],
                            data: e.substring(1)
                        } : {
                            type: y[i]
                        } : b
                    }
                    var i = new Uint8Array(e)[0],
                        o = u(e, 1);
                    return w && "blob" === t && (o = new w([o])), {
                        type: y[i],
                        data: o
                    }
                }, n.decodeBase64Packet = function(e, n) {
                    var r = y[e.charAt(0)];
                    if (!t.ArrayBuffer) return {
                        type: r,
                        data: {
                            base64: !0,
                            data: e.substr(1)
                        }
                    };
                    var i = p.decode(e.substr(1));
                    return "blob" === n && w && (i = new w([i])), {
                        type: r,
                        data: i
                    }
                }, n.encodePayload = function(e, t, r) {
                    function i(e) {
                        return e.length + ":" + e
                    }
                    "function" == typeof t && (r = t, t = null);
                    var o = d(e);
                    return t && o ? w && !g ? n.encodePayloadAsBlob(e, r) : n.encodePayloadAsArrayBuffer(e, r) : e.length ? void s(e, function(e, r) {
                        n.encodePacket(e, !!o && t, !0, function(e) {
                            r(null, i(e))
                        })
                    }, function(e, t) {
                        return r(t.join(""))
                    }) : r("0:")
                }, n.decodePayload = function(e, t, r) {
                    if ("string" != typeof e) return n.decodePayloadAsBinary(e, t, r);
                    "function" == typeof t && (r = t, t = null);
                    var i;
                    if ("" == e) return r(b, 0, 1);
                    for (var o, a, s = "", c = 0, d = e.length; c < d; c++) {
                        var u = e.charAt(c);
                        if (":" != u) s += u;
                        else {
                            if ("" == s || s != (o = Number(s))) return r(b, 0, 1);
                            if (a = e.substr(c + 1, o), s != a.length) return r(b, 0, 1);
                            if (a.length) {
                                if (i = n.decodePacket(a, t, !0), b.type == i.type && b.data == i.data) return r(b, 0, 1);
                                if (!1 === r(i, c + o, d)) return
                            }
                            c += o, s = ""
                        }
                    }
                    return "" != s ? r(b, 0, 1) : void 0
                }, n.encodePayloadAsArrayBuffer = function(e, t) {
                    if (!e.length) return t(new ArrayBuffer(0));
                    s(e, function(e, t) {
                        n.encodePacket(e, !0, !0, function(e) {
                            return t(null, e)
                        })
                    }, function(e, n) {
                        var r = n.reduce(function(e, t) {
                                var n;
                                return n = "string" == typeof t ? t.length : t.byteLength, e + n.toString().length + n + 2
                            }, 0),
                            i = new Uint8Array(r),
                            o = 0;
                        return n.forEach(function(e) {
                            var t = "string" == typeof e,
                                n = e;
                            if (t) {
                                for (var r = new Uint8Array(e.length), a = 0; a < e.length; a++) r[a] = e.charCodeAt(a);
                                n = r.buffer
                            }
                            i[o++] = t ? 0 : 1;
                            for (var s = n.byteLength.toString(), a = 0; a < s.length; a++) i[o++] = parseInt(s[a]);
                            i[o++] = 255;
                            for (var r = new Uint8Array(n), a = 0; a < r.length; a++) i[o++] = r[a]
                        }), t(i.buffer)
                    })
                }, n.encodePayloadAsBlob = function(e, t) {
                    s(e, function(e, t) {
                        n.encodePacket(e, !0, !0, function(e) {
                            var n = new Uint8Array(1);
                            if (n[0] = 1, "string" == typeof e) {
                                for (var r = new Uint8Array(e.length), i = 0; i < e.length; i++) r[i] = e.charCodeAt(i);
                                e = r.buffer, n[0] = 0
                            }
                            for (var o = (e instanceof ArrayBuffer ? e.byteLength : e.size).toString(), a = new Uint8Array(o.length + 1), i = 0; i < o.length; i++) a[i] = parseInt(o[i]);
                            if (a[o.length] = 255, w) {
                                var s = new w([n.buffer, a.buffer, e]);
                                t(null, s)
                            }
                        })
                    }, function(e, n) {
                        return t(new w(n))
                    })
                }, n.decodePayloadAsBinary = function(e, t, r) {
                    "function" == typeof t && (r = t, t = null);
                    for (var i = e, o = [], a = !1; i.byteLength > 0;) {
                        for (var s = new Uint8Array(i), c = 0 === s[0], d = "", p = 1; 255 != s[p]; p++) {
                            if (d.length > 310) {
                                a = !0;
                                break
                            }
                            d += s[p]
                        }
                        if (a) return r(b, 0, 1);
                        i = u(i, 2 + d.length), d = parseInt(d);
                        var f = u(i, 0, d);
                        if (c) try {
                            f = String.fromCharCode.apply(null, new Uint8Array(f))
                        } catch (e) {
                            var l = new Uint8Array(f);
                            f = "";
                            for (p = 0; p < l.length; p++) f += String.fromCharCode(l[p])
                        }
                        o.push(f), i = u(i, d)
                    }
                    var h = o.length;
                    o.forEach(function(e, i) {
                        r(n.decodePacket(e, t, !0), i, h)
                    })
                }
            }).call(this, "undefined" != typeof global ? global : "undefined" != typeof self ? self : "undefined" != typeof window ? window : {})
        }, {
            "./keys": 34,
            after: 1,
            "arraybuffer.slice": 2,
            "base64-arraybuffer": 14,
            blob: 15,
            "has-binary": 39,
            utf8: 70
        }],
        34: [function(e, t, n) {
            t.exports = Object.keys || function(e) {
                var t = [],
                    n = Object.prototype.hasOwnProperty;
                for (var r in e) n.call(e, r) && t.push(r);
                return t
            }
        }, {}],
        35: [function(e, t, n) {
            function r(e) {
                o.call(this);
                var t = e || {};
                this.config = {
                    chunksize: 16384,
                    pacing: 0
                };
                var n;
                for (n in t) this.config[n] = t[n];
                this.file = null, this.channel = null
            }

            function i() {
                o.call(this), this.receiveBuffer = [], this.received = 0, this.metadata = {}, this.channel = null
            }
            var o = e("wildemitter"),
                a = e("util");
            a.inherits(r, o), r.prototype.send = function(e, t) {
                var n = this;
                this.file = e, this.channel = t;
                var r = "number" != typeof t.bufferedAmountLowThreshold,
                    i = 0,
                    o = function() {
                        var a = new window.FileReader;
                        a.onload = function(a) {
                            n.channel.send(a.target.result), n.emit("progress", i, e.size, a.target.result), e.size > i + a.target.result.byteLength ? r ? window.setTimeout(o, n.config.pacing) : t.bufferedAmount <= t.bufferedAmountLowThreshold && window.setTimeout(o, 0) : (n.emit("progress", e.size, e.size, null), n.emit("sentFile")), i += n.config.chunksize
                        };
                        var s = e.slice(i, i + n.config.chunksize);
                        a.readAsArrayBuffer(s)
                    };
                r || (t.bufferedAmountLowThreshold = 8 * this.config.chunksize, t.addEventListener("bufferedamountlow", o)), window.setTimeout(o, 0)
            }, a.inherits(i, o), i.prototype.receive = function(e, t) {
                var n = this;
                e && (this.metadata = e), this.channel = t, t.binaryType = "arraybuffer", this.channel.onmessage = function(e) {
                    var t = e.data.byteLength;
                    n.received += t, n.receiveBuffer.push(e.data), n.emit("progress", n.received, n.metadata.size, e.data), n.received === n.metadata.size ? (n.emit("receivedFile", new window.Blob(n.receiveBuffer), n.metadata), n.receiveBuffer = []) : n.received > n.metadata.size && (console.error("received more than expected, discarding..."), n.receiveBuffer = [])
                }
            }, t.exports = {}, t.exports.support = "undefined" != typeof window && window && window.File && window.FileReader && window.Blob, t.exports.Sender = r, t.exports.Receiver = i
        }, {
            util: 73,
            wildemitter: 75
        }],
        36: [function(e, t, n) {
            var r = {};
            t.exports = function(e, t) {
                var n, i = 2 === arguments.length,
                    o = i ? t : e;
                if ("undefined" == typeof window || "http:" === window.location.protocol) return n = new Error("NavigatorUserMediaError"), n.name = "HTTPS_REQUIRED", o(n);
                if (window.navigator.userAgent.match("Chrome")) {
                    var a = parseInt(window.navigator.userAgent.match(/Chrome\/(.*) /)[1], 10),
                        s = 33,
                        c = !window.chrome.webstore;
                    if (window.navigator.userAgent.match("Linux") && (s = 35), sessionStorage.getScreenMediaJSExtensionId) chrome.runtime.sendMessage(sessionStorage.getScreenMediaJSExtensionId, {
                        type: "getScreen",
                        id: 1
                    }, null, function(t) {
                        if (t && "" !== t.sourceId)(e = i && e || {
                            audio: !1,
                            video: {
                                mandatory: {
                                    chromeMediaSource: "desktop",
                                    maxWidth: window.screen.width,
                                    maxHeight: window.screen.height,
                                    maxFrameRate: 3
                                }
                            }
                        }).video.mandatory.chromeMediaSourceId = t.sourceId, window.navigator.mediaDevices.getUserMedia(e).then(function(e) {
                            o(null, e)
                        }).catch(function(e) {
                            o(e)
                        });
                        else {
                            var n = new Error("NavigatorUserMediaError");
                            n.name = "NotAllowedError", o(n)
                        }
                    });
                    else if (window.cefGetScreenMedia) window.cefGetScreenMedia(function(t) {
                        if (t)(e = i && e || {
                            audio: !1,
                            video: {
                                mandatory: {
                                    chromeMediaSource: "desktop",
                                    maxWidth: window.screen.width,
                                    maxHeight: window.screen.height,
                                    maxFrameRate: 3
                                },
                                optional: [{
                                    googLeakyBucket: !0
                                }, {
                                    googTemporalLayeredScreencast: !0
                                }]
                            }
                        }).video.mandatory.chromeMediaSourceId = t, window.navigator.mediaDevices.getUserMedia(e).then(function(e) {
                            o(null, e)
                        }).catch(function(e) {
                            o(e)
                        });
                        else {
                            var n = new Error("cefGetScreenMediaError");
                            n.name = "CEF_GETSCREENMEDIA_CANCELED", o(n)
                        }
                    });
                    else if (c || a >= 26 && a <= s) e = i && e || {
                        video: {
                            mandatory: {
                                googLeakyBucket: !0,
                                maxWidth: window.screen.width,
                                maxHeight: window.screen.height,
                                maxFrameRate: 3,
                                chromeMediaSource: "screen"
                            }
                        }
                    }, window.navigator.mediaDevices.getUserMedia(e).then(function(e) {
                        o(null, e)
                    }).catch(function(e) {
                        o(e)
                    });
                    else {
                        var d = window.setTimeout(function() {
                            return n = new Error("NavigatorUserMediaError"), n.name = "EXTENSION_UNAVAILABLE", o(n)
                        }, 1e3);
                        r[d] = [o, i ? e : null], window.postMessage({
                            type: "getScreen",
                            id: d
                        }, "*")
                    }
                } else window.navigator.userAgent.match("Firefox") && (parseInt(window.navigator.userAgent.match(/Firefox\/(.*)/)[1], 10) >= 33 ? (e = i && e || {
                    video: {
                        mozMediaSource: "window",
                        mediaSource: "window"
                    }
                }, window.navigator.mediaDevices.getUserMedia(e).then(function(e) {
                    o(null, e);
                    var t = e.currentTime,
                        n = window.setInterval(function() {
                            e || window.clearInterval(n), e.currentTime == t && (window.clearInterval(n), e.onended && e.onended()), t = e.currentTime
                        }, 500)
                }).catch(function(e) {
                    o(e)
                })) : (n = new Error("NavigatorUserMediaError")).name = "EXTENSION_UNAVAILABLE")
            }, "undefined" != typeof window && window.addEventListener("message", function(e) {
                if (e.origin == window.location.origin)
                    if ("gotScreen" == e.data.type && r[e.data.id]) {
                        var t = r[e.data.id],
                            n = t[1],
                            i = t[0];
                        if (delete r[e.data.id], "" === e.data.sourceId) {
                            var o = new Error("NavigatorUserMediaError");
                            o.name = "NotAllowedError", i(o)
                        } else(n = n || {
                            audio: !1,
                            video: {
                                mandatory: {
                                    chromeMediaSource: "desktop",
                                    maxWidth: window.screen.width,
                                    maxHeight: window.screen.height,
                                    maxFrameRate: 3
                                },
                                optional: [{
                                    googLeakyBucket: !0
                                }, {
                                    googTemporalLayeredScreencast: !0
                                }]
                            }
                        }).video.mandatory.chromeMediaSourceId = e.data.sourceId, window.navigator.mediaDevices.getUserMedia(n).then(function(e) {
                            i(null, e)
                        }).catch(function(e) {
                            i(e)
                        })
                    } else "getScreenPending" == e.data.type && window.clearTimeout(e.data.id)
            })
        }, {}],
        37: [function(e, t, n) {
            t.exports = function() {
                return this
            }()
        }, {}],
        38: [function(e, t, n) {
            function r(e, t) {
                var n = -1 / 0;
                e.getFloatFrequencyData(t);
                for (var r = 4, i = t.length; r < i; r++) t[r] > n && t[r] < 0 && (n = t[r]);
                return n
            }
            var i, o = e("wildemitter");
            "undefined" != typeof window && (i = window.AudioContext || window.webkitAudioContext);
            var a = null;
            t.exports = function(e, t) {
                var n = new o;
                if (!i) return n;
                var s = (t = t || {}).smoothing || .1,
                    c = t.interval || 50,
                    d = t.threshold,
                    u = t.play,
                    p = t.history || 10,
                    f = !0;
                a || (a = new i);
                var l, h, m;
                (m = a.createAnalyser()).fftSize = 512, m.smoothingTimeConstant = s, h = new Float32Array(m.frequencyBinCount), e.jquery && (e = e[0]), e instanceof HTMLAudioElement || e instanceof HTMLVideoElement ? (l = a.createMediaElementSource(e), void 0 === u && (u = !0), d = d || -50) : (l = a.createMediaStreamSource(e), d = d || -50), l.connect(m), u && m.connect(a.destination), n.speaking = !1, n.setThreshold = function(e) {
                    d = e
                }, n.setInterval = function(e) {
                    c = e
                }, n.stop = function() {
                    f = !1, n.emit("volume_change", -100, d), n.speaking && (n.speaking = !1, n.emit("stopped_speaking")), m.disconnect(), l.disconnect()
                }, n.speakingHistory = [];
                for (var g = 0; g < p; g++) n.speakingHistory.push(0);
                var v = function() {
                    setTimeout(function() {
                        if (f) {
                            var e = r(m, h);
                            n.emit("volume_change", e, d);
                            var t = 0;
                            if (e > d && !n.speaking) {
                                for (i = n.speakingHistory.length - 3; i < n.speakingHistory.length; i++) t += n.speakingHistory[i];
                                t >= 2 && (n.speaking = !0, n.emit("speaking"))
                            } else if (e < d && n.speaking) {
                                for (var i = 0; i < n.speakingHistory.length; i++) t += n.speakingHistory[i];
                                0 == t && (n.speaking = !1, n.emit("stopped_speaking"))
                            }
                            n.speakingHistory.shift(), n.speakingHistory.push(0 + (e > d)), v()
                        }
                    }, c)
                };
                return v(), n
            }
        }, {
            wildemitter: 75
        }],
        39: [function(e, t, n) {
            (function(n) {
                var r = e("isarray");
                t.exports = function(e) {
                    function t(e) {
                        if (!e) return !1;
                        if (n.Buffer && n.Buffer.isBuffer(e) || n.ArrayBuffer && e instanceof ArrayBuffer || n.Blob && e instanceof Blob || n.File && e instanceof File) return !0;
                        if (r(e)) {
                            for (var i = 0; i < e.length; i++)
                                if (t(e[i])) return !0
                        } else if (e && "object" == typeof e) {
                            e.toJSON && (e = e.toJSON());
                            for (var o in e)
                                if (Object.prototype.hasOwnProperty.call(e, o) && t(e[o])) return !0
                        }
                        return !1
                    }
                    return t(e)
                }
            }).call(this, "undefined" != typeof global ? global : "undefined" != typeof self ? self : "undefined" != typeof window ? window : {})
        }, {
            isarray: 42
        }],
        40: [function(e, t, n) {
            var r = e("global");
            try {
                t.exports = "XMLHttpRequest" in r && "withCredentials" in new r.XMLHttpRequest
            } catch (e) {
                t.exports = !1
            }
        }, {
            global: 37
        }],
        41: [function(e, t, n) {
            var r = [].indexOf;
            t.exports = function(e, t) {
                if (r) return e.indexOf(t);
                for (var n = 0; n < e.length; ++n)
                    if (e[n] === t) return n;
                return -1
            }
        }, {}],
        42: [function(e, t, n) {
            t.exports = Array.isArray || function(e) {
                return "[object Array]" == Object.prototype.toString.call(e)
            }
        }, {}],
        43: [function(e, t, n) {
            ! function(e) {
                function t(e) {
                    if (t[e] !== o) return t[e];
                    var n;
                    if ("bug-string-char-index" == e) n = "a" != "a" [0];
                    else if ("json" == e) n = t("json-stringify") && t("json-parse");
                    else {
                        var r, i = '{"a":[1,true,false,null,"\\u0000\\b\\n\\f\\r\\t"]}';
                        if ("json-stringify" == e) {
                            var s = c.stringify,
                                u = "function" == typeof s && d;
                            if (u) {
                                (r = function() {
                                    return 1
                                }).toJSON = r;
                                try {
                                    u = "0" === s(0) && "0" === s(new Number) && '""' == s(new String) && s(a) === o && s(o) === o && s() === o && "1" === s(r) && "[1]" == s([r]) && "[null]" == s([o]) && "null" == s(null) && "[null,null,null]" == s([o, a, null]) && s({
                                        a: [r, !0, !1, null, "\0\b\n\f\r\t"]
                                    }) == i && "1" === s(null, r) && "[\n 1,\n 2\n]" == s([1, 2], null, 1) && '"-271821-04-20T00:00:00.000Z"' == s(new Date(-864e13)) && '"+275760-09-13T00:00:00.000Z"' == s(new Date(864e13)) && '"-000001-01-01T00:00:00.000Z"' == s(new Date(-621987552e5)) && '"1969-12-31T23:59:59.999Z"' == s(new Date(-1))
                                } catch (e) {
                                    u = !1
                                }
                            }
                            n = u
                        }
                        if ("json-parse" == e) {
                            var p = c.parse;
                            if ("function" == typeof p) try {
                                if (0 === p("0") && !p(!1)) {
                                    var f = 5 == (r = p(i)).a.length && 1 === r.a[0];
                                    if (f) {
                                        try {
                                            f = !p('"\t"')
                                        } catch (e) {}
                                        if (f) try {
                                            f = 1 !== p("01")
                                        } catch (e) {}
                                        if (f) try {
                                            f = 1 !== p("1.")
                                        } catch (e) {}
                                    }
                                }
                            } catch (e) {
                                f = !1
                            }
                            n = f
                        }
                    }
                    return t[e] = !!n
                }
                var r, i, o, a = {}.toString,
                    s = "object" == typeof JSON && JSON,
                    c = "object" == typeof n && n && !n.nodeType && n;
                c && s ? (c.stringify = s.stringify, c.parse = s.parse) : c = e.JSON = s || {};
                var d = new Date(-0xc782b5b800cec);
                try {
                    d = -109252 == d.getUTCFullYear() && 0 === d.getUTCMonth() && 1 === d.getUTCDate() && 10 == d.getUTCHours() && 37 == d.getUTCMinutes() && 6 == d.getUTCSeconds() && 708 == d.getUTCMilliseconds()
                } catch (e) {}
                if (!t("json")) {
                    var u = t("bug-string-char-index");
                    if (!d) var p = Math.floor,
                        f = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334],
                        l = function(e, t) {
                            return f[t] + 365 * (e - 1970) + p((e - 1969 + (t = +(t > 1))) / 4) - p((e - 1901 + t) / 100) + p((e - 1601 + t) / 400)
                        };
                    (r = {}.hasOwnProperty) || (r = function(e) {
                        var t, n = {};
                        return (n.__proto__ = null, n.__proto__ = {
                            toString: 1
                        }, n).toString != a ? r = function(e) {
                            var t = this.__proto__,
                                n = e in (this.__proto__ = null, this);
                            return this.__proto__ = t, n
                        } : (t = n.constructor, r = function(e) {
                            var n = (this.constructor || t).prototype;
                            return e in this && !(e in n && this[e] === n[e])
                        }), n = null, r.call(this, e)
                    });
                    var h = {
                            boolean: 1,
                            number: 1,
                            string: 1,
                            undefined: 1
                        },
                        m = function(e, t) {
                            var n = typeof e[t];
                            return "object" == n ? !!e[t] : !h[n]
                        };
                    if (i = function(e, t) {
                            var n, o, s, c = 0;
                            (n = function() {
                                this.valueOf = 0
                            }).prototype.valueOf = 0, o = new n;
                            for (s in o) r.call(o, s) && c++;
                            return n = o = null, c ? i = 2 == c ? function(e, t) {
                                var n, i = {},
                                    o = "[object Function]" == a.call(e);
                                for (n in e) o && "prototype" == n || r.call(i, n) || !(i[n] = 1) || !r.call(e, n) || t(n)
                            } : function(e, t) {
                                var n, i, o = "[object Function]" == a.call(e);
                                for (n in e) o && "prototype" == n || !r.call(e, n) || (i = "constructor" === n) || t(n);
                                (i || r.call(e, n = "constructor")) && t(n)
                            } : (o = ["valueOf", "toString", "toLocaleString", "propertyIsEnumerable", "isPrototypeOf", "hasOwnProperty", "constructor"], i = function(e, t) {
                                var n, i, s = "[object Function]" == a.call(e),
                                    c = !s && "function" != typeof e.constructor && m(e, "hasOwnProperty") ? e.hasOwnProperty : r;
                                for (n in e) s && "prototype" == n || !c.call(e, n) || t(n);
                                for (i = o.length; n = o[--i]; c.call(e, n) && t(n));
                            }), i(e, t)
                        }, !t("json-stringify")) {
                        var g = {
                                92: "\\\\",
                                34: '\\"',
                                8: "\\b",
                                12: "\\f",
                                10: "\\n",
                                13: "\\r",
                                9: "\\t"
                            },
                            v = function(e, t) {
                                return ("000000" + (t || 0)).slice(-e)
                            },
                            y = function(e) {
                                var t, n = '"',
                                    r = 0,
                                    i = e.length,
                                    o = i > 10 && u;
                                for (o && (t = e.split("")); r < i; r++) {
                                    var a = e.charCodeAt(r);
                                    switch (a) {
                                        case 8:
                                        case 9:
                                        case 10:
                                        case 12:
                                        case 13:
                                        case 34:
                                        case 92:
                                            n += g[a];
                                            break;
                                        default:
                                            if (a < 32) {
                                                n += "\\u00" + v(2, a.toString(16));
                                                break
                                            }
                                            n += o ? t[r] : u ? e.charAt(r) : e[r]
                                    }
                                }
                                return n + '"'
                            },
                            b = function(e, t, n, s, c, d, u) {
                                var f, h, m, g, w, C, S, k, T, E, P, x, R, O, D, _;
                                try {
                                    f = t[e]
                                } catch (e) {}
                                if ("object" == typeof f && f)
                                    if ("[object Date]" != (h = a.call(f)) || r.call(f, "toJSON")) "function" == typeof f.toJSON && ("[object Number]" != h && "[object String]" != h && "[object Array]" != h || r.call(f, "toJSON")) && (f = f.toJSON(e));
                                    else if (f > -1 / 0 && f < 1 / 0) {
                                    if (l) {
                                        for (w = p(f / 864e5), m = p(w / 365.2425) + 1970 - 1; l(m + 1, 0) <= w; m++);
                                        for (g = p((w - l(m, 0)) / 30.42); l(m, g + 1) <= w; g++);
                                        w = 1 + w - l(m, g), S = p((C = (f % 864e5 + 864e5) % 864e5) / 36e5) % 24, k = p(C / 6e4) % 60, T = p(C / 1e3) % 60, E = C % 1e3
                                    } else m = f.getUTCFullYear(), g = f.getUTCMonth(), w = f.getUTCDate(), S = f.getUTCHours(), k = f.getUTCMinutes(), T = f.getUTCSeconds(), E = f.getUTCMilliseconds();
                                    f = (m <= 0 || m >= 1e4 ? (m < 0 ? "-" : "+") + v(6, m < 0 ? -m : m) : v(4, m)) + "-" + v(2, g + 1) + "-" + v(2, w) + "T" + v(2, S) + ":" + v(2, k) + ":" + v(2, T) + "." + v(3, E) + "Z"
                                } else f = null;
                                if (n && (f = n.call(t, e, f)), null === f) return "null";
                                if ("[object Boolean]" == (h = a.call(f))) return "" + f;
                                if ("[object Number]" == h) return f > -1 / 0 && f < 1 / 0 ? "" + f : "null";
                                if ("[object String]" == h) return y("" + f);
                                if ("object" == typeof f) {
                                    for (O = u.length; O--;)
                                        if (u[O] === f) throw TypeError();
                                    if (u.push(f), P = [], D = d, d += c, "[object Array]" == h) {
                                        for (R = 0, O = f.length; R < O; R++) x = b(R, f, n, s, c, d, u), P.push(x === o ? "null" : x);
                                        _ = P.length ? c ? "[\n" + d + P.join(",\n" + d) + "\n" + D + "]" : "[" + P.join(",") + "]" : "[]"
                                    } else i(s || f, function(e) {
                                        var t = b(e, f, n, s, c, d, u);
                                        t !== o && P.push(y(e) + ":" + (c ? " " : "") + t)
                                    }), _ = P.length ? c ? "{\n" + d + P.join(",\n" + d) + "\n" + D + "}" : "{" + P.join(",") + "}" : "{}";
                                    return u.pop(), _
                                }
                            };
                        c.stringify = function(e, t, n) {
                            var r, i, o, s;
                            if ("function" == typeof t || "object" == typeof t && t)
                                if ("[object Function]" == (s = a.call(t))) i = t;
                                else if ("[object Array]" == s) {
                                o = {};
                                for (var c, d = 0, u = t.length; d < u; c = t[d++], ("[object String]" == (s = a.call(c)) || "[object Number]" == s) && (o[c] = 1));
                            }
                            if (n)
                                if ("[object Number]" == (s = a.call(n))) {
                                    if ((n -= n % 1) > 0)
                                        for (r = "", n > 10 && (n = 10); r.length < n; r += " ");
                                } else "[object String]" == s && (r = n.length <= 10 ? n : n.slice(0, 10));
                            return b("", (c = {}, c[""] = e, c), i, o, r, "", [])
                        }
                    }
                    if (!t("json-parse")) {
                        var w, C, S = String.fromCharCode,
                            k = {
                                92: "\\",
                                34: '"',
                                47: "/",
                                98: "\b",
                                116: "\t",
                                110: "\n",
                                102: "\f",
                                114: "\r"
                            },
                            T = function() {
                                throw w = C = null, SyntaxError()
                            },
                            E = function() {
                                for (var e, t, n, r, i, o = C, a = o.length; w < a;) switch (i = o.charCodeAt(w)) {
                                    case 9:
                                    case 10:
                                    case 13:
                                    case 32:
                                        w++;
                                        break;
                                    case 123:
                                    case 125:
                                    case 91:
                                    case 93:
                                    case 58:
                                    case 44:
                                        return e = u ? o.charAt(w) : o[w], w++, e;
                                    case 34:
                                        for (e = "@", w++; w < a;)
                                            if ((i = o.charCodeAt(w)) < 32) T();
                                            else if (92 == i) switch (i = o.charCodeAt(++w)) {
                                            case 92:
                                            case 34:
                                            case 47:
                                            case 98:
                                            case 116:
                                            case 110:
                                            case 102:
                                            case 114:
                                                e += k[i], w++;
                                                break;
                                            case 117:
                                                for (t = ++w, n = w + 4; w < n; w++)(i = o.charCodeAt(w)) >= 48 && i <= 57 || i >= 97 && i <= 102 || i >= 65 && i <= 70 || T();
                                                e += S("0x" + o.slice(t, w));
                                                break;
                                            default:
                                                T()
                                        } else {
                                            if (34 == i) break;
                                            for (i = o.charCodeAt(w), t = w; i >= 32 && 92 != i && 34 != i;) i = o.charCodeAt(++w);
                                            e += o.slice(t, w)
                                        }
                                        if (34 == o.charCodeAt(w)) return w++, e;
                                        T();
                                    default:
                                        if (t = w, 45 == i && (r = !0, i = o.charCodeAt(++w)), i >= 48 && i <= 57) {
                                            for (48 == i && (i = o.charCodeAt(w + 1)) >= 48 && i <= 57 && T(), r = !1; w < a && (i = o.charCodeAt(w)) >= 48 && i <= 57; w++);
                                            if (46 == o.charCodeAt(w)) {
                                                for (n = ++w; n < a && (i = o.charCodeAt(n)) >= 48 && i <= 57; n++);
                                                n == w && T(), w = n
                                            }
                                            if (101 == (i = o.charCodeAt(w)) || 69 == i) {
                                                for (43 != (i = o.charCodeAt(++w)) && 45 != i || w++, n = w; n < a && (i = o.charCodeAt(n)) >= 48 && i <= 57; n++);
                                                n == w && T(), w = n
                                            }
                                            return +o.slice(t, w)
                                        }
                                        if (r && T(), "true" == o.slice(w, w + 4)) return w += 4, !0;
                                        if ("false" == o.slice(w, w + 5)) return w += 5, !1;
                                        if ("null" == o.slice(w, w + 4)) return w += 4, null;
                                        T()
                                }
                                return "$"
                            },
                            P = function(e) {
                                var t, n;
                                if ("$" == e && T(), "string" == typeof e) {
                                    if ("@" == (u ? e.charAt(0) : e[0])) return e.slice(1);
                                    if ("[" == e) {
                                        for (t = [];
                                            "]" != (e = E()); n || (n = !0)) n && ("," == e ? "]" == (e = E()) && T() : T()), "," == e && T(), t.push(P(e));
                                        return t
                                    }
                                    if ("{" == e) {
                                        for (t = {};
                                            "}" != (e = E()); n || (n = !0)) n && ("," == e ? "}" == (e = E()) && T() : T()), "," != e && "string" == typeof e && "@" == (u ? e.charAt(0) : e[0]) && ":" == E() || T(), t[e.slice(1)] = P(E());
                                        return t
                                    }
                                    T()
                                }
                                return e
                            },
                            x = function(e, t, n) {
                                var r = R(e, t, n);
                                r === o ? delete e[t] : e[t] = r
                            },
                            R = function(e, t, n) {
                                var r, o = e[t];
                                if ("object" == typeof o && o)
                                    if ("[object Array]" == a.call(o))
                                        for (r = o.length; r--;) x(o, r, n);
                                    else i(o, function(e) {
                                        x(o, e, n)
                                    });
                                return n.call(e, t, o)
                            };
                        c.parse = function(e, t) {
                            var n, r;
                            return w = 0, C = "" + e, n = P(E()), "$" != E() && T(), w = C = null, t && "[object Function]" == a.call(t) ? R((r = {}, r[""] = n, r), "", t) : n
                        }
                    }
                }
            }(this)
        }, {}],
        44: [function(e, t, n) {
            function r(e) {
                var t = !0;
                return e.getTracks().forEach(function(e) {
                    t = "ended" === e.readyState && t
                }), t
            }

            function i() {
                if ("undefined" == typeof window) return !1;
                if (!window.navigator.mozGetUserMedia) return !1;
                var e = window.navigator.userAgent.match(/Firefox\/(\d+)\./);
                return (e && e.length >= 1 && parseInt(e[1], 10)) < 50
            }

            function o(e) {
                d.call(this);
                var t, n = this.config = {
                    detectSpeakingEvents: !1,
                    audioFallback: !1,
                    media: {
                        audio: !0,
                        video: !0
                    },
                    harkOptions: null,
                    logger: u
                };
                for (t in e) e.hasOwnProperty(t) && (this.config[t] = e[t]);
                this.logger = n.logger, this._log = this.logger.log.bind(this.logger, "LocalMedia:"), this._logerror = this.logger.error.bind(this.logger, "LocalMedia:"), this.localStreams = [], this.localScreens = [], navigator.mediaDevices && navigator.mediaDevices.getUserMedia || this._logerror("Your browser does not support local media capture."), this._audioMonitors = [], this.on("localStreamStopped", this._stopAudioMonitor.bind(this)), this.on("localScreenStopped", this._stopAudioMonitor.bind(this))
            }
            var a = e("util"),
                s = e("hark"),
                c = e("getscreenmedia"),
                d = e("wildemitter"),
                u = e("mockconsole");
            a.inherits(o, d), o.prototype.start = function(e, t) {
                var n = this,
                    i = e || this.config.media;
                this.emit("localStreamRequested", i), navigator.mediaDevices.getUserMedia(i).then(function(e) {
                    if (i.audio && n.config.detectSpeakingEvents && n._setupAudioMonitor(e, n.config.harkOptions), n.localStreams.push(e), e.getTracks().forEach(function(t) {
                            t.addEventListener("ended", function() {
                                r(e) && n._removeStream(e)
                            })
                        }), n.emit("localStream", e), t) return t(null, e)
                }).catch(function(e) {
                    return n.config.audioFallback && "DevicesNotFoundError" === e.name && !1 !== i.video ? (i.video = !1, void n.start(i, t)) : (n.emit("localStreamRequestFailed", i), t ? t(e, null) : void 0)
                })
            }, o.prototype.stop = function(e) {
                this.stopStream(e), this.stopScreenShare(e)
            }, o.prototype.stopStream = function(e) {
                var t = this;
                e ? this.localStreams.indexOf(e) > -1 && (e.getTracks().forEach(function(e) {
                    e.stop()
                }), i() && this._removeStream(e)) : this.localStreams.forEach(function(e) {
                    e.getTracks().forEach(function(e) {
                        e.stop()
                    }), i() && t._removeStream(e)
                })
            }, o.prototype.startScreenShare = function(e, t) {
                var n = this;
                this.emit("localScreenRequested"), "function" != typeof e || t || (t = e, e = null), c(e, function(e, r) {
                    if (e ? n.emit("localScreenRequestFailed") : (n.localScreens.push(r), r.getTracks().forEach(function(e) {
                            e.addEventListener("ended", function() {
                                var e = !0;
                                r.getTracks().forEach(function(t) {
                                    e = "ended" === t.readyState && e
                                }), e && n._removeStream(r)
                            })
                        }), n.emit("localScreen", r)), t) return t(e, r)
                })
            }, o.prototype.stopScreenShare = function(e) {
                var t = this;
                e ? this.localScreens.indexOf(e) > -1 && (e.getTracks().forEach(function(e) {
                    e.stop()
                }), i() && this._removeStream(e)) : this.localScreens.forEach(function(e) {
                    e.getTracks().forEach(function(e) {
                        e.stop()
                    }), i() && t._removeStream(e)
                })
            }, o.prototype.mute = function() {
                this._audioEnabled(!1), this.emit("audioOff")
            }, o.prototype.unmute = function() {
                this._audioEnabled(!0), this.emit("audioOn")
            }, o.prototype.pauseVideo = function() {
                this._videoEnabled(!1), this.emit("videoOff")
            }, o.prototype.resumeVideo = function() {
                this._videoEnabled(!0), this.emit("videoOn")
            }, o.prototype.pause = function() {
                this.mute(), this.pauseVideo()
            }, o.prototype.resume = function() {
                this.unmute(), this.resumeVideo()
            }, o.prototype._audioEnabled = function(e) {
                this.localStreams.forEach(function(t) {
                    t.getAudioTracks().forEach(function(t) {
                        t.enabled = !!e
                    })
                })
            }, o.prototype._videoEnabled = function(e) {
                this.localStreams.forEach(function(t) {
                    t.getVideoTracks().forEach(function(t) {
                        t.enabled = !!e
                    })
                })
            }, o.prototype.isAudioEnabled = function() {
                var e = !0;
                return this.localStreams.forEach(function(t) {
                    t.getAudioTracks().forEach(function(t) {
                        e = e && t.enabled
                    })
                }), e
            }, o.prototype.isVideoEnabled = function() {
                var e = !0;
                return this.localStreams.forEach(function(t) {
                    t.getVideoTracks().forEach(function(t) {
                        e = e && t.enabled
                    })
                }), e
            }, o.prototype._removeStream = function(e) {
                var t = this.localStreams.indexOf(e);
                t > -1 ? (this.localStreams.splice(t, 1), this.emit("localStreamStopped", e)) : (t = this.localScreens.indexOf(e)) > -1 && (this.localScreens.splice(t, 1), this.emit("localScreenStopped", e))
            }, o.prototype._setupAudioMonitor = function(e, t) {
                this._log("Setup audio");
                var n, r = s(e, t),
                    i = this;
                r.on("speaking", function() {
                    i.emit("speaking")
                }), r.on("stopped_speaking", function() {
                    n && clearTimeout(n), n = setTimeout(function() {
                        i.emit("stoppedSpeaking")
                    }, 1e3)
                }), r.on("volume_change", function(e, t) {
                    i.emit("volumeChange", e, t)
                }), this._audioMonitors.push({
                    audio: r,
                    stream: e
                })
            }, o.prototype._stopAudioMonitor = function(e) {
                var t = -1;
                this._audioMonitors.forEach(function(n, r) {
                    n.stream === e && (t = r)
                }), t > -1 && (this._audioMonitors[t].audio.stop(), this._audioMonitors.splice(t, 1))
            }, t.exports = o
        }, {
            getscreenmedia: 36,
            hark: 38,
            mockconsole: 46,
            util: 73,
            wildemitter: 75
        }],
        45: [function(e, t, n) {
            (function(e) {
                function r(e, t) {
                    return e.set(t[0], t[1]), e
                }

                function i(e, t) {
                    return e.add(t), e
                }

                function o(e, t) {
                    for (var n = -1, r = e ? e.length : 0; ++n < r && !1 !== t(e[n], n, e););
                    return e
                }

                function a(e, t) {
                    for (var n = -1, r = t.length, i = e.length; ++n < r;) e[i + n] = t[n];
                    return e
                }

                function s(e, t, n, r) {
                    var i = -1,
                        o = e ? e.length : 0;
                    for (r && o && (n = e[++i]); ++i < o;) n = t(n, e[i], i, e);
                    return n
                }

                function c(e, t) {
                    for (var n = -1, r = Array(e); ++n < e;) r[n] = t(n);
                    return r
                }

                function d(e, t) {
                    return null == e ? void 0 : e[t]
                }

                function u(e) {
                    var t = !1;
                    if (null != e && "function" != typeof e.toString) try {
                        t = !!(e + "")
                    } catch (e) {}
                    return t
                }

                function p(e) {
                    var t = -1,
                        n = Array(e.size);
                    return e.forEach(function(e, r) {
                        n[++t] = [r, e]
                    }), n
                }

                function f(e, t) {
                    return function(n) {
                        return e(t(n))
                    }
                }

                function l(e) {
                    var t = -1,
                        n = Array(e.size);
                    return e.forEach(function(e) {
                        n[++t] = e
                    }), n
                }

                function h(e) {
                    var t = -1,
                        n = e ? e.length : 0;
                    for (this.clear(); ++t < n;) {
                        var r = e[t];
                        this.set(r[0], r[1])
                    }
                }

                function m(e) {
                    var t = -1,
                        n = e ? e.length : 0;
                    for (this.clear(); ++t < n;) {
                        var r = e[t];
                        this.set(r[0], r[1])
                    }
                }

                function g(e) {
                    var t = -1,
                        n = e ? e.length : 0;
                    for (this.clear(); ++t < n;) {
                        var r = e[t];
                        this.set(r[0], r[1])
                    }
                }

                function v(e) {
                    this.__data__ = new m(e)
                }

                function y(e, t) {
                    var n = Ct(e) || K(e) ? c(e.length, String) : [],
                        r = n.length,
                        i = !!r;
                    for (var o in e) !t && !We.call(e, o) || i && ("length" == o || V(o, r)) || n.push(o);
                    return n
                }

                function b(e, t, n) {
                    var r = e[t];
                    We.call(e, t) && $(r, n) && (void 0 !== n || t in e) || (e[t] = n)
                }

                function w(e, t) {
                    for (var n = e.length; n--;)
                        if ($(e[n][0], t)) return n;
                    return -1
                }

                function C(e, t) {
                    return e && I(t, re(t), e)
                }

                function S(e, t, n, r, i, a, s) {
                    var c;
                    if (r && (c = a ? r(e, i, a, s) : r(e)), void 0 !== c) return c;
                    if (!te(e)) return e;
                    var d = Ct(e);
                    if (d) {
                        if (c = G(e), !t) return L(e, c)
                    } else {
                        var p = wt(e),
                            f = p == ue || p == pe;
                        if (St(e)) return x(e, t);
                        if (p == he || p == se || f && !a) {
                            if (u(e)) return a ? e : {};
                            if (c = z(f ? {} : e), !t) return N(e, C(c, e))
                        } else {
                            if (!Ae[p]) return a ? e : {};
                            c = J(e, p, S, t)
                        }
                    }
                    s || (s = new v);
                    var l = s.get(e);
                    if (l) return l;
                    if (s.set(e, c), !d) var h = n ? U(e) : re(e);
                    return o(h || e, function(i, o) {
                        h && (i = e[o = i]), b(c, o, S(i, t, n, r, o, e, s))
                    }), c
                }

                function k(e) {
                    return te(e) ? et(e) : {}
                }

                function T(e, t, n) {
                    var r = t(e);
                    return Ct(e) ? r : a(r, n(e))
                }

                function E(e) {
                    return !(!te(e) || H(e)) && (Q(e) || u(e) ? $e : je).test(X(e))
                }

                function P(e) {
                    if (!W(e)) return ot(e);
                    var t = [];
                    for (var n in Object(e)) We.call(e, n) && "constructor" != n && t.push(n);
                    return t
                }

                function x(e, t) {
                    if (t) return e.slice();
                    var n = new e.constructor(e.length);
                    return e.copy(n), n
                }

                function R(e) {
                    var t = new e.constructor(e.byteLength);
                    return new Ze(t).set(new Ze(e)), t
                }

                function O(e, t) {
                    var n = t ? R(e.buffer) : e.buffer;
                    return new e.constructor(n, e.byteOffset, e.byteLength)
                }

                function D(e, t, n) {
                    return s(t ? n(p(e), !0) : p(e), r, new e.constructor)
                }

                function _(e) {
                    var t = new e.constructor(e.source, _e.exec(e));
                    return t.lastIndex = e.lastIndex, t
                }

                function j(e, t, n) {
                    return s(t ? n(l(e), !0) : l(e), i, new e.constructor)
                }

                function M(e) {
                    return yt ? Object(yt.call(e)) : {}
                }

                function A(e, t) {
                    var n = t ? R(e.buffer) : e.buffer;
                    return new e.constructor(n, e.byteOffset, e.length)
                }

                function L(e, t) {
                    var n = -1,
                        r = e.length;
                    for (t || (t = Array(r)); ++n < r;) t[n] = e[n];
                    return t
                }

                function I(e, t, n, r) {
                    n || (n = {});
                    for (var i = -1, o = t.length; ++i < o;) {
                        var a = t[i],
                            s = r ? r(n[a], e[a], a, n, e) : void 0;
                        b(n, a, void 0 === s ? e[a] : s)
                    }
                    return n
                }

                function N(e, t) {
                    return I(e, bt(e), t)
                }

                function U(e) {
                    return T(e, re, bt)
                }

                function B(e, t) {
                    var n = e.__data__;
                    return q(t) ? n["string" == typeof t ? "string" : "hash"] : n.map
                }

                function F(e, t) {
                    var n = d(e, t);
                    return E(n) ? n : void 0
                }

                function G(e) {
                    var t = e.length,
                        n = e.constructor(t);
                    return t && "string" == typeof e[0] && We.call(e, "index") && (n.index = e.index, n.input = e.input), n
                }

                function z(e) {
                    return "function" != typeof e.constructor || W(e) ? {} : k(Qe(e))
                }

                function J(e, t, n, r) {
                    var i = e.constructor;
                    switch (t) {
                        case be:
                            return R(e);
                        case ce:
                        case de:
                            return new i(+e);
                        case we:
                            return O(e, r);
                        case Ce:
                        case Se:
                        case ke:
                        case Te:
                        case Ee:
                        case Pe:
                        case xe:
                        case Re:
                        case Oe:
                            return A(e, r);
                        case fe:
                            return D(e, r, n);
                        case le:
                        case ve:
                            return new i(e);
                        case me:
                            return _(e);
                        case ge:
                            return j(e, r, n);
                        case ye:
                            return M(e)
                    }
                }

                function V(e, t) {
                    return !!(t = null == t ? ae : t) && ("number" == typeof e || Me.test(e)) && e > -1 && e % 1 == 0 && e < t
                }

                function q(e) {
                    var t = typeof e;
                    return "string" == t || "number" == t || "symbol" == t || "boolean" == t ? "__proto__" !== e : null === e
                }

                function H(e) {
                    return !!qe && qe in e
                }

                function W(e) {
                    var t = e && e.constructor;
                    return e === ("function" == typeof t && t.prototype || Je)
                }

                function X(e) {
                    if (null != e) {
                        try {
                            return He.call(e)
                        } catch (e) {}
                        try {
                            return e + ""
                        } catch (e) {}
                    }
                    return ""
                }

                function $(e, t) {
                    return e === t || e !== e && t !== t
                }

                function K(e) {
                    return Z(e) && We.call(e, "callee") && (!tt.call(e, "callee") || Xe.call(e) == se)
                }

                function Y(e) {
                    return null != e && ee(e.length) && !Q(e)
                }

                function Z(e) {
                    return ne(e) && Y(e)
                }

                function Q(e) {
                    var t = te(e) ? Xe.call(e) : "";
                    return t == ue || t == pe
                }

                function ee(e) {
                    return "number" == typeof e && e > -1 && e % 1 == 0 && e <= ae
                }

                function te(e) {
                    var t = typeof e;
                    return !!e && ("object" == t || "function" == t)
                }

                function ne(e) {
                    return !!e && "object" == typeof e
                }

                function re(e) {
                    return Y(e) ? y(e) : P(e)
                }
                var ie = 200,
                    oe = "__lodash_hash_undefined__",
                    ae = 9007199254740991,
                    se = "[object Arguments]",
                    ce = "[object Boolean]",
                    de = "[object Date]",
                    ue = "[object Function]",
                    pe = "[object GeneratorFunction]",
                    fe = "[object Map]",
                    le = "[object Number]",
                    he = "[object Object]",
                    me = "[object RegExp]",
                    ge = "[object Set]",
                    ve = "[object String]",
                    ye = "[object Symbol]",
                    be = "[object ArrayBuffer]",
                    we = "[object DataView]",
                    Ce = "[object Float32Array]",
                    Se = "[object Float64Array]",
                    ke = "[object Int8Array]",
                    Te = "[object Int16Array]",
                    Ee = "[object Int32Array]",
                    Pe = "[object Uint8Array]",
                    xe = "[object Uint8ClampedArray]",
                    Re = "[object Uint16Array]",
                    Oe = "[object Uint32Array]",
                    De = /[\\^$.*+?()[\]{}|]/g,
                    _e = /\w*$/,
                    je = /^\[object .+?Constructor\]$/,
                    Me = /^(?:0|[1-9]\d*)$/,
                    Ae = {};
                Ae[se] = Ae["[object Array]"] = Ae[be] = Ae[we] = Ae[ce] = Ae[de] = Ae[Ce] = Ae[Se] = Ae[ke] = Ae[Te] = Ae[Ee] = Ae[fe] = Ae[le] = Ae[he] = Ae[me] = Ae[ge] = Ae[ve] = Ae[ye] = Ae[Pe] = Ae[xe] = Ae[Re] = Ae[Oe] = !0, Ae["[object Error]"] = Ae[ue] = Ae["[object WeakMap]"] = !1;
                var Le = "object" == typeof e && e && e.Object === Object && e,
                    Ie = "object" == typeof self && self && self.Object === Object && self,
                    Ne = Le || Ie || Function("return this")(),
                    Ue = "object" == typeof n && n && !n.nodeType && n,
                    Be = Ue && "object" == typeof t && t && !t.nodeType && t,
                    Fe = Be && Be.exports === Ue,
                    Ge = Array.prototype,
                    ze = Function.prototype,
                    Je = Object.prototype,
                    Ve = Ne["__core-js_shared__"],
                    qe = function() {
                        var e = /[^.]+$/.exec(Ve && Ve.keys && Ve.keys.IE_PROTO || "");
                        return e ? "Symbol(src)_1." + e : ""
                    }(),
                    He = ze.toString,
                    We = Je.hasOwnProperty,
                    Xe = Je.toString,
                    $e = RegExp("^" + He.call(We).replace(De, "\\$&").replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g, "$1.*?") + "$"),
                    Ke = Fe ? Ne.Buffer : void 0,
                    Ye = Ne.Symbol,
                    Ze = Ne.Uint8Array,
                    Qe = f(Object.getPrototypeOf, Object),
                    et = Object.create,
                    tt = Je.propertyIsEnumerable,
                    nt = Ge.splice,
                    rt = Object.getOwnPropertySymbols,
                    it = Ke ? Ke.isBuffer : void 0,
                    ot = f(Object.keys, Object),
                    at = F(Ne, "DataView"),
                    st = F(Ne, "Map"),
                    ct = F(Ne, "Promise"),
                    dt = F(Ne, "Set"),
                    ut = F(Ne, "WeakMap"),
                    pt = F(Object, "create"),
                    ft = X(at),
                    lt = X(st),
                    ht = X(ct),
                    mt = X(dt),
                    gt = X(ut),
                    vt = Ye ? Ye.prototype : void 0,
                    yt = vt ? vt.valueOf : void 0;
                h.prototype.clear = function() {
                    this.__data__ = pt ? pt(null) : {}
                }, h.prototype.delete = function(e) {
                    return this.has(e) && delete this.__data__[e]
                }, h.prototype.get = function(e) {
                    var t = this.__data__;
                    if (pt) {
                        var n = t[e];
                        return n === oe ? void 0 : n
                    }
                    return We.call(t, e) ? t[e] : void 0
                }, h.prototype.has = function(e) {
                    var t = this.__data__;
                    return pt ? void 0 !== t[e] : We.call(t, e)
                }, h.prototype.set = function(e, t) {
                    return this.__data__[e] = pt && void 0 === t ? oe : t, this
                }, m.prototype.clear = function() {
                    this.__data__ = []
                }, m.prototype.delete = function(e) {
                    var t = this.__data__,
                        n = w(t, e);
                    return !(n < 0 || (n == t.length - 1 ? t.pop() : nt.call(t, n, 1), 0))
                }, m.prototype.get = function(e) {
                    var t = this.__data__,
                        n = w(t, e);
                    return n < 0 ? void 0 : t[n][1]
                }, m.prototype.has = function(e) {
                    return w(this.__data__, e) > -1
                }, m.prototype.set = function(e, t) {
                    var n = this.__data__,
                        r = w(n, e);
                    return r < 0 ? n.push([e, t]) : n[r][1] = t, this
                }, g.prototype.clear = function() {
                    this.__data__ = {
                        hash: new h,
                        map: new(st || m),
                        string: new h
                    }
                }, g.prototype.delete = function(e) {
                    return B(this, e).delete(e)
                }, g.prototype.get = function(e) {
                    return B(this, e).get(e)
                }, g.prototype.has = function(e) {
                    return B(this, e).has(e)
                }, g.prototype.set = function(e, t) {
                    return B(this, e).set(e, t), this
                }, v.prototype.clear = function() {
                    this.__data__ = new m
                }, v.prototype.delete = function(e) {
                    return this.__data__.delete(e)
                }, v.prototype.get = function(e) {
                    return this.__data__.get(e)
                }, v.prototype.has = function(e) {
                    return this.__data__.has(e)
                }, v.prototype.set = function(e, t) {
                    var n = this.__data__;
                    if (n instanceof m) {
                        var r = n.__data__;
                        if (!st || r.length < ie - 1) return r.push([e, t]), this;
                        n = this.__data__ = new g(r)
                    }
                    return n.set(e, t), this
                };
                var bt = rt ? f(rt, Object) : function() {
                        return []
                    },
                    wt = function(e) {
                        return Xe.call(e)
                    };
                (at && wt(new at(new ArrayBuffer(1))) != we || st && wt(new st) != fe || ct && "[object Promise]" != wt(ct.resolve()) || dt && wt(new dt) != ge || ut && "[object WeakMap]" != wt(new ut)) && (wt = function(e) {
                    var t = Xe.call(e),
                        n = t == he ? e.constructor : void 0,
                        r = n ? X(n) : void 0;
                    if (r) switch (r) {
                        case ft:
                            return we;
                        case lt:
                            return fe;
                        case ht:
                            return "[object Promise]";
                        case mt:
                            return ge;
                        case gt:
                            return "[object WeakMap]"
                    }
                    return t
                });
                var Ct = Array.isArray,
                    St = it || function() {
                        return !1
                    };
                t.exports = function(e) {
                    return S(e, !0, !0)
                }
            }).call(this, "undefined" != typeof global ? global : "undefined" != typeof self ? self : "undefined" != typeof window ? window : {})
        }, {}],
        46: [function(e, t, n) {
            for (var r = "assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","), i = r.length, o = {}; i--;) o[r[i]] = function() {};
            t.exports = o
        }, {}],
        47: [function(e, t, n) {
            function r(e) {
                var t = /^((?:\d+)?\.?\d+) *(ms|seconds?|s|minutes?|m|hours?|h|days?|d|years?|y)?$/i.exec(e);
                if (t) {
                    var n = parseFloat(t[1]);
                    switch ((t[2] || "ms").toLowerCase()) {
                        case "years":
                        case "year":
                        case "y":
                            return n * p;
                        case "days":
                        case "day":
                        case "d":
                            return n * u;
                        case "hours":
                        case "hour":
                        case "h":
                            return n * d;
                        case "minutes":
                        case "minute":
                        case "m":
                            return n * c;
                        case "seconds":
                        case "second":
                        case "s":
                            return n * s;
                        case "ms":
                            return n
                    }
                }
            }

            function i(e) {
                return e >= u ? Math.round(e / u) + "d" : e >= d ? Math.round(e / d) + "h" : e >= c ? Math.round(e / c) + "m" : e >= s ? Math.round(e / s) + "s" : e + "ms"
            }

            function o(e) {
                return a(e, u, "day") || a(e, d, "hour") || a(e, c, "minute") || a(e, s, "second") || e + " ms"
            }

            function a(e, t, n) {
                if (!(e < t)) return e < 1.5 * t ? Math.floor(e / t) + " " + n : Math.ceil(e / t) + " " + n + "s"
            }
            var s = 1e3,
                c = 60 * s,
                d = 60 * c,
                u = 24 * d,
                p = 365.25 * u;
            t.exports = function(e, t) {
                return t = t || {}, "string" == typeof e ? r(e) : t.long ? o(e) : i(e)
            }
        }, {}],
        48: [function(e, t, n) {
            var r = Object.prototype.hasOwnProperty;
            n.keys = Object.keys || function(e) {
                var t = [];
                for (var n in e) r.call(e, n) && t.push(n);
                return t
            }, n.values = function(e) {
                var t = [];
                for (var n in e) r.call(e, n) && t.push(e[n]);
                return t
            }, n.merge = function(e, t) {
                for (var n in t) r.call(t, n) && (e[n] = t[n]);
                return e
            }, n.length = function(e) {
                return n.keys(e).length
            }, n.isEmpty = function(e) {
                return 0 == n.length(e)
            }
        }, {}],
        49: [function(e, t, n) {
            (function(e) {
                var n = /^[\],:{}\s]*$/,
                    r = /\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,
                    i = /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,
                    o = /(?:^|:|,)(?:\s*\[)+/g,
                    a = /^\s+/,
                    s = /\s+$/;
                t.exports = function(t) {
                    return "string" == typeof t && t ? (t = t.replace(a, "").replace(s, ""), e.JSON && JSON.parse ? JSON.parse(t) : n.test(t.replace(r, "@").replace(i, "]").replace(o, "")) ? new Function("return " + t)() : void 0) : null
                }
            }).call(this, "undefined" != typeof global ? global : "undefined" != typeof self ? self : "undefined" != typeof window ? window : {})
        }, {}],
        50: [function(e, t, n) {
            n.encode = function(e) {
                var t = "";
                for (var n in e) e.hasOwnProperty(n) && (t.length && (t += "&"), t += encodeURIComponent(n) + "=" + encodeURIComponent(e[n]));
                return t
            }, n.decode = function(e) {
                for (var t = {}, n = e.split("&"), r = 0, i = n.length; r < i; r++) {
                    var o = n[r].split("=");
                    t[decodeURIComponent(o[0])] = decodeURIComponent(o[1])
                }
                return t
            }
        }, {}],
        51: [function(e, t, n) {
            var r = /^(?:(?![^:@]+:[^:@\/]*@)(http|https|ws|wss):\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?((?:[a-f0-9]{0,4}:){2,7}[a-f0-9]{0,4}|[^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/,
                i = ["source", "protocol", "authority", "userInfo", "user", "password", "host", "port", "relative", "path", "directory", "file", "query", "anchor"];
            t.exports = function(e) {
                for (var t = r.exec(e || ""), n = {}, o = 14; o--;) n[i[o]] = t[o] || "";
                return n
            }
        }, {}],
        52: [function(e, t, n) {
            function r() {
                throw new Error("setTimeout has not been defined")
            }

            function i() {
                throw new Error("clearTimeout has not been defined")
            }

            function o(e) {
                if (p === setTimeout) return setTimeout(e, 0);
                if ((p === r || !p) && setTimeout) return p = setTimeout, setTimeout(e, 0);
                try {
                    return p(e, 0)
                } catch (t) {
                    try {
                        return p.call(null, e, 0)
                    } catch (t) {
                        return p.call(this, e, 0)
                    }
                }
            }

            function a(e) {
                if (f === clearTimeout) return clearTimeout(e);
                if ((f === i || !f) && clearTimeout) return f = clearTimeout, clearTimeout(e);
                try {
                    return f(e)
                } catch (t) {
                    try {
                        return f.call(null, e)
                    } catch (t) {
                        return f.call(this, e)
                    }
                }
            }

            function s() {
                g && h && (g = !1, h.length ? m = h.concat(m) : v = -1, m.length && c())
            }

            function c() {
                if (!g) {
                    var e = o(s);
                    g = !0;
                    for (var t = m.length; t;) {
                        for (h = m, m = []; ++v < t;) h && h[v].run();
                        v = -1, t = m.length
                    }
                    h = null, g = !1, a(e)
                }
            }

            function d(e, t) {
                this.fun = e, this.array = t
            }

            function u() {}
            var p, f, l = t.exports = {};
            ! function() {
                try {
                    p = "function" == typeof setTimeout ? setTimeout : r
                } catch (e) {
                    p = r
                }
                try {
                    f = "function" == typeof clearTimeout ? clearTimeout : i
                } catch (e) {
                    f = i
                }
            }();
            var h, m = [],
                g = !1,
                v = -1;
            l.nextTick = function(e) {
                var t = new Array(arguments.length - 1);
                if (arguments.length > 1)
                    for (var n = 1; n < arguments.length; n++) t[n - 1] = arguments[n];
                m.push(new d(e, t)), 1 !== m.length || g || o(c)
            }, d.prototype.run = function() {
                this.fun.apply(null, this.array)
            }, l.title = "browser", l.browser = !0, l.env = {}, l.argv = [], l.version = "", l.versions = {}, l.on = u, l.addListener = u, l.once = u, l.off = u, l.removeListener = u, l.removeAllListeners = u, l.emit = u, l.prependListener = u, l.prependOnceListener = u, l.listeners = function(e) {
                return []
            }, l.binding = function(e) {
                throw new Error("process.binding is not supported")
            }, l.cwd = function() {
                return "/"
            }, l.chdir = function(e) {
                throw new Error("process.chdir is not supported")
            }, l.umask = function() {
                return 0
            }
        }, {}],
        53: [function(e, t, n) {
            function r(e, t) {
                var n, r = this;
                a.call(this), (e = e || {}).iceServers = e.iceServers || [], this.enableChromeNativeSimulcast = !1, t && t.optional && window.chrome && null === navigator.appVersion.match(/Chromium\//) && t.optional.forEach(function(e) {
                    e.enableChromeNativeSimulcast && (r.enableChromeNativeSimulcast = !0)
                }), this.enableMultiStreamHacks = !1, t && t.optional && window.chrome && t.optional.forEach(function(e) {
                    e.enableMultiStreamHacks && (r.enableMultiStreamHacks = !0)
                }), this.restrictBandwidth = 0, t && t.optional && t.optional.forEach(function(e) {
                    e.andyetRestrictBandwidth && (r.restrictBandwidth = e.andyetRestrictBandwidth)
                }), this.batchIceCandidates = 0, t && t.optional && t.optional.forEach(function(e) {
                    e.andyetBatchIce && (r.batchIceCandidates = e.andyetBatchIce)
                }), this.batchedIceCandidates = [], t && t.optional && window.chrome && t.optional.forEach(function(e) {
                    e.andyetFasterICE && (r.eliminateDuplicateCandidates = e.andyetFasterICE)
                }), t && t.optional && t.optional.forEach(function(e) {
                    e.andyetDontSignalCandidates && (r.dontSignalCandidates = e.andyetDontSignalCandidates)
                }), this.assumeSetLocalSuccess = !1, t && t.optional && t.optional.forEach(function(e) {
                    e.andyetAssumeSetLocalSuccess && (r.assumeSetLocalSuccess = e.andyetAssumeSetLocalSuccess)
                }), window.navigator.mozGetUserMedia && t && t.optional && (this.wtFirefox = 0, t.optional.forEach(function(e) {
                    e.andyetFirefoxMakesMeSad && (r.wtFirefox = e.andyetFirefoxMakesMeSad, r.wtFirefox > 0 && (r.firefoxcandidatebuffer = []))
                })), this.pc = new RTCPeerConnection(e, t), "function" == typeof this.pc.getLocalStreams ? this.getLocalStreams = this.pc.getLocalStreams.bind(this.pc) : this.getLocalStreams = function() {
                    return []
                }, this.getRemoteStreams = this.pc.getRemoteStreams.bind(this.pc), this.addStream = this.pc.addStream.bind(this.pc), this.removeStream = function(e) {
                    "function" == typeof r.pc.removeStream ? r.pc.removeStream.apply(r.pc, arguments) : "function" == typeof r.pc.removeTrack && e.getTracks().forEach(function(e) {
                        r.pc.removeTrack(e)
                    })
                }, "function" == typeof this.pc.removeTrack && (this.removeTrack = this.pc.removeTrack.bind(this.pc)), this.pc.onremovestream = this.emit.bind(this, "removeStream"), this.pc.onremovetrack = this.emit.bind(this, "removeTrack"), this.pc.onaddstream = this.emit.bind(this, "addStream"), this.pc.onnegotiationneeded = this.emit.bind(this, "negotiationNeeded"), this.pc.oniceconnectionstatechange = this.emit.bind(this, "iceConnectionStateChange"), this.pc.onsignalingstatechange = this.emit.bind(this, "signalingStateChange"), this.pc.onicecandidate = this._onIce.bind(this), this.pc.ondatachannel = this._onDataChannel.bind(this), this.localDescription = {
                    contents: []
                }, this.remoteDescription = {
                    contents: []
                }, this.config = {
                    debug: !1,
                    sid: "",
                    isInitiator: !0,
                    sdpSessionID: Date.now(),
                    useJingle: !1
                }, this.iceCredentials = {
                    local: {},
                    remote: {}
                };
                for (n in e) this.config[n] = e[n];
                this.config.debug && this.on("*", function() {
                    (e.logger || console).log("PeerConnection event:", arguments)
                }), this.hadLocalStunCandidate = !1, this.hadRemoteStunCandidate = !1, this.hadLocalRelayCandidate = !1, this.hadRemoteRelayCandidate = !1, this.hadLocalIPv6Candidate = !1, this.hadRemoteIPv6Candidate = !1, this._remoteDataChannels = [], this._localDataChannels = [], this._candidateBuffer = []
            }
            var i = e("util"),
                o = e("sdp-jingle-json"),
                a = e("wildemitter"),
                s = e("lodash.clonedeep");
            i.inherits(r, a), Object.defineProperty(r.prototype, "signalingState", {
                get: function() {
                    return this.pc.signalingState
                }
            }), Object.defineProperty(r.prototype, "iceConnectionState", {
                get: function() {
                    return this.pc.iceConnectionState
                }
            }), r.prototype._role = function() {
                return this.isInitiator ? "initiator" : "responder"
            }, r.prototype.addStream = function(e) {
                this.localStream = e, this.pc.addStream(e)
            }, r.prototype._checkLocalCandidate = function(e) {
                var t = o.toCandidateJSON(e);
                "srflx" == t.type ? this.hadLocalStunCandidate = !0 : "relay" == t.type && (this.hadLocalRelayCandidate = !0), -1 != t.ip.indexOf(":") && (this.hadLocalIPv6Candidate = !0)
            }, r.prototype._checkRemoteCandidate = function(e) {
                var t = o.toCandidateJSON(e);
                "srflx" == t.type ? this.hadRemoteStunCandidate = !0 : "relay" == t.type && (this.hadRemoteRelayCandidate = !0), -1 != t.ip.indexOf(":") && (this.hadRemoteIPv6Candidate = !0)
            }, r.prototype.processIce = function(e, t) {
                t = t || function() {};
                var n = this;
                if ("closed" === this.pc.signalingState) return t();
                if (e.contents || e.jingle && e.jingle.contents) {
                    var r = this.remoteDescription.contents.map(function(e) {
                        return e.name
                    });
                    (e.contents || e.jingle.contents).forEach(function(e) {
                        var i = e.transport || {},
                            a = i.candidates || [],
                            s = r.indexOf(e.name),
                            c = e.name,
                            d = n.remoteDescription.contents.find(function(t) {
                                return t.name === e.name
                            }),
                            u = function() {
                                a.forEach(function(e) {
                                    var t = o.toCandidateSDP(e);
                                    n.pc.addIceCandidate(new RTCIceCandidate({
                                        candidate: t,
                                        sdpMLineIndex: s,
                                        sdpMid: c
                                    }), function() {}, function(e) {
                                        n.emit("error", e)
                                    }), n._checkRemoteCandidate(t)
                                }), t()
                            };
                        if (n.iceCredentials.remote[e.name] && i.ufrag && n.iceCredentials.remote[e.name].ufrag !== i.ufrag)
                            if (d) {
                                d.transport.ufrag = i.ufrag, d.transport.pwd = i.pwd;
                                var p = {
                                    type: "offer",
                                    jingle: n.remoteDescription
                                };
                                p.sdp = o.toSessionSDP(p.jingle, {
                                    sid: n.config.sdpSessionID,
                                    role: n._role(),
                                    direction: "incoming"
                                }), n.pc.setRemoteDescription(new RTCSessionDescription(p), function() {
                                    u()
                                }, function(e) {
                                    n.emit("error", e)
                                })
                            } else n.emit("error", "ice restart failed to find matching content");
                        else u()
                    })
                } else {
                    if (e.candidate && 0 !== e.candidate.candidate.indexOf("a=") && (e.candidate.candidate = "a=" + e.candidate.candidate), this.wtFirefox && null !== this.firefoxcandidatebuffer && this.pc.localDescription && "offer" === this.pc.localDescription.type) return this.firefoxcandidatebuffer.push(e.candidate), t();
                    n.pc.addIceCandidate(new RTCIceCandidate(e.candidate), function() {}, function(e) {
                        n.emit("error", e)
                    }), n._checkRemoteCandidate(e.candidate.candidate), t()
                }
            }, r.prototype.offer = function(e, t) {
                var n = this,
                    r = 2 === arguments.length,
                    i = r && e ? e : {
                        offerToReceiveAudio: 1,
                        offerToReceiveVideo: 1
                    };
                if (t = r ? t : e, t = t || function() {}, "closed" === this.pc.signalingState) return t("Already closed");
                this.pc.createOffer(function(e) {
                    var r = {
                        type: "offer",
                        sdp: e.sdp
                    };
                    n.assumeSetLocalSuccess && (n.emit("offer", r), t(null, r)), n._candidateBuffer = [], n.pc.setLocalDescription(e, function() {
                        var i;
                        n.config.useJingle && ((i = o.toSessionJSON(e.sdp, {
                            role: n._role(),
                            direction: "outgoing"
                        })).sid = n.config.sid, n.localDescription = i, i.contents.forEach(function(e) {
                            var t = e.transport || {};
                            t.ufrag && (n.iceCredentials.local[e.name] = {
                                ufrag: t.ufrag,
                                pwd: t.pwd
                            })
                        }), r.jingle = i), r.sdp.split("\r\n").forEach(function(e) {
                            0 === e.indexOf("a=candidate:") && n._checkLocalCandidate(e)
                        }), n.assumeSetLocalSuccess || (n.emit("offer", r), t(null, r))
                    }, function(e) {
                        n.emit("error", e), t(e)
                    })
                }, function(e) {
                    n.emit("error", e), t(e)
                }, i)
            }, r.prototype.handleOffer = function(e, t) {
                t = t || function() {};
                var n = this;
                if (e.type = "offer", e.jingle) {
                    if (this.enableChromeNativeSimulcast && e.jingle.contents.forEach(function(e) {
                            "video" === e.name && (e.application.googConferenceFlag = !0)
                        }), this.enableMultiStreamHacks && e.jingle.contents.forEach(function(e) {
                            if ("video" === e.name) {
                                var t = e.application.sources || [];
                                0 !== t.length && "3735928559" === t[0].ssrc || (t.unshift({
                                    ssrc: "3735928559",
                                    parameters: [{
                                        key: "cname",
                                        value: "deadbeef"
                                    }, {
                                        key: "msid",
                                        value: "mixyourfecintothis please"
                                    }]
                                }), e.application.sources = t)
                            }
                        }), n.restrictBandwidth > 0 && e.jingle.contents.length >= 2 && "video" === e.jingle.contents[1].name) {
                        var r = e.jingle.contents[1];
                        r.application && r.application.bandwidth && r.application.bandwidth.bandwidth || (e.jingle.contents[1].application.bandwidth = {
                            type: "AS",
                            bandwidth: n.restrictBandwidth.toString()
                        }, e.sdp = o.toSessionSDP(e.jingle, {
                            sid: n.config.sdpSessionID,
                            role: n._role(),
                            direction: "outgoing"
                        }))
                    }
                    e.jingle.contents.forEach(function(e) {
                        var t = e.transport || {};
                        t.ufrag && (n.iceCredentials.remote[e.name] = {
                            ufrag: t.ufrag,
                            pwd: t.pwd
                        })
                    }), e.sdp = o.toSessionSDP(e.jingle, {
                        sid: n.config.sdpSessionID,
                        role: n._role(),
                        direction: "incoming"
                    }), n.remoteDescription = e.jingle
                }
                e.sdp.split("\r\n").forEach(function(e) {
                    0 === e.indexOf("a=candidate:") && n._checkRemoteCandidate(e)
                }), n.pc.setRemoteDescription(new RTCSessionDescription(e), function() {
                    t()
                }, t)
            }, r.prototype.answerAudioOnly = function(e) {
                var t = {
                    mandatory: {
                        OfferToReceiveAudio: !0,
                        OfferToReceiveVideo: !1
                    }
                };
                this._answer(t, e)
            }, r.prototype.answerBroadcastOnly = function(e) {
                var t = {
                    mandatory: {
                        OfferToReceiveAudio: !1,
                        OfferToReceiveVideo: !1
                    }
                };
                this._answer(t, e)
            }, r.prototype.answer = function(e, t) {
                var n = 2 === arguments.length,
                    r = n ? t : e,
                    i = n && e ? e : {
                        mandatory: {
                            OfferToReceiveAudio: !0,
                            OfferToReceiveVideo: !0
                        }
                    };
                this._answer(i, r)
            }, r.prototype.handleAnswer = function(e, t) {
                t = t || function() {};
                var n = this;
                e.jingle && (e.sdp = o.toSessionSDP(e.jingle, {
                    sid: n.config.sdpSessionID,
                    role: n._role(),
                    direction: "incoming"
                }), n.remoteDescription = e.jingle, e.jingle.contents.forEach(function(e) {
                    var t = e.transport || {};
                    t.ufrag && (n.iceCredentials.remote[e.name] = {
                        ufrag: t.ufrag,
                        pwd: t.pwd
                    })
                })), e.sdp.split("\r\n").forEach(function(e) {
                    0 === e.indexOf("a=candidate:") && n._checkRemoteCandidate(e)
                }), n.pc.setRemoteDescription(new RTCSessionDescription(e), function() {
                    n.wtFirefox && window.setTimeout(function() {
                        n.firefoxcandidatebuffer.forEach(function(e) {
                            n.pc.addIceCandidate(new RTCIceCandidate(e), function() {}, function(e) {
                                n.emit("error", e)
                            }), n._checkRemoteCandidate(e.candidate)
                        }), n.firefoxcandidatebuffer = null
                    }, n.wtFirefox), t(null)
                }, t)
            }, r.prototype.close = function() {
                this.pc.close(), this._localDataChannels = [], this._remoteDataChannels = [], this.emit("close")
            }, r.prototype._answer = function(e, t) {
                t = t || function() {};
                var n = this;
                if (!this.pc.remoteDescription) throw new Error("remoteDescription not set");
                if ("closed" === this.pc.signalingState) return t("Already closed");
                n.pc.createAnswer(function(e) {
                    var r = [];
                    if (n.enableChromeNativeSimulcast && (e.jingle = o.toSessionJSON(e.sdp, {
                            role: n._role(),
                            direction: "outgoing"
                        }), e.jingle.contents.length >= 2 && "video" === e.jingle.contents[1].name)) {
                        var i = e.jingle.contents[1].application.sourceGroups || [],
                            a = !1;
                        if (i.forEach(function(e) {
                                "SIM" == e.semantics && (a = !0)
                            }), !a && e.jingle.contents[1].application.sources.length) {
                            var c = JSON.parse(JSON.stringify(e.jingle.contents[1].application.sources[0]));
                            c.ssrc = "" + Math.floor(4294967295 * Math.random()), e.jingle.contents[1].application.sources.push(c), r.push(e.jingle.contents[1].application.sources[0].ssrc), r.push(c.ssrc), i.push({
                                semantics: "SIM",
                                sources: r
                            });
                            var d = JSON.parse(JSON.stringify(c));
                            d.ssrc = "" + Math.floor(4294967295 * Math.random()), e.jingle.contents[1].application.sources.push(d), i.push({
                                semantics: "FID",
                                sources: [c.ssrc, d.ssrc]
                            }), e.jingle.contents[1].application.sourceGroups = i, e.sdp = o.toSessionSDP(e.jingle, {
                                sid: n.config.sdpSessionID,
                                role: n._role(),
                                direction: "outgoing"
                            })
                        }
                    }
                    var u = {
                        type: "answer",
                        sdp: e.sdp
                    };
                    if (n.assumeSetLocalSuccess) {
                        var p = s(u);
                        n.emit("answer", p), t(null, p)
                    }
                    n._candidateBuffer = [], n.pc.setLocalDescription(e, function() {
                        if (n.config.useJingle) {
                            var r = o.toSessionJSON(e.sdp, {
                                role: n._role(),
                                direction: "outgoing"
                            });
                            r.sid = n.config.sid, n.localDescription = r, u.jingle = r
                        }
                        if (n.enableChromeNativeSimulcast && (u.jingle || (u.jingle = o.toSessionJSON(e.sdp, {
                                role: n._role(),
                                direction: "outgoing"
                            })), u.jingle.contents[1].application.sources.forEach(function(e, t) {
                                e.parameters = e.parameters.map(function(e) {
                                    return "msid" === e.key && (e.value += "-" + Math.floor(t / 2)), e
                                })
                            }), u.sdp = o.toSessionSDP(u.jingle, {
                                sid: n.sdpSessionID,
                                role: n._role(),
                                direction: "outgoing"
                            })), u.sdp.split("\r\n").forEach(function(e) {
                                0 === e.indexOf("a=candidate:") && n._checkLocalCandidate(e)
                            }), !n.assumeSetLocalSuccess) {
                            var i = s(u);
                            n.emit("answer", i), t(null, i)
                        }
                    }, function(e) {
                        n.emit("error", e), t(e)
                    })
                }, function(e) {
                    n.emit("error", e), t(e)
                }, e)
            }, r.prototype._onIce = function(e) {
                var t = this;
                if (e.candidate) {
                    if (this.dontSignalCandidates) return;
                    var n = e.candidate,
                        r = {
                            candidate: {
                                candidate: n.candidate,
                                sdpMid: n.sdpMid,
                                sdpMLineIndex: n.sdpMLineIndex
                            }
                        };
                    this._checkLocalCandidate(n.candidate);
                    var i, a, s = o.toCandidateJSON(n.candidate);
                    if (this.eliminateDuplicateCandidates && "relay" === s.type && (i = this._candidateBuffer.filter(function(e) {
                            return "relay" === e.type
                        }).map(function(e) {
                            return e.foundation + ":" + e.component
                        }), (a = i.indexOf(s.foundation + ":" + s.component)) > -1 && s.priority >> 24 >= i[a].priority >> 24)) return;
                    if ("max-bundle" === this.config.bundlePolicy && (i = this._candidateBuffer.filter(function(e) {
                            return s.type === e.type
                        }).map(function(e) {
                            return e.address + ":" + e.port
                        }), (a = i.indexOf(s.address + ":" + s.port)) > -1)) return;
                    if ("require" === this.config.rtcpMuxPolicy && "2" === s.component) return;
                    if (this._candidateBuffer.push(s), t.config.useJingle && (n.sdpMid || (t.pc.remoteDescription && "offer" === t.pc.remoteDescription.type ? n.sdpMid = t.remoteDescription.contents[n.sdpMLineIndex].name : n.sdpMid = t.localDescription.contents[n.sdpMLineIndex].name), t.iceCredentials.local[n.sdpMid] || o.toSessionJSON(t.pc.localDescription.sdp, {
                            role: t._role(),
                            direction: "outgoing"
                        }).contents.forEach(function(e) {
                            var n = e.transport || {};
                            n.ufrag && (t.iceCredentials.local[e.name] = {
                                ufrag: n.ufrag,
                                pwd: n.pwd
                            })
                        }), r.jingle = {
                            contents: [{
                                name: n.sdpMid,
                                creator: t._role(),
                                transport: {
                                    transportType: "iceUdp",
                                    ufrag: t.iceCredentials.local[n.sdpMid].ufrag,
                                    pwd: t.iceCredentials.local[n.sdpMid].pwd,
                                    candidates: [s]
                                }
                            }]
                        }, t.batchIceCandidates > 0)) return 0 === t.batchedIceCandidates.length && window.setTimeout(function() {
                        var e = {};
                        t.batchedIceCandidates.forEach(function(t) {
                            t = t.contents[0], e[t.name] || (e[t.name] = t), e[t.name].transport.candidates.push(t.transport.candidates[0])
                        });
                        var n = {
                            jingle: {
                                contents: []
                            }
                        };
                        Object.keys(e).forEach(function(t) {
                            n.jingle.contents.push(e[t])
                        }), t.batchedIceCandidates = [], t.emit("ice", n)
                    }, t.batchIceCandidates), void t.batchedIceCandidates.push(r.jingle);
                    this.emit("ice", r)
                } else this.emit("endOfCandidates")
            }, r.prototype._onDataChannel = function(e) {
                var t = e.channel;
                this._remoteDataChannels.push(t), this.emit("addChannel", t)
            }, r.prototype.createDataChannel = function(e, t) {
                var n = this.pc.createDataChannel(e, t);
                return this._localDataChannels.push(n), n
            }, r.prototype.getStats = function() {
                if ("function" != typeof arguments[0]) return this.pc.getStats.apply(this.pc, arguments);
                var e = arguments[0];
                this.pc.getStats().then(function(t) {
                    e(null, t)
                }, function(t) {
                    e(t)
                })
            }, t.exports = r
        }, {
            "lodash.clonedeep": 45,
            "sdp-jingle-json": 54,
            util: 73,
            wildemitter: 75
        }],
        54: [function(e, t, n) {
            var r = e("./lib/tosdp"),
                i = e("./lib/tojson");
            n.toIncomingSDPOffer = function(e) {
                return r.toSessionSDP(e, {
                    role: "responder",
                    direction: "incoming"
                })
            }, n.toOutgoingSDPOffer = function(e) {
                return r.toSessionSDP(e, {
                    role: "initiator",
                    direction: "outgoing"
                })
            }, n.toIncomingSDPAnswer = function(e) {
                return r.toSessionSDP(e, {
                    role: "initiator",
                    direction: "incoming"
                })
            }, n.toOutgoingSDPAnswer = function(e) {
                return r.toSessionSDP(e, {
                    role: "responder",
                    direction: "outgoing"
                })
            }, n.toIncomingMediaSDPOffer = function(e) {
                return r.toMediaSDP(e, {
                    role: "responder",
                    direction: "incoming"
                })
            }, n.toOutgoingMediaSDPOffer = function(e) {
                return r.toMediaSDP(e, {
                    role: "initiator",
                    direction: "outgoing"
                })
            }, n.toIncomingMediaSDPAnswer = function(e) {
                return r.toMediaSDP(e, {
                    role: "initiator",
                    direction: "incoming"
                })
            }, n.toOutgoingMediaSDPAnswer = function(e) {
                return r.toMediaSDP(e, {
                    role: "responder",
                    direction: "outgoing"
                })
            }, n.toCandidateSDP = r.toCandidateSDP, n.toMediaSDP = r.toMediaSDP, n.toSessionSDP = r.toSessionSDP, n.toIncomingJSONOffer = function(e, t) {
                return i.toSessionJSON(e, {
                    role: "responder",
                    direction: "incoming",
                    creators: t
                })
            }, n.toOutgoingJSONOffer = function(e, t) {
                return i.toSessionJSON(e, {
                    role: "initiator",
                    direction: "outgoing",
                    creators: t
                })
            }, n.toIncomingJSONAnswer = function(e, t) {
                return i.toSessionJSON(e, {
                    role: "initiator",
                    direction: "incoming",
                    creators: t
                })
            }, n.toOutgoingJSONAnswer = function(e, t) {
                return i.toSessionJSON(e, {
                    role: "responder",
                    direction: "outgoing",
                    creators: t
                })
            }, n.toIncomingMediaJSONOffer = function(e, t) {
                return i.toMediaJSON(e, {
                    role: "responder",
                    direction: "incoming",
                    creator: t
                })
            }, n.toOutgoingMediaJSONOffer = function(e, t) {
                return i.toMediaJSON(e, {
                    role: "initiator",
                    direction: "outgoing",
                    creator: t
                })
            }, n.toIncomingMediaJSONAnswer = function(e, t) {
                return i.toMediaJSON(e, {
                    role: "initiator",
                    direction: "incoming",
                    creator: t
                })
            }, n.toOutgoingMediaJSONAnswer = function(e, t) {
                return i.toMediaJSON(e, {
                    role: "responder",
                    direction: "outgoing",
                    creator: t
                })
            }, n.toCandidateJSON = i.toCandidateJSON, n.toMediaJSON = i.toMediaJSON, n.toSessionJSON = i.toSessionJSON
        }, {
            "./lib/tojson": 57,
            "./lib/tosdp": 58
        }],
        55: [function(e, t, n) {
            n.lines = function(e) {
                return e.split("\r\n").filter(function(e) {
                    return e.length > 0
                })
            }, n.findLine = function(e, t, n) {
                for (var r = e.length, i = 0; i < t.length; i++)
                    if (t[i].substr(0, r) === e) return t[i];
                if (!n) return !1;
                for (var o = 0; o < n.length; o++)
                    if (n[o].substr(0, r) === e) return n[o];
                return !1
            }, n.findLines = function(e, t, n) {
                for (var r = [], i = e.length, o = 0; o < t.length; o++) t[o].substr(0, i) === e && r.push(t[o]);
                if (r.length || !n) return r;
                for (var a = 0; a < n.length; a++) n[a].substr(0, i) === e && r.push(n[a]);
                return r
            }, n.mline = function(e) {
                for (var t = e.substr(2).split(" "), n = {
                        media: t[0],
                        port: t[1],
                        proto: t[2],
                        formats: []
                    }, r = 3; r < t.length; r++) t[r] && n.formats.push(t[r]);
                return n
            }, n.rtpmap = function(e) {
                var t = e.substr(9).split(" "),
                    n = {
                        id: t.shift()
                    };
                return t = t[0].split("/"), n.name = t[0], n.clockrate = t[1], n.channels = 3 == t.length ? t[2] : "1", n
            }, n.sctpmap = function(e) {
                var t = e.substr(10).split(" ");
                return {
                    number: t.shift(),
                    protocol: t.shift(),
                    streams: t.shift()
                }
            }, n.fmtp = function(e) {
                for (var t, n, r, i = e.substr(e.indexOf(" ") + 1).split(";"), o = [], a = 0; a < i.length; a++) n = (t = i[a].split("="))[0].trim(), r = t[1], n && r ? o.push({
                    key: n,
                    value: r
                }) : n && o.push({
                    key: "",
                    value: n
                });
                return o
            }, n.crypto = function(e) {
                var t = e.substr(9).split(" ");
                return {
                    tag: t[0],
                    cipherSuite: t[1],
                    keyParams: t[2],
                    sessionParams: t.slice(3).join(" ")
                }
            }, n.fingerprint = function(e) {
                var t = e.substr(14).split(" ");
                return {
                    hash: t[0],
                    value: t[1]
                }
            }, n.extmap = function(e) {
                var t = e.substr(9).split(" "),
                    n = {},
                    r = t.shift(),
                    i = r.indexOf("/");
                return i >= 0 ? (n.id = r.substr(0, i), n.senders = r.substr(i + 1)) : (n.id = r, n.senders = "sendrecv"), n.uri = t.shift() || "", n
            }, n.rtcpfb = function(e) {
                var t = e.substr(10).split(" "),
                    n = {};
                return n.id = t.shift(), n.type = t.shift(), "trr-int" === n.type ? n.value = t.shift() : n.subtype = t.shift() || "", n.parameters = t, n
            }, n.candidate = function(e) {
                for (var t, n = {
                        foundation: (t = 0 === e.indexOf("a=candidate:") ? e.substring(12).split(" ") : e.substring(10).split(" "))[0],
                        component: t[1],
                        protocol: t[2].toLowerCase(),
                        priority: t[3],
                        ip: t[4],
                        port: t[5],
                        type: t[7],
                        generation: "0"
                    }, r = 8; r < t.length; r += 2) "raddr" === t[r] ? n.relAddr = t[r + 1] : "rport" === t[r] ? n.relPort = t[r + 1] : "generation" === t[r] ? n.generation = t[r + 1] : "tcptype" === t[r] && (n.tcpType = t[r + 1]);
                return n.network = "1", n
            }, n.sourceGroups = function(e) {
                for (var t = [], n = 0; n < e.length; n++) {
                    var r = e[n].substr(13).split(" ");
                    t.push({
                        semantics: r.shift(),
                        sources: r
                    })
                }
                return t
            }, n.sources = function(e) {
                for (var t = [], n = {}, r = 0; r < e.length; r++) {
                    var i = e[r].substr(7).split(" "),
                        o = i.shift();
                    if (!n[o]) {
                        var a = {
                            ssrc: o,
                            parameters: []
                        };
                        t.push(a), n[o] = a
                    }
                    var s = (i = i.join(" ").split(":")).shift(),
                        c = i.join(":") || null;
                    n[o].parameters.push({
                        key: s,
                        value: c
                    })
                }
                return t
            }, n.groups = function(e) {
                for (var t, n = [], r = 0; r < e.length; r++) t = e[r].substr(8).split(" "), n.push({
                    semantics: t.shift(),
                    contents: t
                });
                return n
            }, n.bandwidth = function(e) {
                var t = e.substr(2).split(":"),
                    n = {};
                return n.type = t.shift(), n.bandwidth = t.shift(), n
            }, n.msid = function(e) {
                var t = e.substr(7),
                    n = t.split(" ");
                return {
                    msid: t,
                    mslabel: n[0],
                    label: n[1]
                }
            }
        }, {}],
        56: [function(e, t, n) {
            t.exports = {
                initiator: {
                    incoming: {
                        initiator: "recvonly",
                        responder: "sendonly",
                        both: "sendrecv",
                        none: "inactive",
                        recvonly: "initiator",
                        sendonly: "responder",
                        sendrecv: "both",
                        inactive: "none"
                    },
                    outgoing: {
                        initiator: "sendonly",
                        responder: "recvonly",
                        both: "sendrecv",
                        none: "inactive",
                        recvonly: "responder",
                        sendonly: "initiator",
                        sendrecv: "both",
                        inactive: "none"
                    }
                },
                responder: {
                    incoming: {
                        initiator: "sendonly",
                        responder: "recvonly",
                        both: "sendrecv",
                        none: "inactive",
                        recvonly: "responder",
                        sendonly: "initiator",
                        sendrecv: "both",
                        inactive: "none"
                    },
                    outgoing: {
                        initiator: "recvonly",
                        responder: "sendonly",
                        both: "sendrecv",
                        none: "inactive",
                        recvonly: "initiator",
                        sendonly: "responder",
                        sendrecv: "both",
                        inactive: "none"
                    }
                }
            }
        }, {}],
        57: [function(e, t, n) {
            var r = e("./senders"),
                i = e("./parsers"),
                o = Math.random();
            n._setIdCounter = function(e) {
                o = e
            }, n.toSessionJSON = function(e, t) {
                var r, o = t.creators || [],
                    a = t.role || "initiator",
                    s = t.direction || "outgoing",
                    c = e.split("\r\nm=");
                for (r = 1; r < c.length; r++) c[r] = "m=" + c[r], r !== c.length - 1 && (c[r] += "\r\n");
                var d = c.shift() + "\r\n",
                    u = i.lines(d),
                    p = {},
                    f = [];
                for (r = 0; r < c.length; r++) f.push(n.toMediaJSON(c[r], d, {
                    role: a,
                    direction: s,
                    creator: o[r] || "initiator"
                }));
                p.contents = f;
                var l = i.findLines("a=group:", u);
                return l.length && (p.groups = i.groups(l)), p
            }, n.toMediaJSON = function(e, t, o) {
                var a = o.creator || "initiator",
                    s = o.role || "initiator",
                    c = o.direction || "outgoing",
                    d = i.lines(e),
                    u = i.lines(t),
                    p = i.mline(d[0]),
                    f = {
                        creator: a,
                        name: p.media,
                        application: {
                            applicationType: "rtp",
                            media: p.media,
                            payloads: [],
                            encryption: [],
                            feedback: [],
                            headerExtensions: []
                        },
                        transport: {
                            transportType: "iceUdp",
                            candidates: [],
                            fingerprints: []
                        }
                    };
                "application" == p.media && (f.application = {
                    applicationType: "datachannel"
                }, f.transport.sctp = []);
                var l = f.application,
                    h = f.transport,
                    m = i.findLine("a=mid:", d);
                if (m && (f.name = m.substr(6)), i.findLine("a=sendrecv", d, u) ? f.senders = "both" : i.findLine("a=sendonly", d, u) ? f.senders = r[s][c].sendonly : i.findLine("a=recvonly", d, u) ? f.senders = r[s][c].recvonly : i.findLine("a=inactive", d, u) && (f.senders = "none"), "rtp" == l.applicationType) {
                    var g = i.findLine("b=", d);
                    g && (l.bandwidth = i.bandwidth(g));
                    var v = i.findLine("a=ssrc:", d);
                    v && (l.ssrc = v.substr(7).split(" ")[0]), i.findLines("a=rtpmap:", d).forEach(function(e) {
                        var t = i.rtpmap(e);
                        t.parameters = [], t.feedback = [], i.findLines("a=fmtp:" + t.id, d).forEach(function(e) {
                            t.parameters = i.fmtp(e)
                        }), i.findLines("a=rtcp-fb:" + t.id, d).forEach(function(e) {
                            t.feedback.push(i.rtcpfb(e))
                        }), l.payloads.push(t)
                    }), i.findLines("a=crypto:", d, u).forEach(function(e) {
                        l.encryption.push(i.crypto(e))
                    }), i.findLine("a=rtcp-mux", d) && (l.mux = !0), i.findLines("a=rtcp-fb:*", d).forEach(function(e) {
                        l.feedback.push(i.rtcpfb(e))
                    }), i.findLines("a=extmap:", d).forEach(function(e) {
                        var t = i.extmap(e);
                        t.senders = r[s][c][t.senders], l.headerExtensions.push(t)
                    });
                    var y = i.findLines("a=ssrc-group:", d);
                    l.sourceGroups = i.sourceGroups(y || []);
                    var b = i.findLines("a=ssrc:", d),
                        w = l.sources = i.sources(b || []),
                        C = i.findLine("a=msid:", d);
                    if (C) {
                        var S = i.msid(C);
                        ["msid", "mslabel", "label"].forEach(function(e) {
                            for (var t = 0; t < w.length; t++) {
                                for (var n = !1, r = 0; r < w[t].parameters.length; r++) w[t].parameters[r].key === e && (n = !0);
                                n || w[t].parameters.push({
                                    key: e,
                                    value: S[e]
                                })
                            }
                        })
                    }
                    i.findLine("a=x-google-flag:conference", d, u) && (l.googConferenceFlag = !0)
                }
                var k = i.findLines("a=fingerprint:", d, u),
                    T = i.findLine("a=setup:", d, u);
                k.forEach(function(e) {
                    var t = i.fingerprint(e);
                    T && (t.setup = T.substr(8)), h.fingerprints.push(t)
                });
                var E = i.findLine("a=ice-ufrag:", d, u),
                    P = i.findLine("a=ice-pwd:", d, u);
                return E && P && (h.ufrag = E.substr(12), h.pwd = P.substr(10), h.candidates = [], i.findLines("a=candidate:", d, u).forEach(function(e) {
                    h.candidates.push(n.toCandidateJSON(e))
                })), "datachannel" == l.applicationType && i.findLines("a=sctpmap:", d).forEach(function(e) {
                    var t = i.sctpmap(e);
                    h.sctp.push(t)
                }), f
            }, n.toCandidateJSON = function(e) {
                var t = i.candidate(e.split("\r\n")[0]);
                return t.id = (o++).toString(36).substr(0, 12), t
            }
        }, {
            "./parsers": 55,
            "./senders": 56
        }],
        58: [function(e, t, n) {
            var r = e("./senders");
            n.toSessionSDP = function(e, t) {
                t.role, t.direction;
                var r = ["v=0", "o=- " + (t.sid || e.sid || Date.now()) + " " + (t.time || Date.now()) + " IN IP4 0.0.0.0", "s=-", "t=0 0"],
                    i = e.contents || [],
                    o = !1;
                return i.forEach(function(e) {
                    e.application.sources && e.application.sources.length && (o = !0)
                }), o && r.push("a=msid-semantic: WMS *"), (e.groups || []).forEach(function(e) {
                    r.push("a=group:" + e.semantics + " " + e.contents.join(" "))
                }), i.forEach(function(e) {
                    r.push(n.toMediaSDP(e, t))
                }), r.join("\r\n") + "\r\n"
            }, n.toMediaSDP = function(e, t) {
                var i = [],
                    o = t.role || "initiator",
                    a = t.direction || "outgoing",
                    s = e.application,
                    c = e.transport,
                    d = s.payloads || [],
                    u = c && c.fingerprints || [],
                    p = [];
                if ("datachannel" == s.applicationType ? (p.push("application"), p.push("1"), p.push("DTLS/SCTP"), c.sctp && c.sctp.forEach(function(e) {
                        p.push(e.number)
                    })) : (p.push(s.media), p.push("1"), u.length > 0 ? p.push("UDP/TLS/RTP/SAVPF") : s.encryption && s.encryption.length > 0 ? p.push("RTP/SAVPF") : p.push("RTP/AVPF"), d.forEach(function(e) {
                        p.push(e.id)
                    })), i.push("m=" + p.join(" ")), i.push("c=IN IP4 0.0.0.0"), s.bandwidth && s.bandwidth.type && s.bandwidth.bandwidth && i.push("b=" + s.bandwidth.type + ":" + s.bandwidth.bandwidth), "rtp" == s.applicationType && i.push("a=rtcp:1 IN IP4 0.0.0.0"), c) {
                    c.ufrag && i.push("a=ice-ufrag:" + c.ufrag), c.pwd && i.push("a=ice-pwd:" + c.pwd);
                    u.forEach(function(e) {
                        i.push("a=fingerprint:" + e.hash + " " + e.value), e.setup && i.push("a=setup:" + e.setup)
                    }), c.sctp && c.sctp.forEach(function(e) {
                        i.push("a=sctpmap:" + e.number + " " + e.protocol + " " + e.streams)
                    })
                }
                return "rtp" == s.applicationType && i.push("a=" + (r[o][a][e.senders] || "sendrecv")), i.push("a=mid:" + e.name), s.sources && s.sources.length && (s.sources[0].parameters || []).forEach(function(e) {
                    "msid" === e.key && i.push("a=msid:" + e.value)
                }), s.mux && i.push("a=rtcp-mux"), (s.encryption || []).forEach(function(e) {
                    i.push("a=crypto:" + e.tag + " " + e.cipherSuite + " " + e.keyParams + (e.sessionParams ? " " + e.sessionParams : ""))
                }), s.googConferenceFlag && i.push("a=x-google-flag:conference"), d.forEach(function(e) {
                    var t = "a=rtpmap:" + e.id + " " + e.name + "/" + e.clockrate;
                    if (e.channels && "1" != e.channels && (t += "/" + e.channels), i.push(t), e.parameters && e.parameters.length) {
                        var n = ["a=fmtp:" + e.id],
                            r = [];
                        e.parameters.forEach(function(e) {
                            r.push((e.key ? e.key + "=" : "") + e.value)
                        }), n.push(r.join(";")), i.push(n.join(" "))
                    }
                    e.feedback && e.feedback.forEach(function(t) {
                        "trr-int" === t.type ? i.push("a=rtcp-fb:" + e.id + " trr-int " + (t.value ? t.value : "0")) : i.push("a=rtcp-fb:" + e.id + " " + t.type + (t.subtype ? " " + t.subtype : ""))
                    })
                }), s.feedback && s.feedback.forEach(function(e) {
                    "trr-int" === e.type ? i.push("a=rtcp-fb:* trr-int " + (e.value ? e.value : "0")) : i.push("a=rtcp-fb:* " + e.type + (e.subtype ? " " + e.subtype : ""))
                }), (s.headerExtensions || []).forEach(function(e) {
                    i.push("a=extmap:" + e.id + (e.senders ? "/" + r[o][a][e.senders] : "") + " " + e.uri)
                }), (s.sourceGroups || []).forEach(function(e) {
                    i.push("a=ssrc-group:" + e.semantics + " " + e.sources.join(" "))
                }), (s.sources || []).forEach(function(e) {
                    for (var t = 0; t < e.parameters.length; t++) {
                        var n = e.parameters[t];
                        i.push("a=ssrc:" + (e.ssrc || s.ssrc) + " " + n.key + (n.value ? ":" + n.value : ""))
                    }
                }), (c.candidates || []).forEach(function(e) {
                    i.push(n.toCandidateSDP(e))
                }), i.join("\r\n")
            }, n.toCandidateSDP = function(e) {
                var t = [];
                t.push(e.foundation), t.push(e.component), t.push(e.protocol.toUpperCase()), t.push(e.priority), t.push(e.ip), t.push(e.port);
                var n = e.type;
                return t.push("typ"), t.push(n), "srflx" !== n && "prflx" !== n && "relay" !== n || e.relAddr && e.relPort && (t.push("raddr"), t.push(e.relAddr), t.push("rport"), t.push(e.relPort)), e.tcpType && "TCP" == e.protocol.toUpperCase() && (t.push("tcptype"), t.push(e.tcpType)), t.push("generation"), t.push(e.generation || "0"), "a=candidate:" + t.join(" ")
            }
        }, {
            "./senders": 56
        }],
        59: [function(e, t, n) {
            "use strict";
            var r = {};
            r.generateIdentifier = function() {
                return Math.random().toString(36).substr(2, 10)
            }, r.localCName = r.generateIdentifier(), r.splitLines = function(e) {
                return e.trim().split("\n").map(function(e) {
                    return e.trim()
                })
            }, r.splitSections = function(e) {
                return e.split("\nm=").map(function(e, t) {
                    return (t > 0 ? "m=" + e : e).trim() + "\r\n"
                })
            }, r.matchPrefix = function(e, t) {
                return r.splitLines(e).filter(function(e) {
                    return 0 === e.indexOf(t)
                })
            }, r.parseCandidate = function(e) {
                for (var t, n = {
                        foundation: (t = 0 === e.indexOf("a=candidate:") ? e.substring(12).split(" ") : e.substring(10).split(" "))[0],
                        component: t[1],
                        protocol: t[2].toLowerCase(),
                        priority: parseInt(t[3], 10),
                        ip: t[4],
                        port: parseInt(t[5], 10),
                        type: t[7]
                    }, r = 8; r < t.length; r += 2) switch (t[r]) {
                    case "raddr":
                        n.relatedAddress = t[r + 1];
                        break;
                    case "rport":
                        n.relatedPort = parseInt(t[r + 1], 10);
                        break;
                    case "tcptype":
                        n.tcpType = t[r + 1];
                        break;
                    default:
                        n[t[r]] = t[r + 1]
                }
                return n
            }, r.writeCandidate = function(e) {
                var t = [];
                t.push(e.foundation), t.push(e.component), t.push(e.protocol.toUpperCase()), t.push(e.priority), t.push(e.ip), t.push(e.port);
                var n = e.type;
                return t.push("typ"), t.push(n), "host" !== n && e.relatedAddress && e.relatedPort && (t.push("raddr"), t.push(e.relatedAddress), t.push("rport"), t.push(e.relatedPort)), e.tcpType && "tcp" === e.protocol.toLowerCase() && (t.push("tcptype"), t.push(e.tcpType)), "candidate:" + t.join(" ")
            }, r.parseIceOptions = function(e) {
                return e.substr(14).split(" ")
            }, r.parseRtpMap = function(e) {
                var t = e.substr(9).split(" "),
                    n = {
                        payloadType: parseInt(t.shift(), 10)
                    };
                return t = t[0].split("/"), n.name = t[0], n.clockRate = parseInt(t[1], 10), n.numChannels = 3 === t.length ? parseInt(t[2], 10) : 1, n
            }, r.writeRtpMap = function(e) {
                var t = e.payloadType;
                return void 0 !== e.preferredPayloadType && (t = e.preferredPayloadType), "a=rtpmap:" + t + " " + e.name + "/" + e.clockRate + (1 !== e.numChannels ? "/" + e.numChannels : "") + "\r\n"
            }, r.parseExtmap = function(e) {
                var t = e.substr(9).split(" ");
                return {
                    id: parseInt(t[0], 10),
                    direction: t[0].indexOf("/") > 0 ? t[0].split("/")[1] : "sendrecv",
                    uri: t[1]
                }
            }, r.writeExtmap = function(e) {
                return "a=extmap:" + (e.id || e.preferredId) + (e.direction && "sendrecv" !== e.direction ? "/" + e.direction : "") + " " + e.uri + "\r\n"
            }, r.parseFmtp = function(e) {
                for (var t, n = {}, r = e.substr(e.indexOf(" ") + 1).split(";"), i = 0; i < r.length; i++) n[(t = r[i].trim().split("="))[0].trim()] = t[1];
                return n
            }, r.writeFmtp = function(e) {
                var t = "",
                    n = e.payloadType;
                if (void 0 !== e.preferredPayloadType && (n = e.preferredPayloadType), e.parameters && Object.keys(e.parameters).length) {
                    var r = [];
                    Object.keys(e.parameters).forEach(function(t) {
                        r.push(t + "=" + e.parameters[t])
                    }), t += "a=fmtp:" + n + " " + r.join(";") + "\r\n"
                }
                return t
            }, r.parseRtcpFb = function(e) {
                var t = e.substr(e.indexOf(" ") + 1).split(" ");
                return {
                    type: t.shift(),
                    parameter: t.join(" ")
                }
            }, r.writeRtcpFb = function(e) {
                var t = "",
                    n = e.payloadType;
                return void 0 !== e.preferredPayloadType && (n = e.preferredPayloadType), e.rtcpFeedback && e.rtcpFeedback.length && e.rtcpFeedback.forEach(function(e) {
                    t += "a=rtcp-fb:" + n + " " + e.type + (e.parameter && e.parameter.length ? " " + e.parameter : "") + "\r\n"
                }), t
            }, r.parseSsrcMedia = function(e) {
                var t = e.indexOf(" "),
                    n = {
                        ssrc: parseInt(e.substr(7, t - 7), 10)
                    },
                    r = e.indexOf(":", t);
                return r > -1 ? (n.attribute = e.substr(t + 1, r - t - 1), n.value = e.substr(r + 1)) : n.attribute = e.substr(t + 1), n
            }, r.getMid = function(e) {
                var t = r.matchPrefix(e, "a=mid:")[0];
                if (t) return t.substr(6)
            }, r.parseFingerprint = function(e) {
                var t = e.substr(14).split(" ");
                return {
                    algorithm: t[0].toLowerCase(),
                    value: t[1]
                }
            }, r.getDtlsParameters = function(e, t) {
                return {
                    role: "auto",
                    fingerprints: r.matchPrefix(e + t, "a=fingerprint:").map(r.parseFingerprint)
                }
            }, r.writeDtlsParameters = function(e, t) {
                var n = "a=setup:" + t + "\r\n";
                return e.fingerprints.forEach(function(e) {
                    n += "a=fingerprint:" + e.algorithm + " " + e.value + "\r\n"
                }), n
            }, r.getIceParameters = function(e, t) {
                var n = r.splitLines(e);
                return {
                    usernameFragment: (n = n.concat(r.splitLines(t))).filter(function(e) {
                        return 0 === e.indexOf("a=ice-ufrag:")
                    })[0].substr(12),
                    password: n.filter(function(e) {
                        return 0 === e.indexOf("a=ice-pwd:")
                    })[0].substr(10)
                }
            }, r.writeIceParameters = function(e) {
                return "a=ice-ufrag:" + e.usernameFragment + "\r\na=ice-pwd:" + e.password + "\r\n"
            }, r.parseRtpParameters = function(e) {
                for (var t = {
                        codecs: [],
                        headerExtensions: [],
                        fecMechanisms: [],
                        rtcp: []
                    }, n = r.splitLines(e)[0].split(" "), i = 3; i < n.length; i++) {
                    var o = n[i],
                        a = r.matchPrefix(e, "a=rtpmap:" + o + " ")[0];
                    if (a) {
                        var s = r.parseRtpMap(a),
                            c = r.matchPrefix(e, "a=fmtp:" + o + " ");
                        switch (s.parameters = c.length ? r.parseFmtp(c[0]) : {}, s.rtcpFeedback = r.matchPrefix(e, "a=rtcp-fb:" + o + " ").map(r.parseRtcpFb), t.codecs.push(s), s.name.toUpperCase()) {
                            case "RED":
                            case "ULPFEC":
                                t.fecMechanisms.push(s.name.toUpperCase())
                        }
                    }
                }
                return r.matchPrefix(e, "a=extmap:").forEach(function(e) {
                    t.headerExtensions.push(r.parseExtmap(e))
                }), t
            }, r.writeRtpDescription = function(e, t) {
                var n = "";
                n += "m=" + e + " ", n += t.codecs.length > 0 ? "9" : "0", n += " UDP/TLS/RTP/SAVPF ", n += t.codecs.map(function(e) {
                    return void 0 !== e.preferredPayloadType ? e.preferredPayloadType : e.payloadType
                }).join(" ") + "\r\n", n += "c=IN IP4 0.0.0.0\r\n", n += "a=rtcp:9 IN IP4 0.0.0.0\r\n", t.codecs.forEach(function(e) {
                    n += r.writeRtpMap(e), n += r.writeFmtp(e), n += r.writeRtcpFb(e)
                });
                var i = 0;
                return t.codecs.forEach(function(e) {
                    e.maxptime > i && (i = e.maxptime)
                }), i > 0 && (n += "a=maxptime:" + i + "\r\n"), n += "a=rtcp-mux\r\n", t.headerExtensions.forEach(function(e) {
                    n += r.writeExtmap(e)
                }), n
            }, r.parseRtpEncodingParameters = function(e) {
                var t, n = [],
                    i = r.parseRtpParameters(e),
                    o = -1 !== i.fecMechanisms.indexOf("RED"),
                    a = -1 !== i.fecMechanisms.indexOf("ULPFEC"),
                    s = r.matchPrefix(e, "a=ssrc:").map(function(e) {
                        return r.parseSsrcMedia(e)
                    }).filter(function(e) {
                        return "cname" === e.attribute
                    }),
                    c = s.length > 0 && s[0].ssrc,
                    d = r.matchPrefix(e, "a=ssrc-group:FID").map(function(e) {
                        var t = e.split(" ");
                        return t.shift(), t.map(function(e) {
                            return parseInt(e, 10)
                        })
                    });
                d.length > 0 && d[0].length > 1 && d[0][0] === c && (t = d[0][1]), i.codecs.forEach(function(e) {
                    if ("RTX" === e.name.toUpperCase() && e.parameters.apt) {
                        var r = {
                            ssrc: c,
                            codecPayloadType: parseInt(e.parameters.apt, 10),
                            rtx: {
                                ssrc: t
                            }
                        };
                        n.push(r), o && ((r = JSON.parse(JSON.stringify(r))).fec = {
                            ssrc: t,
                            mechanism: a ? "red+ulpfec" : "red"
                        }, n.push(r))
                    }
                }), 0 === n.length && c && n.push({
                    ssrc: c
                });
                var u = r.matchPrefix(e, "b=");
                return u.length && (0 === u[0].indexOf("b=TIAS:") ? u = parseInt(u[0].substr(7), 10) : 0 === u[0].indexOf("b=AS:") && (u = parseInt(u[0].substr(5), 10)), n.forEach(function(e) {
                    e.maxBitrate = u
                })), n
            }, r.parseRtcpParameters = function(e) {
                var t = {},
                    n = r.matchPrefix(e, "a=ssrc:").map(function(e) {
                        return r.parseSsrcMedia(e)
                    }).filter(function(e) {
                        return "cname" === e.attribute
                    })[0];
                n && (t.cname = n.value, t.ssrc = n.ssrc);
                var i = r.matchPrefix(e, "a=rtcp-rsize");
                t.reducedSize = i.length > 0, t.compound = 0 === i.length;
                var o = r.matchPrefix(e, "a=rtcp-mux");
                return t.mux = o.length > 0, t
            }, r.parseMsid = function(e) {
                var t, n = r.matchPrefix(e, "a=msid:");
                if (1 === n.length) return t = n[0].substr(7).split(" "), {
                    stream: t[0],
                    track: t[1]
                };
                var i = r.matchPrefix(e, "a=ssrc:").map(function(e) {
                    return r.parseSsrcMedia(e)
                }).filter(function(e) {
                    return "msid" === e.attribute
                });
                return i.length > 0 ? (t = i[0].value.split(" "), {
                    stream: t[0],
                    track: t[1]
                }) : void 0
            }, r.writeSessionBoilerplate = function() {
                return "v=0\r\no=thisisadapterortc 8169639915646943137 2 IN IP4 127.0.0.1\r\ns=-\r\nt=0 0\r\n"
            }, r.writeMediaSection = function(e, t, n, i) {
                var o = r.writeRtpDescription(e.kind, t);
                if (o += r.writeIceParameters(e.iceGatherer.getLocalParameters()), o += r.writeDtlsParameters(e.dtlsTransport.getLocalParameters(), "offer" === n ? "actpass" : "active"), o += "a=mid:" + e.mid + "\r\n", e.direction ? o += "a=" + e.direction + "\r\n" : e.rtpSender && e.rtpReceiver ? o += "a=sendrecv\r\n" : e.rtpSender ? o += "a=sendonly\r\n" : e.rtpReceiver ? o += "a=recvonly\r\n" : o += "a=inactive\r\n", e.rtpSender) {
                    var a = "msid:" + i.id + " " + e.rtpSender.track.id + "\r\n";
                    o += "a=" + a, o += "a=ssrc:" + e.sendEncodingParameters[0].ssrc + " " + a, e.sendEncodingParameters[0].rtx && (o += "a=ssrc:" + e.sendEncodingParameters[0].rtx.ssrc + " " + a, o += "a=ssrc-group:FID " + e.sendEncodingParameters[0].ssrc + " " + e.sendEncodingParameters[0].rtx.ssrc + "\r\n")
                }
                return o += "a=ssrc:" + e.sendEncodingParameters[0].ssrc + " cname:" + r.localCName + "\r\n", e.rtpSender && e.sendEncodingParameters[0].rtx && (o += "a=ssrc:" + e.sendEncodingParameters[0].rtx.ssrc + " cname:" + r.localCName + "\r\n"), o
            }, r.getDirection = function(e, t) {
                for (var n = r.splitLines(e), i = 0; i < n.length; i++) switch (n[i]) {
                    case "a=sendrecv":
                    case "a=sendonly":
                    case "a=recvonly":
                    case "a=inactive":
                        return n[i].substr(2)
                }
                return t ? r.getDirection(t) : "sendrecv"
            }, r.getKind = function(e) {
                return r.splitLines(e)[0].split(" ")[0].substr(2)
            }, r.isRejected = function(e) {
                return "0" === e.split(" ", 2)[1]
            }, t.exports = r
        }, {}],
        60: [function(e, t, n) {
            t.exports = e("./lib/")
        }, {
            "./lib/": 61
        }],
        61: [function(e, t, n) {
            function r(e, t) {
                "object" == typeof e && (t = e, e = void 0), t = t || {};
                var n, r = i(e),
                    o = r.source,
                    d = r.id;
                return t.forceNew || t["force new connection"] || !1 === t.multiplex ? (s("ignoring socket cache for %s", o), n = a(o, t)) : (c[d] || (s("new io instance for %s", o), c[d] = a(o, t)), n = c[d]), n.socket(r.path)
            }
            var i = e("./url"),
                o = e("socket.io-parser"),
                a = e("./manager"),
                s = e("debug")("socket.io-client");
            t.exports = n = r;
            var c = n.managers = {};
            n.protocol = o.protocol, n.connect = r, n.Manager = e("./manager"), n.Socket = e("./socket")
        }, {
            "./manager": 62,
            "./socket": 64,
            "./url": 65,
            debug: 19,
            "socket.io-parser": 67
        }],
        62: [function(e, t, n) {
            function r(e, t) {
                if (!(this instanceof r)) return new r(e, t);
                e && "object" == typeof e && (t = e, e = void 0), (t = t || {}).path = t.path || "/socket.io", this.nsps = {}, this.subs = [], this.opts = t, this.reconnection(!1 !== t.reconnection), this.reconnectionAttempts(t.reconnectionAttempts || 1 / 0), this.reconnectionDelay(t.reconnectionDelay || 1e3), this.reconnectionDelayMax(t.reconnectionDelayMax || 5e3), this.randomizationFactor(t.randomizationFactor || .5), this.backoff = new f({
                    min: this.reconnectionDelay(),
                    max: this.reconnectionDelayMax(),
                    jitter: this.randomizationFactor()
                }), this.timeout(null == t.timeout ? 2e4 : t.timeout), this.readyState = "closed", this.uri = e, this.connected = [], this.encoding = !1, this.packetBuffer = [], this.encoder = new s.Encoder, this.decoder = new s.Decoder, this.autoConnect = !1 !== t.autoConnect, this.autoConnect && this.open()
            }
            e("./url");
            var i = e("engine.io-client"),
                o = e("./socket"),
                a = e("component-emitter"),
                s = e("socket.io-parser"),
                c = e("./on"),
                d = e("component-bind"),
                u = (e("object-component"), e("debug")("socket.io-client:manager")),
                p = e("indexof"),
                f = e("backo2");
            t.exports = r, r.prototype.emitAll = function() {
                this.emit.apply(this, arguments);
                for (var e in this.nsps) this.nsps[e].emit.apply(this.nsps[e], arguments)
            }, r.prototype.updateSocketIds = function() {
                for (var e in this.nsps) this.nsps[e].id = this.engine.id
            }, a(r.prototype), r.prototype.reconnection = function(e) {
                return arguments.length ? (this._reconnection = !!e, this) : this._reconnection
            }, r.prototype.reconnectionAttempts = function(e) {
                return arguments.length ? (this._reconnectionAttempts = e, this) : this._reconnectionAttempts
            }, r.prototype.reconnectionDelay = function(e) {
                return arguments.length ? (this._reconnectionDelay = e, this.backoff && this.backoff.setMin(e), this) : this._reconnectionDelay
            }, r.prototype.randomizationFactor = function(e) {
                return arguments.length ? (this._randomizationFactor = e, this.backoff && this.backoff.setJitter(e), this) : this._randomizationFactor
            }, r.prototype.reconnectionDelayMax = function(e) {
                return arguments.length ? (this._reconnectionDelayMax = e, this.backoff && this.backoff.setMax(e), this) : this._reconnectionDelayMax
            }, r.prototype.timeout = function(e) {
                return arguments.length ? (this._timeout = e, this) : this._timeout
            }, r.prototype.maybeReconnectOnOpen = function() {
                !this.reconnecting && this._reconnection && 0 === this.backoff.attempts && this.reconnect()
            }, r.prototype.open = r.prototype.connect = function(e) {
                if (u("readyState %s", this.readyState), ~this.readyState.indexOf("open")) return this;
                u("opening %s", this.uri), this.engine = i(this.uri, this.opts);
                var t = this.engine,
                    n = this;
                this.readyState = "opening", this.skipReconnect = !1;
                var r = c(t, "open", function() {
                        n.onopen(), e && e()
                    }),
                    o = c(t, "error", function(t) {
                        if (u("connect_error"), n.cleanup(), n.readyState = "closed", n.emitAll("connect_error", t), e) {
                            var r = new Error("Connection error");
                            r.data = t, e(r)
                        } else n.maybeReconnectOnOpen()
                    });
                if (!1 !== this._timeout) {
                    var a = this._timeout;
                    u("connect attempt will timeout after %d", a);
                    var s = setTimeout(function() {
                        u("connect attempt timed out after %d", a), r.destroy(), t.close(), t.emit("error", "timeout"), n.emitAll("connect_timeout", a)
                    }, a);
                    this.subs.push({
                        destroy: function() {
                            clearTimeout(s)
                        }
                    })
                }
                return this.subs.push(r), this.subs.push(o), this
            }, r.prototype.onopen = function() {
                u("open"), this.cleanup(), this.readyState = "open", this.emit("open");
                var e = this.engine;
                this.subs.push(c(e, "data", d(this, "ondata"))), this.subs.push(c(this.decoder, "decoded", d(this, "ondecoded"))), this.subs.push(c(e, "error", d(this, "onerror"))), this.subs.push(c(e, "close", d(this, "onclose")))
            }, r.prototype.ondata = function(e) {
                this.decoder.add(e)
            }, r.prototype.ondecoded = function(e) {
                this.emit("packet", e)
            }, r.prototype.onerror = function(e) {
                u("error", e), this.emitAll("error", e)
            }, r.prototype.socket = function(e) {
                var t = this.nsps[e];
                if (!t) {
                    t = new o(this, e), this.nsps[e] = t;
                    var n = this;
                    t.on("connect", function() {
                        t.id = n.engine.id, ~p(n.connected, t) || n.connected.push(t)
                    })
                }
                return t
            }, r.prototype.destroy = function(e) {
                var t = p(this.connected, e);
                ~t && this.connected.splice(t, 1), this.connected.length || this.close()
            }, r.prototype.packet = function(e) {
                u("writing packet %j", e);
                var t = this;
                t.encoding ? t.packetBuffer.push(e) : (t.encoding = !0, this.encoder.encode(e, function(e) {
                    for (var n = 0; n < e.length; n++) t.engine.write(e[n]);
                    t.encoding = !1, t.processPacketQueue()
                }))
            }, r.prototype.processPacketQueue = function() {
                if (this.packetBuffer.length > 0 && !this.encoding) {
                    var e = this.packetBuffer.shift();
                    this.packet(e)
                }
            }, r.prototype.cleanup = function() {
                for (var e; e = this.subs.shift();) e.destroy();
                this.packetBuffer = [], this.encoding = !1, this.decoder.destroy()
            }, r.prototype.close = r.prototype.disconnect = function() {
                this.skipReconnect = !0, this.backoff.reset(), this.readyState = "closed", this.engine && this.engine.close()
            }, r.prototype.onclose = function(e) {
                u("close"), this.cleanup(), this.backoff.reset(), this.readyState = "closed", this.emit("close", e), this._reconnection && !this.skipReconnect && this.reconnect()
            }, r.prototype.reconnect = function() {
                if (this.reconnecting || this.skipReconnect) return this;
                var e = this;
                if (this.backoff.attempts >= this._reconnectionAttempts) u("reconnect failed"), this.backoff.reset(), this.emitAll("reconnect_failed"), this.reconnecting = !1;
                else {
                    var t = this.backoff.duration();
                    u("will wait %dms before reconnect attempt", t), this.reconnecting = !0;
                    var n = setTimeout(function() {
                        e.skipReconnect || (u("attempting reconnect"), e.emitAll("reconnect_attempt", e.backoff.attempts), e.emitAll("reconnecting", e.backoff.attempts), e.skipReconnect || e.open(function(t) {
                            t ? (u("reconnect attempt error"), e.reconnecting = !1, e.reconnect(), e.emitAll("reconnect_error", t.data)) : (u("reconnect success"), e.onreconnect())
                        }))
                    }, t);
                    this.subs.push({
                        destroy: function() {
                            clearTimeout(n)
                        }
                    })
                }
            }, r.prototype.onreconnect = function() {
                var e = this.backoff.attempts;
                this.reconnecting = !1, this.backoff.reset(), this.updateSocketIds(), this.emitAll("reconnect", e)
            }
        }, {
            "./on": 63,
            "./socket": 64,
            "./url": 65,
            backo2: 13,
            "component-bind": 16,
            "component-emitter": 17,
            debug: 19,
            "engine.io-client": 20,
            indexof: 41,
            "object-component": 48,
            "socket.io-parser": 67
        }],
        63: [function(e, t, n) {
            t.exports = function(e, t, n) {
                return e.on(t, n), {
                    destroy: function() {
                        e.removeListener(t, n)
                    }
                }
            }
        }, {}],
        64: [function(e, t, n) {
            function r(e, t) {
                this.io = e, this.nsp = t, this.json = this, this.ids = 0, this.acks = {}, this.io.autoConnect && this.open(), this.receiveBuffer = [], this.sendBuffer = [], this.connected = !1, this.disconnected = !0
            }
            var i = e("socket.io-parser"),
                o = e("component-emitter"),
                a = e("to-array"),
                s = e("./on"),
                c = e("component-bind"),
                d = e("debug")("socket.io-client:socket"),
                u = e("has-binary");
            t.exports = r;
            var p = {
                    connect: 1,
                    connect_error: 1,
                    connect_timeout: 1,
                    disconnect: 1,
                    error: 1,
                    reconnect: 1,
                    reconnect_attempt: 1,
                    reconnect_failed: 1,
                    reconnect_error: 1,
                    reconnecting: 1
                },
                f = o.prototype.emit;
            o(r.prototype), r.prototype.subEvents = function() {
                if (!this.subs) {
                    var e = this.io;
                    this.subs = [s(e, "open", c(this, "onopen")), s(e, "packet", c(this, "onpacket")), s(e, "close", c(this, "onclose"))]
                }
            }, r.prototype.open = r.prototype.connect = function() {
                return this.connected ? this : (this.subEvents(), this.io.open(), "open" == this.io.readyState && this.onopen(), this)
            }, r.prototype.send = function() {
                var e = a(arguments);
                return e.unshift("message"), this.emit.apply(this, e), this
            }, r.prototype.emit = function(e) {
                if (p.hasOwnProperty(e)) return f.apply(this, arguments), this;
                var t = a(arguments),
                    n = i.EVENT;
                u(t) && (n = i.BINARY_EVENT);
                var r = {
                    type: n,
                    data: t
                };
                return "function" == typeof t[t.length - 1] && (d("emitting packet with ack id %d", this.ids), this.acks[this.ids] = t.pop(), r.id = this.ids++), this.connected ? this.packet(r) : this.sendBuffer.push(r), this
            }, r.prototype.packet = function(e) {
                e.nsp = this.nsp, this.io.packet(e)
            }, r.prototype.onopen = function() {
                d("transport is open - connecting"), "/" != this.nsp && this.packet({
                    type: i.CONNECT
                })
            }, r.prototype.onclose = function(e) {
                d("close (%s)", e), this.connected = !1, this.disconnected = !0, delete this.id, this.emit("disconnect", e)
            }, r.prototype.onpacket = function(e) {
                if (e.nsp == this.nsp) switch (e.type) {
                    case i.CONNECT:
                        this.onconnect();
                        break;
                    case i.EVENT:
                    case i.BINARY_EVENT:
                        this.onevent(e);
                        break;
                    case i.ACK:
                    case i.BINARY_ACK:
                        this.onack(e);
                        break;
                    case i.DISCONNECT:
                        this.ondisconnect();
                        break;
                    case i.ERROR:
                        this.emit("error", e.data)
                }
            }, r.prototype.onevent = function(e) {
                var t = e.data || [];
                d("emitting event %j", t), null != e.id && (d("attaching ack callback to event"), t.push(this.ack(e.id))), this.connected ? f.apply(this, t) : this.receiveBuffer.push(t)
            }, r.prototype.ack = function(e) {
                var t = this,
                    n = !1;
                return function() {
                    if (!n) {
                        n = !0;
                        var r = a(arguments);
                        d("sending ack %j", r);
                        var o = u(r) ? i.BINARY_ACK : i.ACK;
                        t.packet({
                            type: o,
                            id: e,
                            data: r
                        })
                    }
                }
            }, r.prototype.onack = function(e) {
                d("calling ack %s with %j", e.id, e.data), this.acks[e.id].apply(this, e.data), delete this.acks[e.id]
            }, r.prototype.onconnect = function() {
                this.connected = !0, this.disconnected = !1, this.emit("connect"), this.emitBuffered()
            }, r.prototype.emitBuffered = function() {
                var e;
                for (e = 0; e < this.receiveBuffer.length; e++) f.apply(this, this.receiveBuffer[e]);
                for (this.receiveBuffer = [], e = 0; e < this.sendBuffer.length; e++) this.packet(this.sendBuffer[e]);
                this.sendBuffer = []
            }, r.prototype.ondisconnect = function() {
                d("server disconnect (%s)", this.nsp), this.destroy(), this.onclose("io server disconnect")
            }, r.prototype.destroy = function() {
                if (this.subs) {
                    for (var e = 0; e < this.subs.length; e++) this.subs[e].destroy();
                    this.subs = null
                }
                this.io.destroy(this)
            }, r.prototype.close = r.prototype.disconnect = function() {
                return this.connected && (d("performing disconnect (%s)", this.nsp), this.packet({
                    type: i.DISCONNECT
                })), this.destroy(), this.connected && this.onclose("io client disconnect"), this
            }
        }, {
            "./on": 63,
            "component-bind": 16,
            "component-emitter": 17,
            debug: 19,
            "has-binary": 39,
            "socket.io-parser": 67,
            "to-array": 69
        }],
        65: [function(e, t, n) {
            (function(n) {
                var r = e("parseuri"),
                    i = e("debug")("socket.io-client:url");
                t.exports = function(e, t) {
                    var o = e,
                        t = t || n.location;
                    return null == e && (e = t.protocol + "//" + t.host), "string" == typeof e && ("/" == e.charAt(0) && (e = "/" == e.charAt(1) ? t.protocol + e : t.hostname + e), /^(https?|wss?):\/\//.test(e) || (i("protocol-less url %s", e), e = void 0 !== t ? t.protocol + "//" + e : "https://" + e), i("parse %s", e), o = r(e)), o.port || (/^(http|ws)$/.test(o.protocol) ? o.port = "80" : /^(http|ws)s$/.test(o.protocol) && (o.port = "443")), o.path = o.path || "/", o.id = o.protocol + "://" + o.host + ":" + o.port, o.href = o.protocol + "://" + o.host + (t && t.port == o.port ? "" : ":" + o.port), o
                }
            }).call(this, "undefined" != typeof global ? global : "undefined" != typeof self ? self : "undefined" != typeof window ? window : {})
        }, {
            debug: 19,
            parseuri: 51
        }],
        66: [function(e, t, n) {
            (function(t) {
                var r = e("isarray"),
                    i = e("./is-buffer");
                n.deconstructPacket = function(e) {
                    function t(e) {
                        if (!e) return e;
                        if (i(e)) {
                            var o = {
                                _placeholder: !0,
                                num: n.length
                            };
                            return n.push(e), o
                        }
                        if (r(e)) {
                            for (var a = new Array(e.length), s = 0; s < e.length; s++) a[s] = t(e[s]);
                            return a
                        }
                        if ("object" == typeof e && !(e instanceof Date)) {
                            a = {};
                            for (var c in e) a[c] = t(e[c]);
                            return a
                        }
                        return e
                    }
                    var n = [],
                        o = e.data,
                        a = e;
                    return a.data = t(o), a.attachments = n.length, {
                        packet: a,
                        buffers: n
                    }
                }, n.reconstructPacket = function(e, t) {
                    function n(e) {
                        if (e && e._placeholder) return t[e.num];
                        if (r(e)) {
                            for (var i = 0; i < e.length; i++) e[i] = n(e[i]);
                            return e
                        }
                        if (e && "object" == typeof e) {
                            for (var o in e) e[o] = n(e[o]);
                            return e
                        }
                        return e
                    }
                    return e.data = n(e.data), e.attachments = void 0, e
                }, n.removeBlobs = function(e, n) {
                    function o(e, c, d) {
                        if (!e) return e;
                        if (t.Blob && e instanceof Blob || t.File && e instanceof File) {
                            a++;
                            var u = new FileReader;
                            u.onload = function() {
                                d ? d[c] = this.result : s = this.result, --a || n(s)
                            }, u.readAsArrayBuffer(e)
                        } else if (r(e))
                            for (var p = 0; p < e.length; p++) o(e[p], p, e);
                        else if (e && "object" == typeof e && !i(e))
                            for (var f in e) o(e[f], f, e)
                    }
                    var a = 0,
                        s = e;
                    o(s), a || n(s)
                }
            }).call(this, "undefined" != typeof global ? global : "undefined" != typeof self ? self : "undefined" != typeof window ? window : {})
        }, {
            "./is-buffer": 68,
            isarray: 42
        }],
        67: [function(e, t, n) {
            function r() {}

            function i(e) {
                var t = "",
                    r = !1;
                return t += e.type, n.BINARY_EVENT != e.type && n.BINARY_ACK != e.type || (t += e.attachments, t += "-"), e.nsp && "/" != e.nsp && (r = !0, t += e.nsp), null != e.id && (r && (t += ",", r = !1), t += e.id), null != e.data && (r && (t += ","), t += p.stringify(e.data)), u("encoded %j as %s", e, t), t
            }

            function o(e, t) {
                l.removeBlobs(e, function(e) {
                    var n = l.deconstructPacket(e),
                        r = i(n.packet),
                        o = n.buffers;
                    o.unshift(r), t(o)
                })
            }

            function a() {
                this.reconstructor = null
            }

            function s(e) {
                var t = {},
                    r = 0;
                if (t.type = Number(e.charAt(0)), null == n.types[t.type]) return d();
                if (n.BINARY_EVENT == t.type || n.BINARY_ACK == t.type) {
                    for (var i = "";
                        "-" != e.charAt(++r) && (i += e.charAt(r), r != e.length););
                    if (i != Number(i) || "-" != e.charAt(r)) throw new Error("Illegal attachments");
                    t.attachments = Number(i)
                }
                if ("/" == e.charAt(r + 1))
                    for (t.nsp = ""; ++r && "," != (a = e.charAt(r)) && (t.nsp += a, r != e.length););
                else t.nsp = "/";
                var o = e.charAt(r + 1);
                if ("" !== o && Number(o) == o) {
                    for (t.id = ""; ++r;) {
                        var a = e.charAt(r);
                        if (null == a || Number(a) != a) {
                            --r;
                            break
                        }
                        if (t.id += e.charAt(r), r == e.length) break
                    }
                    t.id = Number(t.id)
                }
                if (e.charAt(++r)) try {
                    t.data = p.parse(e.substr(r))
                } catch (e) {
                    return d()
                }
                return u("decoded %s as %j", e, t), t
            }

            function c(e) {
                this.reconPack = e, this.buffers = []
            }

            function d(e) {
                return {
                    type: n.ERROR,
                    data: "parser error"
                }
            }
            var u = e("debug")("socket.io-parser"),
                p = e("json3"),
                f = (e("isarray"), e("component-emitter")),
                l = e("./binary"),
                h = e("./is-buffer");
            n.protocol = 4, n.types = ["CONNECT", "DISCONNECT", "EVENT", "BINARY_EVENT", "ACK", "BINARY_ACK", "ERROR"], n.CONNECT = 0, n.DISCONNECT = 1, n.EVENT = 2, n.ACK = 3, n.ERROR = 4, n.BINARY_EVENT = 5, n.BINARY_ACK = 6, n.Encoder = r, n.Decoder = a, r.prototype.encode = function(e, t) {
                u("encoding packet %j", e), n.BINARY_EVENT == e.type || n.BINARY_ACK == e.type ? o(e, t) : t([i(e)])
            }, f(a.prototype), a.prototype.add = function(e) {
                var t;
                if ("string" == typeof e) t = s(e), n.BINARY_EVENT == t.type || n.BINARY_ACK == t.type ? (this.reconstructor = new c(t), 0 === this.reconstructor.reconPack.attachments && this.emit("decoded", t)) : this.emit("decoded", t);
                else {
                    if (!h(e) && !e.base64) throw new Error("Unknown type: " + e);
                    if (!this.reconstructor) throw new Error("got binary data when not reconstructing a packet");
                    (t = this.reconstructor.takeBinaryData(e)) && (this.reconstructor = null, this.emit("decoded", t))
                }
            }, a.prototype.destroy = function() {
                this.reconstructor && this.reconstructor.finishedReconstruction()
            }, c.prototype.takeBinaryData = function(e) {
                if (this.buffers.push(e), this.buffers.length == this.reconPack.attachments) {
                    var t = l.reconstructPacket(this.reconPack, this.buffers);
                    return this.finishedReconstruction(), t
                }
                return null
            }, c.prototype.finishedReconstruction = function() {
                this.reconPack = null, this.buffers = []
            }
        }, {
            "./binary": 66,
            "./is-buffer": 68,
            "component-emitter": 17,
            debug: 19,
            isarray: 42,
            json3: 43
        }],
        68: [function(e, t, n) {
            (function(e) {
                t.exports = function(t) {
                    return e.Buffer && e.Buffer.isBuffer(t) || e.ArrayBuffer && t instanceof ArrayBuffer
                }
            }).call(this, "undefined" != typeof global ? global : "undefined" != typeof self ? self : "undefined" != typeof window ? window : {})
        }, {}],
        69: [function(e, t, n) {
            t.exports = function(e, t) {
                for (var n = [], r = (t = t || 0) || 0; r < e.length; r++) n[r - t] = e[r];
                return n
            }
        }, {}],
        70: [function(e, t, n) {
            (function(e) {
                ! function(r) {
                    function i(e) {
                        for (var t, n, r = [], i = 0, o = e.length; i < o;)(t = e.charCodeAt(i++)) >= 55296 && t <= 56319 && i < o ? 56320 == (64512 & (n = e.charCodeAt(i++))) ? r.push(((1023 & t) << 10) + (1023 & n) + 65536) : (r.push(t), i--) : r.push(t);
                        return r
                    }

                    function o(e) {
                        for (var t, n = e.length, r = -1, i = ""; ++r < n;)(t = e[r]) > 65535 && (i += v((t -= 65536) >>> 10 & 1023 | 55296), t = 56320 | 1023 & t), i += v(t);
                        return i
                    }

                    function a(e) {
                        if (e >= 55296 && e <= 57343) throw Error("Lone surrogate U+" + e.toString(16).toUpperCase() + " is not a scalar value")
                    }

                    function s(e, t) {
                        return v(e >> t & 63 | 128)
                    }

                    function c(e) {
                        if (0 == (4294967168 & e)) return v(e);
                        var t = "";
                        return 0 == (4294965248 & e) ? t = v(e >> 6 & 31 | 192) : 0 == (4294901760 & e) ? (a(e), t = v(e >> 12 & 15 | 224), t += s(e, 6)) : 0 == (4292870144 & e) && (t = v(e >> 18 & 7 | 240), t += s(e, 12), t += s(e, 6)), t += v(63 & e | 128)
                    }

                    function d() {
                        if (g >= m) throw Error("Invalid byte index");
                        var e = 255 & h[g];
                        if (g++, 128 == (192 & e)) return 63 & e;
                        throw Error("Invalid continuation byte")
                    }

                    function u() {
                        var e, t, n, r, i;
                        if (g > m) throw Error("Invalid byte index");
                        if (g == m) return !1;
                        if (e = 255 & h[g], g++, 0 == (128 & e)) return e;
                        if (192 == (224 & e)) {
                            if ((i = (31 & e) << 6 | (t = d())) >= 128) return i;
                            throw Error("Invalid continuation byte")
                        }
                        if (224 == (240 & e)) {
                            if (t = d(), n = d(), (i = (15 & e) << 12 | t << 6 | n) >= 2048) return a(i), i;
                            throw Error("Invalid continuation byte")
                        }
                        if (240 == (248 & e) && (t = d(), n = d(), r = d(), (i = (15 & e) << 18 | t << 12 | n << 6 | r) >= 65536 && i <= 1114111)) return i;
                        throw Error("Invalid UTF-8 detected")
                    }
                    var p = "object" == typeof n && n,
                        f = "object" == typeof t && t && t.exports == p && t,
                        l = "object" == typeof e && e;
                    l.global !== l && l.window !== l || (r = l);
                    var h, m, g, v = String.fromCharCode,
                        y = {
                            version: "2.0.0",
                            encode: function(e) {
                                for (var t = i(e), n = t.length, r = -1, o = ""; ++r < n;) o += c(t[r]);
                                return o
                            },
                            decode: function(e) {
                                h = i(e), m = h.length, g = 0;
                                for (var t, n = []; !1 !== (t = u());) n.push(t);
                                return o(n)
                            }
                        };
                    if (p && !p.nodeType)
                        if (f) f.exports = y;
                        else {
                            var b = {}.hasOwnProperty;
                            for (var w in y) b.call(y, w) && (p[w] = y[w])
                        }
                    else r.utf8 = y
                }(this)
            }).call(this, "undefined" != typeof global ? global : "undefined" != typeof self ? self : "undefined" != typeof window ? window : {})
        }, {}],
        71: [function(e, t, n) {
            "function" == typeof Object.create ? t.exports = function(e, t) {
                e.super_ = t, e.prototype = Object.create(t.prototype, {
                    constructor: {
                        value: e,
                        enumerable: !1,
                        writable: !0,
                        configurable: !0
                    }
                })
            } : t.exports = function(e, t) {
                e.super_ = t;
                var n = function() {};
                n.prototype = t.prototype, e.prototype = new n, e.prototype.constructor = e
            }
        }, {}],
        72: [function(e, t, n) {
            t.exports = function(e) {
                return e && "object" == typeof e && "function" == typeof e.copy && "function" == typeof e.fill && "function" == typeof e.readUInt8
            }
        }, {}],
        73: [function(e, t, n) {
            (function(t, r) {
                function i(e, t) {
                    var r = {
                        seen: [],
                        stylize: a
                    };
                    return arguments.length >= 3 && (r.depth = arguments[2]), arguments.length >= 4 && (r.colors = arguments[3]), m(t) ? r.showHidden = t : t && n._extend(r, t), b(r.showHidden) && (r.showHidden = !1), b(r.depth) && (r.depth = 2), b(r.colors) && (r.colors = !1), b(r.customInspect) && (r.customInspect = !0), r.colors && (r.stylize = o), c(r, e, r.depth)
                }

                function o(e, t) {
                    var n = i.styles[t];
                    return n ? "[" + i.colors[n][0] + "m" + e + "[" + i.colors[n][1] + "m" : e
                }

                function a(e, t) {
                    return e
                }

                function s(e) {
                    var t = {};
                    return e.forEach(function(e, n) {
                        t[e] = !0
                    }), t
                }

                function c(e, t, r) {
                    if (e.customInspect && t && T(t.inspect) && t.inspect !== n.inspect && (!t.constructor || t.constructor.prototype !== t)) {
                        var i = t.inspect(r, e);
                        return y(i) || (i = c(e, i, r)), i
                    }
                    var o = d(e, t);
                    if (o) return o;
                    var a = Object.keys(t),
                        m = s(a);
                    if (e.showHidden && (a = Object.getOwnPropertyNames(t)), k(t) && (a.indexOf("message") >= 0 || a.indexOf("description") >= 0)) return u(t);
                    if (0 === a.length) {
                        if (T(t)) {
                            var g = t.name ? ": " + t.name : "";
                            return e.stylize("[Function" + g + "]", "special")
                        }
                        if (w(t)) return e.stylize(RegExp.prototype.toString.call(t), "regexp");
                        if (S(t)) return e.stylize(Date.prototype.toString.call(t), "date");
                        if (k(t)) return u(t)
                    }
                    var v = "",
                        b = !1,
                        C = ["{", "}"];
                    if (h(t) && (b = !0, C = ["[", "]"]), T(t) && (v = " [Function" + (t.name ? ": " + t.name : "") + "]"), w(t) && (v = " " + RegExp.prototype.toString.call(t)), S(t) && (v = " " + Date.prototype.toUTCString.call(t)), k(t) && (v = " " + u(t)), 0 === a.length && (!b || 0 == t.length)) return C[0] + v + C[1];
                    if (r < 0) return w(t) ? e.stylize(RegExp.prototype.toString.call(t), "regexp") : e.stylize("[Object]", "special");
                    e.seen.push(t);
                    var E;
                    return E = b ? p(e, t, r, m, a) : a.map(function(n) {
                        return f(e, t, r, m, n, b)
                    }), e.seen.pop(), l(E, v, C)
                }

                function d(e, t) {
                    if (b(t)) return e.stylize("undefined", "undefined");
                    if (y(t)) {
                        var n = "'" + JSON.stringify(t).replace(/^"|"$/g, "").replace(/'/g, "\\'").replace(/\\"/g, '"') + "'";
                        return e.stylize(n, "string")
                    }
                    return v(t) ? e.stylize("" + t, "number") : m(t) ? e.stylize("" + t, "boolean") : g(t) ? e.stylize("null", "null") : void 0
                }

                function u(e) {
                    return "[" + Error.prototype.toString.call(e) + "]"
                }

                function p(e, t, n, r, i) {
                    for (var o = [], a = 0, s = t.length; a < s; ++a) R(t, String(a)) ? o.push(f(e, t, n, r, String(a), !0)) : o.push("");
                    return i.forEach(function(i) {
                        i.match(/^\d+$/) || o.push(f(e, t, n, r, i, !0))
                    }), o
                }

                function f(e, t, n, r, i, o) {
                    var a, s, d;
                    if (d = Object.getOwnPropertyDescriptor(t, i) || {
                            value: t[i]
                        }, d.get ? s = d.set ? e.stylize("[Getter/Setter]", "special") : e.stylize("[Getter]", "special") : d.set && (s = e.stylize("[Setter]", "special")), R(r, i) || (a = "[" + i + "]"), s || (e.seen.indexOf(d.value) < 0 ? (s = g(n) ? c(e, d.value, null) : c(e, d.value, n - 1)).indexOf("\n") > -1 && (s = o ? s.split("\n").map(function(e) {
                            return "  " + e
                        }).join("\n").substr(2) : "\n" + s.split("\n").map(function(e) {
                            return "   " + e
                        }).join("\n")) : s = e.stylize("[Circular]", "special")), b(a)) {
                        if (o && i.match(/^\d+$/)) return s;
                        (a = JSON.stringify("" + i)).match(/^"([a-zA-Z_][a-zA-Z_0-9]*)"$/) ? (a = a.substr(1, a.length - 2), a = e.stylize(a, "name")) : (a = a.replace(/'/g, "\\'").replace(/\\"/g, '"').replace(/(^"|"$)/g, "'"), a = e.stylize(a, "string"))
                    }
                    return a + ": " + s
                }

                function l(e, t, n) {
                    var r = 0;
                    return e.reduce(function(e, t) {
                        return r++, t.indexOf("\n") >= 0 && r++, e + t.replace(/\u001b\[\d\d?m/g, "").length + 1
                    }, 0) > 60 ? n[0] + ("" === t ? "" : t + "\n ") + " " + e.join(",\n  ") + " " + n[1] : n[0] + t + " " + e.join(", ") + " " + n[1]
                }

                function h(e) {
                    return Array.isArray(e)
                }

                function m(e) {
                    return "boolean" == typeof e
                }

                function g(e) {
                    return null === e
                }

                function v(e) {
                    return "number" == typeof e
                }

                function y(e) {
                    return "string" == typeof e
                }

                function b(e) {
                    return void 0 === e
                }

                function w(e) {
                    return C(e) && "[object RegExp]" === E(e)
                }

                function C(e) {
                    return "object" == typeof e && null !== e
                }

                function S(e) {
                    return C(e) && "[object Date]" === E(e)
                }

                function k(e) {
                    return C(e) && ("[object Error]" === E(e) || e instanceof Error)
                }

                function T(e) {
                    return "function" == typeof e
                }

                function E(e) {
                    return Object.prototype.toString.call(e)
                }

                function P(e) {
                    return e < 10 ? "0" + e.toString(10) : e.toString(10)
                }

                function x() {
                    var e = new Date,
                        t = [P(e.getHours()), P(e.getMinutes()), P(e.getSeconds())].join(":");
                    return [e.getDate(), j[e.getMonth()], t].join(" ")
                }

                function R(e, t) {
                    return Object.prototype.hasOwnProperty.call(e, t)
                }
                var O = /%[sdj%]/g;
                n.format = function(e) {
                    if (!y(e)) {
                        for (var t = [], n = 0; n < arguments.length; n++) t.push(i(arguments[n]));
                        return t.join(" ")
                    }
                    for (var n = 1, r = arguments, o = r.length, a = String(e).replace(O, function(e) {
                            if ("%%" === e) return "%";
                            if (n >= o) return e;
                            switch (e) {
                                case "%s":
                                    return String(r[n++]);
                                case "%d":
                                    return Number(r[n++]);
                                case "%j":
                                    try {
                                        return JSON.stringify(r[n++])
                                    } catch (e) {
                                        return "[Circular]"
                                    }
                                default:
                                    return e
                            }
                        }), s = r[n]; n < o; s = r[++n]) g(s) || !C(s) ? a += " " + s : a += " " + i(s);
                    return a
                }, n.deprecate = function(e, i) {
                    if (b(r.process)) return function() {
                        return n.deprecate(e, i).apply(this, arguments)
                    };
                    if (!0 === t.noDeprecation) return e;
                    var o = !1;
                    return function() {
                        if (!o) {
                            if (t.throwDeprecation) throw new Error(i);
                            t.traceDeprecation ? console.trace(i) : console.error(i), o = !0
                        }
                        return e.apply(this, arguments)
                    }
                };
                var D, _ = {};
                n.debuglog = function(e) {
                    if (b(D) && (D = t.env.NODE_DEBUG || ""), e = e.toUpperCase(), !_[e])
                        if (new RegExp("\\b" + e + "\\b", "i").test(D)) {
                            var r = t.pid;
                            _[e] = function() {
                                var t = n.format.apply(n, arguments);
                                console.error("%s %d: %s", e, r, t)
                            }
                        } else _[e] = function() {};
                    return _[e]
                }, n.inspect = i, i.colors = {
                    bold: [1, 22],
                    italic: [3, 23],
                    underline: [4, 24],
                    inverse: [7, 27],
                    white: [37, 39],
                    grey: [90, 39],
                    black: [30, 39],
                    blue: [34, 39],
                    cyan: [36, 39],
                    green: [32, 39],
                    magenta: [35, 39],
                    red: [31, 39],
                    yellow: [33, 39]
                }, i.styles = {
                    special: "cyan",
                    number: "yellow",
                    boolean: "yellow",
                    undefined: "grey",
                    null: "bold",
                    string: "green",
                    date: "magenta",
                    regexp: "red"
                }, n.isArray = h, n.isBoolean = m, n.isNull = g, n.isNullOrUndefined = function(e) {
                    return null == e
                }, n.isNumber = v, n.isString = y, n.isSymbol = function(e) {
                    return "symbol" == typeof e
                }, n.isUndefined = b, n.isRegExp = w, n.isObject = C, n.isDate = S, n.isError = k, n.isFunction = T, n.isPrimitive = function(e) {
                    return null === e || "boolean" == typeof e || "number" == typeof e || "string" == typeof e || "symbol" == typeof e || void 0 === e
                }, n.isBuffer = e("./support/isBuffer");
                var j = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                n.log = function() {
                    console.log("%s - %s", x(), n.format.apply(n, arguments))
                }, n.inherits = e("inherits"), n._extend = function(e, t) {
                    if (!t || !C(t)) return e;
                    for (var n = Object.keys(t), r = n.length; r--;) e[n[r]] = t[n[r]];
                    return e
                }
            }).call(this, e("_process"), "undefined" != typeof global ? global : "undefined" != typeof self ? self : "undefined" != typeof window ? window : {})
        }, {
            "./support/isBuffer": 72,
            _process: 52,
            inherits: 71
        }],
        74: [function(e, t, n) {
            var r, i;
            window.mozRTCPeerConnection || navigator.mozGetUserMedia ? (r = "moz", i = parseInt(navigator.userAgent.match(/Firefox\/([0-9]+)\./)[1], 10)) : (window.webkitRTCPeerConnection || navigator.webkitGetUserMedia) && (r = "webkit", i = navigator.userAgent.match(/Chrom(e|ium)/) && parseInt(navigator.userAgent.match(/Chrom(e|ium)\/([0-9]+)\./)[2], 10));
            var o = window.RTCPeerConnection || window.mozRTCPeerConnection || window.webkitRTCPeerConnection,
                a = window.mozRTCIceCandidate || window.RTCIceCandidate,
                s = window.mozRTCSessionDescription || window.RTCSessionDescription,
                c = window.webkitMediaStream || window.MediaStream,
                d = "https:" === window.location.protocol && ("webkit" === r && i >= 26 || "moz" === r && i >= 33),
                u = window.AudioContext || window.webkitAudioContext,
                p = document.createElement("video"),
                f = p && p.canPlayType && "probably" === p.canPlayType('video/webm; codecs="vp8", vorbis'),
                l = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.msGetUserMedia || navigator.mozGetUserMedia;
            t.exports = {
                prefix: r,
                browserVersion: i,
                support: !!o && !!l,
                supportRTCPeerConnection: !!o,
                supportVp8: f,
                supportGetUserMedia: !!l,
                supportDataChannel: !!(o && o.prototype && o.prototype.createDataChannel),
                supportWebAudio: !(!u || !u.prototype.createMediaStreamSource),
                supportMediaStream: !(!c || !c.prototype.removeTrack),
                supportScreenSharing: !!d,
                AudioContext: u,
                PeerConnection: o,
                SessionDescription: s,
                IceCandidate: a,
                MediaStream: c,
                getUserMedia: l
            }
        }, {}],
        75: [function(e, t, n) {
            function r() {}
            t.exports = r, r.mixin = function(e) {
                var t = e.prototype || e;
                t.isWildEmitter = !0, t.on = function(e, t, n) {
                    this.callbacks = this.callbacks || {};
                    var r = 3 === arguments.length,
                        i = r ? arguments[1] : void 0,
                        o = r ? arguments[2] : arguments[1];
                    return o._groupName = i, (this.callbacks[e] = this.callbacks[e] || []).push(o), this
                }, t.once = function(e, t, n) {
                    function r() {
                        i.off(e, r), s.apply(this, arguments)
                    }
                    var i = this,
                        o = 3 === arguments.length,
                        a = o ? arguments[1] : void 0,
                        s = o ? arguments[2] : arguments[1];
                    return this.on(e, a, r), this
                }, t.releaseGroup = function(e) {
                    this.callbacks = this.callbacks || {};
                    var t, n, r, i;
                    for (t in this.callbacks)
                        for (n = 0, r = (i = this.callbacks[t]).length; n < r; n++) i[n]._groupName === e && (i.splice(n, 1), n--, r--);
                    return this
                }, t.off = function(e, t) {
                    this.callbacks = this.callbacks || {};
                    var n, r = this.callbacks[e];
                    return r ? 1 === arguments.length ? (delete this.callbacks[e], this) : (n = r.indexOf(t), r.splice(n, 1), 0 === r.length && delete this.callbacks[e], this) : this
                }, t.emit = function(e) {
                    this.callbacks = this.callbacks || {};
                    var t, n, r, i = [].slice.call(arguments, 1),
                        o = this.callbacks[e],
                        a = this.getWildcardCallbacks(e);
                    if (o)
                        for (t = 0, n = (r = o.slice()).length; t < n && r[t]; ++t) r[t].apply(this, i);
                    if (a)
                        for (n = a.length, t = 0, n = (r = a.slice()).length; t < n && r[t]; ++t) r[t].apply(this, [e].concat(i));
                    return this
                }, t.getWildcardCallbacks = function(e) {
                    this.callbacks = this.callbacks || {};
                    var t, n, r = [];
                    for (t in this.callbacks) n = t.split("*"), ("*" === t || 2 === n.length && e.slice(0, n[0].length) === n[0]) && (r = r.concat(this.callbacks[t]));
                    return r
                }
            }, r.mixin(r)
        }, {}],
        76: [function(e, t, n) {
            function r(e, t, n) {
                return t ? new o(e, t) : new o(e)
            }
            var i = function() {
                    return this
                }(),
                o = i.WebSocket || i.MozWebSocket;
            t.exports = o ? r : null, o && (r.prototype = o.prototype)
        }, {}],
        77: [function(e, t, n) {
            function r(e) {
                var t = !0;
                return e.getTracks().forEach(function(e) {
                    t = "ended" === e.readyState && t
                }), t
            }

            function i(e) {
                var t = this;
                c.call(this), this.id = e.id, this.parent = e.parent, this.type = e.type || "video", this.oneway = e.oneway || !1, this.sharemyscreen = e.sharemyscreen || !1, this.browserPrefix = e.prefix, this.stream = e.stream, this.enableDataChannels = void 0 === e.enableDataChannels ? this.parent.config.enableDataChannels : e.enableDataChannels, this.receiveMedia = e.receiveMedia || this.parent.config.receiveMedia, this.channels = {}, this.sid = e.sid || Date.now().toString(), this.pc = new s(this.parent.config.peerConnectionConfig, this.parent.config.peerConnectionConstraints), this.pc.on("ice", this.onIceCandidate.bind(this)), this.pc.on("endOfCandidates", function(e) {
                    t.send("endOfCandidates", e)
                }), this.pc.on("offer", function(e) {
                    t.parent.config.nick && (e.nick = t.parent.config.nick), t.send("offer", e)
                }), this.pc.on("answer", function(e) {
                    t.parent.config.nick && (e.nick = t.parent.config.nick), t.send("answer", e)
                }), this.pc.on("addStream", this.handleRemoteStreamAdded.bind(this)), this.pc.on("addChannel", this.handleDataChannelAdded.bind(this)), this.pc.on("removeStream", this.handleStreamRemoved.bind(this)), this.pc.on("negotiationNeeded", this.emit.bind(this, "negotiationNeeded")), this.pc.on("iceConnectionStateChange", this.emit.bind(this, "iceConnectionStateChange")), this.pc.on("iceConnectionStateChange", function() {
                    switch (t.pc.iceConnectionState) {
                        case "failed":
                            "offer" === t.pc.pc.localDescription.type && (t.parent.emit("iceFailed", t), t.send("connectivityError"))
                    }
                }), this.pc.on("signalingStateChange", this.emit.bind(this, "signalingStateChange")), this.logger = this.parent.logger, "screen" === e.type ? this.parent.localScreens && this.parent.localScreens[0] && this.sharemyscreen && (this.logger.log("adding local screen stream to peer connection"), this.pc.addStream(this.parent.localScreens[0]), this.broadcaster = e.broadcaster) : this.parent.localStreams.forEach(function(e) {
                    t.pc.addStream(e)
                }), this.on("channelOpen", function(e) {
                    e.protocol === u && (e.onmessage = function(n) {
                        var r = JSON.parse(n.data),
                            i = new d.Receiver;
                        i.receive(r, e), t.emit("fileTransfer", r, i), i.on("receivedFile", function(e, t) {
                            i.channel.close()
                        })
                    })
                }), this.on("*", function() {
                    t.parent.emit.apply(t.parent, arguments)
                })
            }
            var o = e("util"),
                a = e("webrtcsupport"),
                s = e("rtcpeerconnection"),
                c = e("wildemitter"),
                d = e("filetransfer"),
                u = "https://simplewebrtc.com/protocol/filetransfer#inband-v1";
            o.inherits(i, c), i.prototype.handleMessage = function(e) {
                var t = this;
                this.logger.log("getting", e.type, e), e.prefix && (this.browserPrefix = e.prefix), "offer" === e.type ? (this.nick || (this.nick = e.payload.nick), delete e.payload.nick, this.pc.handleOffer(e.payload, function(e) {
                    e || t.pc.answer(function(e, t) {})
                })) : "answer" === e.type ? (this.nick || (this.nick = e.payload.nick), delete e.payload.nick, this.pc.handleAnswer(e.payload)) : "candidate" === e.type ? this.pc.processIce(e.payload) : "connectivityError" === e.type ? this.parent.emit("connectivityError", t) : "mute" === e.type ? this.parent.emit("mute", {
                    id: e.from,
                    name: e.payload.name
                }) : "unmute" === e.type ? this.parent.emit("unmute", {
                    id: e.from,
                    name: e.payload.name
                }) : "endOfCandidates" === e.type && (this.pc.pc.transceivers || []).forEach(function(e) {
                    e.iceTransport && e.iceTransport.addRemoteCandidate({})
                })
            }, i.prototype.send = function(e, t) {
                var n = {
                    to: this.id,
                    sid: this.sid,
                    broadcaster: this.broadcaster,
                    roomType: this.type,
                    type: e,
                    payload: t,
                    prefix: a.prefix
                };
                this.logger.log("sending", e, n), this.parent.emit("message", n)
            }, i.prototype.sendDirectly = function(e, t, n) {
                var r = {
                    type: t,
                    payload: n
                };
                this.logger.log("sending via datachannel", e, t, r);
                var i = this.getDataChannel(e);
                return "open" == i.readyState && (i.send(JSON.stringify(r)), !0)
            }, i.prototype._observeDataChannel = function(e) {
                var t = this;
                e.onclose = this.emit.bind(this, "channelClose", e), e.onerror = this.emit.bind(this, "channelError", e), e.onmessage = function(n) {
                    t.emit("channelMessage", t, e.label, JSON.parse(n.data), e, n)
                }, e.onopen = this.emit.bind(this, "channelOpen", e)
            }, i.prototype.getDataChannel = function(e, t) {
                if (!a.supportDataChannel) return this.emit("error", new Error("createDataChannel not supported"));
                var n = this.channels[e];
                return t || (t = {}), n || (n = this.channels[e] = this.pc.createDataChannel(e, t), this._observeDataChannel(n), n)
            }, i.prototype.onIceCandidate = function(e) {
                if (!this.closed)
                    if (e) {
                        var t = this.parent.config.peerConnectionConfig;
                        "moz" === a.prefix && t && t.iceTransports && e.candidate && e.candidate.candidate && e.candidate.candidate.indexOf(t.iceTransports) < 0 ? this.logger.log("Ignoring ice candidate not matching pcConfig iceTransports type: ", t.iceTransports) : this.send("candidate", e)
                    } else this.logger.log("End of candidates.")
            }, i.prototype.start = function() {
                this.enableDataChannels && this.getDataChannel("simplewebrtc"), this.pc.offer(this.receiveMedia, function(e, t) {})
            }, i.prototype.icerestart = function() {
                var e = this.receiveMedia;
                e.mandatory.IceRestart = !0, this.pc.offer(e, function(e, t) {})
            }, i.prototype.end = function() {
                this.closed || (this.pc.close(), this.handleStreamRemoved())
            }, i.prototype.handleRemoteStreamAdded = function(e) {
                var t = this;
                this.stream ? this.logger.warn("Already have a remote stream") : (this.stream = e.stream, this.stream.getTracks().forEach(function(e) {
                    e.addEventListener("ended", function() {
                        r(t.stream) && t.end()
                    })
                }), this.parent.emit("peerStreamAdded", this))
            }, i.prototype.handleStreamRemoved = function() {
                var e = this.parent.peers.indexOf(this);
                e > -1 && (this.parent.peers.splice(e, 1), this.closed = !0, this.parent.emit("peerStreamRemoved", this))
            }, i.prototype.handleDataChannelAdded = function(e) {
                this.channels[e.label] = e, this._observeDataChannel(e)
            }, i.prototype.sendFile = function(e) {
                var t = new d.Sender,
                    n = this.getDataChannel("filetransfer" + (new Date).getTime(), {
                        protocol: u
                    });
                return n.onopen = function() {
                    n.send(JSON.stringify({
                        size: e.size,
                        name: e.name
                    })), t.send(e, n)
                }, n.onclose = function() {
                    console.log("sender received transfer"), t.emit("complete")
                }, t
            }, t.exports = i
        }, {
            filetransfer: 35,
            rtcpeerconnection: 53,
            util: 73,
            webrtcsupport: 74,
            wildemitter: 75
        }],
        78: [function(e, t, n) {
            function r(e) {
                var t, n, r = this,
                    u = e || {},
                    p = this.config = {
                        url: "https://sandbox.simplewebrtc.com:443/",
                        socketio: {},
                        connection: null,
                        debug: !1,
                        localVideoEl: "",
                        remoteVideosEl: "",
                        enableDataChannels: !0,
                        autoRequestMedia: !1,
                        autoRemoveVideos: !0,
                        adjustPeerVolume: !1,
                        peerVolumeWhenSpeaking: .25,
                        media: {
                            video: !0,
                            audio: !0
                        },
                        receiveMedia: {
                            offerToReceiveAudio: 1,
                            offerToReceiveVideo: 1
                        },
                        localVideo: {
                            autoplay: !0,
                            mirror: !0,
                            muted: !0
                        }
                    };
                this.logger = e.debug ? e.logger || console : e.logger || c;
                for (t in u) u.hasOwnProperty(t) && (this.config[t] = u[t]);
                this.capabilities = a, o.call(this), (n = null === this.config.connection ? this.connection = new d(this.config) : this.connection = this.config.connection).on("connect", function() {
                    r.emit("connectionReady", n.getSessionid()), r.sessionReady = !0, r.testReadiness()
                }), n.on("message", function(e) {
                    var t, n = r.webrtc.getPeers(e.from, e.roomType);
                    "offer" === e.type ? (n.length && n.forEach(function(n) {
                        n.sid == e.sid && (t = n)
                    }), t || (t = r.webrtc.createPeer({
                        id: e.from,
                        sid: e.sid,
                        type: e.roomType,
                        enableDataChannels: r.config.enableDataChannels && "screen" !== e.roomType,
                        sharemyscreen: "screen" === e.roomType && !e.broadcaster,
                        broadcaster: "screen" !== e.roomType || e.broadcaster ? null : r.connection.getSessionid()
                    }), r.emit("createdPeer", t)), t.handleMessage(e)) : n.length && n.forEach(function(t) {
                        e.sid ? t.sid === e.sid && t.handleMessage(e) : t.handleMessage(e)
                    })
                }), n.on("remove", function(e) {
                    e.id !== r.connection.getSessionid() && r.webrtc.removePeers(e.id, e.type)
                }), e.logger = this.logger, e.debug = !1, this.webrtc = new i(e), ["mute", "unmute", "pauseVideo", "resumeVideo", "pause", "resume", "sendToAll", "sendDirectlyToAll", "getPeers"].forEach(function(e) {
                    r[e] = r.webrtc[e].bind(r.webrtc)
                }), this.webrtc.on("*", function() {
                    r.emit.apply(r, arguments)
                }), p.debug && this.on("*", this.logger.log.bind(this.logger, "SimpleWebRTC event:")), this.webrtc.on("localStream", function() {
                    r.testReadiness()
                }), this.webrtc.on("message", function(e) {
                    r.connection.emit("message", e)
                }), this.webrtc.on("peerStreamAdded", this.handlePeerStreamAdded.bind(this)), this.webrtc.on("peerStreamRemoved", this.handlePeerStreamRemoved.bind(this)), this.config.adjustPeerVolume && (this.webrtc.on("speaking", this.setVolumeForAll.bind(this, this.config.peerVolumeWhenSpeaking)), this.webrtc.on("stoppedSpeaking", this.setVolumeForAll.bind(this, 1))), n.on("stunservers", function(e) {
                    r.webrtc.config.peerConnectionConfig.iceServers = e, r.emit("stunservers", e)
                }), n.on("turnservers", function(e) {
                    r.webrtc.config.peerConnectionConfig.iceServers = r.webrtc.config.peerConnectionConfig.iceServers.concat(e), r.emit("turnservers", e)
                }), this.webrtc.on("iceFailed", function(e) {}), this.webrtc.on("connectivityError", function(e) {}), this.webrtc.on("audioOn", function() {
                    r.webrtc.sendToAll("unmute", {
                        name: "audio"
                    })
                }), this.webrtc.on("audioOff", function() {
                    r.webrtc.sendToAll("mute", {
                        name: "audio"
                    })
                }), this.webrtc.on("videoOn", function() {
                    r.webrtc.sendToAll("unmute", {
                        name: "video"
                    })
                }), this.webrtc.on("videoOff", function() {
                    r.webrtc.sendToAll("mute", {
                        name: "video"
                    })
                }), this.webrtc.on("localScreen", function(e) {
                    var t = document.createElement("video"),
                        n = r.getRemoteVideoContainer();
                    t.oncontextmenu = function() {
                        return !1
                    }, t.id = "localScreen", s(e, t), n && n.appendChild(t), r.emit("localScreenAdded", t), r.connection.emit("shareScreen"), r.webrtc.peers.forEach(function(e) {
                        var t;
                        "video" === e.type && (t = r.webrtc.createPeer({
                            id: e.id,
                            type: "screen",
                            sharemyscreen: !0,
                            enableDataChannels: !1,
                            receiveMedia: {
                                offerToReceiveAudio: 0,
                                offerToReceiveVideo: 0
                            },
                            broadcaster: r.connection.getSessionid()
                        }), r.emit("createdPeer", t), t.start())
                    })
                }), this.webrtc.on("localScreenStopped", function(e) {
                    r.getLocalScreen() && r.stopScreenShare()
                }), this.webrtc.on("channelMessage", function(e, t, n) {
                    "volume" == n.type && r.emit("remoteVolumeChange", e, n.volume)
                }), this.config.autoRequestMedia && this.startLocalVideo()
            }
            var i = e("./webrtc"),
                o = e("wildemitter"),
                a = e("webrtcsupport"),
                s = e("attachmediastream"),
                c = e("mockconsole"),
                d = e("./socketioconnection");
            (r.prototype = Object.create(o.prototype, {
                constructor: {
                    value: r
                }
            })).leaveRoom = function() {
                if (this.roomName) {
                    for (this.connection.emit("leave"); this.webrtc.peers.length;) this.webrtc.peers[0].end();
                    this.getLocalScreen() && this.stopScreenShare(), this.emit("leftRoom", this.roomName), this.roomName = void 0
                }
            }, r.prototype.disconnect = function() {
                this.connection.disconnect(), delete this.connection
            }, r.prototype.handlePeerStreamAdded = function(e) {
                var t = this,
                    n = this.getRemoteVideoContainer(),
                    r = s(e.stream);
                e.videoEl = r, r.id = this.getDomId(e), n && n.appendChild(r), this.emit("videoAdded", r, e), window.setTimeout(function() {
                    t.webrtc.isAudioEnabled() || e.send("mute", {
                        name: "audio"
                    }), t.webrtc.isVideoEnabled() || e.send("mute", {
                        name: "video"
                    })
                }, 250)
            }, r.prototype.handlePeerStreamRemoved = function(e) {
                var t = this.getRemoteVideoContainer(),
                    n = e.videoEl;
                this.config.autoRemoveVideos && t && n && t.removeChild(n), n && this.emit("videoRemoved", n, e)
            }, r.prototype.getDomId = function(e) {
                return [e.id, e.type, e.broadcaster ? "broadcasting" : "incoming"].join("_")
            }, r.prototype.setVolumeForAll = function(e) {
                this.webrtc.peers.forEach(function(t) {
                    t.videoEl && (t.videoEl.volume = e)
                })
            }, r.prototype.joinRoom = function(e, t) {
                var n = this;
                this.roomName = e, this.connection.emit("join", e, function(r, i) {
                    if (console.log("join CB", r, i), r) n.emit("error", r);
                    else {
                        var o, a, s, c;
                        for (o in i.clients) {
                            a = i.clients[o];
                            for (s in a) a[s] && (c = n.webrtc.createPeer({
                                id: o,
                                type: s,
                                enableDataChannels: n.config.enableDataChannels && "screen" !== s,
                                receiveMedia: {
                                    offerToReceiveAudio: "screen" !== s && n.config.receiveMedia.offerToReceiveAudio ? 1 : 0,
                                    offerToReceiveVideo: n.config.receiveMedia.offerToReceiveVideo
                                }
                            }), n.emit("createdPeer", c), c.start())
                        }
                    }
                    t && t(r, i), n.emit("joinedRoom", e)
                })
            }, r.prototype.getEl = function(e) {
                return "string" == typeof e ? document.getElementById(e) : e
            }, r.prototype.startLocalVideo = function() {
                var e = this;
                this.webrtc.start(this.config.media, function(t, n) {
                    t ? e.emit("localMediaError", t) : s(n, e.getLocalVideoContainer(), e.config.localVideo)
                })
            }, r.prototype.stopLocalVideo = function() {
                this.webrtc.stop()
            }, r.prototype.getLocalVideoContainer = function() {
                var e = this.getEl(this.config.localVideoEl);
                if (e && "VIDEO" === e.tagName) return e.oncontextmenu = function() {
                    return !1
                }, e;
                if (e) {
                    var t = document.createElement("video");
                    return t.oncontextmenu = function() {
                        return !1
                    }, e.appendChild(t), t
                }
            }, r.prototype.getRemoteVideoContainer = function() {
                return this.getEl(this.config.remoteVideosEl)
            }, r.prototype.shareScreen = function(e) {
                this.webrtc.startScreenShare(e)
            }, r.prototype.getLocalScreen = function() {
                return this.webrtc.localScreens && this.webrtc.localScreens[0]
            }, r.prototype.stopScreenShare = function() {
                this.connection.emit("unshareScreen");
                var e = document.getElementById("localScreen"),
                    t = this.getRemoteVideoContainer();
                this.config.autoRemoveVideos && t && e && t.removeChild(e), e && this.emit("videoRemoved", e), this.getLocalScreen() && this.webrtc.stopScreenShare(), this.webrtc.peers.forEach(function(e) {
                    e.broadcaster && e.end()
                })
            }, r.prototype.testReadiness = function() {
                var e = this;
                this.sessionReady && (this.config.media.video || this.config.media.audio ? this.webrtc.localStreams.length > 0 && e.emit("readyToCall", e.connection.getSessionid()) : e.emit("readyToCall", e.connection.getSessionid()))
            }, r.prototype.createRoom = function(e, t) {
                this.roomName = e, 2 === arguments.length ? this.connection.emit("create", e, t) : this.connection.emit("create", e)
            }, r.prototype.sendFile = function() {
                if (!a.dataChannel) return this.emit("error", new Error("DataChannelNotSupported"))
            }, t.exports = r
        }, {
            "./socketioconnection": 79,
            "./webrtc": 80,
            attachmediastream: 3,
            mockconsole: 46,
            webrtcsupport: 74,
            wildemitter: 75
        }],
        79: [function(e, t, n) {
            function r(e) {
                this.connection = i.connect(e.url, e.socketio)
            }
            var i = e("socket.io-client");
            r.prototype.on = function(e, t) {
                this.connection.on(e, t)
            }, r.prototype.emit = function() {
                this.connection.emit.apply(this.connection, arguments)
            }, r.prototype.getSessionid = function() {
                return this.connection.id
            }, r.prototype.disconnect = function() {
                return this.connection.disconnect()
            }, t.exports = r
        }, {
            "socket.io-client": 60
        }],
        80: [function(e, t, n) {
            function r(e) {
                var t, n = this,
                    r = e || {};
                this.config = {
                    debug: !1,
                    peerConnectionConfig: {
                        iceServers: [{
                            urls: "stun:stun.l.google.com:19302"
                        }]
                    },
                    peerConnectionConstraints: {
                        optional: []
                    },
                    receiveMedia: {
                        offerToReceiveAudio: 1,
                        offerToReceiveVideo: 1
                    },
                    enableDataChannels: !0
                };
                this.logger = e.debug ? e.logger || console : e.logger || a;
                for (t in r) r.hasOwnProperty(t) && (this.config[t] = r[t]);
                o.support || this.logger.error("Your browser doesn't seem to support WebRTC"), this.peers = [], s.call(this, this.config), this.on("speaking", function() {
                    n.hardMuted || n.peers.forEach(function(e) {
                        if (e.enableDataChannels) {
                            var t = e.getDataChannel("hark");
                            if ("open" != t.readyState) return;
                            t.send(JSON.stringify({
                                type: "speaking"
                            }))
                        }
                    })
                }), this.on("stoppedSpeaking", function() {
                    n.hardMuted || n.peers.forEach(function(e) {
                        if (e.enableDataChannels) {
                            var t = e.getDataChannel("hark");
                            if ("open" != t.readyState) return;
                            t.send(JSON.stringify({
                                type: "stoppedSpeaking"
                            }))
                        }
                    })
                }), this.on("volumeChange", function(e, t) {
                    n.hardMuted || n.peers.forEach(function(t) {
                        if (t.enableDataChannels) {
                            var n = t.getDataChannel("hark");
                            if ("open" != n.readyState) return;
                            n.send(JSON.stringify({
                                type: "volume",
                                volume: e
                            }))
                        }
                    })
                }), this.config.debug && this.on("*", function(e, t, r) {
                    (n.config.logger === a ? console : n.logger).log("event:", e, t, r)
                })
            }
            var i = e("util"),
                o = e("webrtcsupport"),
                a = e("mockconsole"),
                s = e("localmedia"),
                c = e("./peer");
            i.inherits(r, s), r.prototype.createPeer = function(e) {
                var t;
                return e.parent = this, t = new c(e), this.peers.push(t), t
            }, r.prototype.removePeers = function(e, t) {
                this.getPeers(e, t).forEach(function(e) {
                    e.end()
                })
            }, r.prototype.getPeers = function(e, t) {
                return this.peers.filter(function(n) {
                    return !(e && n.id !== e || t && n.type !== t)
                })
            }, r.prototype.sendToAll = function(e, t) {
                this.peers.forEach(function(n) {
                    n.send(e, t)
                })
            }, r.prototype.sendDirectlyToAll = function(e, t, n) {
                this.peers.forEach(function(r) {
                    r.enableDataChannels && r.sendDirectly(e, t, n)
                })
            }, t.exports = r
        }, {
            "./peer": 77,
            localmedia: 44,
            mockconsole: 46,
            util: 73,
            webrtcsupport: 74
        }]
    }, {}, [78])(78)
});