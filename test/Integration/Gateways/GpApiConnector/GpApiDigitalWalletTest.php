<?php

use GlobalPayments\Api\Entities\Enums\EncyptedMobileType;
use GlobalPayments\Api\Entities\Enums\Environment;
use GlobalPayments\Api\Entities\Enums\Channel;
use GlobalPayments\Api\Entities\Enums\TransactionModifier;
use GlobalPayments\Api\Entities\Enums\TransactionStatus;
use GlobalPayments\Api\PaymentMethods\CreditCardData;
use GlobalPayments\Api\ServiceConfigs\Gateways\GpApiConfig;
use GlobalPayments\Api\ServicesContainer;
use GlobalPayments\Api\Utils\Logging\Logger;
use GlobalPayments\Api\Utils\Logging\SampleRequestLogger;
use PHPUnit\Framework\TestCase;

class GpApiDigitalWalletTest extends TestCase
{
    private $card;
    private $currency = 'EUR';
    private $amount = 10;
    private $googlePayToken;

    public function setup()
    {
        ServicesContainer::configureService($this->setUpConfig());
        $this->card = new CreditCardData();
        $this->card->expMonth = date('m');
        $this->card->expYear = date('Y', strtotime('+1 year'));
        $this->card->cardHolderName = "James Mason";
        $this->googlePayToken = '{
          "signature": "MEUCIQDRwolbHgD116pBJDKEF2TG/l0KbTBErR9PNta9gHVDbwIgQlQZZLPd/5QRsz56w5xCPtzRa8yBzJRRc9ujw7iM/K4=",
          "protocolVersion": "ECv1",
          "signedMessage": "{\"encryptedMessage\":\"u8U1a6dijdpfA7Jxwi4sO19666Bwis2/spCFAAUEP+llEINKD6ZLWgg8bMuNavicX5Jps57lmhZXhkg0oHxCAdUyAdW2nJx00Q4x5tN+lRfajIHyXSp0E1+29F1OOgbtGqV5qW7yoRUq3rAJwqVYotC5YfCpdNe/SZZV+pIMzfBV8Tm2bl9X4IHYkjzhH55xz0zQkX0Y4uxNSNuybK4n5E3n9+9dKG2bXuklxcTNylFBwaThbJL944EU3qPG0gEH5tTloqaGlqk75qnZgph7MGIxcZmb60yJYu8fKfLvbab5ClbX/5J5Fx8j6UNpGF+c400FjkZZuWCdGCetjfl9RdPIAFM8cqKnWJDTmdqGyGe+xc3o0yrIAXmfgTNpBU/B5qLYn0PKfrPh8+1UUYbPChCmVI3n/MziYDu+y6YVucHiZikWRY9e/OraBBVIpfFoPGnq\",\"ephemeralPublicKey\":\"BK/aN903Xa7imoEEXi38nfkzkOnwBVQZ0MYw8D9QG9D2q6kKtzTgZVR3s03r0MXwPqwVPmx6aKid8y+alT9XCVo\\u003d\",\"tag\":\"AyMdTQznnVLDzZmp+jXz9JhagWAokb+7DEg61vbdNwo\\u003d\"}"
        }';
    }

    public function setUpConfig()
    {
        $config = new GpApiConfig();
        $config->appId = 'i872l4VgZRtSrykvSn8Lkah8RE1jihvT';
        $config->appKey = '9pArW2uWoA8enxKc';
        $config->environment = Environment::TEST;
        $config->channel = Channel::CardNotPresent;
        $config->requestLogger = new SampleRequestLogger(new Logger("logs"));

        return $config;
    }

    public function testPayWithApplePayEncrypted()
    {
	 $this->markTestSkipped('You need a valid ApplePay token that it is valid only for 60 sec');
        $this->card->token = '{"version":"EC_v1","data":"Jguh2VrQWIpbjtmooCKw2B3yxhBQPwj0tU2FXhtJQatMmRiibhWyVcz1RwolGk2MH+zEL8o4Q3vvXQqb7XUFVaregAGm4mLn5unoTTw6/ltJjozThJ99BuNHo1QhHk6asnlNWy1JTliKq69uGvHcV9ZbBKA4pbUbcsLJu7rB5kakZXvNCLItGAFk2Iue2PMAJMGblTD76FhXbcDTpBFCJeSrupoBoEHk83HgbptaJUzUxsSCHnz0T0BPyLDcMk9cK0nzRowsUYEuH/X+lxjh6yJfkCnL6i6eFjZoonZsZXg37Mnt9kmcIammlHbGtxKXl76AeKieMuPwDMAcMDhnY9xPPM+QZo14dNksBxOV8GWuDLVYSBXmqzZ3GOruYQ29q6gpfZuqIZeiKTYArOhKH0S/ro+aX8fUbPDUP7xAkzc=","signature":"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","header":{"ephemeralPublicKey":"MFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAEHM7m7LSYllJofL8/T7Ajf6OC1J48iOvXKw4IRCJ5YK+7hkVV0iDwdLijJjtVrCp22EywLXk1VFFeJFU1X/mbMg==","publicKeyHash":"rEYX/7PdO7F7xL7rH0LZVak/iXTrkeU89Ck7E9dGFO4=","transactionId":"c943bc79e49bd3c023988a0681be4df68a30ee64c8360feba1920a320cc29bd0"}}';
        $this->card->mobileType = EncyptedMobileType::APPLE_PAY;

        $response = $this->card->charge($this->amount)
            ->withCurrency($this->currency)
            ->withModifier(TransactionModifier::ENCRYPTED_MOBILE)
            ->execute();

        $this->assertTransactionResponse($response, TransactionStatus::CAPTURED);
    }

    public function testPayWithDecryptedFlow()
    {
        $encryptedProviders = [EncyptedMobileType::GOOGLE_PAY, EncyptedMobileType::APPLE_PAY];
        foreach ($encryptedProviders as $encryptedProvider) {
            $this->card->token = '5167300431085507';
            $this->card->mobileType = $encryptedProvider;
            $this->card->cryptogram = '234234234';
            $this->card->eci = '5';

            // process an auto-settle authorization
            $response = $this->card->charge($this->amount)
                ->withCurrency($this->currency)
                ->withModifier(TransactionModifier::DECRYPTED_MOBILE)
                ->execute();

            $this->assertTransactionResponse($response, TransactionStatus::CAPTURED);
            $this->assertNotEmpty($response->authorizationCode);
        }
    }

    public function testPayWithGooglePayEncrypted()
    {
        $this->card->token = $this->googlePayToken;
        $this->card->mobileType = EncyptedMobileType::GOOGLE_PAY;

        $response = $this->card->charge($this->amount)
            ->withCurrency($this->currency)
            ->withModifier(TransactionModifier::ENCRYPTED_MOBILE)
            ->execute();

        $this->assertTransactionResponse($response, TransactionStatus::CAPTURED);
    }

    public function testGooglePayEncrypted_LinkedRefund()
    {
        $this->card->token = $this->googlePayToken;
        $this->card->mobileType = EncyptedMobileType::GOOGLE_PAY;

        $transaction = $this->card->charge($this->amount)
            ->withCurrency($this->currency)
            ->withModifier(TransactionModifier::ENCRYPTED_MOBILE)
            ->execute();

        $this->assertTransactionResponse($transaction, TransactionStatus::CAPTURED);

        $refund = $transaction->refund()
            ->withCurrency($this->currency)
            ->execute();

        $this->assertTransactionResponse($refund, TransactionStatus::CAPTURED);
    }

    public function testGooglePayEncrypted_Reverse()
    {
        $this->card->token =  $this->googlePayToken;
        $this->card->mobileType = EncyptedMobileType::GOOGLE_PAY;

        $transaction = $this->card->charge($this->amount)
            ->withCurrency($this->currency)
            ->withModifier(TransactionModifier::ENCRYPTED_MOBILE)
            ->execute();

        $this->assertTransactionResponse($transaction, TransactionStatus::CAPTURED);

        $reverse = $transaction->reverse()
            ->withCurrency($this->currency)
            ->execute();

        $this->assertTransactionResponse($reverse, TransactionStatus::REVERSED);
    }

    public function testGooglePayEncrypted_AuthAndReverse()
    {
        $this->card->token = $this->googlePayToken;
        $this->card->mobileType = EncyptedMobileType::GOOGLE_PAY;

        $transaction = $this->card->authorize($this->amount)
            ->withCurrency($this->currency)
            ->withModifier(TransactionModifier::ENCRYPTED_MOBILE)
            ->execute();

        $this->assertTransactionResponse($transaction, TransactionStatus::PREAUTHORIZED);

        $reverse = $transaction->reverse()
            ->withCurrency($this->currency)
            ->execute();

        $this->assertTransactionResponse($reverse, TransactionStatus::REVERSED);
    }

    private function assertTransactionResponse($transaction, $transactionStatus)
    {
        $this->assertNotNull($transaction);
        $this->assertEquals("SUCCESS", $transaction->responseCode);
        $this->assertEquals($transactionStatus, $transaction->responseMessage);
        $this->assertNotEmpty($transaction->transactionId);
    }

}