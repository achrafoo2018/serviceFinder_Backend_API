<?php

use Illuminate\Database\Seeder;

class SpecialitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('specialities')->delete();

        \DB::table('specialities')->insert(array(
            0 => array("speciality" => "Web Development"),
            1 => array("speciality" => "Software Development"),
            2 => array("speciality" => "Web Design"),
            3 => array("speciality" => "Digital Marketing"),
            4 => array("speciality" => "Music & Audio"),
            5 => array("speciality" => "Marketing Strategy"),
            6 => array("speciality" => "Social Media Marketing"),
            7 => array("speciality" => "SEO"),
            8 => array("speciality" => "Social Media Advertising"),
            9 => array("speciality" => "Public Relations"),
            10 => array("speciality" => "Content Marketing"),
            11 => array("speciality" => "Podcast Marketing"),
            12 => array("speciality" => "Video Marketing"),
            13 => array("speciality" => "Display Advertising"),
            14 => array("speciality" => "Surveys"),
            15 => array("speciality" => "Web Analytics"),
            16 => array("speciality" => "Book & eBook Marketing"),
            17 => array("speciality" => "Influencer Marketing"),
            18 => array("speciality" => "Community Management"),
            19 => array("speciality" => "Local SEO"),
            20 => array("speciality" => "Domain Research"),
            21 => array("speciality" => "E-Commerce Marketing"),
            22 => array("speciality" => "Affiliate Marketing"),
            21 => array("speciality" => "Mobile Marketing & Advertising"),
            22 => array("speciality" => "Music Promotion"),
            23 => array("speciality" => "Web Traffic"),
            24 => array("speciality" => "Text Message Marketing")
        ));
    }
}
