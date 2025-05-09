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
