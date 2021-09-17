<?php
include("config.php");
?>
<!DOCTYPE html>
<html lang="en-us">

<head>
    <title>Titan Distance Routes</title>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link href="https://fonts.googleapis.com/css2?family=Balthazar&display=swap" rel="stylesheet" />

    <script src='https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.css' rel='stylesheet' />

    <!-- Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/icons/favicon-16x16.png">
    <link rel='manifest' href='/manifest.json'>
    <link rel="mask-icon" href="/assets/icons/safari-pinned-tab.svg" color="#ffd700">
    <link rel="shortcut icon" href="/assets/icons/favicon.ico">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="msapplication-config" content="/assets/icons/browserconfig.xml">
    <meta name="theme-color" content="#073763">

    <style>
    body {
        margin: 0px;
        border: 0px;
        padding: 0px;
        font-family: "Balthazar", serif;
        color: #073763;
    }

    .subheading {
        margin-top: 5px;
        background-color: #073763;
        padding-top: 5px;
        padding-bottom: 5px;
        color: #fff;
    }

    @media screen and (min-width: 600px) {
        #map {
            height: 100vh;
            position: absolute;
            width: 80%;
            margin-left: 20%;
        }

        #sidebar {
            display: inline-block;
            vertical-align: top;
            float: left;
            text-align: center;
            width: 20%;
            overflow-y: scroll;
            height: 100vh;
            background-color: #fefefe;
        }

        .logo {
            margin-top: 25px;
        }
    }

    @media screen and (max-width: 600px) {
        #sidebar {
            vertical-align: top;
            text-align: center;
            overflow: scroll;
            height: 30vh;
            background-color: #fefefe;
        }

        #map {
            height: 70vh;
            position: relative;
        }
    }

    .heading {
        text-decoration: underline;
    }

    .route {
        margin-left: 10px;
        font-size: 18px;
    }

    .route:hover {
        color: #007bff;
    }

    .route .active:hover {}

    .active {
        color: #ffd700;
    }

    .miles {
        display: inline-block;
        min-width: 1em;
        margin-left: 10px;
        padding: 0.4em;
        border-radius: 20%;
        background: #073763;
        color: #fff;
    }

    .gbs-marker {
        background-image: url('/assets/logos/GBSAthletics.svg');
        background-size: cover;
        width: 50px;
        cursor: pointer;
    }
    </style>
</head>

<body onload="getURL()">
    <div id="sidebar">
        <a href="https://titandistance.com"><img src="/assets/logos/color.svg" alt="Titan Distance" class="logo"
                width="75%" /></a>
        <!-- RUNNING ROUTES -->
        <h1 class="subheading">Running Routes</h1>
        <h2 class="heading">7-10+ Mile Routes</h2>
        <h3 class="route" id="waterpumpright" onclick="viewRoute('waterpumpright')">
            Water Pump Right<span class="miles">10.1 mi</span>
        </h3>
        <h3 class="route" id="waterpumpleft" onclick="viewRoute('waterpumpleft')">
            Water Pump Left<span class="miles">10.0 mi</span>
        </h3>
        <h3 class="route" id="woodoaks" onclick="viewRoute('woodoaks')">
            Wood Oaks<span class="miles">9.1 mi</span>
        </h3>
        <h3 class="route" id="loyolahill" onclick="viewRoute('loyolahill')">
            Loyola Hill<span class="miles">8.4 mi</span>
        </h3>
        <h3 class="route" id="technyprairie" onclick="viewRoute('technyprairie')">
            Techny Prairie<span class="miles">8.1 mi</span>
        </h3>
        <h3 class="route" id="waltersright" onclick="viewRoute('waltersright')">
            Walters Right<span class="miles">7.7 mi</span>
        </h3>
        <h3 class="route" id="downtownglenview" onclick="viewRoute('downtownglenview')">
            Downtown Glenview<span class="miles">7.6 mi</span>
        </h3>
        <h3 class="route" id="talltrees" onclick="viewRoute('talltrees')">
            Tall Trees<span class="miles">7.2 mi</span>
        </h3>
        <h3 class="route" id="technybasin" onclick="viewRoute('technybasin')">
            Techny Basin<span class="miles">7.0 mi</span>
        </h3>
        <h2 class="heading">4-7 Mile Routes</h2>
        <h3 class="route" id="clearing" onclick="viewRoute('clearing')">
            Clearing<span class="miles">6.3 mi</span>
        </h3>
        <h3 class="route" id="scottforesman" onclick="viewRoute('scottforesman')">
            Scott Foresman<span class="miles">5.6 mi</span>
        </h3>
        <h3 class="route" id="gallerypark" onclick="viewRoute('gallerypark')">
            Gallery Park<span class="miles">5.3 mi</span>
        </h3>
        <h3 class="route" id="golfcourse" onclick="viewRoute('golfcourse')">
            Golf Course<span class="miles">4.9 mi</span>
        </h3>
        <h3 class="route" id="gbnandback" onclick="viewRoute('gbnandback')">
            GBN and Back<span class="miles">4.8 mi</span>
        </h3>
        <h3 class="route" id="westparkpool" onclick="viewRoute('westparkpool')">
            West Park Pool<span class="miles">4.6 mi</span>
        </h3>
        <h3 class="route" id="centralviaflick" onclick="viewRoute('centralviaflick')">
            Central via Flick<span class="miles">4.2 mi</span>
        </h3>
        <h3 class="route" id="indianridge" onclick="viewRoute('indianridge')">
            Indian Ridge<span class="miles">4.2 mi</span>
        </h3>
        <h3 class="route" id="waterpump" onclick="viewRoute('waterpump')">
            Water Pump<span class="miles">4.0 mi</span>
        </h3>
        <h2 class="heading">1-4 Mile Routes</h2>
        <h3 class="route" id="plaza" onclick="viewRoute('plaza')">
            Plaza<span class="miles">3.3 mi</span>
        </h3>
        <h3 class="route" id="willow" onclick="viewRoute('willow')">
            Willow<span class="miles">3.15 mi</span>
        </h3>
        <h3 class="route" id="flickpark" onclick="viewRoute('flickpark')">
            Flick Park<span class="miles">3.1 mi</span>
        </h3>
        <h3 class="route" id="miller" onclick="viewRoute('miller')">
            Miller<span class="miles">3.0 mi</span>
        </h3>
        <h3 class="route" id="crestwood" onclick="viewRoute('crestwood')">
            Crestwood<span class="miles">2.15 mi</span>
        </h3>
        <h3 class="route" id="xccourse" onclick="viewRoute('xccourse')">
            XC Course<span class="miles">1.0 mi</span>
        </h3>
        <h3 class="route" id="dinosaur" onclick="viewRoute('dinosaur')">
            Dinosaur<span class="miles">0.9 mi</span>
        </h3>
    </div>
    <div id="map"></div>


    <!-- Map -->
    <script>
    const vw = Math.max(
        document.documentElement.clientWidth || 0,
        window.innerWidth || 0
    );

    if (vw < 600) {
        var zoom = 12;
    } else {
        var zoom = 13.87;
    }

    mapboxgl.accessToken =
        '<?php echo $mapboxapikey; ?>';
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/jkurtzweil2/ckgacb5xw02fp19r16el5q4xc',
        center: [-87.8333, 42.087],
        maxBounds: mapboxgl.LngLatBounds((mapboxgl.LngLat(-87.905364, 42.142278), mapboxgl.LngLat(-87.779986,
            42.063568))),
        zoom: zoom
    });

    var marker = new mapboxgl.Marker()
        .setLngLat([-87.850, 42.089])
        .addTo(map);
    marker.className = 'gbs-marker';

    // Add geolocate control to the map.
    map.addControl(
        new mapboxgl.GeolocateControl({
            positionOptions: {
                enableHighAccuracy: true
            },
            trackUserLocation: true
        })
    );
    map.addControl(new mapboxgl.NavigationControl());

    function viewRoute(route) {
        var element = document.getElementById(route);

        if (element.classList.contains("active") == true) {
            element.classList.remove("active");
            map.removeLayer(route);
            map.removeSource(route);
        } else {
            map.addSource(route, {
                type: 'geojson',
                data: '/assets/geojson/' + route + '.geojson'
            });

            map.addLayer({
                id: route,
                source: route,
                type: 'line',
                'paint': {
                    'line-width': 5,
                    'line-color': '#073763',
                    'line-opacity': 0.9
                }
            });

            element.classList.add("active");
        }
    }

    function getURL() {
        prevroute = "";
        var query = window.location.search.substring(1);
        if (query !== "") {
            viewRoute(query);
        }
    }
    </script>
</body>

</html>