<?php 
/**
 * 
 */

namespace Libby\Html;

class Parser {
    
    protected $cachePattern = null;
    protected $parser = [];
    protected $scripts = []; 
    protected $html;
    protected $pathConverterToLocal = null;
    protected $pathConverterToPublic = null;
    
    
    /**
     * 
     */
    public function getHtml ( ) {
        
        return $this->html;        
    }
    
    
    /**
     * 
     */
    public function getScriptsHash ( ) {
        
        return md5(serialize($this->scripts));
    }
    
    
    /**
     * 
     */
    public function setCachePattern ( $pattern ) {
        
        $this->cachePattern = $pattern;
    }
    
    
    /**
     * Set html context
     */    
    public function setHtml ( $html ) {
        
        $this->html = $html;
    }
    
    
    /**
     * 
     */
    public function setPathConverterToLocal ( $callback ) {
        
        $this->pathConverterToLocal = $callback;
        
        return $this;
    }
    
    
    /**
     * 
     */
    public function setPathConverterToPublic ( $callback ) {
        
        $this->pathConverterToPublic = $callback;
        
        return $this;
    }
    
    
    /**
     * 
     */
    public function setParser ( $ext, $callback ) {
        
        $this->parser[$ext] = $callback;
    }
    
    
    /**
     * 
     */
    public function scriptsClear ( ) {
        
        $this->scripts = [];
        
        return $this;
    }
    
    
    /**
     * 
     */
    public function scriptsCombine ( ) {
        
        $this->scriptsExtract();
                
        $overallHash = $this->getScriptsHash();
        
        $cacheFileCss = $this->cachePattern;
        $cacheFileCss = str_replace('{type}', 'stylesheet', $cacheFileCss);
        $cacheFileCss = str_replace('{hash}', $overallHash, $cacheFileCss);
        $cacheFileCss = str_replace('{ext}', 'css', $cacheFileCss);
        
        $cacheFileJs = $this->cachePattern;
        $cacheFileJs = str_replace('{type}', 'javascript', $cacheFileJs);
        $cacheFileJs = str_replace('{hash}', $overallHash, $cacheFileJs);
        $cacheFileJs = str_replace('{ext}', 'js', $cacheFileJs);
                
        if (file_exists($cacheFileCss)) {
            
            $this->scriptsClear();
            
            $this->scripts[] = [
                'ext' => 'css',
                'type' => 'stylesheet',
                'path' => $cacheFileCss
            ];
            
            if (file_exists($cacheFileJs)) {
                $this->scripts[] = [
                    'ext' => 'js',
                    'type' => 'javascript',
                    'path' => $cacheFileJs
                ];
            }
            
            $this->scriptsInject();
            
            return $this;
        }
        
        $sources = [];
        
        $pathConverterToLocal = $this->pathConverterToLocal;
                
        
        
        foreach ($this->scripts as $script) {
            
            if (is_callable($pathConverterToLocal)) {
                $script['path'] = $pathConverterToLocal($script['file']);
            }
            
            // Get source from filesystem
            if (isset($script['path']) AND file_exists($script['path'])) {
                
                $source = file_get_contents($script['path']);
            }
            // Get source from http
            else {
                
                $source = file_get_contents($script['file']);
            }            
            
            if (!isset($sources[$script['ext']])) {
                $sources[$script['ext']] = [
                    'ext' => $script['ext'],
                    'type' => $script['type'],
                    'source' => (string) null
                ];
            }
            
            $sources[$script['ext']]['source'] .= $source . PHP_EOL;
            
        }
        
        $hash = 'pre-' . $this->getScriptsHash();
        
        $this->scriptsClear();
                        
        foreach ($sources as $extension => $script) {
            
            $file = $this->cachePattern;
            $file = str_replace('{type}', $script['type'], $file);
            $file = str_replace('{hash}', $hash, $file);
            $file = str_replace('{ext}', $script['ext'], $file);
            
            file_put_contents($file, $script['source']);
            
            unset($script['source']);
            $script['path'] = $file;
            $this->scripts[] = $script;
        }
        
        $this->scriptsInject();
        
        $this->scriptsExtract();
        
        $js = (string) null;
        $css = (string) null;
        
        foreach ($this->scripts as $index => $script) {
            
            if (!empty($this->parser[$script['ext']])) {
                $parser = $this->parser[$script['ext']];                
                $script = $parser($script);
            }
            
            if (empty($script['source'])) {
                $script['source'] = file_get_contents($script['file']);
            }
            
            ${$script['ext']} .= $script['source'];
        }
        
        $this->scriptsClear();
        
        
        
        if (!empty($js)) {                
                        
            $this->scripts[] = [
                'ext' => 'js',
                'type' => 'javascript',
                'path' => $cacheFileJs
            ];
            
            file_put_contents($cacheFileJs, $js);
        }
        
        if (!empty($css)) {
        
            $this->scripts[] = [
                'ext' => 'css',
                'type' => 'stylesheet',
                'path' => $cacheFileCss
            ];
            
            file_put_contents($cacheFileCss, $css);
        }
        
        
        $this->scriptsInject();
        
        return $this;
    }
    
    
    /**
     * 
     */
    public function scriptsConvertPath ( $callback ) {
        
        foreach ($this->scripts as $index => $script) {
            
            $this->scripts[$index] = $callback($script);
        }
    }
    
    
    /**
     * 
     */
    public function scriptsExtract ( array $params = null ) {
                               
        $document = new \DOMDocument;
        
        libxml_use_internal_errors(true);
        
        $html = (substr($this->html, 0, 5) != '<!DOC') ? '<div>' . $this->html . '</div>' : $this->html; 
           
        
        $document->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();  
        
        $xpath = new \DOMXPath($document);
        
        $elements = $xpath->query('//script');
        $removeNodes = [];
        
        foreach ($elements as $element) {
                        
            if (empty($element->getAttribute('src')) or $element->hasAttribute('data-nocache')) {                
                continue;    
            }
            
            if (!empty($params['localPath']) AND strpos($element->getAttribute('src'), $params['localPath']) === false) {
                continue;
            }
            
            $removeNodes[] = $element;
        }
        
        
        foreach ($removeNodes as $element) {
            
            $element->parentNode->removeChild($element);
            $this->scripts[] = [ 'type' => 'javascript', 'file' => $element->getAttribute('src'), 'ext' => 'js' ];            
        }        
        
        
        $elements = $xpath->query('//link');
        
        foreach ($elements as $element) {
            
            $href = $element->getAttribute('href');
            
            if (!empty($params['localPath']) AND strpos($href, $params['localPath']) === false) {
                continue;
            }
            
            if (!empty($element->getAttribute('rel') AND substr($element->getAttribute('rel'), 0, 10) != 'stylesheet')) {
                continue;    
            }            
            
            $da = explode('.', $href);            
            $ext = end($da);
            
            $element->parentNode->removeChild($element);
            $this->scripts[] = [ 'type' => 'stylesheet', 'file' => $href, 'ext' => $ext ];
        }        
       
        $this->html = $document->saveHTML();
        
        
        return $this;        
    }
    
    
    /**
     * 
     */
    public function scriptsInject ( array $params = null ) {
        
        foreach ($this->scripts as $script) {
            
            
            if (!isset($script['file'])) {
                
                if (!is_callable($converter = $this->pathConverterToPublic)) {
                    throw new \Exception('ExceptionNeedPublicPathConverter');
                }
                
                $script['file'] = $converter($script['path']);
            }
                                    
            switch ( $script['type'] ) {
                
                case 'stylesheet':                   
                    $tag = '<link rel="stylesheet" type="text/css" href="' . $script['file'] . '" />';                    
                break;
                
                case 'javascript':                    
                    $tag = '<script src="' . $script['file'] . '"></script>';
                break;
                
                default:
                    $tag = '<!-- unknown script type: ' . $script['type'] . ' -->';
                break;
            }
            
            if (strpos($this->html, '</head>') !== false) {
                $this->html = str_replace('</head>', PHP_EOL . $tag . PHP_EOL . '</head>', $this->html);
            }
            else {
                $this->html .= PHP_EOL . $tag;
            }
        }
        
        $this->scriptsClear();
        
        return $this;
    }
    
    
    /**
     * 
     */
    public function writeCache ( ) {
        
        $pattern = $this->cachePattern;
        $pattern = str_replace('{hash}', $this->getScriptsHash(), $pattern);
        
        $pathConverterToPublic = $this->pathConverterToPublic;
        
        foreach ($this->scripts as $index => $script) {
            
            if (empty($script['source'])) {
                continue;    
            }
            
            $npattern = str_replace('{type}', $script['type'], $pattern);
            $npattern = str_replace('{ext}', $script['ext'], $npattern);
            
            if (!file_exists($path = dirname($npattern))) {                
                \Libby\Dir::create($path);
            }
                            
            file_put_contents($npattern, $script['source']);
            
            $script['path'] = $npattern;
            $script['file'] = $pathConverterToPublic($script['path']);
            $this->scripts[$index] = $script;
        }
                
        return $this;
    }
}