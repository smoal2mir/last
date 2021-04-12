<?php

namespace App\Http\Controllers;

class StaticPagesController extends Controller {

    public function getContact() {
        return view("contact");
    }

    public function getEsh() {
        return view("esh");
    }

    public function getRules() {
        return view("rules");
    }

}
