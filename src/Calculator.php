<?php

declare(strict_types=1);

namespace Drupal\planafolio_finanzrechner;

use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Builds one calculator: iframe URL, name, two-click mode, source link.
 *
 * Shared by the block and the text filter.
 */
final class Calculator {

  private const SERVICE_ORIGIN = 'https://planafolio.com';

  /**
   * Slug => full calculator page per language (English pages under /en/).
   */
  public const TOOLS = [
    'sparplanrechner' => ['de' => '/sparplanrechner', 'en' => '/en/sparplanrechner'],
    'zinseszinsrechner' => ['de' => '/zinseszinsrechner', 'en' => '/en/zinseszinsrechner'],
    'kapitalertragsteuer-rechner' => [
      'de' => '/kapitalertragsteuer-rechner',
      'en' => '/en/capital-gains-tax-calculator',
    ],
    'vorabpauschale-rechner' => ['de' => '/vorabpauschale-rechner', 'en' => '/en/vorabpauschale-rechner'],
    'freistellungsauftrag-rechner' => [
      'de' => '/freistellungsauftrag-rechner',
      'en' => '/en/freistellungsauftrag-rechner',
    ],
    'dividenden-rechner' => ['de' => '/dividenden-rechner', 'en' => '/en/dividenden-rechner'],
    'etf-kosten-rechner' => ['de' => '/etf-kosten-rechner', 'en' => '/en/etf-kosten-rechner'],
    'entnahmerechner' => ['de' => '/entnahmerechner', 'en' => '/en/entnahmerechner'],
    'finanzielle-freiheit-rechner' => [
      'de' => '/finanzielle-freiheit-rechner',
      'en' => '/en/financial-independence-calculator',
    ],
    'rentenluecke-rechner' => ['de' => '/rentenluecke-rechner', 'en' => '/en/rentenluecke-rechner'],
    'altersvorsorgedepot' => ['de' => '/altersvorsorgedepot', 'en' => '/en/altersvorsorgedepot'],
    'fruehstart-rente-rechner' => ['de' => '/fruehstart-rente-rechner', 'en' => '/en/fruehstart-rente-rechner'],
    'inflationsrechner' => ['de' => '/inflationsrechner', 'en' => '/en/inflation-calculator'],
    'einmalanlage-oder-sparplan' => ['de' => '/einmalanlage-oder-sparplan', 'en' => '/en/einmalanlage-oder-sparplan'],
    'tilgen-oder-investieren' => ['de' => '/tilgen-oder-investieren', 'en' => '/en/tilgen-oder-investieren'],
    'rebalancing-rechner' => ['de' => '/rebalancing-rechner', 'en' => '/en/rebalancing-calculator'],
    'musterportfolios' => ['de' => '/musterportfolios', 'en' => '/en/musterportfolios'],
    'xirr-rechner' => ['de' => '/xirr-rechner', 'en' => '/en/xirr-rechner'],
    'ttwror-rechner' => ['de' => '/ttwror-rechner', 'en' => '/en/ttwror-rechner'],
  ];

  /**
   * German calculator names for the placeholder on German calculators.
   */
  private const NAMES_DE = [
    'sparplanrechner' => 'ETF-Sparplanrechner',
    'zinseszinsrechner' => 'Zinseszinsrechner',
    'kapitalertragsteuer-rechner' => 'Kapitalertragsteuer-Rechner',
    'vorabpauschale-rechner' => 'Vorabpauschale-Rechner',
    'freistellungsauftrag-rechner' => 'Freistellungsauftrag-Rechner',
    'dividenden-rechner' => 'Dividenden-Rechner',
    'etf-kosten-rechner' => 'ETF-Kostenrechner',
    'entnahmerechner' => 'Entnahmerechner',
    'finanzielle-freiheit-rechner' => 'Finanzielle-Freiheit-Rechner',
    'rentenluecke-rechner' => 'Rentenlücke-Rechner',
    'altersvorsorgedepot' => 'Altersvorsorgedepot-Rechner',
    'fruehstart-rente-rechner' => 'Frühstart-Rente-Rechner',
    'inflationsrechner' => 'Inflationsrechner',
    'einmalanlage-oder-sparplan' => 'Einmalanlage oder Sparplan?',
    'tilgen-oder-investieren' => 'Tilgen oder investieren?',
    'rebalancing-rechner' => 'ETF-Rebalancing-Rechner',
    'musterportfolios' => 'Musterportfolios',
    'xirr-rechner' => 'XIRR-Rechner',
    'ttwror-rechner' => 'TTWROR-Rechner',
  ];

  /**
   * Placeholder texts in the calculator's language.
   *
   * The calculators exist only in German and English, so the placeholder
   * follows the calculator language instead of waiting for interface
   * translations.
   */
  private const TEXTS = [
    'de' => [
      'intro' => 'Interaktiver Rechner: eigene Zahlen eingeben und das Ergebnis sofort sehen.',
      'button' => 'Rechner laden',
      'note' => 'Beim Laden wird eine Verbindung zu planafolio.com hergestellt.',
      'privacy' => 'Datenschutzerklärung',
      'credit' => 'Rechner bereitgestellt von',
    ],
    'en' => [
      'intro' => 'Interactive calculator. Enter your own numbers and see the result instantly.',
      'button' => 'Load calculator',
      'note' => 'Loading connects to planafolio.com.',
      'privacy' => 'Privacy policy',
      'credit' => 'Calculator provided by',
    ],
  ];

  /**
   * Calculator names for select lists, keyed by slug.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup[]
   *   Translated calculator names.
   */
  public static function options(): array {
    return [
      'sparplanrechner' => new TranslatableMarkup('ETF Savings Plan Calculator'),
      'zinseszinsrechner' => new TranslatableMarkup('Compound Interest Calculator'),
      'kapitalertragsteuer-rechner' => new TranslatableMarkup('Capital Gains Tax Calculator'),
      'vorabpauschale-rechner' => new TranslatableMarkup('Vorabpauschale Calculator'),
      'freistellungsauftrag-rechner' => new TranslatableMarkup('Tax Allowance Split Calculator'),
      'dividenden-rechner' => new TranslatableMarkup('Dividend Calculator'),
      'etf-kosten-rechner' => new TranslatableMarkup('ETF Cost Calculator'),
      'entnahmerechner' => new TranslatableMarkup('Withdrawal Calculator'),
      'finanzielle-freiheit-rechner' => new TranslatableMarkup('Financial Independence Calculator'),
      'rentenluecke-rechner' => new TranslatableMarkup('Pension Gap Calculator'),
      'altersvorsorgedepot' => new TranslatableMarkup('Retirement Portfolio Calculator'),
      'fruehstart-rente-rechner' => new TranslatableMarkup('Frühstart-Rente Calculator'),
      'inflationsrechner' => new TranslatableMarkup('Inflation Calculator'),
      'einmalanlage-oder-sparplan' => new TranslatableMarkup('Lump Sum or Staggered?'),
      'tilgen-oder-investieren' => new TranslatableMarkup('Repay or Invest?'),
      'rebalancing-rechner' => new TranslatableMarkup('ETF Rebalancing Calculator'),
      'musterportfolios' => new TranslatableMarkup('Model Portfolios'),
      'xirr-rechner' => new TranslatableMarkup('XIRR Calculator'),
      'ttwror-rechner' => new TranslatableMarkup('TTWROR Calculator'),
    ];
  }

  /**
   * Render array for one calculator, or NULL for an unknown slug.
   *
   * @param array $settings
   *   Keys: tool, theme (light|dark), lang (auto|de|en), height, consent,
   *   credit.
   * @param string $currentLangcode
   *   Current interface language, used when lang is "auto".
   */
  public static function build(array $settings, string $currentLangcode): ?array {
    $slug = (string) ($settings['tool'] ?? 'sparplanrechner');
    if (!isset(self::TOOLS[$slug])) {
      return NULL;
    }
    $theme = ($settings['theme'] ?? '') === 'dark' ? 'dark' : 'light';
    $lang = (string) ($settings['lang'] ?? 'auto');
    if ($lang !== 'de' && $lang !== 'en') {
      $lang = str_starts_with(strtolower($currentLangcode), 'de') ? 'de' : 'en';
    }
    $height = (int) ($settings['height'] ?? 640);
    $height = ($height >= 200 && $height <= 3000) ? $height : 640;

    $query = ['v' => 1, 'theme' => $theme, 'lang' => $lang, 'utm_medium' => 'drupal'];
    $calculator = [
      'slug' => $slug,
      'name' => $lang === 'de' ? self::NAMES_DE[$slug] : (string) self::options()[$slug],
      'texts' => self::TEXTS[$lang],
      'lang' => $lang,
      'src' => self::SERVICE_ORIGIN . '/embed/' . $slug . '?' . http_build_query($query),
      'href' => self::SERVICE_ORIGIN . self::TOOLS[$slug][$lang] . '?' . http_build_query([
        'utm_source' => 'embed',
        'utm_medium' => 'drupal',
        'utm_campaign' => $slug,
      ]),
      'theme' => $theme,
      'height' => $height,
      'placeholder_height' => min($height, 360),
      'consent' => !empty($settings['consent']),
      'credit' => !empty($settings['credit']),
      'privacy_url' => self::SERVICE_ORIGIN . ($lang === 'en' ? '/en/datenschutz' : '/datenschutz'),
    ];

    return [
      '#theme' => 'planafolio_calculator',
      '#calculator' => $calculator,
      '#attached' => ['library' => ['planafolio_finanzrechner/embed']],
    ];
  }

}
