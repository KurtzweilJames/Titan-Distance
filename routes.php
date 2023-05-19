<?php
$pgtitle = "Routes";
include("header.php");
?>
<div class="container">
    <div class="row">
        <div class="col-md-3 p-0">
            <ul class="list-group list-group-flush overflow-scroll" style="max-height:80vh">
                <?php
                $result = mysqli_query($con, "SELECT * FROM routes ORDER BY distance DESC");
                while ($row = mysqli_fetch_array($result)) {
                    echo '<li class="list-group-item" id="' . $row['id'] . '_toggle" onclick="viewRoute(' . $row['id'] . ')">' . $row['name'] . '<span class="badge bg-primary ms-2">' . $row['distance'] . ' mi</span></li>';
                }
                ?>
            </ul>
        </div>
        <div class="col-md-9 p-0" id="map" style="max-height:80vh">
        </div>
    </div>
</div>
<!-- Map -->
<script>
    mapboxgl.accessToken = '<?php echo $mapboxapikey; ?>';
    const map = new mapboxgl.Map({
        container: 'map',
        // Choose from Mapbox's core styles, or make your own style with Mapbox Studio
        style: 'mapbox://styles/jkurtzweil2/ckgacb5xw02fp19r16el5q4xc',
        center: [-87.8333, 42.087],
        zoom: 12,
        // maxBounds: mapboxgl.LngLatBounds((mapboxgl.LngLat(-87.905364, 42.142278), mapboxgl.LngLat(-87.779986, 42.063568)))
    });

    function viewRoute(id) {
        var listItem = document.getElementById(id + "_toggle");

        if (listItem.classList.contains("active") == true) {
            listItem.classList.remove("active");
            map.removeLayer(id + "_route");
            map.removeSource(id + "_route");
        } else {
            map.addSource(id + "_route", {
                type: 'geojson',
                data: '/api/route?id=' + id
            });

            map.addLayer({
                'id': id + "_route",
                'type': 'line',
                'source': id + "_route",
                'layout': {
                    'line-join': 'round',
                    'line-cap': 'round'
                },
                'paint': {
                    'line-color': '#073763',
                    'line-width': 8
                }
            });

            // map.addLayer({
            //     id: id,
            //     source: "route",
            //     type: 'line',
            //     'paint': {
            //         'line-width': 5,
            //         'line-color': '#073763',
            //         'line-opacity': 0.9
            //     }
            // });

            listItem.classList.add("active");
        }
    }
</script>
<?php include("footer.php"); ?>