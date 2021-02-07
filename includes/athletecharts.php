<?php
include("db.php");


$result = mysqli_query($con,"SELECT meet,points FROM overalltf WHERE profile='". $profile ."' AND points IS NOT NULL ORDER BY date ASC");
while($row = mysqli_fetch_array($result)) {
    $labelpp[] = $meets[$row['meet']];
    $datapp[] = $row['points'];
} 

$result = mysqli_query($con,"SELECT meet,percent FROM overallxc WHERE profile='". $profile ."' AND percent IS NOT NULL ORDER BY date ASC");
while($row = mysqli_fetch_array($result)) {
    $dataxcp[] = $row['percent'];
    $labelxcp[] = $meets[$row['meet']];
}

$result = mysqli_query($con,"SELECT meet,percent FROM overalltf WHERE profile='". $profile ."' AND percent IS NOT NULL ORDER BY date ASC");
while($row = mysqli_fetch_array($result)) {
    $datatfp[] = $row['percent'];
    $labeltfp[] = $meets[$row['meet']];
}

$result = mysqli_query($con,"SELECT meet,time,name FROM overallxc WHERE profile='". $profile ."' AND distance='3mi' ORDER BY date ASC");
while($row = mysqli_fetch_array($result)) {
list($min, $sec) = explode(":", $row['time']);
$data3mi[] = ($min*60)+$sec;
$label3mi[] = $meets[$row['meet']];
}

$result = mysqli_query($con,"SELECT meet,time,name FROM overallxc WHERE profile='". $profile ."' AND distance='2mi' ORDER BY date ASC");
while($row = mysqli_fetch_array($result)) {
list($min, $sec) = explode(":", $row['time']);
$data2mi[] = ($min*60)+$sec;
$label2mi[] = $meets[$row['meet']];
}

$result = mysqli_query($con,"SELECT meet,time,name FROM overalltf WHERE profile='". $profile ."' AND distance='3200m' ORDER BY date ASC");
while($row = mysqli_fetch_array($result)) {
list($min, $sec) = explode(":", $row['time']);
$data3200m[] = ($min*60)+$sec;
$label3200m[] = $meets[$row['meet']];
}

$result = mysqli_query($con,"SELECT meet,time,name FROM overalltf WHERE profile='". $profile ."' AND distance='1600m' ORDER BY date ASC");
while($row = mysqli_fetch_array($result)) {
list($min, $sec) = explode(":", $row['time']);
$data1600m[] = ($min*60)+$sec;
$label1600m[] = $meets[$row['meet']];
}

$result = mysqli_query($con,"SELECT meet,time,name FROM overalltf WHERE profile='". $profile ."' AND distance='800m' ORDER BY date ASC");
while($row = mysqli_fetch_array($result)) {
list($min, $sec) = explode(":", $row['time']);
$data800m[] = ($min*60)+$sec;
$label800m[] = $meets[$row['meet']];
}
?>
<script>
var ctx = document.getElementById('ppChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($labelpp); ?>,
        datasets: [{
            label: 'Performance Points',
            data: <?php echo json_encode($datapp); ?>,
            borderDash: [5, 5],
            backgroundColor: [
                'rgba(7, 55, 99, 0.1)',
            ],
            borderColor: [
                'rgba(7, 55, 99, 1)',
            ],
            borderWidth: 2,
            lineTension: 0.4,
            pointBackgroundColor: '#ffd700',
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        scales: {
            xAxes: [{
                ticks: {
                    display: false
                }
            }],
            yAxes: [{
                ticks: {
                    min: 0,
                    max: 1000
                }
            }]
        },
        legend: {
            display: false
        },

        title: {
            display: true,
            text: 'All-Time Performance Point Progression (Higher is Better)'
        }
    }
});
</script>
<script>
var ctx = document.getElementById('xcpercentChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($labelxcp); ?>,
        datasets: [{
            label: '% Finish',
            data: <?php echo json_encode($dataxcp); ?>,
            borderDash: [5, 5],
            backgroundColor: [
                'rgba(7, 55, 99, 0.1)',
            ],
            borderColor: [
                'rgba(7, 55, 99, 1)',
            ],
            borderWidth: 2,
            lineTension: 0.4,
            pointBackgroundColor: '#ffd700',
            pointRadius: 5,
            pointHoverRadius: 7,
            fill: 'start'
        }]
    },
    options: {
        responsive: true,
        scales: {
            xAxes: [{
                ticks: {
                    display: false
                }
            }],
            yAxes: [{
                ticks: {
                    min: 0,
                    max: 100
                }
            }]
        },
        legend: {
            display: false
        },
        title: {
            display: true,
            text: 'Cross Country Finish Place Percentage (Lower is Better)'
        }
    }
});
</script>
<script>
var ctx = document.getElementById('tfpercentChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($labeltfp); ?>,
        datasets: [{
            label: '% Finish',
            data: <?php echo json_encode($datatfp); ?>,
            borderDash: [5, 5],
            backgroundColor: [
                'rgba(7, 55, 99, 0.1)',
            ],
            borderColor: [
                'rgba(7, 55, 99, 1)',
            ],
            borderWidth: 2,
            lineTension: 0.4,
            pointBackgroundColor: '#ffd700',
            pointRadius: 5,
            pointHoverRadius: 7,
            fill: 'start'
        }]
    },
    options: {
        responsive: true,
        scales: {
            xAxes: [{
                ticks: {
                    display: false
                }
            }],
            yAxes: [{
                ticks: {
                    min: 0,
                    max: 100
                }
            }]
        },
        legend: {
            display: false
        },
        title: {
            display: true,
            text: 'Track Finish Place Percentage (Lower is Better)'
        }
    }
});
</script>
<script>
var ctx = document.getElementById('3miChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($label3mi); ?>,
        datasets: [{
            label: 'Time',
            data: <?php echo json_encode($data3mi); ?>,
            borderDash: [5, 5],
            backgroundColor: [
                'rgba(7, 55, 99, 0.1)',
            ],
            borderColor: [
                'rgba(7, 55, 99, 1)',
            ],
            borderWidth: 2,
            lineTension: 0.4,
            pointBackgroundColor: '#ffd700',
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        scales: {
            xAxes: [{
                ticks: {
                    display: false
                }
            }],
            yAxes: {
                type: 'time',
                time: {
                    unit: 'second',
                    displayFormats: {
                        quarter: 'mm:ss.SSS'
                    },
                    parser: function(date) {
                        return moment.duration(date, "seconds").format("mm:ss.SSS");
                    }
                }
            },
        },
        legend: {
            display: false
        },
        title: {
            display: true,
            text: '3mi Time in Seconds (Lower is Faster)'
        }
    }
});
</script>
<script>
var ctx = document.getElementById('2miChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($label2mi); ?>,
        datasets: [{
            label: 'Time',
            data: <?php echo json_encode($data2mi); ?>,
            borderDash: [5, 5],
            backgroundColor: [
                'rgba(7, 55, 99, 0.1)',
            ],
            borderColor: [
                'rgba(7, 55, 99, 1)',
            ],
            borderWidth: 2,
            lineTension: 0.4,
            pointBackgroundColor: '#ffd700',
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        scales: {
            xAxes: [{
                ticks: {
                    display: false
                }
            }],
            yAxes: {
                type: 'time',
                time: {
                    unit: 'second',
                    displayFormats: {
                        quarter: 'mm:ss.SSS'
                    },
                    parser: function(date) {
                        return moment.duration(date, "seconds").format("mm:ss.SSS");
                    }
                }
            },
        },
        legend: {
            display: false
        },
        title: {
            display: true,
            text: '2mi Time in Seconds (Lower is Faster)'
        }
    }
});
</script>
<script>
var ctx = document.getElementById('3200mChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($label3200m); ?>,
        datasets: [{
            label: 'Time',
            data: <?php echo json_encode($data3200m); ?>,
            borderDash: [5, 5],
            backgroundColor: [
                'rgba(7, 55, 99, 0.1)',
            ],
            borderColor: [
                'rgba(7, 55, 99, 1)',
            ],
            borderWidth: 2,
            lineTension: 0.4,
            pointBackgroundColor: '#ffd700',
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        scales: {
            xAxes: [{
                ticks: {
                    display: false
                }
            }],
            yAxes: {
                type: 'time',
                time: {
                    unit: 'second',
                    displayFormats: {
                        quarter: 'mm:ss.SSS'
                    },
                    parser: function(date) {
                        return moment.duration(date, "seconds").format("mm:ss.SSS");
                    }
                }
            },
        },
        legend: {
            display: false
        },
        title: {
            display: true,
            text: '3200m Time in Seconds (Lower is Faster)'
        }
    }
});
</script>
<script>
var ctx = document.getElementById('1600mChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($label1600m); ?>,
        datasets: [{
            label: 'Time',
            data: <?php echo json_encode($data1600m); ?>,
            borderDash: [5, 5],
            backgroundColor: [
                'rgba(7, 55, 99, 0.1)',
            ],
            borderColor: [
                'rgba(7, 55, 99, 1)',
            ],
            borderWidth: 2,
            lineTension: 0.4,
            pointBackgroundColor: '#ffd700',
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        scales: {
            xAxes: [{
                ticks: {
                    display: false
                }
            }],
            yAxes: {
                type: 'time',
                time: {
                    unit: 'second',
                    displayFormats: {
                        quarter: 'mm:ss.SSS'
                    },
                    parser: function(date) {
                        return moment.duration(date, "seconds").format("mm:ss.SSS");
                    }
                }
            },
        },
        legend: {
            display: false
        },
        title: {
            display: true,
            text: '1600m Time in Seconds (Lower is Faster)'
        }
    }
});
</script>
<script>
var ctx = document.getElementById('800mChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($label800m); ?>,
        datasets: [{
            label: 'Time',
            data: <?php echo json_encode($data800m); ?>,
            borderDash: [5, 5],
            backgroundColor: [
                'rgba(7, 55, 99, 0.1)',
            ],
            borderColor: [
                'rgba(7, 55, 99, 1)',
            ],
            borderWidth: 2,
            lineTension: 0.4,
            pointBackgroundColor: '#ffd700',
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        scales: {
            xAxes: [{
                ticks: {
                    display: false
                }
            }],
            yAxes: {
                type: 'time',
                time: {
                    unit: 'second',
                    displayFormats: {
                        quarter: 'mm:ss.SSS'
                    },
                    parser: function(date) {
                        return moment.duration(date, "seconds").format("mm:ss.SSS");
                    }
                }
            },
        },
        legend: {
            display: false
        },
        title: {
            display: true,
            text: '800m Time in Seconds (Lower is Faster)'
        }
    }
});
</script>