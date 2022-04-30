<?php

namespace ZnBundle\User\Yii2\Web\helpers;

use ZnCore\Base\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

class SecurityMenu extends BaseMenu implements MenuInterface {
	
	public function toArray() {
		$items = [
			[
				'url' => 'user/security/email',
				'label' => ['account/security', 'email'],
			],
			[
				'url' => 'user/security/password',
				'label' => ['account/security', 'password'],
			],
		];
		$items = $this->filter($items);
		return $items;
	}
}
