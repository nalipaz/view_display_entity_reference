<?php

namespace Drupal\entity_reference_view_display\Plugin\Field\FieldWidget;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\Plugin\Field\FieldWidget\OptionsSelectWidget;

/**
 * Plugin implementation of the 'view_display_options' widget.
 *
 * @FieldWidget(
 *   id = "view_display_options",
 *   label = @Translation("View display options"),
 *   field_types = {
 *     "view_display_reference_item"
 *   },
 *   multiple_values = TRUE
 * )
 */
class ViewDisplayOptions extends OptionsSelectWidget {
  
}
