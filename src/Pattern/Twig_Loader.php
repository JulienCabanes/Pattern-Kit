<?php
namespace Pattern;

use Twig_Loader_Filesystem;
use Twig_LoaderInterface;
use Twig_ExistsLoaderInterface;
use Twig_Error_Loader;

/**
 * Pattern Kit Twig Loader
 * Loads template from the pattern system.
 *
 * @author Julien Cabanès <julien.c@icloud.com>
 * @see    Twig_Loader_Filesystem
 */
class Twig_Loader extends Twig_Loader_Filesystem implements Twig_LoaderInterface, Twig_ExistsLoaderInterface
{

    /**
     * {@inheritdoc}
     */
    public function getSource($name)
    {
        $template_path = $this->findTemplate($name);
        $template_content = file_get_contents($template_path);
        $template_dir = dirname($template_path);
        $template_filename = basename($template_path);
        $template_name = str_replace('.twig', '', $template_filename);

        // Data Pattern Specific
        // http://pattern-lab.info/docs/data-pattern-specific.html
        $data_content = $this->getTemplateDataContent($template_dir.'/'.$template_name.'.json');
        // Default data for partials
        $data_content .= $this->getTemplateDataContent($template_dir.'/'.$template_name.'_.json', true);

        if(strlen($data_content) > 0) {
            $data_content = "{# Data Pattern Specific #}\n".$data_content;
        }

        // echo '<pre>'.$data_content.$template_content.'</pre>';
        return $data_content.$template_content;
    }

    protected function getTemplateDataContent($data_path, $default = false) {
        $data_content = '';
        if(file_exists($data_path)) {
            $data = json_decode(file_get_contents(realpath($data_path)), true);

            if(is_array($data)) {
                foreach($data as $name => $value) {
                    $encoded_value = self::unicodeDecode(json_encode($value));

                    if($default) {
                        $encoded_value = $name.'|default('.$encoded_value.')';
                    } elseif(!is_string($value) && substr($encoded_value, 0, 1) !== '[') {
                        $encoded_value = $name.'|merge('.$encoded_value.')';
                    }
                    $data_content .= '{% set '.$name.' = '.$encoded_value.' %}'."\n";
                }
            }
        }

        return $data_content;
    }

    static protected function unicodeDecode($str) {
        return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', function($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $str);
    }

    protected function findTemplate($name)
    {
        $name = (string) $name;

        // normalize name
        $name = preg_replace('#/{2,}#', '/', strtr($name, '\\', '/'));

        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        $this->validateName($name);

        $namespace = self::MAIN_NAMESPACE;
        $shortname = $name;
        if (isset($name[0]) && '@' == $name[0]) {
            if (false === $pos = strpos($name, '/')) {
                throw new Twig_Error_Loader(sprintf('Malformed namespaced template name "%s" (expecting "@namespace/template_name").', $name));
            }

            $namespace = substr($name, 1, $pos - 1);
            $shortname = substr($name, $pos + 1);
        }

        if (!isset($this->paths[$namespace])) {
            throw new Twig_Error_Loader(sprintf('There are no registered paths for namespace "%s".', $namespace));
        }


        // Split pattern path : pattern_type/{4 subdirs max}/patter_name.twig
        $name_fragments = explode('/', $name);
        $pattern_type = array_shift($name_fragments);
        $prefix_pattern = '*';
        $pattern_path = array();
        foreach($name_fragments as $fragment) {
            // $pattern_path .= '/{'.$fragment.',??-'.$fragment.'}';
            // $pattern_path[] = '{??-'.$fragment.',default}';
            $pattern_path[] = '*'.$fragment;
        }
        $pattern_path = implode('/', $pattern_path).'.twig';
        $matching_files = array();
        $matching_pattern = false;


        foreach ($this->paths[$namespace] as $path) {
            for($i = 0; $i < 4; $i++) {
                $pattern_pattern = $path.'/*'.$pattern_type.'/'.str_repeat('*/', $i).$pattern_path;
                // echo $pattern_pattern.'<br/>';

                $matching_files = glob($pattern_pattern, GLOB_BRACE);
                if(count($matching_files) > 0) {
                    break;
                }
            }
            if(count($matching_files) > 0) {
                foreach($matching_files as $file) {
                    if(is_file($file)) {
                        $matching_pattern = $file;
                        break;
                    }
                }
            }

            if($matching_pattern) {
                // print "<pre>(".$name.")\n".$matching_pattern."</pre>";
                return $this->cache[$name] = $matching_pattern;
            }
        }

        throw new Twig_Error_Loader(sprintf('Unable to find template "%s" (looked into: %s).', $name, implode(', ', $this->paths[$namespace])));
    }
}
