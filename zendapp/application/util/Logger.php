<?php

class Logger
{
	// The static logger object.
	protected static $logger = null;

    /**
     * Retrieve the singleton logger instance, creating it if necessary.
	 *
	 * @return Returns the logger.
     */
    public static function getLogger()
    {
		// Make sure the logger has been created.
		if (self::$logger === null) {
			// Define the logging format.
			$format = '%timestamp% %priorityName% %message%' . PHP_EOL;

			// Create the formatter.
			$formatter = new Zend_Log_Formatter_Simple($format);

			// Create the new writer for the log file.
			$writer = new Zend_Log_Writer_Stream(
					Bootstrap::$root . '/logs/app.log');

			// Set the writer format.
			$writer->setFormatter($formatter);

			// Create the logger.
			self::$logger = new Zend_Log($writer);
		}

		// Return the logger.
		return self::$logger;
	}
}

