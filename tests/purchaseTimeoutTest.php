<?php

namespace Omnipay\FirstData;

use Omnipay\Tests\TestCase;

class shortTest extends TestCase
{
    public $responses;


    public function setUp()
    {
        parent::setUp();

        if (!getenv('RAPIDCONNECT_APP') ||
            !getenv('RAPIDCONNECT_DID_ECOMM') ||
            !getenv('RAPIDCONNECT_DID_MOTO') ||
            !getenv('RAPIDCONNECT_DID_RETAIL') ||
            !getenv('RAPIDCONNECT_GROUPID') ||
            !getenv('RAPIDCONNECT_MERCHANTID_ECOMM') ||
            !getenv('RAPIDCONNECT_MERCHANTID_MOTO') ||
            !getenv('RAPIDCONNECT_MERCHANTID_RETAIL') ||
            !getenv('RAPIDCONNECT_MERCHANT_EMAIL') ||
            !getenv('RAPIDCONNECT_SERVICEID') ||
            !getenv('RAPIDCONNECT_TERMINALID') ||
            !getenv('RAPIDCONNECT_TPPID')
        ) {
            $this->markTestSkipped('Missing credentials');
        }
    }

    public function testTimeoutReversal()
    {
        // Arrange
        $expirationDate = new \DateTime();
        $expirationDate->add(new \DateInterval('P1Y'));
        $expiryMonth = $expirationDate->format('m');
        $expiryYear = $expirationDate->format('Y');

        $gateway = new RapidConnectGateway($this->getHttpClient(), $this->getHttpRequest());
        $gateway->setLocalTimeZone('PST');
        $gateway->setApp(getenv('RAPIDCONNECT_APP'));
        $gateway->setGroupID(getenv('RAPIDCONNECT_GROUPID'));
        $gateway->setServiceID(getenv('RAPIDCONNECT_SERVICEID'));
        $gateway->setTerminalID(getenv('RAPIDCONNECT_TERMINALID'));
        $gateway->setDID(getenv('RAPIDCONNECT_DID_ECOMM'));
        $gateway->setMerchantID(getenv('RAPIDCONNECT_MERCHANTID_ECOMM'));


        $requestData = array(
            'card' => array(
                'billingAddress1' => '1307 Broad Hollow Road',
                'billingPostcode' => '11747',
                'cvv' => '123',
                'number' => '4005562231212123',
                'expiryMonth' => $expiryMonth,
                'expiryYear' => $expiryYear,
                'type' => 'visa',
            ),
            'CommonGroup' => array(
                'TPPID' => str_pad(getenv('RAPIDCONNECT_TPPID'), 6, '0'),
                'POSEntryMode' => array(
                    'entryMode' => '01',
                    'pinCapability' => '2',
                ),

                'POSConditionCode' => '59',
                'TerminalCategoryCode' => '00',
                'TerminalEntryCapability' => '01',
                'TerminalLocationIndicator' => '1',
                'CardCaptureCapability' => '0',
                'MerchantCategoryCode' => '5965',
                'STAN' => '840010',
                'ReferenceNumber' => '000000840010',
                'OrderNumber' => '000000840010',
            ),
            'EcommGroup' => array(
                'EcommTransactionIndicator' => '03',
                'EcommURL' => 'google.com',
            ),
            'CustomerInformationGroup' => array(
                'AVSBillingAddress' => '1307 Broad Hollow Road',
                'AVSBillingPostalCode' => '11747',
            ),
            'amount' => '62107',
            'currency' => '840',
            'ClientRef' => '000000840010',
        );

        // Act
        $request = $gateway->purchase($requestData);
        $response = $request->send();

        // Assert
        $this->assertEquals('000', $response->getResponseCode());


        // Arrange
        sleep(35);
        unset($requestData['card']['cvv']);
        $requestData['AdditionalAmountGroups'] =
        [
            [
                'AdditionalAmount' => $requestData['amount'],
                'AdditionalAmountCurrency' => $requestData['currency'],
                'AdditionalAmountType' => 'TotalAuthAmt',
            ]
        ];
        $requestData['OriginalAuthorizationGroup'] =
        [
            'OriginalLocalDateandTime' => $request->getLocalDateandTime()
            ,'OriginalTransmissionDateandTime' => $request->getTransmissionDateandTime()
            ,'OriginalSTAN' => $request->getSTAN()
        ];
        $requestData['TransactionType'] = $request->getTransactionType();
        $requestData['PaymentType'] = $request->getPaymentType();

        // Act
        $request = $gateway->timeoutReversal($requestData);
        $response = $request->send();

        // Assert
        $this->assertEquals('000', $response->getResponseCode());

    }
}