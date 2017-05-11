<?php
/**
 * CubeCart v6
 * ========================================
 * CubeCart is a registered trade mark of CubeCart Limited
 * Copyright CubeCart Limited 2017. All rights reserved.
 * UK Private Limited Company No. 5323904
 * ========================================
 * Web:   http://www.cubecart.com
 * Email:  sales@cubecart.com
 * License:  GPL-3.0 https://www.gnu.org/licenses/quick-guide-gplv3.html
 */
if (!defined('CC_INI_SET')) die('Access Denied');
Admin::getInstance()->permissions('statistics', CC_PERM_READ, true);

if(isset($_GET['resend']) && $_GET['resend']>0) {
	$email_data = $GLOBALS['db']->select('CubeCart_email_log', false, array('id' => (int)$_GET['resend']));

	if($email_data) {
		$mailer = new Mailer();

		$mailer->Subject = $email_data[0]['subject'];
		$mailer->Body = $email_data[0]['content_html'];
		$mailer->AltBody = $email_data[0]['content_text'];
		$recipients = explode(',', $email_data[0]['to']);
		foreach($recipients as $recipient) {
			$mailer->AddAddress($recipient);
		}
		$mailer->Sender = $email_data[0]['from'];
		
		$email_data[0]['result'] = $mailer->Send();
		unset($email_data[0]['date'], $email_data[0]['id']);
		
		if($email_data[0]['result']) {
			$GLOBALS['main']->setACPNotify(sprintf($lang['statistics']['email_resent'],$mailer->Subject,$email_data[0]['to']));
		} else {
			$GLOBALS['main']->setACPWarning($lang['statistics']['email_not_resent']);
		}

		$GLOBALS['db']->insert('CubeCart_email_log', $email_data[0]);
		httpredir(currentPage(array('resend')));
	}
}

$GLOBALS['main']->addTabControl($lang['settings']['title_email_log'], 'email_log');
$GLOBALS['gui']->addBreadcrumb($lang['settings']['title_email_log'], currentPage());

$per_page = 25;
$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
$email_logs = $GLOBALS['db']->select('CubeCart_email_log', false, false, array('date' => 'DESC'), $per_page, $page, false);
if($email_logs!==false) {
	foreach($email_logs as $row) {
		$row['to'] = explode(',', $row['to']);
		$email_log[] = $row;
	}	
}

$GLOBALS['smarty']->assign('EMAIL_LOG', $email_log);
$count = $GLOBALS['db']->count('CubeCart_email_log', 'id');
$GLOBALS['smarty']->assign('PAGINATION_EMAIL_LOG', $GLOBALS['db']->pagination($count, $per_page, $page, 5, 'page', 'email_log'));

$page_content = $GLOBALS['smarty']->fetch('templates/statistics.emaillog.php');