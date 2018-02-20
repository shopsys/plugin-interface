<?php

namespace Shopsys\ShopBundle\Model\Pricing\Group;

use Shopsys\ShopBundle\Component\Domain\AdminDomainTabsFacade;
use Shopsys\ShopBundle\Component\Domain\Domain;
use Shopsys\ShopBundle\Component\Setting\Setting;

class PricingGroupSettingFacade
{
    /**
     * @var \Shopsys\ShopBundle\Model\Pricing\Group\PricingGroupRepository
     */
    private $pricingGroupRepository;

    /**
     * @var \Shopsys\ShopBundle\Component\Domain\Domain
     */
    private $domain;

    /**
     * @var \Shopsys\ShopBundle\Component\Domain\AdminDomainTabsFacade
     */
    private $adminDomainTabsFacade;

    /**
     * @var \Shopsys\ShopBundle\Component\Setting\Setting
     */
    private $setting;

    public function __construct(
        PricingGroupRepository $pricingGroupRepository,
        Domain $domain,
        AdminDomainTabsFacade $adminDomainTabsFacade,
        Setting $setting
    ) {
        $this->pricingGroupRepository = $pricingGroupRepository;
        $this->domain = $domain;
        $this->adminDomainTabsFacade = $adminDomainTabsFacade;
        $this->setting = $setting;
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Pricing\Group\PricingGroup $pricingGroup
     * @return bool
     */
    public function isPricingGroupUsedOnSelectedDomain(PricingGroup $pricingGroup)
    {
        return $this->pricingGroupRepository->existsUserWithPricingGroup($pricingGroup)
            || $this->isPricingGroupDefaultOnSelectedDomain($pricingGroup);
    }

    /**
     * @param int $domainId
     * @return \Shopsys\ShopBundle\Model\Pricing\Group\PricingGroup
     */
    public function getDefaultPricingGroupByDomainId($domainId)
    {
        $defaultPricingGroupId = $this->setting->getForDomain(Setting::DEFAULT_PRICING_GROUP, $domainId);

        return $this->pricingGroupRepository->getById($defaultPricingGroupId);
    }

    /**
     * @return \Shopsys\ShopBundle\Model\Pricing\Group\PricingGroup
     */
    public function getDefaultPricingGroupByCurrentDomain()
    {
        return $this->getDefaultPricingGroupByDomainId($this->domain->getId());
    }

    /**
     * @return \Shopsys\ShopBundle\Model\Pricing\Group\PricingGroup
     */
    public function getDefaultPricingGroupBySelectedDomain()
    {
        return $this->getDefaultPricingGroupByDomainId($this->adminDomainTabsFacade->getSelectedDomainId());
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Pricing\Group\PricingGroup $pricingGroup
     */
    public function setDefaultPricingGroupForSelectedDomain(PricingGroup $pricingGroup)
    {
        $this->setting->setForDomain(Setting::DEFAULT_PRICING_GROUP, $pricingGroup->getId(), $this->adminDomainTabsFacade->getSelectedDomainId());
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Pricing\Group\PricingGroup $pricingGroup
     * @return bool
     */
    public function isPricingGroupDefaultOnSelectedDomain(PricingGroup $pricingGroup)
    {
        return $pricingGroup === $this->getDefaultPricingGroupBySelectedDomain();
    }
}