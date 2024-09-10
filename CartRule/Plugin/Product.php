<?php

namespace Kitchen\CartRule\Plugin;

use Magento\Config\Model\Config\Source\Yesno as SourceConfig;

/**
 * Additional attr for validator.
 */
class Product
{
    /**
     * Name For Condition Attribute
     */
    public const CONDITION_ATTRIBUTE_NAME = 'quote_item_is_custom_condition';

    /**
     * @var SourceConfig
     */
    private $sourceConfig;

    public function __construct(
        SourceConfig $sourceConfig
    ) {
        $this->sourceConfig = $sourceConfig;
    }

    /**
     * @param \Magento\Rule\Model\Condition\Product\AbstractProduct $subject
     * @param \Magento\SalesRule\Model\Rule\Condition\Product $result
     *
     * @return \Magento\SalesRule\Model\Rule\Condition\Product
     */
    public function afterLoadAttributeOptions(
        \Magento\Rule\Model\Condition\Product\AbstractProduct $subject,
        \Magento\SalesRule\Model\Rule\Condition\Product $result
    ) {
        $attributes = $subject->getAttributeOption();
        $attributes[self::CONDITION_ATTRIBUTE_NAME] = __('My custom condition');
        $subject->setAttributeOption($attributes);

        return $result;
    }

    /**
     * @param \Magento\Rule\Model\Condition\Product\AbstractProduct $subject
     * @param $result
     *
     * @return array
     */
    public function afterGetValueSelectOptions(\Magento\Rule\Model\Condition\Product\AbstractProduct $subject, $result)
    {
        if ($subject->getAttribute() === self::CONDITION_ATTRIBUTE_NAME) {

            return $this->sourceConfig->toOptionArray();
        }

        return $result;
    }

    /**
     * @param \Magento\Rule\Model\Condition\Product\AbstractProduct $subject
     * @param string $result
     *
     * @return string
     */
    public function afterGetInputType(\Magento\Rule\Model\Condition\Product\AbstractProduct $subject, $result)
    {
        if ($subject->getAttribute() === self::CONDITION_ATTRIBUTE_NAME) {
            return 'boolean';
        }

        return $result;
    }

    /**
     * @param \Magento\Rule\Model\Condition\Product\AbstractProduct $subject
     * @param string $result
     *
     * @return string
     */
    public function afterGetValueElementType(\Magento\Rule\Model\Condition\Product\AbstractProduct $subject, $result)
    {
        if ($subject->getAttribute() === self::CONDITION_ATTRIBUTE_NAME) {
            return 'select';
        }

        return $result;
    }

    /**
     * @param \Magento\Rule\Model\Condition\Product\AbstractProduct $subject
     * @param array $result
     *
     * @return array
     */
    public function afterGetOperatorSelectOptions(
        \Magento\Rule\Model\Condition\Product\AbstractProduct $subject,
        $result
    ) {
        if ($subject->getAttribute() === self::CONDITION_ATTRIBUTE_NAME) {
            foreach ($result as $key => $item) {
                if ($item['value'] === '<=>') {
                    unset($result[$key]);
                }
            }
        }

        return $result;
    }

    /**
     * @param \Magento\Rule\Model\Condition\Product\AbstractProduct $subject
     * @param \Magento\Framework\Model\AbstractModel $object
     */
    public function beforeValidate(
        \Magento\Rule\Model\Condition\Product\AbstractProduct $subject,
        \Magento\Framework\Model\AbstractModel $object
    ) {
        if ($object instanceof \Magento\Quote\Api\Data\CartItemInterface
            && $object->getQuote()
            && $object->getQuote()->getItems()
        ) {
            // your logic goes here
        }
    }
}