# PublishPress Authors - Hestia Child theme

Hestia Child theme for adding support to PublishPress Authors. 


## Configuring

By default we try to use the same layout used by the Hestia theme. But if you want to use a custom layout, you can define constants in the `wp-config.php` file adding the layout slug as the value: 

```PHP
/**
 * For changing the layout of the authors displayed as post meta, use the following constant:
 */
define('HESTIA_CHILD_AUTHORS_LAYOUT_POST_META', 'inline');

/**
 * For changing the layout of the authors displayed after the post content, use the following constant:
 */
define('HESTIA_CHILD_AUTHORS_LAYOUT_AFTER_POST', 'centered');

/**
 * For changing the layout of the authors displayed after the single post meta, use the following constant:
 */
define('HESTIA_CHILD_AUTHORS_LAYOUT_SINGLE_POST_META', 'inline_avatar');
```
