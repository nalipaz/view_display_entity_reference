<?php

namespace Drupal\entity_reference_view_display\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\EntityOwnerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Plugin implementation of the 'view_display_options' widget.
 *
 * @FieldWidget(
 *   id = "view_display_entity_reference_autocomplete",
 *   label = @Translation("View display autocomplete"),
 *   field_types = {
 *     "view_display_reference_item"
 *   },
 *   multiple_values = TRUE
 * )
 */
class ViewDisplayEntityReferenceAutocomplete extends EntityReferenceAutocompleteWidget {

}
