<?php

/**
 * @file
 *          This file is part of the PdfParser library.
 *
 * @author  Sébastien MALOT <sebastien@malot.fr>
 * @date    2017-01-03
 * @license LGPLv3
 * @url     <https://github.com/Noxxie/pdfparser>
 *
 *  PdfParser is a pdf library written in PHP, extraction oriented.
 *  Copyright (C) 2017 - Sébastien MALOT <sebastien@malot.fr>
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Lesser General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Lesser General Public License for more details.
 *
 *  You should have received a copy of the GNU Lesser General Public License
 *  along with this program.
 *  If not, see <http://www.pdfparser.org/sites/default/LICENSE.txt>.
 *
 */

namespace Noxxie\PdfParser;

/**
 * Class Pages
 *
 * @package Noxxie\PdfParser
 */
class Pages extends PDFObject
{
    /**
     * @param bool $deep
     *
     * @return array
     */
    public function getPages($deep = false)
    {
        if ($this->has('Kids')) {

            if (!$deep) {
                return $this->get('Kids')->getContent();
            } else {
                $kids  = $this->get('Kids')->getContent();
                $pages = array();

                foreach ($kids as $kid) {

                    if ($kid instanceof Pages) {
                        $pages = array_merge($pages, $kid->getPages(true));
                    } else {
                        $pages[] = $kid;
                    }
                }

                return $pages;
            }
        }

        return array();
    }
}
