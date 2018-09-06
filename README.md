# Wordpress Theme
Bootstrap driven WordPress theme.

## Installation

1. [Download](https://github.com/mmaarten/theme/archive/master.zip) and extract zip into `wp-content/themes/` folder.
1. Run `npm install` to install Node modules.
1. Run `bower install` to install vendors.
1. Run `grunt dist` to create assets folder.
1. Activate theme via WordPress admin menu: Appearance/Themes.

## Features
- svg icons.
- Bootstrap styled [Gravity Forms](https://www.gravityforms.com/).
- Bootstrap [navbar](http://getbootstrap.com/docs/4.1/components/navbar/).
- [Advanced Custom Fields](https://www.advancedcustomfields.com/) option page.
- Menu item utilities.

### Nav Menu Item Features
This theme provides some usefull features for menu items:

#### Modal
Makes item able to toggle a modal.

Usage: Set menu item CSS class to `menu-item-modal`.

Note: Works only with 'Custom Links'. You need to set
link to e.g. `#my-modal-id` to refer to the modal.

![alt text](screenshots/menu-item-modal.png)

#### Icon
Adds an icon. 

Usage: Set menu item CSS class to `menu-item-icon-{icon_name}`.

Example: `menu-item-icon-search` creates icon with class `icon-search`.

![alt text](screenshots/menu-item-icon.png)

#### Button
Usage: Add button CSS classes to item 'CSS Classes' setting by using format `menu-item-{button_class}`.

e.g. `menu-item-btn-primary menu-item-btn-sm` adds link classes `btn btn-primary btn-sm`.

Note: `btn` class is automatically added.

![alt text](screenshots/menu-item-button.png)

#### Hide Title
Hides menu item title.

Usage: Set menu item CSS class to `menu-item-hide-title`.

#### Unlink
Removes link from menu item.

Usage: Set menu item CSS class `menu-item-unlink`.

![alt text](screenshots/menu-item-unlink.png)

#### Template
Replaces item content by use of filter.

Usage: Template can be set by using CSS class: `menu-item-template-{template_name}`.

Filter tag will be: `theme/render_nav_menu_template/template={template_name}`.

e.g. `menu-item-template-search_form`.
	
	// Set Search Form
	add_filter( 'theme/render_nav_menu_template/template=search_form', function( $output, $item, $depth, $args )
	{
		return get_search_form();

	}, 10, 4 );

![alt text](screenshots/menu-item-template.png)
