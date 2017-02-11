<?php
class ModelModuleCurrencyRates extends Model {
  
  	public function getSetting($group, $store_id) {
    	$data = array(); 
    	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `group` = '" . $this->db->escape($group) . "'");
    	foreach ($query->rows as $result) {
      		if (!$result['serialized']) {
        		$data[$result['key']] = $result['value'];
      		} else {
        		$data[$result['key']] = unserialize($result['value']);
      		}
    	} 
    	return $data;
  	}

	public function editCurrency($currency_id, $data) {

		$this->db->query("UPDATE " . DB_PREFIX . "currency SET title = '" . $this->db->escape($data['title']) . "', code = '" . $this->db->escape($data['code']) . "', symbol_left = '" . $this->db->escape($data['symbol_left']) . "', symbol_right = '" . $this->db->escape($data['symbol_right']) . "', decimal_place = '" . $this->db->escape($data['decimal_place']) . "', value = '" . $this->db->escape($data['value']) . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE currency_id = '" . (int)$currency_id . "'");



		$this->cache->delete('currency');

	}
}
?>