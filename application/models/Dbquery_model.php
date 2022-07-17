<?php
/**
 * Created by IntelliJ IDEA.
 * User: EMANUEL JULIUS
 * Date: 15/07/2022
 * Time: 16:36 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

set_time_limit(0);
ini_set('memory_limit', '-1');
date_default_timezone_set('Africa/Dar_Es_Salaam');

class Dbquery_model extends CI_Model {

	private $local_conn;

	public function __construct() {
		$this->local_conn = $this->load->database('local_conn',TRUE);
	}

	function saveCountry($name, $code){
		$sql = "INSERT IGNORE INTO country(name, code) VALUES('$name', '$code')";
		$this->local_conn->query($sql);
		return $this->local_conn->insert_id();
	}

	function saveDataSource($name, $last_updated) {
		$sql = "INSERT INTO data_source(name, last_update) VALUES('$name', '$last_updated')";
		$this->local_conn->query($sql);
		return $this->local_conn->insert_id();
	}

	function saveKeyword($name){
		$sql = "INSERT IGNORE INTO keyword(name) VALUES('$name')";
		$this->local_conn->query($sql);
		return $this->local_conn->insert_id();
	}

	function getKeyword($keyword_name){
		$sql = "SELECT * from keyword WHERE name='$keyword_name'";
		return $this->tbl_single_result($sql, $this->local_conn);
	}

	function saveIndicator($total, $year, $keyword_id, $country_id, $data_source_id){
		$sql = "INSERT INTO indicator(total, year, keyword_id, country_id, data_source_id) VALUES($total, $year, $keyword_id, $country_id, $data_source_id)";
		$this->local_conn->query($sql);
		return $this->local_conn->insert_id();
	}

	function getDistinctIndicatorYears(){
		$sql = "SELECT DISTINCT year from indicator ORDER BY year ASC";
		return $this->tbl_all_results($sql, $this->local_conn);
	}

	function getAllCountries(){
		$sql = "SELECT * from country ORDER BY id ASC";
		return $this->tbl_all_results($sql, $this->local_conn);
	}

	function getCountryByCode($country_code){
		$sql = "SELECT * from country WHERE code='$country_code'";
		return $this->tbl_single_result($sql, $this->local_conn);
	}

	function getFirstCountry(){
		$sql = "SELECT * from country ORDER BY id ASC LIMIT 1";
		return $this->tbl_single_result($sql, $this->local_conn);
	}

	function getCountryIndicators($country_id, $data_source_id){
		$sql = "SELECT indct.id, kywrd.name, indct.total, indct.year, indct.keyword_id, indct.country_id, indct.data_source_id from indicator indct INNER JOIN keyword kywrd ON indct.keyword_id=kywrd.id WHERE indct.country_id=$country_id AND indct.data_source_id=$data_source_id ORDER BY year ASC";
		return $this->tbl_all_results($sql, $this->local_conn);
	}

	function getLatestDataSource(){
		$sql = "SELECT * from data_source ORDER BY id DESC LIMIT 1";
		return $this->tbl_single_result($sql, $this->local_conn);
	}

	function dbQuery($sql){
		$this->local_conn->query($sql);
	}

	function tbl_single_result($sql, $db){
		return $db->query($sql)->row_array();
	}

	function tbl_all_results($sql, $db){
		return $db->query($sql)->result_array();
	}

}