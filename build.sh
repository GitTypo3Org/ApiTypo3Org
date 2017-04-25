#!/bin/bash

# TYPO3 CMS
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-typo3cms-master
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-typo3cms-6.2
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-typo3cms-7
cd /var/www/vhosts/api.typo3.org/home/APIBuilder; phing build-typo3cms-8
