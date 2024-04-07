<?php
declare(strict_types=1);

namespace Alex\CatalogSliderModule\Widget;

use Magento\Catalog\Api\CategoryListInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Store\Model\StoreManagerInterface;

class CategoryList extends Template implements BlockInterface
{
    private $categoryListApi;
    private $searchCriteriaBuilder;
    private $storeManager;

    public function __construct(
        Context $context,
        CategoryListInterface $categoryListApi,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->categoryListApi = $categoryListApi;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->storeManager = $storeManager;
    }

    public function getCategories(): array
    {
        $categoryIds = $this->getData('category_ids');
        if (!$categoryIds) {
            return [];
        }

        $categoryIdsArray = explode(',', $categoryIds);
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('entity_id', $categoryIdsArray, 'in')
            ->create();

        return $this->categoryListApi->getList($searchCriteria)->getItems();
    }

    public function getImageUrl(CategoryInterface $category): ?string
    {
        $imagePath = $category->getCustomAttribute('image') ? $category->getCustomAttribute('image')->getValue() : null;
        return $imagePath ?? null;
    }

    public function getIconUrl(CategoryInterface $category): ?string
    {
        $iconPath = $category->getCustomAttribute('custom_image') ? $category->getCustomAttribute('custom_image')->getValue() : null;
        return $iconPath ?? null;
    }


    public function getBackgroundColor(): string
    {
        return $this->getData('background_color') ?: '#ffffff';
    }

    public function getPlaceholderImageUrl(): string
    {
        return $this->getViewFileUrl('Alex_CatalogSliderModule::images/no-image-icon.png');
    }


    public function isAutoplayEnabled(): bool
    {
        return $this->getData('nav') === '1';
    }

}
