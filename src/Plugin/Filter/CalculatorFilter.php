<?php

declare(strict_types=1);

namespace Drupal\planafolio_finanzrechner\Plugin\Filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\filter\Attribute\Filter;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;
use Drupal\filter\Plugin\FilterInterface;
use Drupal\planafolio_finanzrechner\Calculator;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Replaces [planafolio sparplanrechner] in text with a calculator.
 *
 * Optional attributes: theme="dark", lang="en", height="800".
 */
#[Filter(
  id: 'planafolio_calculator',
  title: new TranslatableMarkup('Planafolio calculators'),
  description: new TranslatableMarkup('Replaces [planafolio sparplanrechner] with a Planafolio finance calculator. Place it after "Limit allowed HTML".'),
  type: FilterInterface::TYPE_MARKUP_LANGUAGE,
  settings: [
    'theme' => 'light',
    'consent' => TRUE,
    'credit' => FALSE,
  ],
  weight: 50,
)]
final class CalculatorFilter extends FilterBase implements ContainerFactoryPluginInterface {

  private const PATTERN = '/\[planafolio\s+([a-z0-9-]+)((?:\s+[a-z]+=(?:"|&quot;)[^"&\]]*(?:"|&quot;))*)\s*\]/i';

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    private readonly RendererInterface $renderer,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    return new static($configuration, $plugin_id, $plugin_definition, $container->get('renderer'));
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state): array {
    $form['theme'] = [
      '#type' => 'radios',
      '#title' => $this->t('Default theme'),
      '#options' => ['light' => $this->t('Light'), 'dark' => $this->t('Dark')],
      '#default_value' => $this->settings['theme'],
    ];
    $form['consent'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Two-click privacy mode'),
      '#description' => $this->t('Recommended. Nothing is loaded from planafolio.com until the visitor clicks the button.'),
      '#default_value' => $this->settings['consent'],
    ];
    $form['credit'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show source link'),
      '#description' => $this->t('Shows "Calculator provided by Planafolio" below each calculator. Off by default.'),
      '#default_value' => $this->settings['credit'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode): FilterProcessResult {
    $result = new FilterProcessResult($text);
    if (stripos($text, '[planafolio') === FALSE) {
      return $result;
    }
    $text = preg_replace_callback(self::PATTERN, function (array $m) use ($langcode, $result): string {
      $settings = [
        'tool' => strtolower($m[1]),
        'theme' => $this->settings['theme'],
        'lang' => 'auto',
        'height' => 640,
        'consent' => $this->settings['consent'],
        'credit' => $this->settings['credit'],
      ];
      preg_match_all('/([a-z]+)=(?:"|&quot;)([^"&]*)(?:"|&quot;)/i', $m[2], $attrs, PREG_SET_ORDER);
      foreach ($attrs as [, $name, $value]) {
        $name = strtolower($name);
        if (in_array($name, ['theme', 'lang', 'height'], TRUE)) {
          $settings[$name] = $value;
        }
      }
      $build = Calculator::build($settings, $langcode);
      if ($build === NULL) {
        return $m[0];
      }
      $html = (string) $this->renderer->renderInIsolation($build);
      $result->addAttachments($build['#attached']);
      return $html;
    }, $text);

    return $result->setProcessedText($text);
  }

  /**
   * {@inheritdoc}
   */
  public function tips($long = FALSE): string {
    return (string) $this->t('Insert a Planafolio calculator with [planafolio sparplanrechner] or [planafolio zinseszinsrechner theme="dark"].');
  }

}
