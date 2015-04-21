<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Quote\Test\Unit\Model\GuestCart;

class GuestCartManagementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteManagementMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteIdMaskFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteIdMaskMock;

    /**
     * @var \Magento\Quote\Model\GuestCart\GuestCartManagement
     */
    protected $guestCartManagement;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->quoteManagementMock = $this->getMockForAbstractClass(
            'Magento\Quote\Api\CartManagementInterface',
            [],
            '',
            false,
            true,
            true,
            []
        );
        $this->quoteRepositoryMock = $this->getMock('Magento\Quote\Model\QuoteRepository', [], [], '', false);
        $this->quoteIdMaskFactoryMock = $this->getMock(
            'Magento\Quote\Model\QuoteIdMaskFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->quoteIdMaskMock = $this->getMock(
            'Magento\Quote\Model\QuoteIdMask',
            ['getId', 'getMaskedId', 'load', 'save', 'setId'],
            [],
            '',
            false
        );

        $this->guestCartManagement = $objectManager->getObject(
            'Magento\Quote\Model\GuestCart\GuestCartManagement',
            [
                'quoteManagement' => $this->quoteManagementMock,
                'quoteRepository' => $this->quoteRepositoryMock,
                'quoteIdMaskFactory' => $this->quoteIdMaskFactoryMock
            ]
        );
    }

    public function testCreateEmptyCart()
    {
        $maskedCartId = 'masked1cart2id3';
        $cartId = 1;

        $this->quoteIdMaskMock->expects($this->once())->method('setId')->with($cartId)->willReturnSelf();
        $this->quoteIdMaskMock->expects($this->once())->method('save')->willReturnSelf();
        $this->quoteIdMaskMock->expects($this->once())->method('getMaskedId')->willreturn($maskedCartId);
        $this->quoteIdMaskFactoryMock->expects($this->once())->method('create')->willReturn($this->quoteIdMaskMock);
        $this->quoteManagementMock->expects($this->once())->method('createEmptyCart')->willReturn($cartId);

        $this->assertEquals($maskedCartId, $this->guestCartManagement->createEmptyCart());
    }

    public function testAssignCustomer()
    {
        $maskedCartId = 'masked1cart2id3';
        $cartId = 1;
        $customerId = 1;
        $storeId = 1;

        $this->quoteIdMaskMock->expects($this->once())->method('load')->with($cartId, 'masked_id')->willReturnSelf();
        $this->quoteIdMaskMock->expects($this->once())->method('getId')->willReturn($maskedCartId);
        $this->quoteIdMaskFactoryMock->expects($this->once())->method('create')->willReturn($this->quoteIdMaskMock);
        $this->quoteManagementMock->expects($this->once())->method('assignCustomer')->willReturn(true);

        $this->assertEquals(true, $this->guestCartManagement->assignCustomer($cartId, $customerId, $storeId));
    }

    public function testPlaceOrder()
    {
        $maskedCartId = 'masked1cart2id3';
        $cartId = 1;
        $orderId = 1;

        $this->quoteIdMaskMock->expects($this->once())->method('load')->with($cartId, 'masked_id')->willReturnSelf();
        $this->quoteIdMaskMock->expects($this->once())->method('getId')->willReturn($maskedCartId);
        $this->quoteIdMaskFactoryMock->expects($this->once())->method('create')->willReturn($this->quoteIdMaskMock);
        $this->quoteManagementMock->expects($this->once())->method('placeOrder')->willReturn($orderId);

        $this->assertEquals($orderId, $this->guestCartManagement->placeOrder($cartId));
    }
}
