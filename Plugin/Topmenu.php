<?php

namespace CommerceModules\HomeLink\Plugin;

use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Framework\View\LayoutInterface;

class Topmenu
{
    /**
     * @var NodeFactory
     */
    protected $nodeFactory;

    /**
     * @var LayoutInterface
     */
    protected $layout;

    /**
     * @param NodeFactory $nodeFactory
     * @param LayoutInterface $layout
     */
    public function __construct(NodeFactory $nodeFactory, LayoutInterface $layout)
    {
        $this->nodeFactory = $nodeFactory;
        $this->layout = $layout;
    }

    public function beforeGetHtml(
        \Magento\Theme\Block\Html\Topmenu $subject,
        $outermostClass = '',
        $childrenWrapClass = '',
        $limit = 0
    ) {
        $node = $this->nodeFactory->create(
            [
                'data' => $this->getNodeAsArray(),
                'idField' => 'id',
                'tree' => $subject->getMenu()->getTree()
            ]
        );

        $subject->getMenu()->addChild($node);
    }

    public function afterGetCacheKeyInfo(\Magento\Theme\Block\Html\Topmenu $subject, array $result)
    {
        if ($this->isHome()) {
            $result[] = 'home';
        }
        return $result;
    }

    protected function isHome()
    {
        return in_array('cms_index_index', $this->layout->getUpdate()->getHandles());
    }

    protected function getNodeAsArray()
    {
        return [
            'name' => __('Home'),
            'id' => 'home',
            'url' => '/',
            'has_active' => false,
            'is_active' => $this->isHome()
        ];
    }
}
