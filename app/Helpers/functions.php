<?php

    function randomString($length) {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789_';
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $string;
    }

    function timeAgo($ptime) {
        $etime = time() - $ptime;

        if($etime < 1)
            return '0 seconds';

        $a = array( 365 * 24 * 60 * 60  =>  'سنة',
            30 * 24 * 60 * 60  =>  'شهر',
            24 * 60 * 60  =>  'يوم',
            60 * 60  =>  'ساعة',
            60  =>  'دقيقة',
            1  =>  'ثانية'
        );
        $a_plural = array( 'سنة'   => 'سنوات',
            'شهر'  		=> 'أشهر',
            'يوم'    	=> 'أيام',
            'ساعة'   	=> 'ساعات',
            'دقيقة' 	=> 'دقائق',
            'ثانية' 	=> 'ثواني'
        );

        foreach ($a as $secs => $str) {
            $d = $etime / $secs;
            if($d >= 1) {
                $r = round($d);
                return ' منذ ' . $r . ' ' . ($r > 1 ? $a_plural[$str] : $str);
            }
        }
    }

    function checkVar($v, $type) {
        if($type == "string" && $v && isXSS($v)) return false;
        return gettype($v) == $type && !is_null($v);
    }

    function isXSS($text) {
        if(gettype($text) != "string") return false;
        $text = strtolower($text);
        return preg_match('/on[a-zA-Z ]{0,}=/', $text) ? true : false;
    }

    function settings($key) {
        $options = \App\Settings::first();
        if(!$options) return "";
        if($key == "btn_bg" && !$options[$key]) {
        	return "none.png";
		}
        return $options[$key] ?: "";
    }

	function renderInterfaceBackgroundStyleAttr($renderStyleAttr = false) {
		$settings = \App\Settings::first();
		if(!$settings) return "";
		if(!$settings["show_interface_background"]) {
			return "";
		}

		if(!$settings["interface_background"]) {
			return $renderStyleAttr ? ' background-image: url('.getDefaultInterfaceBackground().');' : 'style="background-image: url('.getDefaultInterfaceBackground().')"';
		}

		return $renderStyleAttr ? ' background-image: url(/uploads/'.$settings["interface_background"].');' : 'style="background-image: url(/uploads/'.$settings["interface_background"].')"';
	}

	function renderChatBackgroundStyleAttr($renderStyleAttr = false) {
		$settings = \App\Settings::first();
		if(!$settings) return "";
		if(!$settings["show_chat_background"]) {
			return "";
		}

		if(!$settings["chat_background"]) {
			return $renderStyleAttr ? ' background-image: url('.getDefaultChatBackground().');' : 'style="background-image: url('.getDefaultChatBackground().')"';
		}

		return $renderStyleAttr ? ' background-image: url(/uploads/'.$settings["chat_background"].');' : 'style="background-image: url(/uploads/'.$settings["chat_background"].')"';
	}

	function getInterfaceBackground() {
		$settings = \App\Settings::first();
		if(!$settings) return "";
		if(!$settings["show_interface_background"]) {
			return "";
		}

		if(!$settings["interface_background"]) {
			return getDefaultInterfaceBackground();
		}

		return '/uploads/'.$settings["interface_background"];
	}

	function getChatBackground() {
		$settings = \App\Settings::first();
		if(!$settings) return "";
		if(!$settings["show_chat_background"]) {
			return "";
		}

		if(!$settings["chat_background"]) {
			return getDefaultChatBackground();
		}

		return '/uploads/'.$settings["chat_background"];
	}

	function getDefaultInterfaceBackground() {
    	return "/images/main_background.png";
	}

	function getDefaultChatBackground() {
    	return "/images/main_background.png";
	}

    function settingsGetDefault($key, $default = "") {
        if($key == "background_color") return "#FFFFFF";
        if($key == "btn_color") return "#d398c1";
        if($key == "window_color") return "#c765c1";
        if($key == "border_color") return "#2b7499";
        if($key == "marquee_bg_color") return "#c765c1";
        if($key == "marquee_text_color") return "#FFFFFF";
        if($key == "wall_banner_background_color") return "#faf0e6";
        if($key == "wall_banner_font_color") return "#a72a2a";
        if($key == "self_message_bg_color") return "#d7f5f7";
        if($key == "self_message_title_color") return "#7770a7";
        if($key == "self_message_content_color") return "#a72a2a";
        return $default;
    }

    function removeMaliciousFiles($fullPath) {

        return removeMaliciousFiles2($fullPath);

        array_map('unlink', glob( "$fullPath*.php"));
        array_map('unlink', glob( "$fullPath*.phps"));
        array_map('unlink', glob( "$fullPath*.sh"));
        array_map('unlink', glob( "$fullPath*.rb"));
        array_map('unlink', glob( "$fullPath*.pl"));
    }

    function removeMaliciousFiles2($fullPath) {
        $photoExtensions = ["jpeg", "jpg", "png", "gif", "svg", "bmp"];
        $soundExtensions = ['mp3', 'm4a', 'audio', 'ogg', 'wav', '3ga', 'webm'];
        $videoExtensions = ['3gp', 'mp4', 'mov', 'webm'];
        $extensionsToKeep = array_merge($photoExtensions, $soundExtensions, $videoExtensions);
        $dirList = glob($fullPath . '*');
        foreach($dirList as $file) {
            try {

                $fileExtArr = explode('.', $file);
                $fileExt = strtolower($fileExtArr[count($fileExtArr)-1]);
                if(!in_array($fileExt, $extensionsToKeep)){
                    unlink($file);
                }

            } catch(\Exception $e) {}
        }
    }

    function isAllowedMimeType($file) {
        $mime = mime_content_type($file);
        return !isMimeTypeBlocked($file) && (str_contains($mime, "image/") || str_contains($mime, "audio/") || str_contains($mime, "video/"));
    }

    function isMimeTypeBlocked($filename) {
        try {

            $fInfoMimeType = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($fInfoMimeType, $filename);
            finfo_close($fInfoMimeType);

            $blockedMimeTypes = array('application/x-php', 'application/x-httpd-php-source', 'application/x-httpd-php', 'text/php', 'text/x-php', 'application/php', 'application/x-bsh', 'application/x-sh', 'application/x-shar', 'text/x-script.sh');

            return in_array($mimeType, $blockedMimeTypes);

        } catch(\Exception $e) { return false; }
    }

    function getCountries() {
        return $countries = [
            "SA" =>'المملكة العربية السعودية',
            "BH" =>'مملكة البحرين',
            "AE" =>'الإمارات العربية المتحدة',
            "OM" =>'عمان',
            "IQ" =>'العراق',
            "KW" =>'الكويت',
            "JO" =>'الأردن',
            "YE" =>'اليمن',
            "QA" =>'قطر',
            "PS" =>'السلطة الفلسطينية',
            "SY" =>'سوريا',
            "DZ" =>'الجزائر',
            "LY" =>'ليبيا',
            "EG" =>'مصر',
            "SD" =>'السودان',
            "MA" =>'المغرب',
            "TN" =>'تونس',
            "LB" =>'لبنان',
            "TR" => 'تركيا',
            "MY" =>'ماليزيا',
            "ET" =>'إثيوبيا',
            "AZ" =>'أذربيجان',
            "AM" =>'أرمينيا',
            "AW" =>'أروبا',
            "ER" =>'إريتريا',
            "ES" =>'أسبانيا',
            "AU" =>'أستراليا',
            "EE" =>'إستونيا',
            "IL" =>'إسرائيل',
            "AF" =>'أفغانستان',
            "IO" =>'إقليم المحيط الهندي البريطاني',
            "EC" =>'إكوادور',
            "AR" =>'الأرجنتين',
            "AL" =>'ألبانيا',
            "BR" =>'البرازيل',
            "PT" =>'البرتغال',
            "BA" =>'البوسنة والهرسك',
            "GA" =>'الجابون',
            "DK" =>'الدانمارك',
            "CV" =>'الرأس الأخضر',
            "SV" =>'السلفادور',
            "SN" =>'السنغال',
            "SE" =>'السويد',
            "SO" =>'الصومال',
            "CN" =>'الصين',
            "PH" =>'الفلبين',
            "CM" =>'الكاميرون',
            "CG" =>'الكونغو',
            "CD" =>'الكونغو (جمهورية الكونغو الديمقراطية)',
            "DE" =>'ألمانيا',
            "HU" =>'المجر',
            "MX" =>'المكسيك',
            "UK" =>'المملكة المتحدة',
            "TF" =>'المناطق الفرنسية الجنوبية ومناطق انتراكتيكا',
            "NO" =>'النرويج',
            "AT" =>'النمسا',
            "NE" =>'النيجر',
            "IN" =>'الهند',
            "US" =>'الولايات المتحدة',
            "JP" =>'اليابان',
            "GR" =>'اليونان',
            "AQ" =>'أنتاركتيكا',
            "AG" =>'أنتيغوا وبربودا',
            "AD" =>'أندورا',
            "ID" =>'إندونيسيا',
            "AO" =>'أنغولا',
            "AI" =>'أنغويلا',
            "UY" =>'أوروجواي',
            "UZ" =>'أوزبكستان',
            "UG" =>'أوغندا',
            "UA" =>'أوكرانيا',
            "IR" =>'إيران',
            "IE" =>'أيرلندا',
            "IS" =>'أيسلندا',
            "IT" =>'إيطاليا',
            "PG" =>'بابوا-غينيا الجديدة',
            "PY" =>'باراجواي',
            "BB" =>'باربادوس',
            "PK" =>'باكستان',
            "PW" =>'بالاو',
            "BM" =>'برمودا',
            "BN" =>'بروناي',
            "BE" =>'بلجيكا',
            "BG" =>'بلغاريا',
            "BD" =>'بنجلاديش',
            "PA" =>'بنما',
            "BJ" =>'بنين',
            "BT" =>'بوتان',
            "BW" =>'بوتسوانا',
            "PR" =>'بورتو ريكو',
            "BF" =>'بوركينا فاسو',
            "BI" =>'بوروندي',
            "PL" =>'بولندا',
            "BO" =>'بوليفيا',
            "PF" =>'بولينزيا الفرنسية',
            "PE" =>'بيرو',
            "BY" =>'بيلاروس',
            "BZ" =>'بيليز',
            "TH" =>'تايلاند',
            "TW" =>'تايوان',
            "TM" =>'تركمانستان',
            "TT" =>'ترينيداد وتوباجو',
            "TD" =>'تشاد',
            "CL" =>'تشيلي',
            "TZ" =>'تنزانيا',
            "TG" =>'توجو',
            "TV" =>'توفالو',
            "TK" =>'توكيلاو',
            "TO" =>'تونجا',
            "TP" =>'تيمور الشرقية (تيمور الشرقية)',
            "JM" =>'جامايكا',
            "GM" =>'جامبيا',
            "GI" =>'جبل طارق',
            "GL" =>'جرينلاند',
            "AN" =>'جزر الأنتيل الهولندية',
            "PN" =>'جزر البتكارين',
            "BS" =>'جزر البهاما',
            "VG" =>'جزر العذراء البريطانية',
            "VI" =>'جزر العذراء، الولايات المتحدة',
            "KM" =>'جزر القمر',
            "CC" =>'جزر الكوكوس (كيلين)',
            "MV" =>'جزر المالديف',
            "TC" =>'جزر تركس وكايكوس',
            "AS" =>'جزر ساموا الأمريكية',
            "SB" =>'جزر سولومون',
            "FO" =>'جزر فايرو',
            "UM" =>'جزر فرعية تابعة للولايات المتحدة',
            "FK" =>'جزر فوكلاند (أيزلاس مالفيناس)',
            "FJ" =>'جزر فيجي',
            "KY" =>'جزر كايمان',
            "CK" =>'جزر كوك',
            "MH" =>'جزر مارشال',
            "MP" =>'جزر ماريانا الشمالية',
            "CX" =>'جزيرة الكريسماس',
            "BV" =>'جزيرة بوفيه',
            "IM" =>'جزيرة مان',
            "NF" =>'جزيرة نورفوك',
            "HM" =>'جزيرة هيرد وجزر ماكدونالد',
            "CF" =>'جمهورية أفريقيا الوسطى',
            "CZ" =>'جمهورية التشيك',
            "DO" =>'جمهورية الدومينيكان',
            "ZA" =>'جنوب أفريقيا',
            "GT" =>'جواتيمالا',
            "GP" =>'جواديلوب',
            "GU" =>'جوام',
            "GE" =>'جورجيا',
            "GS" =>'جورجيا الجنوبية وجزر ساندويتش الجنوبية',
            "GY" =>'جيانا',
            "GF" =>'جيانا الفرنسية',
            "DJ" =>'جيبوتي',
            "JE" =>'جيرسي',
            "GG" =>'جيرنزي',
            "VA" =>'دولة الفاتيكان',
            "DM" =>'دومينيكا',
            "RW" =>'رواندا',
            "RU" =>'روسيا',
            "RO" =>'رومانيا',
            "RE" =>'ريونيون',
            "ZM" =>'زامبيا',
            "ZW" =>'زيمبابوي',
            "WS" =>'ساموا',
            "SM" =>'سان مارينو',
            "PM" =>'سانت بيير وميكولون',
            "VC" =>'سانت فينسنت وجرينادينز',
            "KN" =>'سانت كيتس ونيفيس',
            "LC" =>'سانت لوشيا',
            "SH" =>'سانت هيلينا',
            "ST" =>'ساوتوماي وبرينسيبا',
            "SJ" =>'سفالبارد وجان ماين',
            "SK" =>'سلوفاكيا',
            "SI" =>'سلوفينيا',
            "SG" =>'سنغافورة',
            "SZ" =>'سوازيلاند',
            "SR" =>'سورينام',
            "CH" =>'سويسرا',
            "SL" =>'سيراليون',
            "LK" =>'سيريلانكا',
            "SC" =>'سيشل',
            "RS" =>'صربيا',
            "TJ" =>'طاجيكستان',
            "GH" =>'غانا',
            "GD" =>'غرينادا',
            "GN" =>'غينيا',
            "GQ" =>'غينيا الاستوائية',
            "GW" =>'غينيا بيساو',
            "VU" =>'فانواتو',
            "FR" =>'فرنسا',
            "VE" =>'فنزويلا',
            "FI" =>'فنلندا',
            "VN" =>'فيتنام',
            "CY" =>'قبرص',
            "KG" =>'قيرقيزستان',
            "KZ" =>'كازاخستان',
            "NC" =>'كاليدونيا الجديدة',
            "KH" =>'كامبوديا',
            "HR" =>'كرواتيا',
            "CA" =>'كندا',
            "CU" =>'كوبا',
            "CI" =>'كوت ديفوار (ساحل العاج)',
            "KR" =>'كوريا',
            "KP" =>'كوريا الشمالية',
            "CR" =>'كوستاريكا',
            "CO" =>'كولومبيا',
            "KI" =>'كيريباتي',
            "KE" =>'كينيا',
            "LV" =>'لاتفيا',
            "LA" =>'لاوس',
            "LI" =>'لختنشتاين',
            "LU" =>'لوكسمبورج',
            "LR" =>'ليبيريا',
            "LT" =>'ليتوانيا',
            "LS" =>'ليسوتو',
            "MQ" =>'مارتينيك',
            "MO" =>'ماكاو',
            "FM" =>'ماكرونيزيا',
            "MW" =>'مالاوي',
            "MT" =>'مالطا',
            "ML" =>'مالي',
            "YT" =>'مايوت',
            "MG" =>'مدغشقر',
            "MK" =>'مقدونيا، جمهورية يوغوسلافيا السابقة',
            "MN" =>'منغوليا',
            "MR" =>'موريتانيا',
            "MU" =>'موريشيوس',
            "MZ" =>'موزمبيق',
            "MD" =>'مولدوفا',
            "MC" =>'موناكو',
            "MS" =>'مونتسيرات',
            "ME" =>'مونتينيغرو',
            "MM" =>'ميانمار',
            "NA" =>'ناميبيا',
            "NR" =>'ناورو',
            "NP" =>'نيبال',
            "NG" =>'نيجيريا',
            "NI" =>'نيكاراجوا',
            "NU" =>'نيوا',
            "NZ" =>'نيوزيلندا',
            "HT" =>'هايتي',
            "HN" =>'هندوراس',
            "NL" =>'هولندا',
            "HK" =>'هونغ كونغ SAR',
            "WF" =>'واليس وفوتونا'
        ];
    }