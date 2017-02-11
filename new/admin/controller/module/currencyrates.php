<?php
class ControllerModuleCurrencyRates extends Controller {
	
	
	// Module Unifier
	private $moduleName = 'CurrencyRates';
	private $moduleNameSmall = 'currencyrates';
	private $moduleData_module = 'currencyrates_module';
	private $moduleModel = 'model_module_currencyrates';
	// Module Unifier

    public function index() { 
		
		// Module Unifier
		$this->data['moduleName'] = $this->moduleName;
		$this->data['moduleNameSmall'] = $this->moduleNameSmall;
		$this->data['moduleData_module'] = $this->moduleData_module;
		$this->data['moduleModel'] = $this->moduleModel;
		// Module Unifier
	 
        $this->load->language('module/'.$this->data['moduleNameSmall']);
        $this->load->model('module/'.$this->data['moduleNameSmall']);
        $this->load->model('setting/store');
        $this->load->model('localisation/language');
        $this->load->model('design/layout');
		
        $catalogURL = $this->getCatalogURL();
 
        $this->document->addScript($catalogURL . 'admin/view/javascript/ckeditor/ckeditor.js');
        $this->document->addScript($catalogURL . 'admin/view/javascript/'.$this->data['moduleNameSmall'].'/bootstrap/js/bootstrap.min.js');
        $this->document->addStyle($catalogURL  . 'admin/view/javascript/'.$this->data['moduleNameSmall'].'/bootstrap/css/bootstrap.min.css');
        $this->document->addStyle($catalogURL  . 'admin/view/stylesheet/'.$this->data['moduleNameSmall'].'/font-awesome/css/font-awesome.min.css');
        $this->document->addStyle($catalogURL  . 'admin/view/stylesheet/'.$this->data['moduleNameSmall'].'/'.$this->data['moduleNameSmall'].'.css');
        $this->document->setTitle($this->language->get('heading_title'));

        if(!isset($this->request->get['store_id'])) {
           $this->request->get['store_id'] = 0; 
        }
	
        $store = $this->getCurrentStore($this->request->get['store_id']);
		
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) { 
		
            if (!$this->user->hasPermission('modify', 'module/'.$this->data['moduleNameSmall'])) {
                $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
            }

			if(!isset($this->request->post[$this->data['moduleData_module']])) {
				$this->request->post[$this->data['moduleData_module']] = array();
			}
			
			$settingsGet = $this->{$this->data['moduleModel']}->getSetting($this->data['moduleName']);
			$settingsGet[$this->data['moduleName']] = $this->request->post[$this->data['moduleName']];
			if($settingsGet[$this->data['moduleName']]["Enabled"] == "no")
			{
				
				$direct = dirname(DIR_APPLICATION)."/vqmod/xml/currency_rates.xml";
				if(file_exists($direct = $direct)){
					rename($direct,dirname(DIR_APPLICATION)."/vqmod/xml/currency_rates.xml_");
				}
				
			}else if($settingsGet[$this->data['moduleName']]["Enabled"] == "yes"){
				
				$direct = dirname(DIR_APPLICATION)."/vqmod/xml/currency_rates.xml_";
				if(file_exists($direct = $direct)){
					rename($direct,dirname(DIR_APPLICATION)."/vqmod/xml/currency_rates.xml");
				}
				
			}
			
			$this->{$this->data['moduleModel']}->editSetting($this->data['moduleName'], $settingsGet, $this->request->post['store_id']);
			
            $this->session->data['success'] = $this->language->get('text_success');
            
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }

        if (isset($this->error['code'])) {
            $this->data['error_code'] = $this->error['code'];
        } else {
            $this->data['error_code'] = '';
        }

        $this->data['breadcrumbs']   = array();
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
        );
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
        );
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/'.$this->data['moduleNameSmall'], 'token=' . $this->session->data['token'], 'SSL'),
        );


        $languageVariables = array(
		    // Main
			'heading_title',
			'error_permission',
			'text_success',
			'text_enabled',
			'text_disabled',
			'button_cancel',
			'save_changes',
			'text_default',
			'text_module',
			// Control panel
            'entry_code',
			'entry_code_help',
            'text_content_top', 
            'text_content_bottom',
            'text_column_left', 
            'text_column_right',
            'entry_layout',         
            'entry_position',       
            'entry_status',         
            'entry_sort_order',     
            'entry_layout_options',  
            'entry_position_options',
			'entry_action_options',
            'button_add_module',
            'button_remove',
			// Custom CSS
			'custom_css',
            'custom_css_help',
            'custom_css_placeholder',
			// Module depending
			'wrap_widget',
			'wrap_widget_help',
			'text_products',
			'text_products_help',
			'text_image_dimensions',
			'text_image_dimensions_help',
			'text_pixels',
			'text_panel_name',
			'text_panel_name_help',
			'text_products_small',
			'show_add_to_cart',
			'show_add_to_cart_help'
        );
		
        foreach ($languageVariables as $languageVariable) {
            $this->data[$languageVariable] = $this->language->get($languageVariable);
        }
 
        $this->data['stores'] = array_merge(array(0 => array('store_id' => '0', 'name' => $this->config->get('config_name') . ' (' . $this->data['text_default'].')', 'url' => HTTP_SERVER, 'ssl' => HTTPS_SERVER)), $this->model_setting_store->getStores());
        $this->data['error_warning']          = '';  
        $this->data['languages']              = $this->model_localisation_language->getLanguages();
        $this->data['store']                  = $store;
        $this->data['token']                  = $this->session->data['token'];
        $this->data['action']                 = $this->url->link('module/'.$this->data['moduleNameSmall'], 'token=' . $this->session->data['token'], 'SSL');
        $this->data['cancel']                 = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['data']                   = $this->{$this->data['moduleModel']}->getSetting($this->data['moduleNameSmall'], $store['store_id']);
        $this->data['modules']				= $this->{$this->data['moduleModel']}->getSetting($this->data['moduleData_module'], $store['store_id']);
        $this->data['layouts']                = $this->model_design_layout->getLayouts();
        $this->data['catalog_url']			= $catalogURL;
		
		// Module Unifier
		$this->data['moduleData'] = $this->data['data'][$this->data['moduleName']];
		
		
		// Module Unifier
		
        $this->template = 'module/'.$this->data['moduleNameSmall'].'.tpl';
        $this->children = array('common/header', 'common/footer');
        $this->response->setOutput($this->render());
    }

    private function getCatalogURL() {
        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $storeURL = HTTPS_CATALOG;
        } else {
            $storeURL = HTTP_CATALOG;
        } 
        return $storeURL;
    }

    private function getServerURL() {
        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $storeURL = HTTPS_SERVER;
        } else {
            $storeURL = HTTP_SERVER;
        } 
        return $storeURL;
    }

    private function getCurrentStore($store_id) {    
        if($store_id && $store_id != 0) {
            $store = $this->model_setting_store->getStore($store_id);
        } else {
            $store['store_id'] = 0;
            $store['name'] = $this->config->get('config_name');
            $store['url'] = $this->getCatalogURL(); 
        }
        return $store;
    }
    
    public function install() {
	    $this->load->model('module/'.$this->moduleNameSmall);
	    $this->{$this->moduleModel}->install();
		
		$this->load->model('design/layout');
		$layouts = $this->model_design_layout->getLayouts();
		$settings = array();
		$settings[$this->moduleData_module] = array();
		
		foreach ($layouts as $layout){
			$settings[$this->moduleData_module][] = array(
				'layout_id'=>$layout['layout_id'],
				'position'=>"content_top",
				'status'=>1,
				'sort_order'=>0
			);
		}
		
		$settings['CurrencyRates'] = array();
		$settings['CurrencyRates']['Enabled'] = "no";
		$settings['CurrencyRates']['LicenseCode'] = "";
		
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting($this->moduleName,$settings);
		
		$direct = dirname(DIR_APPLICATION)."/vqmod/xml/currency_rates.xml";
		if(file_exists($direct = $direct)){
			rename($direct,dirname(DIR_APPLICATION)."/vqmod/xml/currency_rates.xml_");
		}
    }
    
    public function uninstall() {
    	$this->load->model('setting/setting');
		
		$this->load->model('setting/store');
		$this->model_setting_setting->deleteSetting($this->moduleData_module,0);
		$stores=$this->model_setting_store->getStores();
		foreach ($stores as $store) {
			$this->model_setting_setting->deleteSetting($this->moduleData_module, $store['store_id']);
		}
		
        $this->load->model('module/'.$this->moduleNameSmall);
        $this->{$this->moduleModel}->uninstall();
		
		$cache = DIR_CACHE.'currencyrates.cachetime';
		if(file_exists($cache)){
			unlink ( $cache );
		}
		
		$direct = dirname(DIR_APPLICATION)."/vqmod/xml/currency_rates.xml";
		if(file_exists($direct = $direct)){
			rename($direct,dirname(DIR_APPLICATION)."/vqmod/xml/currency_rates.xml_");
		}
    }
	
	public function updateRate() {
		$settingsGet = $this->config->get($this->moduleName);
		if(!empty($settingsGet["Enabled"]) && $settingsGet["Enabled"] == "no"){
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
		set_error_handler(array($this, "errorHandler"));
		try{
			$xml = simplexml_load_file($URL);
		}catch(Exception $x){
			$this->session->data['error_warning'] = "Warning: You could not update rates!";
			$this->redirect($this->url->link('localisation/currency', 'token=' . $this->session->data['token'] . '&test=true', 'SSL'));
		}
		restore_error_handler();
		$code = "";
				foreach($xml->results->rate as $rate){
					$attributes = $rate->attributes();
					$code = str_replace($default_currency, "", $attributes["id"]);
					if($code == ""){
						$code = $default_currency;
					}
					$rates = (Array)$rate->Rate;
					$currencyCodes[$code]["value"] = (float)$rates[0];
					$this->model_localisation_currency->editCurrency($currencyCodes[$code]["currency_id"], $currencyCodes[$code]);
				}
		$this->session->data['success'] = "Success: You have updated the rates!'";
		$this->redirect($this->url->link('localisation/currency', 'token=' . $this->session->data['token'] . '&test=true', 'SSL'));
	}
	
	private function errorHandler($errno, $errstr, $errfile, $errline) {
		throw new Exception($errstr);
	}
}

?>