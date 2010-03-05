<?php
 /**
 * @package modules
 * @copyright (C) 2002-2010 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link http://www.xaraya.com
 *
 * @subpackage shop Module
 * @link http://www.xaraya.com/index.php/release/eid/1031
 * @author potion <ryan@webcommunicate.net>
 */
/**
 *  Complete the order.  If all goes well, we'll submit the transaction to the payment gateway, save our own transaction record, and update customer info
 */
function shop_user_complete() {

	if (!isset($_SESSION['did_checkout'])) return;

	$cust = xarMod::APIFunc('shop','user','customerinfo');
	$data['cust'] = $cust;

	if (isset($_SESSION['products']) && $_SESSION['total'] > 0) {  //not sure we need these checks at this point

		$data['products'] = $_SESSION['products'];

		sys::import('modules.dynamicdata.class.objects.master');
		$transobject = DataObjectMaster::getObject(array('name' => 'shop_transactions'));

		$time = time();
		$_SESSION['time'] = $time;

		$myfields = array('first_name', 'last_name', 'street_addr', 'city_addr', 'state_addr', 'postal_code', 'card_type','card_num', 'cvv2', 'exp_date');

		foreach ($myfields as $field) {
			$isvalid = $transobject->properties[$field]->checkInput();
			if (!$isvalid) {
				$_SESSION['errors'][$field] = true;
			} else {
				unset($_SESSION['errors'][$field]); // in case we previously submitted invalid input in this field
			}
			${$field} = $transobject->properties[$field]->getValue();
			 if ($field != 'card_num') {
				//Save values to $_SESSION['checkout'] in case we need to re-display the form in user-checkout.xt 
				$_SESSION['checkout'][$field] = ${$field};
			 }
		}
		//If fields don't validate, re-display the checkout page
		if (!empty($_SESSION['errors'])) {
			xarResponse::Redirect(xarModURL('shop','user','checkout'));
			return;
		}

		//We'll use these fields for the payment gateway and for saving our own transaction record
		$transfields = array(
			'date' => $time,
			'customer' => $cust['id'], 
			'products' => serialize($data['products']),
			'total' => $_SESSION['total'],
			'first_name' => $first_name,
			'last_name' => $last_name,
			'street_addr' => $street_addr,
			'city_addr' => $city_addr,
			'state_addr' => $state_addr,
			'postal_code' => $postal_code,
			'card_type' => $card_type,
			'card_num' => $card_num,
			'cvv2' => $cvv2,
			'exp_date' => $exp_date
			); 

		$custobject = DataObjectMaster::getObject(array('name' => 'shop_customers'));
		$custobject->getItem(array('itemid' => $cust['id']));
		$custobject->properties['first_name']->setValue($first_name);
		$custobject->properties['last_name']->setValue($last_name);
		$custobject->properties['street_addr']->setValue($street_addr);
		$custobject->properties['city_addr']->setValue($city_addr);
		$custobject->properties['postal_code']->setValue($postal_code);
		$custobject->properties['state_addr']->setValue($state_addr);
		$custobject->updateItem();

		// update the name field in roles to use first and last name instead of email
		$rolesobject = DataObjectMaster::getObject(array('name' => 'roles_users'));
		$rolesobject->getItem(array('itemid' => $cust['id']));
		$rolesobject->properties['name']->setValue($first_name . ' ' . $last_name);
		$rolesobject->updateItem();

		/*****************************/
		/***** PAYMENT PROCESSING ****/
		/*****************************/

		$response = xarMod::APIFunc('shop','admin','handlepgresponse', array('transfields' => $transfields));
		
		if (is_numeric($response['trans_id'])) {
			$data['response'] = $response;
			$transfields['pg_transaction_id'] = $response['trans_id'];
			$tid = $transobject->createItem($transfields);
			unset($_SESSION['pg_response']);
			unset($_SESSION['checkout']); 
		} else {
			// There must be a problem...
			$pg_id = xarModVars::get('shop','pg_id');
			$pg_key = xarModVars::get('shop','pg_key');
			if (empty($pg_key) || empty($pg_id)) {
				$_SESSION['pg_response']['msg'] .= "<p style='color:red'><strong>Looks like you haven't set up a payment gateway yet.  <a href='".xarModURL('shop','admin','overview')."'>Please read this</a>.</strong></p>";
			}

			xarResponse::Redirect(xarModURL('shop','user','checkout'));
			return;
		}

	}

	$data['total'] = $_SESSION['total'];
	$data['tid'] = $tid;
	$data['date'] = date('F j, Y g:i a',$_SESSION['time']);
	
	//Might as well unset these now to make sure we can't re-submit the purchase
	unset($_SESSION['shop']);
	unset($_SESSION['products']);
	unset($_SESSION['did_checkout']);

	return $data;

}

?>