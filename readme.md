Your Widget - A Wordpress Framework
=========

Your Widget is a code framework for fast and easy development of new wordpress widgets. It has all the necessary methods implemented, and Twig is loaded and setup and ready for templating.

The framework is built on a three step process.

## 1. Define your data.
You defined the type of data you want your widget to contain. The method for this is somewhat inspired by the drupal form API where you define your fields in an array. The structure is as follows.

```
$fields => [
    // Machine name is just an unique array key
    'field_machine_name' => [
        'label' => 'The label visible to users',
        'type' => 'input type',
        // Options are only used for checkboxes and radio buttons
        'options' => [
            key => [
                'label' => 'The label visible to the user.',
                'value' => 'Unique value to identify selected option.',
            ]
        ]
    ]
]

```
The title and template_file are added by the plugin. Real world example below.

```
function modify_widget_fields($fields, $widget_id) {
    $fields = [
        'number_of_posts' => [
            'label' => 'Number of posts',
            'type' => 'number',
        ],
        'category' => [
            'label' => 'Category',
            'type' => 'radio',
            'options' => [
                'popular' => [
                    'label' => 'Popular posts',
                    'value' => 'popular',
                ],
                'recent' => [
                    'label' => 'Recent posts',
                    'value' => 'recent',
                ],
                'similar' => [
                    'label' => 'Similar posts',
                    'value' => 'similar',
                ]
            ]
        ]
    ];
}
add_filter('your_widget_fields', 'modify_widget_fields', 10, 2);
```

## 2. Setup your template data.
There's a filter which allows you to add your own template data. Let's say you have saved an post id in one of your widget fields. You can now use the filter to load the post and add its data to the template.

```
add_filter('your_widget_tmpl_data', function ($tmplData$, $widget_id) {
    $tmplData['post'] = (array) get_post($tmplData['id']);

    return $tmplData;
}, 10, 2);
```

## 3. Design output
Design the output by adding a template file in the theme root directory. Your widget automatically searches for `[template].html`. We're using .html because Twig is your friend and your template engine. You find the Twig documentation [here](http://twig.sensiolabs.org/documentation). Simple example below where I've added post data to the template data array.

```
{{before_widget}}
    {{before_title}}
        <h2>{{post.post_title}}</h2>
        <div class="content">
            {{post.post_content}}
        </div>
        <a href="{{post.permalink}}">Read more</a>
    {{after_title}}
{{after_widget}}
```

## Help and more info
That's about it. Good luck and let me know if I can help you with anything <3