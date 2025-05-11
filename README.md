[![](https://img.shields.io/packagist/v/inspiredminds/contao-personio.svg)](https://packagist.org/packages/inspiredminds/contao-personio)
[![](https://img.shields.io/packagist/dt/inspiredminds/contao-personio.svg)](https://packagist.org/packages/inspiredminds/contao-personio)

Contao Personio
===============

Personio API connection for Contao.

## Configuration

For the job listings you first need to configure the public XML feed of the company's job listing:

```yaml
# config/config.yaml
contao_personio:
    xml_feed: https://{YOUR_COMPANY}.jobs.personio.de/xml
```

For the Recruiting API you will need to define the Company ID, Client ID, Client Secret:

```yaml
# config/config.yaml
contao_personio:
    company_id: …
    recruiting_api_client_id: …
    recruiting_api_client_secret: …
```

It is recommended to store the Client ID and Client Secret as environment variables:

```
# .env
PERSONIO_RECRUITING_API_CLIENT_ID=
PERSONIO_RECRUITING_API_CLIENT_SECRET=
```

```
# .env.local
PERSONIO_RECRUITING_API_CLIENT_ID=…
PERSONIO_RECRUITING_API_CLIENT_SECRET=…
```

```yaml
# config/config.yaml
contao_personio:
    recruiting_api_client_id: '%env(PERSONIO_RECRUITING_API_CLIENT_ID)%'
    recruiting_api_client_secret: '%env(PERSONIO_RECRUITING_API_CLIENT_SECRET)%'
```

## Job List

The _Personio jobs_ content elements lists all available jobs. Within the content element you can set a redirect page
for the details of the job. This redirect page must be a page of the type _Personio job_ (see below).

## Job Reader

The _Personio job_ content elements acts as the reader output module for the details of a job. However, for it work, it
needs to be placed within a page of the type _Personio job_.

## Application Form

The application form can be placed either alongside the _Personio job_ reader content element, or on its own page. In
both cases the type of the page needs to be _Personio job_. If you place the form on a separate page, you can define
a redirect page in the _Personio job_ content element, so that it automatically generates link to the application form.

The application form can either be generated through the dedicated _Personio job application_ content element, or a form
from the Contao Form Generator.

### Content Element

In order to get additional form fields for your custom Personio attributes into the application form, you can use the
`ModifyApplicationFormListener` event:

```php
// src/EventListener/ModifyApplicationFormListener.php
namespace App\EventListener;

use Codefog\HasteBundle\Util\ArrayPosition;
use InspiredMinds\ContaoPersonio\Event\ModifyApplicationFormEvent;

class ModifyApplicationFormListener
{
    public function __invoke(ModifyApplicationFormEvent $event): void
    {
        $event->getForm()
            ->addFormField(
                'custom_attribute_123',
                [
                    'label' => 'Custom attribute',
                    'inputType' => 'text',
                    'eval' => ['maxlength' => 255],
                ],
                ArrayPosition::after('email'),
            )
        ;
    }
}
```

The name of the field must match the internal name of the custom attribute.

### Form Generator

Instead of using the _Personio job application_ content element you can also create your own form in the Contao Form
Generator and activate the __Store in Personio__ setting. Within the form you can then add the form fields you need. The
names of the fields must match the names of the fields in the [API](https://developer.personio.de/v1.0/reference/post_v1-recruiting-applications#form-postV1RecruitingApplications). For file uploads the field name must match the
category of the file in the API. Also keep in mind that the fields `first_name`, `last_name` and `email` are mandatory
in the Personio API.
