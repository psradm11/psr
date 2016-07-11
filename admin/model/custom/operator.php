<?php
class ModelCustomOperator extends Model {
	
	public function addOperator($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "operator SET status = '" . (int)$data['operator_status'] . "'");

		$operator_id = $this->db->getLastId();

		foreach ($data['operator_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "operator_description SET
			operator_id = '" . (int)$operator_id . "',
			language_id = '" . (int)$language_id . "',
			name = '" . $this->db->escape($value['name']) . "'");
		}
	}

	public function editOperator($operator_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "operator SET status = '" . (int)$data['operator_status'] . "' WHERE operator_id = '" . (int)$operator_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "operator_description WHERE operator_id = '" . (int)$operator_id . "'");

		foreach ($data['operator_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "operator_description SET
			operator_id = '" . (int)$operator_id . "',
			language_id = '" . (int)$language_id . "',
			name = '" . $this->db->escape($value['name']) . "'");
		}

	}

	public function deleteOperator($operator_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "operator WHERE operator_id = '" . (int)$operator_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "operator_description WHERE operator_id = '" . (int)$operator_id . "'");
	}

	public function getOperators($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "operator o LEFT JOIN " . DB_PREFIX . "operator_description od ON (o.operator_id = od.operator_id) WHERE od.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND od.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'od.name'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY od.name";
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

	public function getOperator($operator_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "operator o LEFT JOIN " . DB_PREFIX . "operator_description od ON (o.operator_id = od.operator_id) WHERE o.operator_id = '" . (int)$operator_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getOperatorDescription($operator_id) {
		$operator_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "operator_description WHERE operator_id = '" . (int)$operator_id . "'");

		foreach ($query->rows as $result) {
			$operator_data[$result['language_id']] = array(
				'name' => $result['name']
				);
		}

		return $operator_data;
	}


	public function getTotalOperators() {
		$query = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "operator");

		return $query->row['total'];
	}

}