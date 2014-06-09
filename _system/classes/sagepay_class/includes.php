<?php

/**************************************************************************************************
* Sage Pay Server PHP Kit Includes File
***************************************************************************************************

***************************************************************************************************
* Change history
* ==============
*
* 02/04/2009 - Simon Wolfe - Updated UI for re-brand
* 11/02/2009 - Simon Wolfe - Updated for VSP protocol 2.23
* 18/12/2007 - Nick Selby - New PHP version adapted from ASP
***************************************************************************************************
* Description
* ===========
*
* Page with no visible content, but defines the constants and functions used in other pages in the
* kit.  It also opens connections to the database and defines record sets for later use.  It is
* included at the top of every other page in the kit and is paried with the closedown scipt.
***************************************************************************************************/
ob_start(); //start output buffering
session_start(); //enable sessions

include_once('functions.php');

/***************************************************************************************************
* Values for you to update
***************************************************************************************************/

$strConnectTo='LIVE'; 	/** Set to SIMULATOR for the Sage Pay Simulator expert system, TEST for the Test Server **
							*** and LIVE in the live environment **/

$debug = 0; // Prints Sage Pay response arrays and Error Arrays to screen.  Should be set to 0 for live sites.
$runfromdatabase = true ; // Set to true once you are no longer writing any details to text file and all details are written or retrieved from your database


$iframe_height = '480' ; // non-css raw number or percentage, NO "px" on the end
$iframe_width = '100%' ; // non-css raw number or percentage, NO "px" on the end

// Set allowed payment methods
// Visa = VISA, Visa Delta = DELTA, Visa Electron = UKE, Mastercard = MC, UK Maestro = Maestor, Internation Maestro = MAESTRO, Solo = SOLO, American Express = AMEX, Japan Credit Bureau = JCB, Diners Club = DC, Laser Cards = LASER
$allowedcards = 'VISA,DELTA,UKE,MC,MAESTRO,SOLO,AMEX,JCB,DC,LASER';

/** IMPORTANT.  Set the strYourSiteFQDN value to the Fully Qualified Domain Name of your server. **
** This should start http:// or https:// and should be the name by which our servers can call back to yours **
** i.e. it MUST be resolvable externally, and have access granted to the Sage Pay servers **
** examples would be https://www.mysite.com or http://212.111.32.22/ **
** NOTE: You should leave the final / in place. **/
$strYourSiteFQDN=SITE_DOMAIN; 

/** At the end of a Sage Pay Server transaction, the customer is redirected back to the completion page **
** on your site using a client-side browser redirect. On live systems, this page will always be **
** referenced using the strYourSiteFQDN value above.  During development and testing, however, it **
** is often the case that the development machine sits behind the same firewall as the server **
** hosting the kit, so your browser might not be able resolve external IPs or dns names. **
** e.g. Externally your server might have the IP 212.111.32.22, but behind the firewall it **
** may have the IP 192.168.0.99.  If your test machine is also on the 192.168.0.n network **
** it may not be able to resolve 212.111.32.22. **
** Set the strYourSiteInternalFQDN to the internal Fully Qualified Domain Name by which **
** your test machine can reach the server (in the example above you'd use http://192.168.0.99/) **
** If you are not on the same network as the test server, set this value to the same value **
** as strYourSiteFQDN above. **
** NOTE: You should leave the final / in place. **/
$strYourSiteInternalFQDN=SITE_DOMAIN;

$maintained_getvars = 'product_id='.$_GET['product_id'].'&addressid='.$_GET['addressid'] ;

// Set up the success redirect page for completed transaction.  Only transactions where payment has been successfully taken will use this.
$success_redirect = 'home.php?action=purchasehistory&msg=success' ;

// Set up the error redirect page for errors
$error_redirect = 'home.php?action=credits&subaction=pay&msg=error&'.$maintained_getvars ;

// Redirect for Aborted transactions
$abort_redirect = 'home.php?action=credits&msg=incomplete' ;

// Set up the transaction declined, aborted rejected etc redirect page
$notauthed_redirect = 'home.php?action=credits&subaction=pay&msg=incomplete&'.$maintained_getvars ;

//Set up the card not allowed redirect page
$card_not_allowed_redirect = 'home.php?action=credits&subaction=pay&msg=cardnotallowed&'.$maintained_getvars ;

// noscript try again link
$noscript_try_again_link = 'home.php?action=credits&subaction=pay&'.$maintained_getvars ; ;

$strVendorName='iploylimited'; // Set this value to the Vendor Name assigned to you by Sage Pay or chosen when you applied
$strCurrency='GBP'; // Set this to indicate the currency in which you wish to trade. You will need a merchant number in this currency
$strTransactionType='PAYMENT'; // This can be DEFERRED or AUTHENTICATE if your Sage Pay account supports those payment types
$strPartnerID=''; /** Optional setting. If you are a Sage Pay Partner and wish to flag the transactions with your unique partner id set it here. **/

$allowgiftaid = 0;  // 0 false or 1 true
$avscv2check = 1; // AVS/CV2 checks 0 false or 1 true
$threedsecure = 0; //3D Secure 0 false or 1 true
$paymentprofile = 'LOW';  // Profile can be NORMAL or LOW - Use LOW for Payment in iframe

/**************************************************************************************************
* Global Definitions for this site
***************************************************************************************************/
$strProtocol="2.23";

if ($strConnectTo=="LIVE")
{
  $strAbortURL="https://live.sagepay.com/gateway/service/abort.vsp";
  $strAuthoriseURL="https://live.sagepay.com/gateway/service/authorise.vsp";
  $strCancelURL="https://live.sagepay.com/gateway/service/cancel.vsp";
  $strPurchaseURL="https://live.sagepay.com/gateway/service/vspserver-register.vsp";
  $strRefundURL="https://live.sagepay.com/gateway/service/refund.vsp";
  $strReleaseURL="https://live.sagepay.com/gateway/service/release.vsp";
  $strRepeatURL="https://live.sagepay.com/gateway/service/repeat.vsp";
  $strVoidURL="https://live.sagepay.com/gateway/service/void.vsp";
}
elseif ($strConnectTo=="TEST")
{
  $strAbortURL="https://test.sagepay.com/gateway/service/abort.vsp";
  $strAuthoriseURL="https://test.sagepay.com/gateway/service/authorise.vsp";
  $strCancelURL="https://test.sagepay.com/gateway/service/cancel.vsp";
  $strPurchaseURL="https://test.sagepay.com/gateway/service/vspserver-register.vsp";
  $strRefundURL="https://test.sagepay.com/gateway/service/refund.vsp";
  $strReleaseURL="https://test.sagepay.com/gateway/service/abort.vsp";
  $strRepeatURL="https://test.sagepay.com/gateway/service/repeat.vsp";
  $strVoidURL="https://test.sagepay.com/gateway/service/void.vsp";
}
else
{
  $strAbortURL="https://test.sagepay.com/simulator/VSPServerGateway.asp?Service=VendorAbortTx";
  $strAuthoriseURL="https://test.sagepay.com/simulator/VSPServerGateway.asp?Service=VendorAuthoriseTx";
  $strCancelURL="https://test.sagepay.com/simulator/VSPServerGateway.asp?Service=VendorCancelTx";
  $strPurchaseURL="https://test.sagepay.com/simulator/VSPServerGateway.asp?Service=VendorRegisterTx";
  $strRefundURL="https://test.sagepay.com/simulator/VSPServerGateway.asp?Service=VendorRefundTx";
  $strReleaseURL="https://test.sagepay.com/simulator/VSPServerGateway.asp?Service=VendorReleaseTx";
  $strRepeatURL="https://test.sagepay.com/simulator/VSPServerGateway.asp?Service=VendorRepeatTx";
  $strVoidURL="https://test.sagepay.com/simulator/VSPServerGateway.asp?Service=VendorVoidTx";
}

?>