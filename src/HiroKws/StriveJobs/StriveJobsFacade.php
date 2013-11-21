<?php

namespace StriveJobs;

use Illuminate\Support\Facades\Facade;

class StriveJobsFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'StriveJobs\\StriveJobs'; }

}