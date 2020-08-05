<?php
error_reporting(0);
ini_set('display_errors',0);
class ControllerExtensionPaymentPlatiOnline extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/plationline');
		$this->document->setTitle($this->language->get('heading_title') . ' - ' . $this->language->get('text_PO_version'));
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$this->model_setting_setting->editSetting('payment_plationline', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token']. '&type=payment', true));
		}

		$data = $this->get_all_po_statuses();

		$data['heading_title'] = $this->language->get('heading_title');
		$data['link'] = $this->language->get('text_PO_image');
		$data['text_PO_version'] = $this->language->get('text_PO_version');
		$data['text_fill_required_info'] = $this->language->get('text_fill_required_info');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_f_login'] 						= $this->language->get('entry_f_login');
		$data['entry_rsa_auth'] 					= $this->language->get('entry_rsa_auth');
		$data['entry_rsa_itsn'] 					= $this->language->get('entry_rsa_itsn');
		$data['entry_iv_auth'] 						= $this->language->get('entry_iv_auth');
		$data['entry_iv_itsn'] 						= $this->language->get('entry_iv_itsn');
		$data['entry_relay_method'] 				= $this->language->get('entry_relay_method');
		$data['entry_itsn_method'] 					= $this->language->get('entry_itsn_method');
		$data['entry_info_itsn_url'] 				= $this->language->get('entry_info_itsn_url');
		$data['entry_test'] 						= $this->language->get('entry_test');
		$data['entry_total'] 						= $this->language->get('entry_total');
		$data['entry_order_status'] 				= $this->language->get('entry_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['f_login'])) {
			$data['error_f_login'] = $this->error['f_login'];
		} else {
			$data['error_f_login'] = '';
		}

		if (isset($this->error['rsa_auth'])) {
			$data['error_rsa_auth'] = $this->error['rsa_auth'];
		} else {
			$data['error_rsa_auth'] = '';
		}

		if (isset($this->error['rsa_itsn'])) {
			$data['error_rsa_itsn'] = $this->error['rsa_itsn'];
		} else {
			$data['error_rsa_itsn'] = '';
		}

		if (isset($this->error['iv_auth'])) {
			$data['error_iv_auth'] = $this->error['iv_auth'];
		} else {
			$data['error_iv_auth'] = '';
		}

		if (isset($this->error['iv_itsn'])) {
			$data['error_iv_itsn'] = $this->error['iv_itsn'];
		} else {
			$data['error_iv_itsn'] = '';
		}

		if (isset($this->error['relay_method'])) {
			$data['error_relay_method'] = $this->error['relay_method'];
		} else {
			$data['error_relay_method'] = '';
		}

		if (isset($this->error['itsn_method'])) {
			$data['error_itsn_method'] = $this->error['itsn_method'];
		} else {
			$data['error_itsn_method'] = '';
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], true),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'user_token=' . $this->session->data['user_token'], true),
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/payment/plationline', 'user_token=' . $this->session->data['user_token'], true),
      		'separator' => ' :: '
   		);

		$data['action'] = $this->url->link('extension/payment/plationline', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		if (isset($this->request->post['payment_plationline_f_login'])) {
			$data['payment_plationline_f_login'] = $this->request->post['payment_plationline_f_login'];
		} else {
			$data['payment_plationline_f_login'] = $this->config->get('payment_plationline_f_login');
		}

		if (isset($this->request->post['payment_plationline_rsa_auth'])) {
			$data['payment_plationline_rsa_auth'] = $this->request->post['payment_plationline_rsa_auth'];
		} else {
			$data['payment_plationline_rsa_auth'] = $this->config->get('payment_plationline_rsa_auth');
		}

		if (isset($this->request->post['payment_plationline_rsa_itsn'])) {
			$data['payment_plationline_rsa_itsn'] = $this->request->post['payment_plationline_rsa_itsn'];
		} else {
			$data['payment_plationline_rsa_itsn'] = $this->config->get('payment_plationline_rsa_itsn');
		}

		if (isset($this->request->post['payment_plationline_iv_auth'])) {
			$data['payment_plationline_iv_auth'] = $this->request->post['payment_plationline_iv_auth'];
		} else {
			$data['payment_plationline_iv_auth'] = $this->config->get('payment_plationline_iv_auth');
		}

		if (isset($this->request->post['payment_plationline_iv_itsn'])) {
			$data['payment_plationline_iv_itsn'] = $this->request->post['payment_plationline_iv_itsn'];
		} else {
			$data['payment_plationline_iv_itsn'] = $this->config->get('payment_plationline_iv_itsn');
		}

		if (isset($this->request->post['payment_plationline_relay_method'])) {
			$data['payment_plationline_relay_method'] = $this->request->post['payment_plationline_relay_method'];
		} else {
			$data['payment_plationline_relay_method'] = $this->config->get('payment_plationline_relay_method');
		}

		if (isset($this->request->post['payment_plationline_itsn_method'])) {
			$data['payment_plationline_itsn_method'] = $this->request->post['payment_plationline_itsn_method'];
		} else {
			$data['payment_plationline_itsn_method'] = $this->config->get('payment_plationline_itsn_method');
		}

		if (isset($this->request->post['payment_plationline_test'])) {
			$data['payment_plationline_test'] = $this->request->post['payment_plationline_test'];
		} else {
			$data['payment_plationline_test'] = $this->config->get('payment_plationline_test');
		}

		if (isset($this->request->post['payment_plationline_total'])) {
			$data['payment_plationline_total'] = $this->request->post['payment_plationline_total'];
		} else {
			$data['payment_plationline_total'] = $this->config->get('payment_plationline_total');
		}

		if (isset($this->request->post['payment_plationline_order_status_pending'])) {
			$data['payment_plationline_order_status_pending'] = $this->request->post['payment_plationline_order_status_pending'];
		} else {
			$data['payment_plationline_order_status_pending'] = $this->config->get('payment_plationline_order_status_pending');
		}

		if (isset($this->request->post['payment_plationline_order_status_approved'])) {
			$data['payment_plationline_order_status_approved'] = $this->request->post['payment_plationline_order_status_approved'];
		} else {
			$data['payment_plationline_order_status_approved'] = $this->config->get('payment_plationline_order_status_approved');
		}

		if (isset($this->request->post['payment_plationline_order_status_on_hold'])) {
			$data['payment_plationline_order_status_on_hold'] = $this->request->post['payment_plationline_order_status_on_hold'];
		} else {
			$data['payment_plationline_order_status_on_hold'] = $this->config->get('payment_plationline_order_status_on_hold');
		}

		if (isset($this->request->post['payment_plationline_order_status_decline'])) {
			$data['payment_plationline_order_status_decline'] = $this->request->post['payment_plationline_order_status_decline'];
		} else {
			$data['payment_plationline_order_status_decline'] = $this->config->get('payment_plationline_order_status_decline');
		}

		if (isset($this->request->post['payment_plationline_order_status_error'])) {
			$data['payment_plationline_order_status_error'] = $this->request->post['payment_plationline_order_status_error'];
		} else {
			$data['payment_plationline_order_status_error'] = $this->config->get('payment_plationline_order_status_error');
		}

		if (isset($this->request->post['payment_plationline_order_status_settled'])) {
			$data['payment_plationline_order_status_settled'] = $this->request->post['payment_plationline_order_status_settled'];
		} else {
			$data['payment_plationline_order_status_settled'] = $this->config->get('payment_plationline_order_status_settled');
		}

		if (isset($this->request->post['payment_plationline_order_status_pending_settled'])) {
			$data['payment_plationline_order_status_pending_settled'] = $this->request->post['payment_plationline_order_status_pending_settled'];
		} else {
			$data['payment_plationline_order_status_pending_settled'] = $this->config->get('payment_plationline_order_status_pending_settled');
		}

		if (isset($this->request->post['payment_plationline_order_status_credited'])) {
			$data['payment_plationline_order_status_credited'] = $this->request->post['payment_plationline_order_status_credited'];
		} else {
			$data['payment_plationline_order_status_credited'] = $this->config->get('payment_plationline_order_status_credited');
		}

		if (isset($this->request->post['payment_plationline_order_status_pending_credited'])) {
			$data['payment_plationline_order_status_pending_credited'] = $this->request->post['payment_plationline_order_status_pending_credited'];
		} else {
			$data['payment_plationline_order_status_pending_credited'] = $this->config->get('payment_plationline_order_status_pending_credited');
		}

		if (isset($this->request->post['payment_plationline_order_status_voided'])) {
			$data['payment_plationline_order_status_voided'] = $this->request->post['payment_plationline_order_status_voided'];
		} else {
			$data['payment_plationline_order_status_voided'] = $this->config->get('payment_plationline_order_status_voided_id');
		}

		if (isset($this->request->post['payment_plationline_order_status_pending_voided'])) {
			$data['payment_plationline_order_status_pending_voided'] = $this->request->post['payment_plationline_order_status_pending_voided'];
		} else {
			$data['payment_plationline_order_status_pending_voided'] = $this->config->get('payment_plationline_order_status_pending_voided');
		}

		if (isset($this->request->post['payment_plationline_order_status_cbk'])) {
			$data['payment_plationline_order_status_cbk'] = $this->request->post['payment_plationline_order_status_cbk'];
		} else {
			$data['payment_plationline_order_status_cbk'] = $this->config->get('payment_plationline_order_status_cbk');
		}

		if (isset($this->request->post['payment_plationline_order_status_expired'])) {
			$data['payment_plationline_order_status_expired'] = $this->request->post['payment_plationline_order_status_expired'];
		} else {
			$data['payment_plationline_order_status_expired'] = $this->config->get('payment_plationline_order_status_expired');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_plationline_geo_zone'])) {
			$data['payment_plationline_geo_zone'] = $this->request->post['payment_plationline_geo_zone'];
		} else {
			$data['payment_plationline_geo_zone'] = $this->config->get('payment_plationline_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_plationline_status'])) {
			$data['payment_plationline_status'] = $this->request->post['payment_plationline_status'];
		} else {
			$data['payment_plationline_status'] = $this->config->get('payment_plationline_status');
		}

		if (isset($this->request->post['payment_plationline_sort_order'])) {
			$data['payment_plationline_sort_order'] = $this->request->post['payment_plationline_sort_order'];
		} else {
			$data['payment_plationline_sort_order'] = $this->config->get('payment_plationline_sort_order');
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['is_secure'] = $this->config->get('config_secure');

		$this->response->setOutput($this->load->view('extension/payment/plationline', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/plationline')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_plationline_f_login']) {
			$this->error['f_login'] = $this->language->get('error_f_login');
		}

		if (!$this->request->post['payment_plationline_rsa_auth']) {
			$this->error['rsa_auth'] = $this->language->get('error_rsa_auth');
		}

		if (!$this->request->post['payment_plationline_rsa_itsn']) {
			$this->error['rsa_itsn'] = $this->language->get('error_rsa_itsn');
		}

		if (!$this->request->post['payment_plationline_iv_auth']) {
			$this->error['iv_auth'] = $this->language->get('error_iv_auth');
		}

		if (!$this->request->post['payment_plationline_iv_itsn']) {
			$this->error['iv_itsn'] = $this->language->get('error_iv_itsn');
		}

		if (!$this->request->post['payment_plationline_relay_method']) {
			$this->error['relay_method'] = $this->language->get('error_relay_method');
		}

		if (!$this->request->post['payment_plationline_itsn_method']) {
			$this->error['itsn_method'] = $this->language->get('error_itsn_method');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	public function install() {
		$this->createOrderStatuses();
	}

	private function get_all_po_statuses() {
		$this->load->language('extension/payment/plationline');
		$data = array();
		$data['entry_order_status_pending'] 			= $this->language->get('entry_order_status_pending');
		$data['entry_order_status_approved'] 			= $this->language->get('entry_order_status_approved');
		$data['entry_order_status_on_hold'] 			= $this->language->get('entry_order_status_on_hold');
		$data['entry_order_status_decline'] 			= $this->language->get('entry_order_status_decline');
		$data['entry_order_status_error'] 				= $this->language->get('entry_order_status_error');
		$data['entry_order_status_settled'] 			= $this->language->get('entry_order_status_settled');
		$data['entry_order_status_pending_settled'] 	= $this->language->get('entry_order_status_pending_settled');
		$data['entry_order_status_credited'] 			= $this->language->get('entry_order_status_credited');
		$data['entry_order_status_pending_credited'] 	= $this->language->get('entry_order_status_pending_credited');
		$data['entry_order_status_voided'] 				= $this->language->get('entry_order_status_voided');
		$data['entry_order_status_pending_voided'] 		= $this->language->get('entry_order_status_pending_voided');
		$data['entry_order_status_cbk'] 				= $this->language->get('entry_order_status_cbk');
		$data['entry_order_status_expired'] 			= $this->language->get('entry_order_status_expired');
		return $data;
	}

	private function createOrderStatuses() {
		$data = array();
		$lang_id = (int)$this->config->get('config_language_id');

		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$os = $this->get_all_po_statuses();
		$data = array_merge($data,$os);

		$raw_order_status_data[] = array('order_status' => array($lang_id => array('name' => $data['entry_order_status_pending'])));
		$raw_order_status_data[] = array('order_status' => array($lang_id => array('name' => $data['entry_order_status_approved'])));
		$raw_order_status_data[] = array('order_status' => array($lang_id => array('name' => $data['entry_order_status_on_hold'])));
		$raw_order_status_data[] = array('order_status' => array($lang_id => array('name' => $data['entry_order_status_decline'])));
		$raw_order_status_data[] = array('order_status' => array($lang_id => array('name' => $data['entry_order_status_error'])));
		$raw_order_status_data[] = array('order_status' => array($lang_id => array('name' => $data['entry_order_status_settled'])));
		$raw_order_status_data[] = array('order_status' => array($lang_id => array('name' => $data['entry_order_status_pending_settled'])));
		$raw_order_status_data[] = array('order_status' => array($lang_id => array('name' => $data['entry_order_status_credited'])));
		$raw_order_status_data[] = array('order_status' => array($lang_id => array('name' => $data['entry_order_status_pending_credited'])));
		$raw_order_status_data[] = array('order_status' => array($lang_id => array('name' => $data['entry_order_status_voided'])));
		$raw_order_status_data[] = array('order_status' => array($lang_id => array('name' => $data['entry_order_status_pending_voided'])));
		$raw_order_status_data[] = array('order_status' => array($lang_id => array('name' => $data['entry_order_status_cbk'])));
		$raw_order_status_data[] = array('order_status' => array($lang_id => array('name' => $data['entry_order_status_expired'])));

		foreach ($raw_order_status_data as $order_status_data) {
			$status_already_exists = false;
			foreach ($data['order_statuses'] as $existingOrderStatus) {
				if ($existingOrderStatus['name'] == $order_status_data['order_status'][$lang_id]['name']) {
					$status_already_exists = true;
				}
			}
			if (!$status_already_exists) {
				$this->model_localisation_order_status->addOrderStatus($order_status_data);
			}
		}
	}
}
?>
