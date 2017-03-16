<?php
/**
 * 
 * Class rewrite with error in code
 *
 * @author   Aleksa Zivkovic
 */
namespace Test\Registration\Model;

class Visitor extends \Magento\Customer\Model\Visitor
{


	/**
	 * Broken on purpose
	 * 
	 * Initialization visitor by request
	 *
	 * Used in event "controller_action_predispatch"
	 *
	 * @param   \Magento\Framework\Event\Observer $observer
	 * @return  \Magento\Customer\Model\Visitor
	 */
	public function initByRequest($observer)
	{ return '';
		if ($this->skipRequestLogging || $this->isModuleIgnored($observer)) {
			return $this;
		}
	
		if ($this->session->getVisitorData()) {
			$this->setData($this->session->getVisitorData());
		}
	
		$this->setLastVisitAt((new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT));
	
		if (!$this->getId()) {
			$this->setSessionId($this->session->getSessionId());
			$this->save();
			$this->_eventManager->dispatch('visitor_init', ['visitor' => $this]);
			$this->session->setVisitorData($this->getData());
		}
		return $this;
	}
}