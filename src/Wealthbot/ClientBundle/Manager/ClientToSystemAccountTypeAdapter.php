<?php
/**
 * Created by JetBrains PhpStorm.
 * User: amalyuhin
 * Date: 11.04.13
 * Time: 17:02
 * To change this template use File | Settings | File Templates.
 */

namespace Wealthbot\ClientBundle\Manager;


use Wealthbot\ClientBundle\Model\AccountGroup;
use Wealthbot\ClientBundle\Model\ClientAccount;
use Wealthbot\ClientBundle\Model\SystemAccount;

class ClientToSystemAccountTypeAdapter
{
    private $clientAccount;

    public function __construct(ClientAccount $clientAccount)
    {
        $this->clientAccount = $clientAccount;
    }

    /**
     * Get type of account
     *
     * @return int
     * @throws \Exception
     */
    public function getType()
    {
        $group = $this->clientAccount->getGroupName();
        $type = $this->clientAccount->getTypeName();

        if ($group === AccountGroup::GROUP_EMPLOYER_RETIREMENT) {
            $systemType = SystemAccount::TYPE_RETIREMENT;

        } elseif ($group === AccountGroup::GROUP_DEPOSIT_MONEY || $group === AccountGroup::GROUP_FINANCIAL_INSTITUTION) {
            $systemType = $this->getTypeOfDepositOrTransferAccount($type);

        } elseif ($group === AccountGroup::GROUP_OLD_EMPLOYER_RETIREMENT) {
            $systemType = $this->getTypeOfRolloverAccount($type);

        } else {
            throw new \Exception('Invalid client account group value: ' . $group);
        }

        return $systemType;
    }

    /**
     * Get type of account as string
     *
     * @return string
     */
    public function getTypeAsString()
    {
        $types = SystemAccount::getTypeChoices();
        $type = $this->getType();

        return $types[$type];
    }

    private function getTypeOfDepositOrTransferAccount($type)
    {
        $typesMap = array(
            'Personal Account' => SystemAccount::TYPE_PERSONAL_INVESTMENT,
            'Joint Account'    => SystemAccount::TYPE_JOINT_INVESTMENT,
            'Roth IRA'         => SystemAccount::TYPE_ROTH_IRA,
            'Traditional IRA'  => SystemAccount::TYPE_TRADITIONAL_IRA,
            'Rollover IRA'     => SystemAccount::TYPE_TRADITIONAL_IRA
        );

        if (!array_key_exists($type, $typesMap)) {
            throw new \InvalidArgumentException('Invalid type: ' . $type);
        }

        return $typesMap[$type];
    }

    private function getTypeOfRolloverAccount($type)
    {
        $typesMap = array(
            '401(k) Roth' => SystemAccount::TYPE_ROTH_IRA,
            '401(k)'      => SystemAccount::TYPE_TRADITIONAL_IRA,
            '403(b)'      => SystemAccount::TYPE_TRADITIONAL_IRA,
            'SEP IRA'     => SystemAccount::TYPE_TRADITIONAL_IRA,
            'SIMPLE IRA'  => SystemAccount::TYPE_TRADITIONAL_IRA
        );

        if (!array_key_exists($type, $typesMap)) {
            throw new \InvalidArgumentException('Invalid type: ' . $type);
        }

        return $typesMap[$type];
    }
}