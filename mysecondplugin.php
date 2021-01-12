<?php
/**
 * Plugin Name: My Second Plugin
 * Plugin URI: http://www.mywebsite.com/my-first-plugin
 * Description: The very second plugin that I have ever created.
 * Version: 1.0
 * Author: Amirhossein
 * Author URI: http://www.mywebsite.com
 */
include('/home/ahadli/Documents/learnwordpress/wordpress/wp-load.php');
include ('/home/ahadli/Documents/learnwordpress/wordpress/wp-content/plugins/mysecondplugin/phpquery/phpQuery/phpQuery.php');
add_action( 'the_content', 'my_thank_you_text' );

function my_thank_you_text ( $content ) {
//    var_dump($content);
//    die('123');
//    include ('/home/ahadli/Documents/learnwordpress/wordpress/wp-content/plugins/mysecondplugin/phpquery/phpQuery/phpQuery.php');
    $file = fopen('file.html', "a+");
    fwrite($file,$content);
    phpQuery::newDocumentFileHTML('file.html');
    $i = 0;
    $arr = array();
    while (pq("body > .q:eq($i)")->html() !== '') {
        $arr[] = '{' . "\n" .
            '"@type": "Question",' . "\n" .
            '"name": ' . '"' . pq("body > .q:eq($i)")->html() . '",' . "\n" .
            '"acceptedAnswer": {' . "\n" .
            '"@type": "Answer",' . "\n" .
            '"text": ' . '"' . pq("body > .a:eq($i)")->html() . '"' . "\n" .
            '}' . "\n" .
            '}';
        $i++;
    }
    $arr = implode(", ", $arr);
    global $script;
    $script = '<script type="application/ld+json">
{
"@context": "https://schema.org",
            "@type": "FAQPage",
            "mainEntity": [' . $arr . ']
            }' . "\n" .
        "</script>";
    unlink('file.html');
    return $content .= $script;
}