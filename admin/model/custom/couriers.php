<?php
class ModelCustomCouriers extends Model {
	
	public function addCourier($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "courier SET phone = '" . $this->db->escape($data['courier_phone']) . "', price = '" . (float)$data['courier_price'] . "', status = '" . (int)$data['courier_status'] . "'");

		$courier_id = $this->db->getLastId();

		foreach ($data['courier_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "courier_description SET
			courier_id = '" . (int)$courier_id . "',
			language_id = '" . (int)$language_id . "',
			name = '" . $this->db->escape($value['name']) . "',
			comment = '" . $this->db->escape($value['comment']) . "'");
		}
	}

	public function editCourier($courier_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "courier SET phone = '" . $this->db->escape($data['courier_phone']) . "', price = '" . (float)$data['courier_price'] . "', status = '" . (int)$data['courier_status'] . "' WHERE courier_id = '" . (int)$courier_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "courier_description WHERE courier_id = '" . (int)$courier_id . "'");

		foreach ($data['courier_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "courier_description SET
			courier_id = '" . (int)$courier_id . "',
			language_id = '" . (int)$language_id . "',
			name = '" . $this->db->escape($value['name']) . "',
			comment = '" . $this->db->escape($value['comment']) . "'");
		}

//		$courier_id = $this->db->getLastId();

//		foreach ($data['courier_name'] as $language_id => $value) {
//			$this->db->query("UPDATE " . DB_PREFIX . "courier_description SET name = '" . $this->db->escape($value['courier_name']) . "' WHERE courier_id = '" . (int)$courier_id . "' AND language_id = '" . (int)$language_id . "'");
//		}

	}

	public function deleteCourier($courier_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "courier WHERE courier_id = '" . (int)$courier_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "courier_description WHERE courier_id = '" . (int)$courier_id . "'");
	}

	public function getCouriers($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "courier` c LEFT JOIN " . DB_PREFIX . "courier_description cp ON (c.courier_id = cp.courier_id) WHERE cp.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND cp.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'cp.name',
			'c.phone',
			'c.price',
			'cp.comment'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY cp.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getCourier($courier_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "courier` c LEFT JOIN " . DB_PREFIX . "courier_description cp ON (c.courier_id = cp.courier_id) WHERE c.courier_id = '" . (int)$courier_id . "' AND cp.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getCourierDescription($courier_id) {
		$courier_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "courier_description WHERE courier_id = '" . (int)$courier_id . "'");

		foreach ($query->rows as $result) {
			$courier_data[$result['language_id']] = array(
				'name' => $result['name'],
				'comment' => $result['comment']
				);
		}

		return $courier_data;
	}


	public function getTotalCouriers() {
		$query = $this->db->query("SELECT COUNT(*) as total FROM `" . DB_PREFIX . "courier`");

		return $query->row['total'];
	}

}