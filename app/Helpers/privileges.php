<?php

    function sanitizeAutorizationValue($value, $unit, $type) {
        try {

            if($type == "integer") $value = intval($value);
            if($type == "boolean") $value = $value === "true" ? true : false;

            if(!checkVar($type == "integer" ? intval($value) : $value, $type)) return null;

            if($type == "integer") {
                $unit = in_array($unit, ["minute", "hour", "day", "month"]) ? $unit : "day";
                return $value . "|" . $unit;
            } else if ($type == "boolean") {
                return $value ? 1 : 0;
            }

        } catch(\Exception $e) {return null;}

    }

    function validateAutorizationRoles($data, $valideRoles, $dataKey) {
        try {
            $roles = '';
            foreach($valideRoles as $key => $role) {
                if($data[$dataKey.'_'.$role] == "true") {
                    $roles .= $role . ",";
                }
            }
            return $roles;
        } catch(\Exception $e) {return null;}
    }

    function privilegesSplit($val, $unit = false) {
        try {
            if(!$val && !$unit) return 0;
            if(!$val && $unit) return "minute";
            $val = explode("|", $val);
            return $unit ? $val[1] : $val[0];
        } catch(\Exception $e) {return [0, "minute"];}
    }

    function isRoleSelected($roles, $role) {
        try {
            $roles = explode(',', $roles);
            return in_array($role, $roles);
        } catch(\Exception $e) {return false;}
    }