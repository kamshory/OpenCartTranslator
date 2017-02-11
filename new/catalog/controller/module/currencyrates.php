<?php 
class ControllerModuleCurrencyRates extends Controller  {
	// Module Unifier
	private $moduleName = 'CurrencyRates';
	private $moduleNameSmall = 'currencyrates';
	private $moduleData_module = 'currencyrates_module';
	private $moduleModel = 'model_module_currencyrates';
	// Module Unifier

    public function index() {
		
		$settingsGet = $this->config->get($this->moduleName);
		
		if(!empty($settingsGet["Enabled"]) && $settingsGet["Enabled"] == "no"){
			return;
		}
		
		if($this->isCurrencyUpdated()){
			return;
		}
		
		$currencyCodes = array();
		$this->load->model('localisation/currency');
		$this->load->model('module/currencyrates');
		$currencyCodes = $this->model_localisation_currency->getCurrencies();
		$default_currency = $this->config->get('config_currency');
		
		$test = array();
		$text_begin = '"'.$default_currency;
		$text_end = '", ';
		$text_final = "";
		
		foreach($currencyCodes as $code=>$value){
			$text_final .= $text_begin . $code . $text_end;
		}
		
		$text_final = rtrim($text_final, ", ");
		$URL = "http://query.yahooapis.com/v1/public/yql?q=select * from yahoo.finance.xchange where pair in (".$text_final.")&env=store://datatables.org/alltableswithkeys";
		$xml = simplexml_load_file($URL);
		$code = "";
			foreach($xml->results->rate as $rate){
				$attributes = $rate->attributes();
				$code = str_replace($default_currency, "", $attributes["id"]);
				if($code == ""){
					$code = $default_currency;
				}
				$rates = (Array)$rate->Rate;
				$currencyCodes[$code]["value"] = (float)$rates[0];
				$this->model_module_currencyrates->editCurrency($currencyCodes[$code]["currency_id"], $currencyCodes[$code]);
			}
	}
	
	private function isCurrencyUpdated(){
		$cache_file = DIR_CACHE.'currencyrates.cachetime';
		if(file_exists($cache_file)){
			$lastUpdate = (int)file_get_contents($cache_file);
			$check = time() - $lastUpdate;
			if($check < 86400 && date("d", $lastUpdate) == date("d")){
					return true;
			}
		}else{
			file_put_contents($cache_file, time());
		}
		return false;
	}
}
?>