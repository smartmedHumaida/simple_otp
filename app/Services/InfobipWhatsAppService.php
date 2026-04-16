<?php

namespace App\Services;

use Infobip\Api\WhatsAppApi;
use Infobip\Configuration;
use Infobip\Model\WhatsAppBulkMessage;
use Infobip\Model\WhatsAppMessage;
use Infobip\Model\WhatsAppTemplateContent;
use Infobip\Model\WhatsAppTemplateDataContent;
use Infobip\Model\WhatsAppTemplateBodyContent;

class InfobipWhatsAppService
{
    private WhatsAppApi $api;
    private string $sender;

    public function __construct()
    {
        $this->api = new WhatsAppApi(
            config: new Configuration(
                host: config('services.infobip.base_url'),
                apiKey: config('services.infobip.api_key'),
            )
        );

        $this->sender = config('services.infobip.sender');
    }

    public function sendOtp(string $toPhone, string $otpCode): void
    {
        $bulkMessage = new WhatsAppBulkMessage(
            messages: [
                new WhatsAppMessage(
                    from: $this->sender,
                    to: $toPhone,
                    content: new WhatsAppTemplateContent(
                        // templateName: 'authentication_template_with_copy_code_button',
                        templateName: 'authentication',
                        templateData: new WhatsAppTemplateDataContent(
                            body: new WhatsAppTemplateBodyContent(
                                placeholders: [$otpCode]
                            )
                        ),
                        language: 'en'
                    ),
                )
            ]
        );

        $this->api->sendWhatsAppTemplateMessage($bulkMessage);
    }
}