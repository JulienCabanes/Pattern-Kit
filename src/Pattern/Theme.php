<?php
namespace Pattern;

/**
 * Pattern Kit Theme
 *
 * @author Julien Cabanès <julien.c@icloud.com>
 */
class Theme
{
    protected $name = false;
    protected $dir = false;
    protected $themes_dir = false;
    protected $config = array();
    protected $config_filename = 'package.json';
    protected $paths = array();
    protected $data = array();
    protected $data_filename = 'data/data.json';

    public function __construct($dir) {
        $this->dir = $dir;
    }

    public function getDir() {
        return $this->dir;
    }

    public function getThemesDir() {
        if(!$this->themes_dir) {
            $this->themes_dir = dirname($this->getDir()).DIRECTORY_SEPARATOR;
        }
        return $this->themes_dir;
    }

    public function getName() {
        if(!$this->name) {
            $this->name = basename($this->getDir());
        }
        return $this->name;
    }

    public function getConfig() {
        if(count($this->config) > 0) {
            return $this->config;
        }

        $config = array('theme_paths' => array());
        $config_path = realpath($this->getDir().'/'.$this->config_filename);

        if(file_exists($config_path) && is_readable($config_path)) {
            $config_content = file_get_contents($config_path);
            $config = json_decode($config_content, true);

            // Theme inheritance
            $config_base = (array) $this->getName();
            if(isset($config['base_theme'])) {
                $baseTheme = new Theme(realpath($this->getDir().'/../'.$config['base_theme']));
                $baseThemeConf = $baseTheme->getConfig();
                $config = array_merge($baseThemeConf, $config);
                $config_base = array_merge($config_base, (array) $baseThemeConf['base_theme']);
            }
            $config['base_theme'] = $config_base;
        }

        $this->config = $config;
        return $this->config;
    }

    function getPaths() {
        if(count($this->paths) > 0) {
            return $this->paths;
        }

        $config = $this->getConfig();
        $themes_dir = $this->getThemesDir();

        if(is_array($config['base_theme'])) {
            $paths = array_map(function($path) use ($themes_dir) {
                return realpath($themes_dir.$path.'/views');
            }, $config['base_theme']);
        }
        
        $this->paths = $paths;
        return $this->paths;
    }

    function getData() {
        if(count($this->data) > 0) {
            return $this->data;
        }

        $config = $this->getConfig();
        $themes_dir = $this->getThemesDir();

        $datas = array();
        if(is_array($config['base_theme'])) {
            foreach($config['base_theme'] as $base) {
                $data_path = $themes_dir.$base.'/'.$this->data_filename;
                if(file_exists($data_path)) {
                    $datum = json_decode(file_get_contents($data_path), true);
                    $datas = array_replace_recursive($datum, $datas);
                }
            }
        }
        // TEMP add-on 
        $pages_path = $this->getDir().'/data/pages.json';
        if(is_file($pages_path)) {
            $pages_datas = json_decode(file_get_contents($pages_path), true);
            if(is_array($pages_datas)) {
                $datas['pages'] = $pages_datas;
            }
        }

        $this->datas = $datas;
        return $this->datas;
    }
}
