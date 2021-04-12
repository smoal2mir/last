<?php

use App\Face;
use Illuminate\Database\Seeder;

class FacesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Face::truncate();

        $images = [
            "6ys0uupoxs.gif", "4ueyuccll_.gif", "j22ttfb4t6.gif", "ezdsu8tv9u.gif", "qfgyb939ws.gif", "0vfd2s5zo6.gif",
            "uqv4zpbytr.gif", "ez9zf4gqul.gif", "shqx6_z8la.gif", "apk0ng23ca.gif", "vkveepegse.gif", "72gdphbhgx.gif",
            "ovb5xdo_hw.gif", "183nch552z.gif", "06i59am4z0.gif", "12blv11xoj.gif", "m6nkuse19z.gif", "0h7f43ft0q.gif",
            "eozoebv75y.gif", "bt9xydb2ju.gif", "uxh01w724c.gif", "7ii8c6bq6i.gif", "7arznu6ioy.gif", "pt9yn7zz1o.gif",
            "bwad8w4zji.gif", "70tkac3e31.gif", "pqvmwpe184.gif", "9nxx21zmyu.gif", "35k2r85tze.gif", "hrrohhad_4.gif",
            "_u2is4etk_.gif", "cwfukseg1s.gif", "gpmed0b2p2.gif", "2mw3ptojix.gif", "5yin9ip1ni.gif", "f4i1f5gjwe.gif",
            "rgae436tet.gif", "sgc28uz3co.gif", "d3vvh32h62.gif", "cec4nvyre9.gif", "8fac0kv4rm.gif", "bcgogkdibs.gif",
            "r6kczzwc94.gif", "ll_uz83dzf.gif", "ahlj8rde0z.gif", "0qa0u4spnx.gif", "mglp9j9xcb.gif", "i4s4ozwg7s.gif",
            "9p2bjfw8x0.gif", "b_hbc7mx14.gif", "89uem1op9q.gif", "6dwfgk52n3.gif", "_cf_w47o8w.gif", "89xjhv0lzq.gif",
            "wcjx33rli4.gif", "uj25z1eyog.gif", "_7ks0yhm5d.gif", "9gi1is64pt.gif", "zpg6ri346t.gif", "42bn7odk4b.gif",
            "ukwgp2rku4.gif", "y6v35hw2wu.gif", "11anai90tx.gif", "cg7m30ejbn.gif", "nf4fas6efj.gif", "gtuqakehw9.gif",
            "daul123xvh.gif", "l5zr1f0u_w.gif", "kp1m3x068t.gif", "ffs2l8u3hd.gif", "cxrypl6bcr.gif", "8yk9bkg_az.gif",
            "wkktwdj1nk.gif", "wqwpvw8r1c.gif", "xghhxir2pd.gif", "dqkx2bouy_.gif", "0b8ptyvjiu.gif", "10trcle3hc.gif",
            "24fkvrhejp.gif", "5lgm9wy5y1.gif", "lf1wd36j27.gif", "i6zsplvyb8.gif", "ljg8z2uwik.gif", "9pdgw0ods3.gif",
            "8foi2fjgz7.gif", "z9mxn5l8dt.gif", "gitz0v_5nh.gif", "gr0l1l29pa.gif", "r94yl3k9x9.gif", "8zmykpqgzi.gif"
        ];

        $keys = [
            "ف1", "ف2", "ف3", "ف4", "ف5", "ف6", "ف7", "ف8", "ف9", "ف10", "ف11", "ف12", "ف13", "ف14", "ف15", "ف16",
            "ف17", "ف18", "ف19", "ف20", "ف21", "ف22", "ف23", "ف24", "ف25", "ف26", "ف27", "ف28", "ف29", "ف30", "ف31",
            "ف32", "ف33", "ف34", "ف35", "ف36", "ف37", "ف38", "ف39", "ف40", "ف41", "ف42", "ف43", "ف44", "ف45", "ف46", "ف47",
            "ف48", "ف49", "ف50", "ف51", "ف52", "ف53", "ف54", "ف55", "ف56", "ف57", "ف58", "ف59", "ف60", "ف61", "ف62", "ف63",
            "ف64", "ف65", "ف66", "ف67", "ف68", "ف69", "ف70", "ف71", "ف72", "ف73", "ف74", "ف75", "ف76", "ف77", "ف78",
            "ف79", "ف80", "ف81", "ف82", "ف83", "ف84", "ف85", "ف86", "ف87", "ف88", "ف89", "ف90"
        ];

        foreach($images as $key => $img) {
            Face::create(["icon" => $img, "key" => $keys[$key]]);
        }

    }
}
