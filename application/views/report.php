<div class="row">
	<div class="col-xs-12">
	   <div class="box">
	    <div class="box-header">
	    	<div class="col-xs-3">
		    	<div class="form-group">
		    		<select class="form-control" id="countries_rpt">
				      	<?php
				      	foreach ($countries as $country) {
				      	   ?>
				      	   <option value="<?= $country['code']; ?>"><?= $country['name']; ?></option>
				      	  <?php
				      	}
				      	?>
			      </select>
		    	</div>
	    	</div>
	    </div>
	    <div class="box-body">
			<figure class="highcharts-figure">
			    <div id="chart_container"></div>
			    <p class="highcharts-description" align="center"><span id="country_name"></span>Life expectancy at birth, total (years)</p>
			</figure>
	    </div>
	  </div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function () {
	populateFirstCountryData();
	$('#countries_rpt').on('change', function() {
	  var countryCode = this.value;
	  populateCountryData(countryCode);
	});
});

function populateFirstCountryData(){
	axios.get('get-first-country-code')
        .then((response) => {
		  var countryCode = response.data;
          populateCountryData(countryCode)
        }, (error) => {
          console.log(error);
        });
}

function populateCountryData(countryCode){
	axios.post('get-country-data', {country_code: countryCode})
        .then((response) => {
		  var response_data = response.data;
          var series_data = response_data.series_data;
          var data_source = response_data.data_source;
          var country_name = response_data.country_name;
          var last_update = response_data.last_update;
          populateChartData(series_data, data_source, last_update);
          $("#country_name").text(country_name+" ");
        }, (error) => {
          console.log(error);
        });
}

function populateChartData(series_data, data_source, last_update){
	var chart_data = {
	    chart: {
	        type: 'column'
	    },
	    title: {
	        align: 'left',
	        text: 'Title'
	    },
	    subtitle: {
	        align: 'left',
	        text: 'subtitle'
	    },
	    accessibility: {
	        announceNewData: {
	            enabled: true
	        }
	    },
	    xAxis: {
	        type: 'category'
	    },
	    yAxis: {
	        title: {
	            text: 'Total'
	        }

	    },
	    legend: {
	        enabled: false
	    },
	    plotOptions: {
	        series: {
	            borderWidth: 0,
	            dataLabels: {
	                enabled: true,
	                format: '{point.y:.1f}'
	            }
	        }
	    },

	    tooltip: {
	        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
	        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b><br/>'
	    },

	    series: null

	};
	chart_data.title.text = "Data Source: "+data_source;
	chart_data.subtitle.text = "Last Updated: "+last_update;
	chart_data.series = JSON.parse(series_data);
	Highcharts.chart('chart_container', chart_data);
}
</script>