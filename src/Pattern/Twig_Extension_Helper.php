<?php
namespace Pattern;

use Twig_Extension;
use Twig_SimpleFunction;
use Twig_Filter_Function;

/**
 * Pattern Kit Twig Extension
 * Adds some missing twig functions & filters
 *
 * @author Julien Cabanès <julien.c@icloud.com>
 */
class Twig_Extension_Helper extends Twig_Extension
{
    public function getName()
    {
        return 'pattern-kit-helper';
    }

    public function getFunctions()
    {
    	return array(
    		new Twig_SimpleFunction('file_get_contents', 'file_get_contents', array('is_safe' => array('html'))),
		    new Twig_SimpleFunction('uniqid', 'uniqid'),
		    new Twig_SimpleFunction('print_r', 'print_r'),
		    new Twig_SimpleFunction('BEM', 'Pattern\\BEM'),
		    new Twig_SimpleFunction('tidyHTML', 'Pattern\\tidyHTML')
		);
    }

    public function getFilters()
    {
    	return array(
    		'htmlentities' => new Twig_Filter_Function('htmlentities', array('is_safe' => array('html'))),
	    	'floor' => new Twig_Filter_Function('floor'),
	    	'ceil' => new Twig_Filter_Function('ceil')
	    );
    }
}

/**
 * BEM
 * experimental function to output BEM style class names from strings and/or arrays
 * @param string|array $b block
 * @param string|array $e element
 * @param string|array $m modifier
 * @return string class names
 *
 * eg:
 * BEM('page', 'title')                                             > page_title
 * BEM('btn', 0, 'primary')                                     > btn btn--primary
 * BEM(array('node', 'page-node'), 'title')     > node_title page-node_title
 * BEM(array('node', 'page-node'), 0, array('teaser', 'nested')) > node page-node node--teaser page-node--teaser node--nested page-node--nested ... fiou
 */
function BEM($b = array(), $e = array(), $m = array()) {
    $element_separator = '_';
    $modifier_separator = '--';

    $classes = array();
    $modifiers = array();

    // Every arguments could be a string or an array
    if(!is_array($b)) $b = array($b);
    if(!is_array($e)) $e = array($e);
    if(!is_array($m)) $m = array($m);

    foreach($b as $block) {
        // Block only
        if(empty($e)) {
            $classes[] = $block;

        // Element only
        } else {
            foreach($e as $element) {
                if(!empty($element)) {
                        $classes[] = $block.$element_separator.$element;
                }
            }
        }
    }

    // append modifiers after classes
    foreach($m as $modifier) {
        if(!empty($modifier)) {
            foreach($classes as $classname) {
                if(!empty($classname)) {
                    $modifiers[] = $classname.$modifier_separator.$modifier;
                }
            }
        }
    }

    return implode(' ', array_merge($classes, $modifiers));
}

/**
 * Wrapper for tidy command...
 * WARNING : it is a "wrapper", you should have tidy CLI installed on your machine
 * @param    string $code       HTML code to beautify
 * @return   string             beautified code
 */
function tidyHTML($code = '', $fullDocument = false) {
    $cmd = 'tidy -q --show-body-only 1 --indent 1 --indent-attributes 0 -w 0 --char-encoding raw';
    $descriptorspec = array(
         0 => array("pipe", "r"),    // // stdin est un pipe où le processus va lire
         1 => array("pipe", "w"),    // stdout est un pipe où le processus va écrire
         2 => array("file", "/tmp/error-output.txt", "a") // stderr est un fichier
    );

    $cwd = '/tmp';
    $env = array();

    $process = proc_open($cmd, $descriptorspec, $pipes, $cwd, $env);

    if (is_resource($process)) {
            // $pipes ressemble à :
            // 0 => fichier accessible en écriture, connecté à l'entrée standard du processus fils
            // 1 => fichier accessible en lecture, connecté à la sortie standard du processus fils
            // Toute erreur sera ajoutée au fichier /tmp/error-output.txt

            // Tidy a besoin de son petit doctype quand même...
            if($fullDocument) {
                fwrite($pipes[0], $code);
            } else {
                fwrite($pipes[0], '<!doctype html><title></title><body>'.$code.'</body>');
            }
            fclose($pipes[0]);

            $content = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            // Il est important que vous fermiez les pipes avant d'appeler
            // proc_close afin d'éviter un verrouillage.
            $return_value = proc_close($process);

            // return $content
            return str_replace('    ', "\t", $content);
    }
}
