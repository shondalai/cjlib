<?php

/**
 * Base Class for all stream ciphers
 *
 * PHP version 5
 *
 * @author    Jim Wigginton <terrafrost@php.net>
 * @author    Hans-Juergen Petrich <petrich@tronic-media.com>
 * @copyright 2007 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 */

namespace phpseclib3\Crypt\Common;

/**
 * Base Class for all stream cipher classes
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class StreamCipher extends SymmetricKey
{
    /**
     * Block Length of the cipher
     *
     * Stream ciphers do not have a block size
     *
     * @var int
     * @see SymmetricKey::block_size
     */
    protected $block_size = 0;

    /**
     * Default Constructor.
     *
     * @return StreamCipher
     *@see \phpseclib3\Crypt\Common\SymmetricKey::__construct()
     */
    public function __construct()
    {
        parent::__construct('stream');
    }

    /**
     * Stream ciphers not use an IV
     *
     * @return bool
     */
    public function usesIV()
    {
        return false;
    }
}
