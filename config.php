<?php
//Set Variables below that will be used on the site

//Team Name & Configuration
$headerimage = "/assets/images/header/titanalumni.jpg";
$splash = "none"; # Set to "none" if you don't want anything

//Current Season/Sport/etc.
$currentseason = "Cross Country 2024";
$currentsport = "xc";
$currentyear = "2024";
$currentshort = "xc24";

//List of Teams
$teams = array("none", "Varsity", "Junior Varsity", "Sophomore", "Freshmen", "Frosh/Soph", "Open", "Junior Varsity 2", "Unknown");
$abbreviations = array("none", "v", "jv", "so", "fr", "fs", "o", "jv2", "unknown");

//List of Seasons
$seasons = array("Cross Country 2024", "Track 2024", "Cross Country 2023", "Track 2023", "Cross Country 2022", "Track 2022", "Cross Country 2021", "Track 2021", "Cross Country 2020", "Track 2020", "Cross Country 2019", "Track 2019", "Cross Country 2018", "Track 2018", "Cross Country 2017", "Track 2017", "Cross Country 2016", "Track 2016", "Cross Country 2015", "Community");

//Badges
$badges = array(1 => ["bg-csl", "CSL", "Central Suburban League Conference"], 2 => ["bg-ihsa", "<img src='/assets/icons/ihsa.svg' height='11px' alt='IHSA'>", "IHSA State Series Competition"], 3 => ["bg-info", "TT", "Time Trial"]);

//Home Venues
$homevenues = array("John Davis Titan Stadium" => "stadium", "David P. Pasquini Fieldhouse" => "fieldhouse", "xccourse" => "Cross Country Course");

//Tags
$tags = array(array("name" => "IQ", "desc" => "Individual Qualifier", "color" => "bg-warning"), array("name" => "TQ", "desc" => "Team Qualifier", "color" => "bg-warning"), array("name" => "All-Conf", "desc" => "All Conference", "color" => "bg-csl"));

//Result Statuses
$officials = ["Event has Not Started", "Official Results (F.A.T.)", "Official Results (Hand Timed)", "Unofficial Results", "In Progress", "Partial Results (F.A.T.)", "Partial Results (Hand Timed)"];

//API Keys
$gmapapikey = "{api-key}";
$mapboxapikey = "{api-key}";
$googletapmanagerkey = "{api-key}";
$onesignalappid = "{api-key}";

//Events
$allevents = ["100m", "200m", "400m", "800m", "1600m", "3200m", "110m HH", "300m IH"];
$allrelays = ["4x100m", "4x200m", "4x400m", "4x800m"];
$allfield = ["High Jump", "Triple Jump"];
$trackevents = ["3200m" => "3200m Run", "1600m" => "1600m Run", "1000m" => "1000m Run", "800m" => "800m Run", "400m" => "400m Dash", "300mIH" => "300m Intermediate Hurdles", "300m" => "300m Dash", "200m" => "200m Dash", "160m" => "160m Dash", "110mHH" => "110m High Hurdles", "110m IH" => "110m Intermediate Hurdles", "100m" => "100m Dash", "60m" => "60m Dash", "60mHH" => "60m High Hurdles", "50mLH" => "50m Low Hurdles", "55mIH" => "55m Intermediate Hurdles", "55mHH" => "55m High Hurdles", "55mLH" => "55m Low Hurdles", "50m" => "50m Dash", "SP" => "Shot Put", "DT" => "Discus", "HJ" => "High Jump", "PV" => "Pole Vault", "LJ" => "Long Jump", "TJ" => "Triple Jump", "4x1600m" => "4x1600m Relay", "4x800m" => "4x800m Relay", "4x400m" => "4x400m Relay", "4x240m" => "4x2 Lap Relay", "4x200m" => "4x200m Relay", "4x160m" => "4x1 Lap Relay", "4x100m" => "4x100m Relay", "DMR" => "Distance Medley Relay", "SMR" => "Sprint Medley Relay", "55m" => "55m Dash", 'Throwers' => 'Throwers Relay', '800mSMR' => '800m Sprint Medley Relay', '1600mSMR' => '1600m Spring Medley Relay', 'LHR' => 'Low Hurdle Relay', 'HHR' => 'High Hurdle Relay', '60yLH' => "60 Yard Low Hurdles", "1500m" => "1500m Run", "1mi" => "1 Mile Run", "50y" => "50y Dash", "240m" => "240m Dash", "110mSHH" => "Shuttle High Hurdles", "110mSLH" => "Shuttle Low Hurdles"];
