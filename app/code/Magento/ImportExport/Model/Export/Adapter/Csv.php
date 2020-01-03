<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ImportExport\Model\Export\Adapter;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\File\Write;

/**
 * Export adapter csv.
 *
 * @api
 * @since 100.0.2
 */
class Csv extends AbstractAdapter
{
    /**
     * Field delimiter.
     *
     * @var string
     */
    protected $_delimiter = ',';

    /**
     * Field enclosure character.
     *
     * @var string
     */
    protected $_enclosure = '"';

    /**
     * Source file handler.
     *
     * @var Write
     */
    protected $_fileHandler;

    /**
     * @param Filesystem $filesystem
     * @param null $destination
     */
    public function __construct(Filesystem $filesystem, $destination = null)
    {
        // phpcs:ignore Magento2.Functions.DiscouragedFunction
        register_shutdown_function([$this, 'destruct']);
        parent::__construct($filesystem, $destination);
    }

    /**
     * Object destructor.
     *
     * @return void
     */
    public function destruct()
    {
        if (is_object($this->_fileHandler)) {
            $this->_fileHandler->close();
            $this->_directoryHandle->delete($this->_destination);
        }
    }

    /**
     * Method called as last step of object instance creation. Can be overridden in child classes.
     *
     * @return $this
     */
    protected function _init()
    {
        $this->_fileHandler = $this->_directoryHandle->openFile($this->_destination, 'w');
        return $this;
    }

    /**
     * MIME-type for 'Content-Type' header.
     *
     * @return string
     */
    public function getContentType()
    {
        return 'text/csv';
    }

    /**
     * Return file extension for downloading.
     *
     * @return string
     */
    public function getFileExtension()
    {
        return 'csv';
    }

    /**
     * Set column names.
     *
     * @param array $headerColumns
     * @throws \Exception
     * @return $this
     */
    public function setHeaderCols(array $headerColumns)
    {
        if (null !== $this->_headerCols) {
            throw new LocalizedException(__('The header column names are already set.'));
        }
        if ($headerColumns) {
            foreach ($headerColumns as $columnName) {
                $this->_headerCols[$columnName] = false;
            }
            $this->_fileHandler->writeCsv(array_keys($this->_headerCols), $this->_delimiter, $this->_enclosure);
        }
        return $this;
    }

    /**
     * Write row data to source file.
     *
     * @param array $rowData
     * @throws \Exception
     * @return $this
     */
    public function writeRow(array $rowData)
    {
        if (null === $this->_headerCols) {
            $this->setHeaderCols(array_keys($rowData));
        }
        $this->_fileHandler->writeCsv(
            array_merge($this->_headerCols, array_intersect_key($rowData, $this->_headerCols)),
            $this->_delimiter,
            $this->_enclosure
        );
        return $this;
    }
}
