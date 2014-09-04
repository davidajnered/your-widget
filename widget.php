<?php
/**
 * Plugin Name: Your Widget
 * Version: 0.1
 * Plugin URI: http://davidajnered.com
 * Description: Your Widget is a code framework for fast and easy development of new wordpress widgets.
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
    private $fields = [
        'title' => [
            'label' => 'Title',
            'type' => 'text',
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
        $this->fields = apply_filters('your_widget_fields', $this->fields, $this->id_base);
        $this->tmplDir = '/' . str_replace(ABSPATH, '', plugin_dir_path(__FILE__)) . 'templates/';
        $this->twig = $this->loadTwig();
    }

    /**
     * Load Twig.
     */
    private function loadTwig()
    {
        // Check if Twig_Autoloader is loaded
        if (!class_exists('Twig_Autoloader')) {
            require_once plugin_dir_path(__FILE__) . '/lib/Twig/Autoloader.php';
        }

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
        $tmplData = apply_filters('your_widget_tmpl_data', $tmplData, $this->id_base);

        $tmplFile = $this->tmplDir . 'widget.html';
        if (isset($instance['template_file']) && !empty($instance['template_file'])) {
            $customTmplFile = get_template_directory() . '/' . $instance['template_file'];

            // Add .html if missing
            if (substr($customTmplFile, -5) != '.html') {
                $customTmplFile .= '.html';
            }

            if (file_exists($customTmplFile)) {
                $tmplFile = str_replace(ABSPATH, '', $customTmplFile);
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
        $fields = [];
        foreach ($this->fields as $fieldName => $fieldData) {
            $fields[$fieldName] = $fieldData;

            if (in_array($fieldData['type'], array('checkbox', 'radio'))) {
                $fields[$fieldName]['selected'] = isset($instance[$fieldName]) ? $instance[$fieldName] : null;
            } else {
                $fields[$fieldName]['value'] = isset($instance[$fieldName]) ? $instance[$fieldName] : null;
            }

            $fields[$fieldName]['field_id'] = $this->get_field_id($fieldName);
            $fields[$fieldName]['field_name'] = $this->get_field_name($fieldName);
        }

        $tmplData = [
            'form_fields' => $fields,
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
    wp_enqueue_style('cinnaroll-widget-css', plugin_dir_url(__FILE__) . '/admin.css');
});
