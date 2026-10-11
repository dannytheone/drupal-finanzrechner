<?php

declare(strict_types=1);

namespace Drupal\planafolio_finanzrechner\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\planafolio_finanzrechner\Calculator;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * One Planafolio calculator as a block.
 */
#[Block(
  id: 'planafolio_calculator',
  admin_label: new TranslatableMarkup('Planafolio calculator'),
  category: new TranslatableMarkup('Planafolio'),
)]
final class CalculatorBlock extends BlockBase implements ContainerFactoryPluginInterface {

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    protected LanguageManagerInterface $languageManager,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    return new static($configuration, $plugin_id, $plugin_definition, $container->get('language_manager'));
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration(): array {
    return [
      'tool' => 'sparplanrechner',
      'theme' => 'light',
      'lang' => 'auto',
      'height' => 640,
      'consent' => TRUE,
      'credit' => FALSE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state): array {
    $c = $this->configuration;
    $form['tool'] = [
      '#type' => 'select',
      '#title' => $this->t('Calculator'),
      '#options' => Calculator::options(),
      '#default_value' => $c['tool'],
      '#required' => TRUE,
    ];
    $form['theme'] = [
      '#type' => 'radios',
      '#title' => $this->t('Theme'),
      '#options' => ['light' => $this->t('Light'), 'dark' => $this->t('Dark')],
      '#default_value' => $c['theme'],
    ];
    $form['lang'] = [
      '#type' => 'select',
      '#title' => $this->t('Language'),
      '#options' => [
        'auto' => $this->t('Site language (German or English)'),
        'de' => $this->t('German'),
        'en' => $this->t('English'),
      ],
      '#default_value' => $c['lang'],
    ];
    $form['height'] = [
      '#type' => 'number',
      '#title' => $this->t('Initial height (px)'),
      '#description' => $this->t('The calculator adjusts its height automatically once loaded.'),
      '#min' => 200,
      '#max' => 3000,
      '#step' => 10,
      '#default_value' => $c['height'],
    ];
    $form['consent'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Two-click privacy mode'),
      '#description' => $this->t('Recommended. Nothing is loaded from planafolio.com until the visitor clicks the button.'),
      '#default_value' => $c['consent'],
    ];
    $form['credit'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show source link'),
      '#description' => $this->t('Shows "Calculator provided by Planafolio" below the calculator. Off by default.'),
      '#default_value' => $c['credit'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state): void {
    foreach (['tool', 'theme', 'lang'] as $key) {
      $this->configuration[$key] = (string) $form_state->getValue($key);
    }
    $this->configuration['height'] = (int) $form_state->getValue('height');
    $this->configuration['consent'] = (bool) $form_state->getValue('consent');
    $this->configuration['credit'] = (bool) $form_state->getValue('credit');
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $langcode = $this->languageManager->getCurrentLanguage()->getId();
    $build = Calculator::build($this->configuration, $langcode) ?? [];
    if ($build && ($this->configuration['lang'] ?? 'auto') === 'auto') {
      $build['#cache']['contexts'][] = 'languages:language_interface';
    }
    return $build;
  }

}
