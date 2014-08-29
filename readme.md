Your Widget
=========

Your Widget is a code framework for fast and easy development of new wordpress widgets. It has all the necessary methods implemented, and Twig is loaded and setup and ready for templating.

The idea here is that you only have to modify the $formFields array to add your custom form fields for the widget. Then you add a [your-template].html file (specified in the widget) in your theme root directory and change the widget output as you like.

You can hook into the template data function and load custom data, for example data from a post (maybe you get the ID from the widget).
```
add_filter('your_widget_tmpl_data', function ($tmplData) {
    // Modify the $tmplData array here
    return $tmplData;
});
```
That's about it. Good luck and let me know if I can help you with anything <3