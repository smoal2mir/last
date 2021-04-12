<?php

use App\Gift;
use Illuminate\Database\Seeder;

class GiftsSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        Gift::truncate();

        $images = [
            "9x3hygl3hv.gif", "v0wyvfb4q8.png", "z9i667xpt6.png", "5eexofjzb8.gif", "w5l8qs5cn7.gif", "0i4b5g6fd_.gif",
            "jhrwkhsnwz.gif", "miv1dpwzqy.gif", "zv9sjjlth8.gif", "lume2ald4s.gif", "hbn309913o.gif", "r67z22wohf.gif",
            "yyx3la9u0_.jpg", "zoj_l0sg25.gif", "j7nd8ij4on.jpg", "s9g_vqgc42.jpg", "cedx5gcr6t.gif", "3c91vi_61h.jpg",
            "2fmdjeevh8.jpg", "ecus5jr4_j.jpg", "4id085rujy.jpg", "c6v0z70xrq.gif", "iyyry9hge_.gif", "zsvbwsj5lx.jpg",
            "w9p28_idcb.jpg", "bhde58k8pg.png", "ie0h5kp5fg.png", "65gi8qmcat.gif", "qti_bdis7i.gif", "_dov3drt08.gif",
            "1f6pwun40s.jpg", "4u0pvm75gi.gif", "9l6nd4nl8q.gif", "32066v4gti.gif", "spmhxgrbbx.jpg", "n3ktai_nka.gif",
            "m92v83ygkv.png", "icwwhk9gp4.png", "6jdk57nlpd.gif", "ob0kqm44fu.gif", "ojoey_qv4x.gif", "joof2s9e36.jpg",
            "m9mc4hxyof.png", "3lcetzl_1m.gif", "vgz500czcm.jpg", "60exkcq9i0.jpg", "w9phvxrt37.jpg", "z9w3jqzx2e.jpg",
            "uw2j7hv1a1.jpg", "nhg1m513pu.jpg", "0asegkwepp.jpg", "knzxpt4bhh.jpg"
        ];

        foreach($images as $img) {
            Gift::create(["icon" => $img]);
        }

    }
}
