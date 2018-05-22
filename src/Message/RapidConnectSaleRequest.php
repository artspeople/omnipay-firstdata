<?php

namespace Omnipay\FirstData\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class RapidConnectSaleRequest extends RapidConnectCreditRequest
{

	/**
	 * @return \SimpleXMLElement
	 */
	function getData()
	{
		$xml = <<<'XML'
<?xml version="1.0" encoding="utf-8"?>
<Request
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:xsd="http://www.w3.org/2001/XMLSchema" Version="3" ClientTimeout="30"
    xmlns="http://securetransport.dw/rcservice/xml">
    <ReqClientID>
        <DID></DID>
        <App></App>
        <Auth></Auth>
        <ClientRef></ClientRef>
    </ReqClientID>
    <Transaction>
        <ServiceID></ServiceID>
        <Payload></Payload>
    </Transaction>
</Request>
XML;
		$data = new \SimpleXMLElement($xml);
		$data->ReqClientID->DID = $this->getDID();
		$data->ReqClientID->APP = $this->getApp();
		$data->ReqClientID->ClientRef = $this->getClientRef();
		$data->Transaction->ServiceID = $this->getServiceID();

		$this->addCommonGroup($data);
		$this->addBillPaymentGroup($data);
		$this->addCardGroup($data);
		$this->addPinGroup($data);
		$this->addEcommGroup($data);
		$this->addVisaGroup($data);
		$this->addMastercardGroup($data);
		$this->addDiscoverGroup($data);
		$this->addAmexGroup($data);
		$this->addCustomerInfoGroup($data);
		$this->addOrderGroup($data);
		$this->addResponseGroup($data);
		$this->addOriginalAuthorizationGroup($data);
		$this->addProductCodeGroup($data);
		$this->addFileDownloadGroup($data);
		$this->addLodgingGroup($data);
		$this->addAutoRentalGroup($data);

		return $data;
	}


	function sendData($data)
	{
		$headers = array(
		    "Connection" => "keep-alive",
		    "Cache-Control" => "no-cache",
		    "Content-Type" => "text/xml"
		);
		$data = $data->saveXml();
		$httpResponse = $this->httpClient->request("POST", $this->getEndpoint(), $headers, $data);

		return $this->response = new RapidConnectResponse($this, $httpResponse->getBody()->getContents());
	}


	/**
	 * @param \SimpleXMLElement $data
	 */
	public function addCommonGroup(\SimpleXMLElement $data)
	{
		// Mandatory
		if (!$this->validatePaymentType()) {
		    throw new InvalidRequestException("Invalid payment type");
		}
		$data->CommonGrp->PymtType = $this->getPaymentType();

		// Mandatory
		if (!$this->validateTransactionType()) {
		    throw new InvalidRequestException("Invalid transaction type");
		}
		$data->CommonGrp->TxnType = $this->getTransactionType();

		// Mandatory
		if (!$this->validateLocalDateandTime()) {
		    throw new InvalidRequestException("Invalid local date and time");
		}
		$data->CommonGrp->LocalDateTime = $this->getLocalDateandTime();

		// Mandatory
		if (!$this->validateTransmissionDateandTime()) {
		    throw new InvalidRequestException("Invalid transmission date and time");
		}
		$data->CommonGrp->TrnmsnDateTime = $this->getTransmissionDateandTime();

		// Mandatory
		if (!$this->validateSTAN()) {
		    throw new InvalidRequestException("Invalid stan");
		}
		$data->CommonGrp->STAN = $this->getSTAN();

		// Mandatory
		if (!$this->validateReferenceNumber()) {
		    throw new InvalidRequestException("Invalid reference number");
		}
		$data->CommonGrp->RefNum = $this->getReferenceNumber();

		// Conditional
		if ($this->getOrderNumber() !== null) {
		if (!$this->validateOrderNumber()) {
		    throw new InvalidRequestException("Invalid order number");
		}
		$data->CommonGrp->OrderNum = $this->getOrderNumber();
		}

		// Mandatory
		if (!$this->validateTPPID()) {
		    throw new InvalidRequestException("Invalid tpp id");
		}
		$data->CommonGrp->TPPID = $this->getTPPID();

		// Mandatory
		if (!$this->validateTerminalID()) {
		    throw new InvalidRequestException("Invalid terminal id");
		}
		$data->CommonGrp->TermID = $this->getTerminalID();

		// Mandatory
		if (!$this->validateMerchantID()) {
		    throw new InvalidRequestException("Invalid merchant id");
		}
		$data->CommonGrp->MerchID = $this->getMerchantID();

		// Optional
		if ($this->getMerchantCategoryCode() !== null) {
		if (!$this->validateMerchantCategoryCode()) {
		    throw new InvalidRequestException("Invalid merchant category code");
		}
		$data->CommonGrp->MerchCatCode = $this->getMerchantCategoryCode();
		}

		// Mandatory
		if (!$this->validatePOSEntryMode()) {
		    throw new InvalidRequestException("Invalid pos entry mode");
		}
		$data->CommonGrp->POSEntryMode = $this->getPOSEntryMode();

		// Mandatory
		if (!$this->validatePOSConditionCode()) {
		    throw new InvalidRequestException("Invalid pos condition code");
		}
		$data->CommonGrp->POSCondCode = $this->getPOSConditionCode();

		// Mandatory
		if (!$this->validateTerminalCategoryCode()) {
		    throw new InvalidRequestException("Invalid terminal category code");
		}
		$data->CommonGrp->TermCatCode = $this->getTerminalCategoryCode();

		// Mandatory
		if (!$this->validateTerminalEntryCapability()) {
		    throw new InvalidRequestException("Invalid terminal entry capability");
		}
		$data->CommonGrp->TermEntryCapablt = $this->getTerminalEntryCapability();

		// Mandatory
		if (!$this->validateTransactionAmount()) {
		    throw new InvalidRequestException("Invalid transaction amount");
		}
		$data->CommonGrp->TxnAmt = $this->getTransactionAmount();

		// Mandatory
		if (!$this->validateTransactionCurrency()) {
		    throw new InvalidRequestException("Invalid transaction currency");
		}
		$data->CommonGrp->TxnCrncy = $this->getTransactionCurrency();

		// Mandatory
		if (!$this->validateTerminalLocationIndicator()) {
		    throw new InvalidRequestException("Invalid terminal location indicator");
		}
		$data->CommonGrp->TermLocInd = $this->getTerminalLocationIndicator();

		// Mandatory
		if (!$this->validateCardCaptureCapability()) {
		    throw new InvalidRequestException("Invalid card capture capability");
		}
		$data->CommonGrp->CardCaptCap = $this->getCardCaptureCapability();

		// Mandatory
		if (!$this->validateGroupID()) {
		    throw new InvalidRequestException("Invalid group id");
		}
		$data->CommonGrp->GroupID = $this->getGroupID();

		// Conditional
		if ($this->getPOSID() !== null) {
		if (!$this->validatePOSID()) {
		    throw new InvalidRequestException("Invalid pos id");
		}
		$data->CommonGrp->POSID = $this->getPOSID();
		}

		// Optional
		if ($this->getClerkID() !== null) {
		if (!$this->validateClerkID()) {
		    throw new InvalidRequestException("Invalid clerk id");
		}
		$data->CommonGrp->ClerkID = $this->getClerkID();
		}

		// Optional
		if ($this->getServiceEntitlementNumber() !== null) {
		if (!$this->validateServiceEntitlementNumber()) {
		    throw new InvalidRequestException("Invalid service entitlement number");
		}
		$data->CommonGrp->SENum = $this->getServiceEntitlementNumber();
		}

		// Conditional
		if ($this->getPINLessPOSDebitFlag() !== null) {
		if (!$this->validatePINLessPOSDebitFlag()) {
		    throw new InvalidRequestException("Invalid pinless pos debit flag");
		}
		$data->CommonGrp->PLPOSDebitFlg = $this->getPINLessPOSDebitFlag();
		}

		// Conditional
		if ($this->getNetworkAccessIndicator() !== null) {
		if (!$this->validateNetworkAccessIndicator()) {
		    throw new InvalidRequestException("Invalid network access indicator");
		}
		$data->CommonGrp->NetAccInd = $this->getNetworkAccessIndicator();
		}

		// Optional
		if ($this->getMerchantEcho() !== null) {
		if (!$this->validateMerchantEcho()) {
		    throw new InvalidRequestException("Invalid merchant echo");
		}
		$data->CommonGrp->MerchEcho = $this->getMerchantEcho();
		}

		// Conditional
		if ($this->getWalletIdentifier() !== null) {
		if (!$this->validateWalletIdentifier()) {
		    throw new InvalidRequestException("Invalid wallet identifier");
		}
		$data->CommonGrp->WltID = $this->getWalletIdentifier();
		}

		// Conditional
		if ($this->getNonUSMerchant() !== null) {
		if (!$this->validateNonUSMerchant()) {
		    throw new InvalidRequestException("Invalid non us merchant");
		}
		$data->CommonGrp->NonUSMerch = $this->getNonUSMerchant();
		}

		// Conditional
		if ($this->getDeviceBatchID() !== null) {
		if (!$this->validateDeviceBatchID()) {
		    throw new InvalidRequestException("Invalid device batch id");
		}
		$data->CommonGrp->DevBatchID = $this->getDeviceBatchID();
		}

		// Conditional
		if ($this->getDigitalWalletIndicator() !== null) {
		if (!$this->validateDigitalWalletIndicator()) {
		    throw new InvalidRequestException("Invalid digital wallet indicator");
		}
		$data->CommonGrp->DigWltInd = $this->getDigitalWalletIndicator();
		}

		// Conditional
		if ($this->getDigitalWalletProgramType() !== null) {
		if (!$this->validateDigitalWalletProgramType()) {
		    throw new InvalidRequestException("Invalid digital wallet program type");
		}
		$data->CommonGrp->DigWltProgType = $this->getDigitalWalletProgramType();
		}

		// Optional
		if ($this->getTransactionInitiation() !== null) {
		if (!$this->validateTransactionInitiation()) {
		    throw new InvalidRequestException("Invalid transaction initiation");
		}
		$data->CommonGrp->TranInit = $this->getTransactionInitiation();
		}
	}


	/**
	 * @param \SimpleXMLElement $data
	 */
	public function addBillPaymentGroup(\SimpleXMLElement $data)
	{
		// Conditional
		if ($this->getBillPaymentTransactionIndicator() !== null) {
		if (!$this->validateBillPaymentTransactionIndicator()) {
		    throw new InvalidRequestException("Invalid bill payment transaction
		indicator");
		}
		$data->BillPayGrp->BillPymtTxnInd = $this->getBillPaymentTransactionIndicator();
		}

		// Optional
		if ($this->getInstallmentPaymentInvoiceNumber() !== null) {
		if (!$this->validateInstallmentPaymentInvoiceNumber()) {
		    throw new InvalidRequestException("Invalid installment payment invoice
		number");
		}
		$data->BillPayGrp->InstallInvNum = $this->getInstallmentPaymentInvoiceNumber();
		}

		// Optional
		if ($this->getInstallmentPaymentDescription() !== null) {
		if (!$this->validateInstallmentPaymentDescription()) {
		    throw new InvalidRequestException("Invalid installment payment description");
		}
		$data->BillPayGrp->InstallPymntDesc = $this->getInstallmentPaymentDescription();
		}
	}


	/**
	 * @param \SimpleXMLElement $data
	 */
	public function addCardGroup(\SimpleXMLElement $data)
	{
		// Conditional
		if ($this->getAccountNumber() !== null) {
		if (!$this->validateAccountNumber()) {
		    throw new InvalidRequestException("Invalid account number");
		}
		$data->CardGrp->AcctNum = $this->getAccountNumber();
		}

		// Conditional
		if ($this->getCardExpirationDate() !== null) {
		if (!$this->validateCardExpirationDate()) {
		    throw new InvalidRequestException("Invalid card expiration date");
		}
		$data->CardGrp->CardExpiryDate = $this->getCardExpirationDate();
		}

		// Conditional
		if ($this->getTrackData() !== null) {
		if (!$this->validateTrackData()) {
		    throw new InvalidRequestException("Invalid track 1 data");
		}
		$data->CardGrp->Track1Data = $this->getTrackData();
		}

		// Conditional
		if ($this->getTrackData() !== null) {
		if (!$this->validateTrackData()) {
		    throw new InvalidRequestException("Invalid track 2 data");
		}
		$data->CardGrp->Track2Data = $this->getTrackData();
		}

		// Conditional
		if ($this->getCardType() !== null) {
		if (!$this->validateCardType()) {
		    throw new InvalidRequestException("Invalid card type");
		}
		$data->CardGrp->CardType = $this->getCardType();
		}

		// Optional
		if ($this->getCCVIndicator() !== null) {
		if (!$this->validateCCVIndicator()) {
		    throw new InvalidRequestException("Invalid ccv indicator");
		}
		$data->CardGrp->CCVInd = $this->getCCVIndicator();
		}

		// Optional
		if ($this->getCCVData() !== null) {
		if (!$this->validateCCVData()) {
		    throw new InvalidRequestException("Invalid ccv data");
		}
		$data->CardGrp->CCVData = $this->getCCVData();
		}

		// Conditional
		if ($this->getMVVMAID() !== null) {
		if (!$this->validateMVVMAID()) {
		    throw new InvalidRequestException("Invalid mvv/maid");
		}
		$data->CardGrp->MVVMAID = $this->getMVVMAID();
		}
	}


	/**
	 * @param \SimpleXMLElement $data
	 */
	public function addPinGroup(\SimpleXMLElement $data)
	{
		// Conditional
		if ($this->getPINData() !== null) {
		if (!$this->validatePINData()) {
		    throw new InvalidRequestException("Invalid pin data");
		}
		$data->PINGrp->PINData = $this->getPINData();
		}

		// Conditional
		if ($this->getKeySerialNumberData() !== null) {
		if (!$this->validateKeySerialNumberData()) {
		    throw new InvalidRequestException("Invalid key serial number data");
		}
		$data->PINGrp->KeySerialNumData = $this->getKeySerialNumberData();
		}
	}


	/**
	 * @param \SimpleXMLElement $data
	 */
	public function addEcommGroup(\SimpleXMLElement $data)
	{
		// Conditional
		if ($this->getEcommTransactionIndicator() !== null) {
		if (!$this->validateEcommTransactionIndicator()) {
		    throw new InvalidRequestException("Invalid ecomm transaction indicator");
		}
		$data->EcommGrp->EcommTxnInd = $this->getEcommTransactionIndicator();
		}

		// Conditional
		if ($this->getCustomerServicePhoneNumber() !== null) {
		if (!$this->validateCustomerServicePhoneNumber()) {
		    throw new InvalidRequestException("Invalid customer service phone number");
		}
		$data->EcommGrp->CustSvcPhoneNumber = $this->getCustomerServicePhoneNumber();
		}

		// Conditional
		if ($this->getEcommURL() !== null) {
		if (!$this->validateEcommURL()) {
		    throw new InvalidRequestException("Invalid ecomm url");
		}
		$data->EcommGrp->EcommURL = $this->getEcommURL();
		}
	}


	/**
	 * @param \SimpleXMLElement $data
	 */
	public function addVisaGroup(\SimpleXMLElement $data)
	{
		// Conditional
		if ($this->getAuthorizationCharacteristicsIndicatorACI() !== null) {
		if (!$this->validateAuthorizationCharacteristicsIndicatorACI()) {
		    throw new InvalidRequestException("Invalid authorization characteristics
		indicator (aci)");
		}
		$data->VisaGrp->ACI = $this->getAuthorizationCharacteristicsIndicatorACI();
		}

		// Conditional
		if ($this->getMarketSpecificDataIndicator() !== null) {
		if (!$this->validateMarketSpecificDataIndicator()) {
		    throw new InvalidRequestException("Invalid market specific data indicator");
		}
		$data->VisaGrp->MrktSpecificDataInd = $this->getMarketSpecificDataIndicator();
		}

		// Conditional
		if ($this->getExistingDebtIndicator() !== null) {
		if (!$this->validateExistingDebtIndicator()) {
		    throw new InvalidRequestException("Invalid existing debt indicator");
		}
		$data->VisaGrp->ExistingDebtInd = $this->getExistingDebtIndicator();
		}

		// Conditional
		if ($this->getVisaBID() !== null) {
		if (!$this->validateVisaBID()) {
		    throw new InvalidRequestException("Invalid visa bid");
		}
		$data->VisaGrp->VisaBID = $this->getVisaBID();
		}

		// Conditional
		if ($this->getVisaAUAR() !== null) {
		if (!$this->validateVisaAUAR()) {
		    throw new InvalidRequestException("Invalid visa auar");
		}
		$data->VisaGrp->VisaAUAR = $this->getVisaAUAR();
		}

		// Conditional
		if ($this->getTaxAmountCapability() !== null) {
		if (!$this->validateTaxAmountCapability()) {
		    throw new InvalidRequestException("Invalid tax amount capability");
		}
		$data->VisaGrp->TaxAmtCapablt = $this->getTaxAmountCapability();
		}

		// Conditional
		if ($this->getVisaCheckoutIndicator() !== null) {
		if (!$this->validateVisaCheckoutIndicator()) {
		    throw new InvalidRequestException("Invalid visa checkout indicator");
		}
		$data->VisaGrp->CheckoutInd = $this->getVisaCheckoutIndicator();
		}

		// Conditional
		if ($this->getAuthIndicator() !== null) {
		if (!$this->validateAuthIndicator()) {
		    throw new InvalidRequestException("Invalid auth indicator");
		}
		$data->VisaGrp->VisaAuthInd = $this->getAuthIndicator();
		}

		// Conditional
		if ($this->getStoredCredentialIndicator() !== null) {
		if (!$this->validateStoredCredentialIndicator()) {
		    throw new InvalidRequestException("Invalid stored credential indicator");
		}
		$data->VisaGrp->StoredCredInd = $this->getStoredCredentialIndicator();
		}

		// Conditional
		if ($this->getCardOnFileScheduleIndicator() !== null) {
		if (!$this->validateCardOnFileScheduleIndicator()) {
		    throw new InvalidRequestException("Invalid card on file schedule indicator");
		}
		$data->VisaGrp->CofSchedInd = $this->getCardOnFileScheduleIndicator();
		}

		// Conditional
		if ($this->getMarketSpecificDataIndicator() !== null) {
		if (!$this->validateMarketSpecificDataIndicator()) {
		    throw new InvalidRequestException("Invalid market specific data indicator");
		}
		$data->VisaGrp->MrktSpecificDataInd = $this->getMarketSpecificDataIndicator();
		}

		// Conditional
		if ($this->getAuthIndicator() !== null) {
		if (!$this->validateAuthIndicator()) {
		    throw new InvalidRequestException("Invalid auth indicator");
		}
		$data->VisaGrp->VisaAuthInd = $this->getAuthIndicator();
		}
	}


	/**
	 * @param \SimpleXMLElement $data
	 */
	public function addMastercardGroup(\SimpleXMLElement $data)
	{
		// Conditional
		if ($this->getMarketSpecificDataIndicator() !== null) {
		if (!$this->validateMarketSpecificDataIndicator()) {
		    throw new InvalidRequestException("Invalid market specific data indicator");
		}
		$data->MCGrp->MCMSDI = $this->getMarketSpecificDataIndicator();
		}

		// Conditional
		if ($this->getMarketSpecificDataIndicator() !== null) {
		if (!$this->validateMarketSpecificDataIndicator()) {
		    throw new InvalidRequestException("Invalid market specific data indicator");
		}
		$data->MCGrp->MCMSDI = $this->getMarketSpecificDataIndicator();
		}

		// Conditional
		if ($this->getDeviceTypeIndicator() !== null) {
		if (!$this->validateDeviceTypeIndicator()) {
		    throw new InvalidRequestException("Invalid device type indicator");
		}
		$data->MCGrp->DevTypeInd = $this->getDeviceTypeIndicator();
		}

		// Conditional
		if ($this->getMasterCardAdditionalData() !== null) {
		if (!$this->validateMasterCardAdditionalData()) {
		    throw new InvalidRequestException("Invalid mastercard additional data");
		}
		$data->MCGrp->MCAddData = $this->getMasterCardAdditionalData();
		}

		// Conditional
		if ($this->getAuthorizationType() !== null) {
		if (!$this->validateAuthorizationType()) {
		    throw new InvalidRequestException("Invalid authorization type");
		}
		$data->MCGrp->FinAuthInd = $this->getAuthorizationType();
		}

		// Conditional
		if ($this->getTransactionIntegrityClass() !== null) {
		if (!$this->validateTransactionIntegrityClass()) {
		    throw new InvalidRequestException("Invalid transaction integrity class");
		}
		$data->MCGrp->TranIntgClass = $this->getTransactionIntegrityClass();
		}
	}


	/**
	 * @param \SimpleXMLElement $data
	 */
	public function addDiscoverGroup(\SimpleXMLElement $data)
	{
		// Conditional
		if ($this->getAuthIndicator() !== null) {
		if (!$this->validateAuthIndicator()) {
		    throw new InvalidRequestException("Invalid auth indicator");
		}
		$data->DSGrp->DiscAuthInd = $this->getAuthIndicator();
		}

		// Optional
		if ($this->getMOTOIndicator() !== null) {
		if (!$this->validateMOTOIndicator()) {
		    throw new InvalidRequestException("Invalid moto indicator");
		}
		$data->DSGrp->MOTOInd = $this->getMOTOIndicator();
		}

		// Optional
		if ($this->getRegisteredUserIndicator() !== null) {
		if (!$this->validateRegisteredUserIndicator()) {
		    throw new InvalidRequestException("Invalid registered user indicator");
		}
		$data->DSGrp->RegUserInd = $this->getRegisteredUserIndicator();
		}

		// Optional
		if ($this->getRegisteredUserProfileChangeDate() !== null) {
		if (!$this->validateRegisteredUserProfileChangeDate()) {
		    throw new InvalidRequestException("Invalid registered user profile change
		date");
		}
		$data->DSGrp->RegUserDate = $this->getRegisteredUserProfileChangeDate();
		}

		// Conditional
		if ($this->getAuthIndicator() !== null) {
		if (!$this->validateAuthIndicator()) {
		    throw new InvalidRequestException("Invalid auth indicator");
		}
		$data->DSGrp->DiscAuthInd = $this->getAuthIndicator();
		}
	}


	/**
	 * @param \SimpleXMLElement $data
	 */
	public function addAmexGroup(\SimpleXMLElement $data)
	{
		// Conditional
		if ($this->getGoodsSoldCode() !== null) {
		if (!$this->validateGoodsSoldCode()) {
		    throw new InvalidRequestException("Invalid goods sold code");
		}
		$data->AmexGrp->GdSoldCd = $this->getGoodsSoldCode();
		}
	}


	/**
	 * @param \SimpleXMLElement $data
	 */
	public function addCustomerInfoGroup(\SimpleXMLElement $data)
	{
		// Optional
		if ($this->getAVSBillingAddress() !== null) {
		if (!$this->validateAVSBillingAddress()) {
		    throw new InvalidRequestException("Invalid avs/billing address");
		}
		$data->CustInfoGrp->AVSBillingAddr = $this->getAVSBillingAddress();
		}

		// Optional
		if ($this->getAVSBillingPostalCode() !== null) {
		if (!$this->validateAVSBillingPostalCode()) {
		    throw new InvalidRequestException("Invalid avs/billing postal code");
		}
		$data->CustInfoGrp->AVSBillingPostalCode = $this->getAVSBillingPostalCode();
		}

		// Optional
		if ($this->getCardHolderFirstName() !== null) {
		if (!$this->validateCardHolderFirstName()) {
		    throw new InvalidRequestException("Invalid card holder first name");
		}
		$data->CustInfoGrp->CHFirstNm = $this->getCardHolderFirstName();
		}

		// Conditional
		if ($this->getCardHolderLastName() !== null) {
		if (!$this->validateCardHolderLastName()) {
		    throw new InvalidRequestException("Invalid card holder last name");
		}
		$data->CustInfoGrp->CHLastNm = $this->getCardHolderLastName();
		}
	}


	/**
	 * @param \SimpleXMLElement $data
	 */
	public function addOrderGroup(\SimpleXMLElement $data)
	{
		// Optional
		if ($this->getOrderDate() !== null) {
		if (!$this->validateOrderDate()) {
		    throw new InvalidRequestException("Invalid order date");
		}
		$data->OrderGrp->OrderDate = $this->getOrderDate();
		}
	}


	/**
	 * @param \SimpleXMLElement $data
	 */
	public function addResponseGroup(\SimpleXMLElement $data)
	{
	}


	/**
	 * @param \SimpleXMLElement $data
	 */
	public function addOriginalAuthorizationGroup(\SimpleXMLElement $data)
	{
	}


	/**
	 * @param \SimpleXMLElement $data
	 */
	public function addProductCodeGroup(\SimpleXMLElement $data)
	{
		// Conditional
		if ($this->getServiceLevel() !== null) {
		if (!$this->validateServiceLevel()) {
		    throw new InvalidRequestException("Invalid service level");
		}
		$data->ProdCodeGrp->ServLvl = $this->getServiceLevel();
		}

		// Conditional
		if ($this->getNumberofProducts() !== null) {
		if (!$this->validateNumberofProducts()) {
		    throw new InvalidRequestException("Invalid number of products");
		}
		$data->ProdCodeGrp->NumOfProds = $this->getNumberofProducts();
		}
	}


	/**
	 * @param \SimpleXMLElement $data
	 */
	public function addFileDownloadGroup(\SimpleXMLElement $data)
	{
	}


	/**
	 * @param \SimpleXMLElement $data
	 */
	public function addLodgingGroup(\SimpleXMLElement $data)
	{
	}


	/**
	 * @param \SimpleXMLElement $data
	 */
	public function addAutoRentalGroup(\SimpleXMLElement $data)
	{
	}

}
