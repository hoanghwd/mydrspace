<?php

class Core_Page_Block_Html_Head extends Core_Block_Template
{
    /**
     * Initialize template
     *
     */
    protected function _construct()
    {
        $this->setTemplate('page/html/head.phtml');
    }

    /**
     * Add JavaScript file to HEAD entity
     *
     * @param string $name
     * @param string $params
     * @return Core_Page_Block_Html_Head
     */
    public function addJs($name, $params = "")
    {
        $this->addItem('js', $name, $params);

        return $this;
    }

    /**
     * Add CSS file to HEAD entity
     *
     * @param string $name
     * @param string $params
     * @return Core_Page_Block_Html_Head
     */
    public function addCss($name, $params = "")
    {
        $this->addItem('skin_css', $name, $params);

        return $this;
    }

    /**
     * Add HEAD Item
     *
     * Allowed types:
     *  - js
     *  - js_css
     *  - skin_js
     *  - skin_css
     *  - rss
     *
     * @param string $type
     * @param string $name
     * @param string $params
     * @param string $if
     * @param string $cond
     * @return Core_Page_Block_Html_Head
     */
    public function addItem($type, $name, $params=null, $if=null, $cond=null)
    {
        if ($type==='skin_css' && empty($params)) {
            $params = 'media="all"';
        }
        $this->_data['items'][$type.'/'.$name] = array(
            'type'   => $type,
            'name'   => $name,
            'params' => $params,
            'if'     => $if,
            'cond'   => $cond,
        );

        return $this;
    }

    /**
     *  Retrieve title element text (encoded)
     * @return string
     * @throws Zend_Controller_Request_Exception
     */
    public function getTitle()
    {
        if (empty($this->_data['title'])) {
            $this->_data['title'] = $this->getDefaultTitle();
        }

        return htmlspecialchars(html_entity_decode(trim($this->_data['title']), ENT_QUOTES, 'UTF-8'));
    }

    /**
     * Retrieve default title text
     * @return string|null
     * @throws Zend_Controller_Request_Exception
     */
    public function getDefaultTitle()
    {
        return Virtual::getStoreConfig('design/head/default_title');
    }

    /**
     * Set title element text
     * @param $title
     * @return $this
     * @throws Zend_Controller_Request_Exception
     */
    public function setTitle($title)
    {
        $this->_data['title'] = Virtual::getStoreConfig('design/head/default_title') . ' ' . $title;

        return $this;
    }

    /**
     * Retrieve content for description tag
     * @return mixed
     * @throws Zend_Controller_Request_Exception
     */
    public function getDescription()
    {
        if (empty($this->_data['description'])) {
            $this->_data['description'] = Virtual::getStoreConfig('design/head/default_description');
        }

        return $this->_data['description'];
    }

    /**
     * Getter for path to Favicon
     * @return mixed
     * @throws Exception
     */
    public function getFaviconFile()
    {
        if (empty($this->_data['favicon_file'])) {
            $this->_data['favicon_file'] = $this->_getFaviconFile();
        }

        return $this->_data['favicon_file'];
    }

    /**
     * Retrieve path to Favicon
     * @return string
     * @throws Exception
     */
    protected function _getFaviconFile()
    {
        return $this->getSkinUrl('virtualbox_win.ico');
    }

    /**
     * Get HEAD HTML with CSS/JS/RSS definitions
     * (actually it also renders other elements, TODO: fix it up or rename this method)
     * @return string
     * @throws Exception
     */
    public function getCssJsHtml()
    {
        // separate items by types
        $lines  = array();
        foreach ($this->_data['items'] as $item) {
            if (!is_null($item['cond']) && !$this->getData($item['cond']) || !isset($item['name'])) {
                continue;
            }
            $if     = !empty($item['if']) ? $item['if'] : '';
            $params = !empty($item['params']) ? $item['params'] : '';
            switch ($item['type']) {
                case 'js':        // js/*.js
                case 'skin_js':   // skin/*/*.js
                case 'js_css':    // js/*.css
                case 'skin_css':  // skin/*/*.css
                    $lines[$if][$item['type']][$params][$item['name']] = $item['name'];
                    break;
                default:
                    $this->_separateOtherHtmlHeadElements($lines, $if, $item['type'], $params, $item['name'], $item);
                    break;
            }
        }

        // prepare HTML
        $html   = '';
        foreach ($lines as $if => $items) {
            if (empty($items)) {
                continue;
            }
            if (!empty($if)) {
                // open !IE conditional using raw value
                if (strpos($if, "><!-->") !== false) {
                    $html .= $if . "\n";
                } else {
                    $html .= '<!--[if '.$if.']>' . "\n";
                }
            }

            //Virtual::dump($items);

            // static and skin css
            $html .= $this->_prepareStaticAndSkinElements('<link rel="stylesheet" type="text/css" href="%s"%s />'."\n",
                empty($items['js_css']) ? array() : $items['js_css'],
                empty($items['skin_css']) ? array() : $items['skin_css']
            );

            // static and skin javascripts
            $html .= $this->_prepareStaticAndSkinElements('<script type="text/javascript" src="%s"%s></script>' . "\n",
                empty($items['js']) ? array() : $items['js'],
                empty($items['skin_js']) ? array() : $items['skin_js']
            );

            // other stuff
            if (!empty($items['other'])) {
                $html .= $this->_prepareOtherHtmlHeadElements($items['other']) . "\n";
            }

            if (!empty($if)) {
                // close !IE conditional comments correctly
                if (strpos($if, "><!-->") !== false) {
                    $html .= '<!--<![endif]-->' . "\n";
                } else {
                    $html .= '<![endif]-->' . "\n";
                }
            }
        }

        return $html;
    }

    /**
     * Merge static and skin files of the same format into 1 set of HEAD directives or even into 1 directive
     *
     * Will attempt to merge into 1 directive, if merging callback is provided. In this case it will generate
     * filenames, rather than render urls.
     * The merger callback is responsible for checking whether files exist, merging them and giving result URL
     * @param $format
     * @param array $staticItems
     * @param array $skinItems
     * @return string
     * @throws Exception
     */
    protected function &_prepareStaticAndSkinElements($format, array $staticItems, array $skinItems)
    {
        $designPackage = Virtual::getDesign();
        $baseJsUrl = Virtual::getBaseUrl('js');
        $items = array();

        // get static files from the js folder, no need in lookups
        //Js files
        foreach ($staticItems as $params => $rows) {
            foreach ($rows as $name) {
                $items[$params][] = $baseJsUrl .DS. $name;
            }
        }

        // lookup each file basing on current theme configuration
        //Css files
        foreach ($skinItems as $params => $rows) {
            foreach ($rows as $name) {
                $items[$params][] = $designPackage->getSkinUrl('css'.DS.$name, array());
            }
        }

        $html = '';
        foreach ($items as $params => $rows) {
            $params = trim($params);
            $params = $params ? ' ' . $params : '';
            foreach ($rows as $src) {
                $html .= sprintf($format, $src, $params);
            }
        }

        //Virtual::dump($items);

        return $html;
    }

    /**
     * Render arbitrary HTML head items
     *
     * @see self::getCssJsHtml()
     * @param array $items
     * @return string
     */
    protected function _prepareOtherHtmlHeadElements($items)
    {
        return implode("\n", $items);
    }

    /**
     * Classify HTML head item and queue it into "lines" array
     *
     * @see self::getCssJsHtml()
     * @param array &$lines
     * @param string $itemIf
     * @param string $itemType
     * @param string $itemParams
     * @param string $itemName
     * @param array $itemThe
     */
    protected function _separateOtherHtmlHeadElements(&$lines, $itemIf, $itemType, $itemParams, $itemName, $itemThe)
    {
        $params = $itemParams ? ' ' . $itemParams : '';
        $href   = $itemName;
        switch ($itemType) {
            case 'rss':
                $lines[$itemIf]['other'][] = sprintf('<link href="%s"%s rel="alternate" type="application/rss+xml" />',
                    $href, $params
                );
                break;
            case 'link_rel':
                $lines[$itemIf]['other'][] = sprintf('<link%s href="%s" />', $params, $href);
                break;
        }
    }

}//End of class