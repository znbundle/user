<?php

namespace ZnBundle\User\Yii2\Helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii\web\Request;
use ZnCore\Base\Enums\Http\HttpHeaderEnum;
use ZnBundle\User\Yii2\Entities\YiiIdentityEntity;
use ZnCore\Base\Helpers\DeprecateHelper;
use ZnCore\Base\Helpers\EnvHelper;

DeprecateHelper::hardThrow();

class AuthHelper {

	const KEY = 'authToken';
	
	public static function setToken($token) {
		$tokenDto = self::forgeDto($token);
		Registry::set(self::KEY, $tokenDto);
		self::setTokenToSession($tokenDto);
	}
	
	/**
	 * @return TokenDto|null
	 */
	public static function getTokenDto() {
		$tokenDto = Registry::get(self::KEY);
		if($tokenDto) {
			return $tokenDto;
		}
		$tokenDto = self::getTokenFromSession();
		if($tokenDto) {
			return $tokenDto;
		}
		$token = self::getTokenFromQuery();
		if($token) {
			$tokenDto = self::forgeDto($token);
			return $tokenDto;
		}
		return null;
	}
	/**
	 * @return String|null
	 */
	public static function getTokenString() {
		if(!empty(self::getTokenDto())){
			return self::getTokenDto()->getTokenString();
		}
		return null;
	}
	/**
	 * @return TokenDto
	 */
	public static function updateTokenViaSession() {
		if(Yii::$app->user->enableSession) {
			$tokenDto = AuthHelper::getTokenFromSession();
			AuthHelper::setToken($tokenDto);
		}
		return null;
	}
	
	public static function getTokenFromRequest(Request $request) {
		$token = null;
		if(isset(Yii::$app->request)) {
			$token = $request->headers->get(HttpHeaderEnum::AUTHORIZATION);
			if(!empty($token)) {
				return $token;
			}
			$token = $request->getQueryParam(strtolower(HttpHeaderEnum::AUTHORIZATION));
			if(!empty($token)) {
				return $token;
			}
			$token = $request->getQueryParam(HttpHeaderEnum::AUTHORIZATION);
			if(!empty($token)) {
				return $token;
			}
		}
		return null;
	}
	
	public static function getTokenFromQuery() {
		if(EnvHelper::isConsole()) {
			return null;
		}
		return self::getTokenFromRequest(Yii::$app->request);
	}
	
	public static function getTokenFromIdentity() {
		if(Yii::$app->user->getIsGuest()) {
			return null;
		}
		if(!Yii::$app->user->identity instanceof IdentityInterface) {
			return null;
		}
		$token = Yii::$app->user->identity->getAuthKey();
		if($token) {
			return $token;
		}
		return null;
	}
	
	private static function forgeDto($token) {
		if($token instanceof TokenDto) {
			return $token;
		}
		$tokenDto = null;
		if(is_string($token)) {
			$tokenDto = TokenHelper::forgeDtoFromToken($token);
		}
		if(is_array($token)) {
			$tokenDto = new TokenDto;
			$tokenDto->token = ArrayHelper::getValue($token, 'token');
			$tokenDto->type = ArrayHelper::getValue($token, 'type');
		}
		return $tokenDto;
	}
	
	/**
	 * @return TokenDto|null
	 */
	private static function getTokenFromSession() {
		if(Yii::$app->user->enableSession) {
			return Yii::$app->session->get(self::KEY);
		}
		return null;
	}
	
	private static function setTokenToSession(TokenDto $tokenDto = null) {
		if(Yii::$app->user->enableSession) {
			$tokenFromSession = self::getTokenFromSession();
			if ($tokenFromSession !== $tokenDto) {
				Yii::$app->session->set(self::KEY, $tokenDto);
			}
		}
	}
	
}
