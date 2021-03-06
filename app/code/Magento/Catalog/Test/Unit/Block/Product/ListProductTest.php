<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Catalog\Test\Unit\Block\Product;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ListProductTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Catalog\Block\Product\ListProduct
     */
    protected $block;

    /**
     * @var \Magento\Framework\Registry|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $registryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $layerMock;

    /**
     * @var \Magento\Framework\Data\Helper\PostHelper|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $postDataHelperMock;

    /**
     * @var \Magento\Catalog\Model\Product|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productMock;

    /**
     * @var \Magento\Checkout\Helper\Cart|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $cartHelperMock;

    /**
     * @var \Magento\Catalog\Model\Product\Type\Simple|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $typeInstanceMock;

    /**
     * @var \Magento\Framework\Url\Helper\Data | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $urlHelperMock;
    
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $catCollectionMock;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $prodCollectionMock;

    /**
     * @var \Magento\Framework\View\LayoutInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $layoutMock;

    /**
     * @var \Magento\Catalog\Block\Product\ProductList\Toolbar | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $toolbarMock;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->registryMock = $this->getMock(\Magento\Framework\Registry::class, [], [], '', false);
        $this->layerMock = $this->getMock(\Magento\Catalog\Model\Layer::class, [], [], '', false);
        /** @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Catalog\Model\Layer\Resolver $layerResolver */
        $layerResolver = $this->getMockBuilder(\Magento\Catalog\Model\Layer\Resolver::class)
            ->disableOriginalConstructor()
            ->setMethods(['get', 'create'])
            ->getMock();
        $layerResolver->expects($this->any())
            ->method($this->anything())
            ->will($this->returnValue($this->layerMock));
        $this->postDataHelperMock = $this->getMock(
            \Magento\Framework\Data\Helper\PostHelper::class,
            [],
            [],
            '',
            false
        );
        $this->typeInstanceMock = $this->getMock(
            \Magento\Catalog\Model\Product\Type\Simple::class,
            [],
            [],
            '',
            false,
            false
        );
        $this->productMock = $this->getMock(
            \Magento\Catalog\Model\Product::class,
            [],
            [],
            '',
            false
        );
        $this->cartHelperMock = $this->getMock(
            \Magento\Checkout\Helper\Cart::class,
            [],
            [],
            '',
            false
        );
        $this->catCollectionMock = $this->getMock(
            \Magento\Catalog\Model\ResourceModel\Category\Collection::class,
            [],
            [],
            '',
            false
        );
        $this->prodCollectionMock = $this->getMock(
            \Magento\Catalog\Model\ResourceModel\Product\Collection::class,
            [],
            [],
            '',
            false
        );
        $this->layoutMock = $this->getMock(
            \Magento\Framework\View\LayoutInterface::class,
            [],
            [],
            '',
            false
        );
        $this->toolbarMock = $this->getMock(
            \Magento\Catalog\Block\Product\ProductList\Toolbar::class,
            [],
            [],
            '',
            false
        );

        $this->urlHelperMock = $this->getMockBuilder(\Magento\Framework\Url\Helper\Data::class)
            ->disableOriginalConstructor()->getMock();
        $this->block = $objectManager->getObject(
            \Magento\Catalog\Block\Product\ListProduct::class,
            [
                'registry' => $this->registryMock,
                'layerResolver' => $layerResolver,
                'cartHelper' => $this->cartHelperMock,
                'postDataHelper' => $this->postDataHelperMock,
                'urlHelper' => $this->urlHelperMock,
            ]
        );
        $this->block->setToolbarBlockName('mock');
        $this->block->setLayout($this->layoutMock);
    }

    protected function tearDown()
    {
        $this->block = null;
    }

    public function testGetIdentities()
    {
        $productTag = 'cat_p_1';
        $categoryTag = 'cat_c_p_1';

        $this->productMock->expects($this->once())
            ->method('getIdentities')
            ->will($this->returnValue([$productTag]));

        $this->productMock->expects($this->once())
            ->method('getCategoryCollection')
            ->will($this->returnValue($this->catCollectionMock));

        $this->catCollectionMock->expects($this->once())
            ->method('load')
            ->will($this->returnValue($this->catCollectionMock));

        $this->catCollectionMock->expects($this->once())
            ->method('setPage')
            ->will($this->returnValue($this->catCollectionMock));

        $this->catCollectionMock->expects($this->once())
            ->method('count')
            ->will($this->returnValue(1));

        $this->registryMock->expects($this->any())
            ->method('registry')
            ->will($this->returnValue($this->productMock));

        $currentCategory = $this->getMock(\Magento\Catalog\Model\Category::class, [], [], '', false);
        $currentCategory->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('1'));

        $this->catCollectionMock->expects($this->once())
            ->method('getIterator')
            ->will($this->returnValue([$currentCategory]));

        $this->prodCollectionMock->expects($this->any())
            ->method('getIterator')
            ->will($this->returnValue(new \ArrayIterator([$this->productMock])));
 
        $this->layerMock->expects($this->any())
            ->method('getCurrentCategory')
            ->will($this->returnValue($currentCategory));

        $this->layerMock->expects($this->once())
            ->method('getProductCollection')
            ->will($this->returnValue($this->prodCollectionMock));

        $this->layoutMock->expects($this->once())
            ->method('getBlock')
            ->will($this->returnValue($this->toolbarMock));

        $this->assertEquals(
            [$productTag, $categoryTag],
            $this->block->getIdentities()
        );
        $this->assertEquals(
            '1',
            $this->block->getCategoryId()
        );
    }

    public function testGetAddToCartPostParams()
    {
        $url = 'http://localhost.com/dev/';
        $id = 1;
        $uenc = strtr(base64_encode($url), '+/=', '-_,');
        $expectedPostData = [
            'action' => $url,
            'data' => ['product' => $id, 'uenc' => $uenc],
        ];

        $this->typeInstanceMock->expects($this->once())
            ->method('isPossibleBuyFromList')
            ->with($this->equalTo($this->productMock))
            ->will($this->returnValue(true));
        $this->cartHelperMock->expects($this->any())
            ->method('getAddUrl')
            ->with($this->equalTo($this->productMock), $this->equalTo([]))
            ->will($this->returnValue($url));
        $this->productMock->expects($this->once())
            ->method('getEntityId')
            ->will($this->returnValue($id));
        $this->productMock->expects($this->once())
            ->method('getTypeInstance')
            ->will($this->returnValue($this->typeInstanceMock));
        $this->urlHelperMock->expects($this->once())
            ->method('getEncodedUrl')
            ->with($this->equalTo($url))
            ->will($this->returnValue($uenc));
        $result = $this->block->getAddToCartPostParams($this->productMock);
        $this->assertEquals($expectedPostData, $result);
    }
}
