<?php
class ControllerCustomCouriers extends Controller {
	private $error = array();

	public function index() {
		$this->load->language( 'custom/couriers' );
		$this->document->setTitle( $this->language->get( 'heading_title' ) );
		$this->load->model( 'custom/couriers' );
		
		$this->getList();
	}

	public function add() {
		$this->load->language('custom/couriers');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('custom/couriers');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_custom_couriers->addCourier($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('custom/couriers', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('custom/couriers');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('custom/couriers');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_custom_couriers->editCourier($this->request->get['courier_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('custom/couriers', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('custom/couriers');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('custom/couriers');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $courier_id) {
				$this->model_custom_couriers->deleteCourier($courier_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('custom/couriers', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}


	public function getList() {
		if ( isset( $this->request->get['sort'] ) ) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'cp.name';
		}

		if ( isset( $this->request->get['order'] ) ) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/couriers', 'token=' . $this->session->data['token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/couriers', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}

		$data['add'] = $this->url->link('custom/couriers/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('custom/couriers/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['couriers'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);
		
		$total_couriers = $this->model_custom_couriers->getTotalCouriers();

		$results = $this->model_custom_couriers->getCouriers($filter_data);

		foreach ($results as $result) {
			$data['couriers'][] = array(
				'courier_id' => $result['courier_id'],
				'name'       => $result['name'],
				'phone'      => $result['phone'],
				'price'      => $result['price'],
				'comment'    => $result['comment'],
				'status'     => $result['status'],
				'edit'       => $this->url->link( 'custom/couriers/edit', 'token=' . $this->session->data['token'] . '&courier_id=' . $result['courier_id'] . $url, 'SSL' )
			);
		}

		$data['heading_title'] = $this->language->get( 'heading_title' );
		$data['text_list']     = $this->language->get( 'text_list' );
		$data['button_save']   = $this->language->get( 'button_save' );
		$data['button_cancel'] = $this->language->get( 'button_cancel' );
		$data['button_add']    = $this->language->get( 'button_add' );
		$data['button_edit']   = $this->language->get( 'button_edit' );
		$data['button_delete'] = $this->language->get( 'button_delete' );

		$data['column_name']     = $this->language->get( 'column_name' );
		$data['column_phone']    = $this->language->get( 'column_phone' );
		$data['column_price']    = $this->language->get( 'column_price' );
		$data['column_comment']  = $this->language->get( 'column_comment' );
		$data['column_action']   = $this->language->get( 'column_action' );
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_no_results'] = $this->language->get( 'text_no_results' );

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name']    = $this->url->link( 'custom/couriers', 'token=' . $this->session->data['token'] . '&sort=cp.name' . $url, 'SSL' );
		$data['sort_phone']   = $this->url->link( 'custom/couriers', 'token=' . $this->session->data['token'] . '&sort=c.phone' . $url, 'SSL' );
		$data['sort_price']   = $this->url->link( 'custom/couriers', 'token=' . $this->session->data['token'] . '&sort=c.price' . $url, 'SSL' );
		$data['sort_comment'] = $this->url->link( 'custom/couriers', 'token=' . $this->session->data['token'] . '&sort=cp.comment' . $url, 'SSL' );

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination        = new Pagination();
		$pagination->total = $total_couriers;
		$pagination->page  = $page;
		$pagination->limit = $this->config->get( 'config_limit_admin' );
		$pagination->url   = $this->url->link( 'customer/couriers', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_couriers) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_couriers - $this->config->get('config_limit_admin'))) ? $total_couriers : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_couriers, ceil($total_couriers / $this->config->get('config_limit_admin')));

		$data['sort']  = $sort;
		$data['order'] = $order;


		$data['header']       = $this->load->controller('common/header');
		$data['column_left']  = $this->load->controller('common/column_left');
		$data['footer']       = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('custom/couriers.tpl', $data));
	}
	
	protected function getForm() {
		$data['heading_title'] = $this->language->get( 'heading_title' );
		$data['text_form']     = ! isset( $this->request->get['courier_id'] ) ? $this->language->get( 'text_add' ) : $this->language->get( 'text_edit' );
		$data['text_enabled']  = $this->language->get( 'text_enabled' );
		$data['text_disabled'] = $this->language->get( 'text_disabled' );
		$data['entry_name']    = $this->language->get( 'entry_name' );
		$data['entry_phone']   = $this->language->get( 'entry_phone' );
		$data['entry_price']   = $this->language->get( 'entry_price' );
		$data['entry_comment'] = $this->language->get( 'entry_comment' );
		$data['entry_status']  = $this->language->get( 'entry_status' );

		$data['button_save']   = $this->language->get( 'button_save' );
		$data['button_cancel'] = $this->language->get( 'button_cancel' );


		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}


		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('custom/couriers', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['courier_id'])) {
			$data['action'] = $this->url->link('custom/couriers/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('custom/couriers/edit', 'token=' . $this->session->data['token'] . '&courier_id=' . $this->request->get['courier_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('custom/couriers', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['courier_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$courier_info = $this->model_custom_couriers->getCourier($this->request->get['courier_id']);
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (isset($this->request->get['courier_id'])) {
			$data['name'] = $this->model_custom_couriers->getCourierDescription($this->request->get['courier_id']);
		} else {
			$data['name'] = array();
		}

		if (isset($this->request->post['phone'])) {
			$data['phone'] = $this->request->post['phone'];
		} elseif (!empty($courier_info)) {
			$data['phone'] = $courier_info['phone'];
		} else {
			$data['phone'] = '';
		}

		if (isset($this->request->post['price'])) {
			$data['price'] = $this->request->post['price'];
		} elseif (!empty($courier_info)) {
			$data['price'] = $courier_info['price'];
		} else {
			$data['price'] = '';
		}

		if (isset($this->request->post['comment'])) {
			$data['comment'] = $this->request->post['comment'];
		} elseif (isset($this->request->get['courier_id'])) {
			$data['comment'] = $this->model_custom_couriers->getCourierDescription($this->request->get['courier_id']);
		} else {
			$data['comment'] = array();
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($courier_info)) {
			$data['status'] = $courier_info['status'];
		} else {
			$data['status'] = '';
		}
		
		$data['header']       = $this->load->controller('common/header');
		$data['column_left']  = $this->load->controller('common/column_left');
		$data['footer']       = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('custom/courier_edit.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'custom/couriers')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['courier_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 128)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

//		if (($this->request->post['type'] == 'select' || $this->request->post['type'] == 'radio' || $this->request->post['type'] == 'checkbox')) {
//			if (!isset($this->request->post['custom_field_value'])) {
//				$this->error['warning'] = $this->language->get('error_type');
//			}
//
//			if (isset($this->request->post['custom_field_value'])) {
//				foreach ($this->request->post['custom_field_value'] as $custom_field_value_id => $custom_field_value) {
//					foreach ($custom_field_value['custom_field_value_description'] as $language_id => $custom_field_value_description) {
//						if ((utf8_strlen($custom_field_value_description['name']) < 1) || (utf8_strlen($custom_field_value_description['name']) > 128)) {
//							$this->error['custom_field_value'][$custom_field_value_id][$language_id] = $this->language->get('error_custom_value');
//						}
//					}
//				}
//			}
//		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'custom/couriers')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

}
