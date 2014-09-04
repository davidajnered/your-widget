Your Widget - A WP Framework
=========

Your Widget is a code framework for fast and easy development of new wordpress widgets. It has all the necessary methods implemented, and Twig is loaded and setup and ready for templating.

The idea is built on a three step process.


## Define your data.
Your have to defined the type of data you want your widget to contain. The method for this is somewhat inspired by the drupal form API where you define your fields in an array. The structure is as follows.

```
$fields => [
    // Machine name is just an unique array key
    'field_machine_name' => [
        'label' => 'The label visible to users',
        'type' => 'input type',
        // Options are only used for checkboxes and radio buttons
        'options' => [
            'label' => 'The label visible to users. Overrides previous label',
            'value' => 'Unique value to identify selected option',
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

## Setup your template data.
There's a filter which allows you to add your own template data. Say you've saved an post id in one of your widget fields. You can now use your filter to load tthe post and add its data to the template.

```
add_filter('your_widget_tmpl_data', function ($tmplData$, $widget_id) {
    $tmplData['post'] = (array) get_post($tmplData['id']);

    return $tmplData;
}, 10, 2);
```

## Design output
Finally design the output by adding a template file in the theme root directory. Your Widget uses Twig as template engine. You find the documentation [here](http://twig.sensiolabs.org/documentation). Simple example below where I've added post data to the template data array.

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