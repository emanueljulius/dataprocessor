<?php
defined('BASEPATH') OR exit('No direct script access allowed');

set_time_limit(0);
ini_set('memory_limit', '-1');
date_default_timezone_set('Africa/Dar_Es_Salaam');

class Welcome extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('Dbquery_model');
		$this->load->helper('url');
	}

	public function index(){
		$data['page_title'] = "Upload";
		$this->load->view('templates/header', $data);
		$this->load->view('upload');
		$this->load->view('templates/footer');
	}

	public function view(){
		$data['page_title'] = "View Data";
		$data['data_headers'] = $this->get_data_headers();
		$data_source = $this->Dbquery_model->getLatestDataSource();
		if($data_source == null){
			$data_source_id = 0;
			$data_source = array();
			$data_source['name'] = "";
			$data_source['last_update'] = "";
		}else{
			$data_source_id = $data_source['id'];
		}
		$data['data_source'] = $data_source;
		$data['data_source_values'] = $this->get_data_source_values($data_source_id);
		$this->load->view('templates/header', $data);
		$this->load->view('view');
		$this->load->view('templates/footer');
	}

	public function report(){
		$data['page_title'] = "Data Source Report";
		$data['countries'] = $this->Dbquery_model->getAllCountries();
		$this->load->view('templates/header', $data);
		$this->load->view('report');
		$this->load->view('templates/footer');
	}

	function getCountryData(){
		$request_data_raw = urldecode(file_get_contents("php://input"));
		$decoded_raw_data = json_decode($request_data_raw);
		$country_code = $decoded_raw_data->country_code;
		$country = $this->Dbquery_model->getCountryByCode($country_code);
		$country_name = $country['name'];
		$country_id = $country['id'];
		$data_source = $this->Dbquery_model->getLatestDataSource();
		$data_source_id = $data_source['id'];
		$series_data = $this->get_chart_series_data($country_id, $data_source_id);
		$data_source_name = $data_source['name'];
		$last_update = $data_source['last_update'];
		$response = array();
		$response['data_source'] = $data_source_name;
		$response['country_name'] = $country_name;
		$response['series_data'] = $series_data;
		$response['last_update'] = $last_update;
		echo json_encode($response);
	}

	function getFirstCountryCode(){
		$country = $this->Dbquery_model->getFirstCountry();
		$country_code = $country['code'];
		echo json_encode($country_code);
	}

	public function doUpload() {
        if (isset($_FILES['file']['name'])) {
        	$name = $_FILES['file']['name'];
            $path = "uploads/";
            $path = $path . basename($_FILES['file']['name']);
            $fileType = pathinfo($path, PATHINFO_EXTENSION);
            if ($fileType === "csv") {
            	if (move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
            		$file = $path;
					$handle = fopen($path, "r");
					$data_headers;
					$data_source = "";
					$last_updated = "";
					$data_source_id = 0;
					$i = 1;
					while (($data = fgetcsv($handle, 10000, ",")) !== FALSE){
						if($i == 1){
							$data_source = $data[1];
						}else if($i == 2){
							$last_updated = $data[1];
							$last_updated = $this->format_date($last_updated);
							$data_source_id = $this->Dbquery_model->saveDataSource($data_source, $last_updated);
						}else if($i == 3){
							$data_headers = $data;
							++$i;
							continue;
						}else if($i > 3){
							$this->insertRowData($data, $i, $data_headers, $data_source_id);
						}
					 	++$i;
					}
					fclose($handle);
					//delete the file after done reading records
					unlink($file);
					redirect('index.php/view');
            	}else{
            		echo "File did not upload";
            	}
            }else{
            	echo "File type must be csv";
            }
        }else{
        	echo "Please include csv file";
        }
	}

	private function insertRowData($data, $pos, $data_headers, $data_source_id){
		$name = "";
		$code = "";
		$indicator_name = "";
		$year = 0;
		$total = 0;
		$country_id = 0;
		foreach ($data as $key => $value) {
			//data cell key and value
			switch ($key) {
				case 0:
					$name = $data[0];
					$name = str_replace("'", "\'", $name);
					break;
				case 1:
					$code = $data[1];
					$country_id = $this->Dbquery_model->saveCountry($name, $code);
					if(!$country_id){
						//country id null probably because its already uploaded(as we use insert ignore with unique country code)
						$country = $this->Dbquery_model->getCountryByCode($code);
						if($country){
							$country_id = $country['id'];
						}
					}
					break;
				case 2:
					$indicator_name = $data[2];
					break;
				case $key > 2:
					$year = $data_headers[$key];
					$total = $data[$key];
					$total = ($total != "") ? $total : 0;
					$keyword_id = $this->Dbquery_model->saveKeyword($indicator_name);
					if(!$keyword_id){
						//keyword_id is null means keyword exists(as we use insert ignore with unique indicator name)
						$keyword = $this->Dbquery_model->getKeyword($indicator_name);
						if($keyword){
							$keyword_id = $keyword['id'];
						}
					}
					$this->Dbquery_model->saveIndicator($total, $year, $keyword_id, $country_id, $data_source_id);
					break;
				default:
					break;
			}
		}
	}

	private function get_data_headers(){
		$data_headers = array();
		$data_headers[0] = "Country Name";
		$data_headers[1] = "Country Code";
		$data_headers[2] = "Indicator Name";
		$indicator_years = $this->Dbquery_model->getDistinctIndicatorYears();
		foreach ($indicator_years as $indicator_year) {
			$year = $indicator_year['year'];
			array_push($data_headers, $year);
		}
		return $data_headers;
	}

	private function get_chart_series_data($country_id, $data_source_id){
	    $series_data = '[{"name": "Life expectancy at birth","colorByPoint": true,';
		$series_data .= '"data": [';
		$indicators = $this->Dbquery_model->getCountryIndicators($country_id, $data_source_id);
		$i = 0;
		foreach ($indicators as $indicator) {
			$year = $indicator['year'];
			$total = $indicator['total'];
			if($i){
				$series_data .= ',';
			}
			$series_data .= '{';
			$series_data .= '"name": "'.$year.'",';
			$series_data .= '"y": '.$total.',';
			$series_data .= '"drilldown": "'.$year.'"';
			$series_data .= '}';
			++$i;
		}
		$series_data .= ']}]';
		return $series_data;
	}

	private function get_data_source_values($data_source_id){
		$countries = $this->Dbquery_model->getAllCountries();
		$data_source_values = array();	
		foreach ($countries as $country) {
			$country_data = array();
			$country_id = $country['id'];
			$country_data['name'] = $country['name'];
			$country_data['code'] = $country['code'];
			$indicators = $this->Dbquery_model->getCountryIndicators($country_id, $data_source_id);
			foreach ($indicators as $indicator) {
				$country_data['indicator'] = $indicator['name'];
				$country_data[$indicator['year']] = $indicator['total'];
			}
			array_push($data_source_values, $country_data);
		}
		return $data_source_values;
	}

	private function format_date($date_str){
		$date = date_create($date_str);
		return date_format($date,"Y-m-d");
	}

}