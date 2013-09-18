<?php

// class TestCase extends Illuminate\Foundation\Testing\TestCase
class TestCase extends Orchestra\Testbench\TestCase
{

    /**
     * Creates the application.
     *
     * @return Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function tearDown()
    {
        \Mockery::close();
    }

    protected function getPackageProviders()
    {
        return array( 'StriveJobs\\StriveJobsServiceProvider' );
    }

    protected function getPackageAliases()
    {
        return array( 'SJ' => 'StriveJobs\\StriveJobsFacade' );
    }

}
