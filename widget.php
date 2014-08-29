<?php
/**
 * Plugin Name: Widget Framework
 * Version: 0.1
 * Plugin URI: http://davidajnered.com
 * Description: Code framework for fast development of new wordpress widgets.
 * Author: David Ajnered
 */
namespace YourWidgetNamespace;

class YourlWidget extends \WP_Widget
{
    /**
     * @var Twig
     */
    private $twig;

    /**
     * @var string
     */
    private $tmplDir;

    /**
     * @var string
     *
     * array with name and input type.
     */
    private $formFields = [
        'title' => [
            'label' => 'Title',
            'type' => 'text',
        ],
        'number_of_posts' => [
            'label' => 'Number of posts',
            'type' => 'number',
        ],
        'collection' => [
            'label' => 'Collection',
            'type' => 'checkbox',
            'options' => [
                'popular' => [
                    'label' => 'Popular',
                    'value' => 'popular',
                ],
                'recent' => [
                    'label' => 'Recent',
                    'value' => 'recent',
                ],
                'similar' => [
                    'label' => 'Similar',
                    'value' => 'similar',
                ]
            ]
        ],
        'template_file' => [
            'label' => 'Template',
            'type' => 'text',
        ]
    ];

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(
            'your_widget',
            'Your Widget',
            ['description' => 'Your widget description']
        );

        // Setup variables
        $this->tmplDir = '/' . str_replace(ABSPATH, '', plugin_dir_path(__FILE__)) . 'templates/';
        $this->twig = $this->loadTwig();
    }

    /**
     * Load Twig.
     */
    private function loadTwig()
    {
        require_once plugin_dir_path(__FILE__) . '/lib/Twig/Autoloader.php';
        \Twig_Autoloader::register();
        $twigLoader = new \Twig_Loader_Filesystem(ABSPATH);
        $args = ['autoescape' => false];
        $twig = new \Twig_Environment($twigLoader, $args);

        return $twig;
    }

    /**
     * This is the function where you add all the data you want to access in your tmpl files.
     *
     * @param array $instance
     * @return array $tmplData
     */
    private function getTmplData($instance)
    {
        $tmplData = [];

        if (!empty($instance)) {
            $tmplData = $instance;
        }

        return $tmplData;
    }

    /**
     * Front end.
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        // Use the method getTmplData to add data to your templates...
        $tmplData = $this->getTmplData($instance);

        // ... or add a function in your functions.php to modify the data there.
        $customTmplData = do_action('widget_tmpl_data', $tmplData);

        // Merge $tmplData with the settings from register_sidebar(), usually found in function.php
        $tmplData = array_merge($args, $tmplData);

        // Log $tmplData here for complete list of data
        // error_log(var_export($tmplData, true));

        $tmplFile = $this->tmplDir . 'widget.html';
        if (isset($instance['tmpl_file']) && !empty($instance['tmpl_file'])) {
            $customtmplFile = get_tmpl_directory() . '/' . $instance['tmpl_file'];
            if (file_exists($customtmplFile)) {
                $tmplFile = $customtmplFile;
            }
        }

        echo $this->twig->render($tmplFile, $tmplData);
    }

    /**
     * Backend.
     *
     * @param array $instance
     */
    public function form($instance)
    {
        $formFields = [];
        foreach ($this->formFields as $fieldName => $fieldData) {
            $formFields[$fieldName] = $fieldData;

            if ($fieldData['type'] == 'checkbox') {
                $formFields[$fieldName]['selected'] = isset($instance[$fieldName]) ? $instance[$fieldName] : null;
            } else {
                $formFields[$fieldName]['value'] = isset($instance[$fieldName]) ? $instance[$fieldName] : null;
            }

            $formFields[$fieldName]['field_id'] = $this->get_field_id($fieldName);
            $formFields[$fieldName]['field_name'] = $this->get_field_name($fieldName);
        }

        $tmplData = [
            'form_fields' => $formFields,
        ];

        $tmplFile = $this->tmplDir . 'form.html';
        echo $this->twig->render($tmplFile, $tmplData);
    }

    /**
     * Update.
     *
     * @param array $newInstance
     * @param array $oldInstance
     */
    public function update($newInstance, $oldInstance)
    {
        return $newInstance;
    }
}

// Init widget
add_action('widgets_init', function () {
    register_widget('YourWidgetNamespace\YourWidget');
});

// Add admin style to make the widget look nice for your users
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('your-widget-css', '/wp-content/plugins/' . plugin_basename(__FILE__) . '/admin.css');
});
