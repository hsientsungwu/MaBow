<?php

class ProgramTimeType {
	const DAILY = 1;
	const WEEKLY = 2;
	const MONTHLY = 3;

	static function getProgramTimeType() {
		return array(
			self::DAILY => '每天',
			self::WEEKLY => '每周',
			self::MONTHLY => '每月',
		);
	}
}