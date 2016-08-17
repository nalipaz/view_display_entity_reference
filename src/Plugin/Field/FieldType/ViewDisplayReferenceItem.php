<?php

namespace Drupal\entity_reference_view_display\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\OptGroup;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\TypedData\OptionsProviderInterface;
use Drupal\views\Views;
use Drupal\views\Entity\View;


/**
 * Plugin implementation of the 'view_display_reference_item' field type.
 *
 * @FieldType(
 *   id = "view_display_reference_item",
 *   label = @Translation("View Display"),
 *   description = @Translation("View display reference"),
 *   category = @Translation("Reference"),
 *   default_widget = "view_display_options",
 *   default_formatter = "view_display_rendered",
 * )
 */
class ViewDisplayReferenceItem extends FieldItemBase implements OptionsProviderInterface {

  const VIEW_DISPLAY_SEPARATOR = ':';

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return array(
      'columns' => array(
        'target_id' => array(
          'type' => 'varchar',
          'length' => 255,
        ),
      ),
      'indexes' => array(
        'target_id' => array('target_id'),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['target_id'] = DataDefinition::create('string')
      ->setLabel(t('Text'))
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function mainPropertyName() {
    return 'target_id';
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints() {
    $constraints = parent::getConstraints();
    return $constraints;
  }

  /**
   * {@inheritdoc}
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
    $element = array();
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function getPossibleValues(AccountInterface $account = NULL) {
    return $this->getSettableValues($account);
  }

  /**
   * {@inheritdoc}
   */
  public function getPossibleOptions(AccountInterface $account = NULL) {
    return $this->getSettableOptions($account);
  }

  /**
   * {@inheritdoc}
   */
  public function getSettableValues(AccountInterface $account = NULL) {
    // Flatten options first, because "settable options" may contain group
    // arrays.
    $flatten_options = OptGroup::flattenOptions($this->getSettableOptions($account));
    return array_keys($flatten_options);
  }

  /**
   * {@inheritdoc}
   */
  public function getSettableOptions(AccountInterface $account = NULL) {
    $views = Views::getAllViews();
    $views_displays = array();
    foreach ($views as $view) {
      $views_displays = array_merge($views_displays, $this->getViewDisplays($view));
    }

    $view_bundle = 'view';
    $return[$view_bundle] = $views_displays;

    return count($return) == 1 ? reset($return) : $return;
  }

  /**
   * Get an array of view displays joined by VIEW_DISPLAY_SEPARATOR
   *
   * @see getSettableOptions
   */
  public function getViewDisplays(View $view) {
    return $this->getViewDisplaysList($view->id(), $view->label(), $view->get('display'));
  }

  /**
   * Get an array of view displays joined by VIEW_DISPLAY_SEPARATOR
   *
   * @see getViewDisplays
   */
  public function getViewDisplaysList($view_id, $view_label, $view_displays) {
    $view_displays_embeds = array_filter($view_displays, array($this, 'isDisplayEmbed'));

    $view_displays_list = array();
    foreach ($view_displays_embeds as $display_id => $display) {
      $target_id = $view_id . self::VIEW_DISPLAY_SEPARATOR . $display_id;
      $display_label = $view_label . ': ' . $display['display_title'];
      $view_displays_list[$target_id] = $display_label;
    }
    return $view_displays_list;
  }

  /**
   * Function for array_filter to remove non-embed view displays.
   *
   * @see getViewDisplays
   */
  protected function isDisplayEmbed(array $view_display) {
    return ($view_display['display_plugin'] == 'embed') ? TRUE : FALSE;
  }

}
