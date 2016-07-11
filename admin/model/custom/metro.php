<?php
class ModelCustomMetro extends Model {

	public function addMetro($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "operator SET status = '" . (int)$data['status'] . "'");

		$metro_id = $this->db->getLastId();

		foreach ($data['metro'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "metro_description SET
			metro_id = '" . (int)$metro_id . "',
			language_id = '" . (int)$language_id . "',
			name = '" . $this->db->escape($value['name']) . "'");
		}
	}

	public function editMetro($metro_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "metro SET status = '" . (int)$data['status'] . "' WHERE metro_id = '" . (int)$metro_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "metro_description WHERE metro_id = '" . (int)$metro_id . "'");

		foreach ($data['metro'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "metro_description SET
			metro_id = '" . (int)$metro_id . "',
			language_id = '" . (int)$language_id . "',
			name = '" . $this->db->escape($value['name']) . "'");
		}

	}

	public function deleteMetro($metro_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "metro WHERE metro_id = '" . (int)$metro_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "metro_description WHERE metro_id = '" . (int)$metro_id . "'");
	}

	public function getAllMetro($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "metro m LEFT JOIN " . DB_PREFIX . "metro_description md ON (m.metro_id = md.metro_id) WHERE md.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND md.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'md.name'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY md.name";
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

	public function getMetro($metro_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "metro m LEFT JOIN " . DB_PREFIX . "metro_description md ON (m.metro_id = md.metro_id) WHERE m.metro_id = '" . (int)$metro_id . "' AND md.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getMetroDescription($metro_id) {
		$metro_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "metro_description WHERE metro_id = '" . (int)$metro_id . "'");

		foreach ($query->rows as $result) {
			$metro_data[$result['language_id']] = array(
				'name' => $result['name']
			);
		}

		return $metro_data;
	}


	public function getTotalMetro() {
		$query = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "metro");

		return $query->row['total'];
	}

}