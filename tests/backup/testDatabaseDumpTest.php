<?php

/**
 * Tests for the complete backup process both with
 * the shell commands and with the PHP fallbacks
 *
 * @extends WP_UnitTestCase
 */
class testDatabaseDumpTestCase extends HM_Backup_UnitTestCase {

	/**
	 * Contains the current backup instance
	 *
	 * @var object
	 * @access protected
	 */
	protected $backup;

	/**
	 * Setup the backup object and create the tmp directory
	 *
	 */
	public function setUp() {

		HM\BackUpWordPress\Path::get_instance()->set_path( dirname( __FILE__ ) . '/tmp' );

		$this->backup = new HM\BackUpWordPress\Backup();

		wp_mkdir_p( hmbkp_path() );

	}

	/**
	 * Cleanup the backup file and tmp directory
	 * after every test
	 *
	 */
	public function tearDown() {

		hmbkp_rmdirtree( hmbkp_path() );

		unset( $this->backup );

		HM\BackUpWordPress\Path::get_instance()->reset_path();

	}

	/**
	 * Test a database dump with the zip command
	 *
	 */
	public function testDatabaseDumpWithMysqldump() {

		if ( ! $this->backup->get_mysqldump_command_path() ) {
            $this->markTestSkipped( "Empty mysqldump command path" );
		}

		$this->backup->mysqldump();

		$this->assertFileExists( $this->backup->get_database_dump_filepath() );

	}

	/**
	 * Test a database dump with the PHP fallback
	 *
	 */
	public function testDatabaseDumpWithFallback() {

		$this->backup->mysqldump_fallback();

		$this->assertFileExists( $this->backup->get_database_dump_filepath() );

	}

}