<?php

namespace wbebl\classes\controllers;

class Dashboard_Controller
{
    public function index()
    {
        $title = esc_html__("WordPress bulk bundle", WBEBL_NAME);
        $social_networks = [
            [
                'icon' => WBEBL_IMAGES_URL . "twitter.png",
                'link' => "https://twitter.com/ithemeland_co"
            ],
            [
                'icon' => WBEBL_IMAGES_URL . "instagram.png",
                'link' => "https://www.instagram.com/ithemeland_co/"
            ],
            [
                'icon' => WBEBL_IMAGES_URL . "envato.png",
                'link' => "https://codecanyon.net/user/ithemelandco"
            ],
            [
                'icon' => WBEBL_IMAGES_URL . "youtube.png",
                'link' => "https://www.youtube.com/channel/UCz2_8Kh3Jnr7fBAhGhSQGHw"
            ],
        ];
        include_once WBEBL_VIEWS_DIR . "dashboard/main.php";
    }
}
