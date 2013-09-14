<?php namespace StriveJobsCommands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ChangeStatus extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'sj:change';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Change job status.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		//
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('newStatus', InputArgument::REQUIRED, 'New status for specifed jobs'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('status', 's', InputOption::VALUE_IS_ARRAY, 'Target status', null),
			array('id', 'i', InputOption::VALUE_IS_ARRAY, 'An example option.', null),
			array('oldest', 'o', InputOption::VALUE_NONE, 'An example option.', null),
			array('latest', 'l', InputOption::VALUE_NONE, 'An example option.', null),
			array('execute', 'n', InputOption::VALUE_OPTIONAL, 'How many jobs will execute.', null),
			array('lessThan', 'lt', InputOption::VALUE_NONE, 'Execute for jobs less than specified ID.', null),
			array('lessThanEquale', 'le', InputOption::VALUE_NONE, 'Execute for jobs less than equale with specified ID.', null),
			array('greaterThan', 'gt', InputOption::VALUE_NONE, 'Execute for jobs greater than specified ID.', null),
			array('greaterThanEquale', 'ge', InputOption::VALUE_NONE, 'Execute for jobs greater than or equale with specified ID.', null),
		);
	}

}