# Planafolio Finanzrechner

Free German finance calculators for Drupal 10.3+ and 11: ETF savings plan (Sparplanrechner), compound interest (Zinseszinsrechner), capital gains tax (Kapitalertragsteuer), Vorabpauschale, pension gap (Rentenlücke), withdrawal, FIRE and more. Built for German investors and German tax rules. No account, no API key.

## Features

- 19 calculators, as a **block** and as **`[planafolio …]`** in any text format
- Two-click privacy mode, on by default: no connection to planafolio.com until the visitor clicks
- Light and dark theme, German and English calculators (follows the interface language by default)
- The calculator adjusts its height automatically
- Optional source link "Calculator provided by Planafolio", off by default

## Installation

```
composer require drupal/planafolio_finanzrechner
drush en planafolio_finanzrechner
```

## Usage

**Block:** Structure → Block layout → Place block → *Planafolio calculator*. Choose calculator, theme, language and height.

**In text:** Configuration → Text formats and editors → edit a format (e.g. Basic HTML) → enable *Planafolio calculators* and move it below *Limit allowed HTML* in the processing order. Then write:

```
[planafolio sparplanrechner]
[planafolio zinseszinsrechner theme="dark"]
[planafolio vorabpauschale-rechner lang="en" height="900"]
```

Calculator names: `sparplanrechner`, `zinseszinsrechner`, `kapitalertragsteuer-rechner`, `vorabpauschale-rechner`, `freistellungsauftrag-rechner`, `dividenden-rechner`, `etf-kosten-rechner`, `entnahmerechner`, `finanzielle-freiheit-rechner`, `rentenluecke-rechner`, `altersvorsorgedepot`, `fruehstart-rente-rechner`, `inflationsrechner`, `einmalanlage-oder-sparplan`, `tilgen-oder-investieren`, `rebalancing-rechner`, `musterportfolios`, `xirr-rechner`, `ttwror-rechner`.

## External service and privacy

The calculators run on [planafolio.com](https://planafolio.com/rechner-einbetten) and are embedded as an iframe. When a visitor's browser loads a calculator (immediately, or after the click in two-click mode), it requests `https://planafolio.com/embed/<calculator>`; like any web request this transmits the visitor's IP address, user agent and the referring page. Values typed into the calculator are processed in the browser and only sent if the visitor actively shares a result or saves it in Planafolio. The calculator reports anonymous usage counts (calculator name and the hostname of your site) without cookies or device storage. The module sends no data from your Drupal backend. The service is free and needs no account.

- Privacy policy: https://planafolio.com/datenschutz
- Terms: https://planafolio.com/agb

The calculations are models for illustration, not investment or tax advice.

## License

GPL-2.0-or-later
