
var graphController = function($scope, $http) {

    $scope.isLoading = true;
    $scope.noData = false;
    $scope.showChart = false;
    $scope.totalVisits = 0;
    $scope.totalPageViews = 0;
    $scope.profileName = '';
    $scope.trackingCode = '';

    $http.get(graphDataUrl)
        .success(function (data) {

            $scope.isLoading = false;

            if(data) {
                $scope.showChart = true;
                $scope.totalVisits = data.totalVisits;
                $scope.totalPageViews = data.totalPageViews;
                $scope.profileName = data.profileName;
                $scope.trackingCode = data.trackingCode;
                loadGraph(data.visitsPerDay);
            }
            else {
                $scope.noData = true;
            }

        })
        .error(function (data, status, headers, config) {
            //  Do some error handling here
        });

    function loadGraph(visitsPerDay)
    {
        var ctx = document.getElementById("gaGraphCanvas");

        var labels = [];
        var data = [];

        for(var i = 0; i < visitsPerDay.length; i++)
        {
            var day = visitsPerDay[i];
            var date = day.date;
            var visits = day.visits;

            labels.push(date);
            data.push(visits);
        }

        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label : "",
                    data: data,
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: graphColor,
                    borderColor: graphColor,
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: graphColor,
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 5,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: graphColor,
                    pointHoverBorderColor: graphColor,
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    spanGaps: false
                }]
            },
            options: {
                title : {
                    display:false
                },
                legend : {
                    display:false
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }],
                    xAxes: [{
                        display:false,
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
    }
};