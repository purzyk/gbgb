# Greyhound Board of Great Britain

Greyhound Board of Great Britain - WordPress organisation site with page builder, results display (from HYD-made additional backend) and user accounts integrated with Stadium Portal.

## Get Started

### Theme name

`base-camp/`

### Installation

`npm install` or `yarn install` and `composer install`

### Dev commands

Watch and develop: `npm run watch`
Build for production `npm run production`

## WP Config requirements

add the following to the `wp-config.php` to enable Offload Media Lite integration for AWS with roles:

```
define( 'AS3CF_AWS_USE_EC2_IAM_ROLE', true );
```

### Required plugins

- Advanced Custom Fields PRO
- Advanced Custom Fields: Table Field
- Autocomplete For Relevanssi
- Classic Editor
- Contact Form 7
- Relevanssi
- The SEO Framework
- Wordpress Automatic Plugin
- WP All Import Pro
- WP All Import - User Import Add-On
- WP All Import - ACF Add-On
- WP Offload Media Lite
- WP Mail SMTP
- iThemes Security Pro
- Multiple Roles
- GDPR Cookie Consent

## Deployment

### CI

https://app.buddy.works/mobile5/gbgb-fe/pipelines

### URLs

- https://dev-gbgb.mobilefiveapp.com/
- https://qa-gbgb.mobilefiveapp.com/

## Relevant People

### Developers

[Marcin Harasim](mailto:marcin.harasim@mobile-5.com)
[Mateusz Dorywalski](mailto:mateusz.dorywalski@mobile-5.com)
[Mateusz Nowaczyk](mailto:mateusz.nowaczyk@mobile-5.com)
[Michał Wasiuczyński](mailto:michal.wasiuczynski@mobile-5.com)
[Piotr Purzycki](mailto:piotr.purzycki@mobile-5.com)

### PMs and others

[Emily Raymond](mailto:emily.raymond@mobile-5.com-)

# Structure

```
theme-name/                                          # Theme root
├── acf-json/                                       # ACF fields
├── app/                                            # Theme logic
│   ├── config/                                     # Theme config
│   │   ├── wp/                                     # WordPress specific config
│   │   │   ├── Api/                                # Custom REST Api endpoints
│   │   │   ├── custom-post-types/                  # Custom post types
│   │   │   ├── custom-taxonomies/                  # Custom taxonomies
│   │   │   ├── admin-page.php                      # Register here WordPress Admin Page config
│   │   │   ├── image-sizes.php                     # Register here WordPress Custom image sizes
│   │   │   ├── login-page.php                      # Register here WordPress Login Page config
│   │   │   ├── maintenance.php                     # Maintenance mode config
│   │   │   ├── menus.php                           # Register here WordPress navigation menus
│   │   │   ├── page-builder.php                    # Page Builder class
│   │   │   ├── scripts-and-styles.php              # Register here WordPress scripts and styles
│   │   │   ├── security.php                        # Things that increase the site security
│   │   │   ├── sidebars.php                        # Register here WordPress sidebars
│   │   │   └── theme-supports.php                  # Register here WordPress theme features
│   │   │   └── utils.php                           # Utility functions
│   │   ├── autoload.php                            # Includes all config files (DON'T REMOVE THIS)
│   │   ├── timber.php                              # Timber specific config
│   │   └── woocommerce.php                         # Init woocommerce support
│   │   └── wp-automatic.php                        # Setup WP Automatic integration for s3
│   ├── models/                                     # Wrappers for Timber Classes
│   ├── timber-extends/                             # Extended Timber Classes
│   │   └── BaseCampSite.php                        # Extended TimberSite Class
│   ├── bootstrap.php                               # Bootstrap theme
│   ├── helpers.php                                 # Common helper functions
├── build/                                          # Theme assets and views
│   ├── config.js                                   # Custom webpack config
│   ├── webpack.config.js                           # Webpack config
├── resources/                                      # Theme assets and views
│   ├── assets/                                     # Front-end assets
│   │   ├── images/                                 # Images
│   │   ├── js/                                     # Javascripts
│   │   │   └── components/                         # Vue Components
│   │   │   └── jq-components/                      # jQuery/Native DOM Components
│   │   │   └── services/                           # external API classes
│   │   │   └── utils/                              # utility functions
│   │   │   └── main.js                             # main loader
│   │   ├── sass/                                   # Styles
│   │   │   └── components/                         # Partials
│   │   │   └── pages/                              # Pages
│   │   │   └── single/                             # Single post
│   │   │   └── structure/                          # Header and footer
│   ├── languages/                                  # Language features
│   │   ├── base-camp.pot                           # Template for translation
│   │   └── messages.php                            # Language strings
│   ├── views/                                      # Theme Twig files
│   │   ├── components/                             # Partials
│   │   ├── footer/                                 # Theme footer templates
│   │   └── header/                                 # Theme header templates
│   │   └── single/                                 # Theme single post templates
```
