<?php
namespace AppBundle\Twig;

class FilterBlackListedExtension extends \Twig_Extension
{
    private $blacklistedTags = ['script', 'iframe', 'applet', 'h1', 'h2', 'embed', 'html', 'body', 'head', 'style', 'meta', 'link', 'title', 'font'];

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('filter_black_listed', array($this, 'htmlFilter')),
        );
    }

    public function htmlFilter($html)
    {
        foreach ($this->blacklistedTags as $tag) {
            $html = preg_replace('/(<' . $tag . ')/', '&lt;'.$tag, $html);
            $html = preg_replace('/(<\/' . $tag . '>)/', '&lt;/'.$tag.'&gt;', $html);
        }
        $open = preg_match_all('/<[A-Za-z]+/', $html);
        $close = preg_match_all('/<\/[A-Za-z]+/', $html);
        $single = preg_match_all('/<[A-Za-z]+[A-Za-z0-9 =".:\';-]+[ \/]/', $html);
        if( ($open - $single) !== $close) { 
            $html = preg_replace('/</', '&lt;', $html);
            $html = preg_replace('/>/', '&gt;', $html);
        }
        $html = preg_replace( "/<!--/", "//", $html);
    
        return $html;
    }

    public function getName()
    {
        return 'filter_black_listed_extension';
    }
} ?>