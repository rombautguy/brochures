<?php
/* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
 * THIS NOTICE MUST REMAIN AT THE TOP OF THIS SCRIPT
 * 
 * This script is owned by Cloverswift Solutions and is not to be
 * distributed without prior authorization.  By installing this script on
 * your site you confirm that you are authorized to do so and have the 
 * written permission of Cloverswift Solutions.
 *
 * Please contact webdesign@cloverswift.com if you have any questions or
 * visit http://www.cloverswift.com
 *
 * -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */



class AccountsTable extends SwiftTable
{
	private $account_id;
	
	public static $THIS_TABLE = 'accounts';
	
	public static $MIN_ACCOUNT_ID = 51236;
	
	public static $SIGNUP_KEY = 'WM5Gf47C';

	public static $required_fields = array('accountid', 'firstname', 'lastname', 'accountemail', 'office_id', 'company', 'baddr1', 'bcity', 'bstate', 'bzip', 'bcounty', 'bphone', 'biphone');
	
	public static $license_lookup_states = array(
		'AL' => 'AL',
		'AR' => 'AR',
		'CA' => 'CA',
		'CO' => 'CO',
		'CT' => 'CT',
		'FL' => 'FL',
		'IA' => 'IA',
		'ID' => 'ID',
		'IL' => 'IL',
		'IN' => 'IN',
		'KY' => 'KY',
		//'LA' => 'LA', // 2018-8-22 Lookup reported broken, site changed
		'MD' => 'MD',
		'MI' => 'MI',
		//'MO' => 'MO', // License office names were including lengthy status and extra stuff
		//'ND' => 'ND', // 2019-01-08 Website updated, format no longer the same
		//'NE' => 'NE', // 2018-12-11 Reported Chrome browser hang issues when using lookup
		'NJ' => 'NJ',
		'NY' => 'NY',
		'OH' => 'OH',
		'OK' => 'OK',
		//'PA' => 'PA', // 2018-7-10 No office information in lookup so remove it
		'SD' => 'SD',
		'TX' => 'TX',
		//'WI' => 'WI', // 2018-12-21 Need to implement firms (and data is not always current)
		//'WY' => 'WY', // 2019-01-09 Website updated, format no longer the same
		'PR' => 'PR'
	);

	public static $all_fields = array
	(
		'accountid' => "int(11) unsigned NOT NULL auto_increment",
		'created' => "datetime NOT NULL default '0000-00-00 00:00:00'",
		'modified' => "datetime NOT NULL default '0000-00-00 00:00:00'",
		'accountemail' => "varchar(255) collate utf8_unicode_ci NOT NULL default ''",
		'accountpw' => "varchar(255) collate utf8_unicode_ci NOT NULL",
		'auth_super' => "tinyint(1) unsigned NOT NULL default '0'",
		'auth_agent' => "tinyint(1) unsigned NOT NULL default '0'",
		'auth_appraise' => "tinyint(1) unsigned NOT NULL default '0'",
		'auth_search_tax' => "tinyint(1) unsigned NOT NULL default '0'",
		'last_login' => "datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'last login date and time'",
		'auth_office' => "tinyint(1) unsigned NOT NULL default '0' COMMENT 'does this account own other accounts?'",
		'office_id' => "int(10) unsigned NOT NULL default '0' COMMENT 'the account id who owns this account'",
		'refer_id' => "int(10) unsigned NOT NULL default '0'",
		'refer_name' => "varchar(64) collate utf8_unicode_ci NOT NULL",
		'point_total' => "int(10) unsigned NOT NULL default '0' COMMENT 'referral point total'",
		'upgrade_pts' => "tinyint(3) unsigned NOT NULL default '0'",
		'facebook_pts' => "tinyint(3) unsigned NOT NULL default '0'",
		'twitter_pts' => "tinyint(3) unsigned NOT NULL default '0'",
		'member_terms' => "tinyint(1) unsigned NOT NULL default '0'",
		'list_terms' => "tinyint(1) unsigned NOT NULL default '0'",
		'rets_pt2' => "tinyint(1) unsigned NOT NULL default '0'",
		'rets_rdc' => "tinyint(1) unsigned NOT NULL default '0'",
		'joindate' => "date NOT NULL default '0000-00-00'",
		'renew_date' => "date NOT NULL default '0000-00-00' COMMENT 'date last renewed membership'",
		'join_ip' => "varchar(32) collate utf8_unicode_ci NOT NULL",
		'join_port' => "int(10) unsigned NOT NULL",
		'signup_source' => "enum('public','admin','import','unknown') collate utf8_unicode_ci NOT NULL default 'admin' COMMENT 'how did this user sign up for an account?'",
		'source_agent_id' => 'varchar',
		'source_group_id' => 'varchar',
		'agent_license' => "varchar(32) collate utf8_unicode_ci NOT NULL",
		'appraiser_license' => "varchar(32) collate utf8_unicode_ci NOT NULL",
		'accountplan' => "enum('basic','standard', 'premium','tax','public') collate utf8_unicode_ci NOT NULL default 'basic'",
		'honorific' => "varchar(16) collate utf8_unicode_ci NOT NULL",
		'firstname' => "varchar(128) collate utf8_unicode_ci NOT NULL",
		'lastname' => "varchar(128) collate utf8_unicode_ci NOT NULL",
		'post_nom' => "varchar(32) collate utf8_unicode_ci NOT NULL",
		'title' => "varchar(64) collate utf8_unicode_ci NOT NULL",
		'company' => "varchar(255) collate utf8_unicode_ci NOT NULL",
		'baddr1' => "varchar(128) collate utf8_unicode_ci NOT NULL default ''",
		'baddr2' => "varchar(128) collate utf8_unicode_ci NOT NULL default ''",
		'bcity' => "varchar(128) collate utf8_unicode_ci NOT NULL default ''",
		'bstate' => "varchar(2) collate utf8_unicode_ci NOT NULL default ''",
		'bzip' => "varchar(32) collate utf8_unicode_ci NOT NULL default ''",
		'bcounty' => "varchar(40) collate utf8_unicode_ci NOT NULL",
		'bcountry' => "varchar(40) collate utf8_unicode_ci NOT NULL",
		'bphone' => "varchar(32) collate utf8_unicode_ci NOT NULL default ''",
		'bcell' => "varchar(16) collate utf8_unicode_ci NOT NULL",
		'bfax' => "varchar(16) collate utf8_unicode_ci NOT NULL default ''",
		'accounturl' => "text collate utf8_unicode_ci NOT NULL",
		'profile_html' => "text collate utf8_unicode_ci NOT NULL",
		'accountactive' => "tinyint(3) unsigned NOT NULL default '0'",
		'is_free_account' => 'tinyint',
		'is_ext_pay' => 'tinyint',
		'ext_pay_src' => 'varchar',
		'rowsperpage' => "smallint(5) unsigned NOT NULL default '30'"
	);
		
	public static $account_plans = array
	(
		'basic' => 'Basic',
		'standard' => 'Standard',
		'premium' => 'Premium',
		'public' => 'Public',
		'tax' => 'Property Records Online'
	);

	public static $source_groups = array
	(
		'PUBLIC' => 'Public',
		'ADMIN' => 'Admin',
		'OFFICE' => 'Office',
		'EREALTY' => 'ERealty',
		'OLR' => 'OLR',
		'REALPLUS' => 'RealPlus',
		'SABELLA' => 'RealPro',
		'REALTYMX' => 'RealtyMX',
		'EELESAUN' => 'Saunders',
	);
	
	public static $rets_priorities = array
	(
		'default' => 'Default',
		'agent' => 'Agent',
		'office' => 'Office'
	);
	
	public static $title_codes = array
	(
		'LS'=>'Licensed Salesperson',
		'AB'=>'Associate Broker',
		'BR'=>'Broker of Record'
	);
	
	/** Default widgets and settings, stored in wall_json column
	 *
	 * @var type 
	 */
	public static $wall_widgets = array(
		'quick_search' => array(
			'name'=>'Quick Search',
			'title_bg_color'=>'seafoam',
			'display'=>TRUE
		),
		'company_docs' => array(
			'name'=>'Company Resources',
			'title_bg_color'=>'seafoam',
			'display'=>TRUE
		),
		'my_listings' => array(
			'name'=>'My Listings',
			'title_bg_color'=>'seafoam',
			'display'=>TRUE
		),
		'company_listings' => array(
			'name'=>'Company Listings',
			'title_bg_color'=>'seafoam',
			'display'=>TRUE
		),
		'saved_carts' => array(
			'name'=>'Saved Carts',
			'title_bg_color'=>'seafoam',
			'display'=>TRUE
		),
		'cmas' => array(
			'name'=>'CMAs',
			'title_bg_color'=>'seafoam',
			'display'=>TRUE
		),
		'messages' => array(
			'name'=>'Messages',
			'title_bg_color'=>'seafoam',
			'display'=>FALSE
		),
		'mortgage_calc' => array(
			'name'=>'Mortgage Calculator',
			'title_bg_color'=>'seafoam',
			'display'=>TRUE
		),
		'member_search' => array(
			'name'=>'Member Search',
			'title_bg_color'=>'seafoam',
			'display'=>TRUE
		),
		/*'page_history' => array(
			'name'=>'Page History',
			'title_bg_color'=>'seafoam',
			'display'=>TRUE
		),*/
		'system_status' => array(
			'name'=>'System Status',
			'title_bg_color'=>'seafoam',
			'display'=>TRUE
		),
	);
	
	/** Title background colors, stored in wall_json column
	 *
	 * @var type 
	 */
	public static $widget_colors = array(
		'red' => '#BC0000',
		'orange' => '#DA621E',
		'mustard' => '#D3B53D',
		'brown' => '#a65600',
		'green' => '#26802f',
		'lime' => '#81d126',
		'sage' => '#93A661',
		'teal' => '#01B582',
		'seafoam' => '#28A7AD', // This is our secondary color
		'blue' => '#0b92ff',
		'indigo' => '#4b0082', // '#2a3f54', // This was our header/footer/link color, now it's more purple
		'purple' => '#8D35B0',
		'magenta' => '#B01B65',
		'pink' => '#FF69B4',
		'charcoal' => '#555555',
		'gray' => '#7A7A7A'
	);
	
	// These fields must be washed because inserting 3,124 into our db will result in a value of 3
	public static $number_fields = array();
	
	// These fields must be washed because inserting 3,124 into our db will result in a value of 3
	public static $date_fields = array('joindate', 'renew_date', 'agent_license_expire', 'appraiser_license_expire');
	
	public function __construct($my_account_id, $my_db='', $my_logger='', $table_name='accounts')
	{	
		if (!empty($my_account_id))
		{
			$this->account_id = $my_account_id;
		}
		
		$this->table = $table_name;
		$this->table_nice_name = 'Accounts';
		$this->key_fields = array('accountid');
		
		$this->update_msg_success = 'Member information was successfully updated.';
		$this->insert_msg_success = 'Member successfully added.';
		$this->delete_msg_success = 'Member successfully deleted.';
		$this->update_msg_fail = 'Member information update failed.';
		$this->insert_msg_fail = 'Member insert failed.';
		$this->delete_msg_fail = 'Member remove failed.';
		
		parent::__construct($my_db, $my_logger);
	}
	
	public function pre_raw_modify(&$raw_fields, $modify_type)
	{
		$account = SwiftAccount::getReference();
		$user_account_id = 0;
		$user_name = '';
		
		if (is_object($account))
		{
			$user_account_id = $account->get_info('accountid');
			$user_name = $account->get_info('firstname').' '.$account->get_info('lastname');
			$raw_fields['_change_user_name'] = $user_name;
			$raw_fields['_change_user_id'] = $user_account_id;
		}
		
		if (isset($raw_fields['_renew_date']))
		{
			$raw_fields['_renew_date'] = DateHelper::autoFormatToSQLDate($raw_fields['_renew_date']);
		}

		if (isset($raw_fields['_joindate']))
		{
			$raw_fields['_joindate'] = DateHelper::autoFormatToSQLDate($raw_fields['_joindate']);
		}

		if (isset($raw_fields['_agent_license_expire']))
		{
			$raw_fields['_agent_license_expire'] = DateHelper::autoFormatToSQLDate($raw_fields['_agent_license_expire']);
		}
		
		if (isset($raw_fields['_appraiser_license_expire']))
		{
			$raw_fields['_appraiser_license_expire'] = DateHelper::autoFormatToSQLDate($raw_fields['_appraiser_license_expire']);
		}
		
		if (isset($raw_fields['_firstname']))
		{
			// Pretty up the name by making it mixed case
			$name_val = preg_replace('/[^a-zA-Z]/', '', trim($raw_fields['_firstname']));
			if ((preg_match('/^[A-Z]+$/', $name_val) == 1) || (preg_match('/^[a-z]+$/', $name_val) == 1))
			{
				if (strlen($raw_fields['_firstname']) <= 2)
				{
					$raw_fields['_firstname'] = ucwords($raw_fields['_firstname']);
				}
				else
				{
					$raw_fields['_firstname'] = ucwords(strtolower($raw_fields['_firstname']));
				}
			}
		}
		
		if (isset($raw_fields['_lastname']))
		{
			$name_val = preg_replace('/[^a-zA-Z]/', '', trim($raw_fields['_lastname']));
			if ((preg_match('/^[A-Z]+$/', $name_val) == 1) || (preg_match('/^[a-z]+$/', $name_val) == 1))
			{
				$raw_fields['_lastname'] = ucwords(strtolower($raw_fields['_lastname']));
			}
		}
		
		if (isset($raw_fields['_bphone']))
		{
			$raw_fields['_bphone'] = self::formatUSPhone($raw_fields['_bphone']);
		}
		
		if (isset($raw_fields['_biphone']))
		{
			$raw_fields['_biphone'] = self::formatUSPhone($raw_fields['_biphone']);
		}
		
		if (isset($raw_fields['_bcell']))
		{
			$raw_fields['_bcell'] = self::formatUSPhone($raw_fields['_bcell']);
		}
		
		if (isset($raw_fields['_bfax']))
		{
			$raw_fields['_bfax'] = self::formatUSPhone($raw_fields['_bfax']);
		}
		
		if (isset($raw_fields['_bmainphone']))
		{
			$raw_fields['_bmainphone'] = self::formatUSPhone($raw_fields['_bmainphone']);
		}
	}
	
	/* Field Validation - must return TRUE to continue with table modifcations
	   Any error messages should be put in the status_msg referenced variable 
	   May also be used for pre processing, like encrypting passwords before storing */
	public function validate_fields(&$raw_fields, $modify_type)
	{
		$account = SwiftAccount::getReference();
		$status = TRUE;
		
		// We do NOT want to pass our account id over the wire so we make sure our
		// application gets it from the session and passes it in.  This ensures that
		// no hack programs can mimic a transaction and fudge an account id
		if ($modify_type != TABLE_INSERT)
		{
			$raw_fields['_accountid'] = $this->account_id;
			
			if (isset($raw_fields['_username']) && !empty($raw_fields['_username']))
			{
				if (strpos($raw_fields['_username'], '@') === FALSE)
				{
					if (strlen($raw_fields['_username']) >= 4)
					{
						$sql = "SELECT accountid FROM accounts WHERE username='%s' LIMIT 2";
						$this->db->query_select_buffer_safe($sql, $rows, 'DUPUN', $raw_fields['_username']);
						if (!empty($rows))
						{
							if ((count($rows) > 1) || ($rows[0]['accountid'] != $raw_fields['_accountid']))
							{
								$this->logger->add_warning_message("Username already exists. Please enter another one");
								$status = FALSE;
							}
						}
					}
					else
					{
						$this->logger->add_warning_message("Username must be at least 4 charaters. Please enter another one");
						$status = FALSE;
					}
				}
				else
				{
					$this->logger->add_warning_message("Username cannot contain '@' symbol. Please enter another one");
					$status = FALSE;
				}
			}
		}
		else
		{
			$raw_fields['_mls_name'] = SiteConfig::$MLS_NAME;
			$raw_fields['_accountpw'] = GetRandomString(8);
			$raw_fields['_joindate'] = DateHelper::todayToSQLDate();
			$raw_fields['_renew_date'] = DateHelper::todayToSQLDate();
			$raw_fields['_join_ip'] = $_SERVER['REMOTE_ADDR'];
			$raw_fields['_join_port'] = $_SERVER['REMOTE_PORT'];
			$raw_fields['_accountplan'] = 'premium';
			$raw_fields['_member_terms'] = '1';
			$raw_fields['_list_terms'] = '1';
			
			if (is_object($account) && !$account->isSuperUser1() && ($account->get_info('accountid') >= AccountsTable::$MIN_ACCOUNT_ID))
			{
				$raw_fields['_refer_id'] = $account->get_info('accountid');
			}
		}

		if (isset($raw_fields['_renew_date']) && !DateHelper::isValidSQLDate($raw_fields['_renew_date'], TRUE))
		{
			$this->logger->add_warning_message("Please enter a valid renew date.");
			$status = FALSE;
		}
		
		if (isset($raw_fields['_joindate']) && !DateHelper::isValidSQLDate($raw_fields['_joindate'], TRUE))
		{
			$this->logger->add_warning_message("Please enter a valid join date.");
			$status = FALSE;
		}
		
		if (isset($raw_fields['_agent_license_expire']) && !DateHelper::isValidSQLDate($raw_fields['_agent_license_expire'], TRUE))
		{
			$this->logger->add_warning_message("Please enter a valid agent license expiration date.");
			$status = FALSE;
		}
		
		if (isset($raw_fields['_appraiser_license_expire']) && !DateHelper::isValidSQLDate($raw_fields['_appraiser_license_expire'], TRUE))
		{
			$this->logger->add_warning_message("Please enter a valid appraiser license expiration date.");
			$status = FALSE;
		}

		if (isset($raw_fields['_accountemail']) && !isValidEmail($raw_fields['_accountemail'], FALSE, TRUE))
		{
			// We only want a warning if this is a new account or the email address was filled in
			$this->logger->add_warning_message("Please enter a valid email address.");
			$status = FALSE;
		}

		if (isset($raw_fields['accountpw1']) && !empty($raw_fields['accountpw1']))
		{
			// Change password request
			if ($raw_fields['accountpw1'] == $raw_fields['accountpw2'])
			{
				if (!IsValid::Password($raw_fields['accountpw1']))
				{
					$this->logger->add_warning_message("Please enter a password between 8 and 32 characters.");
					$status = FALSE;
				}
				else
				{
					$epw = Crypto::hashPassword($raw_fields['accountpw1']);
					$raw_fields['_accountpw'] = $epw;
				}
			}
			else
			{
				$this->logger->add_warning_message("Passwords do not match. Please try again.");
				$status = FALSE;
			}
		}
		
		if ($account->isPublicUser())
        {
            return $status;
        }
		
		if (isset($raw_fields['_firstname']) || isset($raw_fields['_lastname']))
		{
			if (!isValidRequiredString($raw_fields['_firstname']) || !isValidRequiredString($raw_fields['_lastname']))
			{
				$this->logger->add_warning_message("Please enter a contact first name and last name.");
				$status = FALSE;
			}
		}

		if (isset($raw_fields['_agent_license_state']))
		{
			$raw_fields['_agent_license_state'] = strtoupper($raw_fields['_agent_license_state']);
		}
		
		/* Office Managers/Admins don't have to enter location info if they supply an office id */ 
		if (isset($raw_fields['_baddr1']))
		{
			if (!isValidRequiredString($raw_fields['_baddr1']) || !isValidRequiredString($raw_fields['_bcity']) || !isValidRequiredString($raw_fields['_bstate']))
			{
				$this->logger->add_warning_message("Please enter location details (address, city, and state).");
				$status = FALSE;
			}
			
			if (!isValidRequiredString($raw_fields['_bzip']))
			{
				$raw_fields['_bzip'] = ZipCodes::getZipFromCity($raw_fields['_bcity'], $raw_fields['_bstate']);
			}
			
			if (!isValidRequiredString($raw_fields['_bcounty']))
			{
				$raw_fields['_bcounty'] = ZipCodes::getCountyNameFromZip5($raw_fields['_bzip']);
			}
		}
		else
		{
			if ($modify_type == TABLE_INSERT)
			{
				/* If we are adding a new member and no location details were specified, we require that
				   the office id is filled in so we can pull that data */
				if (isset($raw_fields['_office_id']) && !empty($raw_fields['_office_id']))
				{
					$office = OfficesTable::getRow('office_name,address1,address2,city,state,zip,county', "office_id='".$raw_fields['_office_id']."'");
					if ($office)
					{
						/* Assign the office details to the agent */
						$raw_fields['_auth_agent'] = 1;
						$raw_fields['_auth_search_tax'] = 1;
						$raw_fields['_company'] = $office['office_name'];
						$raw_fields['_baddr1'] = $office['address1'];
						$raw_fields['_baddr2'] = $office['address2'];
						$raw_fields['_bcity'] = $office['city'];
						$raw_fields['_bstate'] = $office['state'];
						$raw_fields['_bzip'] = $office['zip'];
						$raw_fields['_bcounty'] = $office['county'];
					}
					else
					{
						$this->logger->add_warning_message("A valid office [" . $raw_fields['_office_id'] . "] was not specified.");
						$status = FALSE;
					}
				}
				else
				{
					// We assume office will be added via license because this account is being
					// added by a super user admin
					if (!SwiftAccount::isSuperUser())
					{
						$this->logger->add_warning_message("An office is expected to be assigned to this agent. Please specify an office for which this agent will belong.");
						$status = FALSE;
					}
				}
			}
		}

		if (isset($raw_fields['_bphone']) || isset($raw_fields['_biphone']))
		{
			if (!isValidRequiredString($raw_fields['_bphone']) && !isValidRequiredString($raw_fields['_biphone']))
			{
				$this->logger->add_warning_message("You must enter an office phone number.  Please use the format 123-456-7890.");
				$status = FALSE;
			}
		}
		
		/*if (isset($raw_fields['_member_terms']) || isset($raw_fields['_list_terms']))
		{
			if (($raw_fields['_member_terms'] != 1) || ($raw_fields['_list_terms'] != 1))
			{
				$this->logger->add_warning_message("You must agree to our member and listing terms to proceed.");
				$status = FALSE;
			}
		}*/

		if (is_object($account) && !$account->isSuperUser1())
		{
			if (isset($raw_fields['_broker_name']))
			{
				if (!isValidRequiredString($raw_fields['_broker_name']))
				{
					$this->logger->add_warning_message("Please enter your broker's full name.");
					$status = FALSE;
				}
			}

			if (isset($raw_fields['_broker_phone']) || isset($raw_fields['_broker_email']))
			{
				if (!isValidRequiredString($raw_fields['_broker_phone']) && !isValidEmail($raw_fields['_broker_email'], FALSE, TRUE))
				{
					$this->logger->add_warning_message("Please enter your broker's phone and/or email address.");
					$status = FALSE;
				}
			}
		}

		return $status;
	}
	
	/* Called automatically after a successful table modification, use this 
	   to perform actions after an insert, update, or delete for a certain table */
	protected function post_modify(&$clean_fields, $modify_type)
	{
		$status = TRUE;
		
		if ($modify_type == TABLE_INSERT)
		{
			$new_account_id = $this->last_insert_id;
			Referral::updatePoints($new_account_id, REF_JOIN, $clean_fields['joindate']);
		}
		
		return $status;
	}

	public static function validate_required_fields($id, $test_row, &$msgs)
	{
		global $db;
		global $logger;
		
		$status = TRUE;
		
		if (is_array($test_row) && !empty($test_row))
		{
			$raw_fields = &$test_row;
			$fi = '_';
		}
		else
		{
			$raw_fields = $db->query_select_safe("SELECT " . implode(',', self::$required_fields) . " FROM accounts WHERE accountid='%s'", $id);
			$fi = '';
		}
		
		if (!$raw_fields)
		{
			$msgs[] = 'Unknown account id or empty field array passed to required field validation';
			return FALSE;
		}
		
		if (isEmpty($raw_fields[$fi.'firstname']))
		{
			$msgs[] = 'First Name Missing';
			$status = FALSE;
		}

		if (isEmpty($raw_fields[$fi.'lastname']))
		{
			$msgs[] = 'Last Name Missing';
			$status = FALSE;
		}

		if (!isValidEmail($raw_fields[$fi.'accountemail'], FALSE, TRUE))
		{
			$msgs[] = "Email '".$raw_fields[$fi.'accountemail']."' Not Valid";
			$status = FALSE;
		}

		return $status;
	}

	public static function getName(&$row)
	{
		$name = '';
		if (!empty($row['honorific'])) $name .= $row['honorific'].' ';
		
		$name .= $row['firstname'].' '.$row['lastname'];
		
		if (!empty($row['post_nom'])) $name .= ', '.$row['post_nom'];
		
		return $name;
	}

	public static function getFullName(&$row)
	{
		$name = '';
		$name .= $row['firstname'].' '.$row['lastname'];
		return $name;
	}

	public function isDuplicateEmail($email)
	{
		global $db;
		
		// Make sure email is not already assigned to an active user!
		$my_query = "SELECT accountid FROM ".$this->table." WHERE accountemail='" . trim($email) . "' AND accountactive=1";
		$myrow = $db->query_select($my_query);
		if (!empty($myrow))
		{
			return TRUE;
		}
		
		return FALSE;
	}
	
	public function remove($id)
	{
	}

	public function deactivate($id, $hide_active_listings=TRUE)
	{
		if ($id > SwiftAccount::$FILLMORE)
		{
			$my_query = "UPDATE accounts SET accountactive=0, modified='" . DateHelper::nowToSQLDateTime() . "' WHERE accountid='" . $id . "'";
			$this->status = $this->db->query_modify_1($my_query);
			if ($this->status)
			{
				$this->logger->add_success_message("Member ID '$id' was successfully removed from the active list.");
				if ($hide_active_listings === TRUE)
				{
					$my_query = "UPDATE listings SET hidden=1, hide_reason='inactive', modified='%s' WHERE accountid='%s' AND status IN (100,200,240)";
					$this->db->query_modify_safe($my_query, DateHelper::nowToSQLDateTime(), $id);
					$this->logger->add_success_message("Any active or pending listings this member owned have been hidden.");
				}
			}
			else
			{
				$this->logger->add_error_message("Member ID '$id' failed to be removed from the active list (or they were already deactivated).");
			}
		}
	}

	public function activate($id)
	{
		$my_query = "SELECT accountemail FROM accounts WHERE accountid='" . $id . "'";
		$myrow = $this->db->query_select($my_query, 'ce');
		if ($myrow)
		{
			if (!$this->isDuplicateEmail($myrow['accountemail']))
			{
				$my_query = "UPDATE accounts SET accountactive=1, modified='" . DateHelper::nowToSQLDateTime() . "' WHERE accountid='" . $id . "'";
				$this->status = $this->db->query_modify_1($my_query);
				if ($this->status)
				{
					$this->logger->add_success_message("The member was successfully activated.");
				}
				else
				{
					$this->logger->add_error_message("The member failed to be activated (or he/she was already active).");
				}
			}
			else
			{
				$this->logger->add_error_message("The member failed to be activated because another active member already has the same email '" . $myrow['accountemail'] . "'");
			}
		}
	}
	
	/** Add a new account with an office id, no office, or add an office and assign the agent to it
	 * 
	 * @param type $data
	 * @param int $new_account_id
	 */
	public static function addNewAccount(&$data, &$new_account_id)
	{
		$account = SwiftAccount::getReference();
		$status = FALSE;
		$new_account_id = 0;
		$is_broker = FALSE;
		$account_id = $account->get_info('accountid');
		$office_id = 0;
		$offices_tbl = new OfficesTable();
		
		$is_broker = ($data['_is_broker'] == '1') ? TRUE : FALSE;
		
		if (isset($data['_office_id']) && ($data['_office_id'] >= OfficesTable::$MIN_OFFICE_ID))
		{
			// We have an existing office id so fill the agent's office data with this
			$office = OfficesTable::getRow('*', "office_id='".SwiftDB::get_real_escape_string($data['_office_id'])."'");
			if ($office)
			{
				$office_id = $office['office_id'];
				
				/* Assign the office details to the agent */
				$data['_company'] = $office['office_name'];
				$data['_baddr1'] = $office['address1'];
				$data['_baddr2'] = $office['address2'];
				$data['_bcity'] = $office['city'];
				$data['_bstate'] = $office['state'];
				$data['_bzip'] = $office['zip'];
				$data['_bcounty'] = $office['county'];
			}
			else
			{
				$data['_office_id'] = 0;
			}
		}
		else if (isset($data['office_hash_id']) && (strlen($data['office_hash_id']) >= 3))
		{
			//echo "\nHave office hash id\n";
			// This is from Accounts/LicenseLookup/ API which sets this session variable
			if (isset($_SESSION['license_data'][$data['office_hash_id']]))
			{
				//echo "\add new office\n";
				$license_data = $_SESSION['license_data'][$data['office_hash_id']];

				// We have a cached hash id so use the office information from the license
				// This office isn't in our table so add it
				// Hash Id is auto set in the offices table class
				$office = array
				(
					'_parent_id' => '0',
					'_is_parent' => '0',
					'_office_name' => $license_data['office_name'],
					'_address1' => $license_data['office_address'],
					'_address2' => $license_data['office_address2'],
					'_city' => $license_data['office_city'],
					'_state' => $license_data['office_state'],
					'_zip' => $license_data['office_zip'],
					'_phone' => '',
					'_county' => $license_data['office_county'],
					'_source_name' => 'state:'.$account_id,
					'_state_office_id' => $license_data['office_number'],
					'_hash_id' => $data['office_hash_id']
				);
				
				if ($is_broker)
				{
					$office['broker_id'] = '0';
				}
				
				/* Assign the office details to the agent */
				$data['_company'] = $license_data['office_name'];
				$data['_baddr1'] = $license_data['office_address'];
				$data['_baddr2'] = $license_data['office_address2'];
				$data['_bcity'] = $license_data['office_city'];
				$data['_bstate'] = $license_data['office_state'];
				$data['_bzip'] = $license_data['office_zip'];
				$data['_bcounty'] = $license_data['office_county'];

				$office_add = $offices_tbl->raw_insert($office, TRUE);
				if ($office_add)
				{
					$office_id = $offices_tbl->get_last_insert_id();
					$data['_office_id'] = $office_id;
				}
			}
		}
		else
		{
			$data['_office_id'] = 0;
		}
		
		if (count($data) > 1)
		{
			//debug_print($data, 'data before insert'); exit();
			if (isset($data['_agent_license_expire']))
			{
				$temp_date = DateHelper::autoFormatToSQLDate($data['_agent_license_expire']);
				if (IsValid::Date($temp_date, 'yyyy-mm-dd', TRUE))
				{
					$data['_agent_license_expire'] = $temp_date;
				}
				else
				{
					$data['_agent_license_expire'] = '0000-00-00';
				}
			}
		
			$accounts_tbl = new AccountsTable(0);
			$status = $accounts_tbl->raw_insert($data, TRUE);
			$new_account_id = $accounts_tbl->get_last_insert_id();
		}
		
		// If this agent is the broker and his/her office does not have a designee yet, make them the designee
		if ($is_broker && 
			($office_id >= OfficesTable::$MIN_OFFICE_ID) &&
			isset($office['broker_id']) && 
			($office['broker_id'] == '0') && 
			($new_account_id >= AccountsTable::$MIN_ACCOUNT_ID))
		{
			$tdata = array();
			$tdata['_office_id'] = $office_id;
			$tdata['_broker_id'] = $new_account_id;
			$offices_tbl->raw_update($tdata, TRUE);
		}
		
		if ($office_id >= OfficesTable::$MIN_OFFICE_ID)
		{
			// Make sure all agents (including this new agent) have the most up to date office information
			OfficesTable::cascadeUpdate($office_id, array());
		}
		
		return $status;
	}
	
	public function activateListings($id, $silent=TRUE)
	{
		if ($id > self::$MIN_ACCOUNT_ID)
		{

			// Only unhide listings that were hidden by the deactivate method (active or pending) and are less than a month old
			$my_query = "UPDATE listings SET hidden=0, hide_reason='', modified='%s' WHERE accountid='%s' AND hide_reason='inactive' AND status IN (100,200,240) AND DATE(modified)>=DATE_SUB(CURDATE(), INTERVAL 21 DAY) AND listdate>=DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
			$this->db->query_modify_safe($my_query, DateHelper::nowToSQLDateTime(), $id);
			if (!$silent) $this->logger->add_success_message("Any active or pending listings this member owned have been hidden.");

		}
	}
	
	public function deactivateListings($member_id, $silent=TRUE)
	{
		if ($member_id > self::$MIN_ACCOUNT_ID)
		{
			// Only unhide listings that were hidden by the deactivate method (active or pending) and are less than a month old
			$my_query = "UPDATE listings SET hidden=1, hide_reason='inactive', modified='%s' WHERE accountid='%s' AND status IN (100,200,240)";
			$this->db->query_modify_safe($my_query, DateHelper::nowToSQLDateTime(), $member_id);
			if (!$silent) $this->logger->add_success_message("Any active or pending listings this member owned have been hidden.");
		}
	}

	public static function retsDisableListings($member_id)
	{
		$db = SwiftDB::getReference();
		if ($member_id > self::$MIN_ACCOUNT_ID)
		{
			// Remove these listings from being visible in the RETS server
			$my_query = "UPDATE listings SET rets_enabled=0 WHERE accountid='%s' AND status IN (100,200,240)";
			$db->query_modify_safe($my_query, $member_id);
		}
	}
	
	public static function retsEnableListings($member_id)
	{
		$db = SwiftDB::getReference();
		if ($member_id > self::$MIN_ACCOUNT_ID)
		{
			// Put these listings back into the RETS by making them visible
			$my_query = "UPDATE listings SET rets_enabled=1 WHERE accountid='%s' AND status IN (100,200,240)";
			$db->query_modify_safe($my_query, $member_id);
		}
	}

	private function validate_license(&$raw_fields)
	{
		$status = TRUE;
		if ((($raw_fields['_auth_agent'] == 1) || ($raw_fields['old_auth_agent'] == 1)) && !self::isValidAgentLicense($raw_fields['_agent_license']))
		{
			$this->logger->add_warning_message("Please enter a valid agent license number.  If you need assistance, please contact us and we can help with the validation.");
			$status = FALSE;
		}
		
		if ((($raw_fields['_auth_appraise'] == 1) || ($raw_fields['old_auth_appraise'] == 1)) && !self::isValidAppraiserLicense($raw_fields['_appraiser_license']))
		{
			$this->logger->add_warning_message("Please enter a valid appraiser license number. If you need assistance, please contact us and we can help with the validation.");
			$status = FALSE;
		}
		
		return $status;
	}

	public static function facebook_fan($id)
	{
		global $account;
		global $db;
		
		if ($account->is_valid($id, FALSE))
		{
			if ($account->get_info('facebook_pts') == 0)
			{
				$my_query = "UPDATE accounts SET facebook_pts='1' WHERE accountid='".$id."'";
				if ($db->query_modify_1($my_query))
				{
					//Referral::updatePoints($id, REF_FACEBOOK, TodayToSQLDate());
					$account->set_info('facebook_pts', 1);
				}
			}
		}
	}
	
	public static function twitter_follow($id)
	{
		global $account;
		global $db;
		
		if ($account->is_valid($id, FALSE))
		{
			if ($account->get_info('twitter_pts') == 0)
			{
				$my_query = "UPDATE accounts SET twitter_pts='1' WHERE accountid='".$id."'";
				if ($db->query_modify_1($my_query))
				{
					//Referral::updatePoints($id, REF_TWITTER, TodayToSQLDate());
					$account->set_info('twitter_pts', 1);
				}
			}
		}
	}

	public static function isValidAccount($id, $valid_active=TRUE)
	{
		global $db;
		$status = FALSE;

		if (is_numeric($id) && ($id >= AccountsTable::$MIN_ACCOUNT_ID))
		{
			$where_clause = '';
			if ($valid_active === TRUE)
			{
				$where_clause = " AND accountactive='1'";
			}
			
			$my_query = "SELECT accountid FROM accounts WHERE accountid='" . $id . "' " . $where_clause;
			$result_row = $db->query_select($my_query);
			if ($result_row)
			{
				$status = TRUE;
			}
		}
		
		return $status;
	}
	
	public static function deleteMember($member_id)
	{
		global $db;
		global $logger;
		
		$status = FALSE;
		
		// In order to delete a member, we must first make sure the member id passed in is valid
		// and is NOT active and NOT an office/broker account
		$row = self::getRow(array('accountid'), "accountid='".$member_id."' AND auth_office=0 AND auth_offices=0 AND accountactive=0 AND auth_super=0");
		if ($row)
		{
			// Make sure this account doesn't have any listings
			$my_query = "SELECT accountid FROM listings WHERE accountid='" . $member_id . "' LIMIT 1";
			$list_row = $db->query_select($my_query);
			
			// Make sure they don't have any any payments
			$payments_removed = FALSE;
			$my_query = "SELECT accountid FROM payments WHERE accountid='" . $member_id . "' AND is_active=1 LIMIT 1";
			$pay_row = $db->query_select($my_query);

			// Make sure they don't have any calendar entries
			$my_query = "SELECT accountid FROM date_book WHERE accountid='" . $member_id . "' LIMIT 1";
			$cal_row = $db->query_select($my_query);
			
			if (!$list_row && !$pay_row && !$cal_row)
			{
				$my_query = "UPDATE accounts SET is_void=1 WHERE accountid='".$member_id."' LIMIT 1";
				$status = $db->query_modify_1($my_query);
				$num_del = $db->get_row_count();
				if ($status && ($num_del == 1))
				{
					$logger->add_success_message("The member (" . $member_id . ") was successfully voided from the Accounts database table.");
					if ($payments_removed) $logger->add_success_message("The member (" . $member_id . ") had inactive payments that were successfully deleted.");
				}
				else
				{
					$logger->add_error_message("The member (" . $member_id . ") failed to be deleted from the database table (or the record was already deleted).");
				}
			}
			else
			{
				$tables = array();
				if ($list_row) $tables[] = 'Listings';
				if ($pay_row) $tables[] = 'Payments';
				if ($cal_row) $tables[] = 'Calendar';
				
				$logger->add_error_message("The member (" . $member_id . ") exists in other database tables (" . implode(',', $tables) . ").");
			}
		}
		else
		{
			$logger->add_error_message("The member (" . $member_id . ") failed to be deleted because the member does not exist or has office/super authority or is an active account.");
		}
		
		return $status;
	}
	
	public static function isValidAgentLicense($license)
	{
		return isValidRequiredString($license, 2);
	}
	
	public static function isValidAppraiserLicense($license)
	{
		return isValidRequiredString($license, 2);
	}

	public static function isLicenseExpired($expire_date)
	{
		// Make sure if we are an agent, our license hasn't expired
		$grace_expire_date = DateHelper::dateMath($expire_date, '+', '7', 'days');
		if (DateHelper::isValidSQLDate($grace_expire_date, TRUE))
		{
			if (DateHelper::compareSQLDates(DateHelper::todayToSQLDate(), '>=', $grace_expire_date))
			{
				return TRUE;
			}
		}
		
		return FALSE;
	}

	public static function isValidLicenseState($state_list)
	{
		// Make sure they have a valid state or state list (2 char code, like NY or FL)
		$allowed_state = array();
		$csv_states = strtoupper($state_list);
		$state_array = preg_split('@\s*,\s*@', $csv_states);
		foreach ($state_array as $my_state)
		{
			if (!isset(USOptions::$state_abbrs[$my_state]))
			{
				return FALSE;
			}
		}
		
		return TRUE;
	}
	
	public static function getRow($fields, $conditions)
	{
		global $db;
		$result_row = array();
		
		if (is_array($fields))
		{
			$field_string = implode(',', $fields);
		}
		else
		{
			$field_string = $fields;
		}

		if (is_array($conditions))
		{
			$where_clause = implode(' AND ', $conditions);
		}
		else
		{
			$where_clause = $conditions;
		}
		
		$my_query = "SELECT " . $field_string . " FROM accounts WHERE " . $where_clause . " LIMIT 1";
		$result_row = $db->query_select($my_query, '_gr');
		if (!$result_row)
		{
			$result_row = array();
		}

		return $result_row;
	}

	public static function getFieldValue($field, $id)
	{
		global $db;
		$result_row = array();
		$fvalue = FALSE;
		
		$my_query = "SELECT %s FROM accounts WHERE accountid='%d' LIMIT 1";
		$result_row = $db->query_select_safe_qid($my_query, '_gfv', $field, $id);
		if ($result_row)
		{
			$fvalue = $result_row[$field];
		}

		return $fvalue;
	}

	public static function isValid($id, $use_db=TRUE)
	{
		global $db;
		
		$test_id = $id;
		$row = array();
		
		if (is_numeric($test_id) && ($test_id >= SwiftAccount::$FILLMORE))
		{
			if ($use_db)
			{
				if (valid_db($db))
				{
					$myquery = "SELECT * FROM accounts WHERE accountid='" . $test_id . "' LIMIT 1";
					$row = $db->query_select($myquery);
					if (!$row || ($db->get_row_count() != 1))
					{
						$row = FALSE;
					}
				}
			}
			else
			{
				$row = TRUE;
			}
		}
		
		return $row;
	}
	
	public static function isNewAccount($member_id, &$row)
	{
		if (!($member_id >= self::$MIN_ACCOUNT_ID))
			return false;
		
		if (!isset($row['pay_fail']))
		{
			$acct_tbl = new AccountsTable($member_id);
			$row = $acct_tbl->findKey($member_id);
		}
		
		if ($row['pay_fail'] == 'new_account')
		{
			return true;
		}
		
		return false;
	}

	public static function activateNewAccount(&$joinrow)
	{
		global $db;
		$status = FALSE;
		
		$join_id = $joinrow['accountid'];
		$plain_pw = Crypto::generatePassword(10);
		$pw = Crypto::hashPassword($plain_pw);
		$my_query = "UPDATE accounts SET accountactive='1', accountpw='%s', pay_reason='none' WHERE accountid='%s' AND accountactive='0' LIMIT 1";
		$modify_success = $db->query_modify_safe($my_query, $pw, $join_id);
		$status = self::sendNewAccountEmail($joinrow, $plain_pw);
		
		return $status;
	}
	
	public static function sendNewAccountEmail(&$member, $pw)
	{
		$ToEmail = $member['accountemail'];
		//$FromEmail = strip_tags($_POST['email_from']);
		//$FromName = strip_tags($_POST['email_from_name']);
		$ToCc = WEB_INFO_EMAIL;
		$FromEmail = WEB_INFO_EMAIL;
		
		$mls_nice_name = SiteConfig::$MLS_NICE_NAME;
		$mls_domain = SiteConfig::$MLS_NAME;
		if (!empty(SystemConfig::$MLS_NAMES[$member['mls_name']]))
		{
			$mls_nice_name = SystemConfig::$MLS_NAMES[$member['mls_name']];
			$mls_domain = $member['mls_name'];
		}
		
		$FromName = $mls_nice_name.' Membership';
		$Subject = 'Your new account with '.$mls_nice_name;
		
		$HTMLMessage = '
		<html><head><title>$Subject</title></head><body>
		Welcome ' . $member['firstname'] . ',<br />
		<br />
		Thank you for joining the '.$mls_nice_name.' membership program.  You signed up for a <strong>' . ucfirst($member['accountplan']) . '</strong> membership.  Your login information is below.  We also created a welcome video which gives an introduction to our site.<br />
		<br />
		Welcome Video: <a href="https://youtu.be/FRAdiPBt-x8" title="Welcome Video">Watch Welcome Video</a><br />
		<br />
		URL: <a href="https://www.'.$mls_domain.'.com/members/" target="_blank">https://www.'.$mls_domain.'.com/members/</a><br />
		Login: ' . $ToEmail . '<br />
		Password: ' . $pw . '<br />
		<br />
		You signed up for automatic renewal.  For full billing terms view our terms of use.
		<br />
		If you have any suggestions or questions please do not hesitate to contact us.<br />
		<br />
		Sincerely,<br />
		<br />
		The '.$mls_nice_name.' Team
		</body></html>
		';
		
		$TextMessage = strip_tags($HTMLMessage);
		$charset = 'UTF-8';
		
		$mail = new EmailSender();
		$mail->setSendMethod('mandrill');
		//$mail->setReplyTo();
		$mail->setFrom(array('name'=>$FromName, 'email'=>$FromEmail));
		$mail->setSubject($Subject);
		$mail->setText($TextMessage);
		$mail->setHTML($HTMLMessage);
		$mail->setCc($ToCc);
		//$mail->setBcc(array());

		$mail->setTo($ToEmail);
		
		$status = $mail->send();

		return $status;
	}

	public static function upgradeAccount($member_id, $plan)
	{
		global $db;
		$status = FALSE;

		// Make sure we have a valid account plan
		if (!isset(self::$account_plans[$plan]))
		{
			return FALSE;
		}
		
		$member = self::getRow(array('accountemail','firstname','accountplan','upgrade_pts','joindate'), 'accountid='.$member_id);

		if (!$member)
		{
			return FALSE;
		}
		
		// Make sure the new member gets their upgrade points if they paid
		if ($plan == 'premium')
		{
			if ($member['upgrade_pts'] == 0)
			{
				//Referral::updatePoints($member_id, REF_UPGRADE, $member['joindate']);
				$fields_to_update[] = "upgrade_pts='1'";
			}
			
			// premium members get realtor.com by default
			$fields_to_update[] = "rets_rdc='1'";
		}
		
		$fields_to_update[] = "accountplan='" . $plan . "'";
		$fields_to_update[] = "rets_pt2='1'";
		$fields_to_update[] = "pay_reason='none'";
		$fields_to_update[] = "renew_date='" . DateHelper::todayToSQLDate()  . "'";
		$fields_to_update[] = "modified='" . DateHelper::nowToSQLDateTime() . "'";
		
		$my_query = "UPDATE accounts SET " . implode(', ', $fields_to_update) . " WHERE accountid='" . $member_id . "' LIMIT 1";
		$modify_success = $db->query_modify($my_query);
		
		if (!$modify_success)
		{
			$member['@error'] = TRUE;
		}
		else
		{
			if (($plan == 'premium') || ($plan == 'standard'))
			{
				// Make sure this member's existing listings are all syndicated now
				ListingsTable::updateRETSFeed($member_id, 1);
			}
		}
		
		$member['accountplan'] = $plan;
		$status = self::sendUpgradeAccountEmail($member_id, $member);
		
		return $status;
	}
	
	public static function sendUpgradeAccountEmail($member_id, &$member)
	{
		$status = FALSE;
		
		if (!$member)
		{
			$member = self::getRow(array('accountemail','firstname','accountplan'), 'accountid='.$member_id);
		}
		
		if ($member)
		{
			$ToEmail = $member['accountemail'];
			//$FromEmail = strip_tags($_POST['email_from']);
			//$FromName = strip_tags($_POST['email_from_name']);
			$ToCc = WEB_INFO_EMAIL;
			$FromEmail = WEB_INFO_EMAIL;
			$FromName = SiteConfig::$MLS_NICE_NAME.' Membership';
			$Subject = 'Your account with '.SITE_SHORT_DOMAIN;
			
			$UpgradeMsg = (isset($member['@error'])) ? 'However, we flagged a potential problem upgrading your account to a <strong>' . ucfirst($member['accountplan']) . '</strong> membership.  If you notice that your account did not upgrade, please contact us to resolve this issue.' : 'You upgraded to a <strong>' . ucfirst($member['accountplan']) . '</strong> membership.  Any active, existing listings you have will now be syndicated.';
			
			$HTMLMessage = '
			<html><head><title>$Subject</title></head><body>
			Hello ' . $member['firstname'] . ',<br />
			<br />
			Thank you for upgrading your '.SITE_SHORT_DOMAIN.' membership.  ' . $UpgradeMsg . '<br />
			<br />
			If you have any suggestions or questions please do not hesitate to contact us.<br />
			<br />
			Sincerely,<br />
			<br />
			The '.SiteConfig::$MLS_NICE_NAME.' Team
			</body></html>
			';
			
			$TextMessage = strip_tags($HTMLMessage);
			$charset = 'UTF-8';
			
			$mail = new EmailSender();
			$mail->setSendMethod('mandrill');
			//$mail->setReplyTo();
			$mail->setFrom(array('name'=>$FromName, 'email'=>$FromEmail));
			$mail->setSubject($Subject);
			$mail->setText($TextMessage);
			$mail->setHTML($HTMLMessage);
			$mail->setCc($ToCc);
			//$mail->setBcc(array());

			$mail->setTo($ToEmail);

			$status = $mail->send();
		}
		
		return $status;
	}

	public static function renewAccount($member_id)
	{
		global $db;
		$status = FALSE;

		$member = self::getRow(array('accountemail','firstname','accountplan','upgrade_pts','joindate'), "accountid='".$member_id."'");

		if (!$member)
		{
			return FALSE;
		}
		
		$fields_to_update = array();
		$fields_to_update[] = "pay_reason='none'";
		$fields_to_update[] = "renew_date='" . DateHelper::todayToSQLDate()  . "'";
		$fields_to_update[] = "modified='" . DateHelper::nowToSQLDateTime() . "'";
		
		$my_query = "UPDATE accounts SET " . implode(', ', $fields_to_update) . " WHERE accountid='" . $member_id . "' LIMIT 1";
		$modify_success = $db->query_modify($my_query);
		
		if (!$modify_success)
		{
			$member['@error'] = TRUE;
		}
		
		$status = self::sendRenewAccountEmail($member_id, $member);
		
		return $status;
	}
	
	public static function sendRenewAccountEmail($member_id, &$member)
	{
		$status = FALSE;
		
		if (!$member)
		{
			$member = self::getRow(array('accountemail','firstname','accountplan'), 'accountid='.$member_id);
		}
		
		if ($member)
		{
			$ToEmail = $member['accountemail'];
			//$FromEmail = strip_tags($_POST['email_from']);
			//$FromName = strip_tags($_POST['email_from_name']);
			$ToCc = WEB_INFO_EMAIL;
			$FromEmail = WEB_INFO_EMAIL;
			$FromName = SiteConfig::$MLS_NICE_NAME.' Membership';
			$Subject = 'Your Membership Renewal with '.SITE_SHORT_DOMAIN;
			
			$UpgradeMsg = (isset($member['@error'])) ? 'However, we flagged a potential problem renewing your account membership.  If you notice that your account did not renew, please contact us to resolve this issue.' : '';
			
			$HTMLMessage = '
			<html><head><title>$Subject</title></head><body>
			Hello ' . $member['firstname'] . ',<br />
			<br />
			Thank you for renewing your '.SITE_SHORT_DOMAIN.' membership.  ' . $UpgradeMsg . '<br />
			<br />
			If you have any suggestions or questions please do not hesitate to contact us.<br />
			<br />
			Sincerely,<br />
			<br />
			The '.SiteConfig::$MLS_NICE_NAME.' Team
			</body></html>
			';
			
			$TextMessage = strip_tags($HTMLMessage);
			$charset = 'UTF-8';
			
			$mail = new EmailSender();
			$mail->setSendMethod('mandrill');
			//$mail->setReplyTo();
			$mail->setFrom(array('name'=>$FromName, 'email'=>$FromEmail));
			$mail->setSubject($Subject);
			$mail->setText($TextMessage);
			$mail->setHTML($HTMLMessage);
			$mail->setCc($ToCc);
			//$mail->setBcc(array());

			$mail->setTo($ToEmail);

			$status = $mail->send();
		}
		
		return $status;
	}

	public static function sendEmailToAdmin($title, $msg, &$data, $web_admin_only=FALSE)
	{
		$status = FALSE;
		
		$ToEmail = WEB_INFO_EMAIL;
		$ToCc = WEB_ADMIN_EMAIL;
		
		if ($web_admin_only === TRUE)
		{
			$ToEmail = WEB_ADMIN_EMAIL;
			$ToCc = 'dawn@nystatemls.com';			
		}
		
		$FromEmail = WEB_NOREPLY_EMAIL;
		$FromName = SiteConfig::$MLS_NICE_NAME.' Admin';
		$Subject = $title;
		$data_msg = '';
		
		if (!empty($data))
		{
			$data_msg = json_encode($data, JSON_PRETTY_PRINT);
		}
					
		$HTMLMessage = '
		<html><head><title>$Subject</title></head><body>
		Hello '.SiteConfig::$MLS_NICE_NAME.' Admin,<br />
		<br />
		' . $msg . '<br />
		<br />
		<pre style="font-size: 12pt;">' . $data_msg . '</pre><br />
		<br />
		Sincerely,<br />
		<br />
		The '.SiteConfig::$MLS_NICE_NAME.' Email Bot
		</body></html>
		';
		
		$TextMessage = strip_tags($HTMLMessage);
		$charset = 'UTF-8';
		
		$mail = new EmailSender();
		$mail->setSendMethod('mandrill');
		$mail->setFrom(array('name'=>$FromName, 'email'=>$FromEmail));
		$mail->setSubject($Subject);
		$mail->setText($TextMessage);
		$mail->setHTML($HTMLMessage);
		$mail->setCc($ToCc);
		$mail->setTo($ToEmail);

		$status = $mail->send();
		
		return $status;
	}

	public static function requestLicenseUpdate($account_id, $data)
	{
		$db = SwiftDB::getReference();
		$account = SwiftAccount::getReference();
		global $logger;

		$is_root = SwiftAccount::isSuperUser();
		$matches = array();
		$data['_temp_license_blob'] = '';
		$office_data = array();
		$modify_success = FALSE;
		$change_agent_office = FALSE;
		$office_id = 0;
		$new_office_id = 0;
		
		if (!($account_id >= self::$MIN_ACCOUNT_ID)) return FALSE;
		
		$data['NEW_bphone'] = self::formatUSPhone($data['NEW_bphone']);
		
		$sql = "SELECT m.accountid, m.office_id, o.phone AS office_phone FROM accounts AS m LEFT OUTER JOIN offices AS o ON o.office_id=m.office_id WHERE m.accountid='%s' LIMIT 1";
		$acct_row = $db->query_select_safe($sql, $account_id);
		$office_id = ($acct_row['office_id'] >= OfficesTable::$MIN_OFFICE_ID) ? $acct_row['office_id'] : 0;
	
		// Only update the office phone if none exists
		if (empty($acct_row['office_phone']) && !empty($data['NEW_bphone']))
		{
			$office_data['_phone'] = $data['NEW_bphone'];
		}
		
		if (SiteConfig::$FTR_SUPER_APPROVE_LICENSE)
		{
			if (!$is_root)
			{
				$data['_temp_license_blob'] = '@TEMP_LICENSE_DATE=>'.DateHelper::todayToSQLDate()."\n";
			}

			foreach ($data as $col => $val)
			{
				if (preg_match('@^NEW_(.*)@', $col, $matches) == 1)
				{
					// This is a new license, see if we save it for approval or update it now
					$col_name = $matches[1];
					if ($is_root)
					{
						// Add our field indicator so this value saves
						$data['_'.$col_name] = $val;
					}
					else
					{
						// Save the license data for approval
						$data['_temp_license_blob'] .= $col_name.'=>'.$val."\n";
					}
				}
			}
		}
		else
		{
			// No need for super user approval, just update the license/office
			foreach ($data as $col => $val)
			{
				if (preg_match('@^NEW_(.*)@', $col, $matches) == 1)
				{
					// This is a license update column
					$col_name = $matches[1];
				
					// Add our field indicator so this value saves
					$data['_'.$col_name] = $val;
				}
			}
			
			$new_office_id = trim($data['NEW_office_id']);
			if (!empty($new_office_id))
			{
				if ($acct_row)
				{
					if ($acct_row['office_id'] != $new_office_id)
					{
						// Make sure new office is in the company's list of offices
						$offices_managed = array();
						$user_office_id = $account->get_info('office_id');
						OfficesTable::getOfficesInCompany($user_office_id, $offices_managed);
						if (isset($offices_managed[$new_office_id]))
						{
							$change_agent_office = TRUE;
						}
						else
						{
							FatalError("You do not have permission to change the agent to the new office.");
						}
					}
				}
			}
		}
		
		//debug_print($data, 'Update License Data');
		$agent = new AccountsTable($account_id);
		$modify_success = $agent->update($data, TRUE);
		if ($modify_success)
		{
			$logger->add_success_message("The agent license was successfully updated.");
			
			if ($change_agent_office && !empty($new_office_id))
			{
				$status = AccountsTable::changeAgentOffice($account_id, $new_office_id);
			}
		}
					
		// Now update the office record with any changes
		if (($office_id >= OfficesTable::$MIN_OFFICE_ID) && !empty($office_data) && !$change_agent_office)
		{
			$office_tbl = new OfficesTable();
			$office_data['_office_id'] = $office_id;
			$office_tbl->update($office_data, $quiet=true);
		}

		return $modify_success;
	}

	public static function getTempLicense(&$agent_row, $member_id=0)
	{
		$status = FALSE;

		if (empty($agent_row))
		{
			$agents = new AccountsTable();
			$agent_row = $agents->findKey($member_id, array('accountid', 'temp_license_blob'));
		}

		if (!empty($agent_row['temp_license_blob']))
		{
			// USE JSON NEXT TIME!
			$kv_pairs = preg_split("/\n|\r/", $agent_row['temp_license_blob']);
			foreach ($kv_pairs as $kv_string)
			{
				list($col, $val) = explode('=>', $kv_string);
				if (!empty($col))
				{
					$status = TRUE;
					$agent_row[$col] = $val;
				}
			}
		}
		
		return $status;
	}

	public static function changeAgentOffice($account_id, $office_id)
	{
		global $db;
		global $logger;
		$status = FALSE;
		$num_changed = 0;
		$acct_fields = array();
		
		if (empty($office_id) || !($office_id >= OfficesTable::$MIN_OFFICE_ID))
		{
			return FALSE;
		}

		if (SwiftAccount::isOfficeManager() || SwiftAccount::isSuperUser())
		{
			$timestamp = DateHelper::nowToSQLDateTime();

			$office_tbl = new OfficesTable();
			$office = $office_tbl->findKey($office_id, array('*'));
			if ($office)
			{
				/* Assign the office details to the agent */
				$acct_fields['_accountid'] = $account_id;
				$acct_fields['_office_id'] = $office_id;
				$acct_fields['_company'] = $office['office_name'];
				$acct_fields['_baddr1'] = $office['address1'];
				$acct_fields['_baddr2'] = $office['address2'];
				$acct_fields['_bcity'] = $office['city'];
				$acct_fields['_bstate'] = $office['state'];
				$acct_fields['_bzip'] = $office['zip'];
				$acct_fields['_bcounty'] = $office['county'];
				
				//debug_print($acct_fields, 'Update Office Data');
				$agent_tbl = new AccountsTable($account_id);
				$agent_row = $agent_tbl->findKey($account_id, array('accountid', 'send_broker_form_date', 'broker_name', 'broker_email'));
				$modify_success = $agent_tbl->raw_update($acct_fields, TRUE);
				if ($modify_success !== TRUE)
				{
					$logger->add_error_message("The request to change the agent's office failed.");
				}
				else
				{
					// If we changed the office and the broker form has not been sent yet, send it now
					if (DateHelper::isValidSQLDate($agent_row['send_broker_form_date']))
					{
						// We have an office id so we can send the broker the form
						$formdoc = new FormDocsBrokerParticipation();
						$status = $formdoc->sendRequestToFillEmail($account_id);
						if ($status)
						{
							$sdata = array(
								'_accountid' => $account_id,
								'_send_broker_form_date' => '0000-00-00',
								'_account_status' => 'pending'
							);
							$agent_tbl->raw_update($sdata, $quiet=true);
							$logger->add_success_message('Broker participation form sent to '.$agent_row['broker_email']);
						}
						else
						{
							$logger->add_error_message('Uh oh! An error occurred while trying to email the broker participation form.');
						}						
					}
					
					$my_query = "UPDATE listings SET office_id='" . $office_id . "', modified='" . $timestamp . "' WHERE accountid='" . $account_id . "'";
					$num_changed = $db->query_modify($my_query);
					$logger->add_success_message("The agent was successfully changed to the new office.");
					$logger->add_success_message("The agent's listings were changed to the new office.");
					$status = TRUE;
				}
			}
			else
			{
				$logger->add_warning_message("A valid office [" . $office_id . "] was not specified.");
			}				
		}
		else
		{
			FatalError('You do not have the authority to change the agent\'s office.');
		}
		
		return $status;
	}

	public static function changeAgentOfficeListings($account_id, $office_id)
	{
		global $db;
		global $logger;
		$status = FALSE;
		$num_changed = 0;
		
		$timestamp = DateHelper::nowToSQLDateTime();

		$office = OfficesTable::getRow('office_id', "office_id='" . $office_id . "'");
		if ($office)
		{
			$my_query = "UPDATE listings SET office_id='" . $office_id . "', modified='" . $timestamp . "' WHERE accountid='" . $account_id . "' AND status<=299";
			$num_changed = $db->query_modify($my_query);
			if ($num_changed > 0)
			{
				$logger->add_success_message("'Active' and 'Pending' listings were changed to the new office.");
			}
			$status = TRUE;
		}
		else
		{
			$logger->add_warning_message("A valid office [" . $office_id . "] was not specified. Listings may still be assigned to the old office.  Contact support to resolve this.");
		}				
		
		return $status;
	}

	// Determines if the current user has the authority to edit ListHub (formerly Point2) feed preferences
	public static function authPoint2($plan)
	{
		if (($plan == 'premium') || ($plan == 'standard'))
		{
			return TRUE;
		}
		
		return FALSE;
	}

	// Determines if the current user has the authority to edit Realtor.com feed preferences
	public static function authRealtorDotCom($plan)
	{
		// for now, std and premium get this
		if (($plan == 'premium') || ($plan == 'standard'))
		{
			return TRUE;
		}
		
		return FALSE;
	}

	/** Does the currently logged in user have edit authority for a listing
	 * 
	 * @param type $mls_id
	 */
	public static function canEditListing($mls_id)
	{
		
	}
	
	/** Get listings agents information
	 * 
	 * @global type $db
	 * @param type $office_id
	 * @param type $agents
	 * @return boolean
	 */
	public static function getListingAgentData(&$list_row, &$agents)
	{
		$db = SwiftDB::getReference();
		$status = FALSE;
		$agent_clauses = $agents = array();
		
		if (!empty($list_row['accountid']))
		{
			$agent_clauses[] = "accountid='".$list_row['accountid']."'";
		}
		
		if (!empty($list_row['accountid2']))
		{
			$agent_clauses[] = "accountid='".$list_row['accountid2']."'";
		}
		
		if (!empty($list_row['accountid3']))
		{
			$agent_clauses[] = "accountid='".$list_row['accountid3']."'";
		}

		if (!empty($agent_clauses))
		{
			$sql = "SELECT o.office_id, o.is_parent, o.office_name, o.address1, o.city, o.county, m.accountid, m.firstname, m.lastname, m.honorific, m.post_nom, m.title FROM offices as o, accounts as m WHERE " . implode(' OR ', $agent_clauses);

			$row2 = $db->query_select($sql, 'listagents');
			if ($row2)
			{
				$status = TRUE;
				do
				{
					$agents[$row2['accountid']] = $row2;
				} while ($row2 = $db->next_row('listagents'));
			}
		}
			
		return $status;
	}

	public static function needAccountRenewal(&$agentrow, &$notifications)
	{
		$status = FALSE; return FALSE;

		// If this is a paid account, see if it is expiring soon
		if (($agentrow['is_free_account'] == 0) && !DateHelper::isEmptyDate($agentrow['renew_date']))
		{
			// Is it already expired or not? Note: renew_date is the date agent last renewed
			$unit = ($agentrow['pcycle'] == 'monthly') ? 'month' : 'year';
			$next_renew_date = DateHelper::dateMath($agentrow['renew_date'], '+', '1', $unit);
			if (DateHelper::compareSQLDates($next_renew_date, '<', DateHelper::todayToSQLDate()))
			{
				$status = TRUE;
				$notifications[] = 'Your account is past due and needs to be renewed. Please contact us.';
			}
			else
			{
				if ($agentrow['pcycle'] == 'yearly')
				{
					$expire_days = DateHelper::dateDiff($next_renew_date, DateHelper::todayToSQLDate(), 'days');
					if ($expire_days <= 30)
					{
						if ($agentrow['pay_method'] == 'cc')
						{
							$notifications[] = 'Your account <strong>will auto renew in '.$expire_days.' days</strong>.';
						}
						else if ($agentrow['pay_method'] != 'free')
						{
							$notifications[] = 'Your account <strong>will expire in '.$expire_days.' days</strong>. Please contact us to renew your membership.';
						}
					}
				}
			}
		}
		
		return $status;
	}

	public static function needLicenseRenewal(&$agentrow, &$notifications)
	{
		$status = FALSE; return FALSE;

		// If this is an agent, see if the license is expiring soon
		if (!DateHelper::isEmptyDate($agentrow['agent_license_expire']))
		{
			// Is it already expired or not?
			if (DateHelper::compareSQLDates($agentrow['agent_license_expire'], '<', DateHelper::todayToSQLDate()))
			{
				$status = TRUE;
				$notifications[] = 'Our records indicate your agent license <strong>has expired</strong>. Please login and update your license or contact us.';
			}
			else
			{
				$expire_days = DateHelper::dateDiff($agentrow['agent_license_expire'], DateHelper::todayToSQLDate(), 'days');
				if ($expire_days <= 30)
				{
					$notifications[] = 'Our records indicate your agent license <strong>will expire in '.$expire_days.' days</strong>. Please make sure your profile is up to date if you have changed offices.';
				}
			}
		}
		
		return $status;
	}
	
	public static function setOptListingSearchView($search_view)
	{
		$status = FALSE;
		$account = SwiftAccount::getReference();
		$db = SwiftDB::getReference();
		$account_id = $account->get_id();
		
		if ($account_id >= AccountsTable::$MIN_ACCOUNT_ID)
		{
			$my_query = "UPDATE accounts SET modified=NOW(), opt_listing_search_view='%s' WHERE accountid='%s' LIMIT 1";
			$status = $db->query_modify_safe($my_query, $search_view, $account_id);
			if ($status == 1)
			{
				// Update our session
				$account->setOptListingSearchView($search_view);
				$status = TRUE;
			}
		}
		
		return $status;
	}
	
	public static function forceLogout($account_id)
	{
		global $logger;
		$db = SwiftDB::getReference();

		$session_path = session_save_path();
		$sql = "SELECT accountid, last_session_id FROM accounts WHERE accountid='%s' LIMIT 1";
		$row = $db->query_select_safe($sql, $account_id);
		if ($row && !empty($row['last_session_id']))
		{
			$session_file = $session_path.DIRECTORY_SEPARATOR.'sess_'.$row['last_session_id'];
			if (is_file($session_file))
			{
				unlink($session_file);
				$logger->add_success_message("User force logged out. Session destroyed.");
			}
			else
			{
				$logger->add_warning_message("User is not logged in. Session file does not exist.");
			}
		}
		else
		{
			$logger->add_warning_message("Account ID '$account_id' has not logged in yet.");
		}
		unset($_COOKIE['hash_key']);
		setcookie('hash_key', null, -1, "/");
		return $status;
	}

	public static function addPhoto($account_id, $photo_url, $dbg=FALSE)
	{
		global $db;
		$photo_num = 0;
		$lrow = array();
		$prow = array();
		
		// We must have an mls id and source id for reference
		if (empty($account_id) || (!($account_id >= 51237)) || empty($photo_url))
		{
			if ($dbg) echo "---- Agent Id or Source Photo Url is not valid ----\n";
			return FALSE;
		}

		// Source photo url must be valid
		if (!IsValid::Url($photo_url, FALSE, TRUE))
		{
			if ($dbg) echo "---- Photo url format is not valid ----\n";
			return FALSE;
		}
		else
		{
			// We have a valid photo url so make sure it's really an image
			exec("wget --spider --timeout=30 '".$photo_url."' 2>&1 | grep -i 'Length:'", $output, $rc);

			if ($output && ($rc == 0))
			{
				// Make sure it's a valid image type - Microsoft made up mime type progressive jpeg as pjpeg
				if (preg_match('@\[image/(jpeg|jpg|pjpeg|gif|png)\]@i', $output[0], $matches) != 1)
				{
					if ($dbg) echo "---- Photo url does not resolve to an image ----\n";
					return FALSE;
				}
			}
			else
			{
				if ($dbg) echo "---- Photo url does not exist on server (or server is not responding) ----\n";
				return FALSE;
			}
		}

		if (preg_match('@\A(http|https)://@i', $photo_url) != 1)
		{
			$photo_url = 'http://'.$photo_url;
		}
		
		$timestamp = DateHelper::nowToSQLDateTime();
		$sql = "UPDATE ".self::$THIS_TABLE." SET photo_timestamp='$timestamp', photo_url='%s' WHERE accountid='%s'";
		$res = $db->query_modify_safe($sql, $photo_url, $account_id);
		if (!$res)
		{
			if ($dbg) echo "---- Photo failed to insert/update in the database table ----\n";
			return FALSE;
		}

		return TRUE;
	}

	public static function getDefaultPhoto()
	{
		return get_image_uri().'/ACTIMG/no-profile.jpg';
	}
	
	public static function getPhotoUrl($member_id, $use_default=TRUE)
	{
		global $account;
		$account_id = 0;
		$photo_url = '';
		$timestamp = '';
		
		if (!empty($member_id))
		{
			if (is_object($account) && $account->is_logged_in())
			{
				$account_id = $account->get_id();
			}

			// If the user logged in is who we are getting the photo for, just get it from our session
			if ($member_id == $account_id)
			{
				$photo_url = $account->get_info('photo_url');
				$timestamp = $account->get_info('photo_timestamp');
			}
			else
			{
				// It's another user so get it from their account info
				$agent = new AccountsTable($member_id);
				$row = $agent->findKey($member_id, array('photo_url', 'photo_timestamp'));
				$photo_url = $row['photo_url'];
				$timestamp = $row['photo_timestamp'];
			}
		}

		if (!empty($photo_url))
		{
			// We expect our photo to reside on one of our servers, either nystate, mystate, or our Rackspace CDN
			$timestamp = preg_replace('/\D/', '', $timestamp);
			$photo_url .='?'.$timestamp;
		}
		else
		{
			if ($use_default)
			{
				$photo_url = self::getDefaultPhoto();
			}
			else
			{
				$photo_url = FALSE;
			}
		}
		
		return $photo_url;
	}
	
	public static function getPhotoUrlFromData(&$row, $use_default=TRUE)
	{
		$photo_url = $row['photo_url'];
		$timestamp = $row['photo_timestamp'];

		if (!empty($photo_url))
		{
			// We expect our photo to reside on one of our servers, either nystate, mystate, or our Rackspace CDN
			$timestamp = preg_replace('/\D/', '', $timestamp);
			$photo_url .='?'.$timestamp;
		}
		else
		{
			if ($use_default)
			{
				$photo_url = self::getDefaultPhoto();
			}
			else
			{
				$photo_url = FALSE;
			}
		}
		
		return $photo_url;
	}

	public static function getLogoUrl($member_id)
	{
		global $account;
		global $db;

		$account_id = 0;
		$photo_url = '';
		$timestamp = '';
		
		if (!empty($member_id))
		{
			if (is_object($account) && $account->is_logged_in())
			{
				$account_id = $account->get_id();
			}

			if ($member_id == $account_id)
			{
				$photo_url = $account->get_info('logo_url');
				$timestamp = $account->get_info('logo_timestamp');
			}
			else
			{
				$sql = "SELECT o.logo_url, o.logo_timestamp FROM offices as o, accounts as a WHERE a.accountid='%s' AND a.office_id=o.office_id LIMIT 1";
				$row = $db->query_select_safe_qid($sql, '_glfa', $member_id);
				$photo_url = $row['logo_url'];
				$timestamp = $row['logo_timestamp'];
			}
		}

		if (!empty($photo_url))
		{
			// We expect the photo/logo to reside on one of our servers, either nystate, mystate, or our Rackspace CDN
			$timestamp = preg_replace('/\D/', '', $timestamp);
			$photo_url .='?'.$timestamp;
		}
		else
		{
			$photo_url = FALSE;
		}
		
		return $photo_url;
	}

	public static function formatUSPhone($phone)
	{
		$nice_phone = '';
		$sphone = preg_replace('/\D/', '', $phone);
		if (strlen($sphone) == 10)
		{
			$nice_phone = substr($sphone, 0, 3).'-'.substr($sphone, 3, 3).'-'.substr($sphone, 6, 4);
		}
		else if (strlen($sphone) == 11)
		{
			$nice_phone = substr($sphone, 0, 1).'-'.substr($sphone, 1, 3).'-'.substr($sphone, 3, 3).'-'.substr($sphone, 6, 4);
		}
		else
		{
			$nice_phone = $phone;
		}
		
		return $nice_phone;
	}
	
	public static function getPrimaryLicenseState(&$agent_data)
	{
		$lic_state = substr($agent_data['agent_license_state'], 0, 2);
		if (empty($lic_state))
		{
			$lic_state = $agent_data['bstate'];
		}
		
		return strtoupper($lic_state);
	}
	
	public static function weeklyEmailUnsubscribe($account_id)
	{
		$db = SwiftDB::getReference();
		
		if ($account_id >= self::$MIN_ACCOUNT_ID)
		{
			$sql = "UPDATE accounts SET weekly_email=0 WHERE accountid='%s' LIMIT 1";
			$db->query_modify_safe($sql, $account_id);
			return TRUE;
		}
		
		return FALSE;
	}
	
	public static function signupReminderEmailUnsubscribe($account_id)
	{
		$db = SwiftDB::getReference();
		
		if ($account_id >= self::$MIN_ACCOUNT_ID)
		{
			$sql = "UPDATE accounts SET signup_pay_email=0 WHERE accountid='%s' LIMIT 1";
			$db->query_modify_safe($sql, $account_id);
			return TRUE;
		}
		
		return FALSE;
	}
	
	public static function migrateAccount($from_account_id, $to_account_id, &$columns_to_migrate)
	{
		$status = FALSE;
		if (($from_account_id >= self::$MIN_ACCOUNT_ID) && ($to_account_id >= self::$MIN_ACCOUNT_ID))
		{
			$from_row = self::getRow('*', "accountid='".SwiftDB::get_real_escape_string($from_account_id)."'");
			$to_row = self::getRow('*', "accountid='".SwiftDB::get_real_escape_string($to_account_id)."'");
			
			if ($from_row && $to_row)
			{
				$accounts_tbl = new AccountsTable($to_account_id);
				$new_data = array();
				$new_data['_accountid'] = $to_account_id;
				$new_data['_accountactive'] = 1;
				$new_data['_is_web_login'] = 1;
				$new_data['_weekly_email'] = 1;
				$new_data['_is_free_account'] = 0;
				foreach ($columns_to_migrate as $col)
				{
					$new_data['_'.$col] = $from_row[$col];
				}
				$update_success = $accounts_tbl->raw_update($new_data, $quiet=true);
				
				if ($update_success)
				{
					unset($accounts_tbl);
					$accounts_tbl = new AccountsTable($from_account_id);
					$new_data = array();
					$new_data['_accountid'] = $from_account_id;
					$new_data['_accountpw'] = 'PW_MIGRATED';
					$new_data['_accountactive'] = 0;
					$new_data['_is_web_login'] = 0;
					$new_data['_weekly_email'] = 0;
					$new_data['_is_free_account'] = 0;
					foreach ($columns_to_migrate as $col)
					{
						switch ($col)
						{
							case 'stripe_id':
							case 'stripe_plan_id':
							case 'pcardtype':
							case 'pcardsafe':
							case 'pcardexpmo':
							case 'pcardexpyr':
								$new_data['_'.$col] = '';
								break;
						}
					}
					$update_success = $accounts_tbl->raw_update($new_data, $quiet=true);
					$status = ($update_success) ? true : false;
				}
			}
		}
		
		return $status;
	}

	public static function viewOneProfile(&$myrow, $is_self=FALSE)
	{
		global $page;
?>
	<div style="float: left; padding-top: 10px;">
	<table id="ProfileInfoTable" cellpadding="0" cellspacing="0" border="0">

	<tr>
		<th>&nbsp</th>
		<td id="ProfileTitleBlock">
		<strong style="font-size: 1.2em;"><?= $myrow['honorific']; ?> <?= $myrow['firstname']; ?> <?= $myrow['lastname']; ?></strong>
		</td>
	</tr>

	<?php if ($is_self) : ?>
	<tr>
		<th>&nbsp;</th>
		<td id="ProfileEditProfileBlock"><a class="ActionLink" href="<?=ROOT_DIR?>Accounts/Update/" title="Edit My Profile"><img src="<?= get_image_uri(); ?>/icon-edit.gif" alt="Edit My Profile" /> Edit My Profile</a>&nbsp;&nbsp;&nbsp;&middot;&nbsp;&nbsp;&nbsp;<a class="ActionLink" href="?page=ChangeMyPassword" title="Change My Password">Change Password</a></td>
	</tr>

	<tr>
		<th>Public Page</th>
		<?php $public_url = $page->web_root . '/' . $myrow['@clean_path']; ?>
		<td id="ProfilePublicURLBlock"><a href="<?= $public_url; ?>" title="View My Public Profile">View Your Public Profile (Agent Directory) Page</a></td>
	</tr>

	<tr><td id="ProfileDivider" colspan="2"></td></tr>
	<tr><td id="ProfileDividerPad" colspan="2"></td></tr>
	<?php endif ?>
	
	<tr><td id="ProfileDivider" colspan="2"></td></tr>
	<tr><td id="ProfileDividerPad" colspan="2"></td></tr>

	<?php if ((!empty($myrow['title'])) || $is_self) : ?>
	<tr>
		<th>Title</th>
		<td id="ProfileTitleBlock"><?= $myrow['title']; ?></td>
	</tr>
	
	<tr><td id="ProfileDivider" colspan="2"></td></tr>
	<tr><td id="ProfileDividerPad" colspan="2"></td></tr>
	<?php endif ?>

	<tr>
		<th>Company</th>
		<td id="ProfileCompanyBlock"><span id="ProfileCompanyText"><?= $myrow['company']; ?></span></td>
	</tr>
	
	<tr><td id="ProfileDivider" colspan="2"></td></tr>
	<tr><td id="ProfileDividerPad" colspan="2"></td></tr>
	
	<tr>
		<th>Address</th>
		<td id="ProfileAddressBlock">
		<?= $myrow['baddr1']; ?><br />
		<?php if (!empty($myrow['baddr2'])) : ?>
		<?= $myrow['baddr2'] . '<br />'; ?>
		<?php endif ?>
		<?= $myrow['bcity']; ?>, <?php echo $myrow['bstate']; ?> <?= $myrow['bzip'] ?>
		</div>
		</td>
	</tr>

	<tr>
		<th style="white-space: nowrap;">Primary County</th>
		<td id="ProfileCountyBlock"><?= $myrow['bcounty']; ?></td>
	</tr>

	<tr><td id="ProfileDivider" colspan="2"></td></tr>
	<tr><td id="ProfileDividerPad" colspan="2"></td></tr>

	<?php if (!empty($myrow['bphone'])) : ?>
	<tr>
		<th>Main Office Phone</th>
		<td id="ProfilePhoneBlock"><?= self::formatUSPhone($myrow['bphone']); ?></td>
	</tr>
	<?php endif ?>
	
	<?php if (!empty($myrow['biphone'])) : ?>
	<tr>
		<th>Office Phone</th>
		<td id="ProfilePhoneBlock"><?= self::formatUSPhone($myrow['biphone']); ?></td>
	</tr>
	<?php endif ?>

	<?php if (!empty($myrow['bcell'])) : ?>
	<tr>
		<th>Mobile Phone</th>
		<td id="ProfilePhoneBlock"><?= self::formatUSPhone($myrow['bcell']); ?></td>
	</tr>
	<?php endif ?>

	<?php if (!empty($myrow['bfax'])) : ?>
	<tr>
		<th>Fax</th>
		<td id="ProfilePhoneBlock"><?= self::formatUSPhone($myrow['bfax']); ?></td>
	</tr>
	<?php endif ?>

	<?php //if (($myrow['accountplan'] == 'premium') || $is_self || ($myrow['is_enhanced'])) : ?>
	
	<tr><td id="ProfileDivider" colspan="2"></td></tr>
	<tr><td id="ProfileDividerPad" colspan="2"></td></tr>

	<tr>
		<th>E-mail</th>
		<td id="ProfileEmailBlock"><img src="<?= get_vendor_uri(); ?>/font/FontToImage.php?font-size=12&text=<?= urlencode(base64_encode($myrow['accountemail'])); ?>" border="0" /></td>
	</tr>
	
	<?php if (!empty($myrow['accounturl'])) : ?>
	<tr>
		<th>Web Site</th>
		<?php $clean_url = SwiftURL::getCleanURL($myrow['accounturl']); ?>
		<td id="ProfileWebsiteBlock"><a class="AgentWebLink1" href="<?= $clean_url; ?>" target="_blank" title="<?= full_name; ?> Web Address"><?= $clean_url; ?></a></td>
	</tr>
	<?php endif // empty url? ?>
	
	<?php if ((!empty($myrow['profile_html'])) || $is_self) : ?>
	<tr><td id="ProfileDivider" colspan="2"></td></tr>
	<tr><td id="ProfileDividerPad" colspan="2"></td></tr>

	<tr>
		<th>Profile</th>
		<td id="ProfileTextBlock" style="max-width: 600px;"><?= ((empty($myrow['profile_html'])) && $is_self) ? 'You have not entered any profile text.' : $myrow['profile_html']; ?></td>
	</tr>
	<?php endif // empty profile? ?>
	<?php //endif // is premium member? ?>	
	</table>
	</div>
<?php
	} // end viewOneProfile
}