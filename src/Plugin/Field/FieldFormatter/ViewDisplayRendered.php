<?php

namespace Drupal\entity_reference_view_display\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\views\Entity\View;
use Drupal\entity_reference_view_display\Plugin\Field\FieldType\ViewDisplayReferenceItem;

/**
 * Plugin implementation of the 'view_display_rendered' formatter.
 *
 * @FieldFormatter(
 *   id = "view_display_rendered",
 *   label = @Translation("View Display Rendered"),
 *   field_types = {
 *     "view_display_reference_item"
 *   }
 * )
 */
class ViewDisplayRendered extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      if ($item->_loaded == FALSE) {
        continue;
      }

      if ($item->entity instanceof View) {
        $view = views_embed_view($item->view_id, $item->display_id);

        $elements[$delta] = [
          '#markup' => render($view),
        ];
      }
      else {
        $elements[$delta] = array('#markup' => t('This is not a view'));
      }
    }

    return $elements;
  }

  /**
   * {@inheritdoc}
   *
   * Loads the entities referenced in that field across all the entities being
   * viewed.
   */
  public function prepareView(array $entities_items) {
    // Collect entity IDs to load. For performance, we want to use a single
    // "multiple entity load" to load all the entities for the multiple
    // "entity reference item lists" being displayed. 
    $ids = array();
    foreach ($entities_items as $items) {
      foreach ($items as $item) {
        $item->_loaded = FALSE;
        $this->populateItemViewAndDisplayId($item);
        $ids[] = $item->view_id;
      }
    }
    if ($ids) {
      $target_entities = \Drupal::entityManager()->getStorage('view')->loadMultiple($ids);
    }

    // For each item, pre-populate the loaded entity in $item->entity, and set
    // the 'loaded' flag.
    foreach ($entities_items as $items) {
      foreach ($items as $item) {
        if (isset($target_entities[$item->view_id])) {
          $item->entity = $target_entities[$item->view_id];
          $item->_loaded = TRUE;
        }
      }
    }
  }

  /**
   * Add the view id & display id by exploding the target id.
   */
  protected function populateItemViewAndDisplayId($item) {
    $target_id = $item->target_id;
    $target = explode(ViewDisplayReferenceItem::VIEW_DISPLAY_SEPARATOR, $target_id);
    $item->view_id = $target[0];
    $item->display_id = $target[1];
  }

}
