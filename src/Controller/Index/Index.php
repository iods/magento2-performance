<?php
/**
 * Description of a module goes here for Magento 2
 *
 * @package   Iods_Bones
 * @author    Rye Miller <rye@drkstr.dev>
 * @copyright Copyright © 2021, Rye Miller (https://ryemiller.io)
 * @license   MIT (https://en.wikipedia.org/wiki/MIT_License)
 */
declare(strict_types=1);

namespace Iods\Bones\Controller\Index;

use Magento\Framework\App\Action\{Action, Context};
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /** @var PageFactory $_pageFactory */
	protected PageFactory $_pageFactory;

  /**
   * @param Context $context
   * @param PageFactory $pageFactory
   */
	public function __construct(Context $context, PageFactory $pageFactory)
	{
		$this->_pageFactory = $pageFactory;
		return parent::__construct($context);
	}

  /**
   * Proof of concept.
   */
	public function execute()
	{
	    // return $this->_pageFactory->create();
			echo "Dump the bones.";
			exit;
	}
}
