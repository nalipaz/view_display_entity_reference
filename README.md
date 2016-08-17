# Entity Reference: Views Display #

The standard Views Entity Reference field allows you to select a view, but does not allow you to separate these by display. Our requirement was to include a view display in a field which would then be rendered on a content page, this meant we needed to be able to select both view and display.

## What does it include? ##

The module includes:

* a new field type (`view_display_reference_item`) which stores a `target_id` (view_id**:**display_id) - this plugin implements `OptionsProviderInterface` and uses `getSettableOptions()` to populate an options array with the results of `Views::getAllViews()`
* a new field formatter (`view_display_rendered`) which gets the executable version of the view and sets the display - `prepareView` is implemented to load in the selected entity.
* a new field widget (`view_display_options`) this inherits directly from `OptionsSelectWidget`, but exclusively set to work with `view_display_reference_item`. This widget currently implements no functionality of its own.

## Installation ##

Simply clone the repo to your `modules/custom` directory

``` 
git clone git@bitbucket.org:numiko/entity-reference-view-display.git entity_reference_view_display
```

Then enable the module, you will then be able to add field of type: Reference >> View Display.