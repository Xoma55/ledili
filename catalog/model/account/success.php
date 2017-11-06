<?php
class ModelAccountSuccess extends Model {	
	public function getOrderTotalValue($order_status_id, $period=0) {
		$sql = "SELECT total FROM `" . DB_PREFIX . "order` WHERE customer_id = '" . (int)$this->customer->getId() . "' AND order_status_id = '" . (int)$order_status_id . "'";
		
		if ($period>0) {
			$sql .= " AND date_added>= DATE_ADD(CURDATE(), INTERVAL -'" . $period . "' DAY)";
		}
		
		$query = $this->db->query($sql);
	
		return $query->rows; 
	}
	
	public function editCustomer($customer_id, $customer_group_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
	
		//send notify email
		$group_query = $this->db->query("SELECT admin_notify, customer_notify FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		$group_name = $this->db->query("SELECT `name` FROM " . DB_PREFIX . "customer_group_description WHERE customer_group_id = '" . (int)$customer_group_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
		$query = $this->db->query("SELECT email FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
		$this->load->language('mail/customer_switch');
			
		if (VERSION<='2.0.1.1') {
			$mail = new Mail($this->config->get('config_mail'));
		} else {
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
		}
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		
		if ($group_query->row['customer_notify']) {
		
			$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
		
			$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";
	
			$message .= sprintf($this->language->get('text_login'), $group_name->row['name']) . "\n";
		
			$message .= $this->url->link('account/login', '', 'SSL') . "\n\n";
			$message .= $this->language->get('text_services') . "\n\n";
			$message .= $this->language->get('text_thanks') . "\n";
			$message .= $this->config->get('config_name');
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->setTo($query->row['email']);
			$mail->send();
		}
		if ($group_query->row['admin_notify']) {
		// Send to main admin email if new account email is enabled
			$subject = sprintf($this->language->get('text_admin_subject'), $this->config->get('config_name'), $group_name->row['name']);
		
			$message = sprintf($this->language->get('text_admin_content'), $query->row['email'], $group_name->row['name']) . "\n\n";
	
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->setTo($this->config->get('config_email'));
			$mail->send();
			
			// Send to additional alert emails if new account email is enabled
			$emails = explode(',', $this->config->get('config_mail_alert'));

			foreach ($emails as $email) {
				if (utf8_strlen($email) > 0 && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}
	}
	
	public function getCustomerGroup($customer_group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . (int)$customer_group_id . "'");

		return array(
			'reach_value' => $query->row['reach_value'],
			'period' => $query->row['period'],
			'reach_customer_group_id' => $query->row['reach_customer_group_id'],
			'reach_order_status_ids' => json_decode($query->row['reach_order_status_ids'], true),
			's_status' => $query->row['s_status'],
		);
	}
	
	public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT customer_group_id FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
		
		return $query->row['customer_group_id'];
	}
}