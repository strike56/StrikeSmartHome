<?php

    if ($this->kernel->ajaxMode)
        header("X-Robots-Tag: noindex, nofollow");

#####################################
### URL Auto login

    if (!empty($_GET["sec"])) {
        $sec = base64_decode($_GET["sec"]);
        if ($sec) {
            $sec = explode("|", $sec);
            if (count($sec) == 2) {
                $s = "SELECT * FROM users WHERE email = '".$this->kernel->db->escape($sec[0])."'";
                $r = $this->kernel->db->query($s);
                if ($r && $user = $r->getRow()) {
                    $secLocal = $this->kernel->api->misc->userSec($user);
                    if ($secLocal && $secLocal == $sec[1]) {
                        $fld = ["dateLogin" => date("Y-m-d H:i:s")];
                        $this->kernel->db->make("users", $user["ID"], $fld);
                        $this->kernel->api->misc->userReload($user["ID"]);
                        $this->kernel->api->misc->userAutologin($user["ID"]);
                    }
                }
            }
        }
    }

#####################################
### Auto login

    if (!$this->kernel->usr) {
        $uID = $this->kernel->api->misc->userAutologin();
        if ($uID) $this->kernel->api->misc->userReload($uID);
    } else {
        $this->kernel->api->misc->userReload();
    }

#####################################
### Self person and Self clubs and self couples detects

    $this->kernel->usrPerson = false;
    $this->kernel->usrCouples = [];
    $this->kernel->usrCouple = false;
    $this->kernel->usrClubs = [];
    if ($this->kernel->usr) {
        if (!empty($this->kernel->usr["personID"])) {
            $s = "SELECT * FROM persons WHERE ID = ".$this->kernel->usr["personID"];
            $r = $this->kernel->db->query($s);
            $this->kernel->usrPerson = $r ? $r->getRow() : false;
        }
    }
    if ($this->kernel->usrPerson) {
        $s = "SELECT * FROM couples 
                WHERE personID1 = ".$this->kernel->usrPerson["ID"]."
                OR personID2 = ".$this->kernel->usrPerson["ID"]."
                ORDER BY isActive, ID";
        $r = $this->kernel->db->query($s);
        if ($r) while($couple = $r->getRow()) {
            $this->kernel->usrCouples[$couple["ID"]] = $couple;
            if ($couple["isActive"]) $this->kernel->usrCouple = $couple;
        }

        $s = "SELECT c.*, cp.type as linkType 
                FROM club_persons cp
                LEFT JOIN clubs c ON c.ID = cp.clubID
                WHERE cp.personID = ".$this->kernel->usrPerson["ID"]."
                AND c.ID IS NOT NULL";
        $r = $this->kernel->db->query($s);
        $this->kernel->usrClubs = $r && $r->numRows() ? $r->getRows() : [];
    }

#####################################
### Automatic pagination detector

    $this->kernel->pg = 1;
    if (count($this->kernel->pages) >= 2 &&
        strtolower($this->kernel->pages[count($this->kernel->pages) - 2]) == "page" &&
        (int)$this->kernel->pages[count($this->kernel->pages) - 1]) {
        $page = (int)$this->kernel->pages[count($this->kernel->pages) - 1];
        $this->kernel->pg = $page;
    }

#####################################

    if (!empty($_REQUEST["layer"])) {
        $_SESSION["layer"] = $_REQUEST["layer"];
        $u = explode("?", $_SERVER["REQUEST_URI"])[0];
        header("Location: ".$u);
        die();
    }
    if (!empty($_SESSION["layer"])) {
        $layer = $_SESSION["layer"];
        unset($_SESSION["layer"]);
        $u = PUBLIC_URL."/".$this->kernel->langName."/layer/".$layer;
        $this->kernel->exchange->componentScripts[] = "layer.create('".$u."');";
    }
